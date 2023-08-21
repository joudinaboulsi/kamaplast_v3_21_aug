<?php 

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\QueryException;

class OrdersRepository {

    /*----------------------------------
    get the list of all Orders
    ------------------------------------*/
    public function show()
    {
        $p = \DB::select("SELECT A.*, SUM(B.quantity) as quantity, C.name as payment_status, C.color_code as payment_status_color, D.name as shipping_status, D.color_code as shipping_status_color, IF(A.user_id IS NOT NULL, E.name, A.shipping_name) as customer
                          FROM orders as A
                          LEFT JOIN order_items as B ON A.order_id = B.order_id
                          JOIN orders_statuses as C ON A.order_status_id = C.order_status_id
                          JOIN delivery_status as D ON A.delivery_status_id = D.delivery_status_id
                          LEFT JOIN users as E ON A.user_id = E.id
                          GROUP BY A.order_id");

        return $p;
    }


    /*----------------------------------
    Get the details of a specific Order
    ------------------------------------*/
    public function showDetails($order_id)
    {
         $p = \DB::select("SELECT A.*, B.name as payment_status, B.color_code as payment_status_color, C.name as shipping_status, C.color_code as shipping_status_color, IF(A.user_id IS NOT NULL, E.name, A.shipping_name) as customer, F.promo_code_id, G.name as promo_name, G.discount_percentage
                          FROM orders as A
                          JOIN orders_statuses as B on A.order_status_id = B.order_status_id
                          JOIN delivery_status as C on A.delivery_status_id = C.delivery_status_id
                          LEFT JOIN users as E ON A.user_id = E.id
                          LEFT JOIN promo_codes_used_by_users as F ON A.order_id = F.order_id
                          LEFT JOIN promo_codes as G ON F.promo_code_id = G.promo_code_id
                          WHERE A.order_id=:order_id",
                  array(':order_id' => $order_id)
              ); 
        return $p;
    }

    /*----------------------------------
    Get Payment Statuses List 
    ------------------------------------*/
    public function getPaymentStatuses()
    {
         $p = \DB::select("SELECT * 
                          FROM orders_statuses"
              );
        return $p;
    }


    /*----------------------------------
    Get Shipping Statuses List 
    ------------------------------------*/
    public function getShippingStatuses()
    {
         $p = \DB::select("SELECT * 
                          FROM delivery_status"
              );
        return $p;
    }


    /*----------------------------------
    Get Order Items List
    ------------------------------------*/
    public function getOrderItems($order_id)
    {
        $p = \DB::select("SELECT A.order_status_id, B.*, C.attr_items
                          FROM orders as A
                          LEFT JOIN order_items as B ON A.order_id=B.order_id 
                          LEFT JOIN 
                            (
                              SELECT A.order_item_id, group_concat(C.name SEPARATOR ', ') as attr_items
                              FROM order_items as A
                              LEFT JOIN variants_has_attribute_items as B ON A.variant_id = B.variant_id
                              LEFT JOIN attribute_items as C ON B.attribute_item_id = C.attribute_item_id
                              WHERE A.order_id = :order_id
                              GROUP BY A.order_item_id
                            ) 
                            as C ON B.order_item_id = C.order_item_id
                          WHERE A.order_id=:order_id_2 AND A.order_status_id  ",
                  array(':order_id' => $order_id,
                        ':order_id_2' => $order_id)
              );
        return $p;
    }


    /*----------------------------------
    Get addresses List of order user
    ------------------------------------*/
    public function getAddrList($order_id)
    {
          //get user id of the order
         $u = \DB::select("SELECT user_id
                          FROM orders 
                          WHERE order_id=:order_id",
                  array(':order_id' => $order_id)
              );

         //get user addresses
        $p = \DB::select("SELECT address_id, address
                          FROM addresses 
                          WHERE user_id=:user_id",
                  array(':user_id' => $u[0]->user_id)
              );

         return $p;

    }


  
    /*----------------------------------
    Update Shipping Address of an order
    ------------------------------------*/
    public function updateShippingAddress($request)
    {
        //get selected address info from addresses
         $u = \DB::select("SELECT *
                          FROM addresses 
                          WHERE address_id=:address_id",
                  array(':address_id' => $request->input('shipping_address_edit'))
              );

        //update shipping address
        \DB::table('orders')
           ->where('order_id', '=', $request->input('order_id'))
           ->update(
              array('shipping_name' => $u[0]->name,
                    'shipping_country' => $u[0]->country_id,
                    'shipping_city' => $u[0]->city,
                    'shipping_address' => $u[0]->address,
                    'shipping_apartment' => $u[0]->apartment,
                    'shipping_postal_code' => $u[0]->postal_code,
                    'shipping_phone' => $u[0]->phone,
                    )
           ); 

    }


    /*----------------------------------
    Update order payment and delivery statuses
    ------------------------------------*/
    public function updateOrder($request)
    { 

        $tracking_code = $request->input('tracking_code');

        \DB::table('orders')
           ->where('order_id', '=', $request->input('order_id'))
           ->update(
              array('order_status_id' => $request->input('payment_status'),
                    'delivery_status_id' => $request->input('ship_status'),
                    'tracking_code' => $request->input('tracking_code'),
                    'notes' => $request->input('notes')
                    )
           ); 

    }

   
}