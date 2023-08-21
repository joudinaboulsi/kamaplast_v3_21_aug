<?php 

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\QueryException;

class VariantsRepository {

   
    /*----------------------------------
    get the product Variants list
    ------------------------------------*/
    public function show($product_id)
    {
        $q = \DB::select("SELECT A.variant_id, A.product_id, A.img, A.sku, A.regular_price, A.sales_price, A.sales_price_start_date, A.sales_price_end_date, A.stock_qty, A.stock_status_id, A.is_main, A.hidden, GROUP_CONCAT(C.name SEPARATOR ', ') as item_name, COUNT(C.attribute_item_id) as items_nb, group_concat(D.img) as item_img, group_concat(E.color) as color
                          FROM variants as A
                          LEFT JOIN variants_has_attribute_items as B ON A.variant_id = B.variant_id
                          LEFT JOIN attribute_items as C ON B.attribute_item_id = C.attribute_item_id
                          LEFT JOIN attribute_items as D ON B.attribute_item_id = D.attribute_item_id AND D.img!='null'
                          LEFT JOIN attribute_items as E ON B.attribute_item_id = E.attribute_item_id AND E.color!='null'
                          WHERE A.product_id=:product_id
                          GROUP BY A.variant_id;",
            array(':product_id' => $product_id));
        return $q;
    }

    /*----------------------------------
    Add a product variant
    ------------------------------------*/
    public function add($request)
    {
        try
        {
          \DB::transaction(function () use ($request)
          {

            // Returns product id after insterting a product in products table
            $variant_id = \DB::table('variants')->insertGetId(
                             array('product_id' =>$request->input('edit_product_id'),
                             'sku' =>$request->input('variant_sku'),
                             'is_main' => 0,
                             'regular_price' => $request->input('variant_regular_price'),
                             'sales_price' => null,
                             'sales_price_start_date' => null,
                             'sales_price_end_date' => null,
                             'stock_qty' => $request->input('variant_stock_quantity'),
                             'stock_status_id' => $request->input('variant_stock_status'),
                             'width' => $request->input('variant_width'),
                             'weight' => $request->input('variant_weight'),
                             'length' => $request->input('variant_length'),
                             'height' => $request->input('variant_height'),
                             'diameter' => $request->input('variant_diameter'),
                             'hidden' => 0,
                             'created_by' => Auth::id(),
                             'updated_by' => Auth::id(),
                             'created_at' => Carbon::now('Asia/Beirut'),
                             'updated_at' => Carbon::now('Asia/Beirut')
                              )
                  );

            //Build attribute table dynamically
            $attribute_items = []; //declare an empty array
            for($i=0; $i< $request->input('count'); $i++)
            {
                  // build the component table
                 array_push($attribute_items, array('variant_id' => $variant_id,
                                          'attribute_item_id' => $request->input('attribute'.$i),
                                          ));

            }

            // insert all the new atribute items
           \DB::table('variants_has_attribute_items')->insert($attribute_items); // Query Builder

          });

        }

        catch(QueryException $ex)
        { 
           dd($ex->getMessage());
           //dd('Error while adding a Prodcut');
        }   

    }  



        /*----------------------------------
    Updates the Variant
    ------------------------------------*/
    public function update($request)
    {
         
        try
        {
          \DB::transaction(function () use ($request)
          {
            //update attribute name
            \DB::table('variants')
            ->where('variant_id', '=', $request->input('edit_variant_id'))
            ->update(
                array('regular_price' => $request->input('edit_variant_regular_price'),
                      'sku' => $request->input('edit_variant_sku'),
                      'stock_qty' => $request->input('edit_variant_stock_quantity'),
                      'weight' => $request->input('edit_variant_weight'),
                      'length' => $request->input('edit_variant_length'),
                      'width' => $request->input('edit_variant_width'),
                      'height' => $request->input('edit_variant_height'),
                      'diameter' => $request->input('edit_variant_diameter'),
                      'stock_status_id' => $request->input('edit_variant_stock_status')
               )
             );

            // delete all atribute items linked to the variant
            \DB::select("DELETE 
                         FROM variants_has_attribute_items 
                         WHERE variant_id = :variant_id",
            array(':variant_id' => $request->input('edit_variant_id')
                  )
            );


            //Build attribute items list selected
            $attribute_items = []; //declare an empty array
            for($i=0; $i< $request->input('count'); $i++)
            {
                if($request->input('attribute'.$i)!=null) // if we select a specific item, we add it the the array for insert
                {
                  // build the component table
                  array_push($attribute_items, array('variant_id' => $request->input('edit_variant_id'),
                                            'attribute_item_id' => $request->input('attribute'.$i),
                                            ));
                }

            }

            //If the number of attributes of the product is different than the number of attribute items linked to the product (excluding NONE). 
            //Then we have to edit the status of the variant to Unpublished
            if($request->input('count') != count($attribute_items))
            {
              
              // delete all atribute items linked to the variant
              $product_id = \DB::select("SELECT product_id FROM variants 
                           WHERE variant_id = :variant_id",
                          array(':variant_id' => $request->input('edit_variant_id')
                                )
                      );

              \DB::table('products')
               ->where('product_id', '=', $product_id[0]->product_id)
               ->update(
                   array('hidden' => 1)
               );

              //Update variant publishing status
              \DB::table('variants')
                 ->where('variant_id', '=', $request->input('edit_variant_id'))
                 ->update(
                     array('hidden' => 1)
                 );  
            }

            // insert all the new atribute items
           \DB::table('variants_has_attribute_items')->insert($attribute_items); // Query Builder

          });

        }

        catch(QueryException $ex)
        { 
           dd($ex->getMessage());
           //dd('Error while adding a Prodcut');
        }   
        

    }


