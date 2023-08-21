<?php 

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class BrandsRepository {

	/*----------------------------------
    get the list of all Brands
    ------------------------------------*/
    public function show()
    {
        $t = \DB::select("SELECT *   
			        	  FROM brands
			        	  WHERE hidden=0");
        return $t;
    }


    /*----------------------------------
    Insert a new Brand
    ------------------------------------*/
    public function add($request, $img)
    {

        \DB::table('brands')->insert(
           array('name' => $request->input('name'),
                 'description' =>$request->input('description'),
                 'img' => $img,
                 'hidden' => 0,
                 'created_at' => Carbon::now('Asia/Beirut'),
                 'created_by' => Auth::id(),
                 'updated_at' => Carbon::now('Asia/Beirut'),
                 'updated_by' => Auth::id()
            )
        );        
    }


    /*----------------------------------
    get the the data of a brand from its id
    ------------------------------------*/
    public function getBrandDataFromId($brand_id)
    {
        $q = \DB::select("SELECT * 
                          FROM brands 
                          WHERE brand_id=:brand_id
                          AND hidden=0",
            array(':brand_id' => $brand_id));

        return $q;
            
    }

  
    /*----------------------------------
    Update a brand
    ------------------------------------*/
    public function update($request, $img)
    {	
		  \DB::table('brands')
           ->where('brand_id', '=', $request->input('edit_id'))
           ->update(
               array('name' => $request->input('edit_name'),
               'description' =>$request->input('edit_description'),
               'img' =>$img,
               'hidden' => 0
                     )
           ); 
    }


    /*----------------------------------
    Delete a brand
    ------------------------------------*/
    public function delete($request)
    {
      // delete brand
      \DB::select("DELETE 
                   FROM brands 
                   WHERE brand_id = :brand_id",
      array(':brand_id' => $request->input('id')
            )
      );
      
    }

    
   
}