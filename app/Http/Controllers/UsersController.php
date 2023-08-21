<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\UsersRepository;
use App\Http\Controllers\S3bucketController;
use Auth;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(S3bucketController $s3bucketController,UsersRepository $usersRepository)
    {
        $this->UsersRepository = $usersRepository;
        $this->S3bucketController = $s3bucketController;
        $this->middleware('auth:admin');
    }

    
    //Shows the list of users
    public function index()
    {
        // Returns the Abandonned Checkouts Number
        return view('cms/users/index');
    }

    //Shows the list of users
    public function loadUsersTable()
    {
        // Returns the list of Users
        $users = $this->UsersRepository->show();
        return datatables($users)->make(true);
    }

    //Add a new user Page
    public function addUserPage()
    {
        return view('cms/users/create-user');
    }


    //Add a new user
    public function add(Request $request)
    {
        //Adding a user
        $user_id = $this->UsersRepository->add($request); 
        return redirect('user-details-'.$user_id);
    }


    //Shows details of user 
    public function showDetails($user_id)
    {
        //Returns the details of specific user
        $user_details = $this->UsersRepository->showDetails($user_id);

        //Returns the addresses of specific user
        $userAddresses = $this->UsersRepository->getUserAddresses($user_id);

        //Returns the List of user's orders
        $ordersList = $this->UsersRepository->getUserOrders($user_id);

        //Returns the List of user's orders
        $userTags = $this->UsersRepository->getUserTags($user_id);

        //Returns the List of user's tags
        $tagsList = $this->UsersRepository->getTagsList($user_id);

        // get the list of all the countries
        $countries = $this->UsersRepository->getAllCountries();

        return view('cms/users/user-details', array('user_details' => $user_details, 'userAddresses' => $userAddresses, 'ordersList' => $ordersList, 'userTags' => $userTags, 'tagsList' => $tagsList, 'countries' => $countries));
    }


    //Allows to update a user info 
    public function updateUserInfo(Request $request)
    {
        //Updating a user info
        $this->UsersRepository->updateUserInfo($request); 
           
        return redirect('user-details-'.$request->input('user_id'));
   }


    //Allows to add tag to user
    public function addTag(Request $request)
    {
        // Adding a tag to user
        $this->UsersRepository->AddTag($request); 
        return redirect()->back();
    }


    //Allows to delete linked tag
    public function deleteTag(Request $request)
    {
        // Deleting a user tag
        $this->UsersRepository->DeleteTag($request); 
        // return the info to the ajax call
        return response()->json();
    }


    //Allows to get address info from address id
    public function getAddressFromAddressId(Request $request)
    {
        // get address info
        $data = $this->UsersRepository->getAddrInfo($request); 
        // return the info to the ajax call
        return response()->json($data);
    }


    //Allows to add address to user
    public function addAddress(Request $request)
    {
        // Adding an address to user
        $this->UsersRepository->addAddress($request); 
        return redirect()->back();
    }


    //Allows to update address to user
    public function updateAddress(Request $request)
    {
        // Adding an address to user
        $this->UsersRepository->updateAddress($request); 
        return redirect()->back();
    }


    //Allows to delete a user address
    public function deleteAddress(Request $request)
    {
        // Deleting a user address
        $this->UsersRepository->deleteAddress($request); 
        // return the info to the ajax call
        return response()->json();
    }


    // Allows to publish/unpublish a user
    public function publish(Request $request)
    { 
        //publish/unpublish this user
        $publish = $this->UsersRepository->publish($request);
        // return the info to the ajax call
       return response()->json($publish);  
    }


    //Allows to delete a user
    public function delete(Request $request)
    {
        // Deleting a user
        $this->UsersRepository->delete($request); 
        // return the info to the ajax call
        return response()->json();
    }






}
