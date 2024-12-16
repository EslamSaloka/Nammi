<!doctype html>
<html lang="{{ \App::getLocale() }}" @if(App::isLocale('ar')) dir="rtl" @else dir="ltr" @endif>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="description" content="{{ env('APP_NAME') }}">
    <meta name="author" content="{{ env('APP_NAME') }}">
    <meta name="keywords" content="{{ env('APP_NAME') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ url(getSettings('website_logo','/assets_website/img/brand/fav.png')) }}" type="image/x-icon" />

    <!-- Title -->
    <title>{{ env('APP_NAME') }}</title>

    <!-- BOOTSTRAP CSS -->
    @if(App::isLocale('ar'))
        <link id="style" href="assets_website/plugins/bootstrap/css/bootstrap.rtl.min.css" rel="stylesheet" />
    @else
        <link id="style" href="assets_website/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    @endif

    <!-- Icons css-->
    <link href="{{ url('/assets_website/plugins/web-fonts/icons.css') }}" rel="stylesheet" />
    <link href="{{ url('/assets_website/plugins/web-fonts/font-awesome/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ url('/assets_website/plugins/web-fonts/plugin.css') }}" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{ url('/assets_website/css/style.css') }}" rel="stylesheet" />

    <!-- Internal Jquery.Coutdown css-->
    <link href="{{ url('/assets_website/plugins/jquery-countdown/jquery.countdown.css') }}" rel="stylesheet">

    @if(App::isLocale('ar'))
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@500&display=swap" rel="stylesheet">
        <style>
            * {
                font-family: 'Tajawal', sans-serif;
            }

            *::-webkit-scrollbar {
                height: 10px !important;
            }
            @media (max-width: 991.98px) {
                .landing-page.app.sidenav-toggled .app-sidebar {
                    right: 0;
                    text-align: right;
                }
            }
            @media (max-width: 991.98px) {
                .landing-page .side-menu {
                    padding: 0;
                    margin-top: 20px;
                }
            }
        </style>
    @endif

    @if(App::isLocale('en'))
        <style>
            @media (max-width: 991.98px) {
                .landing-page .side-menu {
                    padding: 0;
                    margin-top: 30px;
                }
            }
        </style>

    @endif

</head>

