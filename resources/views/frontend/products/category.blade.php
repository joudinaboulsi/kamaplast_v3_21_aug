<?php use App\Http\Controllers\Frontend\ProductsController; ?>

@extends('frontend.layouts.app')

@section('content')



<!-- Page Content  -->
<div class="container main-container headerOffset">
    <div class="row">
        <!-- Top Filter  -->
        <div class="catTopBar clearfix">
            <div class="catTopBarInner clearfix">
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="f-left hidden-xs">
                        <h4 id="filter" class="filterToggle filter-is-off"><i class="fa fa-bars"></i> <strong>Filter</strong> <span> &nbsp; </span></h4>
                    </div>
                </div>

                <div class="col-lg-9 col-md-9 col-sm-12">
                    <div class="f-right">
                        <div class="w100  clearfix">
                            <p class="pull-left shrtByP hidden-xs">
                                <span>Sort By :</span>
                                <a class="active sort_product" data-sort="ASC">Price up</a> | <a class="sort_product" data-sort="DESC">Price down</a> | <a class="sort_product" data-sort="NEWEST">New first</a>
                            </p>
                            
                            <div class="show-hide visible-xs-inline"><img src="/images/icons/funnel.svg" class="img-responsive visible-xs-inline" loading="lazy" width="20"></div>

                            <div class="pull-right" style="margin-right: 25px;"> Showing <strong id="product_nb">{{ count($show_list) }}</strong> products </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Top Filter  -->

        <!-- Content  -->
        <div class="catColumnWrapper filter-is-off">

            <!-- Sidebar  -->
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 white-bg filterColumn side-filter">
                <div class="catTopBar visible-xs">
                    <div class="w100  clearfix center-xs-inner padd-10">
                        <p class=" shrtByP center-xs no-margin">
                            <span>Sort By :</span>
                            <a class="active sort_product" data-sort="ASC">Price up</a> | <a class="sort_product" data-sort="DESC">Price down</a> | <a class="sort_product" data-sort="NEWEST">New first</a>
                        </p>
                    </div>
                </div>
                <br class="hidden-xs">
                <div class="panel-group" id="accordion">
                    <!-- Categories  -->
                    <?php use App\Http\Repositories\Frontend\CategoriesApis;?>
                    <div class="panel panel-flat">

                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapseCategory" class="">
                                    Category <span class="pull-right hasMinus"> <i class="i-minus"></i></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapseCategory" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <ul class="nav nav-pills nav-stacked tree">
                                    @foreach($parentCategories as $parent) 

                                        <?php 

                                            $children =  CategoriesApis::getCategoryChildFromCategoryId($parent->category_id); 
                                            //generate SEO URL link 
                                            $seo_link = ProductsController::generateSeoUrlLink($parent->category_name, $parent->category_id);

                                        ?>

                                        <li class="dropdown-tree">

                                            <a class="dropdown-tree-a" @if(empty($children)) href="{{ route('product_list_path', $seo_link) }}" @endif> 
                                                @if(!empty($children))<span class="pull-right"> <i class="fa fa-angle-down"></i></span>@endif {{ $parent->category_name }} 
                                            </a>
                                    
                                            @if(!empty($children))
                                                <ul class="category-level-2 dropdown-menu-tree">
                                                    @foreach($children as $child)
                                                    
                                                        <?php 

                                                        $sub_children =  CategoriesApis::getCategoryChildFromCategoryId($child->category_id); 
                                                        //generate SEO URL link 
                                                        $seo_link = ProductsController::generateSeoUrlLink($child->category_name, $child->category_id);

                                                        ?>

                                                        <li class="dropdown-tree">

                                                            @if(empty($sub_children)) <!-- level 2 doesn't have anymore children -->
                                                                <a class="a_{{$child->category_id}} dropdown-tree-a @if($child->category_id == $category_id)active_a @endif" href="{{ route('product_list_path', $seo_link) }}">
                                                                    {{ $child->category_name }}
                                                                </a>

                                                            @else <!-- level 2 still have children -->
                                                                <a class="dropdown-tree-a">
                                                                   <span class="pull-right"> <i class="fa fa-angle-down"></i></span> {{ $child->category_name }}
                                                                </a>
                                                            @endif

                                                        
                                                            @if(!empty($sub_children))
                                                                <ul class="category-level-2 dropdown-menu-tree">
                                                                    @foreach($sub_children as $sub_child)

                                                                    <?php 
                                                                    //generate SEO URL link 
                                                                    $seo_link = ProductsController::generateSeoUrlLink($sub_child->category_name, $sub_child->category_id);

                                                                    ?>

                                                                        <li>
                                                                            <a class="a_{{$sub_child->category_id}} @if($sub_child->category_id == $category_id) active_a @endif" href="{{ route('product_list_path', $seo_link) }}">
                                                                                {{$sub_child->category_name}}
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif

                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif

                                        </li>

                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!--Price Range-->
                    @if($interval != false)
                    <div class="panel panel-flat">
                       <div class="panel-heading">
                          <h4 class="panel-title">
                              <a class="" data-toggle="collapse" href="#collapsePrice">
                                 Price <span class="pull-right hasMinus"> <i class="i-minus"></i></span> 
                               </a>
                          </h4>
                       </div>
                       <div id="collapsePrice" class="panel-collapse collapse in">
                            <div class="panel-body priceFilterBody">   
                                <div class="block-element">
                                    <label> Enter a Price range </label>
                                    <form class="form-inline" role="form">
                                      <div class="form-group">
                                          <input type="number" class="form-control" name="min_price" id="min_price" value="{{ $interval['min_price'] }}" required>
                                      </div>
                                      <div class="form-group sp"> -</div>
                                      <div class="form-group">
                                          <input type="number" class="form-control" name="max_price" id="max_price" value="{{ $interval['max_price'] }}" required>
                                      </div>
                                      <div id="submit_price_range" class="btn btn-primary pull-right">check</div>
                                  </form>
                                </div>
                            </div>
                       </div>
                    </div>
                    <!--/price panel end-->
                    @endif

                    <!--Brands-->
                    @if(!empty($brands))
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapseBrands" class="">
                                    Brand <span class="pull-right hasMinus"> <i class="i-minus"></i></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapseBrands" class="panel-collapse collapse in">
                            <div class="panel-body smoothscroll maxheight300">
                                @foreach($brands as $b)
                                    <div class="block-element">
                                        <label class="checklabel"> {{$b->name}}  
                                          <input class="checkbox_css click_load" type="checkbox" name="brand_box" value="{{$b->brand_id}}" my_value="{{$b->brand_id}}">
                                          <span class="checkboxmark"></span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!--Tags-->
                    @if(!empty($tags))
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapseTags" class="">
                                    Tags <span class="pull-right hasMinus"> <i class="i-minus"></i></span> 
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTags" class="panel-collapse collapse in">
                            <div class="panel-body smoothscroll maxheight300">
                                @foreach($tags as $t)
                                    <div class="block-element">
                                        <label class="checklabel">{{$t->name}}  
                                          <input class="checkbox_css click_load" type="checkbox" name="tag_box" value="{{$t->tag_id}}" my_value="{{$t->tag_id}}">
                                          <span class="checkboxmark"></span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif            

                    <!--Discounts-->
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapseDiscounts" class="">
                                    Discounts <span class="pull-right hasMinus"> <i class="i-minus"></i></span> 
                                </a>
                            </h4>
                        </div>
                        <div id="collapseDiscounts" class="panel-collapse collapse in">
                            <div class="panel-body">   
                                <div class="block-element">
                                    <label class="checklabel"> Discounted items 
                                      <input class="checkbox_css click_load" type="checkbox" name="discount_box" value="1" my_value="1">
                                      <span class="checkboxmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Sidebar  -->

            <!-- Products List  -->
            <div class="col-lg-9 col-md-9 col-sm-9 categoryColumn">
                <div class="row categoryProduct clearfix">
                    <div id="dynamic_products">
                        @include('frontend.products.includes.product-list') 
                    </div>
                </div>
            </div>
            <!-- End Products List  -->
        </div>
        <!-- End Content -->
    </div>
