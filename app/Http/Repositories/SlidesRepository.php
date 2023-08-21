<?php 

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class SlidesRepository {

	/*----------------------------------
    get the list of all slides/separators
    ------------------------------------*/
    public function show($is_separator)
    {
        $q = \DB::select("SELECT *   
			        	  FROM slides
			        	  WHERE is_separator=:is_separator
                  AND hidden=0",
            array(':is_separator' => $is_separator));

        return $q;
    }

    /*----------------------------------
    get the list of all slides/separators
    ------------------------------------*/
    public function showHidden($is_separator)
    {
        $q = \DB::select("SELECT *   
                          FROM slides
                          WHERE is_separator=:is_separator",
            array(':is_separator' => $is_separator));

        return $q;
    }


    /*----------------------------------
    Insert a new slide/separator
    ------------------------------------*/
    public function add($request, $img)
    {

        \DB::table('slides')->insert(
           array('title' => $request->input('title'),
                 'subtitle' =>$request->input('subtitle'),
                 'button_name' =>$request->input('btn_name'),
                 'action' =>$request->input('action'),
                 'image' => $img,
                 'is_separator' => $request->input('is_separator'),
                 'hidden' => 0,
                 'created_at' => Carbon::now('Asia/Beirut'),
                 'created_by' => Auth::id(),
                 'updated_at' => Carbon::now('Asia/Beirut'),
                 'updated_by' => Auth::id()
            )
        );        
    }


    /*----------------------------------
    get the data of a slide/separator from its id
    ------------------------------------*/
    public function getSlideFromId($id)
    {
        $q = \DB::select("SELECT * 
                          FROM slides 
                          WHERE slide_id=:id",
            array(':id' => $id));

        return $q;    
    }

  
    /*----------------------------------
    Update a slide/separator
    ------------------------------------*/
    public function update($request, $img)
    {	
		  \DB::table('slides')
           ->where('slide_id', '=', $request->input('e_id'))
           ->update(
               array('title' => $request->input('e_title'),
                     'subtitle' =>$request->input('e_subtitle'),
                     'button_name' =>$request->input('e_btn_name'),
                     'action' =>$request->input('e_action'),
                     'image' => $img,
                     'updated_at' => Carbon::now('Asia/Beirut'),
                     'updated_by' => Auth::id()
                     )
           ); 
    }


    /*----------------------------------
    Delete a slide/separator
    ------------------------------------*/
    public function delete($request)
    {
      // delete slide/separator
      \DB::select("DELETE 
                   FROM slides 
                   WHERE slide_id = :slide_id",
      array(':slide_id' => $request->input('id'))
      );
    }
   
}