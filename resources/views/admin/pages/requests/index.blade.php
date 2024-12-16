@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

<div class="row row-sm">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
        <div class="card custom-card">
            @if ($lists->count() > 0)
            <div class="card-body">
                <div class="table-responsive border newlist-table">
                    <table class="table card-table table-striped table-vcenter text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th class="wd-lg-20p"><span>@lang('Club Image')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Club Name')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Email')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Phone')</span></th>
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
                                        @canAny('requests.edit')
                                        <a href="{{ route('admin.requests.edit', [$list->id, 'page' => request()->query('page')]) }}">
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
                                        <div class="hstack gap-2 fs-15">
                                            @canAny('requests.show')
                                                @include('admin.component.buttons.show_actions', [
                                                    'url' => route('admin.requests.show',$list->id),
                                                ])
                                            @endcanAny
                                            @canAny('requests.destroy')
                                                @include('admin.component.buttons.delete_actions', [
                                                    'url' => route('admin.requests.destroy',$list->id),
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
                'name' => __('requests')
            ])
            @endif
        </div>
    </div>
</div>

@include('admin.component.modals.delete')

@endsection
