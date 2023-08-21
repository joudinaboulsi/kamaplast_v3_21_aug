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
            <div class="col-md-12">
                <div class="text-center" style="margin:50px 0">
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
                                <img src="/images/about/1.jpg" loading="lazy" class="img-responsive">
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
                                <img src="/images/about/2.jpg" loading="lazy" class="img-responsive">
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
                                <img src="/images/about/3.jpg" loading="lazy" class="img-responsive">
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
                                <img src="/images/about/4.jpg" loading="lazy" class="img-responsive">
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
                                <img src="/images/about/5.jpg" loading="lazy" class="img-responsive">
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
                                <img src="/images/about/6.jpg" loading="lazy" class="img-responsive">
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
                                <img src="/images/about/7.jpg" loading="lazy" class="img-responsive">
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
                                <img src="/images/about/8.jpg" loading="lazy" class="img-responsive">
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
                                <img src="/images/about/klava.png" loading="lazy" class="img-responsive">
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
                                <img src="/images/about/10.jpg" loading="lazy" class="img-responsive">
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
                                <img src="/images/about/11.jpg" loading="lazy" class="img-responsive">
                            </div>
                        </div>
                    </li>
                </ul>
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
