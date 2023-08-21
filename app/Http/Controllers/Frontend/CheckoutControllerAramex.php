<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\Frontend\UsersApis;
use App\Http\Repositories\Frontend\CheckoutApis;
use App\Http\Repositories\Frontend\CartRepository;
use App\Http\Controllers\Frontend\CartControllers;
use App\Http\Controllers\Frontend\PromoController;
use App\Http\Controllers\Frontend\AramexController;
use Gloudemans\Shoppingcart\Cart;
use Auth;
use Mail;
use Carbon\Carbon;


class CheckoutControllerAramex extends Controller
{
    public function __construct(CartController $cartController, Cart $cart, UsersApis $usersApis, CheckoutApis $checkoutApis, PromoController $promoController, CartRepository $cartRepository, AramexController $aramexController)
    {
        $this->cartController = $cartController;
        $this->cart = $cart;
        $this->usersApis = $usersApis;
        $this->checkoutApis = $checkoutApis;
        $this->promoController = $promoController;
        $this->cartRepository = $cartRepository;
        $this->aramexController = $aramexController;
    }



   	public function showCheckout()
    {
      // call the getCartItems function and display the retruned data
      $cart_data = $this->cartController->getCartItems();  

      if($cart_data['items'] == NULL)
        return redirect('cart');

      else
      {
        //get the addresses of the user if he is authenticated 
        if(Auth::check())
          $addresses = $this->usersApis->showAddresses(Auth::user()->id);
        else
          $addresses = NULL;

        // call aramex API to get the list of cities 
        $aramex_cities = $this->aramexController->getAramexCities();

        $countries = $this->usersApis->getAllCountries();

        $totals =  $this->cartController->getAllTotals($cart_data['items'], session('promo_code'));
     
        return view('frontend.checkout.checkout', array('items' =>$cart_data['items'], 'removed_from_cart' => $cart_data['removed_from_cart'], 'addresses' => $addresses, 'aramex_cities' => $aramex_cities, 'countries' => $countries, 'totals' => $totals));
      }
    }






    public function saveShippingDelivery(Request $request)
    {
        // if the user is authenticated
        if(Auth::check())
        {
            // the user is adding a new address
            if($request->input('stored_address_id') == 0)
            {
              // insert new address in the DB
              $this->usersApis->addAddress($request, Auth::user()->id); 


              // ========= A =========
              // Save all the address inputs in session variables
              $this->storeAddressInSession($request);
            }

            // the user is using an existing address
            else
            { 
              // edit existing address in the BD
              $this->checkoutApis->updateAddress($request); 


              // ========= A =========
              // Save all the address inputs in session variables
              $this->storeAddressInSession($request);
            }
        }

        // user is NOT authenticated
        else 
        {
          // ========= A =========
          // Save all the address inputs in session variables
          $this->storeAddressInSession($request);

        }

        //    dd($request->all());
        return redirect('payment');
    }  




  // ========= A =========
  // Save all the address inputs in session variables

  public function storeAddressInSession($request)
  {   
      if(Auth::check())
        session(['email' => Auth::user()->email]);
      else
        session(['email' => $request->input('email')]);

      // Shipping address
      session(['stored_address_id' => $request->input('stored_address_id')]);
      session(['fullname' => $request->input('fullname')]);
      session(['company' => $request->input('company')]);
      session(['address' => $request->input('address')]);
      session(['apartment' => $request->input('apartment')]);
      session(['country' => $request->input('country')]);
      session(['city' => $request->input('city')]);
      session(['address_phone' => $request->input('address_phone')]);
      session(['postal_code' => $request->input('postal_code')]);
      
      // Billing address
      if($request->input('billing_flag') !== NULL ) // if billing address flag is check => store the info in the billing session
      {
        session(['billing_flag' => $request->input('billing_flag')]); 
        session(['billing_fullname' => $request->input('billing_fullname')]);
        session(['billing_company' => $request->input('billing_company')]);
        session(['billing_address' => $request->input('billing_address')]);
        session(['billing_apartment' => $request->input('billing_apartment')]);
        session(['billing_country' => $request->input('billing_country')]);
        session(['billing_city' => $request->input('billing_city')]);
        session(['billing_address_phone' => $request->input('billing_address_phone')]);
        session(['billing_postal_code' => $request->input('billing_postal_code')]);
      }
       else // the billing addres flag is unchecked => Remove billing sessions
      {
        session()->forget('billing_flag');
        session()->forget('billing_fullname');
        session()->forget('billing_company');
        session()->forget('billing_address');
        session()->forget('billing_apartment');
        session()->forget('billing_country');
        session()->forget('billing_city');
        session()->forget('billing_address_phone');
        session()->forget('billing_postal_code');
      }
       // Delivery timing
      session(['delivery_time' => $request->input('delivery_time')]);
      session(['delivery_date' => $request->input('delivery_date')]);
      session(['notes' => $request->input('notes')]);
  }


