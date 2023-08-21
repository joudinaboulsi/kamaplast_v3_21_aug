<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\S3bucketController;
use App\Http\Repositories\BlogRepository;
use Auth;


class BlogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(BlogRepository $blogRepository, S3bucketController $s3bucketController)
    {
        $this->blogRepository = $blogRepository;
        $this->S3bucketController = $s3bucketController;
        $this->middleware('auth:admin');
    }


    // Shows the list of News
    public function index()
    {
        // Returns the list of slides
        $news = $this->blogRepository->show();
        return view('cms/pages/blog/index', array('news' => $news));
    }




    /* -------------------------------------- News ----------------------------------------*/

    // Allows to add News
    public function addNews(Request $request)
    {
        // If the img is filled
        if($request->hasFile('image'))
           $imageFileName = $this->S3bucketController->fillInputWithImageForAdd($request, 'image', 'blog', 'blog/thumbs', '600', NULL, '100', '67');
        else
            $imageFileName = NULL;

        // If the og img is filled
        if($request->hasFile('og_image'))
            $og_img = $this->S3bucketController->fillInputWithImageForAdd($request, 'og_image', 'seo', 'seo/thumbs', '1200', NULL, '100', '67');
        else
            $og_img = NULL;
            
        // Adding news
        $this->blogRepository->addNews($request, $imageFileName, $og_img); 
        return redirect()->back();
    }


    // Gets News details from id
    public function showDetails($news_id)
    {
        // get news details
        $news_details = $this->blogRepository->showDetails($news_id);

        // get news linked tags
        $newsTags = $this->blogRepository->getBlogTags($news_id);

        // get tags list
        $tagsList = $this->blogRepository->getTagsList($news_id);
       return view('cms/pages/blog/news-details', array('news_details' => $news_details, 'newsTags' => $newsTags, 'tagsList' => $tagsList));
    }


    // Allows to update News
    public function updateNews(Request $request)
    {
        // get the old info of the image
        $info = $this->blogRepository->showDetails($request->input('news_id'));
        
        // process the image, compress and resize to create original img and thumb img. Return the image name
        $imageFileName = $this->S3bucketController->fillInputWithImageForEdit($request, 'image', 'blog', 'blog/thumbs', '600', NULL, '100', '67', $info[0]->image);

        // process the image, compress and resize to create original img and thumb img. Return the image name
        $e_og_img = $this->S3bucketController->fillInputWithImageForEdit($request, 'og_image', 'seo', 'seo/thumbs', '1200', NULL, '100', '67', $info[0]->og_image);

        // Editing News
        $this->blogRepository->updateNews($request, $imageFileName, $e_og_img); 

        return redirect()->back();
    }


    //Allows to add tag to news
    public function addTag(Request $request)
    {
        // Adding a tag to news
        $this->blogRepository->addTag($request); 
        return redirect()->back();
    }


    //Allows to delete linked tag
    public function deleteTag(Request $request)
    {
        // Deleting a news tag
        $this->blogRepository->deleteTag($request); 
        // return the info to the ajax call
        return response()->json();
    }


    // Allows to delete News
    public function deleteNews(Request $request)
    { 
        //Deleting News
        $this->blogRepository->deleteNews($request);
        // return the info to the ajax call
       return response()->json();  
    }
    

}
