@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

@section('buttons')
    @include('admin.component.buttons.filter')
@endsection

<div class="row row-sm">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
        <div class="card custom-card">
            @if ($lists->count() > 0)
            <div class="card-body">

                @canAny('orders.export')
                    <div class="d-flex mg-b-20">
                        <div class="pd-10 order-3">
                            <a href="{{route('admin.orders.export.excel', [request()->getQueryString()])}}" class="btn btn-success">
                                @lang('Export as Excel')
                            </a>
                        </div>
                        <div class="pd-10 order-2">
                            <a href="{{route('admin.orders.export.pdf',[request()->getQueryString()])}}" class="btn btn-danger">
                                @lang('Export as PDF')
                            </a>
                        </div>
                    </div>
                @endcanAny

                <div class="table-responsive border newlist-table">
                    <table class="table card-table table-striped table-vcenter text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th class="wd-lg-8p"><span></span></th>
                                <th class="wd-lg-8p"><span>@lang('#')</span></th>
                                @if (isAdmin())
                                    <th class="wd-lg-8p"><span>@lang('Club')</span></th>
                                @endif
                                <th class="wd-lg-8p"><span>@lang('Customer')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Activity')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Branch')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Status')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Created At')</span></th>
                                <th class="wd-lg-20p">@lang('Actions')</th>
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
                                        {{ $list->id }}
                                    </td>
                                    @if (isAdmin())
                                        <td>
                                            {{ (App::getLocale() == "ar") ? $list->club->name ?? '' : $list->club->name_en ?? '' }}
                                        </td>
                                    @endif
                                    <td>
                                        {{ $list->customer->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $list->activity->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $list->branch->name ?? '' }}
                                    </td>
                                    <td>
                                        {!! $list->showOrderStatus() !!}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($list->created_at)->format("d-m-Y") ?? '' }}
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @canAny('orders.show')
                                                @include('admin.component.buttons.show_actions', [
                                                    'url' => route('admin.orders.show',$list->id),
                                                ])
                                            @endcanAny
                                            @canAny('orders.destroy')
                                                @include('admin.component.buttons.delete_actions', [
                                                    'url' => route('admin.orders.destroy',$list->id),
                                                ])
                                            @endcanAny
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $lists->withQueryString()->links('admin.layouts.inc.paginator') }}
            </div>
            @else
            @include('admin.component.inc.nodata', [
                'name' => __('Orders')
            ])
            @endif
        </div>
    </div>
</div>


@php
    if(isAdmin()) {
        $fields = [
            [
                'name' => 'id',
                'label' => 'Search by order number',
                'type' => 'text'
            ],
            [
                'name' => 'club_id',
                'label' => 'Club',
                'type' => 'select',
                'data' => \App\Models\User::select("id","name")->whereNotNull("accepted_at")->whereHas("roles",function($q){
                    return $q->where("name",\App\Models\User::TYPE_CLUB);
                })->get(),
                'translate' => true
            ],
            [
                'name' => 'customer_id',
                'label' => 'Customer',
                'type' => 'select',
                'data' => \App\Models\User::select("id","name")->whereHas("roles",function($q){
                    return $q->where("name",\App\Models\User::TYPE_CUSTOMER);
                })->get(),
                'translate' => true
            ],
            [
                'name' => 'order_status',
                'label' => 'Status',
                'type' => 'select',
                'data' => [
                    [
                        "id"       => \App\Models\Order::STATUS_PENDING,
                        "name"     => ucwords(str_replace("_"," ",\App\Models\Order::STATUS_PENDING)),
                    ],
                    [
                        "id"       => \App\Models\Order::STATUS_ACCEPTED,
                        "name"     => ucwords(str_replace("_"," ",\App\Models\Order::STATUS_ACCEPTED)),
                    ],
                    [
                        "id"       => \App\Models\Order::STATUS_TIME_CHANGE,
                        "name"     => ucwords(str_replace("_"," ",\App\Models\Order::STATUS_TIME_CHANGE)),
                    ],
                    [
                        "id"       => \App\Models\Order::STATUS_CONFIRMED,
                        "name"     => ucwords(str_replace("_"," ",\App\Models\Order::STATUS_CONFIRMED)),
                    ],
                    [
                        "id"       => \App\Models\Order::STATUS_REJECTED,
                        "name"     => ucwords(str_replace("_"," ",\App\Models\Order::STATUS_REJECTED)),
                    ],
                    [
                        "id"       => \App\Models\Order::STATUS_WAITING_CUSTOMER_COMPLETED,
                        "name"     => ucwords(str_replace("_"," ",\App\Models\Order::STATUS_WAITING_CUSTOMER_COMPLETED)),
                    ],
                    [
                        "id"       => \App\Models\Order::STATUS_COMPLETED,
                        "name"     => ucwords(str_replace("_"," ",\App\Models\Order::STATUS_COMPLETED)),
                    ],
                ],
                'translate' => true
            ],
            [
                'name' => 'from_date',
                'label' => 'from date',
                'type' => 'date'
            ],
            [
                'name' => 'to_date',
                'label' => 'to date',
                'type' => 'date'
            ],
        ];
    } else {
        $fields = [
            [
                'name' => 'id',
                'label' => 'Search by order number',
                'type' => 'text'
            ],
            [
                'name' => 'order_status',
                'label' => 'Status',
                'type' => 'select',
                'data' => [
                    [
                        "id"       => \App\Models\Order::STATUS_PENDING,
                        "name"     => ucwords(str_replace("_"," ",\App\Models\Order::STATUS_PENDING)),
                    ],
                    [
                        "id"       => \App\Models\Order::STATUS_ACCEPTED,
                        "name"     => ucwords(str_replace("_"," ",\App\Models\Order::STATUS_ACCEPTED)),
                    ],
                    [
                        "id"       => \App\Models\Order::STATUS_TIME_CHANGE,
                        "name"     => ucwords(str_replace("_"," ",\App\Models\Order::STATUS_TIME_CHANGE)),
                    ],
                    [
                        "id"       => \App\Models\Order::STATUS_CONFIRMED,
                        "name"     => ucwords(str_replace("_"," ",\App\Models\Order::STATUS_CONFIRMED)),
                    ],
                    [
                        "id"       => \App\Models\Order::STATUS_REJECTED,
                        "name"     => ucwords(str_replace("_"," ",\App\Models\Order::STATUS_REJECTED)),
                    ],
                    [
                        "id"       => \App\Models\Order::STATUS_COMPLETED,
                        "name"     => ucwords(str_replace("_"," ",\App\Models\Order::STATUS_COMPLETED)),
                    ],
                ],
                'translate' => true
            ],
            [
                'name' => 'from_date',
                'label' => 'from date',
                'type' => 'date'
            ],
            [
                'name' => 'to_date',
                'label' => 'to date',
                'type' => 'date'
            ],
        ];
    }
@endphp



@include('admin.component.modals.filter', [
    'fields' => $fields,
    'url' => route('admin.orders.index')
])
@include('admin.component.modals.delete')

@endsection
