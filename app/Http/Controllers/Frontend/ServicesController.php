<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use SEO;

class ServicesController extends Controller
{

    //services page
    public function services()
    {      
        SEO::setTitle('Kamaplast | Services | Plastic Industry, Household, Garden Furniture, Pet Packaging, Commercial');
        SEO::setDescription('kamaplast the number one plastic manufacturer and distributor in the Middle East');
        SEO::opengraph()->setUrl('https://kama-plast.com/');
        SEO::setCanonical('https://kama-plast.com/');
        SEO::opengraph()->addProperty('type', 'articles');
        SEO::twitter()->setSite('@kamaplast');

        return view('frontend.services.services');
    }

    //services details page
    public function serviceDetails()
    {      
       SEO::setTitle('Kamaplast | Services Details | Plastic Industry, Household, Garden Furniture, Pet Packaging, Commercial');
        SEO::setDescription('kamaplast the number one plastic manufacturer and distributor in the Middle East');
        SEO::opengraph()->setUrl('https://kama-plast.com/');
        SEO::setCanonical('https://kama-plast.com/');
        SEO::opengraph()->addProperty('type', 'articles');
        SEO::twitter()->setSite('@kamaplast');

        return view('frontend.services.service-details');
    }

}