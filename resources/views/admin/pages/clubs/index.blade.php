@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')

@if (isAdmin())
    @section('buttons')
        @canAny('clubs.create')
            @include('admin.component.buttons.btn_href', [
                'title' => __('create new club'),
                'color_class' => 'primary',
                'url' => route('admin.clubs.create'),
                'fe_icon' => 'plus'
            ])
        @endcanAny
        @include('admin.component.buttons.filter')
    @endsection
@endif

<div class="row row-sm">
    @if (isAdmin())
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
                                                            return $r->where("name",\App\Models\User::TYPE_CLUB);
                                                        })->whereNotNull("accepted_at")->where('suspend', 0)->count();
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
                                                            return $r->where("name",\App\Models\User::TYPE_CLUB);
                                                        })->whereNotNull("accepted_at")->where('suspend', 1)->count();
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
    @endif
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
        <div class="card custom-card">
            @if ($lists->count() > 0)
            <div class="card-body">


                @canAny('clubs.export')
                    <div class="d-flex mg-b-20">
                        <div class="pd-10 order-3">
                            <form id="export_excel_form" action="{{route('admin.clubs.export.excel', [request()->getQueryString()])}}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    @lang('Export as Excel')
                                </button>
                            </form>
                        </div>
                        <div class="pd-10 order-2">
                            <form action="{{route('admin.clubs.export.pdf')}}" method="POST">
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
                                <th class="wd-lg-20p"><span>@lang('Club Image')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Club Name')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Email')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Phone')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Rates')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Commission')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Status')</span></th>
                                <th class="wd-lg-20p">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                <tr>
                                    <td>
                                        <img alt="avatar" class="rounded-circle avatar-md me-2" src="{{ $list->display_image ?? '' }}">
                                    </td>
                                    <td>
                                        @canAny('clubs.edit')
                                        <a href="{{ route('admin.clubs.edit', [$list->id, 'page' => request()->query('page')]) }}">
                                            {{ (App::getLocale() == "ar") ? $list->name ?? '' : $list->name_en ?? '' }}
                                        </a>
                                        @else
                                            {{ (App::getLocale() == "ar") ? $list->name ?? '' : $list->name_en ?? '' }}
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
                                        <a href="{{ route('admin.clubs.rates.index',$list->id) }}">
                                            ( {{ $list->rates }} )
                                        </a>
                                    </td>
                                    <td>
                                        {{ $list->vat ?? '' }} %
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
                                            @canAny('clubs.edit')
                                                @include('admin.component.buttons.edit_actions', [
                                                    'url' => route('admin.clubs.edit',$list->id),
                                                ])
                                            @endcanAny
                                            @canAny('clubs.destroy')
                                                @include('admin.component.buttons.delete_actions', [
                                                    'url' => route('admin.clubs.destroy',$list->id),
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
                    'name' => __('clubs')
                ])
            @endif
        </div>
    </div>
</div>

@if (isAdmin())
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
                        'name' => __('Suspend')
                    ],
                    [
                        'id' => 0,
                        'name' => __('UnSuspend')
                    ]
                ],
                'translate' => true
            ]
        ],
        'url' => route('admin.clubs.index')
    ])
    @include('admin.component.modals.delete')
@endif

@endsection
