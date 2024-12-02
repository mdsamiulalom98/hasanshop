<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>@yield('title') - {{$generalsetting->name}}</title>
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset($generalsetting->favicon)}}" alt="Websolution IT" />
        <meta name="author" content="Websolution IT" />
        <link rel="canonical" href="" />
        @stack('seo') @stack('css')
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/bootstrap.min.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/animate.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/all.min.css')}}" />
        <link rel="stylesheet" href="{{ asset('public/frontEnd/css/flipper.css') }}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/owl.carousel.min.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/owl.theme.default.min.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/mobile-menu.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/select2.min.css')}}" />
        <!-- toastr css -->
        <link rel="stylesheet" href="{{asset('public/backEnd/')}}/assets/css/toastr.min.css" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/wsit-menu.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/style.css?v=1.0.1')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/responsive.css?v=1.0.1')}}" />
        <script src="{{asset('public/frontEnd/js/jquery-3.7.1.min.js')}}"></script>
        @foreach($pixels as $pixel)
        <!-- Facebook Pixel Code -->
        <script>
            !(function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = "2.0";
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s);
            })(window, document, "script", "https://connect.facebook.net/en_US/fbevents.js");
            fbq("init", "{{{$pixel->code}}}");
            fbq("track", "PageView");
        </script>
        <noscript>
            <img height="1" width="1" style="display: none;" src="https://www.facebook.com/tr?id={{{$pixel->code}}}&ev=PageView&noscript=1" />
        </noscript>
        <!-- End Facebook Pixel Code -->
        @endforeach

        @foreach($gtm_code as $gtm)
        <!-- Google tag (gtag.js) -->
        <script>
            (function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({ "gtm.start": new Date().getTime(), event: "gtm.js" });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != "dataLayer" ? "&l=" + l : "";
                j.async = true;
                j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, "script", "dataLayer", "GTM-{{ $gtm->code }}");
        </script>
        <!-- End Google Tag Manager -->
        @endforeach
    </head>
    <body class="gotop">
        @if($coupon)
        <div  class="coupon-section alert alert-dismissible fade show" >
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="coupon-code">
                            <p>Get {{$coupon->amount}} {{$coupon->type == 1 ? "%" : "Tk"}} Discount use the coupon code <span id="couponCode">{{$coupon->coupon_code}}</span>
                            <button onclick="copyCouponCode()"> <i class="fas fa-copy"></i>
                            </button></p>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @php $subtotal = Cart::instance('shopping')->subtotal(); @endphp
        <div class="mobile-menu">
            <div class="mobile-menu-logo">
                <div class="logo-image">
                    <img src="{{asset($generalsetting->dark_logo)}}" alt="" />
                </div>
                <div class="mobile-menu-close">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <ul class="first-nav">
                @foreach($categories as $scategory)
                <li class="parent-category">
                    <a href="{{route('category',$scategory->slug)}}" class="menu-category-name">
                        <img src="{{asset($scategory->image)}}" alt="" class="side_cat_img" />
                        {{$scategory->name}}
                    </a>
                    @if($scategory->subcategories->count() > 0)
                    <span class="menu-category-toggle">
                        <i class="fa fa-caret-down"></i>
                    </span>
                    @endif
                    <ul class="second-nav" style="display: none;">
                        @foreach($scategory->subcategories as $subcategory)
                        <li class="parent-subcategory">
                            <a href="{{route('subcategory',$subcategory->slug)}}" class="menu-subcategory-name">{{$subcategory->name}}</a>
                            @foreach($subcategory->childcategories as $childcat)
                            <li class="childcategory"><a href="{{route('products',$childcat->slug)}}" class="menu-childcategory-name">{{$childcat->name}}</a></li>
                            @endforeach
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endforeach
            </ul>
            <div class="mobilemenu-bottom">
                <ul>
                    @if(Auth::guard('customer')->user())
                    <li class="for_order">
                        <a href="{{route('customer.account')}}">
                            <i class="fa-regular fa-user"></i>
                            {{Str::limit(Auth::guard('customer')->user()->name,14)}}
                        </a>
                    </li>
                    @else
                    <li class="for_order">
                        <a href="{{route('customer.login')}}">Login / Sign Up</a>
                    </li>
                 @endif
                 <li>
                      <a href="{{route('customer.order_track')}}"> Order Track </a>
                   </li>
                 <li>
                      <a href="{{route('coupon.view')}}">Coupon </a>
                   </li>
                 <li>
                      <a href="{{route('contact')}}">Contact Us </a>
                   </li>
                </ul>
            </div>
        </div>
         <header id="navbar_top">
            <!-- mobile header start -->
            <div class="mobile-header sticky">
                <div class="mobile-logo">
                    <div class="menu-bar">
                        <a class="toggle">
                            <i class="fa-solid fa-bars"></i>
                        </a>
                    </div>
                    <div class="menu-logo">
                        <a href="{{route('home')}}"><img src="{{asset($generalsetting->dark_logo)}}" alt="" /></a>
                    </div>
                    <div class="menu-bag">
                        <a href="{{route('customer.checkout')}}" class="margin-shopping">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span class="mobilecart-qty">{{Cart::instance('shopping')->count()}}</span>
                        </a>
                    </div>
                </div>
            </div>
             <div class="mobile-search main-search">
                <form action="{{route('search')}}">
                    <button><i data-feather="search"></i></button>
                    <input type="text" placeholder="Search Product..." class="search_keyword search_click" name="keyword" />
                </form>
                <div class="search_result"></div>
            </div>
            <!-- mobile header end -->

            <!-- Top-bar-start -->
            <div class="top-bar">
                <div class="container-fluid desktop-c">
                    <div class="main-topbar">
                        <div class="topbar-left">
                            <a title="Welcome to Worldwide Electronics Store" href="#">Welcome to Sellpixer Website
                                Store</a>
                        </div>
                        <div class="topbar-right">
                            <ul>
                                <li>
                                    <a title="Track Your Order" href="{{ route('customer.order_track') }}"
                                        class="right-border"><i class="fa-solid fa-truck-fast"></i>Track Your Order</a>
                                </li>
                                <!-- <li>
                                    <a title="Shop" href="" class="right-border"><i
                                            class="fa-solid fa-bag-shopping"></i>Shop</a>
                                </li> -->
                                
                                <!-- @if (Auth::guard('customer')->user())
                                    <li class="for_order">
                                        <p>
                                            <a href="{{ route('customer.account') }}">
                                                <i class="fa-regular fa-user"></i>

                                                {{ Str::limit(Auth::guard('customer')->user()->name, 10) }}
                                            </a>
                                        </p>
                                    </li>
                                @else
                                    <li>
                                        <a title="My Account" href="{{ route('customer.login') }}"><i
                                                class="fa-regular fa-user"></i>My Account</a>
                                    </li>
                                @endif -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Top-bar-end -->

            <!-- main header start -->
            <div class="main-header">
                <div class="logo-area">
                    <div class="container-fluid desktop-c">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="logo-header">
                                    <div class="main-logo">
                                        <a href="{{route('home')}}"><img src="{{asset($generalsetting->dark_logo)}}" alt="" /></a>
                                    </div>
                                    <!-- menu bar start -->
                                    <!-- <div class="web-dot menu-bar">
                                        <a class="btn btn-white" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                                            <i class="fa-solid fa-bars"></i>
                                        </a>

                                        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                                            <div class="offcanvas-header"></div>
                                            <div class="offcanvas-body">
                                                <div class="mobile-menu mobile-menus active">
                                                    <div class="mobile-menu-logo">
                                                        <div class="logo-image">
                                                            <a href="{{ route('home') }}"> <img src="{{ asset($generalsetting->white_logo) }}" alt="" /></a>
                                                        </div>
                                                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                                    </div>
                                                    <ul class="first-nav">
                                                        @foreach ($categories as $scategory)
                                                        <li class="parent-category">
                                                            <a href="{{ url('category/' . $scategory->slug) }}" class="menu-category-name">
                                                                <img src="{{ asset($scategory->icon) }}" alt="" class="side_cat_img" />
                                                                {{ $scategory->name }}
                                                            </a>
                                                            @if ($scategory->subcategories->count() > 0)
                                                            <span class="menu-category-toggle">
                                                                <i class="fa fa-chevron-down"></i>
                                                            </span>
                                                            @endif
                                                            <ul class="second-nav" style="display: none;">
                                                                @foreach ($scategory->subcategories as $subcategory)
                                                                <li class="parent-subcategory">
                                                                    <a href="{{ url('subcategory/' . $subcategory->slug) }}" class="menu-subcategory-name">{{ $subcategory->subcategoryName }}</a>
                                                                    @if ($subcategory->childcategories->count() > 0)
                                                                    <span class="menu-subcategory-toggle"><i class="fa fa-chevron-down"></i></span>
                                                                    @endif
                                                                    <ul class="third-nav" style="display: none;">
                                                                        @foreach ($subcategory->childcategories as $childcat)
                                                                        <li class="childcategory"><a href="{{ url('products/' . $childcat->slug) }}" class="menu-childcategory-name">{{ $childcat->childcategoryName }}</a></li>
                                                                        @endforeach
                                                                    </ul>
                                                                </li>
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <!-- menu bar end -->

                                    <div class="main-search">
                                        <form action="{{route('search')}}">
                                            <button><i data-feather="search"></i></button>
                                            <input type="text" placeholder="Search Product..." class="search_keyword search_click" name="keyword" />
                                        </form>
                                        <div class="search_result"></div>
                                    </div>
                                    <div class="header-list-items">
                                        <ul>
                                            <li class="main-menu-li">
                                                <a href="{{route ('compare.show')}}" class="main-menu-link">
                                                    <p class="margin-shopping">
                                                        <i class="fa-solid fa-code-compare"></i>
                                                        @if (Cart::instance('compare')->count() > 0)
                                                            <span class="compare-qty">{{Cart::instance('compare')->count()}}</span>
                                                        @endif
                                                        <div class="hover-des">
                                                            Compare
                                                        </div>
                                                    </p>
                                                </a>
                                            </li>
                                            <li class="wish-dialog">
                                                <a href="{{route ('wishlist.show')}}">
                                                    <p class="margin-shopping">
                                                        <i class="fa-solid fa-heart"></i>
                                                        <span class="wish-qty">{{ Cart::instance('wishlist')->count() }}</span>
                                                        <div class="hover-des">
                                                            Wishlist
                                                        </div>
                                                    </p>
                                                </a>
                                            </li>
                                            
                                            @if(Auth::guard('customer')->user())
                                            <li class="for_order">
                                                <p>
                                                    <a href="{{route('customer.account')}}">
                                                        <i class="fa-regular fa-user"></i>
                                                    </a>
                                                    <div class="hover-des">
                                                        My Account
                                                    </div>
                                                </p>
                                            </li>
                                            @else
                                            <li class="for_order">
                                                <p>
                                                    <a href="{{route('customer.login')}}">
                                                        <i class="fa-regular fa-user"></i>
                                                    </a>
                                                    <div class="hover-des">
                                                        Login/Registration
                                                    </div>  
                                                </p>
                                            </li>
                                            @endif

                                            <li class="cart-dialog" id="cart-qty">
                                                <a href="{{route('customer.checkout')}}">
                                                    <p class="margin-shopping">
                                                        <i class="fa-solid fa-cart-shopping"></i>
                                                        <span>{{Cart::instance('shopping')->count()}}</span>
                                                    </p>
                                                </a>
                                                <div class="cshort-summary">
                                                    <ul>
                                                        @foreach(Cart::instance('shopping')->content() as $key=>$value)
                                                        <li>
                                                            <a href=""><img src="{{asset($value->options->image)}}" alt="" /></a>
                                                        </li>
                                                        <li><a href="">{{Str::limit($value->name, 30)}}</a></li>
                                                        <li>Qty: {{$value->qty}}</li>
                                                        <li>
                                                            <p>৳{{$value->price}}</p>
                                                            <button class="remove-cart cart_remove" data-id="{{$value->rowId}}"><i data-feather="x"></i></button>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                    <p><strong>SubTotal : ৳{{$subtotal}}</strong></p>
                                                    <a href="{{route('customer.checkout')}}" class="go_cart">Process To Order </a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- logo area end -->
                <!-- MASTER MENU DESIGN CODE START -->
                <div class="master-menu">
                    <div class="container-fluid desktop-c">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="catagory_menu">
                                    <ul>
                                        <li id="cat_bar" class="cat_bar {{ request()->is('/') ? 'active' : '' }}">
                                            <a href="javascript:void(0);">
                                                <i class="fa-solid fa-bars"></i>
                                                <span class="cat_head">All Departments</span>
                                                <i class="fa-solid fa-angle-down cat_down"></i>
                                            </a>
                                        </li>
                                        <!-- MENU AREA DESIGN CODE START -->
                                        <div class="menu-area" style="display: none;">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="main-menu">
                                                            <ul>
                                                                @foreach ($categories as $category)
                                                                <li>
                                                                    <a href="{{route('category',$category->slug)}}">
                                                                        {{$category->name}}
                                                                        @if ($category->subcategories->count() > 0)
                                                                            <i class="fa-solid fa-angle-down cat_down"></i>
                                                                        @endif
                                                                    </a>
                                                                    @if($category->subcategories->count() > 0)
                                                                    <div class="mega_menu">
                                                                        @foreach ($category->subcategories as $subcat)
                                                                        <ul>
                                                                            <li>
                                                                                <a href="{{ route('subcategory',$subcat->slug) }}" class="cat-title">
                                                                                   {{ Str::limit($subcat->name, 25) }}
                                                                                </a>
                                                                            </li>
                                                                            @foreach($subcat->childcategories as $childcat)
                                                                            <li>
                                                                                <a href="{{ route('products',$childcat->slug) }}">{{ $childcat->name }}</a>
                                                                            </li>
                                                                            @endforeach
                                                                        </ul>
                                                                        @endforeach
                                                                    </div>
                                                                    @endif
                                                                </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- MENU AREA DESIGN CODE END -->


                                        <li class="page-cat"><a href="{{ route('home') }}">Home</a></li>
                                        <li class="page-cat"><a href="">About Us</a></li>
                                        <li class="page-cat"><a href="{{ route('feature_product') }}">Featured Product </a></li>
                                        <li class="page-cat"><a href="{{ route('toprated') }}">Top Rated</a></li>
                                        <li class="page-cat"><a href="{{ route('on_sale') }}">On Sale</a></li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- MASTER MENU DESIGN CODE END -->
  
                </div>
            <!-- main-header end -->
        </header>
        <div id="content">
            @yield('content')
        </div>
        <!-- content end -->
        <!-- FOOTER TOP SECTION START -->
        <section class="multi-products">
            <div class="container-fluid desktop-c">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="feature-header">
                            <h5>Featured Products</h5>
                        </div>
                        @foreach ($feature_products as $key => $value)
                            <div class="show-product">
                                <div class="feature-img">
                                    <a href="{{ route('product', $value->slug) }}">
                                        <img src="{{ asset($value->image ? $value->image->image : '') }}" />
                                    </a>
                                </div>
                                <div class="feature-des">
                                    <div class="spro_name">
                                        <a
                                            href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 50) }}</a>
                                    </div>
                                    <div class="feature-ratting">
                                        @if ($value->reviews)
                                            <div class="details-ratting-wrap mreview_pro">
                                                @php
                                                    $averageRating = $value->reviews->avg('ratting');
                                                    $filledStars = floor($averageRating);
                                                    $emptyStars = 5 - $filledStars;
                                                @endphp

                                                @if ($averageRating >= 0 && $averageRating <= 5)
                                                    @for ($i = 1; $i <= $filledStars; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor

                                                    @if ($averageRating == $filledStars)
                                                        {{-- If averageRating is an integer, don't display half star --}}
                                                    @else
                                                        <i class="far fa-star-half-alt"></i>
                                                    @endif

                                                    @for ($i = 1; $i <= $emptyStars; $i++)
                                                        <i class="far fa-star"></i>
                                                    @endfor

                                                    <span> ({{ number_format($averageRating, 0) }})</span>
                                                @else
                                                    <span>Invalid rating range</span>
                                                @endif

                                            </div>
                                        @endif
                                    </div>
                                    <div class="feature-price">
                                        <p>
                                            ৳ {{ $value->new_price }} @if ($value->old_price)
                                            @endif
                                            @if ($value->whole_sell_price)
                                                <strong>৳ {{ $value->whole_sell_price }}</strong>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- end-col -->
                    </div>
                    <!-- end-col -->
                    <div class="col-sm-3">
                        <div class="feature-header">
                            <h5>Top Selling Products</h5>
                        </div>
                        @foreach ($toprateds as $key => $value)
                            <div class="show-product">
                                <div class="feature-img">
                                    <a href="{{ route('product', $value->slug) }}">
                                        <img src="{{ asset($value->image ? $value->image->image : '') }}" />
                                    </a>
                                </div>
                                <div class="feature-des">
                                    <div class="spro_name">
                                        <a
                                            href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 50) }}</a>
                                    </div>
                                    <div class="feature-ratting">
                                        @if ($value->reviews)
                                            <div class="details-ratting-wrap mreview_pro">
                                                @php
                                                    $averageRating = $value->reviews->avg('ratting');
                                                    $filledStars = floor($averageRating);
                                                    $emptyStars = 5 - $filledStars;
                                                @endphp

                                                @if ($averageRating >= 0 && $averageRating <= 5)
                                                    @for ($i = 1; $i <= $filledStars; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor

                                                    @if ($averageRating == $filledStars)
                                                        {{-- If averageRating is an integer, don't display half star --}}
                                                    @else
                                                        <i class="far fa-star-half-alt"></i>
                                                    @endif

                                                    @for ($i = 1; $i <= $emptyStars; $i++)
                                                        <i class="far fa-star"></i>
                                                    @endfor

                                                    <span> ({{ number_format($averageRating, 0) }})</span>
                                                @else
                                                    <span>Invalid rating range</span>
                                                @endif

                                            </div>
                                        @endif
                                    </div>
                                    <div class="feature-price">
                                        <p>৳ {{ $value->new_price }} @if ($value->old_price)
                                            @endif
                                            @if ($value->whole_sell_price)
                                                <strong>৳ {{ $value->whole_sell_price }}</strong>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- end-col -->
                    </div>
                    <!-- end-col -->
                    <div class="col-sm-3">
                        <div class="feature-header">
                            <h5>On-sale Products</h5>
                        </div>
                        @foreach ($on_sales as $key => $value)
                            <div class="show-product">
                                <div class="feature-img">
                                    <a href="{{ route('product', $value->slug) }}">
                                        <img src="{{ asset($value->image ? $value->image->image : '') }}" />
                                    </a>
                                </div>
                                <div class="feature-des">
                                    <div class="spro_name">
                                        <a
                                            href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 50) }}</a>
                                    </div>
                                    <div class="feature-ratting">
                                        @if ($value->reviews)
                                            <div class="details-ratting-wrap mreview_pro">
                                                @php
                                                    $averageRating = $value->reviews->avg('ratting');
                                                    $filledStars = floor($averageRating);
                                                    $emptyStars = 5 - $filledStars;
                                                @endphp

                                                @if ($averageRating >= 0 && $averageRating <= 5)
                                                    @for ($i = 1; $i <= $filledStars; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor

                                                    @if ($averageRating == $filledStars)
                                                        {{-- If averageRating is an integer, don't display half star --}}
                                                    @else
                                                        <i class="far fa-star-half-alt"></i>
                                                    @endif

                                                    @for ($i = 1; $i <= $emptyStars; $i++)
                                                        <i class="far fa-star"></i>
                                                    @endfor

                                                    <span> ({{ number_format($averageRating, 0) }})</span>
                                                @else
                                                    <span>Invalid rating range</span>
                                                @endif

                                            </div>
                                        @endif
                                    </div>
                                    <div class="feature-price">
                                        <p>৳ {{ $value->new_price }} @if ($value->old_price)
                                            @endif
                                            @if ($value->whole_sell_price)
                                                <strong>৳ {{ $value->whole_sell_price }}</strong>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- end-col -->
                    </div>
                    <!-- end-col -->
                    <div class="col-sm-3">
                        @foreach ($footertopads as $key => $value)
                            <div class="big-feature-img">
                                <a href="{{ $value->link }}">
                                    <img src="{{ asset($value->image) }}"
                                        alt="footertopads image {{ $key + 1 }}" />
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
       <!--  === FOOTER TOP SECTION END === -->
       
        <!-- === MAIN FOOTER SECTION START === -->
        <footer>
            <!-- === NEWSLETTER SECTION START ==== -->
       <div class="newsletter">
            <div class="container-fluid desktop-c">
                <div class="footer-head">
                    <div class="newslater-section">
                        <div class="newsletter-logo">
                            <img src="{{ asset('public/frontEnd/images/6.png') }}" alt="" />
                        </div>
                        <div class="newsletter-sign">
                            <h5 class="newsletter-title">Sign up to Newsletter</h5>
                        </div>
                    </div>
                    <div class="neswletter-des">
                        <span class="newsletter-marketing-text">...and receive <strong>৳ 20 coupon for first
                                shopping</strong></span>
                    </div>
                    <div class="neswletter-search">
                        <ul class="social_links">
                            <div class="footer-search">
                                <form action="{{ route('search') }}">
                                    <input type="email" placeholder=" Enter Your Email Address " value=""
                                        class="search_keyword search_click" id="siginup" name="keyword" />
                                    <button>
                                        <a href="">SignUp</a>
                                    </button>
                                </form>
                                <div class="search_result"></div>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
       <!-- === NEWSLETTER SECTION END ==== -->
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="footer-about">
                                <a href="{{route('home')}}">
                                    <img src="{{asset($generalsetting->dark_logo)}}" alt="" />
                                </a>
                                <p>{{$contact->address}}</p>
                                <p><a href="tel:{{$contact->hotline}}" class="footer-hotlint">{{$contact->hotline}}</a></p>
                                <p><a href="mailto:{{$contact->hotmail}}" class="footer-hotlint">{{$contact->hotmail}}</a></p>
                            </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-3">
                            <div class="footer-menu">
                                <ul>
                                    <li class="title "><a>Useful Link</a></li>
                                    @foreach($pages as $page)
                                    <li><a href="{{route('page',['slug'=>$page->slug])}}">{{$page->name}}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-2">
                            <div class="footer-menu">
                                <ul>
                                    <li class="title"><a>Customer Link</a></li>
                                    <li><a href="{{route('customer.register')}}">Register</a></li>
                                    <li><a href="{{route('customer.login')}}">Login</a></li>
                                    <li><a href="{{route('customer.forgot.password')}}">Forgot Password?</a></li>
                                    <li><a href="{{route('contact')}}">Contact</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- col end -->
                        <div class="col-sm-3">
                            <div class="footer-menu">
                                <ul>
                                    <li class="title text-center"><a>Follow Us</a></li>
                                </ul>
                                <ul class="social_link">
                                    @foreach($socialicons as $value)
                                    <li>
                                        <a  href="{{$value->link}}"><i class="{{$value->icon}}"></i></a>
                                    </li>
                                    @endforeach
                                </ul>
                                <ul>
                                    <li class="title text-center mb-0"><a class="mb-0">Delivery Partner</a></li>
                                    <li class="delivery-partner">
                                        <img src="{{asset('public/frontEnd/images/delivery-partner.png')}}" alt="">
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- col end -->
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="copyright">
                                <p>Copyright © {{ date('Y') }} {{$generalsetting->name}}. All rights reserved. Developed By <a href="https://websolutionit.com">Websolution IT</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!--=== MAIN FOOTER SECTION END  ==-->
        <div class="fixed_whats">
            <a href="https://api.whatsapp.com/send?phone={{$contact->whatsapp}}" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
        </div>

        <div class="scrolltop" style="">
            <div class="scroll">
                <i class="fa fa-angle-up"></i>
            </div>
        </div>

        <!-- /. fixed sidebar -->

        <div id="custom-modal"></div>
        <div id="page-overlay"></div>
        <div id="loading"><div class="custom-loader"></div></div>
        <script src="{{asset('public/frontEnd/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('public/frontEnd/js/owl.carousel.min.js')}}"></script>
        <script src="{{asset('public/frontEnd/js/mobile-menu.js')}}"></script>
        <script src="{{asset('public/frontEnd/js/wsit-menu.js')}}"></script>
        <script src="{{asset('public/frontEnd/js/mobile-menu-init.js')}}"></script>
        <script src="{{asset('public/frontEnd/js/wow.min.js')}}"></script>
         <!-- feather icon -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
        <script>
            feather.replace();
        </script>
        <script src="{{asset('public/frontEnd/js/script.js')}}"></script>
        <script>
            new WOW().init();
        </script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


        <script src="{{asset('public/backEnd/')}}/assets/js/toastr.min.js"></script>
        {!! Toastr::message() !!} @stack('script')
        <script>
            $(".quick_view").on("click", function () {
                var id = $(this).data("id");
                $("#loading").show();
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{route('quickview')}}",
                        success: function (data) {
                            if (data) {
                                $("#custom-modal").html(data);
                                $("#custom-modal").show();
                                $("#loading").hide();
                                $("#page-overlay").show();
                            }
                        },
                    });
                }
            });
        </script>
        <!-- quick view end -->
        <!-- cart js start -->
        <script>
            $(".addcartbutton").on("click", function () {
                var id = $(this).data("id");
                var qty = 1;
                if (id) {
                    $.ajax({
                        cache: "false",
                        type: "GET",
                        url: "{{url('add-to-cart')}}/" + id + "/" + qty,
                        dataType: "json",
                        success: function (data) {
                            if (data) {
                                toastr.success("Success", "Product add to cart successfully");
                                return cart_count() + mobile_cart();
                            }
                        },
                    });
                }
            });
            $(".cart_store").on("click", function () {
                var id = $(this).data("id");
                var qty = $(this).parent().find("input").val();
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id, qty: qty ? qty : 1 },
                        url: "{{route('cart.store')}}",
                        success: function (data) {
                            if (data) {
                                toastr.success("Success", "Product add to cart succfully");
                                return cart_count() + mobile_cart();
                            }
                        },
                    });
                }
            });

            $(".cart_remove").on("click", function () {
                var id = $(this).data("id");
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{route('cart.remove')}}",
                        success: function (data) {
                            if (data) {
                                $(".cartlist").html(data);
                                return cart_count() + mobile_cart() + cart_summary();
                            }
                        },
                    });
                }
            });

            $(".cart_increment").on("click", function () {
                var id = $(this).data("id");
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{route('cart.increment')}}",
                        success: function (data) {
                            if (data) {
                                $(".cartlist").html(data);
                                return cart_count() + mobile_cart();
                            }
                        },
                    });
                }
            });

            $(".cart_decrement").on("click", function () {
                var id = $(this).data("id");
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{route('cart.decrement')}}",
                        success: function (data) {
                            if (data) {
                                $(".cartlist").html(data);
                                return cart_count() + mobile_cart();
                            }
                        },
                    });
                }
            });

            function cart_count() {
                $.ajax({
                    type: "GET",
                    url: "{{route('cart.count')}}",
                    success: function (data) {
                        if (data) {
                            $("#cart-qty").html(data);
                        } else {
                            $("#cart-qty").empty();
                        }
                    },
                });
            }
            function mobile_cart() {
                $.ajax({
                    type: "GET",
                    url: "{{route('mobile.cart.count')}}",
                    success: function (data) {
                        if (data) {
                            $(".mobilecart-qty").html(data);
                        } else {
                            $(".mobilecart-qty").empty();
                        }
                    },
                });
            }
            function cart_summary() {
                $.ajax({
                    type: "GET",
                    url: "{{route('shipping.charge')}}",
                    dataType: "html",
                    success: function (response) {
                        $(".cart-summary").html(response);
                    },
                });
            }
        </script>
        <!-- cart js end -->
        <script>
            $(".search_click").on("keyup change", function () {
                var keyword = $(".search_keyword").val();
                $.ajax({
                    type: "GET",
                    data: { keyword: keyword },
                    url: "{{route('livesearch')}}",
                    success: function (products) {
                        if (products) {
                            $(".search_result").html(products);
                        } else {
                            $(".search_result").empty();
                        }
                    },
                });
            });
            $(".msearch_click").on("keyup change", function () {
                var keyword = $(".msearch_keyword").val();
                $.ajax({
                    type: "GET",
                    data: { keyword: keyword },
                    url: "{{route('livesearch')}}",
                    success: function (products) {
                        if (products) {
                            $("#loading").hide();
                            $(".search_result").html(products);
                        } else {
                            $(".search_result").empty();
                        }
                    },
                });
            });
        </script>
        <!-- search js start -->
        <script></script>
        <script></script>
        <script>
            $(".district").on("change", function () {
                var id = $(this).val();
                $.ajax({
                    type: "GET",
                    data: { id: id },
                    url: "{{route('districts')}}",
                    success: function (res) {
                        if (res) {
                            $(".area").empty();
                            $(".area").append('<option value="">Select..</option>');
                            $.each(res, function (key, value) {
                                $(".area").append('<option value="' + key + '" >' + value + "</option>");
                            });
                        } else {
                            $(".area").empty();
                        }
                    },
                });
            });
        </script>
        <script>
            $(".toggle").on("click", function () {
                $("#page-overlay").show();
                $(".mobile-menu").addClass("active");
            });

            $("#page-overlay").on("click", function () {
                $("#page-overlay").hide();
                $(".mobile-menu").removeClass("active");
                $(".feature-products").removeClass("active");
            });

            $(".mobile-menu-close").on("click", function () {
                $("#page-overlay").hide();
                $(".mobile-menu").removeClass("active");
            });

            $(".mobile-filter-toggle").on("click", function () {
                $("#page-overlay").show();
                $(".feature-products").addClass("active");
            });
        </script>
        <script>
            $(document).ready(function () {
                $(".parent-category").each(function () {
                    const menuCatToggle = $(this).find(".menu-category-toggle");
                    const secondNav = $(this).find(".second-nav");

                    menuCatToggle.on("click", function () {
                        menuCatToggle.toggleClass("active");
                        secondNav.slideToggle("fast");
                        $(this).closest(".parent-category").toggleClass("active");
                    });
                });
                $(".parent-subcategory").each(function () {
                    const menuSubcatToggle = $(this).find(".menu-subcategory-toggle");
                    const thirdNav = $(this).find(".third-nav");

                    menuSubcatToggle.on("click", function () {
                        menuSubcatToggle.toggleClass("active");
                        thirdNav.slideToggle("fast");
                        $(this).closest(".parent-subcategory").toggleClass("active");
                    });
                });
            });
        </script>


        <script>
            $('.compare_store').on('click', function() {
                var id = $(this).data('id');
                var qty = 1;
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: {
                            'id': id,
                            'qty': qty ? qty : 1
                        },
                        url: "{{ route('compare.store') }}",
                         dataType: "json",
                        success: function(data) {
                            if (data) {
                                toastr.success('success', 'Product added in compare');
                                return compare_count()+compare();
                            }
                        }
                    });
                }
            });
            $('.compare_remove').on('click', function() {
                var id = $(this).data('id');
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: {
                            'id': id
                        },
                        url: "{{ route('compare.remove') }}",
                        success: function(data) {
                            if (data) {
                                return compare_count()+compare();
                            }
                        }
                    });
                }
            });
            function compare_count() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('compare.count') }}",
                    dataType: "html",
                    success: function(data) {
                        if (data) {
                            $(".compare-qty").html(data);
                        } else {
                            $(".compare-qty").empty();
                        }
                    }
                });
            };
            function compare() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('compare.summary') }}",
                    dataType: "html",
                    success: function(data) {
                        if (data) {
                            $("#compare").html(data);
                        } else {
                            $("#compare").empty();
                        }
                    }
                });
            };
        </script>
        <!--Compare js -->
        <script>
            $('.wishlist_store').on('click', function() {
                var id = $(this).data('id');
                var qty = 1;
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: {
                            'id': id,
                            'qty': qty ? qty : 1
                        },
                        url: "{{ route('wishlist.store') }}",
                         dataType: "json",
                        success: function(data) {
                            if (data) {
                                toastr.success('success', 'Product added in wishlist');
                                return wishlist_count()+wishlist();
                            }
                        }
                    });
                }
            });
            $('.wishlist_remove').on('click', function() {
                var id = $(this).data('id');
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: {
                            'id': id
                        },
                        url: "{{ route('wishlist.remove') }}",
                        success: function(data) {
                            if (data) {
                                return wishlist_count()+wishlist();
                            }
                        }
                    });
                }
            });
            function wishlist_count() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('wishlist.count') }}",
                    dataType: "html",
                    success: function(data) {
                        if (data) {
                            $(".wish-qty").html(data);
                        } else {
                            $(".wish-qty").empty();
                        }
                    }
                });
            };
            function wishlist() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('wishlist.summary') }}",
                    dataType: "html",
                    success: function(data) {
                        if (data) {
                            $("#wishlist").html(data);
                        } else {
                            $("#wishlist").empty();
                        }
                    }
                });
            };
        </script>
        <!--Compare js -->

        <script>
            var menu = new MmenuLight(document.querySelector("#menu"), "all");

            var navigator = menu.navigation({
                selectedClass: "Selected",
                slidingSubmenus: true,
                // theme: 'dark',
                title: "ক্যাটাগরি",
            });

            var drawer = menu.offcanvas({
                // position: 'left'
            });
            document.querySelector('a[href="#menu"]').addEventListener("click", (evnt) => {
                evnt.preventDefault();
                drawer.open();
            });
        </script>

        <script>
            $(window).scroll(function () {
                if ($(this).scrollTop() > 50) {
                    $(".scrolltop:hidden").stop(true, true).fadeIn();
                } else {
                    $(".scrolltop").stop(true, true).fadeOut();
                }
            });
            $(function () {
                $(".scroll").click(function () {
                    $("html,body").animate({ scrollTop: $(".gotop").offset().top }, "1000");
                    return false;
                });
            });
        </script>
        <script>
            $(".filter_btn").click(function () {
                $(".filter_sidebar").addClass("active");
                $("body").css("overflow-y", "hidden");
            });
            $(".filter_close").click(function () {
                $(".filter_sidebar").removeClass("active");
                $("body").css("overflow-y", "auto");
            });
        </script>

        <script>
            $(document).ready(function () {
                $(".logoslider").owlCarousel({
                    margin: 0,
                    loop: true,
                    dots: false,
                    nav: false,
                    autoplay: true,
                    autoplayTimeout: 6000,
                    animateOut: "fadeOut",
                    animateIn: "fadeIn",
                    smartSpeed: 3000,
                    autoplayHoverPause: true,
                    responsiveClass: true,
                    responsive: {
                        0: {
                            items: 1,
                            nav: false,
                            dots: false,
                        },
                        600: {
                            items: 1,
                            nav: false,
                            dots: false,
                        },
                        1000: {
                            items: 1,
                            nav: false,
                            loop: true,
                            dots: false,
                        },
                    },
                });
            });
        </script>
        <script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>

        <!-- Google Tag Manager (noscript) -->
        @foreach($gtm_code as $gtm)
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-{{$gtm->code}}" height="0" width="0" style="display: none; visibility: hidden;"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        @endforeach

        <script>
            function copyCouponCode() {
                var couponCode = document.getElementById("couponCode").innerText;
                var tempInput = document.createElement("input");
                tempInput.value = couponCode;
                document.body.appendChild(tempInput);
                tempInput.select();
                tempInput.setSelectionRange(0, 99999);
                document.execCommand("copy");
                document.body.removeChild(tempInput);
                toastr.success('Coupon Code copied successfully!');
            }
        </script>
        <script>
            function copyResellerCode() {
                var resellercode = document.getElementById("resellercode").innerText;
                var tempInput = document.createElement("input");
                tempInput.value = resellercode;
                document.body.appendChild(tempInput);
                tempInput.select();
                tempInput.setSelectionRange(0, 99999);
                document.execCommand("copy");
                document.body.removeChild(tempInput);
                toastr.success('Reseller Code copied successfully!');
            }
        </script>
        <script>
            $(document).ready(function() {
                $('#cat_bar').click(function() {
                    $('.menu-area').toggle();
                });
            });

        </script>

    </body>
</html>
