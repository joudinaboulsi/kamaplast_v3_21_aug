{!! Form::open(array('route' => 'edit_user_information_path', 'class' => 'add-form', 'files' => true)) !!}

    <div class="row">

        <div class="col-xs-12">
          
          @if (session('error'))
              <div class="alert alert-danger">
                  {{ session('error') }}
              </div>
          @endif
          @if (session('success'))
              <div class="alert alert-success">
                  {{ session('success') }}
              </div>
          @endif

        </div>

        <div class="col-xs-12">
            <h2 class="block-title-2"> Please be sure to update your personal information if it has changed. </h2>
        </div>

        <div class="col-xs-12 col-sm-6">

            <input type="hidden" name="user_id" value="{{$user[0]->id}}">

            <div class="form-group">
                <?php 
                    if($user[0]->img != NULL)
                        $img = getenv('S3_URL').'/users/thumbs/'.$user[0]->img;
                    else
                        $img = '/images/placeholder-image.png';                   
                 ?>

                <img alt="profile picture" class="img-responsive" src="{{$img}}" onerror="this.onerror=null;this.src='/images/placeholder-image.png';"> 
                <label for="image" style="margin-top:5px">Change Image </label>
                <input class="input-md textinput textInput form-control" id="file-upload" type="file" name="image" />
            </div>
            
        </div>

        <div class="col-xs-12 col-sm-6">

            <div class="form-group required">
                <label for="name">Name <sup>*</sup> </label>
                <input required type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{$user[0]->name}}">
            </div>

            <div class="form-group required">
                <label for="email"> Email <sup>*</sup> </label>
                <input type="email" class="form-control" id="email"name="email" placeholder="example@example.com" value="{{$user[0]->email}}">
            </div>

            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" class="form-control" id="birth_date" name="birth_date" placeholder="example@example.com" value="{{$user[0]->birth_date}}">
            </div>

            <div class="form-group">
                <label for="gender"> Gender </label>
                <select class="form-control" id="gender" name="gender">
                    <option value="Male" @if($user[0]->gender == "Male") selected @endif>Male</option>
                    <option value="Female" @if($user[0]->gender == "Female") selected @endif>Female</option>
                </select>
            </div>

            <div class="form-group ">
                <p class="clearfix">
                    <input type="checkbox" value="1" name="has_newsletter" id="has_newsletters">
                    <label for="has_newsletter">Sign up for our newsletter!</label>
                </p>
            </div>
            
        </div>

      </div>

      <div class="row">

        <div class="col-sm-6">            
            <div class="form-group">
              <a title="Reset your password" class="btn btn-stroke dark thin" href="#" data-toggle="modal" data-target="#modalResetPassword">Reset your password</a>
            </div>
        </div>

        <div class="col-sm-6 text-right">
            <button id="submit_edit" type="submit" class="ladda-button btn btn-stroke btn-dark thin"> Save</button>
        </div>

    </div>
    <!--/row-->

{!! Form::close() !!}



<!-- Modal Reset Password -->
<div class="modal fade" id="modalResetPassword" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">

        <div class="modal-content">

          {!! Form::open(array('route' => 'edit_user_password_path')) !!}

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times;</button>
                <h2 class="text-center" style="position:relative; top:8px;"> Change your Password </h2>
            </div>

            <div class="modal-body">

              <div class="row">

                <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                    <label for="new-password" class="col-md-4 control-label">Current Password</label>

                    <div class="col-md-6">
                        <input id="current-password" type="password" class="form-control" name="current-password" required>

                        @if ($errors->has('current-password'))
                            <span class="help-block">
                            <strong>{{ $errors->first('current-password') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
                    <label for="new-password" class="col-md-4 control-label">New Password</label>

                    <div class="col-md-6">
                        <input id="new-password" type="password" class="form-control" name="new-password" required>

                        @if ($errors->has('new-password'))
                            <span class="help-block">
                            <strong>{{ $errors->first('new-password') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="new-password-confirm" class="col-md-4 control-label">Confirm New Password</label>

                    <div class="col-md-6">
                        <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-4"></div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-stroke btn-dark thin">Change Password</button>
                    </div>
                </div>

              </div>

            </div>

          {!! Form::close() !!}

        </div>

    </div>
</div>
<!-- End Modal Reset Password -->
