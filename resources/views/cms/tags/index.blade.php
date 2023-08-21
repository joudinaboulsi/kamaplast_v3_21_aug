@extends('cms.layouts.app')

@section('content')

<!-- Header and Main Information -->
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-4">
    <h2>Tags Management</h2>
    <ol class="breadcrumb active">
      <li>
        <strong><a href="">Tag Your Products And Users</a></strong>
      </li>
    </ol>
  </div>
  <div class="col-lg-8">
    <div class="title-action">
      <a href="#" data-toggle="modal" data-target="#add_tag" class="btn btn-primary">Add tag</a>
    </div>
  </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <!-- List of product tags -->
    <div class="col-lg-6">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Products Tags</h5>
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
                  <th>Collection</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($tags as $t)
                  @if($t->type_id == 1)
                    <tr class="gradeX" id="rec_{{$t->tag_id}}">
                      <td>{{$t->name}}</td>
                      <td>{{$t->description}}</td>
                      <td>

                        <?php  
                        if($t->is_collection == 0)
                          $checked = '';
                        else
                          $checked = 'checked';
                        ?>


                        <div class="controls" style="display:inline">
                          <div class="switch">
                            <div class="onoffswitch">
                              <input type="checkbox" onclick="ajaxTagCollection({{$t->tag_id}})" class="onoffswitch-checkbox" id="tag_{{$t->tag_id}}" name="tag_{{$t->tag_id}}" data-toggle="collapse" data-target="#demo" value="1" {{$checked}}>
                              <label class="onoffswitch-label" for="tag_{{$t->tag_id}}">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                              </label>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <button onclick="loadDataToEdit({{$t->tag_id}});" data-toggle="modal" data-target="#update_tag" type="button" class="edit_btn" title="Edit Tag"><i class="fa fa-edit fa-lg"></i></button>
                        <button type="button" id='{{$t->tag_id}}' name="{{$t->type_id}}" class="edit_btn delete_tag" title="Delete tag"><i class="fa fa-trash fa-lg"></i></button>
                      </td>
                    </tr>
                  @endif
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
 
    <div class="col-lg-6">
      <!-- List of Users tags -->
      <div class="ibox float-e-margins" style="padding-bottom: 10px;">
        <div class="ibox-title">
          <h5>Users Tags</h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            <table id="client_table2" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($tags as $t) 
                  @if($t->type_id == 2)
                    <tr class="gradeX" id="rec_{{$t->tag_id}}">
                      <td>{{$t->name}}</td>
                      <td>{{$t->description}}</td>
                      <td>
                        <button onclick="loadDataToEdit({{$t->tag_id}});" data-toggle="modal" data-target="#update_tag" type="button" class="edit_btn" title="Edit Tag"><i class="fa fa-edit fa-lg"></i></button>
                        <button type="button" id='{{$t->tag_id}}' name="{{$t->type_id}}" class="edit_btn delete_tag" title="Delete tag"><i class="fa fa-trash fa-lg"></i></button>
                      </td>
                    </tr>
                    @endif
                  @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- List of Blog tags -->
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Blog Tags</h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            <table id="client_table3" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($tags as $t) 
                  @if($t->type_id == 3)
                    <tr class="gradeX" id="rec_{{$t->tag_id}}">
                      <td>{{$t->name}}</td>
                      <td>{{$t->description}}</td>
                      <td>
                        <button onclick="loadDataToEdit({{$t->tag_id}});" data-toggle="modal" data-target="#update_tag" type="button" class="edit_btn" title="Edit Tag"><i class="fa fa-edit fa-lg"></i></button>
                        <button type="button" id='{{$t->tag_id}}' name="{{$t->type_id}}" class="edit_btn delete_tag" title="Delete tag"><i class="fa fa-trash fa-lg"></i></button>
                      </td>
                    </tr>
                    @endif
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
<!--Modal Add Tag -->
<div id="add_tag" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD NEW TAG</h4>
      </div>
      {!! Form::open(array('route' => 'add_tag_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="modal-body">   
          <div id="signupbox" class="mainbox col-md-12 col-sm-12">
            <div class="panel panel-info"> 
              <div class="panel-body"> 

                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label for="description" class="control-label"> Tag Name<span class="red_star">*</span> <br><small><i>Name of the tag created</i></small></label>
                    <input class="input-md textinput textInput form-control" id="name" maxlength="255" name="name" placeholder="Tag name" style="margin-bottom: 10px" type="text" required/>
                  </div>
                </div>

                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label for="description" class="control-label"> Description <br><small><i>Description of the tag created</i></small></label>
                    <input class="input-md textinput textInput form-control" id="description" name="description" placeholder="Description" style="margin-bottom: 10px" type="text" />
                  </div>
                </div>

                <div class="form-group col-md-12">
                  <div class="controls ">
                    <label for="tag_type" class="font-normal"> Tag Type <br><small><i>Product or User Tag</i></small></label>
                    <select class="form-control" id="tag_type" name="tag_type">
                      <option value="1">Products Tag</option>
                      <option value="2">Users Tag</option>
                      <option value="3">Blog Tag</option>
                    </select>
                  </div>
                </div>

                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label for="image" class="control-label requiredField"> Upload Image <span class="text-danger">(600x400)</span><small> or a propotional ratio</small><br><small><i>Image associated to the tag</i></small></label>
                    <div class="controls  ">
                      <input id="file-upload" type="file" name="image" style="margin-bottom: 10px" />
                    </div>
                  </div>
                </div> 

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
          <input type="submit" name="add" value="Add tag" class="ladda-button btn btn-primary" data-style="expand-right" id="submit_add" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<!-- Modal Update Tag -->
<div id="update_tag" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
     {!! Form::open(array('route' => 'cms_edit_tag', 'id' => 'update_form', 'class' => 'update-form', 'files' => true)) !!} 
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">EDIT TAG</h4>
      </div>
      <div class="modal-body">   
        <div id="signupbox" class="mainbox col-md-12 col-sm-12">
          <div class="panel panel-info">
            <div class="panel-body"> 

              <input type="hidden" name="edit_id" id="edit_id">

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label for="description" class="control-label"> Tag Name<span class="red_star">*</span> <br><small><i>Name of the tag</i></small></label>
                  <input class="input-md textinput textInput form-control" id="edit_name" maxlength="30" name="edit_name" placeholder="Tag name" style="margin-bottom: 10px" type="text" required="" />
                </div>
              </div>

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label for="description" class="control-label"> Description <br><small><i>Description of the tag</i></small> </label>
                  <input class="input-md textinput textInput form-control" id="edit_description" name="edit_description" placeholder="Description" style="margin-bottom: 10px" type="text" />
                </div>
              </div>

              <div class="form-group col-md-12">  
                <div class="controls">
                  <label for="image" class="control-label requiredField"> Upload Image <span class="text-danger">(600x400)</span><small> or a propotional ratio</small><br><small><i>Image associated to the tag</i></small></label>
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

    //script for Data Table
    $('#client_table2').DataTable({
        ordering: true,
        paging: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            {extend: 'excel', title: 'ExampleFile'},

        ]
    });

    //script for Data Table
    $('#client_table3').DataTable({
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

<!-- Ajax load data to edit tags -->
<script src="/cms/js/tags/tags-ajax-load-data-to-edit.js"></script>
<!-- Delete tags Script -->
<script src="/cms/js/tags/delete-tags.js"></script>
<!-- Delete Categories Script -->
<script src="/cms/js/tags/ajax-collection-tags.js"></script>

ajax-collection-tags.js

@endsection