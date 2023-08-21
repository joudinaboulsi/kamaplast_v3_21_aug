 <?php $url = Request::path();?>

 <nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                        <?php 
                            if(Auth::user()->img != null)
                                $img = getenv('S3_URL').'/users/'.Auth::user()->img;
                            else
                                $img = 'cms/images/user.png';
                         ?>
                        <img class="img-circle" style="width:50px;" alt="image" src="{{$img}}" />
                         </span><br><br>
                    <a data-toggle="dropdown" class="dropdown-toggle">
                        <span class="clear"> <span class="block m-t-xs"> Hello  <strong class="font-bold">{{Auth::user()->name}}</strong>
                         </span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <!-- <li><a href="profile.html">Profile</a></li>
                        <li><a href="contacts.html">Contacts</a></li>
                        <li><a href="mailbox.html">Mailbox</a></li>
                        <li class="divider"></li> -->
                        <li><a href="{{ route('logout') }}">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                     <img class="img-circle" style="width:40px;" alt="image" src="{{$img}}" />
                </div>   
            </li>

            <li class="{{ ( ($url == 'categories') || ( $url == 'tags') || ($url == 'brands')) ? 'active' : '' }}">
                <a href="index.html"><i class="fa fa-table"></i> <span class="nav-label">Master Data</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li class="{{ ( ($url == 'categories')  ) ? 'active' : '' }} ">
                        <a href="{{ route('cms_category') }}"><i class="fa fa-handshake-o"></i> <span class="nav-label"><i class="fa fa-sitemap"></i> Categories</span></a>
                    </li>
                    <li class="{{ ( ($url == 'tags') ) ? 'active' : '' }} ">
                        <a href="{{ route('cms_tags') }}"><i class="fa fa-handshake-o"></i> <span class="nav-label"><i class="fa fa-tags"></i> Tags</span></a>
                    </li>
                    <li class="{{ ( ($url == 'brands') ) ? 'active' : '' }} ">
                        <a href="{{ route('cms_brands') }}"><i class="fa fa-handshake-o"></i> <span class="nav-label"><i class="fa fa-certificate"></i> Brands</span></a>
                    </li>
                </ul>
            </li>
            <li class="{{ ( ($url == 'users') || ( $url == 'add-users') || (strpos($url, 'user-')) !== false ) ? 'active' : '' }} ">
                <a href="{{ route('cms_users') }}"><i class="fa fa-handshake-o"></i> <span class="nav-label"><i class="fa fa-user"></i> Users</span></a>
            </li>
            <li class="{{ ( ($url == 'products') || ($url =='add-product') || (strpos($url, 'product-')) !== false ) ? 'active' : '' }} ">
                <a href="{{ route('cms_products') }}"><i class="fa fa-handshake-o"></i> <span class="nav-label"><i class="fa fa-diamond"></i> Products</span></a>
            </li>
            <li class="{{ ( ($url == 'promo-codes') || ( $url == 'add-promo-code') || (strpos($url, 'promo-code-')) !== false ) ? 'active' : '' }} ">
                <a href="{{ route('cms_promo_codes') }}"><i class="fa fa-handshake-o"></i> <span class="nav-label"><i class="fa fa-bullhorn"></i> Discounts</span></a>
            </li>
            <li class="{{ ( ($url == 'orders') || (strpos($url, 'order-details-')) !== false ) ? 'active' : '' }} ">
                <a href="{{ route('cms_orders') }}"><i class="fa fa-handshake-o"></i> <span class="nav-label"><i class="fa fa-bell"></i> Orders</span></a>
            </li>
            <li class="{{ ( ($url == 'cms-home') || ( $url == 'cms-about') || ($url == 'cms-media') || ($url == 'cms-tutorials') || (strpos($url, 'news-details-')) !== false || ($url == 'cms-careers')) ? 'active' : '' }}">
                <a href="index.html"><i class="fa fa-file"></i> <span class="nav-label">Pages</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li class="{{ ( ($url == 'cms-home')  ) ? 'active' : '' }} ">
                        <a href="{{ route('cms_home') }}"><i class="fa fa-handshake-o"></i> <span class="nav-label"><i class="fa fa-home"></i> Home</span></a>
                    </li>
                    <li class="{{ ( ($url == 'cms-media') || (strpos($url, 'news-details-')) !== false ) ? 'active' : '' }} ">
                        <a href="{{ route('cms_blog') }}"><i class="fa fa-handshake-o"></i> <span class="nav-label"><i class="fa fa-newspaper-o"></i> Blog</span></a>
                    </li>
                    <li class="{{ ( ($url == 'cms-about')  ) ? 'active' : '' }} ">
                        <a href="{{ route('cms_about') }}"><i class="fa fa-handshake-o"></i> <span class="nav-label"><i class="fa fa-info-circle"></i> About</span></a>
                    </li>
                    <li class="{{ ( ($url == 'cms-tutorials')  ) ? 'active' : '' }} ">
                        <a href="{{ route('cms_videos') }}"><i class="fa fa-handshake-o"></i> <span class="nav-label"><i class="fa fa-video-camera "></i> Tutorials</span></a>
                    </li>
                    <li class="{{ ( ($url == 'cms-careers')  ) ? 'active' : '' }} ">
                        <a href="{{ route('cms_careers') }}"><i class="fa fa-handshake"></i> <span class="nav-label"><i class="fa fa-briefcase"></i> Careers</span></a>
                    </li>

                </ul>
            </li>

            <li class="{{ ($url == 'seo') ? 'active' : '' }} ">
                <a href="{{ route('cms_seo') }}"><i class="fa fa-handshake-o"></i> <span class="nav-label"><i class="fa fa-search"></i> SEO</span></a>
            </li>

            <li class="{{ ($url == 'stock-integration') ? 'active' : '' }} ">
                <a href="{{ route('cms_stock_integration') }}"><i class="fa fa-handshake-o"></i> <span class="nav-label"><i class="fa fa-th"></i> Stock Integration</span></a>
            </li>
            
        </ul>
    </div>
</nav>
