<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\Frontend\CategoriesApis;
use App\Http\Repositories\Frontend\ProductApis;
use App\Http\Repositories\Frontend\UsersApis;
use App\Http\Controllers\Frontend\PagesController;
use App\Http\Repositories\SeoRepository;
use Carbon\Carbon;
use Auth;
use Mail;
use DB;
use SEO;
use SEOMeta;

class ProductsController extends Controller
{
    /**
     * @var PagesRepository;
     */
    private $categoriesApis;
    private $productApis;
    private $usersApis;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PagesController $pagesController, CategoriesApis $categoriesApis, ProductApis $productApis, UsersApis $usersApis, SeoRepository $seoRepository)
    {
        $this->categoriesApis = $categoriesApis;
        $this->productApis = $productApis;
        $this->usersApis = $usersApis;
        $this->seoRepository = $seoRepository;
        $this->pagesController = $pagesController;
    }


    // clean the trailing zeros after decimal number
    public static function cleanNum($nbr)
    {
      return strpos($nbr,'.')!==false ? rtrim(rtrim($nbr,'0'),'.') : $nbr;

      return $num;
    }

    //Get the minimum and maximum price of the products in the selected category. 
    public function minMaxInterval($product_list)
    {
        if(empty($product_list))
            return false; // no minimum or maximun filter price range
        else
        {
            $interval['min_price'] = $this->cleanNum(min(array_column($product_list, 'min_price')));
            $interval['max_price'] = $this->cleanNum(max(array_column($product_list, 'min_price')));
        }

        return $interval;
    }   

    //category page
    public function category($seo_category_link)
    {   
        $tab = explode('-', $seo_category_link); // explode the seo_url to get the category_id
        $category_id = end($tab); // get the last value of the exploded array which is the category_id

        // clear all filter sessions
        session()->forget('brand_box');
        session()->forget('tag_box');
        session()->forget('discount_box');
        session()->forget('min_price');
        session()->forget('max_price');

        $category= $this->categoriesApis->showDetails($category_id); //Show all the subcategories

        //call for setSeo() to set seo for this page
        $this->pagesController->setSeo($category[0], 'product');

        $parentCategories = $this->categoriesApis->getParentCategories(); //Show all the root categories
        $brands = $this->productApis->getBrandsByCategory($category_id); // Show all the brands of the products for a selected category
        $show_list = $this->productApis->showProductListByCategory($category_id); // Show all the products by category
        $interval = $this->minMaxInterval($show_list); //Get the minimum and maximum price of the products in the selected category.      
        $tags = $this->productApis->getProductTagsByCategory($category_id); // Show all the tags of the products for a selected category
        $highlights = $this->categoriesApis->getHighlightedCategories(); // get the higlighted categories

        return view('frontend.products.category',  array('category' => $category, 'category_id' => $category_id, 'parentCategories' => $parentCategories, 'brands' => $brands, 
            'show_list' => $show_list, 'interval' => $interval, 'tags' => $tags, 'highlights' => $highlights, 'now' => Carbon::now('Asia/Beirut')->format('Y-m-d')));
    }

    //product details page
    public function product($seo_url)
    {   
        //get url of the page
        $url = url()->current();
        //get OG twitter
        $og_tw=$this->seoRepository->showOGTwitter();

        $tab = explode('-', $seo_url); // explode the seo_url to get the product_id
        $product_id = end($tab); // get the last value of the exploded array which is the product_id

        $product = $this->productApis->show($product_id); //Shows the information of a specific selected product
            //   dd( $product);


        SEO::setTitle($product[0]->seo_title);
        SEO::setDescription(strip_tags($product[0]->seo_meta_desc));
        SEOMeta::setKeywords($product[0]->seo_meta_keywords);
        SEO::opengraph()->setUrl($url);
        SEO::setCanonical($url);
        SEO::opengraph()->addProperty('type', 'products');
        SEO::twitter()->setSite('@'.$og_tw[0]->og_twitter);
        if($product[0]->img != NULL) // check if product has an image for og img
            SEO::addImages(getenv('S3_URL').'/products/'.$product[0]->img);

        $prices = $this->productApis->getPriceIntervalbyProduct($product_id); //Show the price interval of a specific product
        $images = $this->productApis->getProductImages($product_id); //Shows the main images of the product
        $variants = $this->productApis->getProductVariants($product_id); //Shows the list of variants for a selected product
        $attributes = $this->productApis->getProductAttributes($product_id); //Shows the list of attributes for a specific product
        $attrItems = $this->productApis->getAttributeItems($product_id); //Shows the list of attribute items for a specific product
        $similarProducts = $this->productApis->getSimilarProducts($product_id); //Shows the similar products of a specific product
        $linkedProducts = $this->productApis->getLinkedProducts($product_id); //Shows the linked products of a specific product
       $video = $this->productApis->getVideos($product_id);
        // dd($video);
        //Algorithm to display by default the Price range of the selected products based on its variants

        $min_regular = $prices[0]->min_regular_price; //Get minimum regular price
        $max_regular = $prices[0]->max_regular_price; //Get maximum regular price
        $max_current = $prices[0]->max_current_price; //Get maximum current price
        $min_current = $prices[0]->min_current_price; //Get minimum current price

        //If there is one unique current price (No Interval in Current Price)
        if($min_current == $max_current)
        {
            // If there is one unique Regular price (No interval in Regular price)
            if($min_regular == $max_regular)
            {
                if($min_current == $min_regular)
                {
                    $actual_price = '$'.floatval($min_current);
                    $old_price = '';
                }
                else
                {
                    $actual_price = '$'.floatval($min_current);
                    $old_price = '$'.floatval($min_regular);
                }
            }
            // If there is a range of regular price
            else
            {
                $actual_price = '$'.floatval($min_regular).' - $'.floatval($max_regular);
                $old_price = '$'.floatval($min_current);
            }
        }
        // Ig there is a range of Current price
        else
        {
            // If there is one unique Regular price (No interval in Regular price)
            if($min_regular == $max_regular)
            {
                $actual_price = '$'.floatval($min_regular);
                $old_price = '$'.floatval($min_current).' - $'.floatval($max_current);
            }
            // If there is a range of regular price
            else
            {
                // If there is one unique Regular price (No interval in Regular price)
                if($min_current == $min_regular && $max_current == $max_regular)
                {
                    $actual_price = '$'.floatval($min_current).' - $'.floatval($max_current);
                    $old_price = '';
                }
                else
                {
                    $actual_price = '$'.floatval($min_current).' - $'.floatval($max_current);
                    $old_price = '$'.floatval($min_regular).' - $'.floatval($max_regular);
                }
            }
        }

        return view('frontend.products.product', array('product' => $product, 'images' => $images, 'attributes' => $attributes, 'attrItems' => $attrItems , 'variants' => $variants, 'similarProducts' => $similarProducts, 'linkedProducts' => $linkedProducts, 'prices' => $prices, 'actual_price' => $actual_price, 'old_price' => $old_price ,'video' =>$video));
    }


    // transform the name and the id of the product to a SEO friendly url
    public static function generateSeoUrlLink($name, $id)
    {
        $seo = preg_replace("![^a-z0-9]+!i", "-", $name); // replace al symbols by -
        $seo = strtolower($seo); // transform to lower case
        $seo = str_replace('--',"-",$seo); // remove repeated dashses that replaced the symbols and spaces
        $seo = $seo.'-'.$id; // add the product id at the end
        $seo = str_replace('--',"-",$seo); // execute twice if repeated characters still appear

        return $seo;
    }


    // function that displays the product element 
    public static function displayProductElement($product)
    {   
        $now = Carbon::now('Asia/Beirut')->format('Y-m-d h:i'); 
        $seo_link = ProductsController::generateSeoUrlLink($product->name, $product->product_id);

        ?>

        <div class="product">

            <!-- Product Image -->
            <div class="image">
                <!-- <div class="quickview">
                    <a data-toggle="modal" class="btn btn-xs btn-quickview" href="#" data-target="#productSetailsModalAjax">Quick View </a>
                </div> -->
                <a href="<?php echo route('product_details_path', $seo_link); ?>">
                    <img src="<?php echo getenv('S3_URL').'/products/'.$product->img; ?>"  loading="lazy" alt="Product Image" class="img-responsive">
                </a>
                <div class="promotion">
                    <!--/.IF THERE IS NO STOCK IN THIS PRODUCT WE HAVE TO ADD A TAG OUT OF STOCK-->
                    <?php 
                    if(($product->enable_stock_mgmt == 1 AND ($product->total_stock_qty == 0 OR $product->total_stock_qty == null)) OR ($product->enable_stock_mgmt == 0 AND ($product->total_stock_status == 0 OR $product->total_stock_status == null)))
                    {
                    ?>
                    
                    <span class="new-product"> OUT OF STOCK </span> 
                    <!--/.IF THERE IS STILL STOCK IN THE PRODUCT -->
            
            <?php   
                    }
                    else
                    {
                        // IF THERE IS A DISCOUNT WE DISPLAY THE DISCOUNT TAG 
                        if($product->min_price ==  $product->sales_price && $product->min_price != 0) 
                        {
            ?>
                        <span class="discount">
                        <?php 
                            $sale_percentage =  (1-$product->sales_price/$product->regular_price) * 100;;
                            $sale_percentage = number_format($sale_percentage,2,".","'"); 
                            $sale_percentage = strpos($sale_percentage,'.')!==false ? rtrim(rtrim($sale_percentage,'0'),'.') : $sale_percentage;
                    
                            echo $sale_percentage; ?>% OFF
                        </span>
            <?php 
                        }   
            ?>
                    
                <?php } // end if else?>

                </div>
            </div>
            <!-- End Product Image -->

            <!-- Product Description -->
            <div class="description">
                <h4><a href="<?php echo route('product_details_path', $seo_link); ?>"><?php echo $product->name; ?></a></h4>
                <div class="grid-description">
                    <p class="hidden-xs"><?php echo strip_tags($product->short_description); ?></p>
                </div>
                <div class="list-description">
                   <p class="hidden-xs"><?php echo strip_tags($product->short_description); ?></p>
                </div>

                <!-- Product Price -->
                <!--/.IF THERE IS A SALES PRICE-->
            <?php
                if($product->sales_price != null && strtotime($product->sales_price_start_date) <= strtotime($now) && strtotime($product->sales_price_end_date) >= strtotime($now) )
                {
            ?>        <div class="price price_in_list">
                        <span>$<?php echo floatval($product->sales_price); ?></span> 
                        <span class="old-price"><strike>$<?php echo floatval($product->regular_price);?></strike></span></div>
            <?php
                }
                //IF THERE IS NO SALES PRICE
                else
                {
            ?>
                    <div class="price price_in_list"><span>$<?php echo floatval($product->regular_price); ?></span></div>
            <?php
                }
            ?>
    

            </div>
             <!-- End Product Description -->
  
            <?php
                if(config('global.MULTIPLE_VARIANTS') == 0) 
                {
            ?>
                <!-- Add to Cart -->
                <div class="action-control">
                     <button class="btn btn-stroke btn-dark thin add_to_cart" title="Add to Cart" type="button" href="" qty="1" variantid="<?php echo $product->variant_id; ?>">
                        Add to Cart
                    </button>
                </div>
                <!-- End Add to Cart -->
            <?php 
                }
                else
                {
             ?>
                <!-- Know More -->
                <div class="action-control">
                     <a class="btn btn-stroke btn-dark thin" title="Know More" href="<?php echo route('product_details_path', $seo_link); ?>">
                        Buy Now
                    </a>
                </div>
                <!-- End Know More -->
            <?php 
                }
             ?>    
                <!-- End Product Price -->
   
        </div>

<?php   
    }


    //Ajax call getting the list of variants based on selected attributes
    public function getVariantFromAttributes(Request $request)
    {
        $product_id = $request->product_id;
        $attribute_items_selected = $request->attribute_items;
        $nb_attribute_items = $request->nb_attribute_items;

        $data['attributes_we_can_click'] = $this->productApis->getAttributeItemsWeCanClick($product_id, $attribute_items_selected, $nb_attribute_items); //Returns list of clickable attributes based on attributes pre-selection
        $data['variants_prices'] = $this->productApis->getVariantPriceIntervalByAttributes($product_id, $attribute_items_selected, $nb_attribute_items); //Returns price range of selected variants based on attribute selection
        $data['variant_list'] = $this->productApis->getVariantsByAttributes($product_id, $attribute_items_selected, $nb_attribute_items); //Returns the list of variants that can be selected based on the attributes selection

        return response()->json($data);
    }


    // controller that set and unset the filters session for brands, tags and discounts
    public function setFilterSession(Request $request)
    {   
        // the dynamic name of the input
        $name = $request->input('name');

        // if the checkbox is checked 
        if($request->input('checked') == 'true')
        {   
            if(session()->has($name)) // check if the checked input has already a session to its attributes
                $tmp_arr = session($name); //put the session array in a temp array
            else 
                $tmp_arr =[]; // if no session is present create the temp array from scratch

            array_push($tmp_arr, $request->input('my_value')); // push the selected value into the temp array

            session([$name => $tmp_arr]); // put the temp array in the session of the related attribute
        }  

        else  // if we unchecked the input 
        {   
            $tmp_arr = session($name); // put the actual array session in a temp array
            $value = $request->input('my_value'); // the clicked id 

            if(sizeof($tmp_arr) <= 1) // if the array has only 1 value
                session()->forget($name);  // delete the session completely

            else // if t he array has more than a value
            {
                $key = array_search($value, $tmp_arr); //detect the key of the clicked value in the array
                unset($tmp_arr[$key]); // remove that value from the array
                session([$name => $tmp_arr]); // rebuild my session with the new array
            }
        }    
        
        return response()->json(session($name));
    }


    // controller that set and unset the filters session for price range
    public function filterByPriceRange(Request $request)
    { 
      // set min and max price session
      session(['min_price' => $request->input('min_price')]); 
      session(['max_price' => $request->input('max_price')]);

      return response()->json('');
    }


    // return the view of the dynamic products
    public function loadDynamicProducts($category_id)
    {   
        $category=$this->categoriesApis->showDetails($category_id); //Show all the subcategories
        //call for setSeo() to set seo for this page
        $this->pagesController->setSeo($category[0], 'product');
        
        $showFilteredProductByCategory = $this->productApis->showFilteredProductByCategory($category_id); // Show all the products by category

        $interval = $this->minMaxInterval($showFilteredProductByCategory); //Get the minimum and maximum price of the products in the selected category.      

        return view('frontend.products.includes.product-list', array('categoryProductsNb' => 100, 'show_list' => $showFilteredProductByCategory, 'interval' => $interval, 'now' => Carbon::now('Asia/Beirut')->format('Y-m-d')));
    }


    // ASCENDING function that compare the current prices of 2 products => TO BE USED IN THE SORTING FUNCTION AFTER
    public function ascendingSort($a, $b)
    {
        if ($a->current_price == $b->current_price) {
            return 0;
        }
        return ($a->current_price < $b->current_price) ? -1 : 1; // ASCENDING order by current_price
    }   

    // DESCENDING function that compare the current prices of 2 products => TO BE USED IN THE SORTING FUNCTION AFTER
    public function descendingSort($a, $b)
    {
        if ($a->current_price == $b->current_price) {
            return 0;
        }
        return ($a->current_price > $b->current_price) ? -1 : 1; // DESCENDING order by current_price
    }   

    // LATEST product function that compare the created_date date of the products => TO BE USED IN THE SORTING FUNCTION AFTER
    public function latestSort($a, $b)
    {
        if ($a->created_at == $b->created_at) {
            return 0;
        }
        return ($a->created_at > $b->created_at) ? -1 : 1; // DESCENDING order by created_at date
    }  


    // filter the products by ascending or descending price
    public function sortProducts(Request $request)
    {   

        $show_list = $request->input('show_list');

        $product_list = [];
        
        foreach($show_list as $s)
            array_push($product_list, (object)$s);


        $direction = $request->input('sort_by');


        switch ($direction) {
            case 'ASC':
                usort($product_list, array($this, "ascendingSort"));   // Sort by ascending price
                break;

             case 'DESC':
                usort($product_list, array($this, "descendingSort"));  // Sort by descending price 
                break;

             case 'NEWEST':
                usort($product_list, array($this, "latestSort"));  // Sort by descending date of creation 
                break;
            
            default:
                # code...
                break;
        }

        session(['sorted_prodcuts' => $product_list]); // put the temp array in the session of the related attribute

    }


    // return the view of the sorted products
    public function displaySorted()
    {   
        $sorted_prodcuts = session('sorted_prodcuts');

        $interval = $this->minMaxInterval($sorted_prodcuts); //Get the minimum and maximum price of the products in the selected category.      

        return view('frontend.products.includes.product-list', array('categoryProductsNb' => 100, 'show_list' => $sorted_prodcuts, 'interval' => $interval, 'now' => Carbon::now('Asia/Beirut')->format('Y-m-d')));
    }


}
