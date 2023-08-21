<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\S3bucketController;
use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\CategoryRepository;
use App\Http\Controllers\CategoryController;
use App\Http\Repositories\BrandsRepository;
use App\Http\Repositories\AttributesRepository;
use App\Http\Repositories\VariantsRepository;
use App\Http\Repositories\TagsRepository;
use Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductRepository $productRepository, S3bucketController $s3bucketController, 
        CategoryRepository $categoryRepository, CategoryController $categoryController, 
        BrandsRepository $brandsRepository, AttributesRepository $attributesRepository, VariantsRepository $variantsRepository,
        TagsRepository $tagsRepository)
    {
        $this->ProductRepository = $productRepository;
        $this->S3bucketController = $s3bucketController;
        $this->CategoryRepository = $categoryRepository;
        $this->CategoryController = $categoryController;
        $this->BrandsRepository = $brandsRepository;
        $this->AttributesRepository = $attributesRepository;
        $this->VariantsRepository = $variantsRepository;
        $this->TagsRepository = $tagsRepository;
        
        $this->middleware('auth:admin');
    }


    /*--------------------------------------------------
      PRODUCTS GENERAL INFORMATION
     --------------------------------------------------*/

    //Shows the list of products
    public function index()
    {
        // Returns the list of categories
        $category = $this->CategoryRepository->show();
        // Transforming the objects in arrays
        $categ_array = array();
        foreach ($category as $c){
           $categ = (array) $c;
           array_push($categ_array, $categ);
        }
        // Calling recursive function to generate a tree of categories
        $categoryList = $this->CategoryController->buildTree($categ_array);

        // return the name and the id of all the products - USED FOR THE POSITIONING POP UP
        $products = $this->ProductRepository->showLight();
 
        return view('cms/products/index', array('categoryList' => $categoryList, 'products' => $products));
    }


    // Shows product details
    public function showDetails($product_id)
    {
       $product_info = $this->ProductRepository->showDetails($product_id); 
      
       $prices = $this->ProductRepository->showPriceInterval($product_id);
       $variantsList = $this->VariantsRepository->show($product_id);
       $categories = $this->CategoryRepository->getProductCategories($product_id);
       $all_attributes = $this->AttributesRepository->showAll($product_id);
       $attributes = $this->AttributesRepository->show($product_id);
       $main_attribute = $this->AttributesRepository->showMain($product_id);
       $main_attribute_type = $this->AttributesRepository->getMainAttributeType($product_id);
       $main_attribute_items = $this->AttributesRepository->showMainAttributeItems($product_id);
       $attrItems = $this->AttributesRepository->showAttributeItems($product_id);
       $images = $this->ProductRepository->getImages($product_id);
       $tags = $this->TagsRepository->getProductTags($product_id);
       $videos = $this->ProductRepository->getVideos($product_id);
      
       $tagsList = $this->TagsRepository->getUnlinkTagsToProducts($product_id);
       $brandsList = $this->BrandsRepository->show();
       $prod_list = $this->ProductRepository->getProductsList($product_id);
       $linked_products = $this->ProductRepository->getLinkedProducts($product_id);
       
        // Returns the list of categories that are not used for this product
        $category = $this->CategoryRepository->showUnusedCategories($product_id);
        // Transforming the objects in arrays

        $categ_array = array();
        foreach ($category as $c){
           $categ = (array) $c;
           array_push($categ_array, $categ);
        }
       // Calling recursive function to generate a tree of categories
       $categoryList = $this->CategoryController->buildTree($categ_array);

       return view('cms/products/product-details', array( 'product_info' => $product_info, 'prices' => $prices, 'variantsList' => $variantsList ,'categories' => $categories, 'main_attribute' => $main_attribute, 'main_attribute_type' => $main_attribute_type, 'main_attribute_items' => $main_attribute_items, 'all_attributes' => $all_attributes, 'attributes' => $attributes, 'images' => $images, 'tags' => $tags, 'tagsList' => $tagsList, 'attrItems' => $attrItems, 'categoryList' => $categoryList, 'brandsList' => $brandsList, 'videos' => $videos, 'prod_list' => $prod_list, 'linked_products' => $linked_products ));
    }


    //Opens the page that allows the user creating a product
    public function create()
    {
        // Returns the list of categories
        $category = $this->CategoryRepository->show();
        // Transforming the objects in arrays
        $categ_array = array();
        foreach ($category as $c){
           $categ = (array) $c;
           array_push($categ_array, $categ);
        }
        // Calling recursive function to generate a tree of categories
        $categoryList = $this->CategoryController->buildTree($categ_array);

        // Get the list of brands to select one for the product
        $brandsList = $this->BrandsRepository->show();

       return view('cms/products/create-product', array('categoryList' => $categoryList, 'brandsList' => $brandsList));
    }


    //Actual action of adding a product
    public function add(Request $request)
    {
        // If the form is filled
        if ($request->filled(['name']))
        {
  
          $imageFileName = $this->S3bucketController->uploadProduct(NULL, $request, 'image', 'products/large', 'products', 'products/thumbs', config('global.PRODUCT_LARGE_WIDTH'), config('global.PRODUCT_LARGE_HEIGHT'), config('global.PRODUCT_MEDIUM_WIDTH'), config('global.PRODUCT_MEDIUM_HEIGHT'), config('global.PRODUCT_WIDTH_THUMBS'), config('global.PRODUCT_HEIGHT_THUMBS'));
           
          // Adding a product
          $product_id = $this->ProductRepository->add($request, $imageFileName);
        }
           
        return redirect('product-'.$product_id);
    }

    // Allows to delete a product
    public function delete(Request $request)
    { 
        //Delete this product
        $this->ProductRepository->delete($request);
        // return the info to the ajax call
       return response()->json();  
    }


    //Updates the basic information of the product
    public function updateBasicInfo(Request $request)
    {
      //Get attributes from Main Variant of Product
      $msg = $this->ProductRepository->updateBasicProduct($request);

      // if product is published
      if($msg['status'] == 'success')
        return redirect()->back()->with('success', $msg);  

      //if main variant does not have the correct number of attributes, redirect attributes
        else
      return redirect()->back()->withErrors($msg);

    }

    // update the position of the product
    public function updateProdcutPosition(Request $request)
    {
      $this->ProductRepository->updatePosition($request);
     // flash('The position of your product has been successfully updated')->success();

      return redirect()->back();
    }


    // Allows to publish/unpublish a product
    public function publish(Request $request)
    { 
        //publish/unpublish this product
        $publish = $this->ProductRepository->publish($request);
        // return the info to the ajax call
       return response()->json($publish);  
    }


    //Allows to feature / unfeature a product
    public function feature(Request $request)
    {
        //feature / unfeature a product
         $feature = $this->ProductRepository->feature($request); 
        // return the info to the ajax call
        return response()->json($feature);
    }



    /*--------------------------------------------------
      PRODUCTS IMAGES MANAGEMENT
     --------------------------------------------------*/

    //Allows to Add new product Image
    public function addImage(Request $request)
    {
        $imageFileName = $this->S3bucketController->uploadProduct(NULL, $request, 'image', 'products/large', 'products', 'products/thumbs', config('global.PRODUCT_LARGE_WIDTH'), config('global.PRODUCT_LARGE_HEIGHT'), config('global.PRODUCT_MEDIUM_WIDTH'), config('global.PRODUCT_MEDIUM_HEIGHT'), config('global.PRODUCT_WIDTH_THUMBS'), config('global.PRODUCT_HEIGHT_THUMBS'));

        // Adding a product image
        $this->ProductRepository->AddImage($request,$imageFileName);
    
        return redirect()->back();
    }

    // Allows to delete a product image
    public function deleteImage(Request $request)
    { 
        //Delete the image
        $this->ProductRepository->deleteImage($request);
        //return the info to the ajax call
       return response()->json();  
    }


    //Allows to set a product image as primary
    public function setPrimary(Request $request)
    {
        // Setting the img as primary
        $this->ProductRepository->setImageAsPrimary($request); 
         // return the info to the ajax call
        return response()->json();
    }



    /*--------------------------------------------------
      PRODUCTS TAGS MANAGEMENT
     --------------------------------------------------*/

    //Allows to add tag to product
    public function addTag(Request $request)
    {
        // Adding a tag to product
        $this->TagsRepository->linkToProduct($request); 
        return redirect()->back();
    }


    //Allows to delete linked tag
    public function deleteTag(Request $request)
    {
        // Deleting a product tag
        $this->TagsRepository->unlinkTagFromProduct($request); 
        // return the info to the ajax call
        return response()->json();
    }



    /*--------------------------------------------------
      PRODUCTS VIDEOS MANAGEMENT
     --------------------------------------------------*/

    //Allows to add video to product
    public function addVideo(Request $request)
    {
        // Adding a video to product
        $this->ProductRepository->AddVideo($request); 
        return redirect()->back();
    }


    //Allows to delete linked video
    public function deleteVideo(Request $request)
    {
        // Deleting a product video
        $this->ProductRepository->DeleteVideo($request); 
        // return the info to the ajax call
        return response()->json();
    }



    /*--------------------------------------------------
      PRODUCTS LINKED PRODUCTS MANAGEMENT
     --------------------------------------------------*/

    //Allows to add linked products
    public function addLinkedProduct(Request $request)
    {
        // Adding a linked product
        $this->ProductRepository->addLinkedProduct($request); 
        return redirect()->back();
    }


    //Allows to delete linked products
    public function deleteLinkedProduct(Request $request)
    {
        // Deleting a linked products
        $this->ProductRepository->deleteLinkedProduct($request); 
        // return the info to the ajax call
        return response()->json();
    }

    //Returns the list of products in AJAX datatable
     public function loadProductTable()
     {
        // Returns the list of products
        $product = $this->ProductRepository->show();
        return datatables($product)->make(true);
     }
  
    //Returns the list of products in AJAX datatable
     public function loadProductTableToLink(Request $request)
     {
        // Returns the list of products
        $product = $this->ProductRepository->showNotSelected($request);
        return datatables($product)->make(true);
     }




    /*--------------------------------------------------
      PRODUCTS ATTRIBUTES MANAGEMENT
     --------------------------------------------------*/

    //Allows to add an attribute
    public function addAttribute(Request $request)
    {   
        // Add a Product Attribute
        $this->AttributesRepository->add($request); 
        return redirect()->back();
    }


    // Gets Attribute info from Attribute Id
    public function getAttributeFromId(Request $request)
    {
       $data = $this->AttributesRepository->getAttributeFromId($request->input('id'));
       // return the info to the ajax call
       return response()->json($data);
    }


    // updates an Attribute
    public function updateAttribute(Request $request)
    {
        // updating the Attribute
        $items_to_keep = $this->AttributesRepository->update($request);
        $list = '';
        foreach ($items_to_keep as $key => $value) {
            if ($list == '')
                $list = $value;
            else
                $list = $list.', '.$value;
        }
        if($list != '') 
        {
            $error_msg = 'You cannot delete the following attribute(s): '.$list.'<br/>
                          They are already linked to a variant.<br/>
                          Please unlink these attributes before deleting them.';
            $msg = ['status' => 'error', 'message' => $error_msg];

            return redirect()->back()->withErrors($msg);
        }
        else
            return redirect()->back();
    }


    //Allows to delete an attribute
    public function deleteAttribute(Request $request)
    {
        // deleting a Product Attribute
        $error = $this->AttributesRepository->delete($request); 
        // return the info to the ajax call
        return response()->json($error);
    }

    
    //Allows to add an item to the main attribute
    public function addMainAttributeItem(Request $request)
    {
      // if the main attribute is an image  
      if($request->input('main_attribute_type') == 1) 
      {
        $imageFileName = $this->S3bucketController->uploadProduct(NULL, $request, 'image', 'attribute_items/large', 'attribute_items', 'attribute_items/thumbs', config('global.PRODUCT_LARGE_WIDTH'), config('global.PRODUCT_LARGE_HEIGHT'), config('global.PRODUCT_MEDIUM_WIDTH'), config('global.PRODUCT_MEDIUM_HEIGHT'), config('global.PRODUCT_WIDTH_THUMBS'), config('global.PRODUCT_HEIGHT_THUMBS'));
      }

      else // input is a color
        $imageFileName = NULL; 


        // Add a Product main Attribute item
        $this->AttributesRepository->addMainAttributeItem($request,$imageFileName);
        return redirect()->back();
    }


    // updates main Attribute name
    public function updateMainAttributeName(Request $request)
    {
        // updating main Attribute name
        $this->AttributesRepository->updateMainAttributeName($request);
        return redirect()->back();
    }


    // Gets main Attribute item info from item Id
    public function getMainAttributeItemFromId(Request $request)
    {
       $data = $this->AttributesRepository->getMainAttributeItemFromId($request->input('attribute_item_id'));
       // return the info to the ajax call
       return response()->json($data);
    }


    // updates main Attribute item
    public function updateMainAttributeItem(Request $request)
    {
        // get the old info 
        $data = $this->AttributesRepository->getMainAttributeItemFromId($request->input('edit_id_attribute_item'));

        // check if image has input, process the image, compress and resize to create original img and thumb img. Return the image name
        $imageFileName = $this->S3bucketController->uploadProduct($data[0]->img, $request, 'edit_image', 'attribute_items/large', 'attribute_items', 'attribute_items/thumbs', config('global.PRODUCT_LARGE_WIDTH'), config('global.PRODUCT_LARGE_HEIGHT'), config('global.PRODUCT_MEDIUM_WIDTH'), config('global.PRODUCT_MEDIUM_HEIGHT'), config('global.PRODUCT_WIDTH_THUMBS'), config('global.PRODUCT_HEIGHT_THUMBS'));

        // updating main Attribute item
        $this->AttributesRepository->updateMainAttributeItem($request,$imageFileName);
        return redirect()->back();
    }


    //Allows to delete Main Attribute Item
    public function deleteMainAttributeItem(Request $request)
    {
        // deleting Main Attribute Item
        $error = $this->AttributesRepository->deleteMainAttributeItem($request); 
        // return the info to the ajax call
        return response()->json($error);
    }





    /*--------------------------------------------------
      PRODUCTS VARIANTS MANAGEMENT
     --------------------------------------------------*/

    //Allows to add a Variant
    public function addVariant(Request $request)
    {
        // Add a Product Variant
        $this->VariantsRepository->add($request);       
        
        return Redirect::to(URL::previous() . "#variants_div");
    }


    // Updates a Variant
    public function updateVariant(Request $request)
    {
        // updating the variant
        $this->VariantsRepository->update($request);
        return Redirect::to(URL::previous() . "#variants_div");
    }


    //publish / unpublish variant
    public function publishVariant(Request $request)
    {
        //publish Unpublish the Variant
         $publish = $this->VariantsRepository->publish($request); 
        // return the info to the ajax call
        return response()->json($publish);
    }



    //Allows to delete a product Variant
    public function deleteVariant(Request $request)
    {
        //delete the Variant
        $this->VariantsRepository->delete($request); 
        // return the info to the ajax call
        return response()->json();
    }

     //Allows to delete all product Variants
    public function deleteAllVariants(Request $request)
    {
        //delete all Variants
        $this->VariantsRepository->deleteAll($request); 
        // return the info to the ajax call
        return response()->json();
    }


    //Allows to change a Variant Promo
    public function editVariantPromo(Request $request)
    {
        // Add a Product Variant Promo
        $this->VariantsRepository->editPromo($request); 
        return redirect()->back();
    }


    // Gets Variant Promo from Variant Id
    public function getPromoFromVariantId(Request $request)
    {
       $data = $this->VariantsRepository->getVariantFromId($request->input('variant_id'));
       
       // return the info to the ajax call
       return response()->json($data);
    }

    //Edit Primary image of the selected product
    public function editPrimaryImage(Request $request)
    { 
        // check if image has input, process the image, compress and resize to create original img and thumb img. Return the image name. $old_image param is NULL here because image is a required field and we will always have an image.
        $imageFileName = $this->S3bucketController->uploadProduct(NULL, $request, 'edit_primary_img', 'products/large', 'products', 'products/thumbs', config('global.PRODUCT_LARGE_WIDTH'), config('global.PRODUCT_LARGE_HEIGHT'), config('global.PRODUCT_MEDIUM_WIDTH'), config('global.PRODUCT_MEDIUM_HEIGHT'), config('global.PRODUCT_WIDTH_THUMBS'), config('global.PRODUCT_HEIGHT_THUMBS'));
        
        //update the primary image
        $this->ProductRepository->updatePrimaryImage($request, $imageFileName);        
         
       return Redirect::to(URL::previous() . "#images_div");
    }

    // Gets Variant info from Variant Id
    public function getVariantFromId(Request $request)
    {
       $data = $this->VariantsRepository->getVariantFromId($request->input('variant_id'));
       $data2 = $this->VariantsRepository->getVariantAttrItemsFromId($request->input('variant_id'));

       array_push($data, $data2);
       // return the info to the ajax call
       return response()->json($data);
    }
    


    /*--------------------------------------------------
      PRODUCTS CATEGORIES MANAGEMENT
     --------------------------------------------------*/

    //Allows to link a Category to a product
    public function linkCategory(Request $request)
    {
        //link the Category
        $this->CategoryRepository->linkCategories($request); 
        return redirect()->back();
    }


    //Allows to unlink a Category from a product
    public function unlinkCategory(Request $request)
    {
        //unlink the Category
        $this->CategoryRepository->unlinkCategories($request); 
        // return the info to the ajax call
        return response()->json();
    }


    

    /*--------------------------------------------------
      PRODUCTS DESCRIPTION MANAGEMENT
     --------------------------------------------------*/

    // update the product description 
    public function updateDescriptionInfo(Request $request)
    {
      //Update product basic info
      $this->ProductRepository->updateDescription($request);
      return Redirect::to(URL::previous() . "#description_div");
    }


    // update the SEO info of the product
    public function updateSeoInfo(Request $request)
    {
      //Update product basic info
      $this->ProductRepository->updateSeo($request);
      return Redirect::to(URL::previous() . "#seo_div");
    }



    // ========================= FUNCTION USED FOR MULTIPLE VARIANTS INTEGRATION =================

    // DON'T FORGET THE EDIT MANUALLY THE MAIN AVARIANT AFTER DELETE ONE ATTRIBUTE AND REPLACING ITS ATTRIBUTE ITEM IN THE MAIN VARIANT

     // Combinations for a list of products
     public function db($prod)
     {
       // list of return products
      // $prod = $this->ProductRepository->getprod();
      // dd($prod);
 
  //    foreach($prod as $pr)
  //    {
         // get all the attribute items of the secondary attribute
         $params = $this->ProductRepository->getParamItems($prod);

         // get the main info of the product_id
         $main_variant = $this->ProductRepository->getMainVariantInfo($prod);
 
         // loop all the paramaters
         foreach($params as $p)
         {
           // insert the variant and get its ID
           $variant_id = $this->ProductRepository->insertVariant($prod, $main_variant);
 
           //add the attribute items to the variant
           $this->ProductRepository->insertVariantAttributeItems($variant_id, $p->attribute_item_id);
 
         }
   //   }
     }





     // add combination for a given product
    public function dbMultiple($product_id)
    {
      // get all the attribute items of the main attribute
      $colors = $this->ProductRepository->getColorItems($product_id);

      // get all the attribute items of the secondary attribute
    //  $params = $this->ProductRepository->getParamItems($product_id);

      // get the main info of the product_id
      $main_variant = $this->ProductRepository->getMainVariantInfo($product_id);

      $i = 1; // variable for the SKU to be incremeneted
     
      // loop all the colors / main attribute
      foreach($colors as $c)
      { 
        // loop all the paramaters
        // foreach($params as $p)
        // {
          // insert the variant and get its ID
          $variant_id = $this->ProductRepository->insertVariant($product_id, $main_variant, $i);

          //add the 2 attribute items to the variant
          $this->ProductRepository->insertVariantAttributeItems($variant_id, $c->attribute_item_id);
     //     $this->ProductRepository->insertVariantAttributeItems($variant_id, $p->attribute_item_id);

          $i++;

      }
    }

    
}
