@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')

@section('buttons')
    @canAny('sliders.create')
        @include('admin.component.buttons.btn_href', [
            'title' => __('create new slider'),
            'color_class' => 'primary',
            'url' => route('admin.sliders.create'),
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
                                <th class="wd-lg-20p"><span>@lang('Slider Image')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Club Name')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Activity Name')</span></th>
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
                                        @canAny('sliders.edit')
                                        <a href="{{ route('admin.clubs.edit', [$list->activity->club->id]) }}">
                                            {{ (App::getLocale() == "ar") ? $list->activity->club->name ?? '' : $list->activity->club->name_en ?? '' }}
                                        </a>
                                        @else
                                            {{ (App::getLocale() == "ar") ? $list->activity->club->name ?? '' : $list->activity->club->name_en ?? '' }}
                                        @endcanAny
                                    </td>

                                    <td>
                                        {{ $list->activity->name }}
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @canAny('sliders.edit')
                                                @include('admin.component.buttons.edit_actions', [
                                                    'url' => route('admin.sliders.edit',$list->id),
                                                ])
                                            @endcanAny
                                            @canAny('sliders.destroy')
                                                @include('admin.component.buttons.delete_actions', [
                                                    'url' => route('admin.sliders.destroy',$list->id),
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
                    'name' => __('sliders')
                ])
            @endif
        </div>
    </div>
</div>
@include('admin.component.modals.delete')
@endsection
