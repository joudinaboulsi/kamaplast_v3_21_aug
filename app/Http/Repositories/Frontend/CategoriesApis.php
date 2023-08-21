<?php

namespace App\Http\Repositories\Frontend;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\QueryException;

class CategoriesApis
{
    /*----------------------------------
    Show the list of categories
    ------------------------------------*/
    public function show()
    {
        $q = \DB::select('SELECT * FROM categories');
        return $q;
    }

    /*----------------------------------
    Show a category details
    ------------------------------------*/
    public function showDetails($category_id)
    {
        $q = \DB::select(
            "SELECT * FROM categories
                          WHERE category_id = :category_id
                          AND hidden = 0",
            [':category_id' => $category_id]
        );
        return $q;
    }

    /*----------------------------------
    Show the list of categories with the number of products related
    ------------------------------------*/
    public function showAndCount()
    {
        $q = \DB::select("SELECT A.*, COUNT(B.product_id) as nb_of_products
                          FROM categories as A
                          LEFT JOIN 
                          (SELECT A.*, B.hidden
                          FROM products_has_categories as A
                          JOIN products as B ON A.product_id = B.product_id AND B.hidden=0) as B
                          ON A.category_id=B.category_id 
                          GROUP BY A.category_id");
        return $q;
    }

    /*----------------------------------
    Get list of PARENT categories
    ------------------------------------*/
    public static function getParentCategories()
    {
        $q = \DB::select("SELECT A.category_id as category_id,A.name as category_name, COUNT(B.product_id) as category_count
                          FROM categories as A
                          LEFT JOIN products_has_categories as B ON A.category_id = B.category_id
                          LEFT JOIN products as C ON B.product_id = C.product_id AND C.hidden=0
                          WHERE parent_id IS NULL
                          GROUP BY A.category_id
                          ORDER BY A.name ASC");

        return $q;
    }

    /*----------------------------------
    Get a Category list of Children From category ID
    ------------------------------------*/
    public static function getCategoryChildFromCategoryId($category_id)
    {
        $q = \DB::select(
            "SELECT A.category_id as category_id, A.name as category_name, COUNT(B.product_id) as category_count,A.parent_id as parent_id
                          FROM categories as A
                          LEFT JOIN products_has_categories as B ON A.category_id = B.category_id
                          LEFT JOIN products as C ON B.product_id = C.product_id AND C.hidden=0
                          WHERE A.parent_id=:category_id
                          GROUP BY A.category_id
                          ORDER BY A.name ASC",
            [':category_id' => $category_id]
        );

        return $q;
    }

    /*----------------------------------
    Get the higlighted categories
    ------------------------------------*/
    public function getHighlightedCategories()
    {
        $q = \DB::select("SELECT *
                        FROM categories
                        WHERE hidden =0
                        AND highlight = 1");
        return $q;
    }
}
