<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Frontend\CategoriesApis;
use App\Http\Repositories\Frontend\ProductApis;
use App\Http\Repositories\Frontend\TagsApis;
use App\Http\Repositories\SlidesRepository;
use App\Http\Repositories\AboutRepository;
use App\Http\Repositories\CareersRepository;
use App\Http\Repositories\SeoRepository;
use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use SEO;
use SEOMeta;

class PagesController extends Controller
{
    public function __construct(
        CategoriesApis $categoriesApis,
        TagsApis $tagsApis,
        ProductApis $productApis,
        SlidesRepository $slidesRepository,
        AboutRepository $aboutRepository,
        CareersRepository $careersRepository,
        SeoRepository $seoRepository
    ) {
        $this->categoriesApis = $categoriesApis;
        $this->tagsApis = $tagsApis;
        $this->productApis = $productApis;
        $this->slidesRepository = $slidesRepository;
        $this->aboutRepository = $aboutRepository;
        $this->careersRepository = $careersRepository;
        $this->seoRepository = $seoRepository;
    }

    //Function to set seo dynamically
    public function setSeo($seo_data, $og_type)
    {
        //get url of the page
        $url = url()->current();
        //get OG twitter
        $og_tw = $this->seoRepository->showOGTwitter();
        SEO::setTitle($seo_data->seo_title);
        SEO::setDescription($seo_data->seo_description);
        SEOMeta::setKeywords($seo_data->seo_keywords);
        SEO::opengraph()->setUrl($url);
        SEO::setCanonical($url);
        SEO::opengraph()->addProperty('type', $og_type);
        SEO::twitter()->setSite('@' . $og_tw[0]->og_twitter);
        if ($seo_data->og_image != null) {
            // check if we have an OG image
            SEO::addImages(getenv('S3_URL') . '/seo/' . $seo_data->og_image);
        }
    }

    //home page
    public function home()
    {
        //get page seo info
        $page_seo = $this->seoRepository->getSEOPageInfo(1);
        //call for setSeo() to set seo for this page
        $this->setSeo($page_seo[0], 'website');

        $data['highlights'] = $this->categoriesApis->getHighlightedCategories(); // get the higlighted categories
        $data['collections'] = $this->tagsApis->getCollections(6); // get the collections

        if (!empty($data['collections'])) {
            foreach ($data['collections'] as $c) {
                $c->url_tag_name = $this->fixUrlName($c->name);
            }
        }

        $data['deals'] = $this->productApis->getDeals(6); // get a limited number of deals
        $data['featured'] = $this->productApis->getFeaturedProducts(); // get the featured products
        $data['best_sellers'] = $this->productApis->getBestSellers(); // get the best selling product

        // Returns the list of slides
        $data['slides'] = $this->slidesRepository->show(0);
        // Returns the list of separators
        $data['separators'] = $this->slidesRepository->showHidden(1);

        return view('frontend.pages.homepage', [
            'data' => $data,
            'now' => Carbon::now('Asia/Beirut')->format('Y-m-d'),
        ]);
    }

    // show all deals
    public function deals()
    {
        //get page seo info
        $page_seo = $this->seoRepository->getSEOPageInfo(13);
        //call for setSeo() to set seo for this page
        $this->setSeo($page_seo[0], 'product');

        $data['deals'] = $this->productApis->getDeals(false); // get all the deals

        return view('frontend.pages.deals', [
            'data' => $data,
            'now' => Carbon::now('Asia/Beirut')->format('Y-m-d'),
        ]);
    }

    // function that remove all the forbidden/special characters from the URL route name and replace them by +
    public static function fixUrlName($tag_name)
    {
        $forbidden_chars = [
            '-',
            ' ',
            '"',
            '<',
            '>',
            '#',
            '%',
            '{',
            '}',
            '|',
            '\\',
            '/',
            '//',
            '^',
            '~',
            '[',
            ']',
            '`',
            '?',
            '!',
            ',',
        ]; //set and array of all the forbidden chars
        $tag_name = str_replace($forbidden_chars, '+', $tag_name); // replace these chars by a +
        $tag_name = preg_replace('/\++/', '+', $tag_name); // replace the duplicated +++ by only 1+
        return $tag_name;
    }

    // show all collections
    public function collections()
    {
        SEO::setTitle(
            'Kamaplast | Collections | Plastic Industry, Household, Garden Furniture, Pet Packaging, Commercial'
        );
        SEO::setDescription(
            'kamaplast the number one plastic manufacturer and distributor in the Middle East'
        );
        SEO::opengraph()->setUrl('https://kama-plast.com/');
        SEO::setCanonical('https://kama-plast.com/');
        SEO::opengraph()->addProperty('type', 'articles');
        SEO::twitter()->setSite('@kamaplast');

        $data['collections'] = $this->tagsApis->getCollections(false); // get all the deals

        if (!empty($data['collections'])) {
            foreach ($data['collections'] as $c) {
                $c->url_tag_name = $this->fixUrlName($c->name);
            }
        }

        return view('frontend.pages.collections', [
            'data' => $data,
            'now' => Carbon::now('Asia/Beirut')->format('Y-m-d'),
        ]);
    }

