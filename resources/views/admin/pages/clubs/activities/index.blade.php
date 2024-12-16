@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')

@section('buttons')
    @canAny('activities.create')
        @include('admin.component.buttons.btn_href', [
            'title' => __('create new activity'),
            'color_class' => 'primary',
            'url' => route('admin.clubs.activities.create'),
            'fe_icon' => 'plus'
        ])
    @endcanAny
    @include('admin.component.buttons.filter')
@endsection

<div class="row row-sm">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
        <div class="card custom-card">
            @if ($lists->count() > 0)
            <div class="card-body">


                @canAny('activities.export')
                    <div class="d-flex mg-b-20">
                        <div class="pd-10 order-3">
                            <form id="export_excel_form" action="{{route('admin.clubs.activities.export.excel', [request()->getQueryString()])}}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    @lang('Export as Excel')
                                </button>
                            </form>
                        </div>
                        <div class="pd-10 order-2">
                            <form action="{{route('admin.clubs.activities.export.pdf')}}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    @lang('Export as PDF')
                                </button>
                            </form>
                        </div>
                    </div>
                @endcanAny


                <div class="table-responsive border userlist-table">
                    <table class="table card-table table-striped table-vcenter text-nowrap mb-0">
                        <thead>
                            <tr>
                                @if (isAdmin())
                                    <th class="wd-lg-20p"><span>@lang('Image')</span></th>
                                    <th class="wd-lg-8p"><span>@lang('Club Name')</span></th>
                                @endif
                                <th class="wd-lg-20p"><span>@lang('Activity Name')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Branch')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Price')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Offer')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Disabilities')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Order One Time')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Rates')</span></th>
                                <th class="wd-lg-20p">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                <tr>
                                    @if (isAdmin())
                                        <td>
                                            <img alt="avatar" class="rounded-circle avatar-md me-2" src="{{ $list->display_image }} ">
                                        </td>
                                        <td>
                                            @canAny('clubs.edit')
                                            <a href="{{ route('admin.clubs.edit', [$list->club->id]) }}">
                                                {{ (App::getLocale() == "ar") ? $list->club->name ?? '' : $list->club->name_en ?? '' }}
                                            </a>
                                            @else
                                                {{ (App::getLocale() == "ar") ? $list->club->name ?? '' : $list->club->name_en ?? '' }}
                                            @endcanAny
                                        </td>
                                    @endif

                                    <td>
                                        @canAny('activities.edit')
                                        <a href="{{ route('admin.clubs.activities.edit', $list->id) }}">
                                            {{ $list->name }}
                                        </a>
                                        @else
                                            {{ $list->name }}
                                        @endcanAny
                                    </td>
                                    <td>
                                        @canAny('branches.edit')
                                        <a href="{{ route('admin.clubs.branches.edit', [$list->branch_id]) }}">
                                            {{ $list->branch->country->name }} - {{ $list->branch->city->name }}
                                        </a>
                                        @else
                                            {{ $list->branch->country->name }} - {{ $list->branch->city->name }}
                                        @endcanAny
                                    </td>
                                    <td>
                                        {{ $list->price }} @lang('EGP')
                                    </td>
                                    <td>
                                        @if ($list->offer > 0)
                                            {{ $list->offer }} @lang('EGP')
                                        @else
                                            -----
                                        @endif
                                    </td>

                                    <td>
                                        {!! $list->showDisabilities() !!}
                                    </td>
                                    <td>
                                        {!! $list->showOrderOneTime() !!}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.clubs.activities.rates.index',$list->id) }}">
                                            ( {{ $list->rates }} )
                                        </a>
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @canAny('activities.edit')
                                                @include('admin.component.buttons.edit_actions', [
                                                    'url' => route('admin.clubs.activities.edit',$list->id),
                                                ])
                                            @endcanAny
                                            @canAny('activities.destroy')
                                                @include('admin.component.buttons.delete_actions', [
                                                    'url' => route('admin.clubs.activities.destroy',$list->id),
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
                    'name' => __('activities')
                ])
            @endif
        </div>
    </div>
</div>


@if (isAdmin())
    @php
        $fields = [
            [
                'name' => 'name',
                'label' => 'Search by activity name',
                'type' => 'text'
            ],
            [
                'name' => 'club_id',
                'label' => 'Club',
                'type' => 'select',
                'data' => $clubs,
                'keyV'      => (App::getLocale() == "ar") ? "name" : "name_en",
                'translate' => true
            ],
            [
                'name' => 'country_id',
                'label' => 'Country',
                'type' => 'select',
                'data' => \App\Models\Country::all(),
                'translate' => true
            ],
            [
                'name' => 'disabilities',
                'label' => 'Disabilities',
                'type' => 'select',
                'data' => [
                    [
                        'id' => 1,
                        'name' => __('Yes')
                    ],
                    [
                        'id' => 0,
                        'name' => __('No')
                    ]
                ],
            ],
            [
                'name' => 'order_one_time',
                'label' => 'Order One Time',
                'type' => 'select',
                'data' => [
                    [
                        'id' => 1,
                        'name' => __('Yes')
                    ],
                    [
                        'id' => 0,
                        'name' => __('No')
                    ]
                ],
            ]
        ];
    @endphp
@else
    @php
        $fields = [
            [
                'name' => 'name',
                'label' => 'Search by activity name',
                'type' => 'text'
            ],
            [
                'name' => 'country_id',
                'label' => 'Country',
                'type' => 'select',
                'data' => \App\Models\Country::all(),
                'translate' => true
            ],
            [
                'name' => 'disabilities',
                'label' => 'Disabilities',
                'type' => 'select',
                'data' => [
                    [
                        'id' => 1,
                        'name' => __('Yes')
                    ],
                    [
                        'id' => 0,
                        'name' => __('No')
                    ]
                ],
                'translate' => true
            ],
            [
                'name' => 'order_one_time',
                'label' => 'Order One Time',
                'type' => 'select',
                'data' => [
                    [
                        'id' => 1,
                        'name' => __('Yes')
                    ],
                    [
                        'id' => 0,
                        'name' => __('No')
                    ]
                ],
                'translate' => true
            ]
        ];
    @endphp
@endif

@include('admin.component.modals.filter', [
    'fields' => $fields,
    'url' => route('admin.clubs.activities.index')
])
@include('admin.component.modals.delete')

@endsection
