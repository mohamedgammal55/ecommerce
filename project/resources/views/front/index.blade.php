<!doctype html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title> {{$gs->title}} </title>
    <!-- icon -->
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/'.$gs->favicon)}}">
    <!-- Bootstrap -->
    <link rel="stylesheet" id="BootstrapLink" href="newSite/css/bootstrap.css">
    <!-- Material Design Bootstrap -->
    <link rel="stylesheet" id="MDBlink" href="newSite/css/mdb.min.css">
    <!-- mean menu -->
    <link rel="stylesheet" href="newSite/css/meanmenu.css">
    <!-- select2  -->
    <link rel="stylesheet" href="newSite/css/select2.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="newSite/css/all.css">
    <!-- swiper -->
    <link rel="stylesheet" href="newSite/css/swiper.css">
    <!-- odometer -->
    <link rel="stylesheet" href="newSite/css/odometer.min.css">
    <!-- animate -->
    <link rel="stylesheet" href="newSite/css/animate.min.css">
    <!-- side menu -->
    <link rel="stylesheet" href="newSite/css/ma5-menu.min.css">
    <!-- img gallery -->
    <link rel="stylesheet" href="newSite/css/jquery.fancybox.min.css">
    <!-- Custom style  -->

@if($langg->rtl != "1")
    <!-- Bootstrap -->
        <link rel="stylesheet" id="BootstrapLink" href="newSite/css/bootstrap.css">
        <!-- Material Design Bootstrap -->
        <link rel="stylesheet" id="MDBlink" href="newSite/css/mdb.min.css">

        <link rel="stylesheet" id="StyleLink" href="newSite/css/style.css">

@else
    <!-- Bootstrap -->
        <link rel="stylesheet" id="BootstrapLink" href="newSite/css/bootstrap.rtl.css">
        <!-- Material Design Bootstrap -->
        <link rel="stylesheet" id="MDBlink" href="newSite/css/mdb.rtl.min.css">

        <link rel="stylesheet" id="StyleLink" href="newSite/css/styleAR.css">

@endif
<!-- responsive style  -->
    <link rel="stylesheet" href="newSite/css/responsive.css">
    @toastr_css

</head>


<body>
<!-- --------------------------------------------
-----
-----
-----
-----
-----
-----                  content
-----
-----
-----
-----
-----
-----
-------------------------------------------------->
@if($gs->is_loader == 1)
    <div class="preloader" id="preloader"
         style="background: url({{asset('assets/images/'.$gs->loader)}}) no-repeat scroll center center #FFF;"></div>
@endif
<div class="xloader d-none" id="xloader"
     style="background: url({{asset('assets/front/images/xloading.gif')}}) no-repeat scroll center center #FFF;"></div>

