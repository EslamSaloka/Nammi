@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')

@section('buttons')
    @include('admin.component.buttons.filter')
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
                        <label class="main-content-label tx-13 font-weight-bold mb-1">@lang('Dues Price All')</label>
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
                        <label class="main-content-label tx-13 font-weight-bold mb-1">@lang('Dues Price Confirmed')</label>
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
                        <label class="main-content-label tx-13 font-weight-bold mb-1">@lang('Dues Price Un confirmed')</label>
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

    @foreach ($statistic['ordersStatistic'] as $k=>$v)
        <div class="col-lg-4 col-md-4">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="card-item">
                        <div class="card-item-icon card-icon">
                            <i class="fa fa-list sidemenu-icon menu-icon "></i>
                        </div>
                        <div class="card-item-title mb-2">
                            <label class="main-content-label tx-13 font-weight-bold mb-1">@lang($k) @lang('')</label>
                        </div>
                        <div class="card-item-body">
                            <div class="card-item-stat">
                                <h4 class="font-weight-bold">{{$v}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


</div>


<div class="row row-sm">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
        <div class="card custom-card">
            @canAny('dues.export')
                <div class="d-flex mg-b-20">
                    <div class="pd-10 order-3">
                        <a class="btn btn-success" href="{{route('admin.dues.club.export', [$club->id,request()->getQueryString()])}}">
                            @lang('Export as Excel')
                        </a>
                    </div>
                </div>
            @endcanAny
            @if ($lists->count() > 0)
            <div class="card-body">



                @canAny('dues.confirmed')
                    <a href="{{ route('admin.dues.confirmed',[$club->id,request()->getQueryString()]) }}"
                        class="btn btn-success"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        data-bs-original-title="{{ __('Accept All Dues From This Club') }}"
                       @if (\App::getLocale() == "en")
                       style="position:absolute;left:140px;top:10px;width:16%"
                       @else
                       style="position:absolute;right:125px;top:10px;width:19%"
                        @endif
                    >
                        <i class="fa fa-money-check-alt"></i>
                        @lang('Accept All Dues From This Club')
                    </a>
                @endcanAny


                <div class="table-responsive border userlist-table">
                    <table class="table card-table table-striped table-vcenter text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th class="wd-lg-8p"><span>@lang('Payment Type')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Order Number')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Order Price')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Dues')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Due Date')</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                <tr>
                                    <td>
                                        @if ($list->payment_type == "visa")
                                            <img src="{{ url('visa.jpg') }}" style=" width: 50px; ">
                                        @else
                                            <img src="{{ url('cod.png') }}" style=" width: 50px; ">
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show',$list->order_id) }}">
                                            # {{ $list->order_id }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $list->order->total ?? 0 }} @lang('EGP')
                                    </td>
                                    <td>
                                        {{ $list->price }} @lang('EGP')
                                    </td>
                                    <td>
                                        {{ $list->created_at->format("d-m-Y") }}
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
                    'name' => __('Dues')
                ])
            @endif
        </div>
    </div>
</div>

@include('admin.component.modals.filter', [
    'fields' => [
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
            [
                'name' => 'from_date',
                'label' => 'From Date',
                'type' => 'date'
            ],
            [
                'name' => 'to_date',
                'label' => 'To Date',
                'type' => 'date'
            ],
        ],
    'url' => route('admin.dues.show',$club->id)
])

@endsection
