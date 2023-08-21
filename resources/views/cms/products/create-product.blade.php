<?php use App\Http\Controllers\CategoryController;?>

@extends('cms.layouts.app')

@section('content')

<style type="text/css">
    .chosen-container-multi {
    width: 100% !important;
}
</style>

{!! Form::open(array('route' => 'cms_add_product_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}


<!-- Product title, image and short description Section -->
<div class="row wrapper border-bottom white-bg page-heading">

  <div class="col-md-3">
    <h2> Products</h2>
    <ol class="breadcrumb ">
      <li>
        <strong><a href="{{ route('cms_products') }}">Products</a></strong>
      </li>
      <li>
         Add Product
      </li>
    </ol>
    <hr>
  </div>


  <div class="col-md-12">
    <div class="form-group col-md-6">
      <label for="name" class="control-label requiredField"> Product name<span class="red_star">*</span> <br><small><i>Type the name of the product</i></small></label>
      <input class="input-md textinput textInput form-control" id="name" maxlength="255" name="name" placeholder="Product name" type="text" required/>
    </div>
     <div class="form-group col-md-6">
      <label for="price" class="control-label requiredField"> Regular Price<span class="red_star">*</span> <br><small><i>Standard price of the product</i></small></label>
      <input class="input-md textinput textInput form-control" id="price" name="price" placeholder="Regular Price" type="decimal" required/>
    </div>
    <div class="form-group margin-top col-md-6">
      <label for="categories" class="control-label requiredField"> Categories<br><small><i>Link product to categories</i></small></label>
      <div class="controls">
        <select id="categories" name="categories[]" data-placeholder="Choose a category..." class="chosen-select" multiple tabindex="4">
          <?php echo CategoryController::recursiveSelect($categoryList, 1); ?>
        </select>
      </div>
    </div>
    <div class="custom-file-upload margin-top col-md-6">
      <label for="image" class="control-label requiredField"> Image<span class="red_star">* <small><i> recommended size</i></small> (1200 x 800) px </span><br><small><i>Main image of the product</i></small> </label>
      <input class="input-md textinput textInput form-control" id="file-upload" type="file" name="image" required />
    </div>
  </div>
</div>


<div class="wrapper animated fadeInRight margin-top col-lg-4">
  <div class="row">
      <!-- SHIPPING Section -->
      <div class="col-md-12" style="margin-bottom:15px;">
        <div class="ibox">
          <div class="ibox-title">
            <h5><span><img src="/cms/svg/delivery-truck.svg" width="20"></span> &nbsp SHIPPING <small>Delivery information of product</small></h5>
            <div class="ibox-tools">
              <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
              </a>
            </div>
          </div>
          <div class="ibox-content profile-content">
            <div>
              <div id="signupbox" class="mainbox"> 
              
                <div class="form-group required">
                  <label for="weight" class="font-normal">Weight<small> (Kg)</small><span class="red_star">*</span></label>
                  <div class="input-group date col-md-12">
                    <input class="input-md textinput textInput form-control" id="weight" name="weight" placeholder="Weight" type="text" required/>
                  </div>
                </div>
                <div class="form-group required">
                  <label for="length" class="font-normal">Length<small> (Cm)</small></label>
                  <div class="input-group date col-md-12">
                    <input class="input-md textinput textInput form-control" id="length" name="length" placeholder="Length" type="text"/>
                  </div>
                </div>
                <div class="form-group required">
                  <label for="width" class="font-normal">Width<small> (Cm)</small></label>
                  <div class="input-group date col-md-12">
                    <input class="input-md textinput textInput form-control" id="width" name="width" placeholder="Width" type="text"/>
                  </div>
                </div>
                <div class="form-group required">
                  <label for="height" class="font-normal">Height<small> (Cm)</small></label>
                  <div class="input-group date col-md-12">
                    <input class="input-md textinput textInput form-control" id="height" name="height" placeholder="Height" type="text"/>
                  </div>
                </div>
                <div class="form-group required">
                  <label for="diameter" class="font-normal">Diameter<small> (Cm)</small></label>
                  <div class="input-group date col-md-12">
                    <input class="input-md textinput textInput form-control" id="diameter" name="diameter" placeholder="Diameter" type="text"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


    <!-- INVENTORY Section -->
    <div class="col-md-12" style="margin-bottom:15px;">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><img src="/cms/svg/storage.svg" width="20"></span> &nbspINVENTORY <small>Manage the stock of your product</small></h5>
           <div class="ibox-tools">
              <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
              </a>
            </div>
        </div>
        <div class="ibox-content profile-content">
          <div>
            <div id="signupbox" class="mainbox">
            
              <div class="form-group required">
                <label for="manage_stock" class="font-normal">Manage Stock <br><small><i>Choosing if we manage the stock.</i></small></label>
                <div><input id="manage_stock" class='big_radio' type="checkbox" value="1" name="manage_stock"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- BRAND Section -->
    <div class="col-md-12" style="margin-bottom:15px;">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><img src="/cms/svg/award.svg" width="20"> </span> BRAND <small>Manage the product Brand</small></h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content profile-content">
          <div id="signupbox" class="mainbox">
            <div class="form-group margin-top">
              <label for="brand" class="control-label requiredField"> Brand <br><small><i>Select the Brand of the product</i></small></label>
              <div class="controls input-group date col-md-12">
                <select class="form-control" id="brand" name="brand">
                  <option value="">--NONE--</option>
                  @foreach ($brandsList as $b)
                    <option value="{{$b->brand_id}}">{{$b->name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div> 
        </div>
      </div>
    </div>
  </div>
</div>


<div class="wrapper animated fadeInRight margin-top col-lg-8">
  <div class="row">
    <!-- DESCRIPTION Section -->
    <div class="col-md-12" style="margin-bottom:15px;">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><img src="/cms/svg/information.svg" width="20"></span> &nbspPRODUCT DESCRIPTION <small>General description</small></h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content profile-content">
          <div class="form-group required">
            <label for="edit_short_description" class="font-normal">Short Description<span class="red_star">*</span> <br><small><i>Edit the general description of the product</i></small></label>
            <div class="input-group col-md-12">
              <textarea class="input-md textinput textInput form-control summernote" id="short_description" name="short_description" placeholder="Short Description" required></textarea>
            </div>  
          </div>
          <div class="form-group required">
            <label for="edit_short_description" class="font-normal">Description<span class="red_star">*</span> <br><small><i>Detailed specifications and description of the product.</i></small></label>
            <div class="input-group col-md-12">
              <textarea class="input-md textinput textInput form-control summernote" name="prod_description" id="prod_description" placeholder="Description" required></textarea>
            </div>  
          </div>
        </div>
      </div>
    </div>


    <!-- SEO Section -->
    <div class="col-md-12" style="margin-bottom:15px;">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><i class="fa fa-search fa-lg">&nbsp</i></span> SEO <small>Manage Your Ranking on Google</small></h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content profile-content">
          <div id="signupbox" class="mainbox">

            <div class="form-group required">
              <label for="seo_title" class="font-normal"> SEO Title <br><small><i>Title of the page.</i></small></label>
              <div class="input-group col-md-12">
                <input class="input-md textinput textInput form-control" id="seo_title" name="seo_title" placeholder="SEO Title" type="text"/>
              </div>
            </div>

            <div class="form-group required">
              <label for="seo_slug" class="font-normal"> SEO Slug <br><small><i>Slug of the product URL.</i></small></label>
              <div class="input-group col-md-12">
                <textarea class="input-md textinput textInput form-control" id="seo_slug" name="seo_slug" placeholder="SEO Slug" type="text"></textarea>
              </div>
            </div>

            <div class="form-group required">
              <label for="seo_meta_desc" class="font-normal"> SEO Meta Description <br><small><i>Description of the Product.</i></small></label>
              <div class="input-group col-md-12">
                <textarea class="input-md textinput textInput form-control" id="seo_meta_desc" maxlength="255" name="seo_meta_desc" placeholder="SEO Meta Description" type="text"></textarea>
              </div>
            </div>

            <div class="form-group required">
              <label for="seo_meta_keywords" class="font-normal"> SEO Meta Keywords <br><small><i>Keywords of the product.</i></small></label>
              <div class="input-group col-md-12">
                <textarea class="input-md textinput textInput form-control" id="seo_meta_keywords" maxlength="255" name="seo_meta_keywords" placeholder="SEO Meta Keywords" type="text"></textarea>
              </div>
            </div>

          </div> 
        </div>
      </div>
    </div>
  </div>
</div>


<div class="wrapper animated padding-bottom margin-top">
  <div class="row">
    <div style="text-align:right;margin-top: 15px; margin-right: 15px;">
      <button id="submit_add" class="ladda-button btn btn-primary save_btn">Save</button>
    </div>
  </div>
</div>


{!! Form::close() !!}

<script src="/cms/js/custom_functions.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

        // activate the multiselect for the categories
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


        // call the plugin of the daterange input
        $('input[name="sales_daterange"]').daterangepicker({

          format: 'YYYY-MM-DD',
          startDate: moment(),
          endDate: moment().add(30, 'days'),
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

     });

      // ================== ladda loader for add product ==================
      loadLadda('submit_add');



      // check if the summernote textarea are filled and pop an error if not
      $('#submit_add').on('click', function(e) {

        if($('#short_description').summernote('isEmpty'))   
          swal ( 'Required field' , 'Please fill the "Short Description" field of your product !' ,  'warning' )
        if($('#prod_description').summernote('isEmpty')) 
         swal ( 'Required field' , 'Please fill the "Description" field of your product !' ,  'warning' )
      });



</script>


@endsection