@extends('cms.layouts.app')

@section('content')

{!! Form::open(array('route' => 'edit_user_info_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}

<style type="text/css">
    .chosen-container-multi {
    width: 100% !important;
}
</style>

<!-- GENERAL INFORMATION SECTION -->
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-md-3">
    <h2> Users </h2>
    <ol class="breadcrumb ">
      <li>
        <strong><a href="{{ route('cms_users') }}">Users</a></strong>
      </li>
      <li>
         {{$user_details[0]->name}}
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

    <input type="hidden" name="user_id" value="{{$user_details[0]->id}}">

    <div class="form-group col-md-6">
      <label for="fullname" class="control-label requiredField"> FULL NAME<span class="red_star">*</span> <br><small><i>Name of the User</i></small></label>
      <input class="input-md textinput textInput form-control" id="fullname" maxlength="255" name="fullname" placeholder="Full Name" type="text" value="{{$user_details[0]->name}}" required/>
    </div>
    <div class="form-group col-md-6">
      <label for="email" class="control-label requiredField"> EMAIL<span class="red_star">*</span> <br><small><i>Email of the user</i></small></label>
      <input class="input-md textinput textInput form-control" id="email" name="email" placeholder="email@neocomz.com" type="email" value="{{$user_details[0]->email}}" required/>
    </div>
    <div class="form-group col-md-6">
      <label for="phone" class="control-label requiredField"> PHONE NUMBER<span class="red_star">*</span> <br><small><i>Personal phone number of the user</i></small></label>
      <input class="input-md textinput textInput form-control" id="phone" maxlength="255" name="phone" placeholder="Phone number" type="phone" value="{{$user_details[0]->phone}}" required/>
    </div>
    <div class="form-group required col-md-6">
      <label for="manage_stock" class="font-normal">SUBSCRIBE TO NEWSLETTERS <br><small><i>Receive emails from website</i></small></label>
      <div><input id="newsletters" class='big_radio' type="checkbox" value="1" name="newsletters" @if($user_details[0]->has_newsletters) checked @endif></div>
    </div>
  </div>
</div>

<div class="wrapper animated fadeInRight margin-top">
  <div class="row">
    <!-- ADDRESSES SECTION -->
    <div class="col-md-8">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><img src="/cms/svg/information.svg" width="20"></span> ADDRESS <small>Main address of the user</small></h5>
          <div class="ibox-tools">
            <a data-toggle="modal" data-target="#add_address" title="Add Address">
              <i class="fa fa-plus-square fa-lg" style="color: #1bb394;"></i>
            </a>
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            <table id="" class="table table-striped table-bordered table-hover dataTables-example">
              <thead>
                <tr>
                  <th>ADDRESS</th>
                  <th>CITY</th>
                  <th>COUNTRY</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($userAddresses as $addr)
                <tr class="gradeX" id="addr_rec_{{$addr->address_id}}">
                  <td><a onclick="getAddrDetails({{$addr->address_id}})" data-toggle="modal" data-target="#view_addr" title="View Address">{{$addr->address}}</a></td>
                  <td>{{$addr->city}}</td>
                  <td>{{$addr->country_name}}</td>
                  <td class="center">
                     <button onclick="getAddrtoEdit({{$addr->address_id}})" data-toggle="modal" data-target="#edit_addr" type="button" class="edit_btn" title="Edit Address"><i class="fa fa-edit fa-lg"></i></button>
                    <button type="button" id="{{$addr->address_id}}" class="edit_btn delete_user_address" title="Delete this address"><i class="fa fa-trash fa-lg"></i></button>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- USER TAGS SECTION -->
    <div class="col-lg-4">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><i class="fa fa-tags fa-lg">&nbsp</i></span> TAGS <small>Manage the tags related to a product</small></h5>
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
              @foreach($userTags as $tag)
              <tr class="gradeX" id="user_tag_{{$tag->tag_id}}">
                <td>{{$tag->tag_id}}</td>
                <td>{{$tag->tag_name}}</td>
                <td class="center">
                  <button type="button" id='{{ $tag->tag_id }}' class="edit_btn delete_user_tag" title="Unlink Tag"><i class="fa fa-trash fa-lg"></i></button>
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

<div class="wrapper animated fadeInRight margin-top">
  <div class="row">
   <!-- ORDERS OF USER SECTION -->
   <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><img src="/cms/svg/information.svg" width="20"></span> ORDERS <small>List of orders executed by the user</small></h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            <table id="orders_table" class="table table-striped table-bordered table-hover dataTables-example" >
              <thead>
                <tr>
                  <th>Order</th>
                  <th>Date</th>
                  <th>Payment Status</th>
                  <th>Shipping Status</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach($ordersList as $o)
                  <tr class="gradeX" id="">
                    <td><a href="{{route('cms_order_details', $o->order_id)}}">#{{$o->order_id}}</a></td>
                    <td><?php echo date("d M Y - h:ia", strtotime($o->created_at)); ?></td>
                    <td><span class="label label_color b_radius" style="background-color: {{$o->payment_status_color}}">{{$o->order_status}}</span></td>
                    <td><span class="label label_color b_radius" style="background-color: {{$o->shipping_status_color}}">{{$o->delivery_status}}</span></td>
                    <td>$ {{$o->total}}</td>
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

<div class="wrapper animated fadeInRight margin-top">
  <div class="row">
    <!-- NOTES SECTION -->
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><span><i class="fa fa-tags fa-lg">&nbsp</i></span> NOTES <small>Manage the notes related to a user</small></h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
           <div class="form-group col-md-12">
              <label for="notes" class="control-label requiredField"> NOTES<span class="red_star">*</span> <br><small><i>Notes about the user</i></small></label>
              <textarea class="input-md textinput textInput form-control" id="notes" maxlength="255" name="notes" placeholder="Notes" type="text" rows="5">{{$user_details[0]->notes}}</textarea>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="wrapper animated fadeInRight padding-bottom margin-top">
  <div class="row">
    <div style="text-align:right;margin-top: 15px; margin-right: 15px;">
      <input type="submit" value="Save" class="ladda-button btn btn-primary save_btn" id="submit_edit1"/>
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
      {!! Form::open(array('route' => 'add_user_tag_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="modal-body">   
          <div class="panel-info"> 
            <div class="panel-body"> 

              <input type="hidden" name="edit_user_id" id="edit_user_id_tag" value="{{$user_details[0]->id}}">

              <div class="form-group required">
                <label for="tag" class="control-label col-md-12 requiredField">TAG <br><small><i>Choose from the list of tags</i></small></label>
                <div class="controls col-md-12">
                  <select class="form-control" id="tag" name="tag">
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
<!--Modal View Address -->
<div id="view_addr" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Address Details</h4>
      </div>
      <div class="wrapper margin-top">
        <div class="row">
          <!-- ADDRESS SECTION -->
          <div class="col-md-12">
            <div class="profile-content">
              <div class="row">

                <input type="hidden" name="view_address_id" id="view_address_id">

                <div class="form-group col-md-6">
                    <label for="address_fullname" class="control-label requiredField"> FULL NAME <br><small><i>Name of the User's address</i></small></label>
                    <input class="input-md textinput textInput form-control" id="address_fullname_view" maxlength="255" placeholder="Full Name" type="text" disabled required/>
                </div>
                <div class="form-group col-md-6">
                    <label for="company" class="control-label requiredField"> COMPANY <br><small><i>Name of the Company's address</i></small></label>
                    <input class="input-md textinput textInput form-control" id="company_view" maxlength="255"  placeholder="Company Name" type="text" disabled required/>
                </div>
                <div class="form-group col-md-12">
                    <label for="address" class="control-label requiredField"> ADDRESS <br><small><i>Address of reception</i></small></label>
                    <input class="input-md textinput textInput form-control" id="address_view" maxlength="255"  placeholder="Address" type="text" disabled required/>
                </div>
                <div class="form-group col-md-12">
                    <label for="apartment" class="control-label requiredField"> APARTMENT, SUITE, ETC. <br><small><i>Apartment number or code</i></small></label>
                    <input class="input-md textinput textInput form-control" id="apartment_view" maxlength="255" placeholder="Apartment" type="text" disabled required/>
                </div>
                <div class="form-group col-md-4">
                    <label for="country" class="control-label requiredField"> COUNTRY <br><small><i>Country of the user</i></small></label>
                    <input class="input-md textinput textInput form-control" id="country_view" maxlength="255" placeholder="Country" type="text" disabled required/>
                </div>
                <div class="form-group col-md-4">
                    <label for="postal_code" class="control-label requiredField"> POSTAL CODE <br><small><i>Postal code of the user</i></small></label>
                    <input class="input-md textinput textInput form-control" id="postal_code_view" maxlength="255"  placeholder="Postal code" type="text" disabled required/>
                </div>
                <div class="form-group col-md-4">
                    <label for="city" class="control-label requiredField"> CITY <br><small><i>City of the user</i></small></label>
                    <input class="input-md textinput textInput form-control" id="city_view" maxlength="255"  placeholder="City" type="text" disabled required/>
                </div>
                <div class="form-group col-md-12">
                    <label for="address_phone" class="control-label requiredField"> PHONE <br><small><i>Phone of the user</i></small></label>
                    <input class="input-md textinput textInput form-control" id="address_phone_view" maxlength="255"  placeholder="Phone" type="text" disabled required/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="border-top: none;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div> 
  </div>
</div>
<!--Modal add new Address -->
<div id="add_address" class="modal fade" role="dialog">
  <div class="modal-lg modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Address</h4>
      </div>
        {!! Form::open(array('route' => 'add_address_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
          <div class="wrapper margin-top">
            <div class="row">
              <!-- ADDRESS SECTION -->
              <div class="col-md-12">
                <div class="profile-content">
                  <div class="row">
                   
                    <input type="hidden" name="user_id" value="{{$user_details[0]->id}}">

                    <div class="form-group col-md-6">
                        <label for="address_fullname" class="control-label requiredField"> FULL NAME<span class="red_star">*</span> <br><small><i>Name of the User's address</i></small></label>
                        <input class="input-md textinput textInput form-control" maxlength="255" name="address_fullname" placeholder="Full Name" type="text" required/>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="company" class="control-label requiredField"> COMPANY<span class="red_star">*</span> <br><small><i>Name of the Company's address</i></small></label>
                        <input class="input-md textinput textInput form-control" maxlength="255" name="company" placeholder="Company Name" type="text" required/>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="address" class="control-label requiredField"> ADDRESS<span class="red_star">*</span> <br><small><i>Address of reception</i></small></label>
                        <input class="input-md textinput textInput form-control" maxlength="255" name="address" placeholder="Address" type="text" required/>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="apartment" class="control-label requiredField"> APARTMENT, SUITE, ETC.<span class="red_star">*</span> <br><small><i>Apartment number or code</i></small></label>
                        <input class="input-md textinput textInput form-control" maxlength="255" name="apartment" placeholder="Apartment" type="text" required/>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="country" class="control-label requiredField"> COUNTRY<span class="red_star">*</span> <br><small><i>Country of the user</i></small></label>
                        <select required name="country" class="form-control" id="country">
                             <option value="">--SELECT YOUR COUNTRY--</option>
                                @foreach($countries as $c)
                                    <option value="{{$c->country_id}}">{{$c->title}}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="postal_code" class="control-label requiredField"> POSTAL CODE<span class="red_star">*</span> <br><small><i>Postal code of the user</i></small></label>
                        <input class="input-md textinput textInput form-control" maxlength="255" name="postal_code" placeholder="Postal code" type="text" required/>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="city" class="control-label requiredField"> CITY<span class="red_star">*</span> <br><small><i>City of the user</i></small></label>
                        <input class="input-md textinput textInput form-control" maxlength="255" name="city" placeholder="City" type="text" required/>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="address_phone" class="control-label requiredField"> PHONE<span class="red_star">*</span> <br><small><i>Phone of the user</i></small></label>
                        <input class="input-md textinput textInput form-control" maxlength="255" name="address_phone" placeholder="Phone" type="text" required/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer" style="border-top: none;">
            <input type="submit" name="submit_add_address" value="Add Address" class="ladda-button btn btn-primary" data-style="expand-right" id="submit_add_address"/>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        {!! Form::close() !!}
    </div> 
  </div>
</div>
<!-- MODAL UPDATE ADDRESS -->
<div id="edit_addr" class="modal fade" role="dialog">
  <div class="modal-lg modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Address</h4>
      </div>
      {!! Form::open(array('route' => 'edit_address_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
      <div class="wrapper margin-top">
        <div class="row">
          <!-- ADDRESS SECTION -->
          <div class="col-md-12">
            <div class="profile-content">
              <div class="row">

                <input type="hidden" name="address_id_edit" id="address_id_edit">

                <div class="form-group col-md-6">
                    <label for="address_fullname" class="control-label requiredField"> FULL NAME<span class="red_star">*</span> <br><small><i>Name of the User's address</i></small></label>
                    <input class="input-md textinput textInput form-control" id="address_fullname_edit" maxlength="255" name="address_fullname_edit" placeholder="Full Name" type="text" required/>
                </div>
                <div class="form-group col-md-6">
                    <label for="company" class="control-label requiredField"> COMPANY<span class="red_star">*</span> <br><small><i>Name of the Company's address</i></small></label>
                    <input class="input-md textinput textInput form-control" id="company_edit" maxlength="255" name="company_edit" placeholder="Company Name" type="text" required/>
                </div>
                <div class="form-group col-md-12">
                    <label for="address" class="control-label requiredField"> ADDRESS<span class="red_star">*</span> <br><small><i>Address of reception</i></small></label>
                    <input class="input-md textinput textInput form-control" id="address_edit" maxlength="255" name="address_edit" placeholder="Address" type="text" required/>
                </div>
                <div class="form-group col-md-12">
                    <label for="apartment" class="control-label requiredField"> APARTMENT, SUITE, ETC.<span class="red_star">*</span> <br><small><i>Apartment number or code</i></small></label>
                    <input class="input-md textinput textInput form-control" id="apartment_edit" maxlength="255" name="apartment_edit" placeholder="Apartment" type="text" required/>
                </div>
                <div class="form-group col-md-4">
                    <label for="country_edit" class="control-label requiredField"> COUNTRY<span class="red_star">*</span> <br><small><i>Country of the user</i></small></label>
                    <select required name="country_edit" class="form-control" id="country_edit">
                         <option value="">--SELECT YOUR COUNTRY--</option>
                            @foreach($countries as $c)
                                <option value="{{$c->country_id}}">{{$c->title}}</option>
                            @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="postal_code" class="control-label requiredField"> POSTAL CODE<span class="red_star">*</span> <br><small><i>Postal code of the user</i></small></label>
                    <input class="input-md textinput textInput form-control" id="postal_code_edit" maxlength="255" name="postal_code_edit" placeholder="Postal code" type="text" required/>
                </div>
                <div class="form-group col-md-4">
                    <label for="city" class="control-label requiredField"> CITY<span class="red_star">*</span> <br><small><i>City of the user</i></small></label>
                    <input class="input-md textinput textInput form-control" id="city_edit" maxlength="255" name="city_edit" placeholder="City" type="text" required/>
                </div>
                <div class="form-group col-md-12">
                    <label for="address_phone" class="control-label requiredField"> PHONE<span class="red_star">*</span> <br><small><i>Phone of the user</i></small></label>
                    <input class="input-md textinput textInput form-control" id="address_phone_edit" maxlength="255" name="address_phone_edit" placeholder="Phone" type="text" required/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="border-top: none;">
        <input type="submit" name="submit_edit_address" value="Save Changes" class="ladda-button btn btn-primary" data-style="expand-right" id="submit_edit_address"/>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      {!! Form::close() !!}
    </div> 
  </div>
</div>

<script src="/js/custom_functions.js"></script>
<script type="text/javascript">
  // ================== Converting php data to a json =================
  var user_id = <?=json_encode($user_details[0]->id);?>

  $(document).ready(function(){

     $('#orders_table').DataTable({
            ordering: true,
            paging: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},
                {extend: 'print',
                 customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                }
                }
            ]
        });


      // ================== ladda loader for edit user info =================
      loadLadda('submit_edit');
      loadLadda('submit_edit1');
      // ================== ladda loader for add user tag =================
      loadLadda('submit_add_tag');
      // ================== ladda loader for add user address =================
      loadLadda('submit_add_address');
      // ================== ladda loader for add user address =================
      loadLadda('submit_edit_address');
      // ==================== DELETE address  =====================
      ajaxDelete('.delete_user_address', 'delete-address', 'addr_rec_');
  });
</script>

<!-- Unlink User Tag -->
<script src="cms/js/users/unlink-user-tag.js"></script>
<!-- ajax call to view address -->
<script src="cms/js/users/get-address-details.js"></script>
<!-- ajax call to load address details to edit address -->
<script src="cms/js/users/get-address-to-edit.js"></script>

@endsection