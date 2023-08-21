<div class="row">

    <div class="col-xs-12">

        <p>Please configure your default billing and delivery addresses when placing an order. You may also add additional addresses, which can be useful for sending gifts or receiving an order at your office.</p>

    </div>

</div>

<div class="row" style="margin-bottom: 10px">
        
    <div class="col-sm-8 col-xs-12">
        <h2 class="block-title-2" style="padding:0;margin-bottom:3px;"> Your addresses are listed below </h2>
        <p> Be sure to update your personal information if it has changed.</p>
    </div>

    <div class="col-sm-4 col-xs-12">
        <a class="btn btn-stroke btn-dark thin float-right" data-toggle="modal" data-target="#add_address" title="Add Address"> Add New Address </a>
    </div>

</div>

<div class="row">

    @foreach($addresses as $addr)
        <div class="col-xs-12 col-sm-6 col-md-4" id="addr_rec_{{$addr->address_id}}">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$addr->name}}</h3>
                    <div style="float: right; margin-top: -20px;">
                      <a onclick="getAddrtoEditByUser({{$addr->address_id}})" style="margin-right:5px" data-toggle="modal" data-target="#edit_addr" title="Edit Address"> <i class="fa fa-edit"> </i> </a>
                      <a id="{{$addr->address_id}}" class="delete_user_address" title="Delete Address"> <i class="glyphicon glyphicon-trash"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <ul>
                        <li><span class="address-name"> <strong>Name</strong> {{$addr->name}}</span></li>
                        <li><span class="address-company"><strong>Company:</strong> {{$addr->company}}</span></li>
                        <li><span class="address-line1"><strong>Adress:</strong> {{$addr->address}}</span></li>
                        <li><span class="address-line2"><strong>Apartment:</strong> {{$addr->apartment}}</span></li>
                        <li><span class="address-line2"><strong>City:</strong> {{$addr->city}}</span></li>
                        <li><span class="address-line2"><strong>Country:</strong> {{$addr->country_name}}</span></li>
                        <li><span class="address-line2"><strong>Postal Code:</strong> {{$addr->postal_code}}</span></li>
                        <li><span> <strong>Phone:</strong> {{$addr->phone}}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    @endforeach

</div>
<!--/row-->


<!-- ================== MODALS ================== -->


<!--Modal add new Address -->
<div id="add_address" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times;</button>
          <h2 class="text-center" style="position:relative; top:8px;"> Add Address </h2>
      </div>

      {!! Form::open(array('route' => 'add_user_address_path', 'id' => 'add_form', 'class' => 'add-form', 'files' => true)) !!}
        <div class="modal-body">

          <div class="profile-content">
            <div class="row">
             
              <input type="hidden" name="user_id" value="{{$user[0]->id}}">

              <div class="form-group required col-md-6">
                  <label for="fullname" class="control-label requiredField"> Full Name <sup>*</sup></label>
                  <input class="input-md textinput textInput form-control" maxlength="255" name="fullname" placeholder="Full Name" type="text" required/>
              </div>

              <div class="form-group col-md-6">
                  <label for="company" class="control-label"> Company </label>
                  <input class="input-md textinput textInput form-control" maxlength="255" name="company" placeholder="Company Name" type="text"/>
              </div>

              <div class="form-group required col-md-12">
                  <label for="address" class="control-label requiredField"> Address <sup>*</sup></label>
                  <input class="input-md textinput textInput form-control" maxlength="255" name="address" placeholder="Address" type="text" required/>
              </div>

              <div class="form-group required col-md-12">
                  <label for="apartment" class="control-label requiredField"> Apartment, Suite, etc. <sup>*</sup></label>
                  <input class="input-md textinput textInput form-control" maxlength="255" name="apartment" placeholder="Apartment" type="text" required/>
              </div>

              <div class="form-group required col-md-4">
                  <label class="control-label requiredField"> Country <sup>*</sup></label>
                  <select required name="country" class="form-control">
                      <option value="">--SELECT YOUR COUNTRY--</option>
                      @foreach($countries as $c)
                          <option value="{{$c->country_id}}">{{$c->title}}</option>
                      @endforeach
                  </select>
              </div>

              <div class="form-group required col-md-4">
                  <label for="postal_code" class="control-label requiredField"> Postal Code <sup>*</sup></label>
                  <input class="input-md textinput textInput form-control" maxlength="255" name="postal_code" placeholder="Postal code" type="text" required/>
              </div>

              <div class="form-group required col-md-4">
                  <label for="city" class="control-label requiredField"> City <sup>*</sup></label>
                  <input class="input-md textinput textInput form-control" maxlength="255" name="city" placeholder="City" type="text" required/>
              </div>

              <div class="form-group required col-md-12">
                  <label for="address_phone" class="control-label requiredField"> Phone <sup>*</sup></label>
                  <input class="input-md textinput textInput form-control" maxlength="255" name="address_phone" placeholder="Phone" type="text" required/>
              </div>

              <div class="form-group col-md-12">
                  <input type="submit" name="submit_add_address" value="Add Address" class="ladda-button btn btn-stroke btn-dark thin" data-style="expand-right" id="submit_add_address"/>
                  <button type="button" class="btn btn-stroke dark thin" data-dismiss="modal">Close</button>
              </div>

            </div>
          </div>

        </div>
      {!! Form::close() !!}

    </div> 
  </div>
