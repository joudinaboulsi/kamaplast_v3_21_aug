<?php 

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class BlogRepository {


	/*----------------------------------
    get the list of all News
    ------------------------------------*/
    public function show()
    {
        $q = \DB::select("SELECT *   
			        	  FROM news
			        	  WHERE hidden=0");
        return $q;
    }


    /*--------------------------------------------- News ----------------------------------------------*/

    /*----------------------------------
    Insert News
    ------------------------------------*/
    public function addNews($request, $img, $og_img)
    {
        \DB::table('news')->insert(
           array('title' => $request->input('title'),
                 'date' => $request->input('date'),
                 'image' => $img,
                 'content' => $request->input('content'),
                 'link' => $request->input('link'),
                 'seo_title' => $request->input('edit_seo_title'),
                 'seo_description' => $request->input('edit_seo_description'),
                 'seo_keywords' => $request->input('edit_seo_keywords'),
                 'og_image' => $og_img,
                 'hidden' => 0,
                 'created_at' => Carbon::now('Asia/Beirut'),
                 'created_by' => Auth::id(),
                 'updated_at' => Carbon::now('Asia/Beirut'),
                 'updated_by' => Auth::id()
            )
        );        
    }


    /*----------------------------------
    get News Details
    ------------------------------------*/
    public function showDetails($id)
    {
        $q = \DB::select("SELECT * 
                          FROM news 
                          WHERE news_id=:id",
            array(':id' => $id));
        return $q;    
    }

  
    /*----------------------------------
    Update News
    ------------------------------------*/
    public function updateNews($request, $img, $og_img)
    {	
		  \DB::table('news')
           ->where('news_id', '=', $request->input('news_id'))
           ->update(
               array('title' => $request->input('title'),
                     'date' =>$request->input('date'),
                     'image' => $img,
                     'content' =>$request->input('content'),
                     'link' =>$request->input('link'),
                     'seo_title' => $request->input('seo_title'),
                     'seo_description' => $request->input('seo_description'),
                     'seo_keywords' => $request->input('seo_keywords'),
                     'og_image' => $og_img,
                     'updated_at' => Carbon::now('Asia/Beirut'),
                     'updated_by' => Auth::id()
                     )
           ); 
    }


    /*----------------------------------
    get Blog Tags
    ------------------------------------*/
    public function getBlogTags($news_id)
    {
       $q = \DB::select("SELECT A.news_id, A.tag_id, B.name as tag_name
                          FROM news_has_tags as A
                          JOIN tags as B ON A.tag_id = B.tag_id
                          WHERE A.news_id=:news_id",
                  array(':news_id' => $news_id) );
        return $q;   
    }


    /*----------------------------------
    get available Tags
    ------------------------------------*/
    public function getTagsList($news_id)
    {
       $q = \DB::select("SELECT * 
                         FROM tags 
                         WHERE type_id=3
                         AND tag_id NOT IN (SELECT tag_id 
                                               FROM news_has_tags 
                                               WHERE news_id=:news_id)",
            array(':news_id' => $news_id) );
        return $q;   
    }


    /*----------------------------------
    Add tag to news
    ------------------------------------*/
    public function addTag($request)
    {
      $q = \DB::table('news_has_tags')->insert(
                 array('news_id' => $request->input('t_news_id'),
                       'tag_id' => $request->input('tag') 
                 )
            );

        return $q;
    }


    /*----------------------------------
    Unlink a news tags
    ------------------------------------*/
    public function deleteTag($request)
    {
        \DB::select("DELETE 
                    FROM news_has_tags 
                    WHERE tag_id = :tag_id
                    AND news_id = :news_id",
            array(':tag_id' => $request->input('tag_id'),
                  ':news_id' => $request->input('news_id')
                  )
            );
    }


    /*----------------------------------
    Delete News
    ------------------------------------*/
    public function deleteNews($request)
    {
      \DB::table('news')
           ->where('news_id', '=', $request->input('id'))
           ->update(
               array('hidden' => 1,
                     'updated_at' => Carbon::now('Asia/Beirut'),
                     'updated_by' => Auth::id()
                     )
           ); 
    }
   
}