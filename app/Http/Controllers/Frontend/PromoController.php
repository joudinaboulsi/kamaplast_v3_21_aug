<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\Frontend\ProductApis;
use App\Http\Repositories\Frontend\UsersApis;
use App\Http\Repositories\Frontend\PromoRepository;
use Auth;
use Carbon\Carbon;
use Session;

class PromoController extends Controller
{
    public function __construct(ProductApis $productApis, UsersApis $usersApis, PromoRepository $promoRepository)
    {
        $this->productApis = $productApis;
        $this->usersApis = $usersApis;
        $this->promoRepository = $promoRepository;
    }

    // call the applyPromoFunction
    public function applyPromo(Request $request)
    {   
      $response = $this->applyPromoFunction($request->input('promo_code'), $request->input('user_id'), $request->input('subtotal'));

      return response()->json($response);
    }


     // function that apply the promo on the cart or return an error message
    public function applyPromoFunction($promo_code, $user_id, $subtotal)
    {   
        session()->forget('promo_type');
        session()->forget('value');
        session()->forget('promo_code');

     
        //if the user is autehnticated  
       if(Auth::check()) 
       {    
            $response =[]; // define an empty array for the response
            // return the details of the selected promo
            $promo_details = $this->promoRepository->checkPromoCodeValidity($promo_code);

            // if promo exist
            if(!empty($promo_details))
            {
                $actual_date = Carbon::today('Asia/Beirut')->toDateString();
                // check if promo is expired or not
                if($promo_details[0]->start_date <= $actual_date && $promo_details[0]->end_date >= $actual_date)
                {
                    //if the promo have a minimum purchased amount 
                    if($promo_details[0]->min_purchase_amount !== NULL)
                    {
                        // if the subtotal is greater or equal to the minimum purchased amount 
                        if($subtotal >= $promo_details[0]->min_purchase_amount)
                        {
                            //========= A ==========
                            // LIMIT NUMBER OF USE RPOMO - check the scenarios of the promo code if it has a limit number of use or no
                            $response = $this->limitNbOfUsePromo($promo_code, $user_id, $subtotal, $promo_details);
                            return $response;
                        }

                        else // subtotal amount is less than the minimum amount
                        { 
                            $response['success'] = false;
                            $response['msg'] = 'Promo code not valid. Your subtotal must be greater than $'.$promo_details[0]->min_purchase_amount.' in order to apply this promo code ';
                            return $response;
                        }
                    }

                    else // promo code DOESN'T have minimum purchased amount 
                    {
                        //========= A ==========
                        // LIMIT NUMBER OF USE RPOMO - check the scenarios of the promo code if it has a limit number of use or no
                        $response = $this->limitNbOfUsePromo($promo_code, $user_id, $subtotal, $promo_details);
                        return $response;
                    }
                }

                else // promo code expired  
                {
                    $response['success'] = false;  
                    $response['msg'] = 'Promo code expired'; 
                    return $response;
                }  
            }

            else // promo doesn't exist
            {
                $response['success'] = false;  
                $response['msg'] = 'Invalid promo code'; 
                return $response;
            }
        }

        else // user is not authenticated
        {
            $response['success'] = false;  
            $response['msg'] = 'Please login in order to apply promo codes.'; 
            return $response;
        }

    }   




    //========= A ==========
    // LIMIT NUMBER OF USE RPOMO - check the scenarios of the promo code if it has a limit number of use or no
    public function limitNbOfUsePromo($promo_code, $user_id, $subtotal, $promo_details)
    {
        // if the promo have a max number of use
        if($promo_details[0]->use_limit !== NULL)
        {
            $n = $this->promoRepository->getPromoCodeUsers($promo_code);
            // check if we exceed the maximum number of promo used
            if($n[0]->nbr_of_use !== NULL && $n[0]->nbr_of_use < $promo_details[0]->use_limit)
            {
                //========= B ==========
                // ONE USE PER USER RPOMO - check the scenarios of the promo code if it is to be used once per user or not
                $response = $this->oneUsePerUserPromo($promo_code, $user_id, $subtotal, $promo_details);
                return $response;
            }

            else // max number of use is REACHED
            {
                $response['success'] = false;  
                $response['msg'] = 'This promo code has reached its maximum number of use'; 
                return $response;
            }
        }

        else // promo doesn't have maximum number of use
        {
            //========= B ==========
            // ONE USE PER USER RPOMO - check the scenarios of the promo code if it is to be used once per user or not
            $response = $this->oneUsePerUserPromo($promo_code, $user_id, $subtotal, $promo_details);
            return $response;
        }
    }





    //========= B ==========
    // ONE USE PER USER RPOMO - check the scenarios of the promo code if it is to be used once per user or not
    public function oneUsePerUserPromo($promo_code, $user_id, $subtotal, $promo_details)
    {
         // if the promo code is to be applied ONCE PER USER
        if($promo_details[0]->one_use_per_customer == 1)
        {
            $is_used = $this->promoRepository->isPromoUsedByUser($promo_code, $user_id);
            //if the promo is used by the authenticated user
            if($is_used)
            {
               $response['success'] = false;  
               $response['msg'] = 'You have already used this promo code.'; 
               return $response;
            }
            else // promo code is not used by the authenticated user
            {
               //========= C ==========
               // Valid PROMO - return the data of the promo and store them into a session  
               $response = $this->validPromo($promo_code, $user_id, $subtotal, $promo_details); 
               return $response;
            }
        }
        else // promo code is NOT to be applied ONCE PER USER
        {
           //========= C ==========
           // Valid PROMO - return the data of the promo and store them into a session  
           $response = $this->validPromo($promo_code, $user_id, $subtotal, $promo_details); 
           return $response;
        }
    }



    //========= C ==========
    // Valid PROMO - return the data of the promo and store them into a session
    public function validPromo($promo_code, $user_id, $subtotal, $promo_details)
    {
        session(['promo_code' => $promo_code]);

        // if the promo type is percentage
       if($promo_details[0]->discount_percentage !== NULL)
       {
         session(['promo_type' => 'percentage']);
         session(['value' => $promo_details[0]->discount_percentage]);

         $discount = ($subtotal*$promo_details[0]->discount_percentage)/100;
        
         $response['success'] = true;
         $response['msg'] = 'Valid promo code';
         $response['promo_type'] = 'percentage';
         $response['value'] = $promo_details[0]->discount_percentage;
  
         return $response;
       } 

       //if the promo type is a discount value
       if($promo_details[0]->discount_value !== NULL)
       {
         session(['promo_type' => 'amount']);
         session(['value' => $promo_details[0]->discount_value]);

         $discount = $promo_details[0]->discount_value;

         $response['success'] = true;
         $response['msg'] = 'Valid promo code';
         $response['promo_type'] = 'amount';
         $response['value'] = $promo_details[0]->discount_value;

         return $response;
       } 

        session(['discount' => $discount]);
    }

}