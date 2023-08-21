<?php

namespace App\Http\Repositories\Frontend;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\QueryException;

class ProductApis
{
    /*----------------------------------
    Show a Specific Product
    ------------------------------------*/
    public function show($product_id)
    {
        $q = \DB::select(
            "SELECT A.*, B.name as brand, C.img as primary_img, D.*
                          FROM products as A
                          LEFT JOIN brands as B ON A.brand_id = B.brand_id
                          LEFT JOIN product_images as C ON A.product_id = C.product_id and C.is_primary=1
                          LEFT JOIN variants as D on A.product_id= D.product_id and D.is_main=1
                          WHERE A.product_id=:product_id",
            [':product_id' => $product_id]
        );

        return $q;
    }

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
    Show the products related to a specific category
    ------------------------------------*/
    public function showProductListByCategory($category_id)
    {
        $q = \DB::select(
            "SELECT A.*, B.*
                          FROM
                          (#get the minimum price and the total stock qty and status to make sure that we have at least 1 variant with stocks
                          SELECT A.product_id, MIN(A.current_price) as min_price, SUM(A.stock_qty) as total_stock_qty, SUM(A.stock_status_id) as total_stock_status, A.position
                          FROM
                          (# get the current price depending if there is a promo or not
                          SELECT A.product_id, A.position, IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                          B.stock_qty, B.stock_status_id
                          FROM products as A
                          JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                          JOIN products_has_categories as C ON A.product_id = C.product_id AND C.category_id = :category_id
                          WHERE A.hidden=0
                          ) as A
                          GROUP BY A.product_id
                          ) as A

                          JOIN

                          (
                          SELECT A.*
                          FROM
                          (#get all the needed info of the product
                          SELECT A.product_id, A.brand_id, A.name, A.short_description, A.enable_stock_mgmt,
                            B.variant_id as variant_id, B.regular_price as regular_price, B.sales_price as sales_price,
                            B.sales_price_start_date as sales_price_start_date, B.sales_price_end_date as sales_price_end_date,
                           B.stock_qty as stock_qty, B.stock_status_id as stock_status_id,
                          IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                        D.img as img, A.created_at
                          FROM products as A
                          JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                          JOIN products_has_categories as C ON A.product_id = C.product_id AND C.category_id = :category_id2
                          LEFT JOIN product_images as D ON A.product_id = D.product_id AND D.is_primary = 1
                          WHERE A.hidden=0
                            GROUP BY A.product_id, current_price
                          ) as A
                          ) as B ON A.product_id = B.product_id AND A.min_price = B.current_price

                          ORDER BY A.position DESC
                          ",
            [':category_id' => $category_id, ':category_id2' => $category_id]
        );
        return $q;
    }

    /*----------------------------------
    Show a Specific Product
    ------------------------------------*/
    public function getPriceIntervalbyProduct($product_id)
    {
        $q = \DB::select(
            "SELECT MIN(A.regular_price) as min_regular_price, MAX(A.regular_price) as max_regular_price, MIN(A.current_price) as min_current_price, MAX(A.current_price) as max_current_price
                          FROM
                          (SELECT D.regular_price, D.sales_price, IF(D.sales_price_end_date >= NOW() AND D.sales_price_start_date <= NOW(), D.sales_price, D.regular_price) as current_price
                          FROM products as A
                          LEFT JOIN variants as D on A.product_id= D.product_id AND D.hidden = 0
                          WHERE A.product_id=:product_id
                          AND A.hidden = 0
                          GROUP BY D.variant_id) as A",
            [':product_id' => $product_id]
        );

        return $q;
    }

    /*----------------------------------
    Show a Product Images
    ------------------------------------*/
    public function getProductImages($product_id)
    {
        $q = \DB::select(
            "SELECT *
                          FROM product_images
                          WHERE product_id=:product_id",
            [':product_id' => $product_id]
        );

        return $q;
    }

