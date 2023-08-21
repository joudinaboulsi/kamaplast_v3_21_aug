<?php 

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\QueryException;

class PromoCodesRepository {

    /*----------------------------------
    get the list of all Promo Codes
    ------------------------------------*/
    public function show()
    {
        $p = \DB::select("SELECT A.*, count(B.promo_code_id) as use_number
                          FROM promo_codes as A
                          LEFT JOIN promo_codes_used_by_users as B on A.promo_code_id = B.promo_code_id
                          GROUP BY A.promo_code_id");
        return $p;
    }


    /*----------------------------------
    Insert a new promo code
    ------------------------------------*/
    public function add($request)
    {
       try
      {
        \DB::transaction(function () use ($request){


        if($request->input('type')==0)
        {
          $percent = $request->input('discount_value');
          $amount = null;
        }
        elseif($request->input('type')==1)
        {
          $amount = $request->input('discount_value');
          $percent = null;
        }


        if($request->input('applyto')==1)
        {
          $entire_order = $request->input('applyto');
        }
        else $entire_order = 0;


        if($request->input('requirement')==0)
        {
          $min_purchase_amount = null;
          $min_qty_items = null;
        }
        elseif ($request->input('requirement')==1)
        {
          $min_purchase_amount = $request->input('purchase_amount');
          $min_qty_items = null;
        }
        elseif ($request->input('requirement')==2)
        {
          $min_qty_items = $request->input('items_qty');
          $min_purchase_amount = null;
        }
        else
        {
          $min_purchase_amount = null;
          $min_qty_items = null;
        }


        if($request->input('once_per_user')==1)
        {
          $one_use = $request->input('once_per_user');
        }
        else $one_use = 0;


        // get the start and end date
        $discount_daterange = explode(' - ', $request->input('discount_daterange')); 
        // Returns promo id after insterting a promo in promo table
        $promo_id = \DB::table('promo_codes')->insertGetId(
                     array('name' => $request->input('code_name'),
                     'discount_value' => $amount,
                     'discount_percentage' => $percent,
                     'min_purchase_amount' => $min_purchase_amount,
                     'min_qty_items' => $min_qty_items,
                     'start_date' => $discount_daterange[0],
                     'end_date' => $discount_daterange[1],
                     'use_limit' => $request->input('nbr_of_times'),
                     'one_use_per_customer' => $one_use,
                     'hidden' => 0,
                     'created_at' => Carbon::now('Asia/Beirut'),
                     'created_by' => Auth::id(),
                     'updated_at' => Carbon::now('Asia/Beirut'),
                     'updated_by' => Auth::id()
                    )
                  );   

        // Check if the prpmo has product(s)
        if($request->input('products') != NULL)
         {
            $products = []; //declare an empty array

            foreach($request->input('products') as $p)
               {
                 // build the component table
                 array_push($products, array('product_id' => $p,
                                             'promo_code_id' => $promo_id
                                            ));
               }
            // insert the all the products of the products
            \DB::table('promo_codes_has_products')->insert($products); // Query Builder
          }

        $request->session()->put('promo_id', $promo_id);

          });

      return $request->session()->pull('promo_id');
      }

      catch(QueryException $ex)
      { 
         dd($ex->getMessage());
         //dd('Error while adding a Prodcut');
      }   
    
      
    }


    /*----------------------------------
    Update a promo code
    ------------------------------------*/
    public function update($request)
    {
        \DB::transaction(function () use ($request){

        if($request->input('use_limit')==1)
          $nbr_of_times = $request->input('nbr_of_times');
        else
          $nbr_of_times = NULL;


        if($request->input('once_per_user')==1)
          $one_use = $request->input('once_per_user');
        else 
          $one_use = 0;


        // get the start and end date
        $discount_daterange = explode(' - ', $request->input('discount_daterange')); 

        // updating a promo in promo table
        \DB::table('promo_codes')
           ->where('promo_code_id', '=', $request->input('promo_code_id'))
           ->update(
              array('start_date' => date("Y-m-d", strtotime($discount_daterange[0])),
                    'end_date' => date("Y-m-d", strtotime($discount_daterange[1])),
                    'use_limit' => $nbr_of_times,
                    'one_use_per_customer' => $one_use,
                    'updated_at' => Carbon::now('Asia/Beirut'),
                    'updated_by' => Auth::id()
                     )
           ); 

       });

    }


    /*----------------------------------
    Get the list of products
    ------------------------------------*/
    public function showProducts()
    {
        $p = \DB::select("SELECT product_id,name
                          FROM products
                          WHERE hidden =0");
        return $p;
    }


    /*----------------------------------
    Publish/Unpublish a promo code
    ------------------------------------*/
    public function publish($request)
    {
      // Check if the promo code is published or not
      $hidden = \DB::select("SELECT hidden 
                                FROM promo_codes
                                WHERE promo_code_id = :promo_code_id",
                        array(':promo_code_id' => $request->input('id')));

      if($hidden[0]->hidden==1) //promo_codes is unpublished
        $publish=0; //publish
      elseif($hidden[0]->hidden==0) //promo_codes is published
        $publish=1; //unpublish

      \DB::table('promo_codes')
         ->where('promo_code_id', '=', $request->input('id'))
         ->update(
             array('hidden' => $publish)
         );    

      return $publish;  
    }


    /*----------------------------------
    Delete a promo code
    ------------------------------------*/
    public function delete($request)
    {
        try
        {

          \DB::transaction(function () use ($request){

               \DB::select("DELETE 
                    FROM promo_codes_has_products 
                    WHERE promo_code_id =:promo_code_id",
                array(':promo_code_id' => $request->input('id'))
                );

               \DB::select("DELETE 
                    FROM promo_codes_used_by_users 
                    WHERE promo_code_id =:promo_code_id",
                array(':promo_code_id' => $request->input('id'))
                );

                \DB::select("DELETE 
                            FROM promo_codes 
                            WHERE promo_code_id =:promo_code_id",
                array(':promo_code_id' => $request->input('id'))
                ); 
            });
        
        }

        catch(QueryException $ex)
        { 
           dd($ex->getMessage());
           //dd('Error while adding a Prodcut');
        }  

    }


    /*----------------------------------
    Get the details of a specific Promo Code
    ------------------------------------*/
    public function showDetails($promo_code_id)
    {
        $p = \DB::select("SELECT *
                          FROM promo_codes
                          WHERE promo_code_id =:promo_code_id",
             array(':promo_code_id' => $promo_code_id));
        return $p;
    }


    /*----------------------------------
    Get the products list to which applies a promo
    ------------------------------------*/
    public function getProductsList($promo_code_id)
    {

        $q = \DB::select("SELECT A.product_id, A.name, B.promo_code_id
                          FROM products as A
                          LEFT JOIN promo_codes_has_products as B ON A.product_id = B.product_id AND B.promo_code_id = :promo_code_id",
             array(':promo_code_id' => $promo_code_id));
        return $q;
    }



   
}