   // clear the promo code
   public function clearPromoCode()
   {
        // call the getCartItems function and display the retruned data
        $cart_data = $this->cartController->getCartItems();

         if(Auth::check())
         $auth_user_id = Auth::user()->id;
        else
         $auth_user_id = NULL;

        // clear all the stored session of the promo_code
        session()->forget('promo_code');  
        session()->forget('promo_type');  
        session()->forget('value');  
        session()->forget('discount');

        $totals =  $this->cartController->getAllTotals($cart_data['items'], session('promo_code'));

        return view('frontend.checkout.includes.promo-total', array('items' =>$cart_data['items'], 'removed_from_cart' => $cart_data['removed_from_cart'], 'auth_user_id' => $auth_user_id, 'totals' => $totals));
   }



  // show all the payment methods
  public function showPaymentMethods(Request $request)
  { 
   // call the getCartItems function and display the retruned data
    $cart_data = $this->cartController->getCartItems();

     // if the cart is empty => redirect to cart  
     if($cart_data['items'] == NULL)
        return redirect('cart');

      // if the checkout address is not filled, redirect to checkout
     elseif($request->session()->has('address') == false)
     {
        return redirect('checkout');
     }

     // else redirect to payment
     else
    {
      // get the all the totals of my cart
      $totals =  $this->cartController->getAllTotals($cart_data['items'], session('promo_code'));

      // declare the array below as initial state (false and empty). Used later for cart change status on payment
      $warning_popup['status'] = false; 
    
      return view('frontend.checkout.payment', array('items' =>$cart_data['items'], 'removed_from_cart' => $cart_data['removed_from_cart'], 'totals' => $totals, 'warning_popup' => $warning_popup));
    }
  }


   // pay after selecting the payment methods
  public function pay(Request $request)
  {  
      // call the getCartItems function and display the retruned data
      $cart_data = $this->cartController->getCartItems();  
      // get the all the totals of my cart
      $totals =  $this->cartController->getAllTotals($cart_data['items'], session('promo_code'));

      if($cart_data['removed_from_cart'] != false && $cart_data['removed_from_cart'] != null )
      {
          $products = '';
          foreach($cart_data['removed_from_cart'] as $c)
          {
            $products = $products.' <br/>-'.$c->product_name;
          }

          $warning_popup['status'] = true; // set warning, items were removed from the cart because they were unpublished or stock under qty 
          $warning_popup['title'] = 'Products removed from cart';
          $warning_popup['text'] = 'The following products were removed from your cart because someone bought them before: '.$products. ' <br/> Do you wish to proceed with the new payment amount or check the updated cart before ?';
      }

         
      // if promo code exist but the promo code is not valid anymore 
      elseif($totals['promo_code'] !== NULL && $totals['discount_status'] == false)
      {
          $warning_popup['status'] = true; // set warning, promo code is not valid anymore
          $warning_popup['title'] = 'Promo code error';
          $warning_popup['text'] = $totals['discount_response_msg']. ' <br/><br/> Do you wish to proceed with the new payment amount or check the updated cart before ?';
      }


      else // nothing changed in the cart 
        $warning_popup['status'] = false;
      
 
      // if the cart status changes (product/stock no longer available or invalid promo)
      if($warning_popup['status'] == true) 
      {
        //save the state of the payment_method radio button
        session(['payment_method' => $request->input('payment_method')]);

        return view('frontend.checkout.payment', array('items' =>$cart_data['items'], 'removed_from_cart' => $cart_data['removed_from_cart'], 'totals' => $totals, 'warning_popup' => $warning_popup));
      }
        
      else
      {
        // if payment method is Credit Card
        if($request->input('payment_method') == 1) 
        {
          dd('online payment');
          // gateway payment

          // get response
          // if response is success 
                // ====== A ========= Insert order + send email
          // if respinse is failed
                // ====== B ========= send email on purchase

        }
        // if payment method is Cash On Delivery
        elseif($request->input('payment_method') == 2) 
        {
           // create aramex shipment
           $aramex_response = $this->aramexController->createAramexShipment($cart_data);

           // aramex Api returned an error
          if($aramex_response['status'] == 'error')
          {
             \Session::flash('msg', $aramex_response['msg']);

             return redirect('payment');
          }

          // if aramex API didn't return any error
          else if($aramex_response['status'] == 'success')
          {

          // ====== A ========= Insert order + send email
          $order_id = $this->insertOrder($request, $totals, $cart_data, $aramex_response);

          // return the payment response view
          return view('frontend.checkout.payment-response', array('items' =>$cart_data['items'], 'removed_from_cart' => $cart_data['removed_from_cart'], 'totals' => $totals, 'order_id' => $order_id));

          }
        }

      }
  }
 


