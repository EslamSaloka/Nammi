@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')

@section('buttons')
    @canAny('categories.create')
        @include('admin.component.buttons.btn_href', [
            'title' => __('create new sub category'),
            'color_class' => 'primary',
            'url' => route('admin.categories.chiders.create',$category->id),
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

                @canAny('categories.export')
                    <div class="d-flex mg-b-20">
                        <div class="pd-10 order-3">
                            <form id="export_excel_form" action="{{route('admin.categories.chiders.export.excel', [$category->id,request()->getQueryString()])}}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    @lang('Export as Excel')
                                </button>
                            </form>
                        </div>
                        <div class="pd-10 order-2">
                            <form action="{{route('admin.categories.chiders.export.pdf',$category->id)}}" method="POST">
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
                                <th class="wd-lg-1p"><span>@lang('Image')</span></th>
                                <th class="wd-lg-1p"><span>@lang('Name')</span></th>
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
                                        @canAny('categories.edit')
                                        <a href="{{ route('admin.categories.chiders.edit', [$category->id,$list->id]) }}">
                                            {{ $list->name ?? '' }}
                                        </a>
                                        @else
                                            {{ $list->name ?? '' }}
                                        @endcanAny
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @canAny('categories.edit')
                                                @include('admin.component.buttons.edit_actions', [
                                                    'url' => route('admin.categories.chiders.edit',[$category->id,$list->id]),
                                                ])
                                            @endcanAny
                                            @canAny('categories.destroy')
                                                @include('admin.component.buttons.delete_actions', [
                                                    'url' => route('admin.categories.chiders.destroy',[$category->id,$list->id]),
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
                    'name' => __('categories')
                ])
            @endif
        </div>
    </div>
</div>


@include('admin.component.modals.filter', [
    'fields' => [
        [
            'name' => 'name',
            'label' => 'Search by name',
            'type' => 'text'
        ]
    ],
    'url' => route('admin.categories.chiders.index',$category->id)
])


@include('admin.component.modals.delete')
@endsection
