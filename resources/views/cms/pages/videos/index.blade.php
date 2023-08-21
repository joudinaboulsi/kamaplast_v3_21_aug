
@extends('cms.layouts.app')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-4">
    <h2>Tutorials Management</h2>
    <ol class="breadcrumb active">
      <li>
        <strong><a href="">Manage Tutorials Section of you website</a></strong>
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
          <h5>Tutorials</h5>
          <div class="ibox-tools">
              <a href="#" data-toggle="modal" data-target="#add_video" class="btn btn-primary">Add Tutorial</a>
              <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
              </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            <table id="videos_table" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
              <thead>
                <tr>
                  <th>#</th>
                  <th>Tutorial URL</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($videos as $v)
                    <tr class="gradeX" id="video_{{$v->id}}">
                      <td>{{$v->id}}</td>
                      <td><img src="https://img.youtube.com/vi/{{$v->video_id}}/hqdefault.jpg" width="60" /><a href="{{$v->url}}" target="_blank"> {{$v->url}}</a></td>
                      <td>
                        <button type="button" id='{{$v->id}}'class="edit_btn delete_video" title="Delete Tutorial"><i class="fa fa-trash fa-lg"></i></button>
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

<!--Modal Add Video -->
<div id="add_video" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD NEW VIDEO</h4>
      </div>
      {!! Form::open(array('route' => 'add_video_path', 'id' => 'add_video_form', 'class' => 'add-form')) !!}
        <div class="modal-body">   
          <div id="signupbox" class="mainbox col-md-12 col-sm-12">
            <div class="panel panel-info"> 
              <div class="panel-body"> 

                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> Video URL<span class="red_star">*</span> <br><small><i>URL of the video added</i></small></label>
                    <input class="input-md textinput textInput form-control" name="video_url" placeholder="Video URL" type="text" required/>
                  </div>
                </div>

              </div>
            </div>
          </div> 
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="add" value="Add Video" class="ladda-button btn btn-primary" data-style="expand-right" id="submit_add_video" />
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
    $('#videos_table').DataTable({
        ordering: true,
        paging: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            {extend: 'excel', title: 'ExampleFile'},

        ]
    });

});

// ================== ladda loader for add slide ==================
loadLadda('submit_add_video');

</script>

<!-- ============== Delete Slide/Separator  =============== -->
<script src="/cms/js/videos/delete-video.js"></script>

@endsection