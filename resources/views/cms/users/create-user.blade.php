@extends('cms.layouts.app')

@section('content')

<style type="text/css">
    .chosen-container-multi {
    width: 100% !important;
}
</style>

{!! Form::open(array('route' => 'add_user_path', 'id' => 'add_form', 'class' => 'add-form')) !!}

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-md-3">
    <h2> Users </h2>
    <ol class="breadcrumb ">
      <li>
        <strong><a href="{{ route('cms_users') }}">Users</a></strong>
      </li>
      <li>
         Add Users
      </li>
    </ol>
    <hr>
  </div>
  <div class="col-md-9 margin-top">
    <div style="text-align:right; margin-top:10px;">
      <input type="submit" name="add" value="Save" class="ladda-button btn btn-primary save_btn" id="submit_add"/>
    </div>
  </div>

  <div class="col-md-12">
    <div class="form-group col-md-6">
      <label for="fullname" class="control-label requiredField"> Full Name<span class="red_star">*</span> <br><small><i>Name of the User</i></small></label>
      <input class="input-md textinput textInput form-control" id="fullname" maxlength="255" name="fullname" placeholder="Full Name" type="text" required/>
    </div>
     <div class="form-group col-md-6">
      <label for="email" class="control-label requiredField"> Email<span class="red_star">*</span> <br><small><i>Email of the user</i></small></label>
      <input class="input-md textinput textInput form-control" id="email" name="email" placeholder="email@neocomz.com" type="email" required/>
    </div>

    <div class="form-group col-md-6">
      <label for="phone" class="control-label requiredField"> Phone Number<span class="red_star">*</span> <br><small><i>Personal phone number of the user</i></small></label>
      <input class="input-md textinput textInput form-control" id="phone" maxlength="255" name="phone" placeholder="Phone number" type="phone" required/>
    </div>

    <div class="form-group required col-md-6">
      <label for="manage_stock" class="font-normal"> Subscribe to Newsletters <br><small><i>Receive emails from website</i></small></label>
      <div class="switch">
        <div class="onoffswitch">
          <input type="checkbox" class="onoffswitch-checkbox" name="newsletters" id="newsletters" value="1">
          <label  class="onoffswitch-label" for="newsletters">
            <span class="onoffswitch-inner"></span>
            <span class="onoffswitch-switch"></span>
          </label>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="wrapper animated fadeInRight margin-top">
  <div class="row">
    <!-- ADDRESS SECTION -->
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><img src="/cms/svg/information.svg" width="20"></span> ADDRESS <small>Main address of the user</small></h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content profile-content">
          <div class="row">
            <div class="form-group col-md-6">
              <label for="address_fullname" class="control-label requiredField"> Full name<span class="red_star">*</span> <br><small><i>Name of the User's address</i></small></label>
              <input class="input-md textinput textInput form-control" id="address_fullname" maxlength="255" name="address_fullname" placeholder="Full Name" type="text" required/>
            </div>
            <div class="form-group col-md-6">
              <label for="company" class="control-label requiredField"> Company<span class="red_star">*</span> <br><small><i>Name of the Company's address</i></small></label>
              <input class="input-md textinput textInput form-control" id="company" maxlength="255" name="company" placeholder="Company Name" type="text" required/>
            </div>
            <div class="form-group col-md-12">
              <label for="address" class="control-label requiredField"> Address<span class="red_star">*</span> <br><small><i>Address of reception</i></small></label>
              <input class="input-md textinput textInput form-control" id="address" maxlength="255" name="address" placeholder="Address" type="text" required/>
            </div>
            <div class="form-group col-md-12">
              <label for="apartment" class="control-label requiredField"> Apartment, Suite, etc.<span class="red_star">*</span> <br><small><i>Apartment number or code</i></small></label>
              <input class="input-md textinput textInput form-control" id="apartment" maxlength="255" name="apartment" placeholder="Apartment" type="text" required/>
            </div>
            <div class="form-group col-md-4">
              <label for="country" class="control-label requiredField"> Country<span class="red_star">*</span> <br><small><i>Country of the user</i></small></label>
              <input class="input-md textinput textInput form-control" id="country" maxlength="255" name="country" placeholder="Country" type="text" required/>
            </div>
            <div class="form-group col-md-4">
              <label for="postal_code" class="control-label requiredField"> Postal Code<span class="red_star">*</span> <br><small><i>Postal code of the user</i></small></label>
              <input class="input-md textinput textInput form-control" id="postal_code" maxlength="255" name="postal_code" placeholder="Postal code" type="text" required/>
            </div>
            <div class="form-group col-md-4">
              <label for="city" class="control-label requiredField"> City<span class="red_star">*</span> <br><small><i>City of the user</i></small></label>
              <input class="input-md textinput textInput form-control" id="city" maxlength="255" name="city" placeholder="City" type="text" required/>
            </div>
            <div class="form-group col-md-12">
              <label for="address_phone" class="control-label requiredField"> Phone<span class="red_star">*</span> <br><small><i>Phone of the user</i></small></label>
              <input class="input-md textinput textInput form-control" id="address_phone" maxlength="255" name="address_phone" placeholder="Phone" type="text" required/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER SAVING -->
<div class="wrapper animated fadeInRight padding-bottom margin-top">
  <div class="row">
    <div style="text-align:right;margin-top: 15px; margin-right: 15px;">
      <input type="submit" name="add" value="Save" class="ladda-button btn btn-primary save_btn" id="submit_add1"/>
    </div>
  </div>
</div>

{!! Form::close() !!}


<script src="/cms/js/custom_functions.js"></script>
<script type="text/javascript">

$(document).ready(function(){

 // ================== ladda loader for edit Name and Short Description =================
    loadLadda('submit_add');
    loadLadda('submit_add1');

});


</script>


@endsection