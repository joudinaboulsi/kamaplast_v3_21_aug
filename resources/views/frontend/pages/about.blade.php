@extends('frontend.layouts.app')

@section('content')
    <!-- Page Header  -->
    <?php
    $img = getenv('S3_URL') . '/about/' . $about[0]->header_img;
    ?>
    <div class="parallax-section parallax-fx about-3 parallaxOffset no-padding"
        style="background-image: url({{ $img }}); ">
        <div class="w100 ">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="parallax-content clearfix  wow fadeInUp" data-wow-duration=".8s" data-wow-delay="0">
                            <h2 class="intro-heading ">{{ $about[0]->header_title }}</h2>
                            <p>{{ $about[0]->header_subtitle }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page Header  -->

    <!-- About us Content  -->
    <div class="container main-container ">

        <div class="row">

            <div class="col-sm-8 col-sm-push-2">

                <div class="about-content wow fadeIn" data-wow-duration=".8s" data-wow-delay="0" style="max-width:none;">
                    <p class="lead-2 text-center">


                        Kamaplast Industrial S.A.R.L is the leading plastic manufacturer based in Lebanon. Established in
                        <b>1982</b>.<br> our company has rapidly grown to become the Lebanese largest supplier and brand
                        leader of wide
                        ranged plastic products
                    </p>
                </div>

            </div>

        </div>

        {{-- <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <h1>Our Story</h1>
                </div>
                <ul class="timeline">
                    <li>
                        <div class="timeline-badge primary"><a><i class="glyphicon glyphicon-record" rel="tooltip"
                                    title="11 hours ago via Twitter" id=""></i></a></div>
                        <div class="timeline-panel">
                            <div class="timeline-body">
                                <h3>1982</h3>
                                <p>Mohamad Kabalan and his son Jamil Kabalan established the first household manufacturing
                                    company in Lebanon</p>
                            </div>
                            <div class="timeline-heading">
                                <img src="/images/about/1.jpg" class="img-responsive">
                            </div>
                        </div>
                    </li>

                    <li class="timeline-inverted">
                        <div class="timeline-badge primary"><a><i class="glyphicon glyphicon-record invert" rel="tooltip"
                                    title="11 hours ago via Twitter" id=""></i></a></div>
                        <div class="timeline-panel">
                            <div class="timeline-body">
                                <h3>1984</h3>
                                <p>Official factory opening</p>
                            </div>
                            <div class="timeline-heading">
                                <img src="/images/about/2.jpg" class="img-responsive">
                            </div>
                        </div>
                    </li>

                    <li>
                        <div class="timeline-badge primary"><a><i class="glyphicon glyphicon-record" rel="tooltip"
                                    title="11 hours ago via Twitter" id=""></i></a></div>
                        <div class="timeline-panel">
                            <div class="timeline-body">
                                <h3>1992</h3>
                                <p>Introducing the garden furniture line, by manufacturing the first plastic chair “Florence
                                    Chair”</p>
                            </div>
                            <div class="timeline-heading">
                                <img src="/images/about/3.jpg" class="img-responsive">
                            </div>
                        </div>
                    </li>

                    <li class="timeline-inverted">
                        <div class="timeline-badge primary"><a><i class="glyphicon glyphicon-record invert" rel="tooltip"
                                    title="11 hours ago via Twitter" id=""></i></a></div>
                        <div class="timeline-panel">
                            <div class="timeline-body">
                                <h3>1995</h3>
                                <p>Expansion of the factory premises and introducing new machines</p>
                            </div>
                            <div class="timeline-heading">
                                <img src="/images/about/4.jpg" class="img-responsive">
                            </div>
                        </div>
                    </li>

                    <li>
                        <div class="timeline-badge primary"><a><i class="glyphicon glyphicon-record" rel="tooltip"
                                    title="11 hours ago via Twitter" id=""></i></a></div>
                        <div class="timeline-panel">
                            <div class="timeline-body">
                                <h3>1997</h3>
                                <p>Participating in the biggest fair in Lebanon – Horeca</p>
                            </div>
                            <div class="timeline-heading">
                                <img src="/images/about/5.jpg" class="img-responsive">
                            </div>
                        </div>
                    </li>

                    <li class="timeline-inverted">
                        <div class="timeline-badge primary"><a><i class="glyphicon glyphicon-record invert" rel="tooltip"
                                    title="11 hours ago via Twitter" id=""></i></a></div>
                        <div class="timeline-panel">
                            <div class="timeline-body">
                                <h3>2000</h3>
                                <p>Baalbeck Festival furnished by 1,000 Kamaplast chairs</p>
                            </div>
                            <div class="timeline-heading">
                                <img src="/images/about/6.jpg" class="img-responsive">
                            </div>
                        </div>
                    </li>

                    <li>
                        <div class="timeline-badge primary"><a><i class="glyphicon glyphicon-record" rel="tooltip"
                                    title="11 hours ago via Twitter" id=""></i></a></div>
                        <div class="timeline-panel">
                            <div class="timeline-body">
                                <h3>2002</h3>
                                <p>First prize winning as the “Best Plastic Exhibitor” at Dubai International Exhibition</p>
                            </div>
                            <div class="timeline-heading">
                                <img src="/images/about/7.jpg" class="img-responsive">
                            </div>
                        </div>
                    </li>

                    <li class="timeline-inverted">
                        <div class="timeline-badge primary"><a><i class="glyphicon glyphicon-record invert" rel="tooltip"
                                    title="11 hours ago via Twitter" id=""></i></a></div>
                        <div class="timeline-panel">
                            <div class="timeline-body">
                                <h3>2005</h3>
                                <p>First plastic swing production in Lebanon</p>
                            </div>
                            <div class="timeline-heading">
                                <img src="/images/about/8.jpg" class="img-responsive">
                            </div>
                        </div>
                    </li>

                    <li>
                        <div class="timeline-badge primary"><a><i class="glyphicon glyphicon-record" rel="tooltip"
                                    title="11 hours ago via Twitter" id=""></i></a></div>
                        <div class="timeline-panel">
                            <div class="timeline-body">
                                <h3>2017</h3>
                                <p>Launched a new brand “Klava” specialized in cleaning equipment</p>
                            </div>
                            <div class="timeline-heading">
                                <img src="/images/about/klava.png" class="img-responsive">
                            </div>
                        </div>
                    </li>

                    <li class="timeline-inverted">
                        <div class="timeline-badge primary"><a><i class="glyphicon glyphicon-record invert"
                                    rel="tooltip" title="11 hours ago via Twitter" id=""></i></a></div>
                        <div class="timeline-panel">
                            <div class="timeline-body">
                                <h3>2018</h3>
                                <p>Participated in the biggest worldwide fair - China Import and Export Fair (Canton Fair)
                                </p>
                            </div>
                            <div class="timeline-heading">
                                <img src="/images/about/10.jpg" class="img-responsive">
                            </div>
                        </div>
                    </li>

                    <li>
                        <div class="timeline-badge primary"><a><i class="glyphicon glyphicon-record" rel="tooltip"
                                    title="11 hours ago via Twitter" id=""></i></a></div>
                        <div class="timeline-panel">
                            <div class="timeline-body">
                                <h3>2018</h3>
                                <p>New branch opening in Beirut</p>
                            </div>
                            <div class="timeline-heading">
                                <img src="/images/about/11.jpg" class="img-responsive">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="employees">
                    <p class="counter-count">120</p>
                    <p class="employee-p"><img loading="lazy"  src="/images/about/1.png" width="50"> &nbsp; Products</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="customer">
                    <p class="counter-count">15</p>
                    <p class="customer-p"><img  loading="lazy" src="/images/about/2.png" width="35"> &nbsp; Countries</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="design">
                    <p class="counter-count">2500</p>
                    <p class="design-p"><img  loading="lazy" src="/images/about/3.png" width="50"> &nbsp; Customers</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">

                <div style="clear:both;">
                    <hr class="hr40">
                </div>

                <div class="row has-equal-height-child">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-xxs-12 is-equal">
                        <div class="hw100 display-table">
                            <div class="hw100 display-table-cell">
                                <div class="content-inner wow fadeInRight" data-wow-duration=".8s" data-wow-delay="0">

                                    <div class="about-content-text odm_text">
                                        <div style="display:flex">
                                            <img loading="lazy" src="{{ asset('images/icons8-goal-50.png') }}" width="50">

                                            <h4 class="design-p" style="padding:0 5px">Our Mission</h4>
                                        </div> <br>
                                        <p>
                                            <b>Our 36+ years</b> aim is to create new designs and ideas to enhance and
                                            facilitate the living standards, while meeting customer’s satisfaction.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-xxs-12 is-equal">
                        <div class="content-inner wow fadeInLeft" data-wow-duration=".8s" data-wow-delay="0">
                            <div class="about-content-text odm_text">
                                <div style="display:flex">
                                    <img loading="lazy" src="{{ asset('images/eye.png') }}">
                                    <h4 class="design-p">Our Vision</h4>
                                </div>
                                <br>
                                <p>
                                    <b>Kamaplast</b> a part of everyone’s daily life.
                                </p>

                            </div>
                        </div>

                    </div>


                </div>
                <!--/.row-->

                <div style="clear:both;">
                    <hr class="hr40">
                </div>

            </div>
        </div>

        <div class="row has-equal-height-child footerOffset">

            <div class="text-center about-content-text odm_text">
                <h4 class="design-p">Our Values</h4>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-xxs-12 is-equal">
                <div class="content-inner wow fadeInLeft" data-wow-duration=".8s" data-wow-delay="0">
                    <img  loading="lazy" src="images/about/trust.png" class="img-responsive" alt="img">
                    <h1 class="val-title">Trust</h1>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-xxs-12 is-equal">
                <div class="content-inner wow fadeInRight" data-wow-duration=".8s" data-wow-delay="0">
                    <img  loading="lazy" src="images/about/quality.png" class="img-responsive" alt="img">
                    <h1 class="val-title">Quality</h1>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-xxs-12 is-equal">
                <div class="content-inner wow fadeInRight" data-wow-duration=".8s" data-wow-delay="0">
                    <img  loading="lazy" src="images/about/determination.png" class="img-responsive" alt="img">
                    <h1 class="val-title">Determination</h1>
                </div>
            </div>

        </div>
        <!--/.row-->

        <div class="gap"></div>

    </div>
    <!-- About us Content -->

    <script type="text/javascript">
        $('.counter-count').each(function() {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
            }, {
                duration: 5000,
                easing: 'swing',
                step: function(now) {
                    $(this).text(Math.ceil(now));
                }
            });
        });
    </script>
@endsection
