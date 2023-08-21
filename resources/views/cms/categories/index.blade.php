@extends('cms.layouts.app')

@section('content')

<?php use App\Http\Controllers\CategoryController;?>

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-4">
    <h2>Categories Management</h2>
    <ol class="breadcrumb active">
      <li>
        <strong><a href="">Build Your Hierarchy of Categories</a></strong>
      </li>
    </ol>
  </div>
  <div class="col-lg-8">
    <div class="title-action">
      <a href="#" data-toggle="modal" data-target="#add_category" class="btn btn-primary">Add category</a>
    </div>
  </div>
</div>

<!--Display List of Brands -->
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Product Categories</h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            <table id="client_table" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Highlights</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php echo CategoryController::recursiveDisplay($categoryList, 1); ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!--MODALS -->

<!--Modal Add Category -->
<div id="add_category" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD NEW CATEGORY</h4>
      </div>
      {!! Form::open(array('route' => 'add_category_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="modal-body">   
          <div id="signupbox" class="mainbox col-md-12 col-sm-12">
            <div class="panel panel-info"> 
              <div class="panel-body">       
                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label for="name" class="control-label requiredField"> Category Name<span class="red_star">*</span><br><small><i>Name off the category</i></small> </label>
                      <input class="input-md textinput textInput form-control" id="name" maxlength="255" name="name" placeholder="Category name" style="margin-bottom: 10px" type="text" required/>
                  </div>
                </div>

                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label for="parent" class="control-label requiredField">Parent</label>
                    <select class="form-control" id="parent" name="parent" placeholder="Parent" style="margin-bottom: 10px">
                      <option value="0">--NONE--</option>
                      <?php echo CategoryController::recursiveSelect($categoryList, 1); ?>
                    </select>
                  </div>
                </div>

                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label for="description" class="control-label"> Description <br><small><i>Desription of the category</i></small></label>
                    <input class="input-md textinput textInput form-control" id="description" name="description" placeholder="Description" style="margin-bottom: 10px" type="text" />
                  </div>
                </div>

                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label for="image" class="control-label requiredField"> Upload Image <span class="text-danger">(660x385)</span><small> or a propotional ratio</small><br><small><i>Image associated to the category</i></small></label>
                    <div class="controls  ">
                      <input id="file-upload" type="file" name="image" style="margin-bottom: 10px" />
                    </div>
                  </div>
                </div>  

                <br> 

                <div class="form-group col-md-12"><h2>SEO</h2></div>

                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> SEO Title <br><small><i>Title of Page SEO</i></small></label>
                    <input class="input-md textinput textInput form-control" name="seo_title" placeholder="Title" style="margin-bottom: 10px" type="text"/>
                  </div>
                </div>

                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> SEO Description <br><small><i>Description of Page SEO</i></small></label>
                    <input class="input-md textinput textInput form-control" name="seo_description" placeholder="Description" style="margin-bottom: 10px" type="text"/>
                  </div>
                </div>

                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> SEO Keywords <br><small><i>Separate Keywords with commas (,)</i></small></label>
                    <input class="input-md textinput textInput form-control" name="seo_keywords" placeholder="Keywords" style="margin-bottom: 10px" type="text"/>
                  </div>
                </div>

                <div class="form-group col-md-12">
                  <div class="controls">
                    <label class="control-label"> OG Image<span class="red_star"> <small><i> recommended size</i></small> (1200 x 630) px</span> <br>
                      <small><i>Add OG image</i></small>
                    </label>
                    <input class="input-md textinput textInput form-control" name="og_image" type="file"/>
                  </div>
                </div>

              </div>
            </div>
          </div> 
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="add" value="Add category" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_add"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>


<!-- Modal Update Category -->
<div id="update_category" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
     {!! Form::open(array('route' => 'cms_category', 'id' => 'update_form', 'class' => 'update-form', 'files' => true)) !!} 
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">EDIT CATEGORY</h4>
      </div>
      <div class="modal-body">   
        <div id="signupbox" class="mainbox col-md-12 col-sm-12">
          <div class="panel panel-info"> 
            <div class="panel-body" >
 
              <input type="hidden" name="edit_id" id="edit_id">

              <div class="form-group col-md-12">  
                <div class="controls">
                  <label for="name" class="control-label requiredField"> Category Name<span class="red_star">*</span><br><small><i>Name off the category</i></small> </label>
                    <input class="input-md textinput textInput form-control" id="edit_name" maxlength="30" name="edit_name" placeholder="Category name" style="margin-bottom: 10px" type="text" required="" />
                </div>
              </div>

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label for="parent" class="control-label requiredField">Parent <br><small><i>Select the parent of the category</i></small></label>
                  <select class="form-control" id="edit_parent" name="edit_parent" placeholder="Parent" style="margin-bottom: 10px">
                    <option value="0">--NONE--</option>
                    <?php echo CategoryController::recursiveSelect($categoryList, 1); ?>
                  </select>  
                </div>
              </div>

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label for="description" class="control-label"> Description <br><small><i>Desription of the category</i></small></label>
                  <input class="input-md textinput textInput form-control" id="edit_description" name="edit_description" placeholder="Description" style="margin-bottom: 10px" type="text" />
                </div>
              </div>

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label for="image" class="control-label requiredField"> Upload Image <span class="text-danger">(660x385)</span><small> or a propotional ratio</small><br><small><i>Image associated to the category</i></small></label>
                  <div class="controls  ">
                      <img id="display_edit_img" src="" style="width:100px;">
                      <input id="edit_image" type="file" name="edit_image"/>
                  </div>
                </div>
              </div>

               <div class="form-group col-md-12"><h2>SEO</h2></div>

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label class="control-label"> SEO Title <br><small><i>Title of Page SEO</i></small></label>
                  <input class="input-md textinput textInput form-control" name="edit_seo_title" placeholder="Title" style="margin-bottom: 10px" type="text"/>
                </div>
              </div>

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label class="control-label"> SEO Description <br><small><i>Description of Page SEO</i></small></label>
                  <input class="input-md textinput textInput form-control" name="edit_seo_description" placeholder="Description" style="margin-bottom: 10px" type="text"/>
                </div>
              </div>

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label class="control-label"> SEO Keywords <br><small><i>Separate Keywords with commas (,)</i></small></label>
                  <input class="input-md textinput textInput form-control" name="edit_seo_keywords" placeholder="Keywords" style="margin-bottom: 10px" type="text"/>
                </div>
              </div>

              <div class="form-group col-md-12">
                <div class="controls">
                  <label class="control-label"> OG Image<span class="red_star"> <small><i> recommended size</i></small> (1200 x 630) px</span> <br>
                    <small><i>Update OG image</i></small>
                  </label>
                  <input class="input-md textinput textInput form-control" name="edit_og_image" type="file"/>
                  <img class="display_og_img" src="" width="200">
                </div>
              </div>                              
            
            </div>
          </div> 
        </div>
      </div>
      <div class="modal-footer" style="border-top: none;">
        <input type="submit" name="update" value="Save Changes" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_edit"/>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    {!! Form::close() !!}      
    </div>
  </div>
</div>


<script src="/cms/js/custom_functions.js"></script>
<script type="text/javascript">
  var s3_url = <?=json_encode(getenv('S3_URL')) ?>; 

  //script for Data Table
  $(document).ready(function(){
      $('#client_table').DataTable({
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

     // ================== ladda loader for add ==================
     loadLadda('submit_add');
     // ================== ladda loader for edit =================
     loadLadda('submit_edit');


  });
</script>

<script src="/cms/js/categories/load-data-to-edit-categories.js"></script>
<!-- highlight category script -->
<script src="/cms/js/categories/ajax-highlight-category.js"></script>
<!-- Delete Categories Script -->
<script src="/cms/js/categories/delete-categories.js"></script>


@endsection