
@extends('cms.layouts.app')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-4">
    <h2>Brands Management</h2>
    <ol class="breadcrumb active">
      <li>
        <strong><a href="">Manage the Brands to which your products are assigned</a></strong>
      </li>
    </ol>
  </div>
  <div class="col-lg-8">
    <div class="title-action">
      <a href="#" data-toggle="modal" data-target="#add_brand" class="btn btn-primary">Add brand</a>
    </div>
  </div>
</div>

<!--Display List of Brands -->
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Products Brands</h5>
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
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($brands as $b)
                    <tr class="gradeX" id="rec_{{$b->brand_id}}">
                      <td>
                        <?php 
                          if($b->img != null)
                            $img = getenv('S3_URL').'/brands/thumbs/'.$b->img;
                          else
                            $img ="images/icons/brand_icon.png";
                         ?>
                        <img src="{{$img}}" width="30" style="border-radius:10px; margin-right:5px; ">
                        {{$b->name}}
                      </td>
                      <td>{{$b->description}}</td>
                      <td>
                        <button onclick="loadDataToEdit({{$b->brand_id}});" data-toggle="modal" data-target="#update_brand" type="button" class="edit_btn" title="Edit Brand"><i class="fa fa-edit fa-lg"></i></button>
                        <button type="button" id='{{$b->brand_id}}'class="edit_btn delete_brand" title="Delete brand"><i class="fa fa-trash fa-lg"></i></button>
                      </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!--MODALS -->

<!--Modal Add Brand -->
<div id="add_brand" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD NEW Brand</h4>
      </div>
      {!! Form::open(array('route' => 'add_brand_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="modal-body">   
          <div id="signupbox" class="mainbox col-md-12 col-sm-12">
            <div class="panel panel-info"> 
              <div class="panel-body"> 
                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label for="name" class="control-label"> Brand Name<span class="red_star">*</span> <br><small><i>Name of the brand created</i></small></label>
                    <input class="input-md textinput textInput form-control" id="name" maxlength="255" name="name" placeholder="Brand name" style="margin-bottom: 10px" type="text" required/>
                  </div>
                </div>

                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label for="description" class="control-label"> Description <br><small><i>Description of the brand created</i></small></label>
                    <input class="input-md textinput textInput form-control" id="description" name="description" placeholder="Description" style="margin-bottom: 10px" type="text" />
                  </div>
                </div>

                <div class="form-group col-md-12">
                  <div class="controls">
                    <label for="description" class="control-label"> Image <br><small><i>Upload an image to your brand</i></small></label>
                    <input class="input-md textinput textInput form-control" id="brand_img" name="brand_img" type="file"/>
                  </div>
                </div>

              </div>
            </div>
          </div> 
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="add" value="Add brand" class="ladda-button btn btn-primary" data-style="expand-right" id="submit_add" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>


<!-- Modal Update Brand -->
<div id="update_brand" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
     {!! Form::open(array('route' => 'cms_edit_brand', 'id' => 'update_form', 'class' => 'update-form', 'files' => true)) !!} 
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">EDIT BRAND</h4>
      </div>
      <div class="modal-body">   
        <div id="signupbox" class="mainbox col-md-12 col-sm-12">
          <div class="panel panel-info">
            <div class="panel-body"> 

              <input type="hidden" name="edit_id" id="edit_id">

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label for="edit_name" class="control-label"> Brand Name<span class="red_star">*</span> <br><small><i>Name of the brand</i></small></label>
                  <input class="input-md textinput textInput form-control" id="edit_name" maxlength="30" name="edit_name" placeholder="Brand name" style="margin-bottom: 10px" type="text" required="" />
                </div>
              </div>

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label for="description" class="control-label"> Description <br><small><i>Description of the brand</i></small> </label>
                  <input class="input-md textinput textInput form-control" id="edit_description" name="edit_description" placeholder="Description" style="margin-bottom: 10px" type="text" />
                </div>
              </div>

              <div class="form-group col-md-12">
                <div class="controls">
                  <label for="description" class="control-label"> Image <br><small><i>Upload an image to your brand</i></small></label>
                  <input class="input-md textinput textInput form-control" id="edit_brand_img" name="edit_brand_img" type="file"/>
                  <img class="display_brand_img" src="" width="200">
                </div>
              </div>

            </div>
          </div>
        </div> 
      </div>
      <div class="modal-footer" style="border-top: none;">
        <input type="submit" name="update" value="Save Changes" class="ladda-button btn btn-primary" data-style="expand-right" id="submit_edit" />
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    {!! Form::close() !!}      
    </div>
  </div>
</div>

<script src="/cms/js/custom_functions.js"></script>
<script type="text/javascript">

var s3_url = <?=json_encode(getenv('S3_URL')) ?>; 

$(document).ready(function(){

    //script for Data Table
    $('#client_table').DataTable({
        ordering: true,
        paging: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            {extend: 'excel', title: 'ExampleFile'},

        ]
    });

});

// ================== ladda loader for add ==================
    loadLadda('submit_add');
// ================== ladda loader for edit =================
    loadLadda('submit_edit');

</script>

<!-- ============== Script allowing data to be loaded in Edit PopUp  =============== -->
<script src="/cms/js/brands/ajax-load-data-to-edit-brand.js"></script>
<!-- ============== Delete Brand  =============== -->
<script src="/cms/js/brands/delete-brand.js"></script>

@endsection