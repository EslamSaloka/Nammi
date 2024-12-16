@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')

@section('buttons')
    @canAny('branches.create')
        @include('admin.component.buttons.btn_href', [
            'title' => __('create new branch'),
            'color_class' => 'primary',
            'url' => route('admin.clubs.branches.create'),
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

                @canAny('branches.export')
                    <div class="d-flex mg-b-20">
                        <div class="pd-10 order-3">
                            <form id="export_excel_form" action="{{route('admin.clubs.branches.export.excel', [request()->getQueryString()])}}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    @lang('Export as Excel')
                                </button>
                            </form>
                        </div>
                        <div class="pd-10 order-2">
                            <form action="{{route('admin.clubs.branches.export.pdf')}}" method="POST">
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
                                    <th class="wd-lg-20p"><span>@lang('Club Image')</span></th>
                                    <th class="wd-lg-8p"><span>@lang('Club Name')</span></th>
                                @endif
                                <th class="wd-lg-8p"><span>@lang('Branch Master')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Branch Name')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Country')</span></th>
                                <th class="wd-lg-20p"><span>@lang('City')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Email')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Phone')</span></th>
                                <th class="wd-lg-20p">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                <tr>
                                    @if (isAdmin())
                                        <td>
                                            <img alt="avatar" class="rounded-circle avatar-md me-2" src="{{ $list->club->display_image }}">
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
                                        {{ $list->user->name ?? '' }}
                                    </td>
                                    <td>
                                        @canAny('branches.edit')
                                            <a href="{{ route('admin.clubs.branches.edit',$list->id) }}">
                                                {{ $list->name }}
                                            </a>
                                        @else
                                            {{ $list->name }}
                                        @endcanAny
                                    </td>
                                    <td>
                                        @canAny('countries.edit')
                                        <a href="{{ route('admin.countries.edit', [$list->country->id]) }}">
                                            {{ $list->country->name }}
                                        </a>
                                        @else
                                            {{ $list->country->name }}
                                        @endcanAny
                                    </td>
                                    <td>
                                        @canAny('cities.edit')
                                        <a href="{{ route('admin.countries.cities.edit', [$list->country->id,$list->city->id]) }}">
                                            {{ $list->city->name }}
                                        </a>
                                        @else
                                            {{ $list->city->name }}
                                        @endcanAny
                                    </td>

                                    <td>
                                        <a href="mailto:{{ $list->email ?? '' }}">
                                            {{ $list->email ?? '' }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="tel:{{ $list->phone ?? '' }}">
                                            {{ $list->phone ?? '' }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @canAny('branches.edit')
                                                @include('admin.component.buttons.edit_actions', [
                                                    'url' => route('admin.clubs.branches.edit',$list->id),
                                                ])
                                            @endcanAny
                                            @canAny('branches.destroy')
                                                @include('admin.component.buttons.delete_actions', [
                                                    'url' => route('admin.clubs.branches.destroy',$list->id),
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
                    'name' => __('Branchs')
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
                'label' => 'Search by branch name',
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
            ]
        ];
    @endphp
@else
    @php
        $fields = [
            [
                'name' => 'name',
                'label' => 'Search by branch name',
                'type' => 'text'
            ],
            [
                'name' => 'country_id',
                'label' => 'Country',
                'type' => 'select',
                'data' => \App\Models\Country::all(),
                'translate' => true
            ]
        ];
    @endphp
@endif


@include('admin.component.modals.filter', [
    'fields' => $fields,
    'url' => route('admin.clubs.branches.index')
])
@include('admin.component.modals.delete')

@endsection
