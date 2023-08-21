<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\S3bucketController;
use App\Http\Repositories\Frontend\CategoriesApis;
use App\Http\Repositories\Frontend\ProductApis;
use App\Http\Repositories\Frontend\UsersApis;
use Carbon\Carbon;
use Auth;
use Hash;
use Mail;
use DB;

class UsersController extends Controller
{   

    /**
     * @var PagesRepository;
     */
    private $categoriesApis;
    private $productApis;
    private $usersApis;
    private $s3bucketController;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CategoriesApis $categoriesApis, ProductApis $productApis, UsersApis $usersApis, S3bucketController $s3bucketController)
    {
        $this->s3bucketController = $s3bucketController;
        $this->categoriesApis = $categoriesApis;
        $this->productApis = $productApis;
        $this->usersApis = $usersApis;
        
        $this->middleware('auth');
    }


    //User account page
    public function show()
    {     
        $user = $this->usersApis->show(Auth::user()->id);
        $addresses = $this->usersApis->showAddresses(Auth::user()->id);
        $countries = $this->usersApis->getAllCountries(); 
        $wishlist = $this->usersApis->showWishlist(Auth::user()->id);
        $ordersList = $this->usersApis->showOrdersList(Auth::user()->id);
        
        return view('frontend.users.user-account', array('user' => $user, 'addresses' => $addresses, 'countries' => $countries, 'wishlist' => $wishlist, 'ordersList' => $ordersList));
    }





    //Allows to add user addres
    public function addAddress(Request $request)
    {
        // Adding an address to user
        $this->usersApis->addAddress($request, Auth::user()->id); 
        return redirect()->back();
    }
    

    //Allows to get address info from address id
    public function getAddressFromAddressId(Request $request)
    {
        // get address info
        $data = $this->usersApis->getAddrInfo($request); 
        // return the info to the ajax call
        return response()->json($data);
    }


    //Allows to update user address
    public function updateAddress(Request $request)
    {
        // Adding an address to user
        $this->usersApis->updateAddress($request); 
        return redirect()->back();
    }


    //Allows to delete a user address
    public function deleteAddress(Request $request)
    {
        // Deleting a user address
        $this->usersApis->deleteAddress($request); 
        // return the info to the ajax call
        return response()->json();
    }





    //Update user information
    public function updateInfo(Request $request)
    {
        // If the form is filled
        if ($request->filled(['user_id']))
        {            
            $info = $this->usersApis->show($request->input('user_id'));

            // process the image, compress and resize to create original img and thumb img. Return the image name  
            $imageFileName = $this->s3bucketController->fillInputWithImageForEdit($request, 'image', 'users', 'users/thumbs', '950', NULL, '100', '100', $info[0]->img);

            // Saving the user edits
            $this->usersApis->updateUserInfo($request,$imageFileName);
        }

        return redirect()->back();
    }

     //Update user password
    public function updatePassword(Request $request)
    {  
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }
        
        if($request->get('new-password') != $request->get('new-password_confirmation')){
            //New password and confirmation password are same
            return redirect()->back()->with("error","Confirmation Password is not the same as New Password. Please write it again.");
        }
        
        if(strlen($request->get('new-password')) < 6){
            //New password is too small
            return redirect()->back()->with("error","The New Password chosen is too small, it must contain at least 6 characters. Please choose a different password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success","Password changed successfully !");
    }



    

    //Allows to delete a product from wishlist
    public function deleteFromWishlist(Request $request)
    {
        // Deleting a product from wishlist
        $this->usersApis->deleteFromWishlist($request); 
        // return the info to the ajax call
        return response()->json();
    }





    //User orders List page
    public function showOrderStatus($order_id)
    {   
        //Returns the details of specific order
        $order_details = $this->usersApis->userOrderDetails($order_id);

        //Returns the list of Order Items
        $order_items = $this->usersApis->userOrderItems($order_id);

        return view('frontend.users.user-order-status', array('order_details' => $order_details, 'order_items' => $order_items, 'order_items' => $order_items));
    }
           

}
