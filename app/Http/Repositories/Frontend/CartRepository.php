<?php 

namespace App\Http\Repositories\Frontend;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\QueryException;

class CartRepository {

    /*----------------------------------
    Check the deleted variants, products, out of stock products and remove them from the Cart
    ------------------------------------*/
    public function cleanCartFromExpiredItems($user_id)
    {
        try
        {
           \DB::select("DELETE FROM cart_items
                        WHERE user_id = :user_id
                        AND variant_id IN  (
                         SELECT implicitTemp.variant_id FROM 
                         (SELECT A.variant_id 
                            FROM cart_items as A
                            JOIN products as B ON A.product_id = B.product_id
                            JOIN variants as C ON A.variant_id = C.variant_id
                            WHERE B.hidden = 1
                            OR C.hidden = 1
                            OR (B.enable_stock_mgmt = 0 AND C.stock_status_id = 0)
                            OR (B.enable_stock_mgmt = 1 AND A.quantity > C.stock_qty)
                         ) implicitTemp
                        )",
                        array(':user_id' => $user_id)
                        );
       }
       catch(QueryException $ex)
       {
          dd('Error while adding cleaning expired products');
       }

    }


    /*----------------------------------
    get the cart items from the user id
    ------------------------------------*/
    public function getCartItemsFromUserId($user_id)
    {

      // get all the items in the cart with the final current price   
      $q = \DB::select("SELECT A.*, B.img, B.sku, B.regular_price, B.sales_price, B.sales_price_start_date, B.sales_price_end_date, B.stock_qty, B.stock_status_id, B.weight, B.width, B.length, B.height, C.enable_stock_mgmt, C.name as product_name, E.img as product_img, D.attr_items, IF(B.sales_price_start_date <= NOW() AND B.sales_price_end_date >= NOW(), B.sales_price, B.regular_price) as current_price
                        FROM cart_items as A
                        JOIN variants as B ON A.variant_id = B.variant_id AND B.hidden =0
                        LEFT JOIN products as C ON A.product_id = C.product_id AND C.hidden =0
                        LEFT JOIN product_images as E ON A.product_id = E.product_id AND E.is_primary = 1 AND E.hidden = 0
                        JOIN (

                            SELECT A.variant_id, group_concat(C.name) as attr_items
                            FROM cart_items as A
                            LEFT JOIN variants_has_attribute_items as B ON A.variant_id = B.variant_id
                            LEFT JOIN attribute_items as C ON B.attribute_item_id = C.attribute_item_id
                            WHERE A.user_id = :user_id
                            AND A.hidden = 0
                            GROUP BY A.variant_id
                            
                        ) AS D ON A.variant_id = D.variant_id

                        WHERE A.user_id = :user_id_2
                        AND A.hidden = 0",
                        array(':user_id' => $user_id,
                              ':user_id_2' => $user_id)
                        );
        return $q;
    }


   // insert a new item in the cart
   public function insertVariantInCart($variant_id, $qty)
   {
    try{
           \DB::transaction(function () use ($variant_id, $qty) {

                // DELETE the item I am trying to add if it already exists in the cart
                \DB::select("DELETE 
                             FROM cart_items
                             WHERE variant_id = :variant_id
                             AND user_id = :user_id",
                    array(':variant_id' => $variant_id,
                          ':user_id' => Auth::user()->id)
                );


                // get the product ID of the variant I am trying to add
                $p = \DB::select("SELECT product_id   
                                  FROM variants
                                  WHERE variant_id = :variant_id
                                  AND hidden = 0",
                                  array(":variant_id" => $variant_id)
                                );
        

                // INSERT the variant in the cart
                \DB::table('cart_items')->insert(
                     array('user_id' => Auth::user()->id,
                           'product_id' => $p[0]->product_id,
                           'variant_id' => $variant_id,
                           'quantity' =>  $qty,
                           'hidden' => 0,
                           'created_by' => Auth::user()->id,
                           'updated_by' => Auth::user()->id,
                           'created_at' => Carbon::now('Asia/Beirut'),
                           'updated_at' => Carbon::now('Asia/Beirut'))
                 );

           });
       }

       catch(QueryException $ex)
       {
          dd('Error while adding to cart');
       }
        
   }

   // update the quantity of an item in the cart
   public function updateVariantInCart($variant_id, $qty)
   {

     \DB::table('cart_items')
           ->where('variant_id', '=', $variant_id)
           ->where('user_id', '=', Auth::user()->id)
           ->update(
               array('quantity' => $qty,
                     'updated_by' => Auth::user()->id,
                     'updated_at' => Carbon::now('Asia/Beirut'))
           ); 
   }



    /*----------------------------------
    Count the number of items for a specific user in the cart
    ------------------------------------*/
    public function cartCount($variant_id)
    {
       $q = \DB::select("SELECT COUNT(DISTINCT variant_id) as cart_count
                         FROM cart_items 
                         WHERE user_id = :user_id",
            array(':user_id' => Auth::user()->id)
            );

       return $q[0]->cart_count;
    } 





   /*----------------------------------
    Delete the selected item from the cart
    ------------------------------------*/
    public function deleteCartItem($cart_item_id)
    {
        \DB::table('cart_items')
                ->where('cart_item_id', '=', $cart_item_id)
                ->delete();
    } 



    /*----------------------------------------
    Delete the cart of the selected user
    -----------------------------------------*/
    public function deleteCartOfUser($user_id)
    {
        \DB::table('cart_items')
                ->where('user_id', '=', $user_id)
                ->delete();
    } 

    
   
}