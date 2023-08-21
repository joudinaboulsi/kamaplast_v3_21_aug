@extends('frontend.layouts.app')

@section('content')
    @if (Session::has('msg'))
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-xs modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times;</button>
                        <h3 class="text-center" style="position:relative; top:8px;">{!! Session::get('msg') !!}</h3>
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
                                <h3>Contact us</h3>
                                <p class="lead">
                                    If you would like to speak to us personally, you can contact us using the following
                                    details.<br /> A member of our team will be more than happy to run through everything we
                                    can
                                    offer!
                                </p>
                            </div>

                            <div class="post-main-view">

                                <div class="post-description wow fadeIn" data-wow-duration="0.2s" data-wow-delay=".1s">

                                    <div class="row">

                                        <!-- Dubai Branch  -->
                                        <div class="col-xs-12" style="text-align: center; padding:10px 0px">

                                            <h3 class="block-title-5" style="text-align: center;">
                                                Kamaplast Industrial S.A.R.L

                                            </h3>

                                            <p>
                                                Saida - Lebanon

                                                <br>
                                                <strong> P.O. Box </strong> : 466 Saida
                                                <br>
                                                <strong> Tel </strong> : +961 7 22 22 00
                                                <br>
                                                <strong> Mob & Whatsapp </strong> : +961 3 21 01 00

                                                <br>
                                                <strong>Working Days </strong> : Monday to Saturday
                                                <br>
                                                <strong> Email </strong> : <a
                                                    href="mailto:sales@kama-plast.com">sales@kama-plast.com</a>
                                               <div style="padding:10px 0">
                                                <iframe
                                                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d6653.761890275397!2d35.3426113!3d33.5044745!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzPCsDMwJzE2LjEiTiAzNcKwMjAnNDEuMyJF!5e0!3m2!1sen!2slb!4v1555332955198!5m2!1sen!2slb"
                                                    width="100%" height="450" style="border:0" allowfullscreen></iframe>
                                               </div>
                                            </p>



                                        </div>
                                        <!-- End Dubai Branch  -->
                                        <!-- Dubai Branch  -->
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            <h3 class="block-title-5">
                                                FACTORY
                                            </h3>

                                            <p>
                                                Saida - Ghazieh

                                                <br>
                                                <strong> P.O. Box </strong> : 466 Saida
                                                <br>
                                                <strong> Tel </strong> : +961 7 22 22 00
                                                <br>
                                                <strong> Mob & Whatsapp </strong> : +961 3 21 01 00
                                                <br>
                                                <strong> Email </strong> : <a
                                                    href="mailto:sales@kama-plast.com">sales@kama-plast.com</a>
                                            </p>

                                            <iframe
                                                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d6653.761890275397!2d35.3426113!3d33.5044745!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzPCsDMwJzE2LjEiTiAzNcKwMjAnNDEuMyJF!5e0!3m2!1sen!2slb!4v1555332955198!5m2!1sen!2slb"
                                                width="100%" height="450" style="border:0" allowfullscreen></iframe>

                                        </div>
                                        <!-- End Dubai Branch  -->

                                        <!-- Beirut Branch  -->
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            <h3 class="block-title-5">
                                                STORE
                                            </h3>

                                            <p>
                                                Beirut Branch

                                                <br>
                                                <strong> Address </strong> : Beirut - Basta Al Tahta
                                                <br>
                                                <strong> Tel </strong> : +961 1 66 80 80
                                                <br>
                                                <strong> Mob & Whatsapp </strong> : +961 71 11 80 80
                                                <br>
                                                <strong> Email </strong> : <a
                                                    href="mailto:sales.basta@kama-plast.com">sales.basta@kama-plast.com</a>
                                            </p>

                                            <iframe
                                                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13247.383767895091!2d35.5012316!3d33.8936213!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x44d84600f6a0098!2sKamaplast+Industrial+S.A.R.L!5e0!3m2!1sen!2slb!4v1555333090154!5m2!1sen!2slb"
                                                width="100%" height="450" style="border:0" allowfullscreen></iframe>

                                        </div>
                                        <!-- End Beirut  Branch  -->

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-xs-12">
                                <div class="panel panel-default ">

                                    <div class="panel-heading contact_panel">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" href="#collapse1" class="collapseWill"> Send us an
                                                email
                                                <span class="pull-left"> <i
                                                        class="caret_toggle fa fa-caret-right"></i></span>
                                            </a>
                                        </h4>
                                    </div>

                                    <!-- Form  -->
                                    <div id="collapse1" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            {!! Form::open(['route' => 'contact_path', 'class' => 'form-horizontal', 'id' => 'contactform']) !!}
                                            <fieldset>

                                                <legend style="padding:20px 0!important;">Fill in the below form,
                                                    click “SUBMIT” and someone from our team
                                                    will get back to you shortly</legend>

                                                <!-- Name -->
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="name">Name
                                                        <sup>*</sup></label>

                                                    <div class="col-md-4">
                                                        <input id="name" name="name" placeholder="Name"
                                                            class="form-control required show-error-msg input-md"
                                                            type="text">
                                                    </div>
                                                </div>

                                                <!-- Phone Number -->
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="phone">Phone
                                                        Number</label>

                                                    <div class="col-md-4">
                                                        <input id="phone" name="phone" placeholder="Phone Number"
                                                            class="form-control show-error-msg input-md" type="text">
                                                    </div>
                                                </div>

                                                <!-- Mail -->
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="email">Mail
                                                        <sup>*</sup></label>

                                                    <div class="col-md-4">
                                                        <input id="email" name="email" placeholder="Mail"
                                                            class="form-control required show-error-msg input-md"
                                                            type="email">
                                                    </div>
                                                </div>

                                                <!-- Textarea -->
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="message">Message
                                                        <sup>*</sup></label>

                                                    <div class="col-md-4">
                                                        <textarea class="form-control required show-error-msg" id="message" name="message" rows="5"
                                                            placeholder="Message"></textarea>
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
                                    <!-- End Form  -->

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
                    minlength: 6,
                },
                location: {
                    required: true
                },
                message: {
                    minlength: 3
                }
            }
        });


        $('.contact_panel a').on('click', function() {

            $('.caret_toggle').toggleClass('fa-caret-right fa-caret-down');


        })
    </script>
@endsection
