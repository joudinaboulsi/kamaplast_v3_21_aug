<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\BrandsRepository;
use App\Http\Controllers\S3bucketController;
use Auth;

class BrandsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BrandsRepository $brandsRepository, S3bucketController $s3bucketController)
    {
        $this->BrandsRepository = $brandsRepository;
        $this->S3bucketController = $s3bucketController;
        $this->middleware('auth:admin');
    }


    //Shows the list of Brands
    public function index()
    {
        // Returns the list of Brands
        $brands = $this->BrandsRepository->show();

        return view('cms/brands/index', array('brands' => $brands));
    }


    //Allows to add a new brand
    public function add(Request $request)
    {   
        // check if image has input, process the image, compress and resize to create original img and thumb img. Return the image name
        $imageFileName = $this->S3bucketController->fillInputWithImageForAdd($request, 'brand_img', 'brands', 'brands/thumbs', config('global.BRANDS_MAX_WIDTH'), config('global.BRANDS_MAX_HEIGHT'), config('global.BRANDS_WIDTH_THUMBS'), config('global.BRANDS_HEIGHT_THUMBS'));

        // Adding a brand
        $this->BrandsRepository->add($request, $imageFileName); 
           
        return redirect()->back();
    }


    //Allows to update a brand
    public function update(Request $request)
    {
        // If the form is filled
        if ($request->filled(['edit_name']))
        {   
  
            // get the old info 
            $data = $this->BrandsRepository->getBrandDataFromId($request->input('edit_id'));

            // check if image has input, process the image, compress and resize to create original img and thumb img. Return the image name
            $imageFileName = $this->S3bucketController->fillInputWithImageForEdit($request, 'edit_brand_img', 'brands', 'brands/thumbs', config('global.BRANDS_MAX_WIDTH'), config('global.BRANDS_MAX_HEIGHT'), config('global.BRANDS_WIDTH_THUMBS'), config('global.BRANDS_HEIGHT_THUMBS'), $data[0]->img);

            // Editing a brand
            $this->BrandsRepository->update($request, $imageFileName); 
        }

        return redirect()->back();
    }


    // Allows to delete a brand
    public function delete(Request $request)
    { 
        //Delete this brand
        $this->BrandsRepository->delete($request);
        // return the info to the ajax call
       return response()->json();  
    }

   
    // Gets brand details from brand_id
    public function getBrandDataFromId(Request $request)
    {
       $data = $this->BrandsRepository->getBrandDataFromId($request->input('id'));
       // return the info to the ajax call
       return response()->json($data);
    }

    
}
