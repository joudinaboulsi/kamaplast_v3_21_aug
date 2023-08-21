<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\S3bucketController;
use App\Http\Repositories\SlidesRepository;
use Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(SlidesRepository $slidesRepository, S3bucketController $s3bucketController)
    {
        $this->slidesRepository = $slidesRepository;
        $this->S3bucketController = $s3bucketController;
        $this->middleware('auth:admin');
    }


    // Shows the list of slides and separators
    public function index()
    {
        // Returns the list of slides
        $slides = $this->slidesRepository->show(0);
        // Returns the list of separators
        $separators = $this->slidesRepository->show(1);

        return view('cms/pages/home/index', array('slides' => $slides, 'separators' => $separators));
    }


    /* -------------------------------------- SLIDES / SEPARATORS ----------------------------------------*/

    // Allows to add a new slide/separator
    public function addSlide(Request $request)
    {
        // If the img is filled
        if($request->hasFile('image'))
        {
            $imageFileName = $this->S3bucketController->fillInputWithImageForAdd($request, 'image', 'slides', 'slides/thumbs', '1920', NULL, '100', '67');

            // Adding a slide
            $this->slidesRepository->add($request, $imageFileName); 
        }

        return redirect()->back();
    }


    // Gets slide details from slide_id
    public function getSlideDataFromId(Request $request)
    {
       $data = $this->slidesRepository->getSlideFromId($request->input('id'));
       // return the info to the ajax call
       return response()->json($data);
    }


    // Allows to update a slide/separator
    public function updateSlide(Request $request)
    {
        // get the old info of the image
        $info = $this->slidesRepository->getSlideFromId($request->input('e_id'));
        
        // process the image, compress and resize to create original img and thumb img. Return the image name
        $imageFileName = $this->S3bucketController->fillInputWithImageForEdit($request, 'e_image', 'slides', 'slides/thumbs', '1920', NULL, '100', '67', $info[0]->image);

        // Editing a slide/separator
        $this->slidesRepository->update($request, $imageFileName); 

        return redirect()->back();
    }


    // Allows to delete a slide/separator
    public function deleteSlide(Request $request)
    { 
        //Delete this slide/separator
        $this->slidesRepository->delete($request);
        // return the info to the ajax call
       return response()->json();  
    }
    

}
