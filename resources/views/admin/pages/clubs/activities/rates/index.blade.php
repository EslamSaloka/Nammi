@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')

<div class="row row-sm">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
        <div class="card custom-card">
            @if ($lists->count() > 0)
            <div class="card-body">
                <div class="table-responsive border userlist-table">
                    <table class="table card-table table-striped table-vcenter text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th class="wd-lg-8p"><span>@lang('Customer')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Rate')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Notes')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Confirmed')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Created At')</span></th>
                                @if(isAdmin())
                                    <th class="wd-lg-20p">@lang('Actions')</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                <tr>
                                    <td>
                                        {{ $list->customer->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $list->rate ?? 0 }}
                                    </td>
                                    <td>
                                        {{ $list->notes ?? '' }}
                                    </td>
                                    <td>
                                        @if (isAdmin())
                                        @if ($list->confirmed == 1)
                                            {!! $list->showConfirmed() !!}
                                        @else
                                            <a href="{{ route('admin.clubs.activities.rates.confirmed',[$list->activity->id,$list->id]) }}">
                                                {!! $list->showConfirmed() !!}
                                            </a>
                                        @endif
                                        @else
                                            {!! $list->showConfirmed() !!}
                                        @endif
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($list->created_at)->diffForHumans() ?? '' }}
                                    </td>
                                    <td>
                                        @if(isAdmin())
                                            @include('admin.component.buttons.delete_actions', [
                                                'url' => route('admin.clubs.activities.rates.destroy',[$list->activity->id,$list->id]),
                                            ])
                                        @endif
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
                    'name' => __('Activity Rates')
                ])
            @endif
        </div>
    </div>
</div>

@include('admin.component.modals.delete')
@endsection
