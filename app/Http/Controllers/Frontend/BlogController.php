<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Repositories\BlogRepository;
use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use SEO;

class BlogController extends Controller
{
    public function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    //blog page
    public function blog()
    {   
        SEO::setTitle('Kamaplast | Blog | Plastic Industry, Household, Garden Furniture, Pet Packaging, Commercial');
        SEO::setDescription('Discover Kamaplast and its unique plastic products');
        SEO::opengraph()->setUrl('https://kama-plast.com/');
        SEO::setCanonical('https://kama-plast.com/blog');
        SEO::opengraph()->addProperty('type', 'articles');
        SEO::twitter()->setSite('@kamaplast');

        // Returns the list of slides
        $news = $this->blogRepository->show();

        return view('frontend.blog.blog', array('news' => $news));
    }

    //blog details page
    public function blogDetails($blog_id)
    {      
        
        $news = $this->blogRepository->showDetails($blog_id);

        SEO::setTitle('Eideal | Blog | '.$news[0]->title );
        SEO::setDescription('Discover Kamaplast and its unique plastic products');
        SEO::opengraph()->setUrl('https://kama-plast.com/');
        SEO::setCanonical('https://kama-plast.com/blog');
        SEO::opengraph()->addProperty('type', 'articles');
        SEO::twitter()->setSite('@kamaplast');

        return view('frontend.blog.blog-details', array('news' => $news));
    }

}