</div>


<!-- MODAL UPDATE ADDRESS -->
<div id="edit_addr" class="modal fade" role="dialog">
  <div class="modal-md modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="text-center" style="position:relative; top:8px;">Update Address</h2>
      </div>

      {!! Form::open(array('route' => 'edit_user_address_path', 'class' => 'add-form', 'files' => true)) !!}
      <div class="modal-body margin-top">
        <div class="profile-content">
          <div class="row">

            <input type="hidden" name="address_id_edit" id="address_id_edit">

            <div class="form-group required col-md-6">
                <label for="fullname" class="control-label requiredField"> Full Name <sup>*</sup></label>
                <input class="input-md textinput textInput form-control" id="fullname_edit" maxlength="255" name="fullname_edit" placeholder="Full Name" type="text" required/>
            </div>

            <div class="form-group col-md-6">
                <label for="company" class="control-label"> Company </label>
                <input class="input-md textinput textInput form-control" id="company_edit" maxlength="255" name="company_edit" placeholder="Company Name" type="text"/>
            </div>

            <div class="form-group required col-md-12">
                <label for="address" class="control-label requiredField"> Address <sup>*</sup></label>
                <input class="input-md textinput textInput form-control" id="address_edit" maxlength="255" name="address_edit" placeholder="Address" type="text" required/>
            </div>

            <div class="form-group required col-md-12">
                <label for="apartment" class="control-label requiredField"> Apartment, Suite, etc. <sup>*</sup></label>
                <input class="input-md textinput textInput form-control" id="apartment_edit" maxlength="255" name="apartment_edit" placeholder="Apartment" type="text" required/>
            </div>

            <div class="form-group required col-md-4">
                <label for="country_edit" class="control-label"> Country <sup>*</sup></label>
                <select required name="country_edit" class="form-control" id="country_edit">
                     <option value="">--SELECT YOUR COUNTRY--</option>
                        @foreach($countries as $c)
                            <option value="{{$c->country_id}}">{{$c->title}}</option>
                        @endforeach
                </select>
            </div>

            <div class="form-group required col-md-4">
                <label for="postal_code" class="control-label requiredField"> Postal Code <sup>*</sup></label>
                <input class="input-md textinput textInput form-control" id="postal_code_edit" maxlength="255" name="postal_code_edit" placeholder="Postal code" type="text" required/>
            </div>

            <div class="form-group required col-md-4">
                <label for="city" class="control-label requiredField"> City <sup>*</sup></label>
                <input class="input-md textinput textInput form-control" id="city_edit" maxlength="255" name="city_edit" placeholder="City" type="text" required/>
            </div>

            <div class="form-group required col-md-12">
                <label for="address_phone" class="control-label requiredField"> Phone <sup>*</sup></label>
                <input class="input-md textinput textInput form-control" id="address_phone_edit" maxlength="255" name="address_phone_edit" placeholder="Phone" type="text" required/>
            </div>

            <div class="form-group col-md-12">
                <input type="submit" name="submit_edit_address" value="Save Changes" class="ladda-button btn btn-stroke btn-dark thin" data-style="expand-right" id="submit_edit_address"/>
                <button type="button" class="btn btn-stroke dark thin" data-dismiss="modal">Close</button>
            </div>

          </div>
        </div>
      </div>
      {!! Form::close() !!}

    </div>
  </div>
</div>