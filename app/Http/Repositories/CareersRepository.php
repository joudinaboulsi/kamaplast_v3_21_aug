<?php 

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class CareersRepository {

	/*----------------------------------
    get the list of all slides/separators
    ------------------------------------*/
    public function show()
    {
        $q = \DB::select("SELECT *   
			        	  FROM careers
                  WHERE hidden=0
                  ORDER BY career_id DESC");

        return $q;
    }


    /*----------------------------------
    Insert a new career
    ------------------------------------*/
    public function add($request)
    {

        \DB::table('careers')->insert(
           array('title' => $request->input('title'),
                 'description' =>$request->input('description'),
                 'hidden' => 0,
                 'created_at' => Carbon::now('Asia/Beirut'),
                 'created_by' => Auth::id(),
                 'updated_at' => Carbon::now('Asia/Beirut'),
                 'updated_by' => Auth::id()
            )
        );        
    }


    /*----------------------------------
    get the career details from its id
    ------------------------------------*/
    public function getCareerFromId($id)
    {
        $q = \DB::select("SELECT * 
                          FROM careers 
                          WHERE career_id=:id",
            array(':id' => $id));

        return $q;    
    }

  
    /*----------------------------------
    Update careers
    ------------------------------------*/
    public function update($request)
    {	
		  \DB::table('careers')
           ->where('career_id', '=', $request->input('e_career_id'))
           ->update(
               array('title' => $request->input('e_title'),
                     'description' =>$request->input('e_description'),
                     'updated_at' => Carbon::now('Asia/Beirut'),
                     'updated_by' => Auth::id()
                     )
           ); 
    }


    /*----------------------------------
    Delete a career
    ------------------------------------*/
    public function delete($request)
    {
      \DB::table('careers')
           ->where('career_id', '=', $request->input('id'))
           ->update(
               array('hidden' => 1,
                     'updated_at' => Carbon::now('Asia/Beirut'),
                     'updated_by' => Auth::id()
                     )
           ); 
    }
   
}