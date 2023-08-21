<?php 

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class TagsRepository {

	/*----------------------------------
    get the list of all Tags
    ------------------------------------*/
    public function show()
    {
        $t = \DB::select("SELECT *   
			        	  FROM tags
			        	  WHERE hidden=0");
        return $t;
    }


    /*----------------------------------
    Insert a new Tag
    ------------------------------------*/
    public function add($request, $file_name, $og_img)
    {

        \DB::table('tags')->insert(
           array('name' => $request->input('name'),
           'description' =>$request->input('description'),
           'type_id' =>$request->input('tag_type'),
           'img' => $file_name,
           'seo_title' => $request->input('seo_title'),
           'seo_description' => $request->input('seo_description'),
           'seo_keywords' => $request->input('seo_keywords'),
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
    get the the data of a tag from its id
    ------------------------------------*/
    public function getTagDataFromId($tag_id)
    {
        $q = \DB::select("SELECT * 
                          FROM tags 
                          WHERE tag_id=:tag_id
                          AND hidden=0",
            array(':tag_id' => $tag_id));

        return $q;
            
    }

  
    /*----------------------------------
    Update a Tag
    ------------------------------------*/
    public function update($request, $file_name, $og_img)
    {	
		  \DB::table('tags')
           ->where('tag_id', '=', $request->input('edit_id'))
           ->update(
               array('name' => $request->input('edit_name'),
                    'description' =>$request->input('edit_description'),
                    'img' => $file_name,
                    'seo_title' => $request->input('edit_seo_title'),
                    'seo_description' => $request->input('edit_seo_description'),
                    'seo_keywords' => $request->input('edit_seo_keywords'),
                    'og_image' => $og_img,
                    'hidden' => 0,
                    'updated_at' => Carbon::now('Asia/Beirut'),
                    'updated_by' => Auth::id()
                     )
           ); 
    }


    /*----------------------------------
    Update the collection of the tag
    ------------------------------------*/
    public function updateCollectionTag($request)
    { 

      // Check if the tag is in collection or not
      $q = \DB::select("SELECT is_collection 
                                FROM tags
                                WHERE tag_id = :tag_id",
                        array(':tag_id' => $request->input('tag_id')));

      if($q[0]->is_collection==1) //tag is in collection
        $is_collection=0; //remove from collection
      elseif($q[0]->is_collection==0) //tag is NOT in collection
        $is_collection=1; //put is in collection


      \DB::table('tags')
           ->where('tag_id', '=', $request->input('tag_id'))
           ->update(
               array('is_collection' => $is_collection)
           );

      return $is_collection; 
    }



    /*----------------------------------
    Delete a Tag
    ------------------------------------*/
    public function delete($request)
    {
      try
        {

          \DB::transaction(function () use ($request){

             if($request->input('type')=="1")
                {

                  // Unlink all tags from products
                  \DB::select("DELETE 
                               FROM products_has_tags 
                               WHERE tag_id = :tag_id",
                  array(':tag_id' => $request->input('id')
                        )
                  );

                }

                elseif($request->input('type')=="2")
                {
                  // Unlink all tags from users
                  \DB::select("DELETE 
                               FROM users_has_tags 
                               WHERE tag_id = :tag_id",
                  array(':tag_id' => $request->input('id')
                        )
                  );

                }

                
                  // delete tag
                  \DB::select("DELETE 
                               FROM tags 
                               WHERE tag_id = :tag_id",
                  array(':tag_id' => $request->input('id')
                        )
                  );

          });
        
        }

        catch(QueryException $ex)
        { 
           dd($ex->getMessage());
           //dd('Error while adding a Prodcut');
        }  
      
    }


    /*----------------------------------
    get product tags
    ------------------------------------*/
    public function getProductTags($product_id)
    {
        $q = \DB::select("SELECT A.product_id, A.name, B.tag_id, C.name, C.type_id
                          FROM products as A
                          LEFT JOIN products_has_tags as B on A.product_id = B.product_id
                          JOIN tags as C ON B.tag_id = C.tag_id AND C.type_id = 1
                          WHERE A.product_id=:product_id",
            array(':product_id' => $product_id));

        return $q;     
    }

    /*----------------------------------
    get all the not used tags
    ------------------------------------*/
    public function getUnlinkTagsToProducts($product_id)
    {
      $q = \DB::select("SELECT tag_id, name
                        FROM tags 
                        WHERE type_id = 1 AND tag_id NOT IN (
                          SELECT tag_id 
                          FROM products_has_tags 
                          WHERE product_id = :product_id
                        )
                        AND hidden = 0",
                array(':product_id' => $product_id));

        return $q;
            
    }


    /*----------------------------------
    Add tag to product
    ------------------------------------*/
    public function linkToProduct($request)
    {
      $q = \DB::table('products_has_tags')->insert(
                 array('product_id' => $request->input('edit_product_id'),
                 'tag_id' => $request->input('tag'),
                 'hidden' => 0,
                 'created_by' => Auth::id(),
                 'updated_by' => Auth::id(),
                 'created_at' => Carbon::now('Asia/Beirut'),
                 'updated_at' => Carbon::now('Asia/Beirut')
                  )
                    );

        return $q;
    }


    /*----------------------------------
    Unlink a product tags
    ------------------------------------*/
    public function unlinkTagFromProduct($request)
    {

        \DB::select("DELETE 
                    FROM products_has_tags 
                    WHERE tag_id = :tag_id
                    AND product_id = :product_id",
            array(':tag_id' => $request->input('tag_id'),
                  ':product_id' => $request->input('product_id')
                  )
            );

    }
   
}