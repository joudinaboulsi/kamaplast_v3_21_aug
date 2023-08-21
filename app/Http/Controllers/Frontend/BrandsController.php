<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use SEO;

class BrandsController extends Controller
{

    //brands page
    public function brands()
    {    
         SEO::setTitle('Kamaplast | Blog | Plastic Industry, Household, Garden Furniture, Pet Packaging, Commercial');
        SEO::setDescription('Discover Kamaplast and its unique plastic products');
        SEO::opengraph()->setUrl('https://kama-plast.com/');
        SEO::setCanonical('https://kama-plast.com/blog');
        SEO::opengraph()->addProperty('type', 'articles');
        SEO::twitter()->setSite('@kamaplast');

        return view('frontend.brands.brands');
    }

    //brands details page
    public function brandDetails()
    {      
         SEO::setTitle('Kamaplast | Blog | Plastic Industry, Household, Garden Furniture, Pet Packaging, Commercial');
        SEO::setDescription('Discover Kamaplast and its unique plastic products');
        SEO::opengraph()->setUrl('https://kama-plast.com/');
        SEO::setCanonical('https://kama-plast.com/blog');
        SEO::opengraph()->addProperty('type', 'articles');
        SEO::twitter()->setSite('@kamaplast');

        return view('frontend.brands.brand-details');
    }

}