<?php 

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class AboutRepository {

	/*----------------------------------
    get content of about page
    ------------------------------------*/
    public function show()
    {
        $q = \DB::select("SELECT *   
			        	  FROM about");
        return $q;
    }


    /*----------------------------------
    Update about page content
    ------------------------------------*/
    public function update($request, $img)
    {	
		  \DB::table('about')
           ->update(
               array('content' => $request->input('about'),
                     'header_img' => $img,
                     'header_title' => $request->input('title'),
                     'header_subtitle' => $request->input('subtitle')
                    )
           ); 
    }
   
}