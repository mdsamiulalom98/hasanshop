@extends('frontEnd.layouts.master') 
@section('title', $generalsetting->meta_title) 
@push('seo')
<meta name="app-url" content="" />
<meta name="robots" content="index, follow" />
<meta name="description" content="{{$generalsetting->meta_description}}" />
<meta name="keywords" content="{{$generalsetting->meta_keyword}}" />
<!-- Open Graph data -->
<meta property="og:title" content="{{$generalsetting->meta_title}}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="" />
<meta property="og:image" content="{{ asset($generalsetting->white_logo) }}" />
<meta property="og:description" content="{{$generalsetting->meta_description}}" />
@endpush 
@push('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.carousel.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.theme.default.min.css') }}" />
@endpush 
@section('content')


    <!-- MENU AREA DESIGN CODE START -->
    <div class="menu-area">
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

<!-- SLIDER SECTION START -->
<section class="slider-section" style="background-image: url({{ asset($backgroundimg->image) }});">
    <div class="container-fluid desktop-c">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-10">
                <div class="home-slider-container">
                    <div class="main_slider owl-carousel">
                        @foreach ($sliders as $key => $value)
                            <div class="slider-item">
                               <div class="slider-des">
                                   <h5>{{$value->title}}</h5>
                                   <h4>{{$value->subtitle}}</h4>
                                   <a href="{{$value->link}}">Start Buying</a>
                               </div>
                               <div class="slider-img">
                                   <a href="{{$value->link}}">
                                        <img src="{{ asset($value->image) }}" alt="slider image {{$key+1}}" />
                                   </a>
                               </div>
                            </div>
                            <!-- slider item -->
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- SLIDER SECTION END -->

<!-- SHOP BANNER SECTION START -->
<!-- shop-banner section start -->
<section class="shop-banner">
    <div class="container-fluid desktop-c">
        <div class="row">
            <div class="col-sm-12">
                <div class="shop-banner-slider owl-carousel">
                    @foreach ($sliderbottomads as $key => $value)
                    <div class="shop-item">
                        <a href="{{$value->link}}">
                            <div class="head-multi-shop">
                                <div class="shop-img">
                                    <img src="{{asset($value->image)}}" alt="" />
                                </div>
                                <div class="shop-des">
                                    <h5>
                                        {{$value->title}}
                                    </h5>
                                    <div class="shop-button">
                                      <a href="{{$value->link}}">Shop Now</a>
                                    <a href="{{$value->link}}"><i class="fa-solid fa-angle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                    <!-- end-col -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- SHOP BANNER SECTION END -->



<!-- slider end -->
<div class="home-category">
    <div class="container-fluid desktop-c">
        <div class="row">
            <div class="col-sm-12">
                <div class="category-title">
                    <h3>Top Categories</h3>
                </div>
                <div class="category-slider owl-carousel">
                    @foreach($categories as $key=>$value)
                    <div class="cat-item">
                        <div class="cat-img">
                            <a href="{{route('category',$value->slug)}}">
                                <img src="{{asset($value->image)}}" alt="">
                            </a>
                        </div>
                        <div class="cat-name">
                            <a href="{{route('category',$value->slug)}}">
                                {{$value->name}}
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- SPECIAL OFFER SECTION START -->
<section class="special-feature">
    <div class="container-fluid desktop-c">
        <div class="row">
            <div class="col-sm-4">

                <div class="special-offer owl-carousel specialoffer-slider">
                    @foreach ($special_offer as $key => $value)
                    <div class="specialoffer-item">
                        <h4>{{$value->title}}</h4>
                        <a href="{{ route('product', $value->product->slug) }}" class="special-image-link">
                            <img src="{{asset($value->product->image->image)}}" alt="" />
                        </a>
                        <div class="special-product-name">
                            <a href="{{ route('product', $value->product->slug) }}">{{$value->product->name}}</a>
                        </div>
                        <div class="special-product-price">
                            <h5>৳{{$value->product->new_price}} <del>৳{{$value->product->old_price}}</del></h5>
                        </div>
                        <div class="special-counter">
                             <!-- <div class="deal-progress">
                                <div class="deal-stock">
                                    <span class="stock-sold">Already Sold: <strong>{{$value->product->sold}}</strong></span>
                                    <span class="stock-available">Available: <strong>{{$value->product->stock}}</strong></span>
                                </div>
                                 <div class="progress" role="progressbar" aria-label="Warning example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-warning" style="width: 75%"></div>
                                </div>
                            </div> -->

                            <!-- end-progress-bar -->
                            <h6>Hurry Up! Offer ends in: </h6>
                            <div class="timer_inner">
                                <div class="flipper myFlipper{{$key+1}}"
                                     data-datetime="{{$value->date}} 00:00:00"
                                     data-template="dd|HH|ii|ss"
                                     data-labels="Days|Hours|Minutes|Seconds"
                                     data-reverse="true"
                                     >
                                </div>

                            </div>
                         </div>
                    </div>

                    @endforeach
                </div>
            </div>
            <!-- end-col -->
            <div class="col-sm-8">
                <!-- start-nav-Tabs -->
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Featured</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">On Sale</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Top rated</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="feature">
                                <div class="product_sliders">
                                @foreach ($feature_product as $key => $value)
                                  <div class="product_item wist_item">
                                     @include('frontEnd.layouts.partials.product')
                                  </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <!-- popular product-start -->
                            <div class="feature">
                                <div class="product_sliders">
                                @foreach ($on_sale_tab as $key => $value)
                                  <div class="product_item wist_item">
                                     @include('frontEnd.layouts.partials.product')
                                  </div>
                                @endforeach
                                </div>
                            </div>
                            <!-- popular product-end -->
                        </div>
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="feature">
                                <div class="product_sliders">
                                @foreach ($toprated as $key => $value)
                                  <div class="product_item wist_item">
                                     @include('frontEnd.layouts.partials.product')
                                  </div>
                                @endforeach
                                </div>
                            </div>
                            <!-- ----------end---------- -->
                        </div>
                    </div>
                    <!-- End-nav-Tabs -->
            </div>
            <!-- end-col -->
        </div>
    </div>
