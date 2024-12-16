@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')

@section('buttons')
    @canAny('countries.create')
        @include('admin.component.buttons.btn_href', [
            'title' => __('create new country'),
            'color_class' => 'primary',
            'url' => route('admin.countries.create'),
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
                                <th class="wd-lg-20p"><span>@lang('Cities')</span></th>
                                <th class="wd-lg-20p">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                <tr>
                                    <td>
                                        @canAny('countries.edit')
                                        <a href="{{ route('admin.countries.edit', [$list->id, 'page' => request()->query('page')]) }}">
                                            {{ $list->name ?? '' }}
                                        </a>
                                        @else
                                            {{ $list->name ?? '' }}
                                        @endcanAny
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.countries.cities.index', $list->id) }}">
                                            @lang('Show cities')
                                        </a>
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @canAny('countries.edit')
                                                @include('admin.component.buttons.edit_actions', [
                                                    'url' => route('admin.countries.edit',$list->id),
                                                ])
                                            @endcanAny
                                            @canAny('countries.destroy')
                                                @include('admin.component.buttons.delete_actions', [
                                                    'url' => route('admin.countries.destroy',$list->id),
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
                    'name' => __('countries')
                ])
            @endif
        </div>
    </div>
</div>

@include('admin.component.modals.delete')
@endsection
