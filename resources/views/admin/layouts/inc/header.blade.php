<div class="main-header side-header sticky">
    <div class="main-container container-fluid">
        <div class="main-header-left mmenu">
            <a class="main-header-menu-icon" href="javascript:void(0)" id="mainSidebarToggle"><span></span></a>
            <div class="hor-logo">
                <a class="main-logo" href="{{route('admin.home.index')}}">
                    <img src="{{ getSettings("logo",asset('/dashboard.svg')) }}" height="45px;" class="header-brand-img desktop-logo" alt="logo">
                    <img src="{{ getSettings("logo",asset('/dashboard.svg')) }}" height="45px;" class="header-brand-img desktop-logo-dark"
                        alt="logo">
                </a>
            </div>
        </div>
        <div class="main-header-center">
            <div class="responsive-logo">
                <a href="{{route('admin.home.index')}}"><img src="{{ getSettings("logo",asset('/dashboard.svg')) }}" height="45px;" class="mobile-logo" alt="logo"></a>
                <a href="{{route('admin.home.index')}}"><img src="{{ getSettings("logo",asset('/dashboard.svg')) }}" height="45px;" class="mobile-logo-dark"
                        alt="logo"></a>
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
                        <!-- Change Lang -->
                        <div>
                            @if (\App::getLocale() == "ar")
                                <a class="nav-link" hreflang="en" href="{{ LaravelLocalization::getLocalizedURL("en", null, [], true) }}" style="color:#adc266;">
                                      EN
                                </a>
                            @else
                                <a class="nav-link" hreflang="ar" href="{{ LaravelLocalization::getLocalizedURL("ar", null, [], true) }}" style="color:#adc266;">
                                    العربية
                                </a>
                            @endif
                        </div>
                        <!-- Change Lang -->
                        <!-- Theme-Layout -->
                        <div class="dropdown d-flex main-header-theme">
                            <a class="nav-link icon layout-setting">
                                <span class="dark-layout">
                                    <i class="fe fe-sun header-icons"></i>
                                </span>
                                <span class="light-layout">
                                    <i class="fe fe-moon header-icons"></i>
                                </span>
                            </a>
                        </div>
                        <!-- Theme-Layout -->
                        <!-- Full screen -->
                        <div class="dropdown ">
                            <a class="nav-link icon full-screen-link">
                                <i class="fe fe-maximize fullscreen-button fullscreen header-icons"></i>
                                <i class="fe fe-minimize fullscreen-button exit-fullscreen header-icons"></i>
                            </a>
                        </div>
                        <!-- Full screen -->
                        <!-- Notification -->
                        @if (isAdmin())
                            <div class="">
                                <a class="nav-link icon" href="{{route('admin.notifications.index')}}">
                                    <i class="fe fe-bell header-icons"></i>
                                </a>
                            </div>
                        @else
                            <div class="dropdown main-header-notification">
                                <a class="nav-link icon" href="">
                                    <i class="fe fe-bell header-icons"></i>
                                    <span class="badge bg-danger nav-link-badge">{{getUnSeenAdminNotificationsCount()}}</span>
                                </a>
                                <div class="dropdown-menu scrollable" id="notifications-div">
                                    @if (getUnSeenAdminNotificationsCount() == 0)
                                        <div class="header-navheading">
                                            <p class="main-notification-text">
                                                @lang('No Notifications found')
                                            </p>
                                        </div>
                                        <div class="dropdown-footer">
                                            <a href="{{route('admin.notifications.index')}}">@lang('View All Notifications')</a>
                                        </div>
                                    @else
                                        <div class="main-notification-list">
                                            @include('admin.layouts.inc.notifications_data', [
                                                'notifications' => getUnSeenAdminNotificationsTake()
                                            ])
                                        </div>
                                        <div class="dropdown-footer">
                                            <a href="{{route('admin.notifications.index')}}">@lang('View All Notifications')</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <!-- Notification -->
                        <!-- Profile -->
                        <div class="dropdown main-profile-menu">
                            <a class="d-flex" href="javascript:void(0)">
                                <span class="main-img-user">
                                    <img
                                        alt="avatar"
                                        src="{{ \Auth::user()->display_image }}">
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="header-navheading">
                                    <h6 class="main-notification-title">{{ Auth::user()->name }}</h6>
                                    <p class="main-notification-text">
                                        @foreach (\Auth::user()->roles()->get() as $role)
                                            {{ __($role->name) }},
                                        @endforeach
                                    </p>
                                </div>

                                @php
                                    $show = true;
                                    if(!isAdmin()) {
                                        if(is_null(\Auth::user()->accepted_at)) {
                                            $show = false;
                                        }
                                    }
                                @endphp
                                @if ($show)
                                    <a class="dropdown-item border-top" href="{{ route('admin.profile.index') }}">
                                        <i class="fe fe-user"></i> @lang('My Profile')
                                    </a>
                                    <a class="dropdown-item border-top" href="{{ route('admin.change_password.index') }}">
                                        <i class="fe fe-lock"></i> @lang('Change My Password')
                                    </a>
                                @endif
                                <a class="dropdown-item" href="{{ route('admin.logout') }}">
                                    <i class="fe fe-power"></i> @lang('Logout')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
