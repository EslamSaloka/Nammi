<div class="sticky">
    <div class="main-menu main-sidebar main-sidebar-sticky side-menu">
        <div class="main-sidebar-header main-container-1 active">
            <div class="sidemenu-logo">
                <a class="main-logo" href="{{route('admin.home.index')}}">
                    <img src="{{ getSettings("logo",asset('/dashboard.svg')) }}" height="45px;" class="header-brand-img desktop-logo" alt="logo">
                    <img src="{{ getSettings("logo",asset('/dashboard.svg')) }}" height="45px;" class="header-brand-img icon-logo" alt="logo">
                    <img src="{{ getSettings("logo",asset('/dashboard.svg')) }}" class="header-brand-img desktop-logo theme-logo" alt="logo">
                    <img src="{{ getSettings("logo",asset('/dashboard.svg')) }}" class="header-brand-img icon-logo theme-logo" alt="logo">
                </a>
            </div>


            @php
                $show = true;
                if(!isAdmin()) {
                    if(is_null(\Auth::user()->accepted_at)) {
                        $show = false;
                    }
                }
            @endphp



            <div class="main-sidebar-body main-body-1">
                <div class="slide-left disabled" id="slide-left"><i class="fe fe-chevron-left"></i></div>
                <ul class="menu-nav nav">
                    <li class="nav-item  @if( Str::contains(Route::currentRouteName(), 'home') ) active @endif">
                        <a class="nav-link" href="{{ route('admin.home.index') }}">
                            <span class="shape1"></span>
                            <span class="shape2"></span>
                            <i class="ti-home sidemenu-icon menu-icon "></i>
                            <span class="sidemenu-label">@lang('Home')</span>
                        </a>
                    </li>

                    @canAny('admins.index')
                        <li class="nav-item  @if( Str::contains(Route::currentRouteName(), 'admins') ) active @endif">
                            <a class="nav-link" href="{{ route('admin.admins.index') }}">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="fa fa-user-shield sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">@lang('Admins')</span>
                            </a>
                        </li>
                    @endcanAny

                    @canAny('customers.index')
                        <li class="nav-item  @if( Str::contains(Route::currentRouteName(), 'customers') ) active @endif">
                            <a class="nav-link" href="{{ route('admin.customers.index') }}">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="fa fa-user-friends sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">@lang('Customers')</span>
                            </a>
                        </li>
                    @endcanAny

                    @if ($show)

                        @canAny('clubs.index')
                            @php
                                $clubs= [
                                    "admin.clubs.index",
                                    "admin.clubs.edit",
                                    "admin.clubs.create",
                                    "admin.clubs.rates.index",
                                ];
                            @endphp
                            <li class="nav-item  @if(in_array(Route::currentRouteName(),$clubs)) active @endif">
                                <a class="nav-link" href="{{ route('admin.clubs.index') }}">
                                    <span class="shape1"></span>
                                    <span class="shape2"></span>
                                    <i class="fa fa-store sidemenu-icon menu-icon "></i>
                                    <span class="sidemenu-label">
                                        @php
                                            echo (isAdmin()) ? __("Clubs") : __("My Club");
                                        @endphp
                                    </span>
                                </a>
                            </li>
                        @endcanAny

                        @canAny('staffs.index')
                            @php
                                $staffsActive= [
                                    "admin.clubs.staffs.index",
                                    "admin.clubs.staffs.edit",
                                    "admin.clubs.staffs.create",
                                ];
                            @endphp
                            <li class="nav-item @if(in_array(Route::currentRouteName(),$staffsActive)) active @endif">
                                <a class="nav-link" href="{{ route('admin.clubs.staffs.index') }}">
                                    <span class="shape1"></span>
                                    <span class="shape2"></span>
                                    <i class="fa fa-users sidemenu-icon menu-icon "></i>
                                    <span class="sidemenu-label">
                                        @php
                                            echo (isAdmin()) ? __("Club Staffs") : __("My Staffs");                                        @endphp
                                    </span>
                                </a>
                            </li>
                        @endcanAny

                        @canAny('branches.index')
                            @php
                                $branchesActive= [
                                    "admin.clubs.branches.index",
                                    "admin.clubs.branches.edit",
                                    "admin.clubs.branches.create",
                                ];
                            @endphp
                            <li class="nav-item @if(in_array(Route::currentRouteName(),$branchesActive)) active @endif">
                                <a class="nav-link" href="{{ route('admin.clubs.branches.index') }}">
                                    <span class="shape1"></span>
                                    <span class="shape2"></span>
                                    <i class="fa fa-project-diagram sidemenu-icon menu-icon "></i>
                                    <span class="sidemenu-label">
                                        @php
                                            echo (isAdmin()) ? __("Club Branches") : __("My Branches");
                                        @endphp
                                    </span>
                                </a>
                            </li>
                        @endcanAny

                        @canAny('activities.index')
                            @php
                                $activitiesActive= [
                                    "admin.clubs.activities.index",
                                    "admin.clubs.activities.edit",
                                    "admin.clubs.activities.create",
                                    "admin.clubs.activities.rates.index",
                                ];
                            @endphp
                            <li class="nav-item @if(in_array(Route::currentRouteName(),$activitiesActive)) active @endif">
                                <a class="nav-link" href="{{ route('admin.clubs.activities.index') }}">
                                    <span class="shape1"></span>
                                    <span class="shape2"></span>
                                    <i class="fa fa-spa sidemenu-icon menu-icon "></i>
                                    <span class="sidemenu-label">
                                        @php
                                            echo (isAdmin()) ? __("Club Activities") : __("My Activities");
                                        @endphp
                                    </span>
                                </a>
                            </li>
                        @endcanAny

                    @endif

                    @canAny('sliders.index')
                        @php
                            $slidersActive= [
                                "admin.sliders.index",
                                "admin.sliders.edit",
                                "admin.sliders.create",
                            ];
                        @endphp
                        <li class="nav-item @if(in_array(Route::currentRouteName(),$slidersActive)) active @endif">
                            <a class="nav-link" href="{{ route('admin.sliders.index') }}">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="fa fa-images sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">
                                    @lang('Sliders')
                                </span>
                            </a>
                        </li>
                    @endcanAny

                    @canAny('categories.index')
                        <li class="nav-item  @if( Str::contains(Route::currentRouteName(), 'categories') ) active @endif">
                            <a class="nav-link" href="{{ route('admin.categories.index') }}">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="fa fa-boxes sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">@lang('Categories')</span>
                            </a>
                        </li>
                    @endcanAny

                    @canAny('countries.index')
                        <li class="nav-item  @if( Str::contains(Route::currentRouteName(), 'countries') ) active @endif">
                            <a class="nav-link" href="{{ route('admin.countries.index') }}">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="fa fa-flag sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">@lang('Countries')</span>
                            </a>
                        </li>
                    @endcanAny

                    @canAny('times.index')
                        <li class="nav-item  @if( Str::contains(Route::currentRouteName(), 'times') ) active @endif">
                            <a class="nav-link" href="{{ route('admin.times.index') }}">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="fa fa-clock-o sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">@lang('Times Hobbies')</span>
                            </a>
                        </li>
                    @endcanAny

                    @canAny('coupons.index')
                        <li class="nav-item  @if( Str::contains(Route::currentRouteName(), 'coupons') ) active @endif">
                            <a class="nav-link" href="{{ route('admin.coupons.index') }}">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="fa fa-ticket-alt sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">@lang('Coupons')</span>
                            </a>
                        </li>
                    @endcanAny

                    @canAny('contacts.index')
                        <li class="nav-item  @if( Str::contains(Route::currentRouteName(), 'contacts') ) active @endif">
                            <a class="nav-link" href="{{ route('admin.contacts.index') }}">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="fa fa-comment-alt sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">@lang('Contacts')</span>
                                @php
                                    $contactUsCount = \App\Models\Contact::where('seen',0)->count();
                                    @endphp
                                @if($contactUsCount != 0)
                                <span class="badge bg-primary side-badge">
                                    {{ ($contactUsCount > 1000) ? "1000 +" : $contactUsCount }}
                                </span>
                                @endif
                            </a>
                        </li>
                    @endcanAny

                    @canAny('requests.index')
                        <li class="nav-item  @if( Str::contains(Route::currentRouteName(), 'requests') ) active @endif">
                            <a class="nav-link" href="{{ route('admin.requests.index') }}">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="fa fa-praying-hands sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">@lang('Request Join Clubs')</span>
                                @php
                                    $requestsCount = \App\Models\User::whereHas('roles', function($q) {
                                        return $q->where('name', '=', \App\Models\User::TYPE_CLUB);
                                    })->whereNull("accepted_at")->whereNull("rejected_at")->count();
                                    @endphp
                                @if($requestsCount != 0)
                                <span class="badge bg-primary side-badge">
                                    {{ ($requestsCount > 1000) ? "1000 +" : $requestsCount }}
                                </span>
                                @endif
                            </a>
                        </li>
                    @endcanAny

                    @if ($show)

                        @canAny('orders.index')
                            <li class="nav-item  @if( Str::contains(Route::currentRouteName(), 'orders') ) active @endif">
                                <a class="nav-link" href="{{ route('admin.orders.index') }}">
                                    <span class="shape1"></span>
                                    <span class="shape2"></span>
                                    <i class="fa fa-list sidemenu-icon menu-icon "></i>
                                    <span class="sidemenu-label">@lang('Orders')</span>
                                    @if (isAdmin())
                                        @php
                                            $ordersCount = \App\Models\Order::where('order_status',\App\Models\Order::STATUS_PENDING)->count();
                                        @endphp
                                    @else
                                        @php
                                            $ordersCount = \App\Models\Order::where("club_id",Auth::user()->id)->where('order_status',\App\Models\Order::STATUS_PENDING)->count();
                                        @endphp
                                    @endif
                                    @if($ordersCount != 0)
                                        <span class="badge bg-primary side-badge">
                                            {{ ($ordersCount > 1000) ? "1000 +" : $ordersCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endcanAny

                        @if (!isAdmin())
                            @canAny('chats.index')
                                <li class="nav-item  @if( Str::contains(Route::currentRouteName(), 'chats') ) active @endif">
                                    <a class="nav-link" href="{{ route('admin.chats.index') }}">
                                        <span class="shape1"></span>
                                        <span class="shape2"></span>
                                        <i class="fa fa-comment-dots sidemenu-icon menu-icon "></i>
                                        <span class="sidemenu-label">@lang('chats')</span>
                                        @php
                                            $chatCount = \App\Models\Chat\Message::where("seen",0)->where('user_id',"!=",\Auth::user()->id)
                                            ->whereHas('chat',function($q){
                                                return $q->where("club_id",\Auth::user()->id);
                                            })->count();
                                        @endphp
                                        @if($chatCount != 0)
                                            <span class="badge bg-primary side-badge">
                                                {{ ($chatCount > 1000) ? "1000 +" : $chatCount }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                            @endcanAny
                        @endif

                        @canAny('dues.index')
                            <li class="nav-item  @if( Str::contains(Route::currentRouteName(), 'dues') ) active @endif">
                                <a class="nav-link" href="{{ route('admin.dues.index') }}">
                                    <span class="shape1"></span>
                                    <span class="shape2"></span>
                                    <i class="fa fa-chart-line sidemenu-icon menu-icon "></i>
                                    <span class="sidemenu-label">@lang('Dues')</span>
                                </a>
                            </li>
                        @endcanAny

                            @canAny('dues.index')
                                <li class="nav-item  @if( Str::contains(Route::currentRouteName(), 'revenue') ) active @endif">
                                    <a class="nav-link" href="{{ route('admin.revenue.index') }}">
                                        <span class="shape1"></span>
                                        <span class="shape2"></span>
                                        <i class="fa fa-chart-line sidemenu-icon menu-icon "></i>
                                        <span class="sidemenu-label">@lang('Club Revenue')</span>
                                    </a>
                                </li>
                            @endcanAny





                        @canAny('reports.index')
                            <li class="nav-item  @if( Str::contains(Route::currentRouteName(), 'reports') ) active @endif">
                                <a class="nav-link" href="{{ route('admin.reports.index') }}">
                                    <span class="shape1"></span>
                                    <span class="shape2"></span>
                                    <i class="fa fa-chart-line sidemenu-icon menu-icon "></i>
                                    <span class="sidemenu-label">@lang('Reports')</span>
                                </a>
                            </li>
                        @endcanAny

                    @endif


                    @canAny(['settings.index','pages.index','faqs.index',"roles.index","counts.index","features.index","feedbacks.index","screens.index"])
                        @php
                            $open_settings = false;
                            if( Request::is([
                                '*settings*',
                                '*roles*',
                                '*pages*',
                                '*faqs*',
                                '*counts*',
                                '*feedbacks*',
                                '*features*',
                                '*screens*',
                                ]) ) {
                                $open_settings = true;
                            }
                        @endphp
                        <li class="nav-item @if($open_settings) show @endif">
                            <a class="nav-link with-sub" href="javascript:void(0)">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="ti-settings sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">@lang('Settings')</span>
                                <i class="angle fe fe-chevron-right"></i>
                            </a>
                            <ul class="nav-sub @if($open_settings) open @endif" @if($open_settings) style="display: block;" @else style="display: none;" @endif>
                                @canAny('settings.index')
                                    <li class="nav-item @if( Request::is('*dashboard/settings*') ) active @endif">
                                        <a class="nav-link" href="{{ route('admin.settings.index') }}">
                                            <span class="shape1"></span>
                                            <span class="shape2"></span>
                                            <i class="ti-settings sidemenu-icon menu-icon "></i>
                                            <span class="sidemenu-label">@lang('Global settings')</span>
                                        </a>
                                    </li>
                                @endcanAny
                                @canAny('roles.index')
                                    <li class="nav-item @if( Request::is('*dashboard/roles*') ) active @endif">
                                        <a class="nav-link" href="{{ route('admin.roles.index') }}">
                                            <span class="shape1"></span>
                                            <span class="shape2"></span>
                                            <i class="fa fa-users-cog sidemenu-icon menu-icon "></i>
                                            <span class="sidemenu-label">@lang('User Roles')</span>
                                        </a>
                                    </li>
                                @endcanAny
                                @canAny('pages.index')
                                    <li class="nav-item @if( Request::is('*dashboard/pages*') ) active @endif">
                                        <a class="nav-link" href="{{ route('admin.pages.index') }}">
                                            <span class="shape1"></span>
                                            <span class="shape2"></span>
                                            <i class="fa fa-file-alt sidemenu-icon menu-icon "></i>
                                            <span class="sidemenu-label">@lang('Pages')</span>
                                        </a>
                                    </li>
                                @endcanAny
                                @canAny('faqs.index')
                                    <li class="nav-item @if( Request::is('*dashboard/faqs*') ) active @endif">
                                        <a class="nav-link" href="{{ route('admin.faqs.index') }}">
                                            <span class="shape1"></span>
                                            <span class="shape2"></span>
                                            <i class="fa fa-question sidemenu-icon menu-icon "></i>
                                            <span class="sidemenu-label">@lang('FAQ')</span>
                                        </a>
                                    </li>
                                @endcanAny
                                @canAny('counts.index')
                                    <li class="nav-item @if( Request::is('*dashboard/counts*') ) active @endif">
                                        <a class="nav-link" href="{{ route('admin.counts.index') }}">
                                            <span class="shape1"></span>
                                            <span class="shape2"></span>
                                            <i class="fa fa-star-of-life sidemenu-icon menu-icon "></i>
                                            <span class="sidemenu-label">@lang('Counters')</span>
                                        </a>
                                    </li>
                                @endcanAny
                                @canAny('features.index')
                                    <li class="nav-item @if( Request::is('*dashboard/features*') ) active @endif">
                                        <a class="nav-link" href="{{ route('admin.features.index') }}">
                                            <span class="shape1"></span>
                                            <span class="shape2"></span>
                                            <i class="fa fa-star-half-alt sidemenu-icon menu-icon "></i>
                                            <span class="sidemenu-label">@lang('Features')</span>
                                        </a>
                                    </li>
                                @endcanAny
                                @canAny('feedbacks.index')
                                    <li class="nav-item @if( Request::is('*dashboard/feedbacks*') ) active @endif">
                                        <a class="nav-link" href="{{ route('admin.feedbacks.index') }}">
                                            <span class="shape1"></span>
                                            <span class="shape2"></span>
                                            <i class="fa fa-comment-alt sidemenu-icon menu-icon "></i>
                                            <span class="sidemenu-label">@lang('Feedbacks')</span>
                                        </a>
                                    </li>
                                @endcanAny
                                {{--@canAny('screens.index')
                                    <li class="nav-item @if( Request::is('*dashboard/screens*') ) active @endif">
                                        <a class="nav-link" href="{{ route('admin.screens.index') }}">
                                            <span class="shape1"></span>
                                            <span class="shape2"></span>
                                            <i class="fa fa-comment-alt sidemenu-icon menu-icon "></i>
                                            <span class="sidemenu-label">@lang('Application Screens')</span>
                                        </a>
                                    </li>
                                @endcanAny--}}
                            </ul>
                        </li>
                    @endcanAny

                </ul>
                <div class="slide-right" id="slide-right"><i class="fe fe-chevron-right"></i></div>
            </div>
        </div>
    </div>
</div>