        /*----------------------------------
    Delete a product Variant
    ------------------------------------*/
    public function delete($request)
    {

        \DB::transaction(function () use ($request)
        {
            
            //Delete variant attributes
            \DB::select("DELETE 
                         FROM variants_has_attribute_items
                         WHERE variant_id = :variant_id",
            array(':variant_id' => $request->input('id'))
           ); 


            //Delete variant from variant table
            \DB::select("DELETE 
                         FROM variants 
                         WHERE variant_id = :variant_id",
            array(':variant_id' => $request->input('id'))
           ); 


        });

    }


    /*----------------------------------
    Delete all product Variants
    ------------------------------------*/
    public function deleteAll($request)
    {

      \DB::transaction(function () use ($request)
      {
                
         //select all variants IDs
          $variants_id = \DB::select("SELECT variant_id
                              FROM variants
                              WHERE product_id = :product_id and is_main=0",
                          array(':product_id' => $request->input('id'))
                          );


          //Delete all product variants
          foreach ($variants_id as $v) {
              //Delete linked items to each variant
              \DB::select("DELETE 
                           FROM variants_has_attribute_items
                           WHERE variant_id = :variant_id",
              array(':variant_id' => $v->variant_id)
             ); 

              \DB::select("DELETE 
                       FROM variants 
                       WHERE variant_id = :variant_id",
              array(':variant_id' => $v->variant_id)
              ); 

          }
          

      });

    }



    /*----------------------------------
    Gets the Variant infos from Variant id
    ------------------------------------*/
    public function getVariantFromId($variant_id)
    {
         
        $q=\DB::select("SELECT *
                      FROM variants
                      WHERE variant_id=:variant_id",
            array(':variant_id' => $variant_id 
                 )
          );

        return $q;
    
    }


    /*----------------------------------
    Gets the Variant infos from Variant id
    ------------------------------------*/
    public function getVariantAttrItemsFromId($variant_id)
    {
        $q = \DB::select("SELECT A.attribute_item_id, B.attribute_id, B.name
                      FROM variants_has_attribute_items as A
                      JOIN attribute_items as B ON A.attribute_item_id = B.attribute_item_id
                      WHERE A.variant_id=:variant_id",
            array(':variant_id' => $variant_id 
                 )
          );

        return $q;
        
    }


    /*----------------------------------
    Add a product variant Promotion
    ------------------------------------*/
    public function editPromo($request)
    {
        // If we want to stop the promo of a variant
        if($request->has('stop_promo'))
        {
          \DB::table('variants')
           ->where('variant_id', '=', $request->input('variant_id'))
           ->update(
               array('sales_price' => null,
               'sales_price_start_date' => null,
               'sales_price_end_date' => null,
                    )
           ); 
        }

        // If we only want to add/change promo
        elseif($request->has('submit_edit_variant_promo'))
        {
          $variant_promo_daterange = explode(' - ', $request->input('variant_promo_daterange')); // get the start and end date

           \DB::table('variants')
           ->where('variant_id', '=', $request->input('variant_id'))
           ->update(
               array('sales_price' => $request->input('variant_promo_price'),
               'sales_price_start_date' => date("Y-m-d", strtotime($variant_promo_daterange[0])),
               'sales_price_end_date' => date("Y-m-d", strtotime($variant_promo_daterange[1])) 
                     )
           ); 
        }
    }  




    /*----------------------------------
    Publish unpublish a product Variant
    ------------------------------------*/
    public function publish($request)
    { 
      // Check if the variant is published or not
      $variant = \DB::select("SELECT variant_id, product_id, is_main, hidden 
                                FROM variants
                                WHERE variant_id = :variant_id",
                        array(':variant_id' => $request->input('id')));

      //Get attribute items linked to variant
      $variant_attribute_items = \DB::select("SELECT attribute_item_id
                              FROM variants_has_attribute_items
                              WHERE variant_id = :variant_id",
                        array(':variant_id' => $request->input('id')));
      
      
      //Get list of attributes linked to product
      $product_attributes = \DB::select("SELECT A.attribute_id, A.product_id, B.variant_id
                                                        FROM attributes as A
                                                        JOIN variants as B ON A.product_id = B.product_id
                                                        WHERE B.variant_id = :variant_id",
                        array(':variant_id' => $request->input('id')));


        // If Number of variant attribute items equals Number of attributes, then update the status value normally
        if(count($variant_attribute_items) == count($product_attributes))
        {
          $data['is_main'] = 0;

          if($variant[0]->hidden == 1) //product is unpublished
            $data['publish'] = 0; //publish
          elseif($variant[0]->hidden == 0) //product is published
            $data['publish'] = 1; //unpublish

          if($variant[0]->is_main == 0)
          {
            //Update variant publishing status
            \DB::table('variants')
               ->where('variant_id', '=', $request->input('id'))
               ->update(
                   array('hidden' => $data['publish'])
               );
          }
          else
          {
            \DB::transaction(function () use ($request, $data, $variant)
            {
              //Update variant publishing status
              \DB::table('variants')
                 ->where('variant_id', '=', $request->input('id'))
                 ->update(
                     array('hidden' => $data['publish'])
                 );

              \DB::table('products')
                 ->where('product_id', '=', $variant[0]->product_id)
                 ->update(
                     array('hidden' => 1)
                 );  
            });
            $data['is_main'] = 1;    
          }
        }
      
      return $data;
    }




   
}