@extends('cms.layouts.app')

@section('content')

{!! Form::open(array('route' => 'cms_edit_news', 'id' => 'edit_news_form', 'class' => 'add-form', 'files' => true)) !!}

<!-- GENERAL INFORMATION SECTION -->
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-md-3">
    <h2> News #{{$news_details[0]->news_id}} </h2>
    <ol class="breadcrumb ">
      <li>
        <strong><a href="{{ route('cms_blog') }}">Media</a></strong>
      </li>
      <li>
         {{$news_details[0]->title}}
      </li>
    </ol>
    <hr>
  </div>
  <div class="col-md-9 margin-top">
    <div style="text-align:right; margin-top:10px;">
         <input type="submit" value="Save" class="ladda-button btn btn-primary btn-md save_btn" id="submit_edit"/>
    </div>
  </div>
  <div class="col-md-12">

    <input type="hidden" name="news_id" value="{{$news_details[0]->news_id}}">

    <div class="form-group col-md-6"> 
      <div class="form-group col-md-12">  
        <div class="controls ">
          <label class="control-label"> Title<span class="red_star">*</span> <br><small><i>Update the Title of the News</i></small></label>
          <input class="input-md textinput textInput form-control" name="title" placeholder="Title" style="margin-bottom: 10px" type="text" value="{{$news_details[0]->title}}" required/>
        </div>
      </div>
      <div class="form-group col-md-12"> 
        <div class="controls ">
          <label class="control-label"> Date<span class="red_star">*</span> <br><small><i>Update the Date of the News</i></small></label> 
          <div class="input-group date" >
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="date" class="form-control" autocomplete="off" placeholder="News Date" value="{{$news_details[0]->date}}" required>
          </div>
        </div>
      </div>
      <div class="form-group col-md-12">  
        <div class="controls ">
          <label class="control-label"> Link <br><small><i>Update the Link of the News</i></small></label>
          <input class="input-md textinput textInput form-control" name="link" placeholder="Link" style="margin-bottom: 10px" type="text" value="{{$news_details[0]->link}}"/>
        </div>
      </div>
    </div>
    <div class="form-group col-md-6">
      <div class="col-md-12">
        <img src="{{getenv('S3_URL').'/blog/'.$news_details[0]->image}}" width="60%" style="border-radius:10px; margin-right:5px; margin-bottom: 10px;">
      </div>
      <div class="form-group col-md-12">
        <div class="controls">
          <label class="control-label"> Image <br><small><i>Update the image of your News</i></small></label>
          <input class="input-md textinput textInput form-control" name="image" type="file"/>
        </div>
      </div>
    </div>
    <div class="col-md-12">  
      <div class="controls ">
        <label class="control-label"> Content<span class="red_star">*</span> <br><small><i>Update Content of the News</i></small></label>
        <textarea class="input-md textinput textInput form-control summernote" name="content" required><?php echo htmlspecialchars_decode($news_details[0]->content); ?></textarea>
      </div>
    </div>

    <!-- Blog SEO SECTION -->
    <div class="col-lg-12">
      <div class="form-group col-md-12"><h2>SEO</h2></div>

      <div class="form-group col-md-12">  
        <div class="controls ">
          <label class="control-label"> SEO Title <br><small><i>Title of Page SEO</i></small></label>
          <input class="input-md textinput textInput form-control" name="seo_title" placeholder="Title" style="margin-bottom: 10px" type="text" value="{{$news_details[0]->seo_title}}"/>
        </div>
      </div>

      <div class="form-group col-md-12">  
        <div class="controls ">
          <label class="control-label"> SEO Description <br><small><i>Description of Page SEO</i></small></label>
          <input class="input-md textinput textInput form-control" name="seo_description" placeholder="Description" style="margin-bottom: 10px" type="text" value="{{$news_details[0]->seo_description}}"/>
        </div>
      </div>

      <div class="form-group col-md-12">  
        <div class="controls ">
          <label class="control-label"> SEO Keywords <br><small><i>Separate Keywords with commas (,)</i></small></label>
          <input class="input-md textinput textInput form-control" name="seo_keywords" placeholder="Keywords" style="margin-bottom: 10px" type="text" value="{{$news_details[0]->seo_keywords}}"/>
        </div>
      </div>

      <div class="form-group col-md-12">
        <div class="controls">
          <label class="control-label"> OG Image<span class="red_star"> <small><i> recommended size</i></small> (1200 x 630) px</span> <br>
            <small><i>Update OG image</i></small>
          </label>
          <input class="input-md textinput textInput form-control" name="og_image" type="file"/>
          <?php
            if($news_details[0]->og_image) 
              $img = getenv('S3_URL').'/seo/thumbs/'.$news_details[0]->og_image;
           ?>
          @if($news_details[0]->og_image)<img src="{{$img}}" width="70" style="border-radius:10px; margin-right:5px; ">@endif
        </div>
      </div>
    </div>
    
    <!-- Blog Tags SECTION -->
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><i class="fa fa-tags fa-lg">&nbsp</i></span> TAGS <small>Manage the tags related to the news</small></h5>
          <div class="ibox-tools">
            <a data-toggle="modal" data-target="#add_tag2" title="Add Tag">
              <i class="fa fa-plus-square fa-lg" style="color: #1bb394;"></i>
            </a>
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <table id="" class="table table-striped table-bordered table-hover dataTables-example" >
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($newsTags as $tag)
              <tr class="gradeX" id="news_tag_{{$tag->tag_id}}">
                <td>{{$tag->tag_id}}</td>
                <td>{{$tag->tag_name}}</td>
                <td class="center">
                  <button type="button" id='{{ $tag->tag_id }}' class="edit_btn delete_news_tag" title="Unlink Tag"><i class="fa fa-trash fa-lg"></i></button>
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

{!! Form::close() !!}


<!-- ================== MODALS ================== -->

<!--Modal Add Tag -->
<div id="add_tag2" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add a Tag <small>Add a new tags to the user</small></h4>
      </div>
      {!! Form::open(array('route' => 'add_news_tag_path', 'id' => 'add_news_tag_form', 'class' => 'add-form')) !!}
        <div class="modal-body">   
          <div class="panel-info"> 
            <div class="panel-body"> 

              <input type="hidden" name="t_news_id" value="{{$news_details[0]->news_id}}">

              <div class="form-group required">
                <label class="control-label col-md-12 requiredField">TAG <br><small><i>Choose from the list of tags</i></small></label>
                <div class="controls col-md-12">
                  <select class="form-control" name="tag">
                    @foreach($tagsList as $tl)
                    <option value="{{$tl->tag_id}}">{{$tl->name}}</option>
                    @endforeach
                  </select>  
                </div>   
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
          <input type="submit" name="submit_add_tag" value="Add Tag" class="ladda-button btn btn btn-primary" data-style="expand-right" id="submit_add_tag"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<script src="/js/custom_functions.js"></script>
<script type="text/javascript">

  // ================== Converting php data to a json =================
  var news_id = <?=json_encode($news_details[0]->news_id);?>

  $(document).ready(function(){

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

  // ================== ladda loader for edit news =================
  loadLadda('submit_edit');

  // ================== ladda loader for add news tag =================
  loadLadda('submit_add_tag');

</script>

<!-- Unlink News Tag -->
<script src="cms/js/blog/unlink-news-tag.js"></script>

@endsection