<?php 

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Auth;

class CategoryRepository {

	/*----------------------------------
    get the list of all Category
    ------------------------------------*/
    public function show()
    {       
        $c = \DB::select("SELECT *   
			        	  FROM categories
			        	  WHERE hidden=0");
        return $c;
    }

    /*----------------------------------
    get the list categories that are not used for this product
    ------------------------------------*/
    public function showUnusedCategories($product_id)
    {
        $q = \DB::select("SELECT A.category_id, B.category_id as used_category_id , A.*  
                  FROM categories as A
                  LEFT JOIN products_has_categories as B ON A.category_id = B.category_id AND B.product_id = :product_id
                  WHERE A.hidden=0",
             array(':product_id' => $product_id));
        return $q;
    }


    /*----------------------------------
    Insert a new Category
    ------------------------------------*/
    public function add($request, $file_name, $og_img)
    {
      //If the category does not have a parent
      if ($request->input('parent') == 0)
        $parent_id = NULL;
      else
        $parent_id = $request->input('parent');

      try{
        \DB::table('categories')->insert(
           array('name' => $request->input('name'),
           'description' =>$request->input('description'),
           'parent_id' =>$parent_id,
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
      catch(QueryException $ex)
        {
           dd('Error while adding a Category');
        }
        
    }


    /*----------------------------------
    get the the data of a category from its id
    ------------------------------------*/
    public function getCategoryDataFromId($category_id)
    {
        $q = \DB::select("SELECT * 
                          FROM categories 
                          WHERE category_id=:category_id
                          AND hidden=0",
            array(':category_id' => $category_id));

        return $q;
            
    }

    /*----------------------------------
    Update a Category
    ------------------------------------*/
    public function update($request, $file_name, $og_img)
    {	
		  \DB::table('categories')
           ->where('category_id', '=', $request->input('edit_id'))
           ->update(
               array('name' => $request->input('edit_name'),
               'description' =>$request->input('edit_description'),
               'parent_id' =>$request->input('edit_parent'),
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
    Get list of PARENT categories
    ------------------------------------*/
    public function getParentCategories()
    { 
      $q = \DB::select("SELECT * 
                          FROM categories 
                          WHERE parent_id IS NULL");

        return $q;
    }

    /*----------------------------------
    Get a Category list of Children From category ID
    ------------------------------------*/
    public function getCategoryChildFromCategoryId($category_id)
    { 
      $q = \DB::select("SELECT * 
                          FROM categories 
                          WHERE parent_id=:category_id
                          AND hidden=0",
            array(':category_id' => $category_id));

        return $q;
    }


    /*----------------------------------
    Update a Child's parent_id
    ------------------------------------*/
    public function updateParentId($child_id,$parent_id)
    { 
      \DB::table('categories')
           ->where('category_id', '=', $child_id)
           ->update(
               array(
               'parent_id' => $parent_id,
                     )
           ); 
    }


    /*----------------------------------
    Highlight the selected category
    ------------------------------------*/
    public function highlightCategory($request)
    { 

      // Check if the category is higlighted or not
      $q = \DB::select("SELECT highlight 
                                FROM categories
                                WHERE category_id = :category_id",
                        array(':category_id' => $request->input('category_id')));

      if($q[0]->highlight==1) //category is highlighted
        $highlight=0; //remove highlight
      elseif($q[0]->highlight==0) //category is not highlighted
        $highlight=1; //highlight


      \DB::table('categories')
           ->where('category_id', '=', $request->input('category_id'))
           ->update(
               array(
               'highlight' => $highlight,
                     )
           );

      return $highlight; 
    }

    
    /*----------------------------------
    Delete a Category
    ------------------------------------*/
    public function delete($category_id)
    {
		  

      \DB::transaction(function () use ($category_id)
        {

          //delete all the links between the selected category and the products
          \DB::select("DELETE 
                       FROM products_has_categories 
                       WHERE category_id = :category_id",
          array(':category_id' => $category_id)
          );


          // delete the category
          \DB::select("DELETE 
                       FROM categories 
                       WHERE category_id = :category_id_2",
          array(':category_id_2' => $category_id)
          );


        });
 
    }



     /*----------------------------------------------------------------------------------
    -----  PRODCUCTS CATEGORIES
    -----------------------------------------------------------------------------------*/

    /*----------------------------------
    get product categories
    ------------------------------------*/
    public function getProductCategories($product_id)
    {
        $q = \DB::select("SELECT A.product_id, A.name, B.category_id, C.name
                          FROM products as A
                          LEFT JOIN products_has_categories as B on A.product_id = B.product_id
                          JOIN categories as C ON B.category_id = C.category_id
                          WHERE A.product_id=:product_id",
            array(':product_id' => $product_id));

        return $q;
            
    }


    /*----------------------------------
    link a Category to a product
    ------------------------------------*/
    public function linkCategories($request)
    {
        //Build category table dynamically
        $category_list = []; //declare an empty array
        foreach($request->input('categories') as $categ)
        {
                           // build the component table
             array_push($category_list, array('product_id' => $request->input('product_id'),
                                              'category_id' => $categ,
                                              'created_by' => Auth::id(),
                                              'updated_by' => Auth::id(),
                                              'created_at' => Carbon::now('Asia/Beirut'),
                                              'updated_at' => Carbon::now('Asia/Beirut')
                                      ));

        }

        // insert all the new atribute items
       \DB::table('products_has_categories')->insert($category_list); // Query Builder
    } 


    /*----------------------------------
    Unlink a Category from a product
    ------------------------------------*/
    public function unlinkCategories($request)
    {
        \DB::select("DELETE 
                    FROM products_has_categories 
                    WHERE category_id = :category_id
                    AND product_id = :product_id",
            array(':category_id' => $request->input('category_id'),
                  ':product_id' => $request->input('product_id')
                  )
            );
    } 


   
}