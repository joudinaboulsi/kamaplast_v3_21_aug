<?php 

namespace App\Http\Repositories\Frontend;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\QueryException;

class UsersApis {

	/*----------------------------------
    Show a Specific User Details
    ------------------------------------*/
    public function show($user_id)
    {
        $q = \DB::select("SELECT *   
                          FROM users
                          WHERE id=:user_id",
             array(':user_id' => $user_id));

        return $q;
    }


    /*----------------------------------
    get all the countries
    ------------------------------------*/
    public function getAllCountries()
    {
        $q = \DB::select("SELECT *
                          FROM countries
                          WHERE hidden = 0
                          ORDER BY title ASC");
        return $q;
    }


    /*----------------------------------
    Edit the user information (frontend)
    ------------------------------------*/
    public function updateUserInfo($request, $file_name)
    {
        //if subscribe to newsletters checkbox is checked
        if($request->input('has_newsletter')==1)
          $newsletters = 1;
        else
          $newsletters = 0;
        
         \DB::table('users')
           ->where('id', '=', $request->input('user_id'))
           ->update(
              array('name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'img' => $file_name,
                    'birth_date' => $request->input('birth_date'),
                    'gender' => $request->input('gender'),
                    'has_newsletters' => $newsletters,
                     )
           ); 
    }



    /*----------------------------------
    Show a Specific User Addresses
    ------------------------------------*/
    public function showAddresses($user_id)
    {
        $q = \DB::select("SELECT A.*, B.title as country_name   
                          FROM addresses as A
                          LEFT JOIN countries as B ON A.country_id = B.country_id
                          WHERE user_id=:user_id",
             array(':user_id' => $user_id));

        return $q;
    }


    /*----------------------------------
    Add address to the User
    ------------------------------------*/
    public function addAddress($request, $user_id)
    {
        $q = \DB::table('addresses')->insert(
                 array('user_id' => $user_id,
                       'name' => $request->input('fullname'),
                       'company' => $request->input('company'),
                       'address' => $request->input('address'),
                       'apartment' => $request->input('apartment'),
                       'country_id' => $request->input('country'),
                       'postal_code' => $request->input('postal_code'),
                       'city' => $request->input('city'),
                       'phone' => $request->input('address_phone'),
                       'hidden' => 0,
                       'created_at' => Carbon::now('Asia/Beirut'),
                       'created_by' => Auth::id(),
                       'updated_at' => Carbon::now('Asia/Beirut'),
                       'updated_by' => Auth::id()
                  )
                    );

        return $q;
    }


   /*----------------------------------
    Get the address details of a specific address
    ------------------------------------*/
    public function getAddrInfo($request)
    {
         $p = \DB::select("SELECT * 
                          FROM addresses
                          WHERE address_id=:address_id",
                  array(':address_id' => $request->input('address_id'))
              );
        return $p;
    }
    

    /*----------------------------------
    Update a user address
    ------------------------------------*/
    public function updateAddress($request)
    {
      $q = \DB::table('addresses')
           ->where('address_id', '=', $request->input('address_id_edit'))
           ->update(
              array('name' => $request->input('fullname_edit'),
                   'company' => $request->input('company_edit'),
                   'address' => $request->input('address_edit'),
                   'apartment' => $request->input('apartment_edit'),
                   'country_id' => $request->input('country_edit'),
                   'postal_code' => $request->input('postal_code_edit'),
                   'city' => $request->input('city_edit'),
                   'phone' => $request->input('address_phone_edit')
                    )
           );

        return $q;
    }


    /*----------------------------------
    Delete a user address
    ------------------------------------*/
    public function deleteAddress($request)
    {
        \DB::select("DELETE 
                    FROM addresses
                    WHERE address_id = :address_id",
            array(':address_id' => $request->input('id')
                  )
            );
    }


    /*----------------------------------
    Shows wishlist of UserupdateAddress
    ------------------------------------*/
    public function showWishlist($user_id)
    {
        $q =\DB::select("SELECT A.*, B.*, C.img, D.regular_price, D.sales_price, D.sales_price_start_date, D.sales_price_end_date
                    FROM wishlist_items as A
                    LEFT JOIN products as B ON A.product_id = B.product_id
                    LEFT JOIN product_images as C ON B.product_id = C.product_id and C.is_primary=1
                    LEFT JOIN variants as D on B.product_id = D.product_id and D.is_main=1
                    WHERE A.user_id = :user_id",
            array(':user_id' => $user_id)
        );

        return $q;
    }


    /*----------------------------------
    Delete product from wishlist 
    ------------------------------------*/
    public function deleteFromWishlist($request)
    {
        \DB::select("DELETE 
                    FROM wishlist_items
                    WHERE wishlist_item_id = :wishlist_item_id",
            array(':wishlist_item_id' => $request->input('id'))
            );
    }


    /*----------------------------------
    User subscribes to Newsletters
    ------------------------------------*/
    public function subscribeNewsletters($id)
    {

    }


    /*----------------------------------
    Shows the list of user Orders
    ------------------------------------*/
    public function showOrdersList($user_id)
    {
        $q = \DB::select("SELECT A.*, SUM(B.quantity) as quantity, C.name as payment_status, C.color_code as payment_status_color, D.name as shipping_status, D.color_code as shipping_status_color
                          FROM orders as A
                          LEFT JOIN order_items as B on A.order_id = B.order_id
                          JOIN orders_statuses as C on A.order_status_id = C.order_status_id
                          JOIN delivery_status as D on A.delivery_status_id = D.delivery_status_id
                          WHERE A.user_id = :user_id
                          GROUP BY A.order_id",
            array(':user_id' => $user_id)
        );

        return $q;
    }



    /*----------------------------------
    Shows the details of each Order for a user 
    ------------------------------------*/
    public function userOrderDetails($order_id)
    {
        $q = \DB::select("SELECT A.*, B.name as payment_status, B.color_code as payment_status_color, C.name as shipping_status, C.color_code as shipping_status_color, D.promo_code_id, E.name as promo_name, E.discount_percentage, F.name as user_name
                          FROM orders as A
                          JOIN orders_statuses as B ON A.order_status_id = B.order_status_id
                          JOIN delivery_status as C ON A.delivery_status_id = C.delivery_status_id
                          LEFT JOIN promo_codes_used_by_users as D ON A.order_id = D.order_id
                          LEFT JOIN promo_codes as E ON D.promo_code_id = E.promo_code_id
                          JOIN users as F ON A.user_id = F.id
                          WHERE A.order_id=:order_id",
                  array(':order_id' => $order_id)
              );
        return $q;
    }


    /*----------------------------------
    Shows the items of an Order
    ------------------------------------*/
    public function userOrderItems($order_id)
    {
         $q = \DB::select("SELECT A.order_status_id, B.* 
                          FROM orders as A
                          LEFT JOIN order_items as B ON A.order_id=B.order_id 
                          WHERE A.order_id=:order_id",
                  array(':order_id' => $order_id)
              );
        return $q;
    }



   
}