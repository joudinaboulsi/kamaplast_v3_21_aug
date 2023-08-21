<?php 

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\QueryException;

class SeoRepository {

    /*----------------------------------
    get the list of all Pages SEO
    ------------------------------------*/
    public function show()
    {
        $p = \DB::select("SELECT *
                          FROM seo_page");
        return $p;
    }


    /*----------------------------------
    get OG Twitter
    ------------------------------------*/
    public function showOGTwitter()
    {
        $p = \DB::select("SELECT og_twitter
                          FROM og_twitter
                          WHERE id=1");
        return $p;
    }


    /*----------------------------------
    Get the page seo to edit
    ------------------------------------*/
    public function getSEOPageInfo($seo_page_id)
    {
      $p = \DB::select("SELECT *
                        FROM seo_page
                        WHERE seo_page_id=:seo_page_id",
                  array(':seo_page_id' => $seo_page_id)
              );
      return $p;
    }


    /*----------------------------------
    Update a page seo
    ------------------------------------*/
    public function updateSEO($request, $og_img)
    {
      $q = \DB::table('seo_page')
           ->where('seo_page_id', '=', $request->input('seo_page_id'))
           ->update(
              array('seo_title' => $request->input('title'),
                    'seo_description' => $request->input('description'),
                    'seo_keywords' => $request->input('keywords'),
                    'og_image' => $og_img,
                    'updated_at' => Carbon::now('Asia/Beirut'),
                    'updated_by' => Auth::id()
                   )
           );
      return $q;
    }


    /*----------------------------------
    Update OG Twitter
    ------------------------------------*/
    public function updateOGTwitter($request)
    {
      $q = \DB::table('og_twitter')
           ->where('id', '=', 1)
           ->update(
              array('og_twitter' => $request->input('og_twitter'),
                    'updated_at' => Carbon::now('Asia/Beirut'),
                    'updated_by' => Auth::id()
                   )
           );
      return $q;
    }

}