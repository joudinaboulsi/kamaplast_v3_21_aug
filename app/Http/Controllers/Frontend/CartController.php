<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\Frontend\ShoppingCartApis;
use App\Http\Repositories\Frontend\ProductApis;
use App\Http\Repositories\Frontend\UsersApis;
use App\Http\Controllers\Frontend\PromoController;
use Gloudemans\Shoppingcart\Cart;
use Auth;


class CartController extends Controller
{
    public function __construct(ShoppingCartAPis $shoppingCartApis, ProductApis $productApis, Cart $cart, UsersApis $usersApis, PromoController $promoController)
    {
        $this->shoppingCartApis = $shoppingCartApis;
        $this->productApis = $productApis;
        $this->cart = $cart;
        $this->usersApis = $usersApis;
        $this->promoController = $promoController;
    }

    // function that get all the items and the removed items if they exist
    public function getCartItems()
    {
        $items =[];
        $removed_from_cart = false;

       //if the user is online  
       if(Auth::check()) 
       {    
          // if I have items in my offline cart
          if($this->cart->content()->count() > 0)
             $this->shoppingCartApis->transferItemsToOnlineCart($this->cart->content()); // Transfer the items from the offline to the online cart
          

          $removed_from_cart = $this->shoppingCartApis->cleanCartFromExpiredItems(Auth::user()->id); // clean the cart from the deleted/out of stock products  
          $items = $this->shoppingCartApis->getCartItemsFromUserId(Auth::user()->id); // show the online cart from the database  
       }
     
       else 
       {
          $content = $this->cart->content(); //get the content of the offline cart  
 
          if($this->cart->content()->count() > 0)
          { 
              $variants = ''; // define an empty string 
              // loop the offline cart and get from it a string of variant_ids
              foreach($content as $c)
                  $variants = $variants.$c->id.', ';
              
              $variants = rtrim($variants, ', '); // remove the last coma from the string 
   
              $items = $this->shoppingCartApis->getCartItemsFromVariant($variants); //get all the details of the cart items except for the rowId and quantity


              foreach($content as $c)
              {   
                  $key = array_search($c->id, array_column($items, 'variant_id')); // search in the array of my query for the key that contains the iterated variant_id
                  $items[$key]->cart_item_id = $c->rowId; // add rowId to my query result
                  $items[$key]->quantity = $c->qty; // add quantity to my query result
              }

              $removed_from_cart = [];

              // loop all the items to clean the cart from the deleted/out of stock products
             foreach($items as $key => $i)
             {

                if( ($i->product_hidden != 0) || 
                    ($i->variant_hidden != 0) ||
                    ($i->enable_stock_mgmt == 1 && $i->quantity > $i->stock_qty) ||
                    ($i->enable_stock_mgmt == 0 && $i->stock_status_id == 0) 
                  )
                {
                 
                  // convert the iterated product_name into an object
                  $product_name = (object) ['product_name' => $i->product_name];

                  // push product name in the array of remove_products
                  array_push($removed_from_cart, $product_name);             
           
                  // remove variant from the offline cart content
                  $this->cart->remove($i->cart_item_id);
                  // remove the variant from the array
                  unset($items[$key]);

                }  
             }

             // reset empty array to false if it is still empty
             if(empty($removed_from_cart))
              $removed_from_cart = false;

            }      

       } // end else

       $data['items'] = $items;
       $data['removed_from_cart'] = $removed_from_cart;

       return $data;
    }


    // function that get the subtotal of your cart
    public function getSubtotal($cart_data)
    {
      $subtotal = 0;

      foreach($cart_data as $c)
        $subtotal = $subtotal + ($c->quantity * $c->current_price);

      return $subtotal;
    }


    // function that calculate the shipping of your cart
    public function getShippingFees($subtotal, $cart_items)
    {
        // if shipping fees are constant 
        if(config('global.CONSTANT_SHIPPING_FEES') == 1)
        {
          if($subtotal >= config('global.DELIVERY_THRESHOLD') || $subtotal == 0) // if we reach of passed the threshold, FREE SHIPPING
            return 0;
          else
            return config('global.DELIVERY_PRICE'); // return the constant delivery price if the threshold is not reached
        }

        else
        {
          return 0;
          //=======================
          // RETURN THE API PRICE
        }
    }


