<div class="col-sm-12 col-lg-12 col-xl-8">

    <div class="row row-sm banner-img">
        <div class="col-sm-12 col-lg-12 col-xl-12">
            <div class="card bg-primary custom-card card-box">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="offset-xl-4 offset-sm-6 col-xl-8 col-sm-6 col-12 img-bg ">
                            <h4 class="d-flex mb-3">
                                <span class="fw-bold text-fixed-white ">
                                    @if (isAdmin())
                                        {{ \Auth::user()->name }}
                                    @else
                                        {{ (App::getLocale() == "ar") ? \Auth::user()->name ?? '' : \Auth::user()->name_en ?? '' }}
                                    @endif
                                </span>
                            </h4>
                            <p class="tx-white-7 mb-1">
                                @lang('Have a nice day !')
                            </p>
                        </div>
                        <img src="{{ url('assets/img/29.png') }}" alt="user-img">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach ($statistic as $item)
            <div class="col-lg-4 col-md-4">
                <a href="{{ $item['route'] }}">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="card-item">
                                <div class="card-item-icon card-icon">
                                    <i class="{{$item['icon']}} sidemenu-icon menu-icon "></i>
                                </div>
                                <div class="card-item-title mb-2">
                                    <label class="main-content-label tx-13 font-weight-bold mb-1">{{$item['title']}}</label>
                                </div>
                                <div class="card-item-body">
                                    <div class="card-item-stat">
                                        <h4 class="font-weight-bold">{{$item['count']}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
<div class="col-sm-12 col-lg-12 col-xl-4">

    {{--<h4>
        @lang('Orders Statistic')
    </h4>--}}
    <div class="row">
        @foreach ($orders as $item)
            <div class="col-lg-12 col-md-12">
                <a href="{{ $item['route'] }}" disabled="">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="card-item">
                                <div class="card-item-icon card-icon">
                                    <i class="{{$item['icon']}} sidemenu-icon menu-icon "></i>
                                </div>
                                <div class="card-item-title mb-2">
                                    <label class="main-content-label tx-13 font-weight-bold mb-1">{{$item['title']}}</label>
                                </div>
                                <div class="card-item-body">
                                    <div class="card-item-stat">
                                        <h4 class="font-weight-bold">{{$item['count']}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
