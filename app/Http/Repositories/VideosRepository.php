<?php 

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class VideosRepository {

	/*----------------------------------
    get the list of all videos
    ------------------------------------*/
    public function show()
    {
        $q = \DB::select("SELECT *   
			        	  FROM videos
			        	  WHERE hidden=0");
        return $q;
    }


    /*----------------------------------
    Insert a new video
    ------------------------------------*/
    public function add($request)
    {
      //script to get video id from url
      preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $request->input('video_url'), $match);
      $video_id = $match[1];

      \DB::table('videos')->insert(
         array('video_id' => $video_id,
               'url' =>$request->input('video_url'),
               'hidden' => 0,
               'created_at' => Carbon::now('Asia/Beirut'),
               'created_by' => Auth::id(),
               'updated_at' => Carbon::now('Asia/Beirut'),
               'updated_by' => Auth::id()
          )
      );        
    }


    /*----------------------------------
    Delete a video
    ------------------------------------*/
    public function delete($request)
    {
      // delete video
      \DB::select("DELETE 
                   FROM videos 
                   WHERE id = :id",
      array(':id' => $request->input('id'))
      );
    }
   
}