    // get VAT value
    public function getVat($subtotal)
    {
      $vat = $subtotal * config('global.VAT') / 100;

      return $vat;
    }


    // get the discount value of the promo
    public function getPromoDiscount($promo_code, $user_id, $subtotal)
    {   
      // test the UML of the promotion
      $response  = $this->promoController->applyPromoFunction($promo_code, $user_id, $subtotal);
  
      // if promo is valid 
      if($response['success'] == true)
      {
        if($response['promo_type'] == 'percentage')
          $discount_value = $subtotal * $response['value']/100;        
  
        elseif($response['promo_type'] == 'amount')
          $discount_value = $response['value'];

        $discount['status'] = true;
        $discount['promo_type'] = $response['promo_type'];
        $discount['promo_value'] = $response['value'];
      }

      // No valid promo
      else
      {
        $discount_value = 0;
        $discount['status'] = false;
        $discount['msg'] = $response['msg'];
      } 

      $discount['value'] = $discount_value;
      $discount['promo_code'] = $promo_code;

      return $discount;
    }

    // clean the trailing zeros after decimal number
    public static function cleanNum($nbr)
    {
      return strpos($nbr,'.')!==false ? rtrim(rtrim($nbr,'0'),'.') : $nbr;

      return $num;
    }


    // get the final total of the cart
    public function getAllTotals($cart_items, $promo_code)
    {
      $subtotal = $this->getSubtotal($cart_items);
      
      $shipping_fees = $this->getShippingFees($subtotal, $cart_items);

      $subtotal_without_vat = $subtotal + $shipping_fees;

      $vat = $this->getVat($subtotal);
      

      if(Auth::check())
      {
        $discount = $this->getPromoDiscount($promo_code, Auth::user()->id, $subtotal);
        $data['discount_value'] = $this->cleanNum(number_format($discount['value'],2,".","'"));
        $data['discount_status'] = $discount['status'];
        $data['promo_code'] = $discount['promo_code'];

        if($discount['status'] == true) //promo exist
        {
          $data['discount_response_msg'] = 'Valid promo code';
          $data['promo_type'] = $discount['promo_type'];
          $data['promo_value'] = $discount['value'];
        }
        else
        {
          $data['discount_response_msg'] = $discount['msg'];
          $data['promo_type'] = NULL;
          $data['promo_value'] = NULL;
        }
      }
      else // offline
      {
        $data['discount_value'] = 0;
        $data['discount_status'] = false;
        $data['promo_code'] = NULL;
        $data['promo_type'] = NULL;
        $data['promo_value'] = NULL;
        $discount['value'] = 0;
      }

      $total_without_discount = $subtotal + $shipping_fees +  $vat;

      $total = $total_without_discount - $discount['value'];
      

      // totals with trailing zero and many digit after the .
      $data['subtotal_float'] = $subtotal;
      $data['shipping_fees_float'] = $shipping_fees;
      $data['subtotal_without_vat_float'] = $subtotal_without_vat;
      $data['vat_float'] = $vat; 
      $data['total_without_discount_float'] = $total_without_discount; 
      $data['discount_value_float'] = $discount['value'];
      $data['total_float'] = $total;  


      $data['subtotal'] = $this->cleanNum(number_format($subtotal,2,".","'"));
      $data['shipping_fees'] =  $this->cleanNum(number_format($shipping_fees,2,".","'"));
      $data['subtotal_without_vat'] =  $this->cleanNum(number_format($subtotal_without_vat,2,".","'"));
      $data['vat'] =   $this->cleanNum(number_format($vat,2,".","'")); 
      $data['total_without_discount'] =  $this->cleanNum(number_format($total_without_discount,2,".","'")); 
      $data['total'] =  $this->cleanNum(number_format($total,2,".","'"));  


      return $data;
    }


