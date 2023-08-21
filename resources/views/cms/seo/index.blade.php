
@extends('cms.layouts.app')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-4">
    <h2>SEO Management</h2>
    <ol class="breadcrumb active">
      <li>
        <strong><a href="">Manage the pages SEO of you website</a></strong>
      </li>
    </ol>
  </div>
  <div class="col-lg-8">
    <div class="title-action">
    </div>
  </div>
</div>

<!-- Display og twitter -->
<div class="wrapper wrapper-content animated fadeInRight" style="padding-bottom: 0px;">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>OG Twitter</h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-down"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content" style="display: none;">
          {!! Form::open(array('route' => 'cms_edit_og_twitter', 'id' => 'edit_og_twitter', 'class' => 'update-form')) !!} 
          <div class="row">  
            <div class="controls col-lg-6">
              <label class="control-label">OG Twitter</label>
              <div class="input-group m-b"><span class="input-group-addon">@</span>
                <input class="input-md textinput textInput form-control" name="og_twitter" placeholder="OG Twitter" type="text" value="{{$og_twitter}}"/>
              </div>
            </div>
          </div>
          <input type="submit" name="update_og" value="Save" class="ladda-button btn btn-primary" data-style="expand-right" id="submit_edit_og_twitter" />
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Display pages SEO -->
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>SEO</h5>
          <div class="ibox-tools">
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
                  <th>ID</th>
                  <th>Page Name</th>
                  <th>SEO Title</th>
                  <th>SEO Description</th>
                  <th>SEO Keywords</th>
                  <th>OG Image</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($seo as $s)
                    <tr class="gradeX" id="slide_{{$s->seo_page_id}}">
                      <td>{{$s->seo_page_id}}</td>
                      <td>{{$s->admin_pagename}}</td>
                      <td>{{$s->seo_title}}</td>
                      <td>{{$s->seo_description}}</td>
                      <td>{{$s->seo_keywords}}</td>
                      <td>
                        <?php
                          if($s->og_image) 
                            $img = getenv('S3_URL').'/seo/thumbs/'.$s->og_image;
                         ?>
                        @if($s->og_image)<img src="{{$img}}" width="70" style="border-radius:10px; margin-right:5px; ">@endif
                      </td>
                      <td>
                        <button onclick="loadDataToEdit({{$s->seo_page_id}});" data-toggle="modal" data-target="#update_slide" type="button" class="edit_btn" title="Edit Slide"><i class="fa fa-edit fa-lg"></i></button>
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

<!-- MODALS -->

<!-- Modal Update SEO -->
<div id="update_slide" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
     {!! Form::open(array('route' => 'cms_edit_page_seo', 'id' => 'update_slide_form', 'class' => 'update-form', 'files' => true)) !!} 
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">EDIT</h4>
      </div>
      <div class="modal-body">   
        <div id="signupbox" class="mainbox col-md-12 col-sm-12">
          <div class="panel panel-info">
            <div class="panel-body"> 

              <input type="hidden" name="seo_page_id">

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label class="control-label"> SEO Title <br><small><i>Title of Page SEO</i></small></label>
                  <input class="input-md textinput textInput form-control" name="title" placeholder="Title" style="margin-bottom: 10px" type="text"/>
                </div>
              </div>

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label class="control-label"> SEO Description <br><small><i>Description of Page SEO</i></small></label>
                  <input class="input-md textinput textInput form-control" name="description" placeholder="Description" style="margin-bottom: 10px" type="text"/>
                </div>
              </div>

              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label class="control-label"> SEO Keywords <br><small><i>Separate Keywords with commas (,)</i></small></label>
                  <input class="input-md textinput textInput form-control" name="keywords" placeholder="Keywords" style="margin-bottom: 10px" type="text"/>
                </div>
              </div>

              <div class="form-group col-md-12">
                <div class="controls">
                  <label class="control-label"> OG Image<span class="red_star"> <small><i> recommended size</i></small> (1200 x 1200) px</span> <br>
                    <small><i>Update OG image</i></small>
                  </label>
                  <input class="input-md textinput textInput form-control" name="og_image" type="file"/>
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

    //script for Data Table of slides
    $('#seo_table').DataTable({
        ordering: true,
        paging: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            {extend: 'excel', title: 'ExampleFile'},

        ]
    });

});


// ================== ladda loader for edit =================
loadLadda('submit_edit');

loadLadda('submit_edit_og_twitter');

</script>

<!-- ============== Script allowing data to be loaded in Edit PopUp  =============== -->
<script src="/cms/js/seo/ajax-load-data-to-edit-seo.js"></script>

@endsection