@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')

@section('buttons')
    @if (isAdmin())
        @include('admin.component.buttons.filter')
    @endif
@endsection


<div class="row">
    <div class="col-lg-4 col-md-4">
        <div class="card custom-card">
            <div class="card-body">
                <div class="card-item">
                    <div class="card-item-icon card-icon">
                        <i class="fa fa-money-check-alt sidemenu-icon menu-icon "></i>
                    </div>
                    <div class="card-item-title mb-2">
                        <label class="main-content-label tx-13 font-weight-bold mb-1">@lang('Revenue Price All')</label>
                    </div>
                    <div class="card-item-body">
                        <div class="card-item-stat">
                            <h4 class="font-weight-bold">{{$statistic['total']}} @lang('EGP')</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4">
        <div class="card custom-card">
            <div class="card-body">
                <div class="card-item">
                    <div class="card-item-icon card-icon">
                        <i class="fa fa-money-check-alt sidemenu-icon menu-icon "></i>
                    </div>
                    <div class="card-item-title mb-2">
                        <label class="main-content-label tx-13 font-weight-bold mb-1">@lang('Revenue Price Confirmed')</label>
                    </div>
                    <div class="card-item-body">
                        <div class="card-item-stat">
                            <h4 class="font-weight-bold">{{$statistic['confirmed']}} @lang('EGP')</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="card custom-card">
            <div class="card-body">
                <div class="card-item">
                    <div class="card-item-icon card-icon">
                        <i class="fa fa-money-check-alt sidemenu-icon menu-icon "></i>
                    </div>
                    <div class="card-item-title mb-2">
                        <label class="main-content-label tx-13 font-weight-bold mb-1">@lang('Revenue Price Un confirmed')</label>
                    </div>
                    <div class="card-item-body">
                        <div class="card-item-stat">
                            <h4 class="font-weight-bold">{{$statistic['un_confirmed']}} @lang('EGP')</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="row row-sm">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
        <div class="card custom-card">
            @if ($lists->count() > 0)
            <div class="card-body">

                @canAny('dues.export')
                    <div class="d-flex mg-b-20">
                        <div class="pd-10 order-3">
                            <a class="btn btn-success" href="{{route('admin.revenue.export', [request()->getQueryString()])}}">
                                @lang('Export as Excel')
                            </a>
                        </div>
                    </div>
                @endcanAny


                <div class="table-responsive border userlist-table">
                    <table class="table card-table table-striped table-vcenter text-nowrap mb-0">
                        <thead>
                            <tr>
                                @if (isAdmin())
                                    <th class="wd-lg-20p"><span>@lang('Club Image')</span></th>
                                    <th class="wd-lg-8p"><span>@lang('Club Name')</span></th>
                                @endif
                                <th class="wd-lg-8p"><span>@lang('Commission Percentage')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Club Revenue')</span></th>
                                    @if (isAdmin())
                                <th class="wd-lg-20p">@lang('Actions')</th>
                                    @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                <tr>
                                    @if (isAdmin())
                                        <td>
                                            <img alt="avatar" class="rounded-circle avatar-md me-2" src="{{ $list->display_image }}">
                                        </td>
                                        <td>
                                            @canAny('clubs.edit')
                                            <a href="{{ route('admin.clubs.edit', [$list->id]) }}">
                                                {{ (App::getLocale() == "ar") ? $list->name ?? '' : $list->name_en ?? '' }}
                                            </a>
                                            @else
                                                {{ (App::getLocale() == "ar") ? $list->name ?? '' : $list->name_en ?? '' }}
                                            @endcanAny
                                        </td>
                                    @endif
                                    <td>
                                        {{  $list->vat }} %
                                     </td>
                                    <td>
                                        {{ getClubRevenuePrice($list) }} @lang('EGP')
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @canAny('dues.confirmed')
                                                @include('admin.component.buttons.show_actions', [
                                                    'url' => route('admin.revenue.show',$list->id),
                                                ])
                                            @endcanAny
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $lists->withQueryString()->withQueryString()->links('admin.layouts.inc.paginator') }}
            </div>
            @else
                @include('admin.component.inc.nodata', [
                    'name' => __('Clubs')
                ])
            @endif
        </div>
    </div>
</div>

@if (isAdmin())
    @include('admin.component.modals.filter', [
        'fields' => [
                [
                    'name' => 'club_id',
                    'label' => 'Club',
                    'type' => 'select',
                    'data' => $clubs,
                    'keyV'      => (App::getLocale() == "ar") ? "name" : "name_en",
                    'translate' => true
                ],
                /*[
                    'name' => 'payment_type',
                    'label' => 'Payment Type',
                    'type' => 'select',
                    'data' => [
                        [
                            'id' => 'visa',
                            'name' => __('Visa')
                        ],
                        [
                            'id' => 'cod',
                            'name' => __('COD')
                        ]
                    ],
                    'translate' => true
                ],*/
            ],
        'url' => route('admin.revenue.index')
    ])
@endif

@endsection
