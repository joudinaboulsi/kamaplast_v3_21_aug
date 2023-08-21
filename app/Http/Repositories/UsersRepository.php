<?php 

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\QueryException;

class UsersRepository {

    /*----------------------------------
    get the list of all Users
    ------------------------------------*/
    public function show()
    {
        $p = \DB::select("SELECT A.*, B.abandonned_checkout, C.amount_spent
                          FROM 
                          
                          (SELECT A.id, A.name, A.email, A.has_newsletters, count(C.order_id) as number_of_orders, A.created_at as since, A.hidden
                          FROM users as A
                          LEFT JOIN orders as C on A.id = C.user_id AND C.order_status_id IN (2,3)
                          GROUP BY A.id) as A
                          
                          LEFT JOIN
                          
                          (SELECT A.id, count(B.order_id) as abandonned_checkout 
                          FROM users as A 
                          LEFT JOIN orders as B on A.id = B.user_id AND B.order_status_id = 8
                          GROUP BY A.id) as B ON A.id = B.id

                          LEFT JOIN

                          (SELECT A.id, SUM(C.regular_price) as amount_spent
                            FROM users as A 
                            LEFT JOIN orders as B on A.id = B.user_id
                            LEFT JOIN order_items as C ON B.order_id = C.order_id
                            GROUP BY A.id) as C ON A.id = C.id
                          ");
        return $p;
    }


    /*----------------------------------
    Insert a new User
    ------------------------------------*/
    public function add($request)
    {
       
       try
      {
        \DB::transaction(function () use ($request){


        if($request->input('newsletters')==1)
          $newsletters = 1;
        else
          $newsletters = 0;

        // Returns User id after insterting a User in users table
        $user_id = \DB::table('users')->insertGetId(
                     array('name' => $request->input('fullname'),
                     'email' => $request->input('email'),
                     'phone' => $request->input('phone'),
                     'has_newsletters' => $newsletters,
                     'hidden' => 0,
                     'created_at' => Carbon::now('Asia/Beirut'),
                     'created_by' => Auth::id(),
                     'updated_at' => Carbon::now('Asia/Beirut'),
                     'updated_by' => Auth::id()
                    )
                  ); 

                  \DB::table('addresses')->insert(
                     array('user_id' => $user_id,
                     'name' => $request->input('address_fullname'),
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

        $request->session()->put('user_id', $user_id);

          });

      return $request->session()->pull('user_id');
      }

      catch(QueryException $ex)
      { 
         dd($ex->getMessage());
         //dd('Error while adding a Prodcut');
      }   
      
    }


    /*----------------------------------
    Get the details of a specific User
    ------------------------------------*/
    public function showDetails($user_id)
    {
         $p = \DB::select("SELECT *
                          FROM users
                          WHERE id=:user_id",
                  array(':user_id' => $user_id)
              );
        return $p;
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
    Get the addresses of a specific User
    ------------------------------------*/
    public function getUserAddresses($user_id)
    {
         $p = \DB::select("SELECT A.*, B.title as country_name 
                           FROM addresses as A
                           LEFT JOIN countries as B ON A.country_id = B.country_id
                           WHERE user_id=:user_id",
                  array(':user_id' => $user_id)
              );
        return $p;
    }


    /*----------------------------------
    Get the orders list of a specific User
    ------------------------------------*/
    public function getUserOrders($user_id)
    {
         $p = \DB::select("SELECT A.*, SUM(B.quantity) as quantity, C.name as order_status, C.color_code as payment_status_color, D.name as delivery_status, D.color_code as shipping_status_color, IF(A.user_id IS NOT NULL, E.name, A.shipping_name) as customer
                           FROM orders as A
                           LEFT JOIN order_items as B ON A.order_id = B.order_id
                           JOIN orders_statuses as C ON A.order_status_id = C.order_status_id
                           JOIN delivery_status as D ON A.delivery_status_id = D.delivery_status_id
                           LEFT JOIN users as E ON A.user_id = E.id
                           WHERE A.user_id = :user_id
                           GROUP BY A.order_id",
                  array(':user_id' => $user_id));

        return $p;
    }


    /*----------------------------------
    Get tags linked to a User
    ------------------------------------*/
    public function getUserTags($user_id)
    {
         $p = \DB::select("SELECT A.user_id, A.tag_id, B.name as tag_name
                          FROM users_has_tags as A
                          JOIN tags as B ON A.tag_id = B.tag_id
                          WHERE A.user_id=:user_id",
                  array(':user_id' => $user_id)
              );
        return $p;
    }



    /*----------------------------------
    Update a User infos
    ------------------------------------*/
    public function updateUserInfo($request)
    {
       if($request->input('newsletters')==1)
          $newsletters = 1;
        else
          $newsletters = 0;

        // updating a promo in promo table
        \DB::table('users')
           ->where('id', '=', $request->input('user_id'))
           ->update(
              array('name' => $request->input('fullname'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'notes' => $request->input('notes'),
                    'has_newsletters' => $newsletters,
                     )
           ); 

    }


    /*----------------------------------
    get all the not used tags
    ------------------------------------*/
    public function getTagsList($user_id)
    {
      $q = \DB::select("SELECT tag_id, name
                        FROM tags 
                        WHERE type_id = 2 AND tag_id NOT IN (
                          SELECT tag_id 
                          FROM users_has_tags 
                          WHERE user_id = :user_id
                        )
                        AND hidden = 0",
                array(':user_id' => $user_id));

        return $q;
            
    }


    /*----------------------------------
    Add tag to user
    ------------------------------------*/
    public function addTag($request)
    {
      $q = \DB::table('users_has_tags')->insert(
                 array('user_id' => $request->input('edit_user_id'),
                 'tag_id' => $request->input('tag'),
                  )
                    );

        return $q;
    }


    /*----------------------------------
    Unlink a user tags
    ------------------------------------*/
    public function deleteTag($request)
    {
        \DB::select("DELETE 
                    FROM users_has_tags 
                    WHERE tag_id = :tag_id
                    AND user_id = :user_id",
            array(':tag_id' => $request->input('tag_id'),
                  ':user_id' => $request->input('user_id')
                  )
            );
    }


    /*----------------------------------
    Get the address details of a specific address
    ------------------------------------*/
    public function getAddrInfo($request)
    {
         $p = \DB::select("SELECT A.*, B.title as country_name
                          FROM addresses as A
                          LEFT JOIN countries as B ON A.country_id = B.country_id
                          WHERE address_id=:address_id",
                  array(':address_id' => $request->input('address_id'))
              );
        return $p;
    }


    /*----------------------------------
    Add address to user
    ------------------------------------*/
    public function addAddress($request)
    {
      $q = \DB::table('addresses')->insert( 
                 array('user_id' => $request->input('user_id'),
                       'name' => $request->input('address_fullname'),
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
    Update a user address
    ------------------------------------*/
    public function updateAddress($request)
    {
      $q = \DB::table('addresses')
           ->where('address_id', '=', $request->input('address_id_edit'))
           ->update(
              array('name' => $request->input('address_fullname_edit'),
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
    Publish/Unpublish a user
    ------------------------------------*/
    public function publish($request)
    {
      // Check if the product is published or not
      $hidden = \DB::select("SELECT hidden 
                                FROM users
                                WHERE id = :id",
                        array(':id' => $request->input('id')));

      if($hidden[0]->hidden==1) //product is unpublished
        $publish=0; //publish
      elseif($hidden[0]->hidden==0) //product is published
        $publish=1; //unpublish

      \DB::table('users')
         ->where('id', '=', $request->input('id'))
         ->update(
             array('hidden' => $publish)
         ); 

      return $publish;   
    }


    /*----------------------------------
    Delete a User
    ------------------------------------*/
    public function delete($request)
    {
      try
        {
          \DB::transaction(function () use ($request){

            \DB::select("DELETE 
                        FROM addresses
                        WHERE user_id = :id",
                array(':id' => $request->input('id')
                      )
                );

            \DB::select("DELETE 
                        FROM users_has_tags
                        WHERE user_id = :id",
                array(':id' => $request->input('id')
                      )
                );

           \DB::select("DELETE 
                        FROM users
                        WHERE id = :id",
                array(':id' => $request->input('id')
                      )
                );

             });

        }

        catch(QueryException $ex)
        { 
           dd($ex->getMessage());
           //dd('Error while deleting a Prodcut');
        }   

    }



   
}