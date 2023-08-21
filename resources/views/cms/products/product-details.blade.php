<?php use App\Http\Controllers\CategoryController;?>

@extends('cms.layouts.app')

@section('content')
<style type="text/css">
  .yes{color: #0F0;}
  .no{color: #F00;}
</style>

<!-- Getting today's date -->
<?php $now = date("Y/m/d"); ?>

<!-- Modal returning the errors in case there is any -->
@if (session()->has('success'))
  <div class="modal fade" id="success-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="loginmodal-container">
        <button class="close close_error_modal" type="button" data-dismiss="modal" aria-hidden="true">x</button>
        <div class="margin-top alert alert-success">
          <b>Success!</b><br>
          {{session('success')['message']}}
        </div>
      </div>
    </div>
  </div>

  <script>
  $(function() {
      $('#success-modal').modal('show');
  });
  </script>
@endif

<!-- The error SHOULD be returned as ARRAY with element [0] the status of the error, and element [1] the message of the error -->
@if (!empty($errors->all()))
<div class="modal fade" id="error-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="loginmodal-container">
      <button class="close close_error_modal" type="button" data-dismiss="modal" aria-hidden="true">x</button>
      <div class="margin-top alert alert-danger">
        <b>Warning!</b><br/>
        {!! $errors->all()[1] !!} <!-- display ONLY the message of the error [1] WITHOUT the status [0] -->
      </div>
    </div>
  </div>
</div>
@endif

<input type="hidden" name="edit_product_id" value="{{ $product_info[0]->product_id }}">

<!-- Header of the product details including:
  Product title, image and short description Section -->
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-3">
      <img id="product_primary" src="{{getenv('S3_URL')}}/products/{{ $product_info[0]->img }}" width="100%"/>
  </div>
  <div class="col-lg-9">
    <div class="col-lg-9">
      <h2> {{$product_info[0]->name}}  
        [
        @if($prices[0]->min_current_price == $prices[0]->max_current_price) 
          ${{floatval($prices[0]->min_current_price)}} 
        @else 
          ${{floatval($prices[0]->min_current_price)}} - ${{floatval($prices[0]->max_current_price)}} 
        @endif
        ]
        @if($product_info[0]->sales_price!=null && strtotime($product_info[0]->sales_price_end_date) > strtotime($now))
          <span class="label label-warning b_radius" style="margin-left: 5px; position:relative; bottom:3px;">On sale</span>
        @endif
      </h2>
      <ol class="breadcrumb ">
        <li>
          <strong><a href="{{ route('cms_products') }}">Products</a></strong>
        </li>
        <li>
           {{$product_info[0]->name}}
        </li>
      </ol>
      <hr>
    </div>

    <div class="col-lg-3" style="text-align:right; margin-top:25px;">
      <button data-toggle="modal" data-target="#edit_basic_info" type="button" class="edit_btn" title="Edit basic info"><i class="fa fa-edit fa-lg"></i></button>
    </div>

    <div class="col-lg-4">   
      <h3>Published <br> <small> Product is published or not.</small></h3>
      @if($product_info[0]->hidden == 0 )
      <p class="js_published"><span class="text-info"><b>PUBLISHED</b></span></p>
      @else
      <p><span class="text-danger"><b>UNPUBLISHED</b></span></p>
      @endif
    </div>

    <div class="col-lg-4">  
      <h3>Brand <br> <small> Brand linked to the product.</small></h3>
      @if($product_info[0]->brand_id != null)
      <p><span class="text-info"><b>{{ strtoupper($product_info[0]->brand_name)}}</b></span></p>
      @else
      <p><span class="text-danger"><b>NO BRAND</b></span></p>
      @endif
    </div>

    <div class="col-lg-4">
      <h3>Manage Stock <br> <small> Stock counting vs Stock Status </small></h3>
      <p>
        @if($product_info[0]->enable_stock_mgmt == 1)
          <span class="text-info"><b>YES</b></span>
        @else
          <span class="text-danger"><b>NO</b></span>
        @endif
      </p>
    </div>
  </div>
</div>

<div class="col-lg-4 wrapper animated fadeInRight margin-top">
  <div class="row">
    <!-- CATEGORY section -->
    <!-- Category Section -->
    <div class="col-lg-12" style="margin-bottom:15px;">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
         <h5><i class="fa fa-list fa-lg">&nbsp</i></span> CATEGORIES </h5> 
          <div class="ibox-tools">
            <a data-toggle="modal" data-target="#link_categ" title="Link to category">
              <i class="fa fa-plus-square fa-lg" style="color: #1bb394;"></i>
            </a>
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          @if(!$categories)
            <p style="text-align: center;padding-top: 30px;padding-bottom: 10px;"><i>No <b>categories </b> to show. <br> Add your first category!</i></p>
            <div style="text-align: center;">
              <a data-toggle="modal" data-target="#link_categ" title="Link to category">
                <i class="fa fa-plus-circle big-icon"></i>
              </a>
            </div><br>
            
          @else
          <div class="table-responsive">
            <table id="client_table" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($categories as $c) 
                  <tr class="gradeX" id="categrec_{{ $c->category_id }}">
                    <td>{{ $c->name }}</td>
                    <td class="center">
                      <button type="button" id='{{ $c->category_id }}' class="edit_btn delete_cat" title="Unlink Category"><i class="fa fa-trash fa-lg"></i></button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @endif
        </div>
      </div>
    </div>
    <!-- IMAGES section -->
    <!-- Images section -->
    <div class="col-lg-12" style="margin-bottom:15px;" id="images_div">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5><span><i class="fa fa-image fa-lg">&nbsp</i></span> PHOTO GALLERY </h5>
          <div class="ibox-tools">
            <a data-toggle="modal" data-target="#add_img" title="Add Image">
              <i class="fa fa-plus-square fa-lg" style="color: #1bb394;"></i>
            </a>
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          @if(!$images)
          <p style="text-align: center;padding-top: 30px;padding-bottom: 10px;"><i>No <b>secondary pictures</b> to show. <br> Add your first secondary image!</i></p>
          <div style="text-align: center;">
            <a data-toggle="modal" data-target="#add_img" title="Add Image">
              <i class="fa fa-plus-circle big-icon"></i>
            </a>
          </div><br>
          @else
          <div class="table-responsive">
            <table id="prod_img_table" class="prod_img_table footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Primary Action</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($images as $i)
                <tr class="gradeX" id="rec_{{ $i->product_img_id }}" img_id="{{ $i->product_img_id }}">
                  <td><img width="50" src="{{getenv('S3_URL')}}/products/thumbs/{{ $i->img }}" data-toggle="modal" data-target="#img_big{{$i->product_img_id}}" id="prod_img_{{ $i->product_img_id }}" style="border-radius:10px; margin-right:5px;"></td>

                  @if ($i->is_primary!=0)
                    <td><span class="label label-primary b_radius">Primary</span></td>
                  @else
                    <td><a id='{{ $i->product_img_id }}' type="button" class="active set_primary_img" title="Set as primary image">Set Primary</a></td>
                  @endif 

                  <td class="center">
                     @if($i->is_primary!=1)
                      <button type="button" id='{{ $i->product_img_id }}' class="edit_btn delete_product_img" title="Delete img"><i class="fa fa-trash fa-lg"></i></button>
                     @else
                      <button onclick="loadEditPrimaryImage({{$i->product_img_id}} , '{{$i->img}}');" type="button" data-toggle="modal" data-target="#edit_primary_img" id='{{ $i->product_img_id }}' class="edit_btn edit_primary_img" title="Edit primary image"><i class="fa fa-edit fa-lg"></i></button> 
                     @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @endif
        </div>
      </div>
    </div>

    
    @if(config('global.MULTIPLE_VARIANTS') == 1) 

      <!-- MAIN ATTRIBUTES Section -->
      <div class="col-lg-12" style="margin-bottom:10px">
        <div class="ibox">
          <div class="ibox-title">
            <h5 style="text-transform: uppercase;"><span><img src="/cms/svg/paint-board-and-brush.svg" width="20"></span>@if($main_attribute) {{$main_attribute[0]->name}} @else COLOR @endif &nbsp</h5><span style="display: inline !important;">
            @if($main_attribute)
            <a onclick="loadMainAttrNameToEdit({{ $main_attribute[0]->attribute_id }});" data-toggle="modal" data-target="#edit_main_attr_name" title="Edit name">
              <i class="fa fa-edit" style="color: #1bb394;"></i>
            </a>
            @endif</span>
            
            <div class="ibox-tools" style="display: inline; float:right;">
              <a data-toggle="modal" data-target="#add_main_attribute" title="Add Main Attribute" id="add_main_me">
                <i class="fa fa-plus-square fa-lg" style="color: #1bb394;"></i>
              </a>
              <a class="collapse-link">
                <i class="fa fa-chevron-down"></i>
              </a>
            </div>
          </div>
          <div class="ibox-content hidden_ibox">
            @if(!$main_attribute_items)
            <p style="text-align: center;padding-top: 30px;padding-bottom: 10px;"><i>No <b>color</b> to show.<br> Add your first color!</i></p>
            <div style="text-align: center;">
              <a data-toggle="modal" data-target="#add_main_attribute" title="Add Main Attribute" id="add_main_me">
                <i class="fa fa-plus-circle big-icon"></i>
              </a>
            </div><br>
            @else
            <div class="table-responsive">
              <table id="client_table" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
                <thead>
                  <tr>
                    <th>Items</th>
                    <th>@if($main_attribute[0]->is_main == 1)Images @elseif($main_attribute[0]->is_main == 2) Color @endif</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($main_attribute_items as $m) 
                    <tr class="gradeX" id="rec_main_attr_item_{{ $m->attribute_item_id }}">
                      <td>{{ $m->item_name }}</td>
                      <td>
                        @if($main_attribute[0]->is_main == 1)
                          <img width="50" src="{{getenv('S3_URL')}}/attribute_items/thumbs/{{ $m->img }}" style="border-radius:10px; margin-right:5px;">
                         @elseif($main_attribute[0]->is_main == 2) 
                          <div style="width:40px; height:20px; background:{{$m->color}}"></div>
                        @endif

                        </td>
                      <td class="center">
                        <button onclick="loadMainAttrItemToEdit({{ $m->attribute_item_id }});" data-toggle="modal" data-target="#edit_main_attr_item" type="button" class="edit_btn" title="Edit Item"><i class="fa fa-edit fa-lg"></i></button>
                        <button id="{{ $m->attribute_item_id }}" class="edit_btn delete_main_attribute_item" title="Delete Item"><i class="fa fa-trash fa-lg"></i></button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @endif
          </div>
        </div>
      </div>

      <!-- ATTRIBUTES Section -->
      <div class="col-lg-12" style="margin-bottom:10px">
        <div class="ibox">
          <div class="ibox-title">
            <h5><span><i class="fa fa-paperclip fa-lg">&nbsp</i></span> ATTRIBUTES </h5>
            <div class="ibox-tools">
              <a data-toggle="modal" data-target="#add_attribute" title="Add Attribute" id="add_me">
                <i class="fa fa-plus-square fa-lg" style="color: #1bb394;"></i>
              </a>
              <a class="collapse-link">
                <i class="fa fa-chevron-down"></i>
              </a>
            </div>
          </div>
          <div class="ibox-content hidden_ibox">
            @if(!$attributes)
            <p style="text-align: center;padding-top: 30px;padding-bottom: 10px;"><i>No <b>attributes</b> to show. <br> Add your first attribute!</i></p>
            <div style="text-align: center;">
              <a data-toggle="modal" data-target="#add_attribute" title="Add Attribute" id="add_me">
                <i class="fa fa-plus-circle big-icon"></i>
              </a>
            </div><br>
            @else
            <div class="table-responsive">
              <table id="client_table" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
                <thead>
                  <tr>
                    <th>Attributes</th>
                    <th>Values</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($attributes as $a) 
                    <tr class="gradeX" id="recattr_{{ $a->attribute_id }}">
                      <td>{{ $a->name }}</td>
                      <td>{{ $a->attribute_names }}</td>
                      <td class="center">
                        <button onclick="loadAttrToEdit({{ $a->attribute_id }});" data-toggle="modal" data-target="#edit_attr" type="button" class="edit_btn" title="Edit Attribute"><i class="fa fa-edit fa-lg"></i></button>
                        <button id="{{ $a->attribute_id }}" class="edit_btn delete_attribute" title="Delete attribute"><i class="fa fa-trash fa-lg"></i></button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @endif
          </div>
        </div>
      </div>

    @endif

    <!-- TAGS Section -->
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><i class="fa fa-tags fa-lg">&nbsp</i></span> TAGS</h5>
          <div class="ibox-tools">
            <a data-toggle="modal" data-target="#add_tag" title="Add Tag">
              <i class="fa fa-plus-square fa-lg" style="color: #1bb394;"></i>
            </a>
            <a class="collapse-link">
              <i class="fa fa-chevron-down"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content hidden_ibox">
          @if(!$tags)
           <p style="text-align: center;padding-top: 30px;padding-bottom: 10px;"><i>No <b>tags</b> to show.<br> Add your first tag!</i></p>
            <div style="text-align: center;">
              <a data-toggle="modal" data-target="#add_tag" title="Add Tag">
                <i class="fa fa-plus-circle big-icon"></i>
              </a>
            </div><br>
          @else
          <div class="table-responsive">
            <table id="client_table" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($tags as $t)
                <tr class="gradeX" id="prod_tag_{{$t->tag_id}}">
                  <td>{{ $t->name }}</td>
                  <td class="center">
                    <button type="button" id='{{ $t->tag_id }}' class="edit_btn delete_prod_tag" title="Unlink Tag"><i class="fa fa-trash fa-lg"></i></button>
                  </td>
                </tr> 
                @endforeach
              </tbody>
            </table>
          </div>
          @endif
        </div>
      </div>
    </div> 
  </div>
</div>

<div class="col-lg-8 wrapper animated fadeInRight margin-top">
  <div class="row">
  
   <!-- DESCRIPTIONS section -->
    <div class="col-md-12" style="margin-bottom:10px" id="description_div">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><img src="/cms/svg/information.svg" width="20"></span> &nbspPRODUCT DESCRIPTION</h5>
          <div class="ibox-tools">
            <a data-toggle="modal" data-target="#edit_description" class="edit_btn" title="Edit Description"><i class="fa fa-edit fa-lg" style="color: #1bb394;"></i></a>
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content profile-content">
           
            @if($product_info[0]->short_description == htmlspecialchars_decode('<p><br></p>'))
            <p style="text-align: center;padding-top: 15px;padding-bottom: 10px;"><i>No <b>short description</b> to show. <br> Write something!</i></p>
            <div style="text-align: center;">
              <a data-toggle="modal" data-target="#edit_description">
                <i class="fa fa-plus-circle big-icon"></i>
              </a>
            </div><br>
            @else
            <h3>Short Description</h3>
            {!! htmlspecialchars_decode($product_info[0]->short_description) !!}
            @endif
         
          <hr> 

           @if($product_info[0]->description == htmlspecialchars_decode('<p><br></p>'))
            <p style="text-align: center;padding-top: 15px;padding-bottom: 10px;"><i>No <b>description</b> to show. <br> Write something!</i></p>
            <div style="text-align: center;">
              <a data-toggle="modal" data-target="#edit_description">
                <i class="fa fa-plus-circle big-icon"></i>
              </a>
            </div><br>
           @else
           <h3>Description</h3>
           {!! htmlspecialchars_decode($product_info[0]->description) !!}@endif
        </div>
      </div>
    </div>

   <!-- VARIANTS section -->
    <?php $expired=0; ?>

    @if($variantsList)
      @foreach($variantsList as $var)
        <!-- If there is a promotion end date for any variant and this end date 
        is expired, display that n variants promotions have been expired -->
        @if(!is_null($var->sales_price) && strtotime($var->sales_price_end_date) < strtotime($now))
          <?php $expired++;?>
        @endif
      @endforeach 
    @endif

    <div class="col-lg-12" style="margin-bottom:15px;" id="variants_div">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5><span><i class="fa fa-clipboard fa-lg">&nbsp</i></span> VARIANTS </h5>
          @if($expired > 0)
          <span class="label label-danger b_radius">{{$expired}} Promo Expired</span>
          @endif 
          <div class="ibox-tools">

           @if(config('global.MULTIPLE_VARIANTS') == 1) 
            <a id="click_add_variant" @if(!$all_attributes) class="add_variant_control" @else data-toggle="modal" data-target="#add_variant" @endif title="Add Variant">
              <i class="fa fa-plus-square fa-lg" style="color: #1bb394;"></i>
            </a>
           @endif

            <a class="collapse-link">
              <i class="fa fa-chevron-down"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content visible">
          @if(sizeof($variantsList) > 1)
          <div class="row">
            <div style="float:right;margin-right: 2.5%;">
             <button type="button" class="btn btn-default btn-danger btn-sm margin-bottom delete_all_variants" id="{{ $product_info[0]->product_id }}">DELETE ALL</button>
            </div>
            <hr style="width: 95%;margin-top: 23px;">
          </div>
          @endif
          <div class="table-responsive">
            <table id="client_table" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded variants_table" >
              <thead>
                <tr>
                  <th></th>
                  <th>Description</th>
                  <th>Regular Price</th>
                  <th>Sales Price</th>
                  <th>Stock</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              @if($variantsList) 
                @foreach($variantsList as $v)
                <tr class="gradeX" id="var_rec_{{ $v->variant_id }}">
                  <td>
                    @if($v->is_main != 1)
                      @if($v->items_nb == count($all_attributes))
                      <div class="controls">
                        <div class="switch">
                          <div class="onoffswitch">
                            <input type="checkbox" onclick="ajaxPublish({{$v->variant_id}})" class="onoffswitch-checkbox" id="var_{{$v->variant_id}}" name="check_variant_id" data-toggle="collapse" data-target="#demo" value="1" @if($v->hidden == 0) checked ="checked" @endif>
                            <label class="onoffswitch-label" for="var_{{$v->variant_id}}">
                              <span class="onoffswitch-inner"></span>
                              <span class="onoffswitch-switch"></span>
                            </label>
                          </div>
                        </div>
                      </div>
                      @else
                        <span class="label label-danger b_radius">Inactive</span><br><small>Attribute Missing<small>
                      @endif
                    @endif
                  </td>

                  <td>
                    <?php 
                    // check if the vairant is a color
                    if($v->color == NULL)
                    {
                      // check if the variant has image. if no diplay an unkown image
                      if($v->item_img != NULL)
                        $img = getenv('S3_URL').'/attribute_items/thumbs/'.$v->item_img;
                      else
                        $img = 'images/icons/box_icon.png';
                     ?>
                    <img src="{{$img}}" width="50" style="border-radius:10px; margin-right:5px; float:left;">  
                    <?php 
                    }
                    else // the variant is a color
                    ?>
                    <div style="width:50px; height:50px; border-radius:10px; margin-right:5px; float:left; background-color:{{$v->color}}"></div>  

                    <span style="position:relative; top:7px; left:5px;">{{$v->sku}} <br> 
                      <small> {{$v->item_name}} </small>
                    </span>
                  </td>

                  <td>${{ floatval($v->regular_price) }}</td>

                  @if ($v->sales_price!=null) <!-- If there is a promotion for the variant -->
                    <!-- If the promotion is not expired, click to edit or stop it (Stop Promo button appears) -->
                    @if (strtotime($v->sales_price_end_date) >= strtotime($now)) 
                      <td><a onclick="loadVariantPromoToEdit({{ $v->variant_id }});" data-toggle="modal" data-target="#edit_promo" title="Change Promotion">${{ floatval($v->sales_price) }} <span class="label label-success b_radius" style="margin-left: 5px;">Expires on {{ date("m/d", strtotime($v->sales_price_end_date)) }}</span></a></td>
                    @else
                      <!-- If the promotion is expired, display expired label, click to edit or stop it (Stop Promo button appears) -->
                      <td>
                      <a onclick="loadVariantPromoToEdit({{ $v->variant_id }});" data-toggle="modal" data-target="#edit_promo" title="Update Promotion">${{ floatval($v->sales_price) }}</a> 
                      <span class="label label-danger b_radius" style="margin-left: 5px;">Expired</span>
                      </td>
                    @endif
                  @else <!-- If there is not a promotion for the variant -->
                    <td><a onclick="loadVariantPromoToEdit({{ $v->variant_id }});" data-toggle="modal" data-target="#edit_promo" title="Add Promotion">Add promo</a></td>
                  @endif 

                  <!-- display if variant is in stock or not -->
                  <td>@if( $product_info[0]->enable_stock_mgmt == 1 && $v->stock_qty != null)
                        @if($v->stock_qty == 0)
                          <span class="label label-danger b_radius" style="margin-left: 5px;">Out of Stock</span>
                        @elseif($v->stock_qty < 10)
                          <span class="label label-warning b_radius" style="margin-left: 5px;"><b>{{$v->stock_qty}}</b> remaining</span>
                        @else
                          <span class="label label-primary b_radius" style="margin-left: 5px;"><b>{{$v->stock_qty}}</b> remaining</span>
                        @endif
                        
                      @elseif($product_info[0]->enable_stock_mgmt == 1 && $v->stock_qty == null)
                        <span class="label label-danger b_radius" style="margin-left: 5px;">Out of Stock</span>
                      @elseif($v->stock_status_id == 1) 
                        <span class="label label-primary b_radius" style="margin-left: 5px;">In Stock</span>
                      @else
                        <span class="label label-danger b_radius" style="margin-left: 5px;">Out of Stock</span>
                      @endif
                  </td>

                  <td class="center">
                    <button onclick="loadVariantDataToEdit({{ $v->variant_id }});" data-toggle="modal" data-target="#update_variant" type="button" class="edit_btn" title="Edit Variant"><i class="fa fa-edit fa-lg"></i></button>
                  @if(!empty($all_attributes))
                    <button onclick="loadVariantDataToDuplicate({{ $v->variant_id }});" data-toggle="modal" data-target="#add_variant" type="button" class="edit_btn" title="Duplicate Variant"><i class="fa fa-copy fa-lg"></i></button>
                  @endif
                    <button type="button" id="{{ $v->variant_id }}" class="edit_btn delete_variant" title="Delete item">@if($v->is_main == 0)<i class="fa fa-trash fa-lg"></i>@endif</button>
                  </td>
                </tr>
                @endforeach
              @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

   <!-- SEO section -->
   <!-- SEO Section -->
    <div class="col-md-12" style="margin-bottom:10px" id="seo_div">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><i class="fa fa-search fa-lg">&nbsp</i></span> SEARCH ENGINE OPTIMIZATION</h5>
          <div class="ibox-tools">
            <a data-toggle="modal" data-target="#edit_seo" class="edit_btn" title="Edit SEO info"><i class="fa fa-edit fa-lg" style="color: #1bb394;"></i></a>
            <a class="collapse-link">
              <i class="fa fa-chevron-down"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content profile-content hidden_ibox">
          <div id="signupbox" class="mainbox">
            <div class="col-lg-12">
              @if(!$product_info[0]->seo_title)
                <p style="text-align: center;padding-top: 30px;padding-bottom: 10px;"><i>No <b>SEO title</b> to show.<br> Add SEO Title!</i></p>
                <div style="text-align: center;">
                  <a data-toggle="modal" data-target="#edit_seo">
                    <i class="fa fa-plus-circle big-icon"></i>
                  </a>
                </div><br>
              @else
                <h3>SEO Title</h3>
                <p>{{$product_info[0]->seo_title}}</p>
              @endif
              <hr>
              
              <!-- @if(!$product_info[0]->seo_slug)
                <p style="text-align: center;padding-top: 30px;padding-bottom: 10px;"><i>No <b>SEO Slug</b> to show.<br> Add SEO Slug!</i></p>
                <div style="text-align: center;">
                  <a data-toggle="modal" data-target="#edit_seo">
                    <i class="fa fa-plus-circle big-icon"></i>
                  </a>
                </div><br>
              @else
              <h3>SEO Slug</h3>
              <p>{{$product_info[0]->seo_slug}}</p>
              @endif
              <hr> -->
              
              @if(!$product_info[0]->seo_meta_desc)
                <p style="text-align: center;padding-top: 30px;padding-bottom: 10px;"><i>No <b>SEO Meta Description</b> to show.<br> Add SEO Meta Description!</i></p>
                <div style="text-align: center;">
                  <a data-toggle="modal" data-target="#edit_seo">
                    <i class="fa fa-plus-circle big-icon"></i>
                  </a>
                </div><br>
              @else
              <h3>SEO Meta Description</h3>
              <p>{{$product_info[0]->seo_meta_desc}}</p>
              @endif
              <hr>
              
              @if(!$product_info[0]->seo_meta_keywords)
                <p style="text-align: center;padding-top: 30px;padding-bottom: 10px;"><i>No <b>SEO Meta Keywords</b> to show.<br> Add SEO Meta Keywords!</i></p>
                <div style="text-align: center;">
                  <a data-toggle="modal" data-target="#edit_seo">
                    <i class="fa fa-plus-circle big-icon"></i>
                  </a>
                </div><br>
              @else
              <h3>SEO Meta Keywords</h3>
              <p>{{$product_info[0]->seo_meta_keywords}}</p>
              @endif
            </div>
            <div style="text-align:right; margin-top: 15px; margin-right: 15px;">
              .
            </div>
          </div> 
        </div>
      </div>
    </div>

  <!-- LINKED PRODUCTS Stylsheet -->
   <link href="/cms/css/plugins/steps/jquery.steps.css" rel="stylesheet">
    <!-- LINKED PRODUCTS Section -->
    <div class="col-lg-12" style="margin-bottom:10px">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><i class="fa fa-tags fa-lg">&nbsp</i></span> LINKED PRODUCTS </h5>
          <div class="ibox-tools">
            <a data-toggle="modal" data-target="#add_linked_product" title="Add Linked Product">
              <i class="fa fa-plus-square fa-lg" style="color: #1bb394;"></i>
            </a>
            <a class="collapse-link">
              <i class="fa fa-chevron-down"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content hidden_ibox">
          @if(!$linked_products)
           <p style="text-align: center;padding-top: 30px;padding-bottom: 10px;"><i>No <b>Linked Products</b> to show.<br> Add your first linked product!</i></p>
            <div style="text-align: center;">
              <a data-toggle="modal" data-target="#add_linked_product" title="Add Linked Product">
                <i class="fa fa-plus-circle big-icon"></i>
              </a>
            </div><br>
          @else
          <div class="table-responsive">
            <table id="client_table" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($linked_products as $l)
                <tr class="gradeX" id="prod_link_{{$l->product_id}}">
                  <td>
                    <?php // check if the product has an image. if no diplay an unkown image
                      if($l->img != NULL)
                        $img = getenv('S3_URL').'/products/thumbs/'.$l->img;
                      else
                        $img = 'images/icons/box_icon.png';
                     ?>

                    <img src="{{$img}}" width="50" style="border-radius:10px; margin-right:5px;"> {{ $l->name }}
                  </td>
                  <td class="center">
                    <button type="button" id='{{ $l->product_id }}' class="edit_btn delete_prod_link" title="Unlink Product"><i class="fa fa-trash fa-lg"></i></button>
                  </td>
                </tr> 
                @endforeach
              </tbody>
            </table>
          </div>
          @endif
        </div>
      </div>
    </div> 

   <!-- VIDEOS section -->
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><img src="/cms/svg/youtube.svg" width="20">&nbsp</span> VIDEOS </h5>
          <div class="ibox-tools">
            <a data-toggle="modal" data-target="#add_video" title="Add Video">
              <i class="fa fa-plus-square fa-lg" style="color: #1bb394;"></i>
            </a>
            <a class="collapse-link">
              <i class="fa fa-chevron-down"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content hidden_ibox">
          @if(!$videos)
           <p style="text-align: center;padding-top: 30px;padding-bottom: 10px;"><i>No <b>videos</b> to show.<br> Add your first video!</i></p>
            <div style="text-align: center;">
              <a data-toggle="modal" data-target="#add_video" title="Add Video">
                <i class="fa fa-plus-circle big-icon"></i>
              </a>
            </div><br>
          @else
          <div class="table-responsive">
            <table id="client_table" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
              <thead>
                <tr>
                  <th>Link</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($videos as $v)
                <tr class="gradeX" id="prod_video_{{$v->video_id}}">
                  <td>{{ $v->link }}</td>
                  <td class="center">
                    <button type="button" id='{{ $v->video_id }}' class="edit_btn delete_prod_video" title="Delete Video"><i class="fa fa-trash fa-lg"></i></button>
                  </td>
                </tr> 
                @endforeach
              </tbody>
            </table>
          </div>
          @endif
        </div>
      </div>
    </div> 

  </div>
</div>

<div class="wrapper animated padding-bottom margin-top">
  <div class="row">
    
  </div>
</div>

<!-- =============== MODALS HEADER ================== -->
<!--Modal Edit basic info -->
<div id="edit_basic_info" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
    {!! Form::open(array('route' => 'edit_product_basic_info_path', 'class' => 'add-form')) !!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Basic info <small>Edit the basic info of the product</small></h4>
      </div>
      
        <div class="modal-body">   
          <div class="panel-info"> 
            <div class="panel-body"> 
              <div class="row"> 

                <input type="hidden" name="edit_product_id" id="edit_product_id_attr" value="{{$product_info[0]->product_id}}">

                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="edit_product_name" class="font-normal">Product Name<span class="red_star">*</span> <br><small><i>Name of the product.</i></small></label>
                    <input class="input-md textinput textInput form-control" value="{{$product_info[0]->name}}" id="edit_product_name" name="edit_product_name" placeholder="Name" type="text"/>
                  </div>   
                </div>

                @if(!empty($brandsList))
                <div class="col-lg-12">
                    <div class="form-group required">
                      <label for="manage_stock" class="font-normal">Brand <br><small><i>Select the Brand of the product</i></small></label><br>
                      <select class="form-control" id="brand" name="brand">
                        <option value="">--NONE--</option>
                        @foreach ($brandsList as $b)
                          <option value="{{$b->brand_id}}" @if($product_info[0]->brand_id == $b->brand_id) selected @endif >{{$b->name}}</option>
                        @endforeach
                      </select>
                    </div>
                </div>
                @endif

                <div class="col-lg-6">
                    <div class="form-group required">
                      <label for="manage_stock" class="font-normal">Manage Stock <br><small><i>Choosing if we manage the stock</i></small></label><br>
                      <input type="checkbox" class="big_checkbox" name="manage_stock" id="manage_stock" value="1" @if($product_info[0]->enable_stock_mgmt == 1) checked  @endif> 
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group required">
                      <label for="publish_unpublish" class="font-normal">Publish Product<br><small><i>Choosing if we publish the product</i></small></label><br>
                      <input type="checkbox" class="big_checkbox" name="publish_unpublish" id="publish_unpublish" value="1" @if($product_info[0]->hidden == 0) checked  @endif> 
                    </div>
                </div>

              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="submit_edit_basic_info" value="Save" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_edit_basic_info"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<!-- =============== MODALS ATTRIBUTES ================== -->
<!--Modal Add Attribute -->
<div id="add_attribute" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add an Attribute <small>Add a new attribute for the product</small></h4>
      </div>
      {!! Form::open(array('route' => 'add_attribute_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="modal-body">   
          <div class="panel-info"> 
            <div class="panel-body"> 
              <div class="row"> 

                <input type="hidden" name="edit_product_id" id="edit_product_id_attr" value="{{ $product_info[0]->product_id }}">

                <div class="form-group col-md-12">
                  <label for="attribute" class="control-label col-md-12 requiredField">Attribute<span class="red_star">*</span> <br><small><i>Type the attribute name</i></small></label>
                  <div class="controls col-md-12">
                    <input class="input-md textinput textInput form-control" type="text" id="attribute" name="attribute" placeholder="Enter Attribute name"/>
                  </div>
                </div>
                <div class="form-group col-md-12">
                 <label for="attr_items" class="control-label col-md-12 requiredField">Items<span class="red_star">*</span> <br><small><i>Write the attribute items separated by a comma</i></small></label>
                  <div class="col-md-12">
                    <input class="form-control tagsinput" data-role="tagsinput" type="text" name="items" id="items" value="" style="display: none;" required>
                  </div>     
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="submit_add_attr" value="Add Attribute" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_add_attr"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>


<!--Modal edit Attribute -->
<div id="edit_attr" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit an Attribute <small>Edit an attribute of the product</small></h4>
      </div>
      {!! Form::open(array('route' => 'edit_attribute_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="modal-body">   
          <div class="panel-info"> 
            <div class="panel-body"> 
              <div class="row"> 

                <input type="hidden" name="edit_attribute_id" id="edit_attribute_id">

                <div class="form-group col-md-12">
                  <label for="attribute" class="control-label col-md-12 requiredField">Attribute<span class="red_star">*</span> <br><small><i>Edit the attribute name</i></small></label>
                  <div class="controls col-md-12">
                    <input class="input-md textinput textInput form-control" type="text" id="edit_attribute_name" name="edit_attribute_name" placeholder="Enter Attribute name"/>
                  </div>
                </div>
                <div class="form-group col-md-12">
                 <label for="attr_items" class="control-label col-md-12 requiredField">Items<span class="red_star">*</span> <br><small><i>Edit the attribute items separated by a comma</i></small></label>
                  <div class="col-md-12">
                    <input class="form-control bootstrap-tagsinput" type="text" name="edit_attr_items" id="edit_attr_items" value="" style="display: none;">
                  </div>     
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="submit_edit_attr" value="Save Changes" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_edit_attr"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>


<!-- Modal Add MAIN Attribute -->
<div id="add_main_attribute" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Main Attribute <small>Add your main attribute with the related images or colors</small></h4>
      </div>
      {!! Form::open(array('route' => 'add_main_attribute_items_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="modal-body">   
          <div class="panel-info"> 
            <div class="panel-body"> 
              <div class="row"> 

                <input type="hidden" name="attribute_id" value="@if($main_attribute){{$main_attribute[0]->attribute_id}}@else @endif">

                <input type="hidden" name="product_id" value="{{Request::route('product_id')}}">

                <div class="form-group col-md-12">
                  <label for="attribute" class="control-label col-md-12 requiredField">Item<span class="red_star">*</span> <br><small><i>Type the main attribute item</i></small></label>
                  <div class="controls col-md-12">
                    <input class="input-md textinput textInput form-control" type="text" name="main_attribute_item" placeholder="Enter Attribute item name" required/>
                  </div>
                </div>

                @if(empty($main_attribute_type))
                <div class="form-group col-md-12" style="padding-left:30px;">
                    <div style="display:inline;" class="type_radio">
                        <input id="main_att_img" class="inline" type="radio" value="1" name="main_attribute_type" checked>
                        <label class="pointer" for="main_att_img"> Image </label>
                    </div>
                    &nbsp&nbsp
                    <div style="display:inline;" class="type_radio">
                        <input id="main_att_color" class="inline" type="radio" value="2" name="main_attribute_type" >
                        <label class="pointer" for="main_att_color"> Color </label>
                    </div>
                </div>
                @else
                <input type="hidden" name="main_attribute_type" value="{{$main_attribute_type[0]->is_main}}"> <!-- force the main attribute type to be like the one that already exist -->
                @endif
                
                @if(empty($main_attribute_type))
                  <div class="form-group col-md-12" id="image_input_div">
                   <label for="attr_items" class="control-label col-md-12 requiredField">Image<span class="red_star">* <small><i> recommended size</i></small> (1200 x 800) px</span> <br><small><i>Upload the item image</i></small></label>
                     <div class="col-md-12">
                      <input id="image_input" class="input-md" type="file" name="image" required/>
                    </div>   
                  </div>

                  <div class="form-group col-md-12" id="color_input_div" style="display:none;">
                    <label for="attribute" class="control-label col-md-12 requiredField">Color<span class="red_star">*</span> <br><small><i>Choose the color of your product</i></small></label>
                    <div class="controls col-md-12">
                      <input id="color_input" class="input-md textinput textInput form-control" type="color" name="color_att" style="width:100px;"/>
                    </div>
                  </div>
                  @else
                    @if($main_attribute_type[0]->is_main == 1) <!-- IMAGE -->
                    <div class="form-group col-md-12" id="image_input_div">
                       <label for="attr_items" class="control-label col-md-12 requiredField">Image<span class="red_star">* <small><i> recommended size</i></small> (1200 x 800) px</span> <br><small><i>Upload the item image</i></small></label>
                         <div class="col-md-12">
                          <input id="image_input" class="input-md" type="file" name="image" required/>
                        </div>   
                    </div>
                    @elseif($main_attribute_type[0]->is_main == 2) <!-- COLOR -->
                    <div class="form-group col-md-12" id="color_input_div">
                      <label for="attribute" class="control-label col-md-12 requiredField">Color<span class="red_star">*</span> <br><small><i>Choose the color of your product</i></small></label>
                      <div class="controls col-md-12">
                        <input id="color_input" class="input-md textinput textInput form-control" type="color" name="color_att" style="width:100px;" required/>
                      </div>
                    </div>
                   @endif
                @endif

              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="submit_add_main_attr_item" value="Add Attribute" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_add_main_attr_item"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>


<!-- Modal edit MAIN Attribute Item -->
<div id="edit_main_attr_item" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Main Attribute Item <small>Edit main attribute item of the product</small></h4>
      </div>
      {!! Form::open(array('route' => 'edit_main_attribute_item_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="modal-body">   
          <div class="panel-info"> 
            <div class="panel-body"> 
              <div class="row"> 

                <input type="hidden" name="edit_id_attribute_item">

                <div class="form-group col-md-12">
                  <label for="attribute" class="control-label col-md-12 requiredField">Item<span class="red_star">*</span> <br><small><i>Type the main attribute item</i></small></label>
                  <div class="controls col-md-12">
                    <input class="input-md textinput textInput form-control" type="text" name="edit_attribute_item" placeholder="Enter main Attribute item name" required/>
                  </div>
                </div>

                @if(!empty($main_attribute_type))
                  @if($main_attribute_type[0]->is_main == 1) <!-- IMAGE -->
                    <div class="form-group col-md-12">
                     <label for="attr_items" class="control-label col-md-12 requiredField">Image<span class="red_star">* <small><i> recommended size</i></small> (1200 x 800) px</span> <br><small><i>Upload the item image</i></small><br>
                        <img id="display_edit_attr_item_img" width="100px;">
                     </label>
                    
                     <div class="col-md-12">
                        <input class="input-md form-control" type="file" name="edit_image"/>
                      </div>   
                    </div>
                  @elseif($main_attribute_type[0]->is_main == 2) <!-- COLOR -->
                    <div class="form-group col-md-12" id="color_input_div">
                      <label for="attribute" class="control-label col-md-12 requiredField">Color<span class="red_star">*</span> <br><small><i>Choose the color of your product</i></small></label>
                      <div class="controls col-md-12">
                        <input id="edit_color_input" class="input-md textinput textInput form-control" type="color" name="edit_color_att" style="width:100px;" required/>
                      </div>
                    </div>
                 @endif
               @endif

              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="submit_edit_main_attr_item" value="Save Changes" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_edit_main_attr_item"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<!-- Modal Edit main attribute Name -->
<div id="edit_main_attr_name" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Main Attribute Name <small>Edit main attribute Name</small></h4>
      </div>
      {!! Form::open(array('route' => 'edit_main_attr_name_path', 'id' => 'add_form', 'class' => 'add-form')) !!}
        <div class="modal-body">   
          <div class="panel-info"> 
            <div class="panel-body"> 
              <div class="row"> 

                <input type="hidden" name="e_id">

                <div class="form-group col-md-12">
                  <label class="control-label col-md-12 requiredField">Name<span class="red_star">*</span> <br><small><i>Type the main attribute name</i></small></label>
                  <div class="controls col-md-12">
                    <input class="input-md textinput textInput form-control" type="text" name="e_attr_name" placeholder="Enter main Attribute name" required/>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="submit_main_attr_name" value="Save Changes" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_main_attr_name"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<!-- =============== MODALS CATEGORIES ================== -->
<!--Modal Link a Product to Category -->

<div id="link_categ" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Link product to categories <small>Add new categories to the list</small></h4>
      </div>
      {!! Form::open(array('route' => 'link_category_to_product_path', 'id' => 'add_form', 'class' => 'add-form')) !!}
        <div class="modal-body" style="padding-bottom: 0;">   
          <div class="table-responsive">
            <table id="client_table" class="table table-striped table-bordered table-hover" >
              <thead>
                <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
                <?php echo CategoryController::recursiveDisplay($categoryList, 1, 1); ?>
              </tbody>
            </table>
          </div>
        </div>
        <input type="hidden" name="product_id" value="{{ $product_info[0]->product_id }}">
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="add" value="Link Categories" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_add"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<!-- =============== MODALS DESCRIPTION ================== -->
<!--Modal Edit description info -->
<div id="edit_description" class="modal fade" role="dialog">
  <div class="modal-lg modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
    {!! Form::open(array('route' => 'edit_product_description_info_path', 'class' => 'add-form')) !!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit description info <small>Edit the description of the product</small></h4>
      </div>
      
        <div class="modal-body">   
          <div class="panel-info"> 
            <div class="panel-body"> 
              <div class="row"> 
                 <input type="hidden" name="edit_product_id" id="edit_product_id" value="{{$product_info[0]->product_id}}">

                 <div class="form-group required">
                    <label for="edit_short_description" class="font-normal">Short Description<span class="red_star">*</span> <br><small><i>Edit the general description of the product</i></small></label>
                    <div class="input-group col-md-12" >
                      <textarea class="input-md textinput textInput form-control summernote" id="edit_short_description" name="edit_short_description"><?php echo htmlspecialchars_decode($product_info[0]->short_description); ?></textarea>
                    </div>  
                  </div>
                  <div class="form-group required">
                    <label for="edit_short_description" class="font-normal">Description<span class="red_star">*</span> <br><small><i>Detailed specifications and description of the product.</i></small></label>
                    <div class="input-group col-md-12">
                      <textarea class="input-md textinput textInput form-control summernote" name="edit_prod_description" id="edit_prod_description" placeholder="Description"><?php echo htmlspecialchars_decode($product_info[0]->description); ?></textarea>
                    </div>  
                  </div>

              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" value="Save" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_edit_basic_info"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>


<!-- =============== MODALS IMAGES ================== -->
<!--Modal Add Image -->
<div id="add_img" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add image to product <small>Add new image to the list</small></h4>
      </div>
      {!! Form::open(array('route' => 'add_product_img_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="modal-body">   
          <div class="panel-info"> 
            <div class="panel-body"> 

              <input type="hidden" name="edit_product_id" id="edit_product_id_img" value="{{ $product_info[0]->product_id }}">

              <div class="form-group required">
                <div class="custom-file-upload ">
                  <label for="image" class="control-label col-md-12 requiredField"> Upload 
                    <span class="red_star"><small><i> recommended size</i></small> (1200 x 800) px </span></span><br/>
                    <small><i>Upload your image here</i></small> </label>
                  <div class="controls col-md-12">
                    <input class="input-md textinput textInput form-control" id="file-upload" type="file" name="image" required/>
                  </div>
                </div>
              </div>
            </div>
          </div> 
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="submit_add_img" value="Add Image" class="ladda-button btn btn-primary" data-style="expand-right" id="submit_add_img"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<!--Modal Edit primary image -->
<div id="edit_primary_img" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
    {!! Form::open(array('route' => 'edit_primary_image_path', 'class' => 'add-form', 'files' => true)) !!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Primary image <small>Edit the primary image of the product</small></h4>
      </div>
      
        <div class="modal-body">   
          <div class="panel-info"> 
            <div class="panel-body"> 
              <div class="row"> 

                <input type="hidden" name="edit_primary_img_id" id="edit_primary_img_id_attr" value="">

                <div class="form-group col-md-12">
                  <label for="attribute" class="control-label col-md-12 requiredField">Primary Image<span class="red_star">* <small><i> recommended size</i></small> (1200 x 800) px </span> <br>
                    <small><i>Upload your new primary image</i></small>
                  </label>
                  <img class="col-md-6" id="display_edit_primary_img">
                  <div class="controls col-md-12">
                    <input class="input-md textinput textInput form-control" type="file" name="edit_primary_img" required/>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" value="Save" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_edit_primary_img"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<!--Modal Open Big Image -->
@foreach($images as $i)
<div id="img_big{{$i->product_img_id}}" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="text-align: center;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 class="modal-title"><strong>Image #{{$i->product_img_id}}</strong></h3>
      </div>
      <div class="modal-body" style="text-align: center;"> 
        <img class="img-responsive" src="{{getenv('S3_URL')}}/products/{{ $i->img}}">
      </div>
      <div class="modal-footer" style="border-top: none;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div> 
  </div>
</div>
@endforeach


<!-- =============== MODALS LINKED PRODUCTS ================== -->
<!--Modal Add Linked Product -->
<div id="add_linked_product" class="modal fade" role="dialog">
  <div class="modal-lg modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add a Linked Product <small>Add a new Linked Product</small></h4>
      </div>
      {!! Form::open(array('route' => 'add_product_link_path', 'id' => 'add_form', 'class' => 'add-form')) !!}
        <div class="modal-body">   
          <div class="panel-info"> 
            <div class="panel-body"> 

              <input type="hidden" name="link_product_id" id="edit_product_id_link" value="{{ $product_info[0]->product_id }}">

             <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                      <div class="ibox-content">
                        <div class="table-responsive">
                          <table id="linked_products_tbl" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" style="width:100%">
                            <thead>
                              <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Brand</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> 
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="submit_add_link" value="Add Link" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_add_link"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<!-- =============== MODALS SEO ================== -->
<!--Modal Edit SEO info -->
<div id="edit_seo" class="modal fade" role="dialog">
  <div class="modal-lg modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
    {!! Form::open(array('route' => 'edit_product_seo_info_path', 'class' => 'add-form')) !!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit SEO info <small>Edit the SEO info of the product</small></h4>
      </div>
      
        <div class="modal-body">   
          <div class="panel-info"> 
            <div class="panel-body"> 
              <div class="row"> 

                <input type="hidden" name="edit_product_id" id="edit_product_id" value="{{$product_info[0]->product_id}}">

                <div class="form-group required">
                  <label for="seo_title_edit" class="font-normal"> SEO Title <br><small><i>Title of the page</i></small></label>
                  <div class="input-group col-md-12">
                    <input class="input-md textinput textInput form-control" id="seo_title_edit" name="seo_title_edit" placeholder="SEO Title" value="{{ $product_info[0]->seo_title }}" type="text"/>
                  </div>
                </div>

                <div class="form-group required">
                  <label for="seo_slug_edit" class="font-normal"> SEO Slug <br><small><i>Slug of the product</i></small></label>
                  <div class="input-group col-md-12">
                    <textarea class="input-md textinput textInput form-control" id="seo_slug_edit" name="seo_slug_edit" placeholder="SEO Slug" type="text">{{ $product_info[0]->seo_slug }}</textarea>
                  </div>
                </div>

                <div class="form-group required">
                  <label for="seo_meta_desc_edit" class="font-normal"> SEO Meta Description <br><small><i>Description of the product</i></small></label>
                  <div class="input-group col-md-12">
                    <textarea class="input-md textinput textInput form-control" id="seo_meta_desc_edit" maxlength="255" name="seo_meta_desc_edit" placeholder="SEO Meta Description" type="text">{{ $product_info[0]->seo_meta_desc }}</textarea>
                  </div>
                </div>

                <div class="form-group required">
                  <label for="seo_meta_keywords_edit" class="font-normal"> SEO Meta Keywords <br><small><i>Keywords of the product</i></small></label>
                  <div class="input-group col-md-12">
                    <textarea class="input-md textinput textInput form-control" id="seo_meta_keywords_edit" maxlength="255" name="seo_meta_keywords_edit" placeholder="SEO Meta Keywords" type="text">{{ $product_info[0]->seo_meta_keywords }}</textarea>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" value="Save" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_edit_basic_info"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<!-- =============== MODALS TAGS ================== -->
<!--Modal Add Tag -->
<div id="add_tag" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add a Tag <small>Add a new tag to the product</small></h4>
      </div>
      {!! Form::open(array('route' => 'add_product_tag_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="modal-body">   
          <div class="panel-info"> 
            <div class="panel-body"> 

              <input type="hidden" name="edit_product_id" id="edit_product_id_tag" value="{{ $product_info[0]->product_id }}">

              <div class="form-group required">
                <label for="tag" class="control-label col-md-12 requiredField">Tag <br><small><i>Choose from the list of tags</i></small></label>
                <div class="controls col-md-12">
                  <select class="form-control" id="tag" name="tag">
                    @foreach($tagsList as $tl)
                    <option value="{{$tl->tag_id}}">{{$tl->name}}</option>
                    @endforeach
                  </select>  
                </div>   
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="submit_add_tag" value="Add Tag" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_add_tag"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<!-- =============== MODALS VARIANTS ================== -->
<!--Modal Add Variant -->
<div id="add_variant" class="modal fade" role="dialog">
  <div class="modal-lg modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD VARIANT <small>Add a new variant of the product</small></h4>
      </div>
      {!! Form::open(array('route' => 'add_variant_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="">   
          <div class="panel-info"> 
            <div class="panel-body">
              <div class="row"> 

                <input type="hidden" name="edit_product_id" value="{{ $product_info[0]->product_id }}">

                <div class="form-group col-md-12">
                  <div class="controls col-md-12 ">
                    <h3>GENERAL INFORMATION <small>UPDATING general informations of the product</small></h3>
                  </div>
                </div>

                <div class="col-md-12">


                <div class="form-group col-md-4">
                    <label for="edit_variant_sku" class="control-label requiredField">SKU Name<span class="red_star">*</span></label>
                    <div class="controls">
                      <input class="input-md textinput textInput form-control" id="variant_sku" name="variant_sku" placeholder="SKU" type="text" required/>
                    </div>  
                 </div>
                 
                  <div class="controls col-md-4">
                    <label for="edit_variant_regular_price" class="control-label requiredField">Regular Price<span class="red_star">*</span></label>
                    <input class="input-md textinput textInput form-control" id="variant_regular_price" name="variant_regular_price" placeholder="Regular Price" type="text" required />
                  </div>  
               
                  <!-- If the Stock is not enabled display the stock status -->
                  @if($product_info[0]->enable_stock_mgmt == 0)
                  <div class="form-group col-md-4" id="stock_status_showed">
                    <label for="variant_stock_status" class="control-label requiredField">Stock Status</label>
                    <div class="controls">
                      <select class="form-control" id="variant_stock_status" name="variant_stock_status">
                        <option id="add_stock_option1" value="1">In Stock</option>
                        <option id="add_stock_option2" value="0">Out of Stock</option>
                      </select>
                    </div>
                  </div>
                  <!-- If the Stock is enabled display the Stock Quantity -->
                  @else
                   <div class="form-group col-md-4" id="stock_qty_showed">
                    <label for="variant_stock_quantity" class="control-label requiredField">Stock Quantity </label>
                    <div class="controls">
                      <input class="input-md textinput textInput form-control" id="variant_stock_quantity" name="variant_stock_quantity" placeholder="Stock Quantity" type="text"/>
                    </div>  
                  </div>
                  @endif


                  <div class="form-group col-md-3">
                    <label for="variant_weight" class="control-label requiredField">Weight <small>(Kg)</small><span class="red_star">*</span></label>
                    <div class="controls">
                      <input class="input-md textinput textInput form-control" id="variant_weight" name="variant_weight" placeholder="Weight" type="text" required/>
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div class="controls">
                      <label for="variant_length" class="control-label requiredField"> Length <small>(Cm)</small></label>
                      <input class="input-md textinput textInput form-control" id="variant_length" name="variant_length" placeholder="Length" type="text"/>
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div class="controls">
                      <label for="variant_width" class="control-label requiredField"> Width <small>(Cm)</small></label>
                      <input class="input-md textinput textInput form-control" id="variant_width" name="variant_width" placeholder="Width" type="text"/>
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div class="controls">
                      <label for="variant_height" class="control-label requiredField"> Height <small>(Cm)</small></label>
                      <input class="input-md textinput textInput form-control" id="variant_height" name="variant_height" placeholder="Height" type="text"/>
                    </div>
                  </div>

                  <div class="form-group col-md-3">
                    <div class="controls">
                      <label for="variant_diameter" class="control-label requiredField"> Diameter <small>(Cm)</small></label>
                      <input class="input-md textinput textInput form-control" id="variant_diameter" name="variant_diameter" placeholder="Diameter" type="text"/>
                    </div>
                  </div>
                </div>
              </div>

              <div class="hr-line-dashed"></div>
              <div class="row">
                <div class="form-group col-md-12">
                    <div class="controls col-md-12 ">
                        <h3>ATTRIBUTES <small>Creating a product items assigned to its attributes</small></h3>
                    </div>
                </div>
                <div class="col-md-12" style="margin-bottom:10px">
                  <?php $count = 0; ?>
                  @foreach($all_attributes as $attr)
                  <div class="form-group col-md-3">
                    <label for="attribute_{{$count}}" class="control-label requiredField"><span>{{$attr->name}}<span class="red_star">*</span> </span><br><small><i>Choose an attribute from the list</i></small></label>
                    <div class="controls">
                      <select class="form-control attributes" id="add_attribute_{{$count}}" name="attribute{{ $count }}">
                            <option value="">-NONE-</option>
                        @foreach($attrItems as $item)
                          @if($attr->attribute_id == $item->attribute_id)
                            <option id="add_attribute_item_{{$item->attribute_item_id}}" value="{{$item->attribute_item_id}}">{{$item->item_name}}</option>
                          @endif
                        @endforeach
                      </select>  
                    </div>  
                  </div>
                  <?php $count++; ?>
                  @endforeach
                </div>
              </div>
              <input type="hidden" name="count" value="{{$count}}"/>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="add" value="Save" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_add_variant"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<!--Modal Update Variant -->
<div id="update_variant" class="modal fade" role="dialog">
  <div class="modal-lg modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">UPDATE VARIANT <small>Update the variant of the product</small></h4>
      </div>
      {!! Form::open(array('route' => 'update_variant_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="">   
          <div class="panel-info"> 
            <div class="panel-body">
              <div class="row"> 

                <input type="hidden" name="edit_product_id" id="edit_variant_product_id" value="{{ $product_info[0]->product_id }}">

                <input type="hidden" name="edit_variant_id" id="edit_variant_id">

                <div class="form-group col-md-12">
                  <div class="controls col-md-12 ">
                    <h3>GENERAL INFORMATION <small>UPDATING general informations of the product</small></h3>
                  </div>
                </div>

                <div class="col-md-12">

                  <div class="form-group col-md-4">
                    <label for="edit_variant_sku" class="control-label requiredField">SKU Name<span class="red_star">*</span></label>
                    <div class="controls">
                      <input class="input-md textinput textInput form-control" id="edit_variant_sku" name="edit_variant_sku" placeholder="SKU" type="text" required/>
                    </div>  
                  </div>

                  <div class="controls col-md-4">
                    <label for="edit_variant_regular_price" class="control-label requiredField">REGULAR PRICE<span class="red_star">*</span></label>
                    <input class="input-md textinput textInput form-control" id="edit_variant_regular_price" name="edit_variant_regular_price" placeholder="Regular Price" type="text" required/>
                  </div>  
                 
                  <!-- If the Stock is not enabled display the stock status -->
                  @if($product_info[0]->enable_stock_mgmt == 0)
                  <div class="form-group col-md-4" id="stock_status_showed">
                    <label class="control-label requiredField">Stock Status</label>
                    <div class="controls">
                      <select class="form-control" id="edit_variant_stock_status" name="edit_variant_stock_status">
                        <option id="stock_option1" value="1">In Stock</option>
                        <option id="stock_option2" value="0">Out of Stock</option>
                      </select>
                    </div>
                  </div>
                  <!-- If the Stock is enabled display the Stock Quantity -->
                  @else
                   <div class="form-group col-md-4" id="stock_qty_showed">
                    <label for="edit_variant_stock_quantity" class="control-label requiredField">Stock Qty </label>
                    <div class="controls">
                      <input class="input-md textinput textInput form-control" id="edit_variant_stock_quantity" name="edit_variant_stock_quantity" placeholder="Stock Quantity" type="text"/>
                    </div>  
                  </div>
                  @endif

                  <div class="form-group col-md-3">
                    <label for="edit_variant_weight" class="control-label requiredField">Weight <small>(Kg)</small><span class="red_star">*</span></label>
                    <div class="controls">
                      <input class="input-md textinput textInput form-control" id="edit_variant_weight" name="edit_variant_weight" placeholder="Weight" type="text" required/>
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div class="controls">
                      <label for="edit_variant_length" class="control-label requiredField"> Length <small>(Cm)</small></label>
                      <input class="input-md textinput textInput form-control" id="edit_variant_length" name="edit_variant_length" placeholder="Length" type="text"/>
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div class="controls">
                      <label for="edit_variant_width" class="control-label requiredField"> Width <small>(Cm)</small></label>
                      <input class="input-md textinput textInput form-control" id="edit_variant_width" name="edit_variant_width" placeholder="Width" type="text"/>
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div class="controls">
                      <label for="edit_variant_height" class="control-label requiredField"> Height <small>(Cm)</small></label>
                      <input class="input-md textinput textInput form-control" id="edit_variant_height" name="edit_variant_height" placeholder="Height" type="text"/>
                    </div>
                  </div>

                  <div class="form-group col-md-3">
                    <div class="controls">
                      <label for="edit_variant_diameter" class="control-label requiredField"> Diameter <small>(Cm)</small></label>
                      <input class="input-md textinput textInput form-control" id="edit_variant_diameter" name="edit_variant_diameter" placeholder="Diameter" type="text"/>
                    </div>
                  </div>
                </div>
              </div>

              @if(!empty($all_attributes))
              <div class="hr-line-dashed"></div>
              <div class="row">
                <div class="form-group col-md-12">
                  <div class="controls col-md-12 ">
                    <h3>ATTRIBUTES </h3>
                  </div>
                </div>
                <div class="col-md-12">
                  <?php $count = 0; ?>
                  @foreach($all_attributes as $attr)
                  <div class="form-group col-md-3">
                    <label class="control-label requiredField"><span style="">{{$attr->name}}<span class="red_star">*</span> </span><br><small><i>Choose an attribute from the list</i></small></label>
                    <div class="controls">
                      <select class="form-control" id="attribute_{{$attr->attribute_id}}" name="attribute{{ $count }}" attr="{{$attr->attribute_id}}">
                        <option value="" >-- NONE --</option>
                        @foreach($attrItems as $item)
                          @if($attr->attribute_id == $item->attribute_id)
                            <option id="attribute_item_{{$item->attribute_item_id}}" value="{{$item->attribute_item_id}}">{{$item->item_name}}</option>
                          @endif
                        @endforeach
                      </select>  
                    </div>  
                  </div>
                  <?php $count++; ?>
                  @endforeach
                </div>
              </div>
              @endif

              <input type="hidden" name="count" value="{{$count}}"/>

            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="submit_edit_variant" value="Save Changes" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_edit_variant"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<!--Modal add/edit variant promotion -->
<div id="edit_promo" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Variant Promotion</h4>
      </div>
      {!! Form::open(array('route' => 'edit_variant_promo_path', 'id' => 'edit_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="modal-body">   
          <div id="signupbox" class="mainbox col-md-12 col-sm-12">
            <div class="panel panel-info"> 
              <div class="panel-body"> 

                <input type="hidden" name="variant_id" id="variant_id_promo">

                 <div class="form-group required">
                    <label for="edit_variant_promo_price" class="font-normal"> Sales Price <br><small><i>Price when product is on sale</i></small></label>
                    <div class="input-group date col-md-12">
                      <input class="input-md textinput textInput form-control" id="edit_variant_promo_price" name="variant_promo_price" placeholder="Sales Price" style="margin-bottom:10px" type="text"/>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="font-normal"> Schedule <br><small><i>Choose the Start and End date of the sale</i></small></label>
                    <div>
                      <input onkeydown="return false" class="form-control" type="text" name="variant_promo_daterange" id="variant_promo_daterange" value="" autocomplete="off" required/>
                    </div>
                  </div>
              </div>
            </div>
          </div> 
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="stop_promo" id="stop_promo" value="Stop Promo" class="ladda-button btn btn-danger"/>
          <input type="submit" name="submit_edit_variant_promo" id="submit_edit_variant_promo" value="Save" class="ladda-button btn btn-primary" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<!--Modal Add Video -->
<div id="add_video" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add a Video <small>Add a new Video to the product</small></h4>
      </div>
      {!! Form::open(array('route' => 'add_product_video_path', 'id' => 'add_form', 'class' => 'add-form')) !!}
        <div class="modal-body">   
          <div class="panel-info"> 
            <div class="panel-body"> 

              <input type="hidden" name="video_product_id" id="edit_product_id_video" value="{{ $product_info[0]->product_id }}">

              <div class="form-group required">
                <label for="video" class="control-label col-md-12 requiredField">Video<span class="red_star">*</span><br><small><i>Type the video URL</i></small></label>
                <div class="controls col-md-12">
                  <input class="input-md textinput textInput form-control" type="text" id="video" name="video" placeholder="Enter video Link" required />
                </div>   
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="submit_add_video" value="Add Video" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_add_video"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<!-- =============== SCRIPTS ================== -->
@if ($errors->any())
  <script>
      $( document ).ready(function() {
          $('#error-modal').modal('show');
      });
  </script>
@endif

<!-- JS Custom Functions -->
<script src="/cms/js/custom_functions.js"></script>

<!-- Toastr script -->
<script src="/cms/js/plugins/toastr/toastr.min.js"></script>


<script type="text/javascript">

var s3_url = <?=json_encode(getenv('S3_URL')) ?>; 
var product_id = <?=json_encode($product_info[0]->product_id)?>;
var stock_mgmt = <?=json_encode($product_info[0]->enable_stock_mgmt)?>;

$(document).ready(function(){

    //script for Data Table
    $('.client_table').DataTable({
        ordering: false,
        paging: false,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            {extend: 'copy'},
            {extend: 'csv'},
            {extend: 'excel', title: 'ExampleFile'},
            {extend: 'pdf', title: 'ExampleFile'},
            {extend: 'print',
             customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');

                    $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
            }
            }
        ]
    });

    //script for multi selection of categories
    var config = {
                '.chosen-select'           : {},
                '.chosen-select-deselect'  : {allow_single_deselect:true},
                '.chosen-select-no-single' : {disable_search_threshold:10},
                '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
                '.chosen-select-width'     : {width:"95%"}
                }
            for (var selector in config) {
                $(selector).chosen(config[selector]);
            }

    //Hide the contents of the sections hidden section
    $('.hidden_ibox').hide();

    //activate items attribute section
    $('.tagsinput').tagsinput({
      tagClass: 'label label-primary'
    });

   $('[data-role="remove"]').html('xxx');

    // call the plugin of the variant promotion daterange input
    $('input[name="variant_promo_daterange"]').daterangepicker({
      opens: 'left',
      format: 'MM/DD/YYYY',
      startDate: moment(),
      endDate: moment().add(5, 'days'),
      minDate: moment(),
    });
    

    // script to activate the summer note
    $('.summernote').summernote({
           toolbar: [
                   ['font', ['bold', 'italic', 'underline', 'clear']],
                   ['fontname', ['fontname']],
                   ['fontsize', ['fontsize']],
                   ['color', ['color']],
                   ['para', ['ul', 'ol', 'paragraph']],
                   ['height', ['height']],
                   ['table', ['table']],
                   ['insert', ['link', 'hr']],
                   ['view', ['fullscreen', 'codeview']],
                 ],

               fontSizes: ['8', '9', '10', '11', '12', '13', '14', '15', '18', '24', '36', '48']
       });

    // PRODUCTS DATATABLE TO LINK TO THE CURRENT PRODUCT
     $('#linked_products_tbl').DataTable({
       processing: true,
       serverSide: true,
       ordering: true,
       paging: true,
       buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},
                {extend: 'print',
                 customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                }
                }
            ],
       ajax: {
           url: '/load-product-table-to-link',
           type: 'POST',
           data: {
                     'id' : product_id
                   },
           headers: { 'X-CSRF-Token': $('input[name=_token]').val() },
       },
       columns: [
               { data: "hidden",
                     render: function (data, type, row)
                     {
                       if(row.hidden == 0)
                        checked = 'checked';
                       else
                        checked = '';

                       return `<td> <input type="checkbox" value="`+ row.product_id +`" name="products[]"/> </td>`;
                     }  
                    },
               { data: "prod_name",
                 render: function (data, type, row)
                 {
                   return '<img src="'+ s3_url +'/products/thumbs/'+ row.primary_img +'" width="50" style="border-radius:10px; margin-right:5px; ">'+ row.prod_name +' ';
                 }
               },
               { data: "min_price",
                 render: function (data, type, row)
                 {                    
                    if (row.sales_price == null)
                    {
                        if(row.min_price == row.max_price)
                          return '$'+row.min_price;
                        else
                          return '$'+row.min_price+' - $'+ row.max_price;
                    }
                    else
                    {
                      if(row.min_price == row.max_price)
                          return '$'+row.sales_price+'  <span class="label label-warning b_radius" style="margin-left:5px">On Sale</span>';
                        else
                          return '$'+row.min_price+' - $'+ row.max_price+'  <span class="label label-warning b_radius" style="margin-left:5px">On Sale</span>';
                    }
                        
                    
                 }
                },
               { data: "cat_name",
                 render: function (data, type, row)
                 {
                  cat_list = '';
                  if(row.cat_name != null)
                  {
                    categ = row.cat_name.split(',');
                  
                   categ.forEach(function(entry) {
                        cat_list = cat_list + '<span class="label label-primary b_radius">'+entry+'</span> '
                    });
                  }
                   
                   return cat_list;
                 }        
               },
               { data: "brand"},
               
           ]
   });

});


  
// show hide image/color attribute input depending on radio selection 
$( ".type_radio" ).on('click', function() { 

    val = $("input[name='main_attribute_type']:checked").val();

    if(val == 1)
    {   
        $( "#image_input_div" ).slideDown( "slow" );
        $('input[name="image"]').prop('required',true);
        $( "#color_input_div" ).slideUp( "slow" );
        $('input[name="color_att"]').prop('required',false);
    }

    else if(val == 2)
    {
        $( "#color_input_div" ).slideDown( "slow" );
        $('input[name="color_att"]').prop('required',true);
        $( "#image_input_div" ).slideUp( "slow" );
        $('input[name="image"]').prop('required',false);
    }

});



// ================== ladda loader for add Variant ==================
loadLadda('submit_add_variant');

// ================== ladda loader for link category ==================
loadLadda('submit_add');

// ================== ladda loader for add tag =================
loadLadda('submit_add_tag');

// ================== ladda loader for add attribute =================
loadLadda('submit_add_attr');

// ================== ladda loader for add main attribute item =================
loadLadda('submit_add_main_attr_item');

// ================== ladda loader for add image =================
loadLadda('submit_add_img');

// ================== ladda loader for add video =================
loadLadda('submit_add_video');

// ================== ladda loader for add video =================
loadLadda('submit_add_link');

// ================== ladda loader for edit primary image ==================
loadLadda('submit_edit_primary_img');

// ================== ladda loader for editing basic info ==================
loadLadda('submit_edit_basic_info');

// ================== ladda loader for edit attribute =================
loadLadda('submit_edit_attr');

// ================== ladda loader for edit main attribute item =================
loadLadda('submit_edit_main_attr_item');

// ================== ladda loader for edit main attribute name =================
loadLadda('submit_main_attr_name');

// ================== ladda loader for edit Shipping info =================
loadLadda('submit_edit_shipping');

// ================== ladda loader for edit SEO =================
loadLadda('submit_edit_seo');

// ================== ladda loader for edit variant =================
loadLadda('submit_edit_variant');

// ==================== DELETE Image  =====================
ajaxDelete('.delete_product_img', 'delete-image', 'rec_');

// ==================== DELETE Variant  =====================
ajaxDelete('.delete_variant', 'delete-variant', 'var_rec_');

 // ==================== DELETE VIDEO  =====================
ajaxDelete('.delete_prod_video', 'delete-prod-video', 'prod_video_');

</script>

<script src="cms/js/products/product-details-attributes.js"></script>

<script src="cms/js/products/product-details-variants.js"></script>

<script src="cms/js/products/product-details-tags.js"></script>

<script src="cms/js/products/product-details-categories.js"></script>

<script src="cms/js/products/product-details-images.js"></script>

<script src="cms/js/products/product-details-product-links.js"></script>

@endsection