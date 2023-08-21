<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\S3bucketController;
use App\Http\Repositories\VideosRepository;
use Auth;


class VideosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(VideosRepository $videosRepository, S3bucketController $s3bucketController)
    {
        $this->videosRepository = $videosRepository;
        $this->S3bucketController = $s3bucketController;
        $this->middleware('auth:admin');
    }


    // Shows the list of videos
    public function index()
    {
        // Returns the list of videos
        $videos = $this->videosRepository->show();

        return view('cms/pages/videos/index', array('videos' => $videos));
    }


    /* -------------------------------------- VIDEOS ----------------------------------------*/

    // Allows to add a new Video
    public function addVideo(Request $request)
    {
        // Adding a Video
        $this->videosRepository->add($request); 
        return redirect()->back();
    }


    // Allows to delete a video
    public function deleteVideo(Request $request)
    { 
        //Delete this Video
        $this->videosRepository->delete($request);
        // return the info to the ajax call
       return response()->json();  
    }
    

}
