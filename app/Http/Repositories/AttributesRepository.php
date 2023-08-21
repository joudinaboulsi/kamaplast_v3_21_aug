<?php 

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Auth;

class AttributesRepository {


    /*----------------------------------
    get product Attributes
    ------------------------------------*/
    public function show($product_id)
    {
        $q = \DB::select("SELECT A.attribute_id, A.name, group_concat(B.name) as attribute_names
                          FROM attributes as A
                          LEFT JOIN attribute_items as B ON A.attribute_id = B.attribute_id AND B.hidden=0
                          WHERE product_id=:product_id AND is_main=0
                          AND A.hidden=0
                          GROUP BY A.attribute_id",
            array(':product_id' => $product_id));

        return $q;
            
    }

    /*----------------------------------
    get all product variants Attributes
    ------------------------------------*/
    public function showAll($product_id)
    {
        $q = \DB::select("SELECT A.attribute_id, A.name, group_concat(B.name) as attribute_names
                          FROM attributes as A
                          LEFT JOIN attribute_items as B ON A.attribute_id = B.attribute_id AND B.hidden=0
                          WHERE product_id=:product_id
                          AND A.hidden=0
                          GROUP BY A.attribute_id",
            array(':product_id' => $product_id));

        return $q;
            
    }


        /*----------------------------------
    Add a product Attribute
    ------------------------------------*/
    public function add($request)
    {
        try
        {
          \DB::transaction(function () use ($request){


              // Returns attribute id after insterting an attribute in attributes table
              $attribute_id = \DB::table('attributes')->insertGetId(
                               array('name' =>$request->input('attribute'),
                               'product_id' =>$request->input('edit_product_id'),
                               'description' => null,
                               'hidden' => 0,
                               'created_by' => Auth::id(),
                               'updated_by' => Auth::id(),
                               'created_at' => Carbon::now('Asia/Beirut'),
                               'updated_at' => Carbon::now('Asia/Beirut')
                                )
                    );

              \DB::table('variants')
              ->where('product_id', '=', $request->input('edit_product_id'))
              ->update(
                   array('hidden' => 1)
               ); 

              \DB::table('products')
               ->where('product_id', '=', $request->input('edit_product_id'))
               ->update(
                   array('hidden' => 1)
               ); 
            
              // Check if the attribute has items
              if($request->input('items') != NULL)
               {
                   $items = explode(',', $request->input('items')); //declare an array of the values
                   
                   $values= []; //declare an empty array
                   $is_first_attribute_item = 0;
                   foreach($items as $i)
                   {
                     // build the component table
                     array_push($values, array('attribute_id' => $attribute_id,
                                              'name' => $i,
                                              'hidden' => 0,
                                              'created_by' => Auth::id(),
                                              'updated_by' => Auth::id(),
                                              'created_at' => Carbon::now('Asia/Beirut'),
                                              'updated_at' => Carbon::now('Asia/Beirut')
                                              ));
                   }

                 // insert all the atribute items
                 \DB::table('attribute_items')->insert($values); // Query Builder
                }

          });

          return $request->session()->pull('product_id');
        }

        catch(QueryException $ex)
        { 
           dd($ex->getMessage());
           //dd('Error while adding a Prodcut');
        }   

    }


        /*----------------------------------
    update an attribute
    ------------------------------------*/
    public function update($request)
    {

        try
        {

          \DB::transaction(function () use ($request){

            //update attribute name
            \DB::table('attributes')
            ->where('attribute_id', '=', $request->input('edit_attribute_id'))
            ->update(
                 array('name' => $request->input('edit_attribute_name'))
             );


            // delete all existing atribute items
            \DB::select("DELETE 
                         FROM attribute_items 
                         WHERE attribute_id = :attr_id
                         AND attribute_item_id NOT IN (SELECT attribute_item_id FROM variants_has_attribute_items)",
                    array(':attr_id' => $request->input('edit_attribute_id')
                          )
            );

            // Get list of all linked Items
            $listoflinkeditems = \DB::select("SELECT DISTINCT(B.name) FROM variants_has_attribute_items as A 
                                              JOIN attribute_items as B ON A.attribute_item_id = B.attribute_item_id  
                                              WHERE B.attribute_id = :attr_id",
                                  array(':attr_id' => $request->input('edit_attribute_id')
                                        )
            );


            $items = explode(',', $request->input('edit_attr_items')); //declare an array of the values
 
           
            $items_to_insert= []; //declare an empty array that will contain the list of items that we will insert to the attribute
            //Parse the list of attribute items that we want to edit
            foreach($items as $i)
             {  $bool = 0;
                //Parse the List of Linked Items from the selected attribute
                foreach ($listoflinkeditems as $key => $value) {
                    // Check if the attribute items that we want to add exists in the list of linked attribute items
                    if($i == $value->name)
                    {
                        // Do not add this item to the $items_to_insert. 
                        $bool = 1;
                    }                     
                      
                }
                //If bool = 0 , add the item to the list of $items_to_insert
                if($bool == 0)
                {
                    // build the component table
                      array_push($items_to_insert, array('attribute_id' => $request->input('edit_attribute_id'),
                                    'name' => $i,
                                    'hidden' => 0,
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
                                    'created_at' => Carbon::now('Asia/Beirut'),
                                    'updated_at' => Carbon::now('Asia/Beirut')
                                    ));
                }
             }

            $items_to_keep = []; // decalare an empty array that will contain the list of items that we will not delete from the attribute list
            //Parse the list of LinkedItems
            foreach ($listoflinkeditems as $key => $value) 
            {
              $bool = 0;
              //Parse the list of Items that we want to edit
              foreach($items as $i)
              {
                  // Check if the attribute items that we want to add exists in the list of linked attribute items
                  if($i == $value->name)
                  {
                      // Do not add this item to the $items_to_keep. 
                      $bool = 1;
                      
                  }
              }
              //If bool = 0 , add the item to the list of $items_to_keep
              // This scenario means that the attribute item linked that we are parsing has not been found in the list of items that we want to add.
              if($bool == 0)
              {
                  array_push($items_to_keep, $value->name);  
              }
            }

   

           // insert all the new atribute items
           \DB::table('attribute_items')->insert($items_to_insert); // Query Builder

           $request->session()->put('items_to_keep', $items_to_keep);

          });

          return $request->session()->pull('items_to_keep');
        
        }

        catch(QueryException $ex)
        { 
           //dd($ex->getMessage());
           //dd('Error while adding a Prodcut');
        }   

        
    }