</section>
<!-- SPECIAL OFFER SECTION END -->
<!----------- best-deals-section-start ----------->
<section class="best-seller-section">
    <div class="container-fluid desktop-c">
        <div class="best-saller-head">
            <div class="best-saller-left all-header">
                <h5>Best Deals</h5>
            </div>
            <div class="best-saller-right">
                <ul class="Cat-menu">
                    @foreach ($categories as $key=>$scategory)
                        <li class="best-saller @if($key > 1) over_list @endif">
                            <a href="{{ url('category/' . $scategory->slug) }}">
                                <span>{{ Str::limit($scategory->name, 25) }}</span>

                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="best-seller-body">
            <div class="row">
                <div class="col-sm-4">
                  <div class="product-sliders deals_product_inner">
                    @foreach ($best_deals as $key => $value)
                    @if($key < 4)
                    <div class="product_item wist_item">
                        @include('frontEnd.layouts.partials.product')
                    </div>
                    @endif
                    @endforeach
                  </div>
                </div>
                 <!-- end-best-seller-col item  -->
                 <div class="col-sm-4">
                        <div class="special-offer-bottom">
                        @foreach ($special_offer as $key => $value)
                        @if($key == 1)
                        <h4>Special Offer</h4>
                        <a href="{{route ('product', $value->product->slug)}}"><img src="{{ asset($value->product->image->image)}}" alt="" /></a>
                        <div class="special-product-name">
                            <a href="{{ route('product', $value->product->slug)}}">{{Str::limit($value->product->name, 50) }}</a>
                        </div>
                        <div class="special-product-price">
                            <p>৳ {{ $value->product->new_price }} @if ($value->product->old_price) @endif
                                @if($value->product->old_price)
                                <span>৳ {{$value->product->old_price}}</span>
                                @endif
                            </p>
                        </div>

                         <div class="button-products">
                                <div class="quick_wishlists">
                                    <button data-id="{{$value->product->id}}" class="hover-zoom wishlist_store" title="Wishlist"><i class="fa-regular fa-heart"></i>Wishlist</button>
                                </div>

                                <div class="details-compare-buttons">
                                    <button data-id="{{ $value->id }}" class="hover-zoom compare_store"
                                    title="Compare"><i class="fa-solid fa-sliders"></i></button>
                                </div>
                            </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                 <!-- end-col -->
                 <div class="col-sm-4">
                  <div class="product-sliders deals_product_inner">
                   @foreach ($best_deals as $key => $value)
                    @if($key > 4)
                     <div class="product_item wist_item">
                        @include('frontEnd.layouts.partials.product')
                     </div>
                     @endif
                   @endforeach
                  </div>
                </div>
                 <!-- end-best-seller-col item  -->
                 <div class="col-sm-12 btn-view">
                    <a href="{{ route('bestdeals') }}" class="view_more_btn" style="">View More</a>
                </div>

                  </div>
                </div>
                 <!-- end-best-seller-col item  -->
            </div>
        </div>
        <!-- seller-part-1 end -->
    </div>