  // ====== A ========= Insert order + send email
  public function insertOrder($request, $totals, $cart_data, $aramex_response)
  {
    // if the session variables exist    
    if(session('email') !== NULL && session('address') !== NULL  && session('country') !== NULL)  
    {     
      // insert the order in the DB 
      $order_id = $this->checkoutApis->insertOrder($request, $cart_data['items'], $totals['shipping_fees_float'], $totals['promo_type'], $totals['promo_value'], $totals['subtotal_float'], $totals['total_float'], $aramex_response);

      // ====== B ========= send email on purchase
      $this->sendPurchaseEmail($cart_data, $totals, $order_id, $aramex_response); 

      // ====== C ========= Empty Cart
      $this->emptyCart();

      return $order_id;
    }


    else // session variables expired
    {
      \Session::flash('msg', 'Your session has expired ! Please try again.');

      return redirect('/cart');
    }

  }



  // ====== B ========= send email on purchase
  public function sendPurchaseEmail($cart_data, $totals, $order_id, $aramex_response)
  {
    $purchase['date'] = Carbon::now(config('global.TIMEZONE'))->format("d M Y");
    $purchase['time'] = Carbon::now(config('global.TIMEZONE'))->format("h:i a");
    $shipping_country = $this->checkoutApis->getCountryFromId(session('country')); // get the name of the country - shipping address
    
    if(session('billing_country'))
      $billing_country = $this->checkoutApis->getCountryFromId(session('billing_country')); // get the name of the country - billing address
    else
      $billing_country = NULL;

    // send email to client
    Mail::send('emails.cash-on-delivery-client-email', array('cart_data' => $cart_data, 'totals' => $totals, 'order_id' => $order_id, 'purchase' => $purchase, 'shipping_country' => $shipping_country, 'billing_country' => $billing_country, 'aramex_response' => $aramex_response), function($message)
    {
        $message->from(config('global.INFO_MAIL'), config('global.COMPANY_NAME'));
        $message->to(session('email'))->subject(config('global.COMPANY_NAME').' | Online order');
    });

 
    // send email to admin
    Mail::send('emails.cash-on-delivery-admin-email', array('cart_data' => $cart_data, 'totals' => $totals, 'order_id' => $order_id, 'purchase' => $purchase, 'shipping_country' => $shipping_country, 'billing_country' => $billing_country, 'client_email' => session('email'), 'aramex_response' => $aramex_response), function($message)
    {
        $message->from('no-reply@'.config('global.COMPANY_DOMAIN'), config('global.COMPANY_NAME'));
        $message->to(config('global.INFO_MAIL'))->subject('Online order');
    });

  }



  // ====== C ========= Empty Cart
  public function emptyCart()
  {
    // if the user is authenticated
    if(Auth::check())
      $this->cartRepository->deleteCartOfUser(Auth::user()->id); //delete all the items from the cart of the authenticated user
    else
      $this->cart->destroy(); //destroy the cart session of the unAuthenticated user

  }



 // function that return true of false if a device is mobile/tablet or not
 public static function isMobileDevice() 
 {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
 }


}