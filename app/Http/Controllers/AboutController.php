<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\S3bucketController;
use App\Http\Repositories\AboutRepository;
use Auth;

class AboutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AboutRepository $aboutRepository, S3bucketController $s3bucketController)
    {
        $this->aboutRepository = $aboutRepository;
        $this->S3bucketController = $s3bucketController;
        $this->middleware('auth:admin');
    }


    // Shows the content of about page
    public function index()
    {
        // Returns about page content
        $about = $this->aboutRepository->show();
        return view('cms/pages/about/index', array('about' => $about));
    }


    /* -------------------------------------- ABOUT CONTENT ----------------------------------------*/

    // Allows to update about page content
    public function update(Request $request)
    {
        // get the old info of the header image
        $info = $this->aboutRepository->show();
        if($info)
        {
            // process the image, compress and resize to create original img and thumb img. Return the image name
            $imageFileName = $this->S3bucketController->fillInputWithImageForEdit($request, 'image', 'about', 'about/thumbs', '1920', NULL, '100', '67', $info[0]->header_img);
        }
        else 
        {
            // process the image, compress and resize to create original img and thumb img. Return the image name
            $imageFileName = $this->S3bucketController->fillInputWithImageForAdd($request, 'image', 'about', 'about/thumbs', '1920', NULL, '100', '67');
        }

        $this->aboutRepository->update($request, $imageFileName); 
        return redirect()->back();
    }
    
}