    /*----------------------------------
    Delete a product Attribute
    ------------------------------------*/
    public function delete($request)
    { 

      $error= 0;
        try
        {

          \DB::transaction(function () use ($request){
  
                // delete all attributes items of the attribute
                \DB::select("DELETE 
                             FROM attribute_items 
                             WHERE attribute_id = :attr_id",
                array(':attr_id' => $request->input('id')
                      )
                );

                // delete all existing atributes
                \DB::select("DELETE 
                             FROM attributes 
                             WHERE attribute_id = :attr_id",
                array(':attr_id' => $request->input('id')
                      )
                );
          
          });
        
        }

        catch(QueryException $ex)
        { 
           $error = 1;
          // return($ex->getMessage());
        }  

        return $error;
    }


    /*----------------------------------
    get product Main Attribute
    ------------------------------------*/
    public function showMain($product_id)
    {
        $q = \DB::select("SELECT * 
                          FROM attributes as A
                          WHERE product_id=:product_id AND is_main != 0",
            array(':product_id' => $product_id));

        return $q;
            
    }


    /*----------------------------------
    get the product attributes items
    ------------------------------------*/
    public function showAttributeItems($product_id)
    {
        $q = \DB::select("SELECT A.attribute_id, A.product_id, A.name as attribute_name, B.name as item_name, B.attribute_item_id, B.color
                          FROM attributes as A
                          JOIN attribute_items as B ON A.attribute_id = B.attribute_id
                          WHERE A.product_id=:product_id",
            array(':product_id' => $product_id));
        return $q;
    }


    /*----------------------------------
    get the product main attribute items
    ------------------------------------*/
    public function showMainAttributeItems($product_id)
    {
        $q = \DB::select("SELECT A.attribute_item_id, A.name as item_name, A.img, A.color, B.name as attribute_name, B.attribute_id
                          FROM attribute_items as A
                          JOIN attributes as B ON B.product_id=:product_id AND A.attribute_id=B.attribute_id AND B.is_main != 0",
                          
            array(':product_id' => $product_id));
        return $q;
    }


    /*----------------------------------
    Get the type of the main attribute - COLOR or IMAGE
    ------------------------------------*/
    public function getMainAttributeType($product_id)
    {
      $q = \DB::select("SELECT is_main
                        FROM attributes
                        WHERE product_id = :product_id
                        AND hidden = 0
                        AND is_main != 0",
                          
            array(':product_id' => $product_id));
        return $q;

    }


