@extends('frontend.layouts.app')

@section('content')
    @if (Session::has('msg'))
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-xs modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times;</button>
                        <h2 class="text-center" style="position:relative; top:8px;">{!! Session::get('msg') !!}</h2>
                    </div>

                    <div class="modal-body">
                        <p>Your message is sent. We will get back to you within 24h.</p>
                    </div>

                </div>

            </div>
        </div>
    @endif

    <!-- Page Content  -->
    <div class="blog-wrapper headerOffset">
        <div class="container">
            <div class="row">
                <div class="blog-left">
                    <div class="bl-inner">
                        <div class="item-blog-post">

                            <div class="about-content-text text-center">
                                <h3>Careers</h3>
                                <p> We are always on the lookout for skilled people with a passion for working in our
                                    industry.</p>
                            </div>

                            <div class="post-main-view">

                                <div class="post-description wow fadeIn" data-wow-duration="0.2s" data-wow-delay=".1s">

                                    <div class="row">

                                        <!-- Sidebar  -->
                                        <div class="col-sm-6">

                                            <div class="panel-group" role="tablist" aria-multiselectable="true">

                                                @foreach ($careers as $c)
                                                    <!-- Position  -->
                                                    <div class="panel panel-default">

                                                        <div class="panel-heading" role="tab"
                                                            id="heading_{{ $c->career_id }}">
                                                            <h4 class="panel-title">
                                                                <a class="collapsed" role="button" data-toggle="collapse"
                                                                    href="#collapse_{{ $c->career_id }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="collapse_{{ $c->career_id }}">
                                                                    {{ $c->title }}
                                                                </a>
                                                            </h4>
                                                        </div>

                                                        <div id="collapse_{{ $c->career_id }}"
                                                            class="panel-collapse collapse" role="tabpanel"
                                                            aria-labelledby="heading_{{ $c->career_id }}">
                                                            <div class="panel-body">
                                                                {!! $c->description !!}
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <!-- End Position  -->
                                                @endforeach

                                            </div>

                                        </div>
                                        <!-- End Sidebar  -->

                                        <!-- Form  -->
                                        <div class="col-sm-6 col-xs-12">

                                            <div class="panel panel-default ">

                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a data-toggle="collapse" href="#collapse1" class="collapseWill">
                                                            Apply
                                                            <span class="pull-left"> <i
                                                                    class="fa fa-caret-right"></i></span>
                                                        </a>
                                                    </h4>
                                                </div>

                                                <div id="collapse1" class="panel-collapse collapse in">
                                                    <div class="panel-body">
                                                        {!! Form::open(['route' => 'careers_path', 'class' => 'form-horizontal', 'id' => 'contactform']) !!}
                                                        <fieldset>

                                                            <legend style="font-size:20px !important">Fill in the below form
                                                                to apply for a job opportunity
                                                            </legend>

                                                            <!-- Full Name -->
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="fullname">Full
                                                                    Name <sup>*</sup></label>

                                                                <div class="col-md-9">
                                                                    <input id="fullname" name="fullname"
                                                                        placeholder="Full Name"
                                                                        class="form-control required show-error-msg input-md"
                                                                        type="text">
                                                                </div>
                                                            </div>

                                                            <!-- Date of Birth -->
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="dob">Date of
                                                                    Birth <sup>*</sup></label>

                                                                <div class="col-md-9">
                                                                    <input id="dob" name="dob"
                                                                        class="form-control required show-error-msg input-md"
                                                                        type="date">
                                                                </div>
                                                            </div>

                                                            <!-- Position -->
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label"
                                                                    for="position">Position <sup>*</sup></label>

                                                                <div class="col-md-9">
                                                                    <select id="position" name="position"
                                                                        class="form-control required show-error-msg input-md">
                                                                        <option value="Sales Executive (Field-Based)">Sales
                                                                            Executive (Field-Based)</option>
                                                                        <option value="Collapsible Group Item #2">
                                                                            Collapsible Group Item #2</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- Phone -->
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="phone">Phone
                                                                    <sup>*</sup></label>

                                                                <div class="col-md-9">
                                                                    <input id="phone" name="phone" placeholder="Phone"
                                                                        class="form-control required show-error-msg input-md"
                                                                        type="text">
                                                                </div>
                                                            </div>

                                                            <!-- Salary -->
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="salary">Salary
                                                                    <sup>*</sup></label>

                                                                <div class="col-md-9">
                                                                    <input id="salary" name="salary"
                                                                        placeholder="Salary"
                                                                        class="form-control required show-error-msg input-md"
                                                                        type="text">
                                                                </div>
                                                            </div>

                                                            <!-- Mail -->
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="email">Mail
                                                                    <sup>*</sup></label>

                                                                <div class="col-md-9">
                                                                    <input id="email" name="email"
                                                                        placeholder="Mail"
                                                                        class="form-control required show-error-msg input-md"
                                                                        type="email">
                                                                </div>
                                                            </div>

                                                            <!-- Photo -->
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label"
                                                                    for="photo">Photo</label>

                                                                <div class="col-md-9">
                                                                    <input id="photo" name="photo"
                                                                        class="form-control show-error-msg input-md"
                                                                        type="file">
                                                                </div>
                                                            </div>

                                                            <!-- CV -->
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="cv">CV
                                                                    <sup>*</sup></label>

                                                                <div class="col-md-9">
                                                                    <input id="cv" name="cv"
                                                                        class="form-control required show-error-msg input-md"
                                                                        type="file">
                                                                </div>
                                                            </div>

                                                            <!-- Experience -->
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label"
                                                                    for="message">Experience</label>

                                                                <div class="col-md-9">
                                                                    <textarea class="form-control show-error-msg" id="message" name="message" rows="5"
                                                                        placeholder="Experience"></textarea>
                                                                </div>
                                                            </div>

                                                            <!-- Submit button -->
                                                            <div class="form-group">
                                                                <div class="text-center">
                                                                    <button type="submit" id="submit"
                                                                        class="btn btn-stroke-dark thin lite">Submit</button>
                                                                </div>
                                                            </div>

                                                        </fieldset>
                                                        {!! Form::close() !!}
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- End Form  -->

                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="clear:both"></div>

    </div>
    <!-- End Page Content  -->

    <div class="gap"></div>

    <script src="js/jquery.validate.min.js"></script>

    <script type="text/javascript">
        $("#contactform").validate({
            ignore: ".ignore",
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    minlength: 6,
                },
                salary: {
                    required: true
                },
                location: {
                    required: true
                },
                message: {
                    minlength: 3
                }
            }
        });
    </script>
@endsection
