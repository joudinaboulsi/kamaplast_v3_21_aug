<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\CategoryRepository;
use Auth;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->CategoryRepository = $categoryRepository;
    }


    //build the menu dynamically
    public function buildMenu()
    {
        // Returns the list of categories
        $category = $this->CategoryRepository->show();
        // dd($category);
        // Transforming the objects in arrays
        $categ_array = array();
        foreach ($category as $c){
           $categ = (array) $c;
           array_push($categ_array, $categ);
        }
        // Calling recursive function to generate a tree of categories
        $categoryList = $this->buildTree($categ_array);

        return $categoryList;
    }


    public function buildTree(array $elements, $parentId = 0) 
    { 
       $branch = array();
       foreach ($elements as $element) {
           if ($element['parent_id'] == $parentId) {
               $children = $this->buildTree($elements, $element['category_id']);
               if ($children) {   
                   $element['children'] = $children;
               }
               $branch[] = $element;
           }    
       }
       
       return $branch;
    }

}