<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\S3bucketController;
use App\Http\Repositories\PromoCodesRepository;
use Auth;

class PromoCodesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PromoCodesRepository $promoCodesRepository)
    {
        $this->PromoCodesRepository = $promoCodesRepository;
        $this->middleware('auth:admin');
    }


    //Shows the list of Promo Codes
    public function index()
    {
        // Returns the list of Promo Codes
        $promo_codes = $this->PromoCodesRepository->show();
      
        return view('cms/promo-codes/index', array('promo_codes' => $promo_codes));
    }


    //Allows to add a new Promo Code
    public function addPromoPage()
    {
        // Returns the list of Promo Codes
        $products_list = $this->PromoCodesRepository->showProducts();
        return view('cms/promo-codes/add-promo-code', array('products_list' => $products_list));
    }


    //Allows to add a new Promo Code
    public function add(Request $request)
    {
        // Adding a Promo Code
        $promo_id= $this->PromoCodesRepository->add($request); 
           
       return redirect('promo-code-'.$promo_id);
    }


    //Allows to update a Promo Code
    public function update(Request $request)
    {
        // update a Promo Code
        $this->PromoCodesRepository->update($request); 
           
       return redirect()->back();
    }


    // Allows to publish/unpublish a Promo Code
    public function publish(Request $request)
    { 
        //publish/unpublish this Promo Code
        $publish = $this->PromoCodesRepository->publish($request);
        // return the info to the ajax call
       return response()->json($publish);  
    }


    //Allows to delete Promo Code
    public function delete(Request $request)
    {
        // deleting a Promo Code
        $this->PromoCodesRepository->delete($request); 

        // return the info to the ajax call
        return response()->json();  
    }


    //Shows the Promo Code details 
    public function showDetails($promo_code_id)
    {
        // Returns the details of specific Promo Code
        $promo_code_details = $this->PromoCodesRepository->showDetails($promo_code_id);
      
        return view('cms/promo-codes/promo-code-details', array('promo_code_details' => $promo_code_details));
    }



    
}
