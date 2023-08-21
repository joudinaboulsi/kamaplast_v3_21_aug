<?php

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\QueryException;

class ProductRepository
{
    /*----------------------------------------------------------------------------------
    -----  PRODCUCTS GENERAL INFORMATION
    -----------------------------------------------------------------------------------*/

    /*----------------------------------
    get the list of all Products
    ------------------------------------*/
    public function show()
    {
        $p = \DB::select("SELECT A.*, B.cat_name, A.brand, A.enable_stock_mgmt, C.min_stock_qty, C.stock_status_id, C.featured
                          FROM ( 
                            SELECT A.product_id, A.prod_name, A.primary_img as primary_img, A.sales_price as sales_price, MIN(A.current_price) as min_price, MAX(A.current_price) as max_price, A.hidden, A.enable_stock_mgmt, A.brand, A.position
                            FROM
                            (
                              SELECT A.product_id, A.name as prod_name, A.hidden, A.enable_stock_mgmt, A.position,
                              B.img as primary_img, 
                              C.regular_price, C.sales_price, IF(C.sales_price IS NOT NULL, C.sales_price, C.regular_price) as current_price, 
                              D.name as brand
                              FROM products as A
                              LEFT JOIN product_images as B  ON A.product_id = B.product_id AND B.is_primary = 1 AND B.hidden = 0
                              LEFT JOIN variants as C ON A.product_id = C.product_id AND C.hidden = 0
                              LEFT JOIN brands as D ON A.brand_id = D.brand_id) as A
                              GROUP BY A.product_id
                            ) as A

                          LEFT JOIN 
                          (
                            SELECT A.product_id, GROUP_CONCAT(E.name) as cat_name
                            FROM products as A
                            LEFT JOIN products_has_categories as D ON A.product_id = D.product_id
                            LEFT JOIN categories as E on E.category_id = D.category_id AND E.hidden = 0
                            GROUP BY A.product_id
                          ) as B ON A.product_id = B.product_id
                          
                          LEFT JOIN 
                          (
                            SELECT A.product_id, A.featured, MIN(A.stock_qty) as min_stock_qty, MIN(A.stock_status_id) as stock_status_id
                            FROM
                            (                          
                              SELECT A.product_id, A.featured, IF(B.stock_qty IS NOT NULL, B.stock_qty, 0) as stock_qty, IF(B.stock_status_id IS NOT NULL, B.stock_status_id, 0) as stock_status_id
                              FROM products as A
                              LEFT JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                            ) as A
                            GROUP BY A.product_id
                          ) as C ON A.product_id = C.product_id
                          ORDER BY A.position DESC
                       ");
        return $p;
    }

    /*----------------------------------
    get the name and the id of all the products
    ------------------------------------*/

    public function showLight()
    {
        $p = \DB::select("SELECT product_id, name
                        FROM products");
        return $p;
    }

    //update the basic info of the product
    public function updateBasicProduct($request)
    {
        //if stock management is enabled
        if ($request->input('manage_stock') == '1') {
            $stock_management = '1';
        } else {
            $stock_management = '0';
        }

        //if product is published
        if ($request->input('publish_unpublish') == '1') {
            // Check if the product is published or not
            $main_variant = \DB::select(
                "SELECT A.variant_id 
                              FROM variants as A
                              JOIN products as B ON A.product_id = B.product_id AND B.product_id = :product_id
                              WHERE A.is_main = 1",
                [':product_id' => $request->input('edit_product_id')]
            );

            \DB::transaction(function () use (
                $request,
                $main_variant,
                $stock_management
            ) {
                // Get the attributes of a specific product
                $product_attributes = \DB::select(
                    'SELECT A.product_id FROM attributes as A WHERE A.product_id = :product_id',
                    [':product_id' => $request->input('edit_product_id')]
                );

                //Get attribute items linked to variant
                $variant_attribute_items = \DB::select(
                    "SELECT attribute_item_id
                              FROM variants_has_attribute_items
                              WHERE variant_id = :variant_id",
                    [':variant_id' => $main_variant[0]->variant_id]
                );

                $msg = [];
                if (
                    count($product_attributes) ==
                    count($variant_attribute_items)
                ) {
                    //Update variant publishing status
                    \DB::table('variants')
                        ->where('variant_id', '=', $main_variant[0]->variant_id)
                        ->update(['hidden' => 0]);

                    \DB::table('products')
                        ->where(
                            'product_id',
                            '=',
                            $request->input('edit_product_id')
                        )
                        ->update([
                            'name' => $request->input('edit_product_name'),
                            'brand_id' => $request->input('brand'),
                            'hidden' => 0,
                            'enable_stock_mgmt' => $stock_management,
                            'updated_by' => Auth::id(),
                            'updated_at' => Carbon::now('Asia/Beirut'),
                        ]);

                    $msg = [
                        'status' => 'success',
                        'message' => 'Product successfully saved and published',
                    ];
                    $request->session()->put('msg', $msg);
                } else {
                    $msg = [
                        'status' => 'error',
                        'message' =>
                            'Cannot Publish Product Because Number Of Attributes Linked to Product is Incorrect',
                    ];
                    $request->session()->put('msg', $msg);
                }
            });

            return $request->session()->pull('msg');
        } else {
            \DB::table('products')
                ->where('product_id', '=', $request->input('edit_product_id'))
                ->update([
                    'name' => $request->input('edit_product_name'),
                    'brand_id' => $request->input('brand'),
                    'hidden' => 1,
                    'enable_stock_mgmt' => $stock_management,
                    'updated_by' => Auth::id(),
                    'updated_at' => Carbon::now('Asia/Beirut'),
                ]);

            return $msg = [
                'status' => 'error',
                'message' => 'Product is unpublished',
            ];
        }
    }

    /*----------------------------------
    Insert a new Product
    ------------------------------------*/
    public function add($request, $file_name)
    {
        try {
            \DB::transaction(function () use ($request, $file_name) {
                //INVENTORY INITIALIZATION
                //if stock management is enabled
                if ($request->input('manage_stock') == '1') {
                    $stock_management = '1';
                    $qty = $request->input('stock');
                    $stock_status = '1';
                } else {
                    $stock_management = '0';
                    $stock_status = $request->input('stock_status');
                    $qty = null;
                }

                //if allow backorders is enabled
                if ($request->input('allow_backorders') == '1') {
                    $backorders = '1';
                } else {
                    $backorders = '0';
                }

                // get the last position number
                $q = \DB::select("SELECT MAX(position) as position
                                FROM products
                                WHERE hidden = 0");

                $position = $q[0]->position;

                if ($position == null || $position == 0) {
                    $position = 1;
                } else {
                    $position = $position + 1;
                }

                // Returns product id after insterting a product in products table
                $product_id = \DB::table('products')->insertGetId([
                    'name' => $request->input('name'),
                    'brand_id' => $request->input('brand'),
                    'description' => $request->input('prod_description'),
                    'short_description' => $request->input('short_description'),
                    'enable_stock_mgmt' => $stock_management,
                    'allow_backorders' => $backorders,
                    'seo_title' => $request->input('seo_title'),
                    'seo_slug' => $request->input('seo_slug'),
                    'seo_meta_desc' => $request->input('seo_meta_desc'),
                    'seo_meta_keywords' => $request->input('seo_meta_keywords'),
                    'position' => $position,
                    'hidden' => 0,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'created_at' => Carbon::now('Asia/Beirut'),
                    'updated_at' => Carbon::now('Asia/Beirut'),
                ]);

                // Insert primary image in product_images table
                \DB::table('product_images')->insert([
                    'product_id' => $product_id,
                    'img' => $file_name,
                    'is_primary' => 1,
                    'hidden' => 0,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'created_at' => Carbon::now('Asia/Beirut'),
                    'updated_at' => Carbon::now('Asia/Beirut'),
                ]);

                // Check if a main variant exists
                $variant_list = \DB::select(
                    "SELECT variant_id 
                                FROM variants
                                WHERE product_id = :product_id 
                                AND hidden = 0",
                    [':product_id' => $product_id]
                );

                if (empty($variant_list)) {
                    $is_main = 1;
                } else {
                    $is_main = 0;
                }

                // Insert variant info in variants table
                \DB::table('variants')->insert([
                    'product_id' => $product_id,
                    'is_main' => $is_main,
                    'img' => $file_name,
                    'regular_price' => $request->input('price'),
                    'width' => $request->input('width'),
                    'weight' => $request->input('weight'),
                    'length' => $request->input('length'),
                    'height' => $request->input('height'),
                    'diameter' => $request->input('diameter'),
                    'stock_qty' => $qty,
                    'stock_status_id' => $stock_status,
                    'hidden' => 0,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'created_at' => Carbon::now('Asia/Beirut'),
                    'updated_at' => Carbon::now('Asia/Beirut'),
                ]);

                // Check if the product has categories
                if ($request->input('categories') != null) {
                    $categories = []; //declare an empty array
                    foreach ($request->input('categories') as $c) {
                        // build the component table
                        array_push($categories, [
                            'product_id' => $product_id,
                            'category_id' => $c,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'created_at' => Carbon::now('Asia/Beirut'),
                            'updated_at' => Carbon::now('Asia/Beirut'),
                        ]);
                    }

                    // insert the all the categories of the products
                    \DB::table('products_has_categories')->insert($categories); // Query Builder
                }

                $request->session()->put('product_id', $product_id);
            });

            return $request->session()->pull('product_id');
        } catch (QueryException $ex) {
            dd($ex->getMessage());
            //dd('Error while adding a Prodcut');
        }
    }

    /*----------------------------------
    Publish/Unpublish a Product
    ------------------------------------*/
    public function publish($request)
    {
        // Check if the product is published or not
        $product = \DB::select(
            "SELECT hidden 
                                FROM products
                                WHERE product_id = :product_id",
            [':product_id' => $request->input('id')]
        );

        if ($product[0]->hidden == 1) {
            //product is unpublished
            $hidden = 0;
            // Check if the product is published or not
            $main_variant = \DB::select(
                "SELECT A.variant_id 
                              FROM variants as A
                              JOIN products as B ON A.product_id = B.product_id AND B.product_id = :product_id
                              WHERE A.is_main = 1",
                [':product_id' => $request->input('id')]
            );

            \DB::transaction(function () use (
                $request,
                $main_variant,
                $hidden
            ) {
                //Update variant publishing status
                \DB::table('variants')
                    ->where('variant_id', '=', $main_variant[0]->variant_id)
                    ->update(['hidden' => $hidden]);

                \DB::table('products')
                    ->where('product_id', '=', $request->input('id'))
                    ->update(['hidden' => $hidden]);
            });
        } else {
            $hidden = 1;
            \DB::table('products')
                ->where('product_id', '=', $request->input('id'))
                ->update(['hidden' => $hidden]);
        }

        return $hidden;
    }

    /*----------------------------------
    update the position of the product
    ------------------------------------*/
    public function updatePosition($request)
    {
        $product_id = $request->input('product_id');
        $old = $request->input('old_position');

        // get position from ID
        $q = \DB::select(
            "SELECT position
                        FROM products 
                        WHERE product_id = :product_id",
            [':product_id' => $request->input('product_select')]
        );

        // new position
        $new = $q[0]->position;

        $direction = $request->input('position_dir'); // get the direction of the new position

        if ($old != $new) {
            // if we are going DOWN
            if ($new < $old) {
                // if BEFORE and order DOWN
                if ($direction == 'after') {
                    \DB::transaction(function () use ($product_id, $old, $new) {
                        \DB::select(
                            " UPDATE products
                                SET position = position + 1
                                WHERE position BETWEEN :new AND :old",
                            [':new' => $new, ':old' => $old - 1]
                        );

                        \DB::select(
                            " UPDATE products
                                SET position = :new2
                                WHERE product_id = :product_id",
                            [':new2' => $new, ':product_id' => $product_id]
                        );
                    });
                }

                // end if($direction == "after")
                // if BEFORE and order DOWN
                else {
                    // record are not consecutive | it is NOT a useless reordering
                    if ($new != $old + 1) {
                        \DB::transaction(function () use (
                            $product_id,
                            $old,
                            $new
                        ) {
                            \DB::select(
                                "UPDATE products
                               SET position = position + 1
                               WHERE position BETWEEN :new AND :old",
                                [':new' => $new + 1, ':old' => $old - 1]
                            );

                            \DB::select(
                                "UPDATE products
                                 SET position = :new2
                                 WHERE product_id = :product_id",
                                [
                                    ':new2' => $new + 1,
                                    ':product_id' => $product_id,
                                ]
                            );
                        });
                    }
                } // end else
            }

            // end if($new < $old)
            // if we are going UP
            else {
                // if AFTER and order UP
                if ($direction == 'after') {
                    // record are not consecutive | it is NOT a useless reordering
                    if ($new != $old + 1) {
                        \DB::transaction(function () use (
                            $product_id,
                            $old,
                            $new
                        ) {
                            \DB::select(
                                "UPDATE products
                               SET position = position - 1
                               WHERE position BETWEEN :old AND :new",
                                [':old' => $old + 1, ':new' => $new - 1]
                            );

                            \DB::select(
                                "UPDATE products
                                 SET position = :new2
                                 WHERE product_id = :product_id",
                                [
                                    ':new2' => $new - 1,
                                    ':product_id' => $product_id,
                                ]
                            );
                        });
                    }
                }

                // end if($direction == "after")
                // if BEFORE and order UP
                else {
                    \DB::transaction(function () use ($product_id, $old, $new) {
                        \DB::select(
                            " UPDATE products
                                SET position = position - 1
                                WHERE position BETWEEN :old AND :new",
                            [':old' => $old + 1, ':new' => $new]
                        );

                        \DB::select(
                            " UPDATE products
                                  SET position = :new2
                                  WHERE product_id = :product_id",
                            [':new2' => $new, ':product_id' => $product_id]
                        );
                    });
                } // end else
            } // end else if we are going UP
        } // end if($old != $new)
    }

    /*----------------------------------
    Feature /Unfeature a Product
    ------------------------------------*/
    public function feature($request)
    {
        // Check if the product is featureed or not
        $product = \DB::select(
            "SELECT featured 
                                FROM products
                                WHERE product_id = :product_id",
            [':product_id' => $request->input('id')]
        );

        if ($product[0]->featured == 1) {
            //product is featured
            $featured = 0;
        } else {
            $featured = 1;
        }

        \DB::table('products')
            ->where('product_id', '=', $request->input('id'))
            ->update(['featured' => $featured]);

        return $featured;
    }

    /*----------------------------------
    Delete a Product
    ------------------------------------*/
    public function delete($request)
    {
        try {
            \DB::transaction(function () use ($request) {
                //Delete all links between product and categories
                \DB::select(
                    "DELETE 
                          FROM products_has_categories
                          WHERE product_id = :product_id",
                    [':product_id' => $request->input('id')]
                );

                //Delete all links between product and tags
                \DB::select(
                    "DELETE 
                          FROM products_has_tags
                          WHERE product_id = :product_id",
                    [':product_id' => $request->input('id')]
                );

                //Delete all product images
                \DB::select(
                    "DELETE 
                          FROM product_images
                          WHERE product_id = :product_id",
                    [':product_id' => $request->input('id')]
                );

                //Delete all product videos
                \DB::select(
                    "DELETE 
                          FROM products_has_videos
                          WHERE product_id = :product_id",
                    [':product_id' => $request->input('id')]
                );

                //Delete all linked products
                \DB::select(
                    "DELETE 
                          FROM products_has_links
                          WHERE current_product_id = :product_id",
                    [':product_id' => $request->input('id')]
                );

                //Delete all links between variants and attribute_items
                \DB::select(
                    "DELETE FROM variants_has_attribute_items
                          WHERE variant_id IN
                          (
                            SELECT variant_id
                            FROM variants  
                            WHERE product_id = :product_id
                          )",
                    [':product_id' => $request->input('id')]
                );

                //Delete all attribute_items related to the attributes of this product
                \DB::select(
                    "DELETE FROM attribute_items
                          WHERE attribute_id IN(
                          select attribute_id
                          FROM attributes
                          WHERE product_id = :product_id
                          )",
                    [':product_id' => $request->input('id')]
                );

                //Delete all the attributes related to the product
                \DB::select(
                    "DELETE 
                          FROM attributes
                          WHERE product_id = :product_id",
                    [':product_id' => $request->input('id')]
                );

                //Delete all the promotion linked to the product
                \DB::select(
                    "DELETE 
                          FROM promo_codes_has_products
                          WHERE product_id = :product_id",
                    [':product_id' => $request->input('id')]
                );

                //Delete all product variants
                \DB::select(
                    "DELETE 
                          FROM variants
                          WHERE product_id = :product_id",
                    [':product_id' => $request->input('id')]
                );

                //Delete the product
                \DB::select(
                    "DELETE 
                          FROM products
                          WHERE product_id = :product_id",
                    [':product_id' => $request->input('id')]
                );
            });
        } catch (QueryException $ex) {
            dd($ex->getMessage());
            //dd('Error while deleting a Prodcut');
        }
    }

    /*----------------------------------
    get the Main Variant Details of the specific product
    ------------------------------------*/
    public function showDetails($product_id)
    {
        $q = \DB::select(
            "SELECT A.product_id, A.name, B.img, A.brand_id, A.description, A.short_description, A.enable_stock_mgmt, A.allow_backorders, A.purchase_note, A.order_position, A.seo_title, A.seo_slug, A.seo_meta_desc, A.seo_meta_keywords, A.hidden,
                                 B.img, 
                                 C.name as brand_name, 
                                 D.sales_price, D.sales_price_end_date
                          FROM products as A
                          JOIN product_images as B ON A.product_id=B.product_id AND B.is_primary=1
                          LEFT JOIN brands as C ON A.brand_id = C.brand_id
                          LEFT JOIN variants as D on A.product_id = D.product_id and D.is_main=1
                          WHERE A.product_id=:product_id;",
            [':product_id' => $product_id]
        );
        return $q;
    }

    /*----------------------------------
    Show a Specific Product
    ------------------------------------*/
    public function showPriceInterval($product_id)
    {
        $q = \DB::select(
            "SELECT MIN(A.regular_price) as min_regular_price, MAX(A.regular_price) as max_regular_price, MIN(A.sales_price) as min_sales_price, MIN(A.current_price) as min_current_price, MAX(A.current_price) as max_current_price
                          FROM
                          (SELECT D.regular_price, D.sales_price, IF(D.sales_price IS NOT NULL, D.sales_price, D.regular_price) as current_price
                          FROM products as A
                          LEFT JOIN variants as D on A.product_id= D.product_id
                          WHERE A.product_id=:product_id) as A",
            [':product_id' => $product_id]
        );

        return $q;
    }

    /*----------------------------------------------------------------------------------
    -----  PRODCUCTS IMAGES
    -----------------------------------------------------------------------------------*/

    /*----------------------------------
    get product images
    ------------------------------------*/
    public function getImages($product_id)
    {
        $q = \DB::select(
            "SELECT product_id, product_img_id, img, is_primary
                          FROM product_images
                          WHERE product_id=:product_id
                          AND hidden=0",
            [':product_id' => $product_id]
        );

        return $q;
    }

    /*----------------------------------
    Add new image of the product
    ------------------------------------*/
    public function addImage($request, $file_name)
    {
        $q = \DB::table('product_images')->insert([
            'product_id' => $request->input('edit_product_id'),
            'img' => $file_name,
            'is_primary' => 0,
            'hidden' => 0,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'created_at' => Carbon::now('Asia/Beirut'),
            'updated_at' => Carbon::now('Asia/Beirut'),
        ]);

        return $q;
    }

    /*----------------------------------
    Update primary image
    ------------------------------------*/
    public function updatePrimaryImage($request, $img)
    {
        \DB::table('product_images')
            ->where(
                'product_img_id',
                '=',
                $request->input('edit_primary_img_id')
            )
            ->update(['img' => $img]);
    }

    /*----------------------------------
    Set an Image as primary
    ------------------------------------*/
    public function setImageAsPrimary($request)
    {
        try {
            \DB::transaction(function () use ($request) {
                \DB::table('product_images')
                    ->where('product_id', '=', $request->input('product_id'))
                    ->update(['is_primary' => 0]);

                \DB::table('product_images')
                    ->where('product_id', '=', $request->input('product_id'))
                    ->where('product_img_id', '=', $request->input('img_id'))
                    ->update(['is_primary' => 1]);
            });
        } catch (QueryException $ex) {
            dd($ex->getMessage());
            //dd('Error while deleting a Prodcut');
        }
    }

    /*----------------------------------
    Delete a product image
    ------------------------------------*/
    public function deleteImage($request)
    {
        \DB::select(
            "DELETE 
                FROM product_images 
                WHERE product_img_id = :product_img_id",
            [':product_img_id' => $request->input('id')]
        );
    }

    /*----------------------------------------------------------------------------------
    -----  PRODCUCTS DESCRIPTION
    -----------------------------------------------------------------------------------*/

    //update the product description
    public function updateDescription($request)
    {
        \DB::table('products')
            ->where('product_id', '=', $request->input('edit_product_id'))
            ->update([
                'short_description' => $request->input(
                    'edit_short_description'
                ),
                'description' => $request->input('edit_prod_description'),
                'updated_by' => Auth::id(),
                'updated_at' => Carbon::now('Asia/Beirut'),
            ]);
    }

    /*----------------------------------------------------------------------------------
    -----  PRODCUCTS SEO
    -----------------------------------------------------------------------------------*/

    //update the SEO info of the product
    public function updateSeo($request)
    {
        \DB::table('products')
            ->where('product_id', '=', $request->input('edit_product_id'))
            ->update([
                'seo_title' => $request->input('seo_title_edit'),
                'seo_slug' => $request->input('seo_slug_edit'),
                'seo_meta_desc' => $request->input('seo_meta_desc_edit'),
                'seo_meta_keywords' => $request->input(
                    'seo_meta_keywords_edit'
                ),
                'updated_by' => Auth::id(),
                'updated_at' => Carbon::now('Asia/Beirut'),
            ]);
    }

    /*----------------------------------------------------------------------------------
    -----  PRODCUCTS VIDEOS
    -----------------------------------------------------------------------------------*/

    /*----------------------------------
    get product videos
    ------------------------------------*/
    public function getVideos($product_id)
    {
        $q = \DB::select(
            "SELECT *
                          FROM products_has_videos
                          WHERE product_id=:product_id",
            [':product_id' => $product_id]
        );

        return $q;
    }

    /*----------------------------------
    Add video to product
    ------------------------------------*/
    public function addVideo($request)
    {
        try {
            \DB::transaction(function () use ($request) {
                \DB::table('products_has_videos')->insert([
                    'product_id' => $request->input('video_product_id'),
                    'link' => $request->input('video'),
                ]);
            });
        } catch (QueryException $ex) {
            dd($ex->getMessage());
            //dd('Error while deleting a Prodcut');
        }
    }

    /*----------------------------------
    Unlink a product videos
    ------------------------------------*/
    public function deleteVideo($request)
    {
        try {
            \DB::transaction(function () use ($request) {
                \DB::select(
                    "DELETE 
                        FROM products_has_videos 
                        WHERE video_id = :video_id",
                    [':video_id' => $request->input('id')]
                );
            });
        } catch (QueryException $ex) {
            dd($ex->getMessage());
            //dd('Error while deleting a Prodcut');
        }
    }

    /*----------------------------------------------------------------------------------
    -----  PRODCUCTS LINKED PRODUCTS
    -----------------------------------------------------------------------------------*/

    /*----------------------------------
    get the list of all Products that we can link 
    to another product (Remove pre-selcted products)
    ------------------------------------*/
    public function showNotSelected($request)
    {
        $p = \DB::select(
            "SELECT A.*, B.cat_name, A.brand, A.enable_stock_mgmt, C.min_stock_qty, C.stock_status_id
                          FROM ( 
                            SELECT A.product_id, A.prod_name,A.primary_img as primary_img,A.sales_price as sales_price, MIN(A.current_price) as min_price, MAX(A.current_price) as max_price, A.hidden, A.enable_stock_mgmt, A.brand
                            FROM
                            (
                              /*  Return list of products */
                              SELECT A.product_id, A.name as prod_name, A.hidden, A.enable_stock_mgmt,
                              B.img as primary_img, 
                              C.regular_price, C.sales_price, IF(C.sales_price IS NOT NULL, C.sales_price, C.regular_price) as current_price, 
                              D.name as brand
                              FROM products as A
                              LEFT JOIN product_images as B  ON A.product_id = B.product_id AND B.is_primary = 1 AND B.hidden = 0
                              LEFT JOIN variants as C ON A.product_id = C.product_id AND C.hidden = 0
                              LEFT JOIN brands as D ON A.brand_id = D.brand_id) as A
                              WHERE A.product_id NOT IN (SELECT linked_product_id FROM products_has_links WHERE current_product_id = :product_id)
                              AND A.product_id != :product_id2
                              GROUP BY A.product_id
                            ) as A

                          LEFT JOIN 
                          (
                            /*  Return categories of products */
                            SELECT A.product_id, GROUP_CONCAT(E.name) as cat_name
                            FROM products as A
                            LEFT JOIN products_has_categories as D ON A.product_id = D.product_id
                            LEFT JOIN categories as E on E.category_id = D.category_id AND E.hidden = 0
                            GROUP BY A.product_id
                          ) as B ON A.product_id = B.product_id
                          
                          LEFT JOIN 
                          (
                            /*  Return stock of each product */
                            SELECT A.product_id, MIN(A.stock_qty) as min_stock_qty, MIN(A.stock_status_id) as stock_status_id
                            FROM
                            (                          
                              SELECT A.product_id, IF(B.stock_qty IS NOT NULL, B.stock_qty, 0) as stock_qty, IF(B.stock_status_id IS NOT NULL, B.stock_status_id, 0) as stock_status_id
                              FROM products as A
                              LEFT JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                            ) as A
                            GROUP BY A.product_id
                          ) as C ON A.product_id = C.product_id
                       ",
            [
                ':product_id' => $request->input('id'),
                ':product_id2' => $request->input('id'),
            ]
        );
        return $p;
    }

    /*----------------------------------
    Add Linked Product
    ------------------------------------*/
    public function addLinkedProduct($request)
    {
        try {
            \DB::transaction(function () use ($request) {
                //Build product table dynamically
                $product_list = []; //declare an empty array
                foreach ($request->input('products') as $p) {
                    // build the component table
                    array_push($product_list, [
                        'current_product_id' => $request->input(
                            'link_product_id'
                        ),
                        'linked_product_id' => $p,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'created_at' => Carbon::now('Asia/Beirut'),
                        'updated_at' => Carbon::now('Asia/Beirut'),
                    ]);
                }

                // insert all the new atribute items
                \DB::table('products_has_links')->insert($product_list); // Query Builder
            });
        } catch (QueryException $ex) {
            dd($ex->getMessage());
            //dd('Error while deleting a Prodcut');
        }
    }

    /*----------------------------------
    Delete Linked Product
    ------------------------------------*/
    public function deleteLinkedProduct($request)
    {
        try {
            \DB::transaction(function () use ($request) {
                \DB::select(
                    "DELETE 
                        FROM products_has_links
                        WHERE linked_product_id = :linked_product_id
                        AND current_product_id = :current_product_id",
                    [
                        ':linked_product_id' => $request->input(
                            'linked_product_id'
                        ),
                        ':current_product_id' => $request->input(
                            'current_product_id'
                        ),
                    ]
                );
            });
        } catch (QueryException $ex) {
            dd($ex->getMessage());
            //dd('Error while deleting a Prodcut');
        }
    }

    /*----------------------------------
    Get the products list to choose a linked product. 
    The products already linked are not displayed
    ------------------------------------*/
    public function getProductsList($product_id)
    {
        $p = \DB::select(
            "SELECT product_id, name
                          FROM products
                          WHERE hidden =0 AND product_id != :product_id AND product_id NOT IN (select linked_product_id 
                                                                                               from products_has_links 
                                                                                               where current_product_id = :product_id2)
                                                                                             ",
            [':product_id' => $product_id, ':product_id2' => $product_id]
        );
        return $p;
    }

    /*----------------------------------
    Get the list of linked products
    ------------------------------------*/
    public function getLinkedProducts($product_id)
    {
        $p = \DB::select(
            "SELECT B.product_id, B.name, C.img
                          FROM products_has_links as A
                          RIGHT JOIN products as B on A.linked_product_id=B.product_id and B.hidden = 0
                          JOIN product_images as C on A.linked_product_id=C.product_id AND C.is_primary=1
                          WHERE A.current_product_id =  :product_id",
            [':product_id' => $product_id]
        );
        return $p;
    }

    // ========================= FUNCTION USED FOR MULTIPLE VARIANTS INTEGRATION =================

    /*----------------------------------
   get all the attribute color
    ------------------------------------*/
    public function getColorItems($product_id)
    {
        $p = \DB::select(
            "SELECT attribute_item_id
                          FROM attribute_items
                          WHERE attribute_id IN 
                          (

                            SELECT attribute_id 
                            FROM attributes as A
                            WHERE product_id = :product_id
                            AND is_main = 2
                              
                          )",
            [':product_id' => $product_id]
        );
        return $p;
    }

    /*----------------------------------
   get all the attribute color
    ------------------------------------*/
    public function getParamItems($product_id)
    {
        $p = \DB::select(
            "SELECT attribute_item_id
                          FROM attribute_items
                          WHERE attribute_id IN 
                          (

                            SELECT attribute_id 
                            FROM attributes as A
                            WHERE product_id = :product_id
                            AND is_main != 1
                              
                          )",
            [':product_id' => $product_id]
        );
        return $p;
    }

    /*----------------------------------
   get main variant info
    ------------------------------------*/
    public function getMainVariantInfo($product_id)
    {
        $p = \DB::select(
            "SELECT *
                          FROM variants
                          WHERE product_id = :product_id
                          and is_main = 1",
            [':product_id' => $product_id]
        );
        return $p;
    }

    /*----------------------------------
   insert variants
    ------------------------------------*/
    public function insertVariant($product_id, $main_variant, $i)
    {
        $variant_id = \DB::table('variants')->insertGetId([
            'product_id' => $product_id,
            'sku' => $main_variant[0]->sku . '-' . $i,
            'img' => null,
            'is_main' => 0,
            'regular_price' => $main_variant[0]->regular_price,
            'sales_price' => null,
            'sales_price_start_date' => null,
            'sales_price_end_date' => null,
            'stock_qty' => null,
            'stock_status_id' => 1,
            'width' => null,
            'weight' => 10,
            'length' => null,
            'height' => null,
            'shipping_class_id' => null,
            'hidden' => 0,
            'created_at' => Carbon::now('Asia/Beirut'),
            'updated_at' => Carbon::now('Asia/Beirut'),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return $variant_id;
    }

    /*----------------------------------
   insert variant and attribute_items
   ------------------------------------*/
    public function insertVariantAttributeItems($variant_id, $attribute_item_id)
    {
        $variant_id = \DB::table('variants_has_attribute_items')->insert([
            'variant_id' => $variant_id,
            'attribute_item_id' => $attribute_item_id,
        ]);
    }
}
