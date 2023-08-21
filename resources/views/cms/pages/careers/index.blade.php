
@extends('cms.layouts.app')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-4">
    <h2>Careers Management</h2>
    <ol class="breadcrumb active">
      <li>
        <strong><a href="">Manage the Careers page of your website</a></strong>
      </li>
    </ol>
  </div>
  <div class="col-lg-8">
    <div class="title-action">
    </div>
  </div>
</div>

<!--Display List of careers -->
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Careers</h5>
          <div class="ibox-tools">
            <a href="#" data-toggle="modal" data-target="#add_careers" class="btn btn-primary">Add Careers</a>
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            <table id="news_table" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
              <thead>
                <tr>
                  <th>#</th>
                  <th>Title</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($careers as $c)
                  <tr class="gradeX" id="rec_{{$c->career_id}}">
                    <td>{{$c->career_id}}</td>
                    <td>{{$c->title}}</td>
                    <td>
                      <button onclick="loadDataToEdit({{$c->career_id}});" data-toggle="modal" data-target="#edit_careers" type="button" class="edit_btn" title="Edit career"><i class="fa fa-edit fa-lg"></i></button>
                      <button type="button" id="{{$c->career_id}}" class="edit_btn delete_careers" title="Delete Careers"><i class="fa fa-trash fa-lg"></i></button>
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

<!--Modal Add Careers -->
<div id="add_careers" class="modal fade" role="dialog" style="overflow: auto;">
  <div class="modal-lg modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD CAREERS</h4>
      </div>
      {!! Form::open(array('route' => 'cms_add_careers', 'id' => 'add_careers_form', 'class' => 'add-form')) !!}
        <div class="modal-body">   
          <div id="signupbox" class="mainbox col-md-12 col-sm-12">
            <div class="panel panel-info"> 
              <div class="panel-body"> 

                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> Title<span class="red_star">*</span> <br><small><i>Title of the News</i></small></label>
                    <input class="input-md textinput textInput form-control" maxlength="255" name="title" placeholder="Title" style="margin-bottom: 10px" type="text"/>
                  </div>
                </div>
   
                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> Description<span class="red_star">*</span> <br><small><i>Description of the career</i></small></label>
                    <textarea class="input-md textinput textInput form-control summernote" name="description" required></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div> 
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="add" value="Save" class="ladda-button btn btn-primary" data-style="expand-right" id="submit_add" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>


<!--Modal Edit Careers -->
<div id="edit_careers" class="modal fade" role="dialog" style="overflow: auto;">
  <div class="modal-lg modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">EDIT CAREERS</h4>
      </div>
      {!! Form::open(array('route' => 'cms_edit_careers', 'id' => 'edit_careers_form', 'class' => 'add-form')) !!}
        <div class="modal-body">   
          <div id="signupbox" class="mainbox col-md-12 col-sm-12">
            <div class="panel panel-info"> 
              <div class="panel-body"> 

                <input type="hidden" name="e_career_id">

                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> Title<span class="red_star">*</span> <br><small><i>Title of the News</i></small></label>
                    <input class="input-md textinput textInput form-control" maxlength="255" name="e_title" placeholder="Title" style="margin-bottom: 10px" type="text"/>
                  </div>
                </div>
   
                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> Description<span class="red_star">*</span> <br><small><i>Description of the career</i></small></label>
                    <textarea class="input-md textinput textInput form-control summernote" id="e_description" name="e_description" required></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div> 
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="add" value="Save" class="ladda-button btn btn-primary" data-style="expand-right" id="submit_edit" />
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

  //script for Data Table of slides
  $('#news_table').DataTable({
    ordering: true,
    paging: true,
    dom: '<"html5buttons"B>lTfgitp',
    order: [[ 0, "desc" ]],
    buttons: [
        {extend: 'excel', title: 'ExampleFile'},

    ]
  });

  // script to activate the summer note
  $('.summernote').summernote();

});

// ================== ladda loader for add news ==================
loadLadda('submit_add');
// ================== ladda loader for edit header ==================
loadLadda('submit_edit');


// =========== function that load the content of the selected record for edit =====================
  function loadDataToEdit(id){

    // ajax call to edit the image
    $.ajax({
          url: "/get-careers-by-id",
          method: "POST",
          data: {
                  'id' : id
                },      
          headers: {
             'X-CSRF-Token': $('input[name=_token]').val()
          },                      
          dataType: "json",
          success: function (data) {

            // populate the data
            $("input[name^='e_career_id']").val(id);
             $("input[name^='e_title']").val(data[0].title);
             $("#e_description").summernote("code", data[0].description);
                    
              },
           error: function(jqXHR, textStatus, errorThrown) {

              console.log('Status:'+jqXHR.status);
              console.log('Text status:'+textStatus);
              console.log('Error Thrown:'+errorThrown);
          }
      });
  }

  // delete thte record 
ajaxDelete('.delete_careers', 'delete-careers', 'rec_')




</script>


@endsection