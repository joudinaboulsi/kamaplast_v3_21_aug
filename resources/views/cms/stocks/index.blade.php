
@extends('cms.layouts.app')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-4">
    <h2>Stock Integration</h2>
    <ol class="breadcrumb active">
      <li>
        <strong><a href="">Update your stocks from excel file</a></strong>
      </li>
    </ol>
  </div>
  <div class="col-lg-8">
    <div class="title-action">
      <button data-toggle="modal" data-target="#add_excel" class="btn btn-primary btn-sm">Add excel</button>     
    </div>
  </div>
</div>

<br/>

@if(isset($response) && $response['status'] == 'error')

<div class="alert alert-danger"> {{$response['message']}} </div>

@elseif(isset($response) && $response['status'] == 'success')

<div class="alert alert-success"> {{$response['message']}} </div>

@endif


<!-- upload excel pop up modal -->
<div class="modal inmodal fade" id="add_excel" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xs">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Add Excel</h4>
        <small class="font-bold">Upload your stock in order to update their quantity in the system</small>
      </div>

       {!! Form::open(array('route' => 'cms_stock_integration_update', 'class' => 'form-horizontal', 'files' => true)) !!}
        <div class="modal-body">

            <div class="form-group">
                 <div class="col-sm-12">
                    <label class="control-label"> Upload file <small><i>(Only .csv format allowed)</i></small></label>    
                </div>                                
                <div class="col-sm-12">
                    <input id="excel" name="excel" type="file" class="form-control" placeholder="Enter the excel">
                </div>
                @if ($errors->has('excel'))
                    <span class="help-block">
                    <strong>{{ $errors->first('csv_file') }}</strong>
                </span>
                @endif
            </div>

        </div>    
        
        <div class="modal-footer">
            <button type="button" id="close_popup_add" class="btn btn-white" data-dismiss="modal">Close</button>
            <button type="submit" id="submit_validate" class="ladda-button btn btn-primary" data-style="expand-right">Validate</button>
        </div>

       {!! Form::close() !!}
    </div>
  </div>
</div>




<script src="/cms/js/custom_functions.js"></script>
<script type="text/javascript">


// ================== ladda loader for edit =================
loadLadda('submit_validate');

</script>


@endsection