<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Repositories\BlogRepository;
use App\Http\Repositories\VideosRepository;
use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use SEO;

class MediaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(BlogRepository $blogRepository, VideosRepository $videosRepository)
    {
        $this->blogRepository = $blogRepository;
        $this->videosRepository = $videosRepository;
    }

    //media page
    public function media()
    {      
         SEO::setTitle('Kamaplast | Blog | Plastic Industry, Household, Garden Furniture, Pet Packaging, Commercial');
        SEO::setDescription('Discover Kamaplast and its unique plastic products');
        SEO::opengraph()->setUrl('https://kama-plast.com/');
        SEO::setCanonical('https://kama-plast.com/blog');
        SEO::opengraph()->addProperty('type', 'articles');
        SEO::twitter()->setSite('@kamaplast');

        // Returns the list of slides
        $media = $this->blogRepository->show();

        return view('frontend.media.media', array('media' => $media));
    }

    //tutorials page
    public function tutorials()
    {      
         SEO::setTitle('Kamaplast | Blog | Plastic Industry, Household, Garden Furniture, Pet Packaging, Commercial');
        SEO::setDescription('Discover Kamaplast and its unique plastic products');
        SEO::opengraph()->setUrl('https://kama-plast.com/');
        SEO::setCanonical('https://kama-plast.com/blog');
        SEO::opengraph()->addProperty('type', 'articles');
        SEO::twitter()->setSite('@kamaplast');
        
        // Returns the list of tutorials
        $tutorials = $this->videosRepository->show();
        
        return view('frontend.media.tutorials', array('tutorials' => $tutorials));
    }

}