</div>
<!-- End Page Content  -->

<!-- Modal after add to cart start -->
@include('frontend.products.includes.modal-add-to-cart') 

<script src="/js/jquery/jquery-2.1.3.min.js"></script>
<script src="/js/jquery.cookie.js"></script>

<script type="text/javascript">
    var category_id=<?=json_encode($category[0]->category_id)?>;
    var show_list=<?=json_encode($show_list)?>;
    var url_price_range=<?=json_encode(route('filter_price_range_path'))?>;
    var url_sort_by =<?=json_encode(route('sort_by_path'))?>;
    var url_click_filters=<?=json_encode(route('set_filter_session_path'))?>;
    var url_load_ajax_products=<?=json_encode(route('load_dynamic_products_path', $category[0]->category_id)) ?>;

    var display_sorted_route = <?=json_encode(route('display_sorted_path')) ?>;

    // open the selected category in the sidebar on load
    $(window).on("load", function(){
        $('.a_'+category_id).parents('li.dropdown-tree').addClass('open-tree active');
    });


    // Store the state of the filter in a cookie variable
    $("#filter").click(function() {
       $(this).toggleClass("open");
       $.cookie("filteropen", $(this).hasClass("open")?"true":"false");
   });
    if($.cookie('filteropen') == 'true'){
       $('#filter').addClass("open");
       $('.filterToggle').removeClass('filter-is-off');
       $('.catColumnWrapper').removeClass('filter-is-off');
   }
    


</script>

<!-- Management of all sidebar filters -->
<script src="/js/products/sort-by.js"></script>
<script src="/js/products/sidebar-filter-content.js"></script>
<script src="/js/products/product-details-add-to-cart.js"></script>

@endsection