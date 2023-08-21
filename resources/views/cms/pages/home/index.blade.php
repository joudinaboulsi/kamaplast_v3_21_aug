
@extends('cms.layouts.app')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-4">
    <h2>Home Management</h2>
    <ol class="breadcrumb active">
      <li>
        <strong><a href="">Manage the Home page of you website</a></strong>
      </li>
    </ol>
  </div>
  <div class="col-lg-8">
    <div class="title-action">
    </div>
  </div>
</div>

<!--Display List of Slides -->
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Slides</h5>
          <div class="ibox-tools">
            <a href="#" data-toggle="modal" data-target="#add_slide" class="btn btn-primary">Add Slide</a>
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            <table id="slides_table" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Title</th>
                  <th>Subtitle</th>
                  <th>Button Name</th>
                  <th>Action Button</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($slides as $s)
                    <tr class="gradeX" id="slide_{{$s->slide_id}}">
                      <td>
                        <?php 
                            $img = getenv('S3_URL').'/slides/thumbs/'.$s->image;
                         ?>
                        <img src="{{$img}}" width="70" style="border-radius:10px; margin-right:5px; ">
                      </td>
                      <td>{{$s->title}}</td>
                      <td>{{$s->subtitle}}</td>
                      <td>{{$s->button_name}}</td>
                      <td><a href="{{$s->action}}" target="_blank">{{$s->action}}</a></td>
                      <td>
                        <button onclick="loadDataToEdit({{$s->slide_id}});" data-toggle="modal" data-target="#update_slide" type="button" class="edit_btn" title="Edit Slide"><i class="fa fa-edit fa-lg"></i></button>
                        <button type="button" id='{{$s->slide_id}}'class="edit_btn delete_slide" title="Delete Slide"><i class="fa fa-trash fa-lg"></i></button>
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

<!--Display List of Separators -->
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Separators</h5>
          <div class="ibox-tools">
            <!-- <a href="#" data-toggle="modal" data-target="#add_separator" class="btn btn-primary">Add Separator</a> -->
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            <table id="separators_table" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Title</th>
                  <th>Subtitle</th>
                  <th>Button Name</th>
                  <th>Action Button</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($separators as $se)
                    <tr class="gradeX" id="slide_{{$se->slide_id}}">
                      <td>
                        <?php 
                            $img = getenv('S3_URL').'/slides/thumbs/'.$se->image;
                         ?>
                        <img src="{{$img}}" width="70" style="border-radius:10px; margin-right:5px; ">
                      </td>
                      <td>{{$se->title}}</td>
                      <td>{{$se->subtitle}}</td>
                      <td>{{$se->button_name}}</td>
                      <td><a href="{{$se->action}}" target="_blank">{{$se->action}}</a></td>
                      <td>
                        <button onclick="loadDataToEdit({{$se->slide_id}});" data-toggle="modal" data-target="#update_slide" type="button" class="edit_btn" title="Edit Separator"><i class="fa fa-edit fa-lg"></i></button>
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