    // return the totals of the current cart in JSON repsponse
    public function getCartTotalsAjax()
    {
      $cart_data = $this->getCartItems(); // get the cart items + info

      $data = $this->getAllTotals($cart_data['items'], NULL); // get the totals 

      return response()->json($data);
    }



    //Shows the products in the cart
    public function show()
    {// dd('test');
      // call the getCartItems function and display the retruned data
     $cart_data = $this->getCartItems();  
   
     $totals =  $this->getAllTotals($cart_data['items'], NULL);

      // Returns the cart 
    	return view('frontend.cart.index', array('items' =>$cart_data['items'], 'removed_from_cart' => $cart_data['removed_from_cart'], 'totals' => $totals));
    }


    // function that return the rowId from variant_id in the offline gloudemans cart
    public function cartSearch($variant_id)
    {
        $item = $this->cart->search(function ($cart, $key) use($variant_id) {
               return $cart->id == $variant_id;
            })->first();

        return $item;
    }


    // function that add /edit items to cart in offline or online mode
    public function onlineOfflineActions($product_info, $qty, $action)
    {
       //if the user is online  
       if(Auth::check()) 
       {
            // if I am adding an item
            if($action == 'add')
                $this->shoppingCartApis->insertVariantInCart($product_info[0]->variant_id, $qty); //insert variant in the DATABASE cart
            // if I am editing an item
            else if($action =='edit')
                $this->shoppingCartApis->updateVariantInCart($product_info[0]->variant_id, $qty); //update variant in the DATABASE cart
       }
       
       // user if offline 
       else 
       {
            // get the rowId of the adding/editing variant
            $item = $this->cartSearch($product_info[0]->variant_id);

            // if I am adding an item
            if($action == 'add')
            {
               // remove the added variant if it already exist 
               if(!empty($item))
                  $this->cart->remove($item->rowId);
               // add the variant in the SESSION cart 
               $this->cart->add($product_info[0]->variant_id, $product_info[0]->name, $qty, 0, ['product_id' => $product_info[0]->product_id]);
            }
            // if I am editing an item
            else if($action =='edit')
                 $this->cart->update($item->rowId, $qty); //update the qty of the selected item
       }  
    }



    //add the variant to the cart
    public function addEditCart(Request $request)
    {   
        $variant_id = $request->input('variant_id');
        $qty = $request->input('qty');
        $action = $request->input('action');

        //get the product and variant info from variant id 
        $product_info = $this->productApis->getInfosFromVariantId($variant_id);

        // check if the product that I am trying to update is still existing
        if(!empty($product_info))
        {    
            if($product_info[0]->enable_stock_mgmt == 1) // If we are managing the stock number
            {
                if($product_info[0]->stock_qty > 0 && $qty <= $product_info[0]->stock_qty) // if I have enough stock 
                    $this->onlineOfflineActions($product_info, $qty, $action); // ADD/EDIT VARIANT - call the function that add/edit the variant online OR offline
                else
                    return response()->json(array('title' => 'Not enough stock', 'msg' => 'The quantity of product you are trying to add doesn\'t exist in our stock !'));
            }

            else // No stock management 
            {
              if($product_info[0]->stock_status_id == 1) // product IN stock 
                    $this->onlineOfflineActions($product_info, $qty, $action); // ADD/EDIT VARIANT - call the function that add/edit the variant online OR offline
              else
                    return response()->json(array('title' => 'Out of stock', 'msg' => 'The product you are trying to add is out of stock !'));
            }
        }

        else
             return response()->json(array('title' => 'Product is not available', 'msg' => 'This product doesn\'t exist anymore!'));    

    }


    //delete an item from the cart - ONLINE
    public function deleteCartItem(Request $request)
    {   


        $this->shoppingCartApis->deleteCartItem($request->input('id')); // delete the cart item from the database
    }


    //delete an item from the session cart - OFFLINE
    public function deleteCartItemOffline(Request $request)
    {   
        $this->cart->remove($request->input('id'));
    }
         
}