@if($gs->is_popup== 1)

    @if(isset($visited))
        <div style="display:none">
            <img src="{{asset('assets/images/'.$gs->popup_background)}}">
        </div>

        <!--  Starting of subscribe-pre-loader Area   -->
        <div class="subscribe-preloader-wrap" id="subscriptionForm" style="display: none;">
            <div class="subscribePreloader__thumb"
                 style="background-image: url({{asset('assets/images/'.$gs->popup_background)}});">
                <span class="preload-close"><i class="fas fa-times"></i></span>
                <div class="subscribePreloader__text text-center">
                    <h1>{{$gs->popup_title}}</h1>
                    <p>{{$gs->popup_text}}</p>
                    <form action="{{route('front.subscribe')}}" id="subscribeform" method="POST">
                        {{csrf_field()}}
                        <div class="form-group">
                            <input type="email" name="email" placeholder="{{ $langg->lang741 }}" required="">
                            <button id="sub-btn" type="submit">{{ $langg->lang742 }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--  Ending of subscribe-pre-loader Area   -->

    @endif

@endif

<content>

    <!-- Top Header Area -->
    <section class=" topHeader ">
        <div class="container">
            <div class="row">
                <div class="col-md-6 p-1 d-flex justify-content-md-start justify-content-center align-items-center ">

                    @if($gs->is_language == 1)
                        <div class="d-flex align-items-center language-selector">
                            <p><i class="fas fa-globe color1 me-1"></i> {{--اللغة :--}} </p>
                            <select class="select2" onchange="window.location = this.value" id="selectors" name="language" style="min-width: 100px!important;">
                                @foreach(DB::table('languages')->get() as $language)
                                    <option value="{{route('front.language',$language->id)}}" {{ Session::has('language') ? ( Session::get('language') == $language->id ? 'selected' : '' ) : ( $language->is_default == 1 ? 'selected' : '') }} >{{$language->language}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if($gs->is_currency == 1)
                        <div class="d-flex align-items-center">
                            <p><i class="fas fa-money-bill-wave color1 me-1"></i>{{-- العملة :--}} </p>
                            <select class="select2 " onchange="window.location = this.value" style="min-width: 100px!important;">
                                @foreach(DB::table('currencies')->get() as $currency)
                                    <option value="{{route('front.currency',$currency->id)}}" {{ Session::has('currency') ? ( Session::get('currency') == $currency->id ? 'selected' : '' ) : ( $currency->is_default == 1 ? 'selected' : '') }} >{{$currency->name}}</option>
                                @endforeach
                            </select>
                        </div>
                @endif

                <!-- <span id="language">
                  <a href="#!"> English </a>
                </span> -->
                </div>
                <div class="col-md-6 p-1 d-flex justify-content-md-end justify-content-center align-items-center ">
                    @if(!Auth::guard('web')->check())
                        <span class="Login"><a href="{{ route('user.login') }}"> {{ $langg->lang12 }} </a> | <a href="{{ route('user.login') }}"> {{ $langg->lang13 }} </a></span>
                    @else
                        {{--                    href="javascript: ;" id="profile-icon"--}}
                    @endif


                    @if($gs->reg_vendor == 1)
                        @if(Auth::check())
                            @if(Auth::guard('web')->user()->is_vendor == 2)

                                <a href="{{ route('vendor-dashboard') }}"
                                   class="btn btn-rounded shadow-1  btn-info">{{ $langg->lang220 }}</a>
                            @else
                                <a href="{{ route('user-package') }}"
                                   class="btn btn-rounded shadow-1  btn-info">{{ $langg->lang220 }}</a>
                            @endif
                        @else
                            <li>
                                <a href="javascript:;" data-toggle="modal" data-target="#vendor-login"
                                   class=" btn btn-rounded shadow-1  btn-info">{{ $langg->lang220 }}</a>
                            </li>
                        @endif
                    @endif


                    {{--                    <a href="#!" class="btn btn-rounded shadow-1  btn-info"> تاجر معنا </a>--}}



                    {{--                   --}}{{-- ///////////////////////////////////////////////////////////////////////////////////////////////////--}}
                    {{--                                        <div class="right-content">--}}
                    {{--                                            <div class="list">--}}
                    {{--                                                <ul>--}}
                    {{--                                                    @if(!Auth::guard('web')->check())--}}
                    {{--                                                        <li class="login">--}}
                    {{--                                                            <a href="{{ route('user.login') }}" class="sign-log">--}}
                    {{--                                                                <div class="links">--}}
                    {{--                                                                    <span class="sign-in">{{ $langg->lang12 }}</span> <span>|</span>--}}
                    {{--                                                                    <span class="join">{{ $langg->lang13 }}</span>--}}
                    {{--                                                                </div>--}}
                    {{--                                                            </a>--}}
                    {{--                                                        </li>--}}
                    {{--                                                    @else--}}
                    {{--                                                        <li class="profilearea my-dropdown">--}}
                    {{--                                                            <a href="javascript: ;" id="profile-icon" class="profile carticon">--}}
                    {{--                    												<span class="text">--}}
                    {{--                    													<i class="far fa-user"></i>	{{ $langg->lang11 }} <i--}}
                    {{--                                                                                class="fas fa-chevron-down"></i>--}}
                    {{--                    												</span>--}}
                    {{--                                                            </a>--}}
                    {{--                                                            <div class="my-dropdown-menu profile-dropdown">--}}
                    {{--                                                                <ul class="profile-links">--}}
                    {{--                                                                    <li>--}}
                    {{--                                                                        <a href="{{ route('user-dashboard') }}"><i--}}
                    {{--                                                                                    class="fas fa-angle-double-right"></i> {{ $langg->lang221 }}--}}
                    {{--                                                                        </a>--}}
                    {{--                                                                    </li>--}}
                    {{--                                                                    @if(Auth::user()->IsVendor())--}}
                    {{--                                                                        <li>--}}
                    {{--                                                                            <a href="{{ route('vendor-dashboard') }}"><i--}}
                    {{--                                                                                        class="fas fa-angle-double-right"></i> {{ $langg->lang222 }}--}}
                    {{--                                                                            </a>--}}
                    {{--                                                                        </li>--}}
                    {{--                                                                    @endif--}}

                    {{--                                                                    <li>--}}
                    {{--                                                                        <a href="{{ route('user-profile') }}"><i--}}
                    {{--                                                                                    class="fas fa-angle-double-right"></i> {{ $langg->lang205 }}--}}
                    {{--                                                                        </a>--}}
                    {{--                                                                    </li>--}}

                    {{--                                                                    <li>--}}
                    {{--                                                                        <a href="{{ route('user-logout') }}"><i--}}
                    {{--                                                                                    class="fas fa-angle-double-right"></i> {{ $langg->lang223 }}--}}
                    {{--                                                                        </a>--}}
                    {{--                                                                    </li>--}}
                    {{--                                                                </ul>--}}
                    {{--                                                            </div>--}}
                    {{--                                                        </li>--}}
                    {{--                                                    @endif--}}


                    {{--                                                </ul>--}}
                    {{--                    --}}{{-- ///////////////////////////////////////////////////////////////////////////////////////////////////--}}


                </div>
            </div>
        </div>
    </section>
    <!-- Middle Header Area -->
    <section class="MiddleHeader">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 p-1 d-flex justify-content-lg-start justify-content-center align-items-center">
                    <a href="{{ route('front.index') }}">
                        <img src="{{asset('assets/images/'.$gs->logo)}}" class="logo">
                    </a>

                </div>
                <div class="col-lg-6 col-12  p-1 d-flex  justify-content-end   align-items-center">
                    {{--                        <form id="searchForm" class="search-form"--}}
                    {{--                              action="{{ route('front.category', [Request::route('category'),Request::route('subcategory'),Request::route('childcategory')]) }}"--}}
                    {{--                              method="GET">--}}
                    {{--                            @if (!empty(request()->input('sort')))--}}
                    {{--                                <input type="hidden" name="sort" value="{{ request()->input('sort') }}">--}}
                    {{--                            @endif--}}
                    {{--                            @if (!empty(request()->input('minprice')))--}}
                    {{--                                <input type="hidden" name="minprice" value="{{ request()->input('minprice') }}">--}}
                    {{--                            @endif--}}
                    {{--                            @if (!empty(request()->input('maxprice')))--}}
                    {{--                                <input type="hidden" name="maxprice" value="{{ request()->input('maxprice') }}">--}}
                    {{--                            @endif--}}
                    {{--                            <input type="text" id="prod_name" name="search" placeholder="{{ $langg->lang2 }}"--}}
                    {{--                                   value="{{ request()->input('search') }}" autocomplete="off">--}}
                    {{--                            <div class="autocomplete">--}}
                    {{--                                <div id="myInputautocomplete-list" class="autocomplete-items">--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                            <button type="submit"><i class="icofont-search-1"></i></button>--}}
                    {{--                        </form>--}}


                    {{--                    <div class="search-box">--}}
                    {{--                        <div class="categori-container" id="catSelectForm">--}}
                    {{--                            <select name="category" id="category_select" class="categoris">--}}
                    {{--                                <option value="">{{ $langg->lang1 }}</option>--}}
                    {{--                                @foreach($categories as $data)--}}
                    {{--                                    <option value="{{ $data->slug }}" {{ Request::route('category') == $data->slug ? 'selected' : '' }}>{{ $data->name }}</option>--}}
                    {{--                                @endforeach--}}
                    {{--                            </select>--}}
                    {{--                        </div>--}}


                    <form id="searchForm" class="search-form"
                          action="{{ route('front.category', [Request::route('category'),Request::route('subcategory'),Request::route('childcategory')]) }}"
                          method="GET">
                        <div class="row align-items-center">
                            <div class="col-md-4 position-relative p-1">
                                <select class="categoris select2 form-control"  name="category" id="category_select" >
                                    <option value="">{{ $langg->lang1 }}</option>--}}
                                    @foreach($categories as $data)
                                        <option value="{{ $data->slug }}" {{ Request::route('category') == $data->slug ? 'selected' : '' }}>{{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8 p-1">
                                <div class="search-box">
                                    <input type="text" id="prod_name" name="search" placeholder="{{ $langg->lang2 }}"
                                           value="{{ request()->input('search') }}" autocomplete="off"  class="form-control" >

                                    <button type="submit"><i class="far fa-search"></i></button>
                                </div>
                                <div class="autocomplete">
                                    <div id="myInputautocomplete-list" class="autocomplete-items">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <div
                    class="col-md-3 p-md-1 p-0 d-flex justify-content-md-end justify-content-center align-items-center">
                    <div class=" linkIcons">
                        <a href="{{ route('front.index') }}" class=" d-lg-none ">
                            <i class="fal fa-home-alt"></i>
                            <span class="TabName"> {{$langg->lang17}} </span>
                        </a>
                        <a href="#!" class=" d-lg-none ">
                            <i class="fal fa-store-alt"></i>
                            <span class="TabName"> {{$langg->lang42}} </span>
                        </a>
                        <a href="{{ route('product.compare') }}">
                            <i class="fal fa-compress-alt"> <span
                                    class="badge" id="Compare"> {{ Session::has('compare') ? count(Session::get('compare')->items) : '0' }} </span></i>
                            <span class="TabName"> {{$langg->lang10}} </span>
                        </a>

                        @if(Auth::guard('web')->check())
                            <a href="{{ route('user-wishlists') }}">
                                <i class="fal fa-heart"><span class="badge" > {{ Auth::user()->wishlistCount() }} </span></i>
                                <span class="TabName"> {{$langg->lang26}} </span>
                            </a>
                        @endif
                        <a href="{{ route('front.checkout') }}">
                            <i class="fal fa-shopping-cart"> <span id="count"
                                                                   class="badge"> {{ Session::has('cart') ? count(Session::get('cart')->items) : '0' }} </span></i>
                            <span class="TabName"> {{$langg->lang3}} </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Navbar Area -->
    <div class="navbar-area">
        <div class="main-navbar">
            <div class="container">
                <div class="navbar navbar-expand navbar-light">
                    <!-- <a class="navbar-brand font-weight-bold" href="index.html"> جملجي </a> -->
                    <div class="collapse navbar-collapse mean-menu">


                        <ul class="navbar-nav">
                            <li class="nav-item" style="margin: 5px">
                                <a href="{{ route('front.index') }}" class="nav-link"> {{ $langg->lang22 }} </a>
                            </li>


                            @foreach(DB::table('pages')->where('header','=',1)->get() as $data)
                                <li class="nav-item" style="margin: 5px !important;">
                                    <a href="{{ route('front.page',$data->slug) }}"
                                       class="nav-link"> {{ $data->title }} </a>
                                </li>
                            @endforeach
                                                        <li class="nav-item" style="margin: 4px">
                                                            <a href="#!" class="ma5menu__toggle">
                                                                <i class="fal fa-bars"></i>
                                                                {{$langg->lang42}}
                                                            </a>
                                                        </li>

                        </ul>
                    </div>
                    <!-- mobile menu toggle button start -->
                    <!-- <a href="#!" class="ma5menu__toggle  d-lg-none">
                        <i class="fal fa-bars"></i>
                        الاقسام
                   </a> -->
                </div>
            </div>
        </div>
    </div>
    <!-- mobile menu toggle button end -->
    <div style="display: none;">
        <ul class="site-menu">
            @foreach($categories as $category)
                @if(count($category->subs) > 0)
            <li>
                <a href="{{ route('front.category',$category->slug) }}"> {{ $category->name }} </a>
                <ul>
                    @foreach($category->subs()->whereStatus(1)->get() as $subcat)
                        <li>
                            <a href="{{ route('front.subcat',['slug1' => $category->slug, 'slug2' => $subcat->slug]) }}">{{$subcat->name}}</a>
                            @if(count($subcat->childs) > 0)
                            <ul>
                                @foreach($subcat->childs()->whereStatus(1)->get() as $childcat)
                                <li><a href="{{ route('front.childcat',['slug1' => $category->slug, 'slug2' => $subcat->slug, 'slug3' => $childcat->slug]) }}">{{$childcat->name}} </a></li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </li>
                @else
                    <li> <a href="{{ route('front.category',$category->slug) }}"> {{ $category->name }} </a></li>
                @endif
            @endforeach

        </ul>
    </div>



@if($ps->slider == 1)
    @if(count($sliders))


        <!-- Top Slider -->
            <section class="TopSlider">
                <div class="container">
                    <div id="MainSlider">
                        <div class="swiper-container MainSlider-container ">
                            <div class="swiper-wrapper">
                                @foreach($sliders as $data)
                                    <div class="swiper-slide  mainSlideItem"
                                         style="background-image: url('{{asset('assets/images/sliders/'.$data->photo)}}')">
                                        <div class=" info mr-lg-4">
                                            <h1> {{$data->subtitle_text}} </h1>
                                            <h4> {{$data->title_text}} </h4>
                                            <p> {{$data->details_text}} </p>
                                            <a href="{{$data->link}}" class="btn "> {{ $langg->lang25 }} </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </div>
            </section>

    @endif
@endif


@if($ps->featured_category == 1)

    <!-- Categories Slider -->
        <section class="Categories mt-4">
            <div class="container p-1">
                <div class="swiper-container CategoriesSlider">
                    <div class="swiper-wrapper">
                        @foreach($categories->where('is_featured','=',1) as $cat)
                            <div class="swiper-slide ">
                                <a href="{{ route('front.category',$cat->slug) }}" class="singleCategory ">
                                    <span class="categoryImg"><img
                                            src="{{asset('assets/images/categories/'.$cat->image) }}"></span>
                                    <p>
                                        {{ $cat->name }}
                                    </p>
                                    <p class="count">
                                        {{ count($cat->products) }} {{ $langg->lang4 }}
                                    </p>
                                </a>
                            </div>

                        @endforeach

                    </div>
                </div>
            </div>
        </section>

@endif


@if($ps->featured == 1)

    <!-- Category  -->
        <section class=" single-category-section">
            <div class="container">
                <div class="section-title">
                    <h2>
                        {{ $langg->lang26 }}
                    </h2>
                </div>

                <!-- products -->
                <div class="arrivals-products">
                    <div class="swiper-container productsSlider">
                        <div class="swiper-wrapper">
                            @foreach($feature_products as $prod)
                                <div class="swiper-slide p-1">
                                    <div class="single-products">
                                        <div class="products-image">
                                            <a href="{{ route('front.product', $prod->slug) }}"><img
                                                    src="{{ $prod->thumbnail ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/noimage.png') }}"></a>
                                            {{--                                    <div class="tag"> جديد </div>--}}
                                            <ul class="action">
                                                @if(Auth::guard('web')->check())

                                                    <li>
                                                        <a href="#!" class="add-to-wish"
                                                           data-href="{{ route('user-wishlist-add',$prod->id) }}"
                                                           data-toggle="tooltip" data-placement="right"
                                                           title="{{ $langg->lang54 }}" data-placement="right"><i
                                                                class="far fa-heart"></i></a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a href="{{ route('front.product', $prod->slug) }}"{{-- data-toggle="modal" data-target="#productsQuickView"--}}><i
                                                            class="fal fa-eye"></i></a>
                                                </li>
                                                <li>
                                                    <a href="#!" class="add-to-compare" data-toggle="tooltip"
                                                       data-placement="right" title="{{ $langg->lang57 }}"
                                                       data-placement="right"
                                                       data-href="{{ route('product.compare.add',$prod->id) }}"><i
                                                            class="fas fa-compress-alt"></i></a>
                                                </li>
                                                <li>
                                                    <a href="#!" class="add-to-cart add-to-cart-btn"
                                                       data-href="{{ route('product.cart.add',$prod->id) }}"
                                                       class="default-btn"><i class="fas fa-cart-plus"></i></a>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="products-content">
                                            <h3>
                                                <a href="{{ route('front.product', $prod->slug) }}">{{ $prod->showName() }}</a>
                                            </h3>
                                            <div class="prices">
                                                <h6 class="title">  قائمة الاسعار :  </h6>
                                                <div class="price"> <span class="count"> من 1 الي 5 : </span> <span class="priceCount">  500 ج.م </span> </div>
                                                <div class="price"> <span class="count"> من 1 الي 5 : </span> <span class="priceCount">  500 ج.م </span> </div>
                                                <div class="price"> <span class="count"> من 1 الي 5 : </span> <span class="priceCount">  500 ج.م </span> </div>
                                                <div class="price"> <span class="count"> من 1 الي 5 : </span> <span class="priceCount">  500 ج.م </span> </div>
                                            </div>
                                            {{--                                    <p>منتج متميز مصم خصيصا لك وبجودة عالبة جدا من اجل راحتك</p>--}}
                                            {{--                                    <ul class="rating">--}}
                                            {{--                                        <li><i class="fas fa-star"></i></li>--}}
                                            {{--                                        <li><i class="fas fa-star"></i></li>--}}
                                            {{--                                        <li><i class="fas fa-star"></i></li>--}}
                                            {{--                                        <li><i class="fas fa-star"></i></li>--}}
                                            {{--                                        <li><i class="fas fa-star"></i></li>--}}
                                            {{--                                    </ul>--}}
                                            @php
                                                $attrPrice = 0;
                                                $sessionCur = session()->get('currency');
                                                $sessionCurr = DB::table('currencies')->where('id',$sessionCur)->first();
                                                $databaseCurr = DB::table('currencies')->where('is_default',1)->first();
                                                $curr = $sessionCurr ? $sessionCurr: $databaseCurr;

                                                if($prod->user_id != 0){
                                                $attrPrice = $prod->price + $gs->fixed_commission + ($prod->price/100) * $gs->percentage_commission ;
                                                }

                                            if(!empty($prod->size) && !empty($prod->size_price)){
                                                  $attrPrice += $prod->size_price[0];
                                              }

                                              if(!empty($prod->attributes)){
                                                $attrArr = json_decode($prod->attributes, true);
                                              }
                                            @endphp
                                            @if (!empty($prod->attributes))
                                                @php
                                                    $attrArr = json_decode($prod->attributes, true);
                                                @endphp
                                            @endif

                                            @if (!empty($attrArr))
                                                @foreach ($attrArr as $attrKey => $attrVal)
                                                    @if (array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1)
                                                        @foreach ($attrVal['values'] as $optionKey => $optionVal)
                                                            @if ($loop->first)
                                                                @if (!empty($attrVal['prices'][$optionKey]))
                                                                    @php
                                                                        $attrPrice = $attrPrice + $attrVal['prices'][$optionKey] * $curr->value;
                                                                    @endphp
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif

                                            @php
                                                $withSelectedAtrributePrice = $attrPrice+$prod->price;
                                                $withSelectedAtrributePrice = round(($withSelectedAtrributePrice) * $curr->value,2);

                                              //   if($gs->currency_format == 0){
                                              //        $curr->sign.$withSelectedAtrributePrice;
                                              //     }
                                              //     else{
                                              //          $withSelectedAtrributePrice.$curr->sign;
                                              //     }
                                            @endphp
                                            <span
                                                class="price">{{ $attrPrice != 0 ?  $gs->currency_format == 0 ? $curr->sign.$withSelectedAtrributePrice : $withSelectedAtrributePrice.$curr->sign :$prod->showPrice() }}</span>

                                            {{--                                    <div class=" text-center mt-3">--}}
                                            {{--                                        <a href="#!" class="add-to-cart add-to-cart-btn" data-href="{{ route('product.cart.add',$prod->id) }}" class="default-btn"><i class="fas fa-shopping-cart me-2"></i> اضف للسلة--}}
                                            {{--                                            <span></span>--}}
                                            {{--                                        </a>--}}
                                            {{--                                    </div>--}}

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </section>

@endif

@if($ps->small_banner == 1)
    <!-- AdsSections  -->
        <section class="AdsSections">
            <div class="container">
                @foreach($top_small_banners->chunk(2) as $chunk)
                    <div class="row">
                        @foreach($chunk as $img)
                            <div class="col-md-6 p-1">
                                <div class="wide">
                                    <div class="position-relative">
                                        <a href="{{ $img->link }}"><img
                                                src="{{asset('assets/images/banners/'.$img->photo)}}"/></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                @endforeach

            </div>
        </section>
@endif
@if($ps->hot_sale == 1)

    <!-- trend-products -->
        <section class="arrivals-products">
            <div class="container">
                <div class="section-title">
                    <h2>{{ $langg->lang30 }}</h2>
                </div>
                <div class="swiper-container productsSlider">
                    <div class="swiper-wrapper">
                        @foreach($hot_products->chunk(3) as $chunk)
                            @foreach($chunk as $prod)
                                @php
                                    $attrPrice = 0;
                                    $sessionCur = session()->get('currency');
                                    $sessionCurr = DB::table('currencies')->where('id',$sessionCur)->first();
                                    $databaseCurr = DB::table('currencies')->where('is_default',1)->first();
                                    $curr = $sessionCurr ? $sessionCurr: $databaseCurr;

                                    if($prod->user_id != 0){
                                        $attrPrice = $prod->price + $gs->fixed_commission + ($prod->price/100) * $gs->percentage_commission ;
                                        }

                                    if(!empty($prod->size) && !empty($prod->size_price)){
                                          $attrPrice += $prod->size_price[0];
                                      }

                                      if(!empty($prod->attributes)){
                                        $attrArr = json_decode($prod->attributes, true);
                                      }
                                @endphp

                                @if (!empty($prod->attributes))
                                    @php
                                        $attrArr = json_decode($prod->attributes, true);
                                    @endphp
                                @endif

                                @if (!empty($attrArr))
                                    @foreach ($attrArr as $attrKey => $attrVal)
                                        @if (array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1)
                                            @foreach ($attrVal['values'] as $optionKey => $optionVal)
                                                @if ($loop->first)
                                                    @if (!empty($attrVal['prices'][$optionKey]))
                                                        @php
                                                            $attrPrice = $attrPrice + $attrVal['prices'][$optionKey] * $curr->value;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif

                                @php
                                    $withSelectedAtrributePrice = $attrPrice+$prod->price;
                                    $withSelectedAtrributePrice = round(($withSelectedAtrributePrice) * $curr->value,2);


                                @endphp

                                <div class="swiper-slide p-1">
                                    <div class="single-products">
                                        <div class="products-image">
                                            <a href="{{ route('front.product', $prod->slug) }}"><img
                                                    src="{{ $prod->thumbnail ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/noimage.png') }}"></a>
                                            {{--                                    <div class="tag"> جديد </div>--}}
                                            <ul class="action">
                                                @if(Auth::guard('web')->check())

                                                    <li>
                                                        <a href="#!" class="add-to-wish"
                                                           data-href="{{ route('user-wishlist-add',$prod->id) }}"
                                                           data-toggle="tooltip" data-placement="right"
                                                           title="{{ $langg->lang54 }}" data-placement="right"><i
                                                                class="far fa-heart"></i></a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a href="{{ route('front.product', $prod->slug) }}"{{-- data-toggle="modal" data-target="#productsQuickView"--}}><i
                                                            class="fal fa-eye"></i></a>
                                                </li>
                                                <li>
                                                    <a href="#!" class="add-to-compare" data-toggle="tooltip"
                                                       data-placement="right" title="{{ $langg->lang57 }}"
                                                       data-placement="right"
                                                       data-href="{{ route('product.compare.add',$prod->id) }}"><i
                                                            class="fas fa-compress-alt"></i></a>
                                                </li>
                                                <li>
                                                    <a href="#!" class="add-to-cart add-to-cart-btn"
                                                       data-href="{{ route('product.cart.add',$prod->id) }}"
                                                       class="default-btn"><i class="fas fa-cart-plus"></i></a>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="products-content">
                                            <h3>
                                                <a href="{{ route('front.product', $prod->slug) }}">{{ $prod->showName() }}</a>
                                            </h3>
                                            <div class="prices">
                                                <h6 class="title">  قائمة الاسعار :  </h6>
                                                <div class="price"> <span class="count"> من 1 الي 5 : </span> <span class="priceCount">  500 ج.م </span> </div>
                                                <div class="price"> <span class="count"> من 1 الي 5 : </span> <span class="priceCount">  500 ج.م </span> </div>
                                                <div class="price"> <span class="count"> من 1 الي 5 : </span> <span class="priceCount">  500 ج.م </span> </div>
                                                <div class="price"> <span class="count"> من 1 الي 5 : </span> <span class="priceCount">  500 ج.م </span> </div>
                                            </div>
                                            {{--                                    <p>منتج متميز مصم خصيصا لك وبجودة عالبة جدا من اجل راحتك</p>--}}
                                            {{--                                    <ul class="rating">--}}
                                            {{--                                        <li><i class="fas fa-star"></i></li>--}}
                                            {{--                                        <li><i class="fas fa-star"></i></li>--}}
                                            {{--                                        <li><i class="fas fa-star"></i></li>--}}
                                            {{--                                        <li><i class="fas fa-star"></i></li>--}}
                                            {{--                                        <li><i class="fas fa-star"></i></li>--}}
                                            {{--                                    </ul>--}}
                                            @php
                                                $attrPrice = 0;
                                                $sessionCur = session()->get('currency');
                                                $sessionCurr = DB::table('currencies')->where('id',$sessionCur)->first();
                                                $databaseCurr = DB::table('currencies')->where('is_default',1)->first();
                                                $curr = $sessionCurr ? $sessionCurr: $databaseCurr;

                                                if($prod->user_id != 0){
                                                $attrPrice = $prod->price + $gs->fixed_commission + ($prod->price/100) * $gs->percentage_commission ;
                                                }

                                            if(!empty($prod->size) && !empty($prod->size_price)){
                                                  $attrPrice += $prod->size_price[0];
                                              }

                                              if(!empty($prod->attributes)){
                                                $attrArr = json_decode($prod->attributes, true);
                                              }
                                            @endphp
                                            @if (!empty($prod->attributes))
                                                @php
                                                    $attrArr = json_decode($prod->attributes, true);
                                                @endphp
                                            @endif

                                            @if (!empty($attrArr))
                                                @foreach ($attrArr as $attrKey => $attrVal)
                                                    @if (array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1)
                                                        @foreach ($attrVal['values'] as $optionKey => $optionVal)
                                                            @if ($loop->first)
                                                                @if (!empty($attrVal['prices'][$optionKey]))
                                                                    @php
                                                                        $attrPrice = $attrPrice + $attrVal['prices'][$optionKey] * $curr->value;
                                                                    @endphp
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif

                                            @php
                                                $withSelectedAtrributePrice = $attrPrice+$prod->price;
                                                $withSelectedAtrributePrice = round(($withSelectedAtrributePrice) * $curr->value,2);

                                              //   if($gs->currency_format == 0){
                                              //        $curr->sign.$withSelectedAtrributePrice;
                                              //     }
                                              //     else{
                                              //          $withSelectedAtrributePrice.$curr->sign;
                                              //     }
                                            @endphp
                                            <span
                                                class="price">{{ $attrPrice != 0 ?  $gs->currency_format == 0 ? $curr->sign.$withSelectedAtrributePrice : $withSelectedAtrributePrice.$curr->sign :$prod->showPrice() }}</span>

                                            {{--                                    <div class=" text-center mt-3">--}}
                                            {{--                                        <a href="#!" class="add-to-cart add-to-cart-btn" data-href="{{ route('product.cart.add',$prod->id) }}" class="default-btn"><i class="fas fa-shopping-cart me-2"></i> اضف للسلة--}}
                                            {{--                                            <span></span>--}}
                                            {{--                                        </a>--}}
                                            {{--                                    </div>--}}

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach

                    </div>
                    <!-- Add Arrows -->
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>
@endif


<!-- Special Products  -->
    <section class="special-products-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4 p-1">
                    <div class="special-products-inner"
                         style="background-image: url({{asset('assets/images/'.$ps->big_save_banner)}});">
                        <div class="inner-content">
                            <span class=" text-white ">{{ $langg->lang31 }} </span>
                            <!-- <h3> غسول لليد</h3>
                            <p> كمية محدودة </p>
                            <div class="inner-btn">
                              <a href="#!" class="default-btn">
                                <i class="fal fa-shopping-cart"></i>
                                تسوق الآن
                                <span></span>
                              </a>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-8 pt-4  p-1" style=" background-color: #fbfbfe;">
                    <div class="section-title">
                        <h2 class="px-3" style=" background-color: #fbfbfe;">{{ $langg->lang31 }}</h2>
                    </div>
                    <div class="swiper-container newProductsSlider">
                        <div class="swiper-wrapper">
                            @foreach($latest_products->chunk(3) as $chunk)
                                @foreach($chunk as $prod)

                                    <div class="swiper-slide p-1">
                                        <div class="single-products">
                                            <div class="products-image">
                                                <a href="{{ route('front.product', $prod->slug) }}"><img
                                                        src="{{ $prod->thumbnail ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/noimage.png') }}"></a>
                                                <div class="tag"> {{ $langg->lang31 }} </div>
                                                <ul class="action">
                                                    @if(Auth::guard('web')->check())

                                                        <li>
                                                            <a href="#!" class="add-to-wish"
                                                               data-href="{{ route('user-wishlist-add',$prod->id) }}"
                                                               data-toggle="tooltip" data-placement="right"
                                                               title="{{ $langg->lang54 }}" data-placement="right"><i
                                                                    class="far fa-heart"></i></a>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <a href="{{ route('front.product', $prod->slug) }}"{{-- data-toggle="modal" data-target="#productsQuickView"--}}><i
                                                                class="fal fa-eye"></i></a>
                                                    </li>
                                                    <li>
                                                        <a href="#!" class="add-to-compare" data-toggle="tooltip"
                                                           data-placement="right" title="{{ $langg->lang57 }}"
                                                           data-placement="right"
                                                           data-href="{{ route('product.compare.add',$prod->id) }}"><i
                                                                class="fas fa-compress-alt"></i></a>
                                                    </li>
                                                    <li>
                                                        <a href="#!" class="add-to-cart add-to-cart-btn"
                                                           data-href="{{ route('product.cart.add',$prod->id) }}"
                                                           class="default-btn"><i class="fas fa-cart-plus"></i></a>
                                                    </li>

                                                </ul>
                                            </div>
                                            <div class="products-content">
                                                <h3>
                                                    <a href="{{ route('front.product', $prod->slug) }}">{{ $prod->showName() }}</a>
                                                </h3>

                                                <div class="prices">
                                                    <h6 class="title">  قائمة الاسعار :  </h6>
                                                    <div class="price"> <span class="count"> من 1 الي 5 : </span> <span class="priceCount">  500 ج.م </span> </div>
                                                    <div class="price"> <span class="count"> من 1 الي 5 : </span> <span class="priceCount">  500 ج.م </span> </div>
                                                    <div class="price"> <span class="count"> من 1 الي 5 : </span> <span class="priceCount">  500 ج.م </span> </div>
                                                    <div class="price"> <span class="count"> من 1 الي 5 : </span> <span class="priceCount">  500 ج.م </span> </div>
                                                </div>
                                                {{--                                    <p>منتج متميز مصم خصيصا لك وبجودة عالبة جدا من اجل راحتك</p>--}}
                                                {{--                                    <ul class="rating">--}}
                                                {{--                                        <li><i class="fas fa-star"></i></li>--}}
                                                {{--                                        <li><i class="fas fa-star"></i></li>--}}
                                                {{--                                        <li><i class="fas fa-star"></i></li>--}}
                                                {{--                                        <li><i class="fas fa-star"></i></li>--}}
                                                {{--                                        <li><i class="fas fa-star"></i></li>--}}
                                                {{--                                    </ul>--}}
                                                @php
                                                    $attrPrice = 0;
                                                    $sessionCur = session()->get('currency');
                                                    $sessionCurr = DB::table('currencies')->where('id',$sessionCur)->first();
                                                    $databaseCurr = DB::table('currencies')->where('is_default',1)->first();
                                                    $curr = $sessionCurr ? $sessionCurr: $databaseCurr;

                                                    if($prod->user_id != 0){
                                                    $attrPrice = $prod->price + $gs->fixed_commission + ($prod->price/100) * $gs->percentage_commission ;
                                                    }

                                                if(!empty($prod->size) && !empty($prod->size_price)){
                                                      $attrPrice += $prod->size_price[0];
                                                  }

                                                  if(!empty($prod->attributes)){
                                                    $attrArr = json_decode($prod->attributes, true);
                                                  }
                                                @endphp
                                                @if (!empty($prod->attributes))
                                                    @php
                                                        $attrArr = json_decode($prod->attributes, true);
                                                    @endphp
                                                @endif

                                                @if (!empty($attrArr))
                                                    @foreach ($attrArr as $attrKey => $attrVal)
                                                        @if (array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1)
                                                            @foreach ($attrVal['values'] as $optionKey => $optionVal)
                                                                @if ($loop->first)
                                                                    @if (!empty($attrVal['prices'][$optionKey]))
                                                                        @php
                                                                            $attrPrice = $attrPrice + $attrVal['prices'][$optionKey] * $curr->value;
                                                                        @endphp
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif

                                                @php
                                                    $withSelectedAtrributePrice = $attrPrice+$prod->price;
                                                    $withSelectedAtrributePrice = round(($withSelectedAtrributePrice) * $curr->value,2);

                                                  //   if($gs->currency_format == 0){
                                                  //        $curr->sign.$withSelectedAtrributePrice;
                                                  //     }
                                                  //     else{
                                                  //          $withSelectedAtrributePrice.$curr->sign;
                                                  //     }
                                                @endphp
                                                <span
                                                    class="price">{{ $attrPrice != 0 ?  $gs->currency_format == 0 ? $curr->sign.$withSelectedAtrributePrice : $withSelectedAtrributePrice.$curr->sign :$prod->showPrice() }}</span>

                                                {{--                                    <div class=" text-center mt-3">--}}
                                                {{--                                        <a href="#!" class="add-to-cart add-to-cart-btn" data-href="{{ route('product.cart.add',$prod->id) }}" class="default-btn"><i class="fas fa-shopping-cart me-2"></i> اضف للسلة--}}
                                                {{--                                            <span></span>--}}
                                                {{--                                        </a>--}}
                                                {{--                                    </div>--}}

                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            @endforeach
                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@if($ps->partners == 1)

    <!-- partnersSection -->
        <section class="partnersSection">
            <div class="container">
                <div class="section-title">
                    <h2 class="px-3">{{ $langg->lang236 }}</h2>
                </div>

                <div class="swiper-container partners">
                    <div class="swiper-wrapper">
                        @foreach($partners as $data)
                            <a href="{{ $data->link }}" class="swiper-slide "><img
                                    src="{{asset('assets/images/partner/'.$data->photo)}}"></a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
@endif

<!-- Start Footer -->
    <section class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="single-footer-widget">
                        <img src="{{asset('assets/images/'.$gs->logo)}}" style="height: 100px ; object-fit: contain">
                        <h2> {{$gs->title}} </h2>
                        <ul class="footer-contact-info">
{{--                            @if($ps->street != null)--}}
{{--                                <li>--}}
{{--                                    <span>{{$langg->lang291}}</span>--}}
{{--                                    <a href="#" target="_blank">{!! $ps->street !!}</a>--}}
{{--                                </li>--}}
{{--                            @endif--}}
{{--                            @if($ps->phone != null)--}}
{{--                                <li>--}}
{{--                                    <span>{{$langg->lang48}} : </span>--}}
{{--                                    <a href="#"> 0123456789</a>--}}
{{--                                </li>--}}
{{--                            @endif--}}

                            @if($ps->email != null)
                                <li>
                                    <span>{{$langg->lang49}} : </span>
                                    <a href="mailto:{{$ps->email}}">{{$ps->email}}</a>
                                </li>
                            @endif


                        </ul>
                        <ul class="footer-social">
                            @if(App\Models\Socialsetting::find(1)->f_status == 1)
                                <li>
                                    <a href="{{ App\Models\Socialsetting::find(1)->facebook }}" target="_blank">
                                        <i class='fab fa-facebook-f'></i>
                                    </a>
                                </li>
                            @endif
                            @if(App\Models\Socialsetting::find(1)->g_status == 1)
                                <li>
                                    <a href="{{ App\Models\Socialsetting::find(1)->gplus }}" target="_blank">
                                        <i class='fab fa-google'></i>
                                    </a>
                                </li>
                            @endif
                            @if(App\Models\Socialsetting::find(1)->t_status == 1)
                                <li>
                                    <a href="{{ App\Models\Socialsetting::find(1)->twitter }}" target="_blank">
                                        <i class='fab fa-twitter'></i>
                                    </a>
                                </li>
                            @endif
                            @if(App\Models\Socialsetting::find(1)->l_status == 1)
                                <li>
                                    <a href="{{ App\Models\Socialsetting::find(1)->linkedin }}" target="_blank">
                                        <i class='fab fa-linkedin-in'></i>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="single-footer-widget">
                        <h2>{{ $langg->lang21 }}</h2>
                        <ul class="quick-links">
                            <li>
                                <a href="{{ route('front.index') }}"> {{ $langg->lang22 }} </a>
                            </li>
                            @foreach(DB::table('pages')->where('footer','=',1)->get() as $data)
                                <li>
                                    <a href="{{ route('front.page',$data->slug) }}"> {{ $data->title }} </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6">
                    <div class="single-footer-widget">
                        <h2> اخبارنا</h2>
                        <div class="newsletter-item">
                            <div class="newsletter-content">
                                <p>انضم للحصول علي الاخبار</p>
                            </div>
                            <form class="newsletter-form" data-toggle="validator">
                                <input type="email" class="input-newsletter" placeholder=" البريد الالكتروني "
                                       name="EMAIL" required
                                       autocomplete="off">
                                <button type="submit">اشتراك</button>
                                <div id="validator-newsletter" class="form-result"></div>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Footer -->
    <!-- Start Copy Right -->
    <div class="copyright">
        <div class="container">
            <div class="copyright-content">
                <p>{!! $gs->copyright !!}</p>
            </div>
        </div>
    </div>
    <!-- End Copy Right -->
    <!-- Start Go Top -->
    <a href="#">
        <div class="go-top">
            <i class="far fa-chevron-double-up"></i>
        </div>
    </a>
    <!-- End Go Top -->


</content>
<!-- --------------------------------------------
-----
-----
-----
-----
-----
-----                 end content
-----
-----
-----
-----
-----
-----
-------------------------------------------------->
<!--  QuickView Modal  -->
<div class="modal fade productsQuickView" id="productsQuickView" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span><i class="far fa-times"></i></span>
            </button>
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 ">
                    <div class="sliderWithThumb">
                        <div class="swiper-container gallery-top">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <a data-fancybox="products" href="img/p1.png" data-caption="">
                                        <img src="newSite/img/p1.png">
                                    </a>
                                </div>
                                <div class="swiper-slide">
                                    <a data-fancybox="products" href="img/p2.png" data-caption="">
                                        <img src="newSite/img/p2.png">
                                    </a>
                                </div>
                                <div class="swiper-slide">
                                    <a data-fancybox="products" href="img/p3.png" data-caption="">
                                        <img src="newSite/img/p3.png">
                                    </a>
                                </div>
                            </div>
                            <!-- Add Arrows -->
                            <div class="swiper-button-next swiper-button-white"></div>
                            <div class="swiper-button-prev swiper-button-white"></div>
                        </div>
                        <div class="swiper-container gallery-thumbs">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide"><img src="newSite/img/p1.png"></div>
                                <div class="swiper-slide"><img src="newSite/img/p2.png"></div>
                                <div class="swiper-slide"><img src="newSite/img/p3.png"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="product-content">
                        <h3>منتج 1 </h3>
                        <div class="product-review">
                            <div class="rating">
                                <i class='fas fa-star'></i>
                                <i class='fas fa-star'></i>
                                <i class='fas fa-star'></i>
                                <i class='fas fa-star'></i>
                                <i class='fas fa-star'></i>
                            </div>
                        </div>
                        <div class="price">
                            <span class="old-price">150.00</span>
                            <span class="new-price">75.00</span>
                        </div>
                        <p>افضل منتج للاستخدام وذلك باجماع الخبراء</p>
                        <ul class="products-info">
                            <li><span>التوفر:</span> <a href="#!"> متاح </a></li>
                        </ul>
                        <div class="product-quantities">
                            <span>الكمية:</span>
                            <div class="input-counter">
                  <span class="minus-btn">
                    <i class="far fa-minus"></i>
                  </span>
                                <input type="number" value="0">
                                <span class="plus-btn">
                    <i class="far fa-plus"></i>
                  </span>
                            </div>
                        </div>

                        <!-- <div class="products-share">
                          <ul class="social">
                            <li><span>مشاركة :</span></li>
                            <li>
                              <a href="#!" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            </li>
                            <li>
                              <a href="#!" target="_blank"><i class="fab fa-twitter"></i></a>
                            </li>
                            <li>
                              <a href="#!" target="_blank"><i class="fab fa-whatsapp"></i></a>
                            </li>
                            <li>
                              <a href="#!" target="_blank"><i class="fab fa-instagram"></i></a>
                            </li>
                          </ul>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--////////////////////////////////////////////////////////////////////////////////-->
<!--////////////////////////////////////////////////////////////////////////////////-->
<!--////////////////////////////////////////////////////////////////////////////////-->
<!--/////////////////////////////JavaScript/////////////////////////////////////////-->
<!--////////////////////////////////////////////////////////////////////////////////-->
<!--////////////////////////////////////////////////////////////////////////////////-->
<!--////////////////////////////////////////////////////////////////////////////////-->
<script src="newSite/js/jquery-3.5.1.min.js"></script>
<script src="newSite/js/popper.min.js"></script>
<script src="newSite/js/jquery.appear.min.js"></script>
<script src="newSite/js/bootstrap.min.js"></script>
{{--<script src="newSite/js/mdb.min.js"></script>--}}
<script src="newSite/js/swiper.js"></script>
<script src="newSite/js/wow.min.js"></script>
<script src="newSite/js/jquery.fancybox.min.js"></script>
<script src="newSite/js/fontawesome-pro.js"></script>
<script src="newSite/js/odometer.min.js"></script>
<script src="newSite/js/select2.js"></script>
<script src="newSite/js/ma5-menu.min.js"></script>
<script src="newSite/js/Custom.js"></script>
@toastr_js
@toastr_render
<script>
    @if($langg->rtl != "1")
    ma5menu({
        menu: '.site-menu',
        activeClass: 'active',
        position: 'left',
        closeOnBodyClick: true
    });
    @else
    ma5menu({
        menu: '.site-menu',
        activeClass: 'active',
        position: 'right',
        closeOnBodyClick: true
    });
    @endif
</script>
<script>
    var mainurl = "<?php echo e(url('/')); ?>";
    var gs = <?php echo json_encode(\App\Models\Generalsetting::first()->makeHidden(['stripe_key', 'stripe_secret', 'smtp_pass', 'instamojo_key', 'instamojo_token', 'paystack_key', 'paystack_email', 'paypal_business', 'paytm_merchant', 'paytm_secret', 'paytm_website', 'paytm_industry', 'paytm_mode', 'molly_key', 'razorpay_key', 'razorpay_secret'])); ?>;
    var langg = <?php echo json_encode($langg); ?>;

    $(document).on('click', '.add-to-compare', function () {
        $.get($(this).data('href'), function (data) {
            $("#Compare").html(data[1]);
            if (data[0] == 0) {
                toastr.success(langg.add_compare);
            } else {
                toastr.error(langg.already_compare);
            }

        });
        return false;
    });

    $(document).on('click', '.add-to-wish', function () {
        $.get($(this).data('href'), function (data) {

            if (data[0] == 1) {
                toastr.success(langg.add_wish);
                $('#wishlist-count').html(data[1]);

            } else {

                toastr.error(langg.already_wish);
            }

        });

        return false;
    });

    $(document).on('click', '.add-to-cart', function () {


        $.get($(this).data('href'), function (data) {

            if (data == 'digital') {
                toastr.error(langg.already_cart);
            } else if (data == 0) {
                toastr.error(langg.out_stock);
            } else {
                $("#count").html(data[0]);
                $("#cart-items").load(mainurl + '/carts/view');
                toastr.success(langg.add_cart);
            }
        });
        return false;
    });


</script>

</body>

</html>