<!--Modal Add Slide -->
<div id="add_slide" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD NEW Slide</h4>
      </div>
      {!! Form::open(array('route' => 'add_slide_path', 'id' => 'add_slide_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="modal-body">   
          <div id="" class="mainbox col-md-12 col-sm-12">
            <div class="panel panel-info"> 
              <div class="panel-body"> 

                <input type="hidden" name="is_separator" value="0">

                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> Title <br><small><i>Title of the slide created</i></small></label>
                    <input class="input-md textinput textInput form-control" maxlength="255" name="title" placeholder="Slide Title" style="margin-bottom: 10px" type="text"/>
                  </div>
                </div>
                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> Subtitle <br><small><i>Subtitle of the slide created</i></small></label>
                    <input class="input-md form-control" name="subtitle" placeholder="Slide Subtitle" style="margin-bottom: 10px" type="text"/>
                  </div>
                </div>
                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> Button Name <br><small><i>Button Name of the slide created</i></small></label>
                    <input class="input-md textinput textInput form-control" name="btn_name" placeholder="Button Name" style="margin-bottom: 10px" type="text"/>
                  </div>
                </div>
                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> Action Button <br><small><i>Action Button of the slide created</i></small></label>
                    <input class="input-md textinput textInput form-control" name="action" placeholder="Action Button" style="margin-bottom: 10px" type="text"/>
                  </div>
                </div>

                <div class="form-group col-md-12">
                  <div class="controls">
                    <label class="control-label"> Image<span class="red_star">* <small><i> recommended size</i></small> (1920 x 848) px</span> <br>
                      <small><i>Upload an image to your slide</i></small></label>
                    <input class="input-md textinput textInput form-control" name="image" type="file" required/>
                  </div>
                </div>

              </div>
            </div>
          </div> 
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="add" value="Add Slide" class="ladda-button btn btn-primary" data-style="expand-right" id="submit_add_slide" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<!--Modal Add Separator -->
<div id="add_separator" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD NEW Separator</h4>
      </div>
      {!! Form::open(array('route' => 'add_slide_path', 'id' => 'add_separator_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="modal-body">   
          <div id="signupbox" class="mainbox col-md-12 col-sm-12">
            <div class="panel panel-info"> 
              <div class="panel-body"> 

                <input type="hidden" name="is_separator" value="1">

                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> Title <br><small><i>Title of the Separator created</i></small></label>
                    <input class="input-md textinput textInput form-control" maxlength="255" name="title" placeholder="Separator Title" style="margin-bottom: 10px" type="text"/>
                  </div>
                </div>
                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> Subtitle <br><small><i>Subtitle of the Separator created</i></small></label>
                    <input class="input-md textinput textInput form-control" name="subtitle" placeholder="Separator Subtitle" style="margin-bottom: 10px" type="text" required/>
                  </div>
                </div>
                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> Button Name <br><small><i>Button Name of the slide created</i></small></label>
                    <input class="input-md textinput textInput form-control" name="btn_name" placeholder="Button Name" style="margin-bottom: 10px" type="text"/>
                  </div>
                </div>
                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> Action Button <br><small><i>Action Button of the Separator created</i></small></label>
                    <input class="input-md textinput textInput form-control" name="action" placeholder="Action Button" style="margin-bottom: 10px" type="text" />
                  </div>
                </div>
                <div class="form-group col-md-12">
                  <div class="controls">
                    <label class="control-label"> Image<span class="red_star">* <small><i> recommended size</i></small> (1920 x 848) px</span> <br>
                      <small><i>Upload an image to your Separator</i></small></label>
                    <input class="input-md textinput textInput form-control" name="image" type="file" required/>
                  </div>
                </div>

              </div>
            </div>
          </div> 
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="add" value="Add Separator" class="ladda-button btn btn-primary" data-style="expand-right" id="submit_add_separator" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>


<!-- Modal Update Slide/Separator -->
<div id="update_slide" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
     {!! Form::open(array('route' => 'cms_edit_slide', 'id' => 'update_slide_form', 'class' => 'update-form', 'files' => true)) !!} 
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">EDIT</h4>
      </div>
      <div class="modal-body">   
        <div id="signupbox" class="mainbox col-md-12 col-sm-12">
          <div class="panel panel-info">
            <div class="panel-body"> 

              <input type="hidden" name="e_id">

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label class="control-label"> Title <br><small><i>Title of slide/separator</i></small></label>
                  <input class="input-md textinput textInput form-control" name="e_title" placeholder="Title" style="margin-bottom: 10px" type="text"/>
                </div>
              </div>

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label class="control-label"> Subtitle <br><small><i>Subtitle of slide/separator</i></small></label>
                  <input class="input-md textinput textInput form-control" name="e_subtitle" placeholder="Subtitle" style="margin-bottom: 10px" type="text"/>
                </div>
              </div>

              <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> Button Name <br><small><i>Button Name of the slide created</i></small></label>
                    <input class="input-md textinput textInput form-control" name="e_btn_name" placeholder="Button Name" style="margin-bottom: 10px" type="text"/>
                  </div>
                </div>

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label class="control-label"> Action Button <br><small><i>Action Button of the Slide/Separator created</i></small></label>
                  <input class="input-md textinput textInput form-control" name="e_action" placeholder="Action Button" style="margin-bottom: 10px" type="text"/>
                </div>
              </div>

              <div class="form-group col-md-12">
                <div class="controls">
                  <label for="description" class="control-label"> Image<span class="red_star">* <small><i> recommended size</i></small> (1920 x 848) px</span> <br>
                    <small><i>Upload an image to your Slide/Separator</i></small></label>
                  <input class="input-md textinput textInput form-control" name="e_image" type="file"/>
                  <img class="display_slide_img" src="" width="200">
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

    //script for Data Table of slides
    $('#slides_table').DataTable({
        ordering: true,
        paging: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            {extend: 'excel', title: 'ExampleFile'},

        ]
    });

    //script for Data Table of separators
    $('#separators_table').DataTable({
        ordering: true,
        paging: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            {extend: 'excel', title: 'ExampleFile'},

        ]
    });

});

// ================== ladda loader for add slide ==================
loadLadda('submit_add_slide');
// ================== ladda loader for add separator ==================
loadLadda('submit_add_separator');
// ================== ladda loader for edit =================
loadLadda('submit_edit');

</script>

<!-- ============== Script allowing data to be loaded in Edit PopUp  =============== -->
<script src="/cms/js/home/ajax-load-data-to-edit-slide.js"></script>
<!-- ============== Delete Slide/Separator  =============== -->
<script src="/cms/js/home/delete-slide.js"></script>

@endsection