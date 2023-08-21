<?php 

namespace App\Http\Repositories\Frontend;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\QueryException;

class PromoRepository {

    /*----------------------------------
    Check the deleted variants, products, out of stock products and remove them from the Cart
    ------------------------------------*/
    public function checkPromoCodeValidity($promo_code)
    {

     $q = \DB::select("SELECT *
                       FROM promo_codes
                       WHERE name = '".$promo_code."'
                       AND hidden = 0"
                    );

     return $q;
    }


    /*---------------------------------------------------------
    return the number of user that used the selected promo code
    ----------------------------------------------------------*/
    public function getPromoCodeUsers($promo_code)
    {

     $q = \DB::select("SELECT COUNT(A.promo_code_id) as nbr_of_use
                       FROM promo_codes_used_by_users as A
                       JOIN promo_codes as B ON A.promo_code_id = B.promo_code_id
                       WHERE B.name = '".$promo_code."'
                       AND A.hidden = 0"
                    );
     return $q;
    }


    /*------------------------------------------------------------
    Check if the selected user used the selected promo code or not 
    -------------------------------------------------------------*/
    public function isPromoUsedByUser($promo_code, $user_id)
    {

     $q = \DB::select("SELECT A.promo_code_id
                       FROM promo_codes_used_by_users as A
                       JOIN promo_codes as B ON A.promo_code_id = B.promo_code_id
                       WHERE B.name = '".$promo_code."'
                       AND user_id = :user_id
                       AND A.hidden = 0",
                    array(':user_id' => $user_id)
                    );
     
     if(!empty($q)) 
        return true; // promo is used
     else
        return false; // promo not used
    
    }

}
