<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(\App::getLocale() == "ar")  style="direction:rtl" @endif>
<head>
    @include('admin.layouts.inc.head')
</head>
<?php $themeDir = App::getLocale() == 'ar' ? 'rtl' : 'ltr'; ?>
<body class="{{$themeDir}} main-body leftmenu">
    <!-- Loader -->
    {{--<div id="global-loader">
        <img src="{{ asset('assets/img/loader.svg') }}" class="loader-img" alt="Loader">
    </div>--}}
    <!-- End Loader -->

    <!-- Loading Modal -->
    @include('admin.component.inc.loading')
    <!-- Loading Modal End -->

    <!-- Page -->
    <div class="page">

        <!-- Main Header-->
        @include('admin.layouts.inc.header')
        <!-- End Main Header-->

        <!-- Sidemenu -->
        @include('admin.layouts.inc.menu')
        <!-- End Sidemenu -->

        <!-- Main Content-->
        <div class="main-content side-content pt-0">
            <div class="main-container container-fluid">
                <div class="inner-body">
                    <!-- Page Header -->
                    <div class="page-header">
                        @include('admin.layouts.inc.breadcrumb')
                        <div class="d-flex">
                            <div class="justify-content-center">
                                @yield('buttons')
                            </div>
                        </div>
                    </div>
                    <!-- End Page Header -->

                    @if (!isAdmin())
                        @if (!isBranchMaster())

                            @if (is_null(\Auth::user()->accepted_at))
                                @if (is_null(\Auth::user()->rejected_at))
                                    <div class="alert alert-danger" role="alert">
                                        @lang('Plase waiting to accepted your club')
                                    </div>
                                @else
                                    <div class="alert alert-danger" role="alert">
                                        @lang('Oops, Your Club Has Been Rejected')
                                        <h6>@lang('Reject Message'):</h6>
                                        {!! \Auth::user()->rejected_message !!}
                                    </div>
                                @endif
                                <br/>
                            @else

                                @php
                                    $clubValidation = [];
                                @endphp
                                @if (is_null(\Auth::user()->about) || is_null(\Auth::user()->about_en))
                                    @php
                                        $clubValidation[] = [
                                            "title" => __("Please Complete Your Account"),
                                            "route" => route("admin.clubs.edit",\Auth::user()->id),
                                        ];
                                    @endphp
                                @endif
                                @if (count(\Auth::user()->clubBranches) == 0)
                                    @php
                                        $clubValidation[] = [
                                            "title" => __("Please Added Your Branchs"),
                                            "route" => route("admin.clubs.branches.index"),
                                        ];
                                    @endphp
                                @endif
                                @if (count(\Auth::user()->clubActivities) == 0)
                                    @php
                                        $clubValidation[] = [
                                            "title" => __("Please Added Your Activities"),
                                            "route" => route("admin.clubs.activities.index"),
                                        ];
                                    @endphp
                                @endif
                                @if (count(\Auth::user()->clubImages) == 0)
                                    @php
                                        $clubValidation[] = [
                                            "title" => __("Please Added Your Images"),
                                            "route" => route("admin.clubs.edit",\Auth::user()->id),
                                        ];
                                    @endphp
                                @endif

                                @if (count($clubValidation) > 0)
                                    <div class="alert alert-secondary" role="alert">
                                        <ul>
                                            @foreach ($clubValidation as $v)
                                                <li>
                                                    <a href="{{ $v['route'] }}" style=" color: black; font-weight: 900; ">
                                                        {{ $v['title'] }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <br/>
                                @endif

                            @endif

                        @endif
                    @endif


                    <!-- Row -->
                    @yield('PageContent')
                    <!-- End Row -->
                </div>
            </div>
        </div>
        <!-- End Main Content-->

        <!-- Main Footer-->
        @include('admin.layouts.inc.footer')
        <!--End Footer-->

    </div>
    <!-- End Page -->

    <!-- Back-to-top -->
    <a href="#top" id="back-to-top"><i class="fe fe-arrow-up"></i></a>

    @include('admin.layouts.inc.scripts')
</body>
</html>
