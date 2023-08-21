<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\S3bucketController;
use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\SeoRepository;
use Auth;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CategoryRepository $categoryRepository, S3bucketController $s3bucketController, SeoRepository $seoRepository)
    {
        $this->CategoryRepository = $categoryRepository;
        $this->S3bucketController = $s3bucketController;
        $this->seoRepository = $seoRepository;
        $this->middleware('auth:admin');
    }

    //Shows the list of categories
    public function index()
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

        return view('cms/categories/index', array('categoryList' => $categoryList, 'category' => $category));
    }

    //Allows to add a new category
    public function add(Request $request)
    {
        // If the form is filled
        if ($request->filled(['name']))
        {
            $imageFileName = $this->S3bucketController->fillInputWithImageForAdd($request, 'image', 'categories', 'categories/thumbs', '600', NULL, '100', '67');

            $og_img = $this->S3bucketController->fillInputWithImageForAdd($request, 'og_image', 'seo', 'seo/thumbs', '1200', NULL, '100', '67');

            // Adding a category
            $this->CategoryRepository->add($request, $imageFileName, $og_img); 
        }
           
        return redirect()->back();
    }


    //Allows to update a category
    public function update(Request $request)
    {
        // If the form is filled
        if ($request->filled(['edit_name']))
        {
            // get the old info of the image
            $info = $this->CategoryRepository->getCategoryDataFromId($request->input('edit_id'));
            
            // process the image, compress and resize to create original img and thumb img. Return the image name
            $imageFileName = $this->S3bucketController->fillInputWithImageForEdit($request, 'edit_image', 'categories', 'categories/thumbs', '600', NULL, '100', '67', $info[0]->img);

            // process the image, compress and resize to create original img and thumb img. Return the image name
            $e_og_img = $this->S3bucketController->fillInputWithImageForEdit($request, 'edit_og_image', 'seo', 'seo/thumbs', '1200', NULL, '100', '67', $info[0]->og_image);

            // Editing a category
            $this->CategoryRepository->update($request, $imageFileName, $e_og_img); 
        }

        return redirect()->back();
    }


    // highligh a category
    public function highlightCategory(Request $request)
    {
      $data = $this->CategoryRepository->highlightCategory($request);
       // return the info to the ajax call
       return response()->json($data);
    }
    


    // Allows to delete a category
    public function delete(Request $request)
    { 
        $category_id = $request->input('id');

        //Get the category informations
        $info = $this->CategoryRepository->getCategoryDataFromId($category_id); 
        
          if ($info[0]->parent_id==0) //if it's the Root
          {
            //Fetch the category children
            $childInfo = $this->CategoryRepository->getCategoryChildFromCategoryId($category_id);

            if($childInfo) //if it has children
            {
              foreach ($childInfo as $child)
              {
                //update the parent id of the child (to 0)
                $this->CategoryRepository->updateParentId($child->category_id,0);
              }
            }

            //Delete this category
            $this->CategoryRepository->delete($category_id);
          }

          else if ($info[0]->parent_id!=0)
          {
            //Fetch the category children
            $childInfo = $this->CategoryRepository->getCategoryChildFromCategoryId($category_id);

            if($childInfo) //if it has children
            {
              //put the parent id in a variable
              $parent = $info[0]->parent_id;
              foreach ($childInfo as $child)
              {
                //update the parent id of the child (to $parent)
                $this->CategoryRepository->updateParentId($child->category_id,$parent);
              }
            }

            //Delete this category
            $this->CategoryRepository->delete($category_id);
          }

        return response()->json();
    }


   
    // Gets category details from category_id
    public function getCategoryDataFromId(Request $request)
    {
       $data = $this->CategoryRepository->getCategoryDataFromId($request->input('id'));
       // return the info to the ajax call
       return response()->json($data);
    }


    //Algorithm that allows to make the hierarchical display of categories
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


    //Function allowing to recursively display the datatable listing the tree of categories
    //The array is the tree that we want to parse
    //The level defines the level of hierarchy of the category
    public static function recursiveDisplay($array, $level = 1, $has_checkboxes=0){
      $c = 0;
      foreach($array as $key => $value){

          //If $value is an array.
          if(is_array($value)){    
               //We need to loop through it.
              self::recursiveDisplay($value, $level + 1, $has_checkboxes);
          } 
          //If the value is not an array
          else{
               //If the key that we are parsing is the category ID
              if ($key == 'category_id'){
                   //we store the category id
                  $category_id = $value; 
                  echo '<tr class="gradeX" id="rec_'.$category_id.'">';
              }
              //If the key that we are parsing are the name, the description or the image, we can display them in the table           
              elseif ($key == 'name')
              {
              
                 if($level-2 == 0)
                   echo '<td><span class="label label-primary b_radius" style="margin-right:10px;">Level 1</span> <b>'.$value.'</b></td>';
                 elseif($level-2 == 2)
                 {
                    echo '<td><span class="label label-warning b_radius" style="margin-right:10px;">Level 2</span> ', str_repeat("~", $level-2), $value, '</td>';
                 }
                elseif($level-2 == 4)
                  echo '<td><span class="label label-danger b_radius" style="margin-right:10px;">Level 3</span> ', str_repeat("~", $level-2), $value, '</td>';
              }
              elseif($key == 'description')
                  echo '<td>', $value, '</td>';

              elseif ($key == 'used_category_id'){
                    if (is_null($value))
                      echo '<td> <input type="checkbox" value="'.$category_id.'" name="categories[]"/> </td>';
                    else
                      echo '<td> <input type="checkbox" value="'.$category_id.'" name="categories[]" disabled/> </td>';
                  
              }
              // getenv('S3_URL')/categories/thumbs/'.$value.'
              if($has_checkboxes == 0)
              { 

                if($key == 'highlight')
                {
                  $highlight = $value;
                

                // check if the highlight field is checked or not
                  if($highlight == 1)
                    $checked = 'checked';
                   else
                    $checked = '';


                echo '<td class="center">';
                  echo '<div class="controls" style="display:inline">';
                    echo '<div class="switch">';
                      echo '<div class="onoffswitch">';
                        echo '<input type="checkbox" onclick="ajaxCategHighlight('.$category_id.')" class="onoffswitch-checkbox" id="highlight_'.$category_id.'" name="highlight_'.$category_id.'" data-toggle="collapse" data-target="#demo" value="1"'.$checked.'>';
                        echo '<label class="onoffswitch-label" for="highlight_'.$category_id.'">';
                          echo '<span class="onoffswitch-inner"></span>';
                          echo '<span class="onoffswitch-switch"></span>';
                        echo '</label>';
                      echo '</div>';
                    echo '</div>';
                  echo '</div>';
                echo '</td>'; 

                }  



                //If the key is at the last iteration of the element, we need to add the action buttons
                if($key == 'updated_by'){

                  echo '<td class="center">';
                   echo '<button onclick="loadDataToEdit('. $category_id .')" data-toggle="modal" data-target="#update_category" type="button" class="edit_btn" title="Edit category"><i class="fa fa-edit fa-lg"></i></button>';
                   if($level-2 != 0) // disable delete for level 1 category
                    echo '<button type="button" id='.$category_id.' class="edit_btn delete_category" title="Delete category"><i class="fa fa-trash fa-lg"></i></button>';

                  echo '</td>';

                }   
              }
              
          }
      }
    }

  //Function allowing recursively to display the select box in add and edit modals
  //The array is the tree that we want to parse
  //The level defines the level of hierarchy of the category
  public static function recursiveSelect($array, $level = 1){
   foreach($array as $key => $value){
       //If $value is an array.
       if(is_array($value)){
           //We need to loop through it.
           self::recursiveSelect($value, $level + 1);
       } 
       else{
        //While parsing the category id we store it in a variable
        if ($key == 'category_id')
          $category_id = $value;
        //While parsing the name we create the option and associate it to the category id
        elseif ($key == 'name')
        {
            if($level-2 <= 3)
              //It is not an array, so print it out.
              echo '<option value="'. $category_id .'">', str_repeat("&nbsp;", $level-2), $value, '</option>';
        }
            
       }
   }
}


public function logout()
{ dd('logout from admin');
  // \Auth::logout();
  
   return redirect('/admin/login');
}



}