    // show all collections
    public function categories()
    {
        //get page seo info
        $page_seo = $this->seoRepository->getSEOPageInfo(12);
        //call for setSeo() to set seo for this page
        // dd($page_seo);
        $this->setSeo($page_seo[0], 'website');

        $categories = $this->categoriesApis->show(); // get all the categories

        return view('frontend.pages.categories', ['categories' => $categories]);
    }

    // show all collections
    public function bestSellers()
    {
        SEO::setTitle(
            'Kamaplast | Best Sellers | Plastic Industry, Household, Garden Furniture, Pet Packaging, Commercial'
        );
        SEO::setDescription(
            'kamaplast the number one plastic manufacturer and distributor in the Middle East'
        );
        SEO::opengraph()->setUrl('https://kama-plast.com/');
        SEO::setCanonical('https://kama-plast.com/');
        SEO::opengraph()->addProperty('type', 'articles');
        SEO::twitter()->setSite('@kamaplast');

        $data['featured'] = $this->productApis->getFeaturedProducts(); // get the featured products

        $data['tag_details'][0]->url_tag_name = $this->fixUrlName($tag_name); //remove all the forbidden chars in the url

        $data['best_sellers'] = $this->productApis->getBestSellers(); // get the best selling product

        return view('frontend.pages.bestsellers', ['data' => $data]);
    }

    // show products of a specific collection
    public function getProductsOfCollection($tag_name, $tag_id)
    {
        SEO::setTitle(
            'Kamaplast | Collections | Plastic Industry, Household, Garden Furniture, Pet Packaging, Commercial'
        );
        SEO::setDescription(
            'kamaplast the number one plastic manufacturer and distributor in the Middle East'
        );
        SEO::opengraph()->setUrl('https://kama-plast.com/');
        SEO::setCanonical('https://kama-plast.com/');
        SEO::opengraph()->addProperty('type', 'articles');
        SEO::twitter()->setSite('@kamaplast');

        $data['tag_details'] = $this->tagsApis->getTagDetailsFromId($tag_id); // get the tag details from the tag id
        $data[
            'product_of_collection'
        ] = $this->productApis->getProductsOfCollection($tag_id); // get all the products of the selected collection  (tag_id)

        return view('frontend.pages.products-of-collection', [
            'data' => $data,
            'now' => Carbon::now('Asia/Beirut')->format('Y-m-d'),
        ]);
    }

    //about page
    public function about()
    {
        //get page seo info
        $page_seo = $this->seoRepository->getSEOPageInfo(2);
        //call for setSeo() to set seo for this page
        $this->setSeo($page_seo[0], 'website');

        // Returns about page content
        $about = $this->aboutRepository->show();

        return view('frontend.pages.about', ['about' => $about]);
    }

    //our story page
    public function ourstory()
    {
        //get page seo info
        $page_seo = $this->seoRepository->getSEOPageInfo(2);
        //call for setSeo() to set seo for this page
        $this->setSeo($page_seo[0], 'website');

        // Returns about page content
        $about = $this->aboutRepository->show();

        return view('frontend.pages.story', ['about' => $about]);
    }

    //about page
    public function odm()
    {
        //get page seo info
        $page_seo = $this->seoRepository->getSEOPageInfo(4);
        //call for setSeo() to set seo for this page
        $this->setSeo($page_seo[0], 'website');

        return view('frontend.pages.odm');
    }

    //contact page
    public function contact()
    {
        //get page seo info
        $page_seo = $this->seoRepository->getSEOPageInfo(3);
        //call for setSeo() to set seo for this page
        $this->setSeo($page_seo[0], 'website');

        return view('frontend.pages.contact');
    }

    //Get the contact form details and send the mail
    public function getContactForm(Request $request)
    {
        $name = $request->input('name');
        $phone_client = $request->input('phone');
        $email_client = $request->input('email');
        $msg_client = $request->input('message');

        // if tform is full
        if (!empty($_POST)) {
            Mail::send(
                'emails.contact',
                [
                    'name' => $name,
                    'email_client' => $email_client,
                    'phone_client' => $phone_client,
                    'msg_client' => $msg_client,
                ],
                function ($message) use ($email_client, $name) {
                    $message->from($email_client, $name);
                    $message
                        ->to('info@kama-plast.com')
                        ->subject('Email from Website');
                }
            );

            \Session::flash('msg', 'Email Sent!');

            return redirect()->back();
        }
    }

    //terms page
    public function terms()
    {
        SEO::setTitle(
            'Kamaplast | Terms | Plastic Industry, Household, Garden Furniture, Pet Packaging, Commercial'
        );
        SEO::setDescription(
            'kamaplast the number one plastic manufacturer and distributor in the Middle East'
        );
        SEO::opengraph()->setUrl('https://kama-plast.com/');
        SEO::setCanonical('https://kama-plast.com/');
        SEO::opengraph()->addProperty('type', 'articles');
        SEO::twitter()->setSite('@kamaplast');

        return view('frontend.pages.terms');
    }

