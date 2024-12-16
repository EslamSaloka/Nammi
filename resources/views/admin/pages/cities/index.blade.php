@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')

@section('buttons')
    @canAny('cities.create')
        @include('admin.component.buttons.btn_href', [
            'title' => __('create new city'),
            'color_class' => 'primary',
            'url' => route('admin.countries.cities.create',$country->id),
            'fe_icon' => 'plus'
        ])
    @endcanAny
@endsection

<div class="row row-sm">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
        <div class="card custom-card">
            @if ($lists->count() > 0)
            <div class="card-body">
                <div class="table-responsive border userlist-table">
                    <table class="table card-table table-striped table-vcenter text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th class="wd-lg-8p"><span>@lang('Name')</span></th>
                                <th class="wd-lg-20p">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                <tr>
                                    <td>
                                        @canAny('cities.edit')
                                        <a href="{{ route('admin.countries.cities.edit', [$country->id,$list->id]) }}">
                                            {{ $list->name ?? '' }}
                                        </a>
                                        @else
                                            {{ $list->name ?? '' }}
                                        @endcanAny
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @canAny('cities.edit')
                                                @include('admin.component.buttons.edit_actions', [
                                                    'url' => route('admin.countries.cities.edit',[$country->id,$list->id]),
                                                ])
                                            @endcanAny
                                            @canAny('cities.destroy')
                                                @include('admin.component.buttons.delete_actions', [
                                                    'url' => route('admin.countries.cities.destroy',[$country->id,$list->id]),
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
                    'name' => __('cities')
                ])
            @endif
        </div>
    </div>
</div>

@include('admin.component.modals.delete')
@endsection