<body class="app sidebar-mini ltr landing-page horizontalmenu" @if(App::isLocale('ar')) style="direction: rtl;" @endif>

    <!-- Loader -->
    <div id="global-loader">
        <img src="/assets_website/img/loader.svg" class="loader-img" alt="Loader">
    </div>
    <!-- End Loader -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main" id="home">

            <!-- Main Header-->
            <div class="main-header side-header">
                <div class="main-container container-fluid">
                    <div class="main-header-left">
                        <a class="main-header-menu-icon" href="javascript:void(0)"
                            id="mainSidebarToggle"><span></span></a>
                        <div class="hor-logo">
                            <a class="main-logo" href="{{ url('/') }}">
                                <img src="{{ url(getSettings('logo','/logo.png')) }}" class="header-brand-img desktop-logo"
                                    alt="logo" style=" width: 85px !important; ">
                                <img src="{{ url(getSettings('logo','/logo.png')) }}" class="header-brand-img desktop-logo-dark"
                                    alt="logo" style=" width: 85px !important; ">
                            </a>
                        </div>
                    </div>
                    <div class="main-header-center">
                        <div class="responsive-logo">
                            <a href="{{ url('/') }}">
                                <img src="{{ url(getSettings('logo','/logo.png')) }}" class="mobile-logo" alt="logo" style=" width: 75px !important; ">
                                </a>
                            <a href="{{ url('/') }}">
                                <img src="{{ url(getSettings('logo','/logo.png')) }}" class="mobile-logo-dark" alt="logo" style=" width: 75px !important; ">
                            </a>
                        </div>
                    </div>
                    <div class="main-header-right">
                        <button class="navbar-toggler navresponsive-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fe fe-more-vertical header-icons navbar-toggler-icon"></i>
                        </button><!-- Navresponsive closed -->
                        <div
                            class="navbar navbar-expand-lg  nav nav-item  navbar-nav-right responsive-navbar navbar-dark  ">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                <div class="d-flex order-lg-2 ms-auto">
                                    <!-- SEARCH -->
                                    <div class="header-nav-right p-3">
                                        @if (\Auth::check() == false)
                                            <a href="{{ route('admin.register.index') }}" class="btn ripple btn-min w-lg btn-outline-light mb-3 me-2">
                                                @lang('Join US')
                                            </a>

                                            @php
                                                $getLocale = (\App::getLocale() == "ar") ? 'en' : 'ar';
                                            @endphp
                                            <a
                                                href="{{ LaravelLocalization::getLocalizedURL($getLocale, null, [], true) }}"
                                                hreflang="{{ $getLocale }}"
                                                data-language="{{ $getLocale }}"
                                                class="btn ripple btn-min w-lg mb-3 me-2 btn-light">
                                                {{ config('laravellocalization.supportedLocales')[$getLocale]['name'] }}
                                            </a>
                                        @else
                                            <a href="{{ route('admin.profile.index') }}" class="btn ripple btn-min w-lg btn-outline-light mb-3 me-2">
                                                {{\Auth::user()->name}}
                                            </a>
                                            @php
                                                $getLocale = (\App::getLocale() == "ar") ? 'en' : 'ar';
                                            @endphp
                                            <a
                                                href="{{ LaravelLocalization::getLocalizedURL($getLocale, null, [], true) }}"
                                                hreflang="{{ $getLocale }}"
                                                data-language="{{ $getLocale }}"
                                                class="btn ripple btn-min w-lg mb-3 me-2 btn-light">
                                                {{ config('laravellocalization.supportedLocales')[$getLocale]['name'] }}
                                            </a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Main Header-->

            <div class="landing-top-header overflow-hidden">
                <div  class="top sticky">
                    <!--APP-SIDEBAR-->
                    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
                    <div class="app-sidebar bg-transparent">
                        <div class="container">
                            <div class="row">
                                <div class="main-sidemenu navbar px-0">
                                    <a class="main-logo" href="{{ url('/') }}">
                                        <img src="{{ url(getSettings('logo','/logo.png')) }}" class="header-brand-img desktop-logo"
                                            alt="logo" style=" width: 85px !important; ">
                                        <img src="{{ url(getSettings('logo','/logo.png')) }}"
                                            class="header-brand-img desktop-logo-dark" alt="logo" style=" width: 85px !important; ">
                                    </a>
                                    <div class="slide-left disabled" id="slide-left"><svg
                                            xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                                        </svg></div>
                                    <ul class="side-menu">
                                        <li class="slide">
                                            <a class="side-menu__item active" data-bs-toggle="slide" href="#home"><span
                                                    class="side-menu__label">@lang('Home')</span></a>
                                        </li>
                                        <li class="slide">
                                            <a class="side-menu__item" data-bs-toggle="slide" href="#Features"><span
                                                    class="side-menu__label">@lang('Features')</span></a>
                                        </li>
                                        <li class="slide">
                                            <a class="side-menu__item" data-bs-toggle="slide" href="#Faqs"><span
                                                    class="side-menu__label">@lang("FAQ'S")</span></a>
                                        </li>
                                        <li class="slide">
                                            <a class="side-menu__item" data-bs-toggle="slide" href="#Clients"><span
                                                    class="side-menu__label">@lang('Testimonials')</span></a>
                                        </li>
                                        <li class="slide">
                                            <a class="side-menu__item" data-bs-toggle="slide" href="#Contact"><span
                                                    class="side-menu__label">@lang('Contact Us')</span></a>
                                        </li>

                                    </ul>
                                    <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg"
                                            fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                                            <path
                                                d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                                        </svg></div>
                                    <div class="header-nav-right d-none d-lg-block">
                                        @if (\Auth::check() == false)
                                            <a href="{{ route('admin.register.index') }}" class="btn ripple btn-min w-lg btn-outline-light mb-3 me-2">
                                                @lang('Join US')
                                            </a>

                                            @php
                                                $getLocale = (\App::getLocale() == "ar") ? 'en' : 'ar';
                                            @endphp
                                            <a
                                                href="{{ LaravelLocalization::getLocalizedURL($getLocale, null, [], true) }}"
                                                hreflang="{{ $getLocale }}"
                                                data-language="{{ $getLocale }}"
                                                class="btn ripple btn-min w-lg mb-3 me-2 btn-light">
                                                {{ config('laravellocalization.supportedLocales')[$getLocale]['name'] }}
                                            </a>
                                        @else
                                            <a href="{{ route('admin.profile.index') }}" class="btn ripple btn-min w-lg btn-outline-light mb-3 me-2">
                                                {{\Auth::user()->name}}
                                            </a>
                                            @php
                                                $getLocale = (\App::getLocale() == "ar") ? 'en' : 'ar';
                                            @endphp
                                            <a
                                            href="{{ LaravelLocalization::getLocalizedURL($getLocale, null, [], true) }}"
                                            hreflang="{{ $getLocale }}"
                                            data-language="{{ $getLocale }}"
                                            class="btn ripple btn-min w-lg mb-3 me-2 btn-light">
                                                {{ config('laravellocalization.supportedLocales')[$getLocale]['name'] }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/APP-SIDEBAR-->
                </div>
                <div class="demo-screen-headline main-demo main-demo-1 spacing-top overflow-hidden reveal" >
                    <div class="container px-sm-0">
                        <div class="row">

                            @if(App::isLocale('ar'))
                            <div class="col-xl-6 col-lg-6 animation-zidex pos-relative" style="text-align:right !important;">
                                @else
                                    <div class="col-xl-6 col-lg-6 animation-zidex pos-relative">
                                @endif


                                @if(App::isLocale('ar'))
                                            <h1 class="text-start fw-bold" style="text-align:right !important;">
                                                @else
                                                    <h1 class="text-start fw-bold">
                                                @endif
                                    {!! getSettings('home_title_'.App::getLocale()) !!}
                                </h1>
                                <h6 class="pb-3">
                                    {!! getSettings('home_content_'.App::getLocale()) !!}
                                </h6>

                                <a href="{{ getSettings('google_play') }}"
                                    target="_blank" class="">
                                    <img src="assets_website/landing/g.png" style="width:38%;">
                                </a>
                                <a href="{{ getSettings('apple_store') }}"
                                   target="_blank" class="">
                                    <img src="assets_website/landing/q.png" style="width:30%;">
                                </a>

                            </div>
                            <div class="col-xl-6 col-lg-6 my-auto">
                                <img src="{{ (new \App\Support\Image)->displayImage(getSettings('home_image')) }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--app-content open-->
            <div class="main-content mt-0" >
                <div class="side-app">

                    <!-- ROW-2 OPEN -->
                    <div class="sptb section bg-white" id="Features" style="padding-top:10px !important;">
                        <div class="container">
                            <div class="row">
                                <h4 class="text-center fw-medium landing-card-header">@lang('Features')</h4>
                                <span class="landing-title"></span>
                                <h2 class="fw-semibold text-center">@lang('Nammi Main Features')</h2>
                                <p class="text-default mb-5 text-center">@lang("We provide an awesome services for users to easily book favourite activity in easy way")</p>
                                <div class="row mt-7">
                                    @foreach (\App\Models\Landing\Features::all() as $one)
                                        <div class="col-lg-3 col-md-12">
                                            <div class="card features main-features main-features-1 wow fadeInUp reveal revealleft"
                                                data-wow-delay="0.1s">
                                                <div class="card-body">
                                                    <div class="counter-body-2">
                                                        <div class="bg-img mb-2 text-left hexagon-wrapper">
                                                            <div class="counter-icon hexagon">
                                                                <img src="{{ $one->display_image }}" style="height:45px;width:45px;"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-left counter-body">
                                                        <h4 class="fw-bold">{{ $one->name }}</h4>
                                                        <p class="mb-0">
                                                            {!! $one->content !!}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ROW-2 CLOSED -->

                    <!-- ROW-1 OPEN Counters-->
                    <div class="testimonial-owl-landing">
                        <div class="container">
                            <div class="row">
                                <div class="card bg-transparent mb-0">
                                    <div class="demo-screen-skin code-quality" id="dependencies">
                                        <div class="text-center p-0">
                                            <div class="row justify-content-center">
                                                <div class="row text-center services-statistics landing-statistics">
                                                    <div class="col-xl-12">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="row">
                                                                        @foreach (\App\Models\Landing\Count::all() as $one)
                                                                            <div class="col-xl-3 col-md-6 col-lg-3">
                                                                                <div class="card reveal">
                                                                                    <div class="bg-transparent">
                                                                                        <div class="counter-status">
                                                                                            <div class="counter-icon">
                                                                                                <img src="{{ $one->display_image }}" style=" z-index: 999; "/>
                                                                                            </div>
                                                                                            <div
                                                                                                class="test-body text-center">
                                                                                                <h1 class=" mb-0">
                                                                                                    <span class="counter fw-semibold counter" style="color:#000 !important;">
                                                                                                        {{ $one->count }}
                                                                                                    </span>
                                                                                                    <span class="" style="color:#000 !important;">+</span>
                                                                                                </h1>
                                                                                                <div class="counter-text">
                                                                                                    <h5 class="font-weight-normal mb-0" style="color:#000 !important;">
                                                                                                        {{ $one->name }}
                                                                                                    </h5>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ROW-1 CLOSED -->

                    <!-- ROW-7 OPEN -->
                    <div class="section" id="Faqs">
                        <div class="container">
                            <div class="row">
                                <h4 class="text-center fw-semibold landing-card-header">@lang("FAQ'S") </h4>
                                <span class="landing-title"></span>
                                <h2 class="text-center fw-semibold">@lang('We are here to help you')</h2>
                                <div class="row justify-content-center">
                                    <p class="col-xl-9 wow fadeInUp text-default sub-text mb-5" data-wow-delay="0s"></p>
                                </div>
                                <section class="sptb demo-screen-demo" id="faqs">
                                    <div class="row align-items-top">
                                        <div class="col-md-12 col-lg-6">
                                            @php
                                                $faqIndex = 1;
                                            @endphp
                                            @foreach (\App\Models\FAQ::all() as $faq)
                                                <div class="col-md-12 grid-item px-0" >
                                                    <div
                                                        class="card card-collapsed bg-primary-transparent  border  p-0 reveal" style="background-color: transparent !important;">
                                                        <div class="card-header grid-link" data-bs-toggle="card-collapse">
                                                            <a href="#"
                                                                class="card-options-collapse h5 fw-bold card-title mb-0  text-primary" style="color:#000 !important;">
                                                                <span class="me-3 fs-18 fw-bold" style="color:#000 !important;">{{ $faqIndex }}.</span>
                                                                {{ $faq->question }}
                                                            </a>
                                                        </div>
                                                        <div class="card-body pt-0">
                                                            @if (App::isLocale('ar'))
                                                            <p style="text-align:right;color:#000 !important;">{!! $faq->answer !!}</p>
                                                            @else
                                                                <p>{!! $faq->answer !!}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                @php
                                                    $faqIndex++
                                                @endphp
                                            @endforeach
                                        </div>
                                        <div class="col-md-12 col-lg-6 reveal revealright">
                                            <img src="/assets_website/landing/images/frequently-asked-questions1.png" alt="">
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                    <!-- ROW-7 CLOSED -->

                    <!-- ROW-9 OPEN -->
                    <div class="testimonial-owl-landing section pb-0" style="background:#f8f9fb !important;" id="Clients">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card bg-transparent">
                                        <div class="card-body pt-5">
                                            <h4 class="text-center fw-semibold">@lang('Testimonials') </h4>
                                            <span class="landing-title"></span>
                                            <h2 class="text-center fw-semibold text-white mb-5" style="margin-top:20px;">
                                                @lang('What People Are Saying About Our Product').
                                            </h2>

                                            <div class="testimonial-carousel">
                                                @foreach (\App\Models\Landing\FeedBack::all() as $one)
                                                    <div class="slide text-center">
                                                        <div class="row">
                                                            <div class="col-xl-8 col-md-12 d-block mx-auto">
                                                                <div class="testimonia">
                                                                    <p style="color:#000 !important;">
                                                                        <i class="fa fa-quote-left fs-20 text-white-80" style="color:#000 !important;"></i>
                                                                        {!! $one->content !!}
                                                                    </p>
                                                                    <h3 class="title" style="color:#000 !important;">{{ $one->name }}</h3>
                                                                    <div class="rating-stars block my-rating-5 mb-5" data-rating="{{ $one->star }}"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ROW-9 CLOSED -->

                    <!-- ROW-10 OPEN -->
                    <div class="section pb-0" id="Contact">
                        <div class="container">
                            <div class="">
                                <div class="card reveal p-5 mb-0 ">
                                    <h4 class="text-center fw-semibold mt-7 landing-card-header ">@lang('Contact Us')</h4>
                                    <span class="landing-title"></span>
                                    <h2 class="text-center fw-semibold mb-0 px-2">@lang('Get in Touch with') <span
                                            class="text-primary">@lang('US').</span></h2>
                                    <div class="card-body text-dark">
                                        <div class="statistics-info">
                                            <div class="row justify-content-center">
                                                <div class="col-lg-12">
                                                    <div class="mt-3">
                                                        <div class="text-dark">
                                                            <div class="services-statistics reveal my-5">
                                                                <div class="row text-center">
                                                                    <div class="col-xl-3 col-md-6 col-lg-6">
                                                                        <div class="card">
                                                                            <div class="card-body p-0">
                                                                                <div class="counter-status">
                                                                                    <div
                                                                                        class="counter-icon bg-primary-transparent box-shadow-primary">
                                                                                        <i
                                                                                            class="fe fe-map-pin text-primary fs-23"></i>
                                                                                    </div>
                                                                                    <h4 class="mb-2 fw-semibold">
                                                                                        @lang('Address')</h4>
                                                                                    <p class="title-desc mb-1">
                                                                                        @if (\App::getLocale() == "ar")
                                                                                            {{ getSettings("main_branch_ar") }}
                                                                                        @else
                                                                                            {{ getSettings("main_branch_en") }}
                                                                                        @endif
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-md-6 col-lg-6">
                                                                        <div class="card">
                                                                            <div class="card-body p-0">
                                                                                <div class="counter-status">
                                                                                    <div
                                                                                        class="counter-icon bg-secondary-transparent box-shadow-secondary">
                                                                                        <i
                                                                                            class="fe fe-headphones text-secondary fs-23"></i>
                                                                                    </div>
                                                                                    <h4 class="mb-2 fw-semibold">
                                                                                        @lang('Phone') </h4>
                                                                                    <p class="mb-0">{{ getSettings("phone") }}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-md-6 col-lg-6">
                                                                        <div class="card">
                                                                            <div class="card-body p-0">
                                                                                <div class="counter-statuss">
                                                                                    <div
                                                                                        class="counter-icon bg-success-transparent box-shadow-success">
                                                                                        <i
                                                                                            class="fe fe-mail text-success fs-23"></i>
                                                                                    </div>
                                                                                    <h4 class="mb-2 fw-semibold">
                                                                                        @lang('Email')</h4>
                                                                                    <p class="mb-0">{{ getSettings("email") }}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-md-6 col-lg-6">
                                                                        <div class="card">
                                                                            <div class="card-body p-0">
                                                                                <div class="counter-status">
                                                                                    <div
                                                                                        class="counter-icon bg-danger-transparent box-shadow-danger">
                                                                                        <i
                                                                                            class="fe fe-airplay text-danger fs-23"></i>
                                                                                    </div>
                                                                                    <h4 class="mb-2 fw-semibold">
                                                                                        @lang('Working Hours')</h4>
                                                                                    <p class="mb-0">
                                                                                        {{ getSettings('working_hours') }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ROW-10 CLOSED -->




                </div>
            </div>
            <!--app-content closed-->
        </div>
        <div class="pos-relative">
            <div class="shape overflow-hidden bottom-footer-shape">
                <svg viewBox="0 0 2880 48" fill="none" xmsns="http://www.w3.org/2000/svg">
                    <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="#0e0e23"></path>
                </svg>
            </div>
        </div>
        <!-- FOOTER OPEN -->
        <div class="demo-footer">
            <div class="container">
                <div class="row">
                    <div class="card mb-0">
                        <div class="card-body p-0">
                            <div class="top-footer">
                                <div class="row">

                                    <div class="col-lg-12 col-sm-12 col-md-4 reveal revealleft">

                                        <div class=" mt-6">
                                            <a href="{{ getSettings('facebook') }}" type="button" class="btn btn-icon rounded-pill">
                                                <i class="fe fe-facebook"></i>
                                            </a>
                                            <a href="{{ getSettings('twitter') }}" type="button" class="btn btn-icon rounded-pill">
                                                <i class="fe fe-twitter"></i>
                                            </a>
                                            <a href="{{ getSettings('instagram') }}" type="button" class="btn btn-icon rounded-pill">
                                                <i class="fe fe-instagram"></i>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <hr class="mb-0">
                            <footer class="main-footer px-0 text-center">
                                <div class="row ">
                                    <div class="col-md-12 col-sm-12">
                                         <span id="year"></span> <a href="{{ env('COMPANY_URL') }}">{{ env('COMPANY_NAME') }}</a> @lang('All rights Reserved')
                                    </div>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FOOTER CLOSED -->
    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- Jquery js-->
    <script src="{{ url('/assets_website/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ url('/assets_website/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ url('/assets_website/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- COUNTERS JS-->
    <script src="{{ url('/assets_website/plugins/counters/counterup.min.js') }}"></script>
    <script src="{{ url('/assets_website/plugins/counters/waypoints.min.js') }}"></script>
    <script src="{{ url('/assets_website/plugins/counters/counters-1.js') }}"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="{{ url('/assets_website/plugins/owl-carousel/owl.carousel.js') }}"></script>
    <script src="{{ url('/assets_website/landing/lib/company-slider/slider.js') }}"></script>

    <!--- TABS JS -->
    <script src="{{ url('/assets_website/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
    <script src="{{ url('/assets_website/plugins/tabs/tab-content.js') }}"></script>

    <!-- Star Rating Js-->
    <script src="{{ url('/assets_website/plugins/rating/jquery-rate-picker.js') }}"></script>
    <script src="{{ url('/assets_website/plugins/rating/rating-picker.js') }}"></script>

    <!-- Star Rating-1 Js-->
    <script src="{{ url('/assets_website/plugins/ratings-2/jquery.star-rating.js') }}"></script>
    <script src="{{ url('/assets_website/plugins/ratings-2/star-rating.js') }}"></script>

    <!-- SIDE-MENU JS -->
    <script src="{{ url('/assets_website/landing/js/sidemenu.js') }}"></script>

    <!-- Sticky js -->
    <script src="{{ url('/assets_website/js/sticky.js') }}"></script>

    <!-- CUSTOM JS -->
    @if(App::isLocale('ar'))
        <script src="{{ url('/assets_website/landing/js/landing-rtl.js') }}"></script>
    @else
        <script src="{{ url('/assets_website/landing/js/landing.js') }}"></script>
    @endif

</body>

</html>
