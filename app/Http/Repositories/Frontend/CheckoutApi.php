<?php 

namespace App\Http\Repositories\Frontend;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\QueryException;

class CheckoutApi {

    /*----------------------------------
    Update a user address
    ------------------------------------*/
    public function updateAddress($request)
    {
      $q = \DB::table('addresses')
           ->where('address_id', '=', $request->input('stored_address_id'))
           ->update(
              array( 'name' => $request->input('fullname'),
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


    /*---------------------------------------
    Get the country name from the country_id
    -----------------------------------------*/
    public function getCountryFromId($country_id)
    {
        $q = \DB::select("SELECT title
                         FROM countries
                         WHERE country_id = :country_id",
                    array(':country_id' => $country_id)
                    );
        return $q[0]->title;
    }


    /*----------------------------------
    Insert an order
    ------------------------------------*/
    public function insertOrder($request, $cart_data, $shipping_fees, $promo_type, $promo_value, $subtotal, $total)
    {
        \DB::transaction(function () use ($request, $cart_data, $shipping_fees, $promo_type, $promo_value, $subtotal, $total)
        {  
          // check if the user is authenticated or not and fill the related info
          if(Auth::check())
          {
            $user_id = Auth::user()->id;

            // get the user info
            $user = \DB::select("SELECT *
                                FROM users
                                WHERE id = :user_id",
                 array(':user_id' => $user_id));

            $name = $user[0]->name;
            $email = $user[0]->email;
            $birth_date = $user[0]->birth_date;
            $phone = $user[0]->phone;
            $gender = $user[0]->gender;
          }

          else
          {
            $user_id = NULL;
            $name = NULL;
            $email = $request->input('email');
            $birth_date = NULL;
            $phone = NULL;
            $gender = NULL;
          }

          // get the payment method name
          $pm = \DB::select("SELECT *
                             FROM payment_methods
                             WHERE payment_method_id = :payment_method_id",
                    array(':payment_method_id' => $request->input('payment_method'))
                    );


          // get the country name if shipping address
          $shipping_country = \DB::select("SELECT *
                                FROM countries
                                WHERE country_id = :country_id",
                    array(':country_id' => session('country'))
                    );

          // if billing country session exist
          if(session('billing_country'))
          {
            // get the country name if billing address
            $c = \DB::select("SELECT *
                                FROM countries
                                WHERE country_id = :country_id",
                    array(':country_id' => session('billing_country'))
                    );

            $billing_country = $c[0]->title;
          }

          else
            $billing_country = NULL;


          // check if a promo is applied and give its discount value to the correspanding field
          if($promo_type !== NULL)
          {
            if($promo_type == 'amount')
            {
               $discount_value = $promo_value;
               $discount_percentage_value = NULL;
            }
            elseif($promo_type == 'percentage')
            {
               $discount_value = NULL;
               $discount_percentage_value = $promo_value;
            }
          }

          else // no promo_applied
          {
            $discount_value = NULL;
            $discount_percentage_value = NULL;
          }

           // insert order
           $order_id =  \DB::table('orders')->insertGetId(
               array('order_status_id' => 1,
                     'delivery_status_id' => 1,
                     'payment_method_id' => $request->input('payment_method'),
                     'payment_method' => $pm[0]->name,
                     'user_id' => $user_id,
                     'name' => $name,
                     'email' => $email,
                     'birth_date' => $birth_date,
                     'phone' => $phone,
                     'gender' => $gender,
                     'vat' => config('global.VAT'),
                     'delivery_time' => session('delivery_time'),
                     'delivery_date' => session('delivery_date'),
                     'notes' => nl2br(session('notes')),
                     'shipping_fees' => $shipping_fees,
                     'shipping_name' => session('fullname'),
                     'shipping_country' => $shipping_country[0]->title,
                     'shipping_city' => session('city'),
                     'shipping_address' => session('address'),
                     'shipping_company' => session('company'),
                     'shipping_apartment' => session('apartment'),
                     'shipping_postal_code' => session('postal_code'),
                     'shipping_phone' => session('address_phone'),

                     'billing_name' => session('billing_fullname'),
                     'billing_country' => $billing_country,
                     'billing_city' => session('billing_city'),
                     'billing_address' => session('billing_address'),
                     'billing_company' => session('billing_company'),
                     'billing_apartment' => session('billing_apartment'),
                     'billing_postal_code' => session('billing_postal_code'),
                     'billing_phone' => session('billing_address_phone'),

                     'discount_value' => $discount_value,
                     'discount_percentage_value' => $discount_percentage_value,
                     'subtotal' => $subtotal,
                     'total' => $total,
                     'hidden' => 0,
                     'created_at' => Carbon::now('Asia/Beirut'), 
                     'created_by' => Auth::id(),
                     'updated_at' => Carbon::now('Asia/Beirut'),
                     'updated_by' => Auth::id()
                )
          );  

          $order_items = []; //declare an empty array 

          foreach($cart_data as $c)
          {
            if($c->current_price != $c->regular_price) // item is under discount => store the sales price
              $sales_price = $c->sales_price;
            else
              $sales_price = NULL; // no discount on item => keep the sales price NULL

            // build the order_items table
            array_push($order_items, array('order_id' => $order_id,
                                           'product_id' => $c->product_id,
                                           'product_name' => $c->product_name, 
                                           'product_img' => $c->product_img,
                                           'variant_id' => $c->variant_id,
                                           'sku' => $c->sku,
                                           'regular_price' => $c->regular_price,
                                           'sales_price' => $sales_price,
                                           'quantity' => $c->quantity,
                                           'total_price' => ($c->current_price * $c->quantity),
                                           'weight' => $c->weight,
                                           'width' => $c->width,
                                           'length' => $c->length,
                                           'height' => $c->height,
                                           'hidden' => 0,
                                           'created_by' => Auth::id(),
                                           'updated_by' => Auth::id(),
                                           'created_at' => Carbon::now('Asia/Beirut'),
                                           'updated_at' => Carbon::now('Asia/Beirut')
                                      ));

              // if I am managing the stock number of this product                           
              if($c->enable_stock_mgmt == 1)
              {
                  // update the stock quantity by substracting the right bought quantity
                  \DB::select("UPDATE variants
                               SET stock_qty = stock_qty - :qty
                               WHERE variant_id = :variant_id",
                      array(':qty' => $c->quantity,
                            ':variant_id' => $c->variant_id
                            )
                      );
              }

           }


           // check if a promo is applied and insert the promo_id as used for the user
          if($promo_type !== NULL)
          {
            // insert used promo code
            \DB::select("INSERT INTO promo_codes_used_by_users
                         SELECT promo_code_id, :user_id, :order_id, 0, :created_at, :created_by, :updated_at, :updated_by
                         FROM promo_codes WHERE name ='".session('promo_code')."'",
                    array(':user_id' => Auth::user()->id,
                          ':order_id' => $order_id,
                          ':created_at' => Carbon::now('Asia/Beirut'),
                          ':created_by' => Auth::user()->id,
                          ':updated_at' => Carbon::now('Asia/Beirut'),
                          ':updated_by' => Auth::user()->id,
                         )
                    );
          }



          // insert all the order_items
          \DB::table('order_items')->insert($order_items); // Query Builder

          session(['order_id' => $order_id]);

        });
     
        $order_id = session('order_id');
        session()->forget('order_id');  

        return $order_id;
    }





   
}