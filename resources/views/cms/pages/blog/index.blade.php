
@extends('cms.layouts.app')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-4">
    <h2>Media Management</h2>
    <ol class="breadcrumb active">
      <li>
        <strong><a href="">Manage the Media page of you website</a></strong>
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
          <h5>News</h5>
          <div class="ibox-tools">
            <a href="#" data-toggle="modal" data-target="#add_news" class="btn btn-primary">Add News</a>
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
                  <th>Title</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($news as $n)
                  <tr class="gradeX" id="news_{{$n->news_id}}">
                    <td>
                      <?php 
                          $img = getenv('S3_URL').'/blog/thumbs/'.$n->image;
                       ?>
                      <img src="{{$img}}" width="50" style="border-radius:10px; margin-right:5px; ">
                      <a href="{{ route('cms_news_details_path', $n->news_id) }}">{{$n->title}}</a>
                    </td>
                    <td>{{$n->date}}</td>
                    <td>
                      <button type="button" id="{{$n->news_id}}" class="edit_btn delete_news" title="Delete News"><i class="fa fa-trash fa-lg"></i></button>
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

<!--Modal Add News -->
<div id="add_news" class="modal fade" role="dialog" style="overflow: auto;">
  <div class="modal-lg modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD NEWS</h4>
      </div>
      {!! Form::open(array('route' => 'add_news_path', 'id' => 'add_news_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="modal-body">   
          <div id="signupbox" class="mainbox col-md-12 col-sm-12">
            <div class="panel panel-info"> 
              <div class="panel-body"> 

                <div class="form-group col-md-7">  
                  <div class="controls ">
                    <label class="control-label"> Title<span class="red_star">*</span> <br><small><i>Title of the News</i></small></label>
                    <input class="input-md textinput textInput form-control" maxlength="255" name="title" placeholder="Title" style="margin-bottom: 10px" type="text"/>
                  </div>
                </div>
                <div class="form-group col-md-5"> 
                  <div class="controls ">
                    <label class="control-label"> Date<span class="red_star">*</span> <br><small><i>Date of the News</i></small></label> 
                    <div class="input-group date" >
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="date" class="form-control" autocomplete="off" placeholder="News Date" required>
                    </div>
                  </div>
                </div>
                <div class="form-group col-md-12">  
                  <div class="controls ">
                    <label class="control-label"> Content<span class="red_star">*</span> <br><small><i>Content of the News</i></small></label>
                    <textarea class="input-md textinput textInput form-control summernote" name="content" required></textarea>
                  </div>
                </div>
                <div class="form-group col-md-7">  
                  <div class="controls ">
                    <label class="control-label"> Link <br><small><i>Link of the News</i></small></label>
                    <input class="input-md textinput textInput form-control" name="link" placeholder="Link" style="margin-bottom: 10px" type="text" />
                  </div>
                </div>
                <div class="form-group col-md-5">
                  <div class="controls">
                    <label class="control-label"> Image<span class="red_star">*</span> <br><small><i>Upload an image to your News</i></small></label>
                    <input class="input-md textinput textInput form-control" name="image" type="file" required/>
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
                    <label class="control-label"> SEO Keywords <br><small><i>Keywords of Page SEO</i></small></label>
                    <input class="input-md textinput textInput form-control" name="seo_keywords" placeholder="Keywords" style="margin-bottom: 10px" type="text"/>
                  </div>
                </div>

                <div class="form-group col-md-12">
                  <div class="controls">
                    <label class="control-label"> OG Image<span class="red_star"> <small><i> recommended size</i></small> (1200 x 1200) px</span> <br>
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
          <input type="submit" name="add" value="Add News" class="ladda-button btn btn-primary" data-style="expand-right" id="submit_add" />
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
    buttons: [
        {extend: 'excel', title: 'ExampleFile'},

    ]
  });

  $('.input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: false,
    format: 'yyyy-mm-dd',
  });

  // script to activate the summer note
  $('.summernote').summernote();

});

// ================== ladda loader for add news ==================
loadLadda('submit_add');
// ================== ladda loader for edit header ==================
loadLadda('submit_edit_header');

</script>

<!-- ============== Delete news  =============== -->
<script src="/cms/js/blog/delete-news.js"></script>

@endsection