    //privacy page
    public function privacy()
    {
        SEO::setTitle(
            'Kamaplast | Privacy | Plastic Industry, Household, Garden Furniture, Pet Packaging, Commercial'
        );
        SEO::setDescription(
            'kamaplast the number one plastic manufacturer and distributor in the Middle East'
        );
        SEO::opengraph()->setUrl('https://kama-plast.com/');
        SEO::setCanonical('https://kama-plast.com/');
        SEO::opengraph()->addProperty('type', 'articles');
        SEO::twitter()->setSite('@kamaplast');

        return view('frontend.pages.privacy');
    }

    //disclaimer page
    public function disclaimer()
    {
        SEO::setTitle(
            'Kamaplast | Disclaimer | Plastic Industry, Household, Garden Furniture, Pet Packaging, Commercial'
        );
        SEO::setDescription(
            'kamaplast the number one plastic manufacturer and distributor in the Middle East'
        );
        SEO::opengraph()->setUrl('https://kama-plast.com/');
        SEO::setCanonical('https://kama-plast.com/');
        SEO::opengraph()->addProperty('type', 'articles');
        SEO::twitter()->setSite('@kamaplast');

        return view('frontend.pages.disclaimer');
    }

    //careers page
    public function careers()
    {
        //get page seo info
        $page_seo = $this->seoRepository->getSEOPageInfo(6);
        //call for setSeo() to set seo for this page
        $this->setSeo($page_seo[0], 'website');

        // Return careers content
        $careers = $this->careersRepository->show();

        return view('frontend.pages.careers', ['careers' => $careers]);
    }

    //Get the careers form details and send the mail attached with a CV
    public function getCareersForm(Request $request)
    {
        $fullname = $request->input('fullname');
        $dob = $request->input('dob');
        $position = $request->input('position');
        $phone = $request->input('phone');
        $salary = $request->input('salary');
        $email = $request->input('email');
        $photo = $request->file('photo');
        $cv = $request->file('cv');
        $msg_client = $request->input('message');

        if (!empty($_POST)) {
            Mail::send(
                'emails.careers',
                [
                    'fullname' => $fullname,
                    'dob' => $dob,
                    'position' => $position,
                    'phone' => $phone,
                    'salary' => $salary,
                    'email' => $email,
                    'photo' => $photo,
                    'cv' => $cv,
                    'msg_client' => $msg_client,
                ],
                function ($message) use ($email, $fullname, $cv, $photo) {
                    $message->from($email, $fullname);
                    $message
                        ->to('hr@kama-plast.com')
                        ->subject(
                            '[Career Request] ' . $fullname . ' from Website'
                        );
                    if ($cv != null) {
                        $message->attach($cv->getRealPath(), [
                            'as' => 'cv.' . $cv->getClientOriginalExtension(),
                            'mime' => $cv->getMimeType(),
                        ]);
                    }
                    if ($photo != null) {
                        $message->attach($photo->getRealPath(), [
                            'as' =>
                                'photo.' . $photo->getClientOriginalExtension(),
                            'mime' => $photo->getMimeType(),
                        ]);
                    }
                }
            );

            \Session::flash('msg', 'Email Sent!');

            return redirect()->back();
        }
    }

    //faq page
    public function faq()
    {
        SEO::setTitle(
            'Kamaplast | FAQ | Plastic Industry, Household, Garden Furniture, Pet Packaging, Commercial'
        );
        SEO::setDescription(
            'kamaplast the number one plastic manufacturer and distributor in the Middle East'
        );
        SEO::opengraph()->setUrl('https://kama-plast.com/');
        SEO::setCanonical('https://kama-plast.com/');
        SEO::opengraph()->addProperty('type', 'articles');
        SEO::twitter()->setSite('@kamaplast');

        return view('frontend.pages.faq');
    }

    // Search bar Post ajax call
    public function getSearch(Request $request)
    {
        $result = $this->productApis->search($request->input('search'));

        return response()->json($result);
    }

    // Search result
    public function searchResult(Request $request)
    {
        //get page seo info
        $page_seo = $this->seoRepository->getSEOPageInfo(11);
        //call for setSeo() to set seo for this page
        $this->setSeo($page_seo[0], 'product');

        session(['search_input' => $request->input('txt_search')]);

        $data['input'] = $request->input('txt_search');
        $data['results'] = $this->productApis->searchResults(
            $request->input('txt_search')
        );

        return view('frontend.pages.search-result', ['data' => $data]);
    }

    // Search result
    public function getSearchResults(Request $request)
    {
        //get page seo info
        $page_seo = $this->seoRepository->getSEOPageInfo(11);
        //call for setSeo() to set seo for this page
        $this->setSeo($page_seo[0], 'product');

        // if I have a previous search in the session
        if (session()->has('search_input')) {
            $input_search = session('search_input');

            $data['input'] = $input_search;
            $data['results'] = $this->productApis->searchResults($input_search);

            return view('frontend.pages.search-result', ['data' => $data]);
        }
        //No previous search applied
        else {
            return redirect('/');
        }
    }
}
