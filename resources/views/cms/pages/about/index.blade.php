
@extends('cms.layouts.app')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-4">
    <h2>About Management</h2>
    <ol class="breadcrumb active">
      <li>
        <strong><a href="">Manage the About page of you website</a></strong>
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
          <h5>About Page</h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          {!! Form::open(array('route' => 'cms_edit_about', 'id' => 'edit_about_form', 'class' => 'add-form', 'files' => true)) !!}
            <div class="form-group col-md-6"> 
              <div class="form-group col-md-12">  
                <div class="controls ">
                  <label class="control-label">Header Title<span class="red_star">*</span> <br><small><i>Update the Title of About page header</i></small></label>
                  <input class="input-md textinput textInput form-control" name="title" placeholder="Header Title" style="margin-bottom: 10px" type="text" value="@if($about) {{$about[0]->header_title}} @else @endif" required/>
                </div>
              </div>
              <div class="form-group col-md-12"> 
                <div class="controls ">
                  <label class="control-label">Header Subtitle<span class="red_star">*</span> <br><small><i>Update the Subtitle of About page header</i></small></label> 
                  <input class="input-md textinput textInput form-control" name="subtitle" placeholder="Header Subtitle" style="margin-bottom: 10px" type="text" value="@if($about)  {{$about[0]->header_subtitle}} @else @endif" required/>
                </div>
              </div>
            </div>
            <div class="form-group col-md-6">
              <div class="col-md-12">
                <?php 
                  $src='';
                  if($about)
                  {
                    if($about[0]->header_img == null)
                      $src='/cms/images/noimage.png';
                    else
                      $src= getenv('S3_URL').'/about/'.$about[0]->header_img;
                  }
                  else
                    $src='/cms/images/noimage.png';
                ?>
                <img src="{{$src}}" width="60%" style="border-radius:10px; margin-right:5px; margin-bottom: 10px;">
              </div>
              <div class="form-group col-md-12">
                <div class="controls">
                  <label class="control-label">Header Image <span class="red_star"><small><i> recommended size</i></small> (1920 x 848) px</span><br>
                    <small><i>Update the image of About page header</i></small></label>
                  <input class="input-md textinput textInput form-control" name="image" type="file"/>
                </div>
              </div>
            </div>
            <div class="form-group required">
              <label for="edit_short_description" class="font-normal">About Page Content <br><small><i>Edit the content of About Page</i></small></label>
              <div class="input-group col-md-12" >
                <textarea class="input-md textinput textInput form-control summernote" name="about">@if($about) <?php echo htmlspecialchars_decode($about[0]->content); ?> @endif</textarea>
              </div>  
            </div>
            <div class="modal-footer" style="border-top: none;">
              <input type="submit" name="update" value="Save Changes" class="ladda-button btn btn-primary" data-style="expand-right" id="submit_edit" />
            </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>

<script src="/cms/js/custom_functions.js"></script>
<script type="text/javascript">

var s3_url = <?=json_encode(getenv('S3_URL')) ?>; 

$(document).ready(function(){

  // script to activate the summer note
  $('.summernote').summernote();

});


// ================== ladda loader for edit =================
loadLadda('submit_edit');

</script>

@endsection