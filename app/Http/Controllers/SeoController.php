<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\SeoRepository;
use App\Http\Controllers\S3bucketController;
use Auth;

class SeoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(S3bucketController $s3bucketController, SeoRepository $seoRepository)
    {
        $this->seoRepository = $seoRepository;
        $this->S3bucketController = $s3bucketController;
        $this->middleware('auth:admin');
    }

    
    //Shows the list of pages seo
    public function index()
    {
        // Returns the pages seo List
        $seo=$this->seoRepository->show(); 
        // dd($seo);
        $og_tw=$this->seoRepository->showOGTwitter();
        $og_twitter=$og_tw[0]->og_twitter;
        return view('cms/seo/index', array('seo' => $seo, 'og_twitter' => $og_twitter));
    }


    //Allows to get seo info to edit
    public function getSEOToEdit(Request $request)
    {
        // get seo info
        $data = $this->seoRepository->getSEOPageInfo($request->input('id')); 
        // return the info to the ajax call
        return response()->json($data);
    }


    //Allows to update a page seo
    public function updateSEO(Request $request)
    {
        // get the old info of the image
        $info = $this->seoRepository->getSEOPageInfo($request->input('seo_page_id'));
        
        // process the image, compress and resize to create original img and thumb img. Return the image name
        $imageFileName = $this->S3bucketController->fillInputWithImageForEdit($request, 'og_image', 'seo', 'seo/thumbs', '1200', NULL, '100', '67', $info[0]->og_image);
        //Updating seo 
        $this->seoRepository->updateSEO($request, $imageFileName);  
        return redirect()->back();
   }
   

    //Allows to update OG Twitter
    public function updateOGTwitter(Request $request)
    {
        $this->seoRepository->updateOGTwitter($request);  
        return redirect()->back();
    }
 
}
