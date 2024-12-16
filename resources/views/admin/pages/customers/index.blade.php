@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')

@section('buttons')
    @canAny('customers.create')
        @include('admin.component.buttons.btn_href', [
            'title' => __('create new customer'),
            'color_class' => 'primary',
            'url' => route('admin.customers.create'),
            'fe_icon' => 'plus'
        ])
    @endcanAny
    @include('admin.component.buttons.filter')
@endsection

<div class="row row-sm">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">


        <div class="row">
            <div class="col-lg-12">
                <div class="row">

                    <div class="col-lg-4 col-md-4">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="card-item">
                                    <div class="card-item-icon card-icon">
                                        <i class="ti-user sidemenu-icon menu-icon "></i>
                                    </div>
                                    <div class="card-item-title mb-2">
                                        <label class="main-content-label tx-13 font-weight-bold mb-1">@lang('Active')</label>
                                    </div>
                                    <div class="card-item-body">
                                        <div class="card-item-stat">
                                            <h4 class="font-weight-bold">
                                                @php
                                                    $c = \App\Models\User::whereHas('roles',function($r){
                                                        return $r->where("name",\App\Models\User::TYPE_CUSTOMER);
                                                    })->where('suspend', 0)->count();
                                                @endphp
                                                {{ $c }}
                                            </h4>
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
                                        <i class="ti-user sidemenu-icon menu-icon "></i>
                                    </div>
                                    <div class="card-item-title mb-2">
                                        <label class="main-content-label tx-13 font-weight-bold mb-1">@lang('UnActive')</label>
                                    </div>
                                    <div class="card-item-body">
                                        <div class="card-item-stat">
                                            <h4 class="font-weight-bold">
                                                @php
                                                    $c = \App\Models\User::whereHas('roles',function($r){
                                                        return $r->where("name",\App\Models\User::TYPE_CUSTOMER);
                                                    })->where('suspend', 1)->count();
                                                @endphp
                                                {{ $c }}
                                            </h4>
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
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
        <div class="card custom-card">
            @if ($lists->count() > 0)
            <div class="card-body">

                @canAny('customers.export')
                    <div class="d-flex mg-b-20">
                        <div class="pd-10 order-3">
                            <form id="export_excel_form" action="{{route('admin.customers.export.excel', [request()->getQueryString()])}}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    @lang('Export as Excel')
                                </button>
                            </form>
                        </div>
                        <div class="pd-10 order-2">
                            <form action="{{route('admin.customers.export.pdf')}}" method="POST">
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
                                <th class="wd-lg-20p"><span>@lang('avatar')</span></th>
                                <th class="wd-lg-8p"><span>@lang('name')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Email')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Phone')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Status')</span></th>
                                <th class="wd-lg-20p">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                <tr>
                                    <td>
                                        <img alt="avatar" class="rounded-circle avatar-md me-2" src="{{ $list->display_image }}">
                                    </td>
                                    <td>
                                        @canAny('customers.edit')
                                        <a href="{{ route('admin.customers.edit', [$list->id, 'page' => request()->query('page')]) }}">
                                            {{ $list->name ?? '' }}
                                        </a>
                                        @else
                                            {{ $list->name ?? '' }}
                                        @endcanAny
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $list->email ?? '' }}">
                                            {{ $list->email ?? '' }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $list->phone ?? '' }}
                                    </td>
                                    <td>
                                        @if ($list->suspend == 1)
                                            @lang('Suspend')
                                        @else
                                            @lang('UnSuspend')
                                        @endif
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @canAny('customers.edit')
                                                @include('admin.component.buttons.edit_actions', [
                                                    'url' => route('admin.customers.edit',$list->id),
                                                ])
                                            @endcanAny
                                            @canAny('customers.destroy')
                                                @include('admin.component.buttons.delete_actions', [
                                                    'url' => route('admin.customers.destroy',$list->id),
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
                    'name' => __('Customers')
                ])
            @endif
        </div>
    </div>
</div>

@include('admin.component.modals.filter', [
    'fields' => [
        [
            'name' => 'name',
            'label' => 'Search by name or email or phone number',
            'type' => 'text'
        ],
        [
            'name' => 'suspend',
            'label' => 'Status',
            'type' => 'select',
            'data' => [
                [
                    'id' => 1,
                    'name' => __("UnSuspend")
                ],
                [
                    'id' => 0,
                    'name' => __("Suspend")
                ]
            ],
            'translate' => true
        ]
    ],
    'url' => route('admin.customers.index')
])
@include('admin.component.modals.delete')
@endsection
