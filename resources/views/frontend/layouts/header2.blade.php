<?php
use App\Http\Repositories\Frontend\CategoriesApis;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Repositories\CategoryRepository;
use App\Http\Controllers\Frontend\ProductsController;
$categ_rep = new CategoryRepository();
$categ_controller = new CategoryController($categ_rep);

// get all the categories with there children
$categories = $categ_controller->buildMenu();
?>

<nav class="navbar navbar-default navbar-fixed-top">
    <!-- Navbar Top -->
    <div class="navbar-top hidden-xs">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 no-margin no-padding">
                    <div class="pull-left">
                        <ul class="list-inline">
                            <li><i class="fa fa-whatsapp" aria-hidden="true" style="font:bold"></i><a
                                    href="https://wa.me/9613210100"><b> +961 3 21 01 00</b></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-8 no-margin no-padding">
                    <div class="pull-right">
                        <ul class="list-inline">
                            <!-- DESKTOP -->
                            <li class="hidden-xs"><a href="{{ route('about_path') }}"> Company </a></li>
                            <li class="hidden-xs"><a href="{{ route('story_path') }}"> Our Story </a></li>
                            <!-- DESKTOP -->
                            <li class="hidden-xs"><a href="{{ route('odm_path') }}"> ODM </a></li>
                            <!-- DESKTOP -->
                            <!-- <li class="hidden-xs"><a href="{{ route('blog_path') }}"> Resources </a></li> -->
                            <!-- DESKTOP -->
                            <li class="hidden-xs"><a href="{{ route('careers_path') }} "> Careers </a></li>
                            <!-- DESKTOP -->
                            <li class="hidden-xs"><a href="{{ route('contact_path') }} "> Contact us </a></li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Navbar Top -->




    <!-- Brand and toggle get grouped for better mobile display -->

    <div class="container">
        <div class="navbar-header w100" style="margin-left: 0px;">

            <button type="button" class="navbar-toggle collapsed" data-toggle="slide-collapse"
                data-target="#slide-navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand " href="/"> <img src="/images/logo.png" alt="Kamaplast"> </a>

            <div class="col-xs-12 col-md-8 col-sm-6 no-padding hidden-xs" id="search-bar-toggle">
                {!! Form::open([
                    'route' => 'search_result_path',
                    'id' => 'custom-search-input',
                    'class' => 'navbar-form no-padding',
                    'role' => 'search',
                ]) !!}
                <div class="input-group input-group-md">
                    <input type="text" class="form-control input-md" placeholder="What are you looking for?"
                        id="txt_search" name="txt_search" autocomplete="off">
                    <div class="input-group-btn">
                        <button class="btn btn-default btn-md" type="submit"><img src="/images/icons/search.svg"
                                class="img-responsive" width="22"></button>
                    </div>
                </div>
                <ul id="searchResult" style="display:none"></ul>
                {!! Form::close() !!}
            </div>

            <ul class="nav list-inline text-right usricons">
                @if (Auth::check())
                    <li class="dropdown">
                        <!-- FOR DESKTOP -->

                        <?php // get the first name from the full name
                        $tab = explode(' ', Auth::user()->name);
                        $firstname = $tab[0];
                        ?>

                        <a href="{{ route('user_account_path') }}" class="dropdown-toggle hidden-xs"
                            data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
                            style="padding-right: 5px;">
                            <span class="hidden-xs"><b><span class="hidden-md">Hi,</span> {{ $firstname }}</b> <b
                                    class="caret"></b></span>
                        </a>
                        <ul class="dropdown-menu" style="">
                            <li><a href="{{ route('user_account_path') }}/#info"> My Account</a></li>
                            <li><a href="{{ route('user_account_path') }}/#addresses"> My Addresses</a></li>
                            <li><a href="{{ route('user_account_path') }}/#history"> My Orders</a></li>
                            <li><a href="{{ url('/logout') }}"> Logout</a></li>
                        </ul>
                    </li>
                @else
                    <li>
                        <!-- FOR DESKTOP -->
                        <a href="/login" class="userprof hidden-xs"> <b>Login</b> </a>
                    </li>
                @endif
                <li style="border-left: 1px solid #eee;line-height: 40px;"><span class="hidden-xs">&thinsp;</span></li>

                @if (Auth::check())
                    <li class="dropdown">
                        <!-- FOR MOBILE -->
                        <a href="{{ route('user_account_path') }}" class="dropdown-toggle visible-xs"
                            data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
                            style="padding-right: 15px;">
                            <span class="visible-xs"><img src="/images/icons/avatar.svg"
                                    class="img-responsive visible-xs" width="20"
                                    style="position: relative; top:7px;"></span>
                        </a>
                        <ul class="dropdown-menu" style="position: absolute; left: -37px; top: 51px;">
                            <li><a href="{{ route('user_account_path') }}/#info"> My Account</a></li>
                            <li><a href="{{ route('user_account_path') }}/#addresses"> My Addresses</a></li>
                            <li><a href="{{ route('user_account_path') }}/#history"> My Orders</a></li>
                            <li><a href="{{ url('/logout') }}"> Logout</a></li>
                        </ul>
                    </li>
                @else
                    <li>
                        <!-- FOR MOBILE -->
                        <a href="/login" class="userprof visible-xs"> <img src="/images/icons/avatar.svg"
                                class="img-responsive visible-xs" width="20"
                                style="position: relative; top:7px;"> </a>
                    </li>
                @endif

                <li>
                    <!-- FOR MOBILE -->
                    <img src="/images/icons/search.svg" class="img-responsive visible-xs" width="20"
                        id="search-action" style="margin-right: 10px; position: relative; top:7px;">
                </li>


                <li>
                    <a id="js_cart" href="{{ route('cart_path') }}" style="padding-left: 5px;">
                        @if ($count != 0)
                            <!-- FOR DESKTOP -->
                            <span class="js_cart_nb cart_count hidden-xs">{{ $count }}</span>
                            <!-- FOR MOBILE -->
                            <span class="js_cart_nb cart_count_responsive visible-xs">{{ $count }}</span>
                        @endif
                        <!-- FOR DESKTOP -->
                        <img src="/images/icons/shopping-cart.svg" class="img-responsive hidden-xs" width="30">
                        <!-- FOR MOBILE -->
                        <img src="/images/icons/shopping-cart.svg" class="img-responsive visible-xs" width="20"
                            style="position: relative; top:7px;">
                    </a>
                </li>
            </ul>

        </div>
    </div>


    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse bg-black nav-down" id="slide-navbar-collapse">

        <div class="container no-padding">

            <ul class="nav navbar-nav">


                <!-- ============================== DESKTOP DYNAMIC MENU ======================== -->

                @foreach ($categories as $c)
                    @if ($c['parent_id'] == null || $c['parent_id'] == 0)
                        <?php //generate seo url link
                        $seo_link = ProductsController::generateSeoUrlLink($c['name'], $c['category_id']);
                        ?>

                        <li class="@if (!empty($c['children'])) dropdown megaDropMenu @endif hidden-xs">
                            <a @if (!empty($c['children'])) data-toggle="dropdown" class="dropdown-toggle" data-hover="dropdown" data-close-others="false" @endif
                                href="{{ route('product_list_path', $seo_link) }}"> {{ $c['name'] }} </a>
                            @if (!empty($c['children']))
                                <ul class="dropdown-menu row" style="padding:20px 40px;">
                                    <!-- LVL 2 -->
                                    @foreach ($c['children'] as $subCateg)
                                        <?php //generate seo url link
                                        $seo_link_sub = ProductsController::generateSeoUrlLink($subCateg['name'], $subCateg['category_id']);
                                        ?>

                                        <li class="col-sm-6 col-md-4">
                                            <ul>
                                                <?php
                                                if ($subCateg['img'] == null) {
                                                    $img = '/images/placeholder-image.png';
                                                } else {
                                                    $img = getenv('S3_URL') . '/categories/thumbs/' . $subCateg['img'];
                                                }
                                                ?>
                                                <li>
                                                    <img class="img_menu" src="{{ $img }}">
                                                    <a href="{{ route('product_list_path', $seo_link_sub) }}"
                                                        class="categ_style1 categ_a">{{ $subCateg['name'] }}</a>
                                                </li>

                                                @if (isset($subCateg['children']))
                                                    @foreach ($subCateg['children'] as $subSubCateg)
                                                        <?php //generate seo url link
                                                        $seo_link_sub_sub = ProductsController::generateSeoUrlLink($subSubCateg['name'], $subSubCateg['category_id']);
                                                        ?>

                                                        <li><a href="{{ route('product_list_path', $seo_link_sub_sub) }}"
                                                                class="categ_style2">{{ $subSubCateg['name'] }}</a>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </li>
                                    @endforeach

                                </ul>
                            @endif
                        </li>
                    @endif
                @endforeach


                <!-- ============================== MOBILE DYNAMIC MENU ======================== -->
                <!-- LEVEL 1 -->
                @foreach ($categories as $c)
                    @if ($c['parent_id'] == null || $c['parent_id'] == 0)
                        <?php //generate seo url link
                        $seo_link = ProductsController::generateSeoUrlLink($c['name'], $c['category_id']);
                        ?>

                        <li class="visible-xs dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle"
                                href="{{ route('product_list_path', $seo_link) }}"> {{ $c['name'] }} @if (!empty($c['children']))
                                    <b class="caret caret_sidebar"> </b>
                                @endif
                            </a>
                            @if (!empty($c['children']))
                                <ul class="dropdown-menu">
                                    <!-- LEVEL 2 -->
                                    @foreach ($c['children'] as $c2)
                                        <?php //generate seo url link
                                        $seo_link = ProductsController::generateSeoUrlLink($c2['name'], $c2['category_id']);
                                        ?>

                                        <li class="dropdown-submenu">
                                            <a @if (!empty($c2['children'])) data-toggle="dropdown" class="dropdown-toggle" @endif
                                                href="{{ route('product_list_path', $seo_link) }}">
                                                {{ $c2['name'] }} @if (!empty($c2['children']))
                                                    <b class="caret caret_sidebar"> </b>
                                                @endif
                                            </a>
                                            <ul class="dropdown-menu">
                                                @if (!empty($c2['children']))
                                                    <!-- LEVEL 3 -->
                                                    @foreach ($c2['children'] as $c3)
                                                        <?php //generate seo url link
                                                        $seo_link = ProductsController::generateSeoUrlLink($c3['name'], $c3['category_id']);
                                                        ?>

                                                        <li><a
                                                                href="{{ route('product_list_path', $seo_link) }}">{{ $c3['name'] }}</a>
                                                        </li>
                                                    @endforeach
                                                    <li class="divider"></li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endif
                @endforeach


                <!-- MOBILE -->
                <li class="{{ Route::currentRouteName() == 'about_path' ? 'active' : '' }} visible-xs"><a
                        href="{{ route('about_path') }}"> Company </a></li>

                <!-- MOBILE -->
                <li class="{{ Route::currentRouteName() == 'odm_path' ? 'active' : '' }} visible-xs"><a
                        href="{{ route('odm_path') }}"> ODM </a></li>

                <!-- MOBILE -->
                <li class="{{ Route::currentRouteName() == 'contact_path' ? 'active' : '' }} visible-xs"><a
                        href="{{ route('contact_path') }} "> Contact </a></li>


                <li class="divider visible-xs"></li>

                @if (Auth::check())
                    <li class="visible-xs"><a href="{{ route('user_account_path') }}"> {{ Auth::user()->name }}
                            Profile</a></li>
                    <li class="visible-xs"><a href="{{ url('/logout') }}"> Logout</a></li>
                @else
                    <li class="visible-xs">
                        <a href="/login"> Login </a>
                    </li>
                @endif

                <li class="divider visible-xs"></li>

            </ul>

        </div>

        <ul class="list-inline visible-xs">

            <li>
                <a href="hhttps://www.instagram.com/kamaplast/" target="_blank" rel="noopener" title="Instagram"
                    data-placement="top" data-toggle="tooltip">
                    <i class="fa fa-instagram fa-lg"> &nbsp; </i>
                </a>
            </li>

            <li>
                <a href="https://www.facebook.com/KAMAPLAST.CO/" target="_blank" rel="noopener" title="Facebook"
                    data-placement="top" data-toggle="tooltip">
                    <i class="fa fa-facebook fa-lg"> &nbsp; </i>
                </a>
            </li>

            <li>
                <a href="https://www.linkedin.com/company/kamaplast-industrial-company/" target="_blank"
                    rel="noopener" title="Linkedin" data-placement="top" data-toggle="tooltip">
                    <i class="fa fa-linkedin fa-lg"> &nbsp; </i>
                </a>
            </li>

        </ul>

    </div>
    <!-- /.navbar-collapse -->

</nav>

<div class="menu-overlay"></div>

<script type="text/javascript">
    $("#search-action").click(function() {
        $("#search-bar-toggle").toggleClass("hidden-xs");
    });


    // bring the offset to the top of the page
    if (location.hash) {
        setTimeout(function() {
            window.scrollTo(0, 0);
        }, 50);
    }
</script>
