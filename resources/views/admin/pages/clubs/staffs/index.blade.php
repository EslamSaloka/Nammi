@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')

@section('buttons')
    @canAny('staffs.create')
        @include('admin.component.buttons.btn_href', [
            'title' => __('create new staff'),
            'color_class' => 'primary',
            'url' => route('admin.clubs.staffs.create'),
            'fe_icon' => 'plus'
        ])
    @endcanAny
    @include('admin.component.buttons.filter')
@endsection

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
                                                            return $r->where("name",\App\Models\User::TYPE_BRANCH);
                                                        })->where("id","!=",1)->where('suspend', 0)->count();
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
                                                            return $r->where("name",\App\Models\User::TYPE_BRANCH);
                                                        })->where("id","!=",1)->where('suspend', 1)->count();
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
                <div class="table-responsive border userlist-table">
                    <table class="table card-table table-striped table-vcenter text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th class="wd-lg-20p"><span>@lang('Avatar')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Name')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Email')</span></th>
                                <th class="wd-lg-20p">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                <tr>
                                    <td>
                                        <img alt="avatar" class="rounded-circle avatar-md me-2" src="{{ (new \App\Support\Image)->displayImageByModel($list,'avatar') }}">
                                    </td>
                                    <td>
                                        @canAny('staffs.edit')
                                        <a href="{{ route('admin.clubs.staffs.edit', [$list->id, 'page' => request()->query('page')]) }}">
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
                                        <div class="hstack gap-2 fs-15">
                                            @canAny('staffs.edit')
                                                @include('admin.component.buttons.edit_actions', [
                                                    'url' => route('admin.clubs.staffs.edit',$list->id),
                                                ])
                                            @endcanAny
                                            @canAny('staffs.destroy')
                                                @include('admin.component.buttons.delete_actions', [
                                                    'url' => route('admin.clubs.staffs.destroy',$list->id),
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
                    'name' => __('Staffs')
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
                'label' => 'Search by name or email or phone number',
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
        ];
    @endphp
@else
    @php
        $fields = [
            [
                'name' => 'name',
                'label' => 'Search by name or email or phone number',
                'type' => 'text'
            ],
        ];
    @endphp
@endif

@include('admin.component.modals.filter', [
    'fields' => $fields,
    'url' => route('admin.clubs.staffs.index')
])
@include('admin.component.modals.delete')
@endsection