</section>
<!----------- best-deals-section-end ----------->
<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="section-title">
                    <h3> <a href="{{route('bestdeals')}}">Best Deals</a></h3>
                    <a href="{{route('bestdeals')}}" class="view_all">View All</a>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="product_slider owl-carousel">
                    @foreach ($hotdeal_top as $key => $value)
                        <div class="product_item wist_item">
                            @include('frontEnd.layouts.partials.product')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@foreach ($homecategory as $homecat)
    <section class="homeproduct">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="section-title">
                        <h3><a href="{{route('category',$homecat->slug)}}">{{$homecat->name}} </a></h3>
                        <a href="{{route('category',$homecat->slug)}}" class="view_all">View All</a>
                    </div>
                </div>
                @php
                    $products = App\Models\Product::where(['status' => 1, 'category_id' => $homecat->id])
                        ->orderBy('id', 'DESC')
                        ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type','category_id')
                        ->withCount('variable')
                        ->limit(12)
                        ->get();
                @endphp
                <div class="col-sm-12">
                    <div class="product_slider owl-carousel">
                        @foreach ($products as $key => $value)
                            <div class="product_item wist_item">
                               @include('frontEnd.layouts.partials.product')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endforeach

    <div class="home-category mt-4">
        <div class="container-fluid desktop-c">
            <div class="row">
                <div class="col-sm-12">
                    <div class="category-title">
                        <h3>Brands</h3>
                    </div>
                    <div class="category-slider owl-carousel">
                        @foreach($brands as $key=>$value)
                        <div class="brand-item">
                            <a href="{{route('brand',$value->slug)}}">
                                <img src="{{asset($value->image)}}" alt="">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-gap"></div>
@endsection 
@push('script')
<script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('public/frontEnd/js/jquery.flipper-responsive.js') }}"></script>
@foreach ($special_offer as $key => $value)
<script>
    $(function(){
        $('.myFlipper{{$key+1}}').flipper('init');
    });
</script>
@endforeach
<script>
    $(document).ready(function() {
         $(".main_slider").owlCarousel({
            items: 1,
            loop: true,
            dots: false,
            autoplay: true,
            nav: true,
            autoplayHoverPause: false,
            margin: 0,
            mouseDrag: true,
            smartSpeed: 8000,
            autoplayTimeout: 3000,

            navText: ["<i class='fa-solid fa-angle-left'></i>",
                "<i class='fa-solid fa-angle-right'></i>"
            ],
        });

         owl.on('changed.owl.carousel', function (event) {
            var item = event.item.index - 2; // Position of the current item
            $('h4').removeClass('animated fadeInUp');
            $('h5').removeClass('animated fadeInRight');
            $('a').removeClass('animated fadeInRight');
            $('img').removeClass('animated fadeInUp');
            $('.owl-item').not('.cloned').eq(item).find('h4').addClass('animated fadeInUp');
            $('.owl-item').not('.cloned').eq(item).find('h5').addClass('animated fadeInRight');
            $('.owl-item').not('.cloned').eq(item).find('a').addClass('animated fadeInRight');
            $('.owl-item').not('.cloned').eq(item).find('img').addClass('animated fadeInUp');
        });



    })
</script>
<script>
    $(document).ready(function() {

         $(".category-slider").owlCarousel({
            margin: 15,
            loop: true,
            dots: false,
            nav: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 3,
                },
                600: {
                    items: 3,
                },
                1000: {
                    items: 8,
                },
            },
        });

        $(".product_slider").owlCarousel({
            margin: 10,
            items: 5,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 2,
                    nav: false,
                },
                600: {
                    items: 5,
                    nav: false,
                },
                1000: {
                    items: 5,
                    nav: false,
                },
            },
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".shop-banner-slider").owlCarousel({
            margin: 15,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                    nav: true,
                },
                600: {
                    items: 2,
                    nav: false,
                },
                1000: {
                    items: 4,
                    nav: true,
                    loop: false,
                },
            },
        });
    });
</script>
<script>
    $('.specialoffer-slider').owlCarousel({
        items: 1,
        loop:true,
        dots: false,
        margin:1,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:true
            },
            600:{
                items:1,
                nav:false
            },
            1000:{
                items:1,
                loop:false
            }
        }
    });
</script>
@endpush