    /*----------------------------------
    Add a product main Attribute item
    ------------------------------------*/
    public function addMainAttributeItem($request,$img)
    {
        try
        {
          \DB::transaction(function () use ($request,$img){


            // if the main attribute is an image  
              if($request->input('main_attribute_type') == 1) 
              {
                $image = $img;
                $color = NULL; 
              }

              // if the main attribute is a color
              elseif($request->input('main_attribute_type') == 2) 
              {
                 $image = NULL;
                 $color = $request->input('color_att') ; 
              }



            // if we don't have a main attribute
            if($request->input('attribute_id') == null)
            {

            // Returns attribute id after inserting the main attribute in attributes table
            $main_attribute_id = \DB::table('attributes')->insertGetId(
                             array('name' =>'Color',
                                   'product_id' =>$request->input('product_id'),
                                   'description' => null,
                                   'is_main'=> $request->input('main_attribute_type'),
                                   'hidden' => 0,
                                   'created_by' => Auth::id(),
                                   'updated_by' => Auth::id(),
                                   'created_at' => Carbon::now('Asia/Beirut'),
                                   'updated_at' => Carbon::now('Asia/Beirut')
                              )
                  );

             // insert main atribute item
             \DB::table('attribute_items')->insert(
               array('attribute_id' => $main_attribute_id,
                     'name' => $request->input('main_attribute_item'),
                     'img' => $image,
                     'color' => $color,
                     'hidden' => 0,
                     'created_by' => Auth::id(),
                     'updated_by' => Auth::id(),
                     'created_at' => Carbon::now('Asia/Beirut'),
                     'updated_at' => Carbon::now('Asia/Beirut')
                )
                  );

             \DB::table('variants')
              ->where('product_id', '=', $request->input('product_id'))
              ->update(
                   array('hidden' => 1)
               ); 

              \DB::table('products')
               ->where('product_id', '=', $request->input('product_id'))
               ->update(
                   array('hidden' => 1)
               ); 

            }

            else
            {
              // insert main atribute item
              \DB::table('attribute_items')->insert(
               array('attribute_id' => $request->input('attribute_id'),
                     'name' => $request->input('main_attribute_item'),
                     'img' => $image,
                     'color' => $color,
                     'hidden' => 0,
                     'created_by' => Auth::id(),
                     'updated_by' => Auth::id(),
                     'created_at' => Carbon::now('Asia/Beirut'),
                     'updated_at' => Carbon::now('Asia/Beirut')
                )
                  );
            }



          });

        }
        

        catch(QueryException $ex)
        { 
           dd($ex->getMessage());
           //dd('Error while adding a Prodcut');
        }   

    }


    /*----------------------------------
    update main attribute name
    ------------------------------------*/
    public function updateMainAttributeName($request)
    {
      //update attribute name
      \DB::table('attributes')
      ->where('attribute_id', '=', $request->input('e_id'))
      ->update(
           array('name' => $request->input('e_attr_name')
               )
       );
    }


    /*----------------------------------
    get the main attribute item infos from item id
    ------------------------------------*/
    public function getMainAttributeItemFromId($attribute_item_id)
    {
      $q = \DB::select("SELECT *
                          FROM attribute_items
                          WHERE attribute_item_id=:attribute_item_id",
            array(':attribute_item_id' => $attribute_item_id));
        return $q;
    }


    /*----------------------------------
    update main attribute item
    ------------------------------------*/
    public function updateMainAttributeItem($request,$img)
    {
      if($request->input('edit_color_att') !== NULL)
        $color = $request->input('edit_color_att');
      else
        $color = NULL;

      //update attribute name
      \DB::table('attribute_items')
      ->where('attribute_item_id', '=', $request->input('edit_id_attribute_item'))
      ->update(
           array('name' => $request->input('edit_attribute_item'),
                 'img' => $img,
                 'color' => $color
               )
       );
        
    }


    /*----------------------------------
    Delete Main Attribute Item
    ------------------------------------*/
    public function deleteMainAttributeItem($request)
    {
        $error= 0;
        try
        {

          \DB::transaction(function () use ($request){

            $attribute_id = \DB::select("SELECT attribute_id
                                  FROM attribute_items
                                  WHERE attribute_item_id=:attribute_item_id",
                          array(':attribute_item_id' => $request->input('id')));

            $count = \DB::select("SELECT count(attribute_item_id) as count
                                  FROM attribute_items
                                  WHERE attribute_id=:attribute_id",
                          array(':attribute_id' => $attribute_id[0]->attribute_id));

            if ($count[0]->count <= 1) //if this is the last record of main attribute items
            {

              // delete Main Attribute Item from attribute_items table
              \DB::select("DELETE 
                           FROM attribute_items 
                           WHERE attribute_item_id=:attribute_item_id1",
              array(':attribute_item_id1' => $request->input('id')
                    )
              );

              // delete main attribute from atributes table
              \DB::select("DELETE 
                           FROM attributes 
                           WHERE attribute_id = :attr_id",
              array(':attr_id' => $attribute_id[0]->attribute_id
                    )
              );

            }

            else
            {
              // delete Main Attribute Item from attribute_items table
              \DB::select("DELETE 
                           FROM attribute_items 
                           WHERE attribute_item_id=:attribute_item_id2",
              array(':attribute_item_id2' => $request->input('id')
                    )
              );
            }

          });
        
        }

        catch(QueryException $ex)
        { 
           $error = 1;
          // return($ex->getMessage());
        }
          
         return $error;

    }

   
    /*----------------------------------
    get the attribute infos from attribute id
    ------------------------------------*/
    public function getAttributeFromId($attribute_id)
    {
      $q = \DB::select("SELECT A.attribute_id, A.name as attribute, group_concat(B.name) as attr_items
                          FROM attributes as A
                          JOIN attribute_items as B ON A.attribute_id = B.attribute_id
                          WHERE A.attribute_id=:attribute_id
                          GROUP BY A.attribute_id",
            array(':attribute_id' => $attribute_id));

        return $q;
        
    }



   
}