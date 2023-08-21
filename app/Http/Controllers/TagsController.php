<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\S3bucketController;
use App\Http\Repositories\TagsRepository;
use Auth;

class TagsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TagsRepository $tagsRepository, S3bucketController $s3bucketController)
    {
        $this->TagsRepository = $tagsRepository;
        $this->S3bucketController = $s3bucketController;
        $this->middleware('auth:admin');
    }


    //Shows the list of tags
    public function index()
    {
        // Returns the list of tags
        $tags = $this->TagsRepository->show();

        return view('cms/tags/index', array('tags' => $tags));
    }


    //Allows to add a new tag
    public function add(Request $request)
    {

        // If the form is filled
        if ($request->filled(['name']))
        {
            $imageFileName = $this->S3bucketController->fillInputWithImageForAdd($request, 'image', 'tags', 'tags/thumbs', '600', NULL, '100', '67');

             $og_img = $this->S3bucketController->fillInputWithImageForAdd($request, 'og_image', 'seo', 'seo/thumbs', '1200', NULL, '100', '67');

            // Adding a tag
            $this->TagsRepository->add($request, $imageFileName, $og_img); 
        }
           
        return redirect()->back();
    }


    //Allows to update a tag
    public function update(Request $request)
    {
        // If the form is filled
        if ($request->filled(['edit_name']))
        {
            // get the old info of the image
            $info = $this->TagsRepository->getTagDataFromId($request->input('edit_id'));
            
            // process the image, compress and resize to create original img and thumb img. Return the image name
            $imageFileName = $this->S3bucketController->fillInputWithImageForEdit($request, 'edit_image', 'tags', 'tags/thumbs', '600', NULL, '100', '67', $info[0]->img);

            // process the image, compress and resize to create original img and thumb img. Return the image name
            $e_og_img = $this->S3bucketController->fillInputWithImageForEdit($request, 'edit_og_image', 'seo', 'seo/thumbs', '1200', NULL, '100', '67', $info[0]->og_image);

            // Editing a category
            $this->TagsRepository->update($request, $imageFileName, $e_og_img); 
        }


        return redirect()->back();
    }


    // Update collection tag
    public function updateCollectionTag(Request $request)
    {
      $data = $this->TagsRepository->updateCollectionTag($request);
       // return the info to the ajax call
       return response()->json($data);
    }    


    // Allows to delete a tag
    public function delete(Request $request)
    { 
        //Delete this tag
        $this->TagsRepository->delete($request);
        // return the info to the ajax call
       return response()->json();  
    }

   
    // Gets tag details from tag_id
    public function getTagDataFromId(Request $request)
    {
       $data = $this->TagsRepository->getTagDataFromId($request->input('id'));
       // return the info to the ajax call
       return response()->json($data);
    }
    
}