    /*----------------------------------
    Show a Specific Product
    ------------------------------------*/
    public function getProductVariants($product_id)
    {
        $q = \DB::select(
            "SELECT A.variant_id, A.product_id, A.regular_price, A.img, A.sales_price, A.sales_price_start_date, A.sales_price_end_date, A.stock_qty, A.stock_status_id, A.is_main, GROUP_CONCAT(C.attribute_item_id SEPARATOR '_') as item_code
                          FROM variants as A
                          LEFT JOIN variants_has_attribute_items as B ON A.variant_id = B.variant_id AND A.hidden=0
                          LEFT JOIN attribute_items as C ON B.attribute_item_id = C.attribute_item_id
                          WHERE A.product_id=:product_id
                          GROUP BY A.variant_id;",
            [':product_id' => $product_id]
        );
        return $q;
    }

    /*----------------------------------
    Shows the list of attributes we can click based on a predefined selection og other attributes
    ------------------------------------*/
    public function getAttributeItemsWeCanClick(
        $product_id,
        $attribute_items_selected,
        $nb_attribute_items
    ) {
        //Removing the "" from code.
        $attribute_items_selected = str_replace(
            '"',
            '',
            $attribute_items_selected
        );
        $q = \DB::select(
            "SELECT *
                          FROM variants_has_attribute_items as A
                          WHERE A.variant_id IN (SELECT  A.variant_id
                                                  FROM variants_has_attribute_items as A
                                                  JOIN variants as B ON A.variant_id = B.variant_id AND B.product_id = :product_id AND B.hidden = 0
                                                  WHERE A.attribute_item_id IN (" .
                $attribute_items_selected .
                ")
                                                  GROUP BY A.variant_id
                                                  HAVING COUNT(*) = :nb_attribute_items)
                          AND A.attribute_item_id NOT IN (" .
                $attribute_items_selected .
                ')',
            [
                ':product_id' => $product_id,
                'nb_attribute_items' => $nb_attribute_items,
            ]
        );
        return $q;
    }

    /*----------------------------------
    Shows the list of variants based on attribute selection
    ------------------------------------*/
    public function getVariantPriceIntervalByAttributes(
        $product_id,
        $attribute_items_selected,
        $nb_attribute_items
    ) {
        //Removing the "" from code.
        $attribute_items_selected = str_replace(
            '"',
            '',
            $attribute_items_selected
        );
        //$attribute_items_selected = intval($attribute_items_selected);
        $q = \DB::select(
            "SELECT MIN(A.regular_price) as min_regular_price, MAX(A.regular_price) as max_regular_price, MAX(current_price) as max_current_price, MIN(current_price) as min_current_price
                          FROM
                          (SELECT B.variant_id, B.regular_price, B.sales_price, IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price
                                                    FROM variants_has_attribute_items as A
                                                    JOIN variants as B ON A.variant_id = B.variant_id AND B.product_id = :product_id AND B.hidden = 0
                                                    WHERE A.attribute_item_id IN (" .
                $attribute_items_selected .
                ")
                                                    GROUP BY B.variant_id
                                                    HAVING COUNT(*) = :nb_attribute_items) as A",
            [
                ':product_id' => $product_id,
                ':nb_attribute_items' => $nb_attribute_items,
            ]
        );
        return $q;
    }

    /*----------------------------------
    Shows the list of variants based on attribute selection
    ------------------------------------*/
    public function getVariantsByAttributes(
        $product_id,
        $attribute_items_selected,
        $nb_attribute_items
    ) {
        //Removing the "" from code.
        $attribute_items_selected = str_replace(
            '"',
            '',
            $attribute_items_selected
        );
        //$attribute_items_selected = intval($attribute_items_selected);
        $q = \DB::select(
            "SELECT B.variant_id, B.regular_price, B.sales_price, IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                                B.stock_qty, B.stock_status_id, C.enable_stock_mgmt
                                                    FROM variants_has_attribute_items as A
                                                    JOIN variants as B ON A.variant_id = B.variant_id AND B.product_id = :product_id AND B.hidden = 0
                                                    JOIN products as C ON B.product_id = C.product_id AND C.hidden = 0
                                                    WHERE A.attribute_item_id IN (" .
                $attribute_items_selected .
                ")
                                                    GROUP BY B.variant_id
                                                    HAVING COUNT(*) = :nb_attribute_items",
            [
                ':product_id' => $product_id,
                ':nb_attribute_items' => $nb_attribute_items,
            ]
        );
        return $q;
    }

    /*----------------------------------
    get product Attributes
    ------------------------------------*/
    public function getProductAttributes($product_id)
    {
        $q = \DB::select(
            "SELECT A.attribute_id, A.name, A.is_main, group_concat(B.name) as attribute_names
                          FROM attributes as A
                          LEFT JOIN attribute_items as B ON A.attribute_id = B.attribute_id AND B.hidden=0
                          WHERE product_id=:product_id
                          AND A.hidden=0
                          GROUP BY A.attribute_id
                          ORDER BY A.is_main DESC",
            [':product_id' => $product_id]
        );

        return $q;
    }

    /*----------------------------------
    get the product attributes items
    ------------------------------------*/
    public function getAttributeItems($product_id)
    {
        $q = \DB::select(
            "SELECT A.attribute_id, A.product_id, A.name as attribute_name, B.name as item_name, B.attribute_item_id, B.img as attribute_item_img, B.color
                          FROM attributes as A
                          JOIN attribute_items as B ON A.attribute_id = B.attribute_id
                          JOIN variants_has_attribute_items as C ON B.attribute_item_id = C.attribute_item_id
                          JOIN variants as D ON C.variant_id = D.variant_id AND D.hidden = 0
                          WHERE A.product_id=:product_id
                          GROUP BY B.attribute_item_id",
            [':product_id' => $product_id]
        );
        return $q;
    }

    /*----------------------------------
    Shows Similar products
    ------------------------------------*/
    public function getSimilarProducts($product_id)
    {
        $q = \DB::select(
            "SELECT A.*, B.*
                          FROM
                          (#get the minimum price and the total stock qty and status to make sure that we have at least 1 variant with stocks
                            SELECT A.product_id, MIN(A.current_price) as min_price, SUM(A.stock_qty) as total_stock_qty, SUM(A.stock_status_id) as total_stock_status
                            FROM
                            (
                              SELECT A.product_id, IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                              B.stock_qty, B.stock_status_id
                              FROM products as A
                              JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                              JOIN products_has_categories as C ON A.product_id = C.product_id AND C.category_id = ( SELECT category_id
                                                                                                                       FROM products_has_categories
                                                                                                                       WHERE product_id=:product_id
                                                                                                                       LIMIT 1
                                                                                                                      )
                              WHERE A.hidden=0
                            ) as A
                            GROUP BY A.product_id
                          ) as A

                          JOIN

                          (
                            SELECT A.*
                            FROM
                            (
                              #get all the needed info of the product
                              SELECT A.product_id, A.brand_id, A.name, A.short_description, A.enable_stock_mgmt,
                              B.variant_id as variant_id, B.regular_price as regular_price,B.sales_price as sales_price,
                              B.sales_price_start_date as sales_price_start_date,B.sales_price_end_date as sales_price_end_date,
                            B.stock_qty as stock_qty, B.stock_status_id as stock_status_id,
                              IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                           D.img as img, A.created_at
                              FROM products as A
                              JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                              JOIN products_has_categories as C ON A.product_id = C.product_id AND C.category_id = ( SELECT category_id
                                                                                                                         FROM products_has_categories
                                                                                                                         WHERE product_id=:product_id2
                                                                                                                         LIMIT 1
                                                                                                                      )
                            LEFT JOIN product_images as D ON A.product_id = D.product_id AND D.is_primary = 1
                            WHERE A.hidden=0
                              GROUP BY A.product_id, current_price
                            ) as A
                            ) as B ON A.product_id = B.product_id AND A.min_price = B.current_price
                          WHERE A.product_id != :product_id3
                          ORDER BY RAND()
                          LIMIT 10",
            [
                ':product_id' => $product_id,
                ':product_id2' => $product_id,
                ':product_id3' => $product_id,
            ]
        );
        return $q;
    }

    /*----------------------------------
    Shows Linked products
    ------------------------------------*/
    public function getLinkedProducts($product_id)
    {
        $q = \DB::select(
            "SELECT A.*, B.*
                          FROM
                          (#get the minimum price and the total stock qty and status to make sure that we have at least 1 variant with stocks
                            SELECT A.product_id, MIN(A.current_price) as min_price, SUM(A.stock_qty) as total_stock_qty, SUM(A.stock_status_id) as total_stock_status
                            FROM
                            (
                              SELECT A.product_id, IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                              B.stock_qty, B.stock_status_id
                              FROM products as A
                              JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                              JOIN products_has_links as C ON A.product_id = C.linked_product_id AND C.current_product_id =:product_id
                              WHERE A.hidden=0
                            ) as A
                            GROUP BY A.product_id
                          ) as A

                          JOIN

                          (
                            SELECT A.*
                            FROM
                            ( #get all the needed info of the product
                              SELECT A.product_id, A.brand_id, A.name, A.short_description, A.enable_stock_mgmt,
                              B.variant_id as variant_id, B.regular_price as regular_price, B.sales_price as sales_price,
                              B.sales_price_start_date as sales_price_start_date, B.sales_price_end_date as sales_price_end_date,
                              B.stock_qty as stock_qty, B.stock_status_id as stock_status_id,
                              IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                              D.img as img, A.created_at
                              FROM products as A
                              JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                              JOIN products_has_links as C ON A.product_id = C.linked_product_id AND C.current_product_id =:product_id2
                              LEFT JOIN product_images as D ON A.product_id = D.product_id AND D.is_primary = 1
                              WHERE A.hidden=0
                            ) as A
                          ) as B ON A.product_id = B.product_id AND A.min_price = B.current_price
                          ORDER BY RAND()
                          LIMIT 10",
            [':product_id' => $product_id, ':product_id2' => $product_id]
        );

        return $q;
    }

    /*----------------------------------
    Shows the list of Brands
    ------------------------------------*/
    public function loadBrandProducts($request)
    {
        $q = \DB::select(
            "SELECT A.*, B.*, C.*, D.name as brand, E.img
                          FROM products_has_categories as A
                          JOIN products as B ON A.product_id = B.product_id AND B.hidden=0
                          JOIN variants as C ON B.product_id = C.product_id AND C.is_main=1
                          JOIN brands as D ON B.brand_id = D.brand_id AND D.brand_id=brand_id
                          LEFT JOIN product_images as E ON B.product_id = E.product_id and E.is_primary=1
                          WHERE A.category_id=category_id",
            [
                ':category_id' => $request->input('category_id'),
                ':brand_id' => $request->input('brand_id'),
            ]
        );
        return $q;
    }

    /*----------------------------------
    Apply the sidebar filter on the list of products
    ------------------------------------*/
    public function showFilteredProductByCategory($category_id)
    {
        //VARIABLES DE SESSIONS
        $brands = session('brand_box'); //Brands
        $tags = session('tag_box'); //Tags
        $min_price = session('min_price');
        $max_price = session('max_price');
        $current_date = Carbon::now('Asia/Beirut')->format('Y-m-d');

        $query = '';
        //If isset the Tags session
        if (isset($tags)) {
            //Implode Tags Session
            $t = implode(',', $tags);
            //Building the query
            $query = 'SELECT A.* FROM (';
        }

        //Building the query
        $query =
            $query .
            "
                 SELECT A.min_price,  A.total_stock_qty,  A.total_stock_status, B.*
                  FROM
                  (#get the minimum price and the total stock qty and status to make sure that we have at least 1 variant with stocks
                  SELECT A.product_id, MIN(A.current_price) as min_price, SUM(A.stock_qty) as total_stock_qty, SUM(A.stock_status_id) as total_stock_status
                  FROM
                  ( # get the current price depending if there is a promo or not
                    SELECT A.product_id, IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                    B.stock_qty, B.stock_status_id
                    FROM products as A
                    JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                    JOIN products_has_categories as C ON A.product_id = C.product_id AND C.category_id = :category_id
                    WHERE A.hidden=0
                  ) as A
                  GROUP BY A.product_id
                  ) as A

                  JOIN

                  (
                            SELECT A.*
                            FROM
                            (
                              #get all the needed info of the product
                              SELECT A.product_id, A.brand_id, A.name, A.short_description, A.enable_stock_mgmt, A.hidden,
                              B.variant_id as variant_id, B.regular_price as regular_price, B.sales_price as sales_price,
                              B.sales_price_start_date as sales_price_start_date, B.sales_price_end_date as sales_price_end_date,
                              B.stock_qty as stock_qty, B.stock_status_id as stock_status_id,
                              IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                              D.img as img, A.created_at
                              FROM products as A
                              JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                              JOIN products_has_categories as C ON A.product_id = C.product_id AND C.category_id = :category_id2
                            LEFT JOIN product_images as D ON A.product_id = D.product_id AND D.is_primary = 1
                            WHERE A.hidden=0
                              GROUP BY A.product_id, current_price
                            ) as A
                            ) as B ON A.product_id = B.product_id AND A.min_price = B.current_price
                  WHERE B.hidden = 0
                ";

        $bind = [
            ':category_id' => $category_id,
            ':category_id2' => $category_id,
        ];

        //If isset Min and Max Price session
        if (isset($min_price) and isset($max_price)) {
            //Building the query
            $query =
                $query . 'AND A.min_price BETWEEN :min_price AND :max_price ';

            $bind[':min_price'] = $min_price;
            $bind[':max_price'] = $max_price;
        }

        //If isset Brands session
        if (isset($brands)) {
            //Implode Tags Session
            $b = implode(',', $brands);
            //Building the query
            $query = $query . 'AND B.brand_id IN (' . $b . ')';
        }

        //If isset Offers session
        if (session()->has('discount_box')) {
            //Building the query
            $query =
                $query .
                ' AND B.sales_price_start_date <= :current_date_3 AND B.sales_price_end_date >= :current_date_4 ';

            $bind[':current_date_3'] = $current_date;
            $bind[':current_date_4'] = $current_date;
        }

        //If isset Tags session
        if (isset($tags)) {
            $query =
                $query .
                ") as A  JOIN
                                     (
                                     SELECT A.product_id
                                     FROM products as A
                                     JOIN products_has_tags as B ON A.product_id = B.product_id
                                     WHERE B.tag_id IN (" .
                $t .
                ")
                                     GROUP BY A.product_id
                                       ) as B
                                   ON A.product_id = B.product_id";
        }

        //  dd($query);

        //if bind is empty
        if (empty($bind)) {
            //execute the built query without the bind
            $q = \DB::select(\DB::raw($query));
        } else {
            //execute the built query
            $q = \DB::select(\DB::raw($query), $bind);
        }

        return $q;
    }

    /*----------------------------------
    Shows the list of Brands of the Products linked to a category
    ------------------------------------*/
    public function getBrandsByCategory($category_id)
    {
        $q = \DB::select(
            "SELECT D.brand_id, D.name
                          FROM products_has_categories as A
                          JOIN products as B ON A.product_id = B.product_id AND B.hidden=0
                          JOIN brands as D ON B.brand_id = D.brand_id
                          WHERE A.category_id=:category_id
                          GROUP BY D.brand_id",
            [':category_id' => $category_id]
        );

        return $q;
    }

    /*----------------------------------
    Shows the list of Tags of the Products linked to a category
    ------------------------------------*/
    public function getProductTagsByCategory($category_id)
    {
        $q = \DB::select(
            "SELECT A.tag_id, B.name
                          FROM products_has_tags as A
                          LEFT JOIN tags as B ON A.tag_id = B.tag_id
                          WHERE A.product_id IN
                          (SELECT A.product_id
                          FROM products_has_categories as A
                          JOIN products as B ON A.product_id = B.product_id AND B.hidden=0
                          WHERE A.category_id=:category_id)
                          GROUP BY A.tag_id",
            [':category_id' => $category_id]
        );

        return $q;
    }

    /*----------------------------------
    Shows info of products and variants from variant_id
    ------------------------------------*/
    public function getInfosFromVariantId($variant_id)
    {
        $q = \DB::select(
            "SELECT A.*, B.name, B.enable_stock_mgmt
                          FROM variants as A
                          JOIN products as B ON A.product_id = B.product_id AND B.hidden=0
                          WHERE A.variant_id = :variant_id",
            [':variant_id' => $variant_id]
        );
        return $q;
    }

    /*----------------------------------
    Show the featured products
    ------------------------------------*/
    public function getFeaturedProducts()
    {
        $q = \DB::select("SELECT A.*, B.*
                          FROM
                          (#get the minimum price and the total stock qty and status to make sure that we have at least 1 variant with stocks
                          SELECT A.product_id, MIN(A.current_price) as min_price, SUM(A.stock_qty) as total_stock_qty, SUM(A.stock_status_id) as total_stock_status
                          FROM
                          (
                          SELECT A.product_id, IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                          B.stock_qty, B.stock_status_id
                          FROM products as A
                          JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                          WHERE A.hidden=0
                          AND A.featured = 1
                          ) as A
                          GROUP BY A.product_id
                          ) as A

                          JOIN

                          (
                          SELECT A.*
                          FROM
                          (#get all the needed info of the product
                          SELECT A.product_id, A.brand_id, A.name, A.short_description, A.enable_stock_mgmt,
                          B.variant_id as variant_id, B.regular_price as regular_price, B.sales_price as sales_price,
                          B.sales_price_start_date as sales_price_start_date, B.sales_price_end_date as sales_price_end_date,
                          B.stock_qty as stock_qty, B.stock_status_id as stock_status_id,
                          IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                          D.img as img, A.created_at
                          FROM products as A
                          JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                          LEFT JOIN product_images as D ON A.product_id = D.product_id AND D.is_primary = 1
                          WHERE A.hidden=0
                          AND A.featured = 1
                          GROUP BY A.product_id, current_price
                          ) as A
                          ) as B ON A.product_id = B.product_id AND A.min_price = B.current_price");
        return $q;
    }

    /*-------------------------------------------
    Show the Deals - products that are under promotion
    --------------------------------------------*/
    public function getDeals($limit)
    {
        $query = 'SELECT A.*, B.*
                          FROM
                          ( #get the minimum price and the total stock qty and status to make sure that we have at least 1 variant with stocks
                          SELECT A.product_id, MIN(A.current_price) as min_price, SUM(A.stock_qty) as total_stock_qty, SUM(A.stock_status_id) as total_stock_status
                          FROM
                          ( # get the current price depending if there is a promo or not
                          SELECT A.product_id, IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                          B.stock_qty, B.stock_status_id
                          FROM products as A
                          JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                          WHERE A.hidden=0
                          AND B.sales_price IS NOT NULL
                          AND B.sales_price_end_date >= NOW()
                          AND B.sales_price_start_date <= NOW()

                          ) as A
                          GROUP BY A.product_id
                          ) as A

                          JOIN

                          (
                          SELECT A.*
                          FROM
                          ( #get all the needed info of the product
                          SELECT A.product_id, A.brand_id, A.name, A.short_description, A.enable_stock_mgmt,
                            B.variant_id as variant_id, B.regular_price as regular_price, B.sales_price as sales_price,
                            B.sales_price_start_date as sales_price_start_date, B.sales_price_end_date as sales_price_end_date,
                            B.stock_qty as stock_qty, B.stock_status_id as stock_status_id,
                          IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                          D.img as img, A.created_at
                          FROM products as A
                          JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                          LEFT JOIN product_images as D ON A.product_id = D.product_id AND D.is_primary = 1
                          WHERE A.hidden=0
                          AND B.sales_price IS NOT NULL
                          AND B.sales_price_end_date >= NOW()
                          AND B.sales_price_start_date <= NOW()
                            GROUP BY A.product_id, current_price
                          ) as A
                          ) as B ON A.product_id = B.product_id AND A.min_price = B.current_price';

        // if we want to limit the number of product to be display, $limit = [value]
        if ($limit != false) {
            $query = $query . ' LIMIT 0, :limit';
            $bind[':limit'] = $limit;

            $q = \DB::select(\DB::raw($query), $bind);
        }
        // if we don't want to limit the number of product to be display, $limit = false
        else {
            //execute the built query without bind
            $q = \DB::select(\DB::raw($query));
        }

        return $q;
    }

    /*-------------------------------------------
    get the best sellers products
    --------------------------------------------*/
    public function getBestSellers()
    {
        $q = \DB::select("SELECT A.*, B.*, C.nb_of_order
                          FROM
                          ( #get the minimum price and the total stock qty and status to make sure that we have at least 1 variant with stocks
                            SELECT A.product_id, MIN(A.current_price) as min_price, SUM(A.stock_qty) as total_stock_qty, SUM(A.stock_status_id) as total_stock_status
                            FROM
                            ( # get the current price depending if there is a promo or not
                              SELECT A.product_id, IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                              B.stock_qty, B.stock_status_id
                              FROM products as A
                              JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                              WHERE A.hidden=0
                            ) as A
                            GROUP BY A.product_id
                          ) as A

                          JOIN

                          (
                          SELECT A.*
                          FROM
                            (
                            SELECT A.product_id, A.brand_id, A.name, A.short_description, A.enable_stock_mgmt,
                            B.variant_id as variant_id, B.regular_price as regular_price, B.sales_price as sales_price,
                            B.sales_price_start_date as sales_price_start_date, B.sales_price_end_date as sales_price_end_date,
                            B.stock_qty as stock_qty, B.stock_status_id as stock_status_id,
                            IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                            D.img as img, A.created_at
                            FROM products as A
                            JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                            LEFT JOIN product_images as D ON A.product_id = D.product_id AND D.is_primary = 1
                            WHERE A.hidden=0
                            GROUP BY A.product_id, current_price
                            ) as A
                          ) as B ON A.product_id = B.product_id AND A.min_price = B.current_price


                          JOIN

                          (
                          SELECT product_id, SUM(quantity) as nb_of_order
                          FROM order_items
                          WHERE hidden = 0
                          GROUP BY product_id
                          ) as C
                          ON B.product_id = C.product_id

                          ORDER BY C.nb_of_order DESC");

        return $q;
    }

    /*-------------------------------------------
    get all the products for a selected collection
    --------------------------------------------*/
    public function getProductsOfCollection($tag_id)
    {
        $q = \DB::select(
            "SELECT A.*, B.*
                          FROM
                          (#get the minimum price and the total stock qty and status to make sure that we have at least 1 variant with stocks
                            SELECT A.product_id, MIN(A.current_price) as min_price, SUM(A.stock_qty) as total_stock_qty, SUM(A.stock_status_id) as total_stock_status
                            FROM
                              (# get the current price depending if there is a promo or not
                              SELECT A.product_id, IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                              B.stock_qty, B.stock_status_id
                              FROM products as A
                              JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                              JOIN products_has_tags as C ON A.product_id = C.product_id AND C.tag_id = :tag_id
                              WHERE A.hidden=0
                              ) as A
                            GROUP BY A.product_id
                          ) as A

                          JOIN

                          (
                            SELECT A.*
                              FROM
                              (#get all the needed info of the product
                                SELECT A.product_id, A.brand_id, A.name, A.short_description, A.enable_stock_mgmt,
                                B.variant_id as variant_id, B.regular_price as regular_price, B.sales_price as sales_price,
                                B.sales_price_start_date as sales_price_start_date, B.sales_price_end_date as sales_price_end_date,
                                B.stock_qty as stock_qty, B.stock_status_id as stock_status_id,
                                IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                                D.img as img, A.created_at
                                FROM products as A
                                JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                                JOIN products_has_tags as C ON A.product_id = C.product_id AND C.tag_id = :tag_id_2
                                LEFT JOIN product_images as D ON A.product_id = D.product_id AND D.is_primary = 1
                                WHERE A.hidden=0
                                GROUP BY A.product_id, current_price
                              ) as A
                          ) as B ON A.product_id = B.product_id AND A.min_price = B.current_price",
            [':tag_id' => $tag_id, ':tag_id_2' => $tag_id]
        );
        return $q;
    }

    /*-------------------------------------------
    get the reuslt of the search keyword
    --------------------------------------------*/
    public function search($keyword)
    {
        $keyword = addslashes($keyword);
        $q = \DB::select(
            "SELECT A.product_id, A.name, B.img
                        FROM products as A
                        LEFT JOIN product_images as B ON A.product_id = B.product_id AND B.hidden = 0 AND B.is_primary = 1
                        WHERE A.name LIKE '%" .
                $keyword .
                "%'
                        AND A.hidden = 0
                       "
        );
        return $q;
    }

    public function searchResults($keyword)
    {
        $keyword = addslashes($keyword);
        $p = \DB::select(
            "SELECT A.*, B.*
                          FROM
                          (#get the minimum price and the total stock qty and status to make sure that we have at least 1 variant with stocks
                          SELECT A.product_id, MIN(A.current_price) as min_price, SUM(A.stock_qty) as total_stock_qty, SUM(A.stock_status_id) as total_stock_status
                          FROM
                          (# get the current price depending if there is a promo or not
                          SELECT A.product_id, IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                          B.stock_qty, B.stock_status_id
                          FROM products as A
                          JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                          WHERE A.hidden=0
                          ) as A
                          GROUP BY A.product_id
                          ) as A

                          JOIN

                          (
                          SELECT A.*
                          FROM
                          (#get all the needed info of the product
                          SELECT A.product_id, A.brand_id, A.name, A.short_description, A.enable_stock_mgmt,
                            B.variant_id as variant_id, B.regular_price as regular_price, B.sales_price as sales_price,
                            B.sales_price_start_date as sales_price_start_date, B.sales_price_end_date as sales_price_end_date,
                            B.stock_qty as stock_qty, B.stock_status_id as stock_status_id,
                          IF(B.sales_price_end_date >= NOW() AND B.sales_price_start_date <= NOW(), B.sales_price, B.regular_price) as current_price,
                          D.img as img, A.created_at
                          FROM products as A
                          JOIN variants as B ON A.product_id = B.product_id AND B.hidden = 0
                          LEFT JOIN product_images as D ON A.product_id = D.product_id AND D.is_primary = 1
                          WHERE A.hidden=0
                            GROUP BY A.product_id, current_price
                          ) as A
                          ) as B ON A.product_id = B.product_id AND A.min_price = B.current_price

                          WHERE name LIKE '%" .
                $keyword .
                "%'
                       "
        );
        return $p;
    }
}
