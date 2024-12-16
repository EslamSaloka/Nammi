@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')

@section('buttons')
    @canAny('coupons.create')
        @include('admin.component.buttons.btn_href', [
            'title' => __('Create new Coupon'),
            'color_class' => 'primary',
            'url' => route('admin.coupons.create'),
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
                <div class="table-responsive border userlist-table">
                    <table class="table card-table table-striped table-vcenter text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th class="wd-lg-8p"><span>@lang('Coupon Code')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Value')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Created At')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Status')</span></th>
                                <th class="wd-lg-20p">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                <tr>
                                    <td>
                                        {{ $list->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $list->value ?? '' }} @if($list->type != "fixed") % @else @lang("EGP") @endif
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($list->created_at)->diffForHumans() ?? '' }}
                                    </td>
                                    <td>
                                        @if(\Carbon\Carbon::now() < \Carbon\Carbon::parse($list->expire))
                                            @lang("Active")
                                        @else
                                            @lang("Expired")
                                        @endif
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @canAny('coupons.edit')
                                                @include('admin.component.buttons.edit_actions', [
                                                    'url' => route('admin.coupons.edit',$list->id),
                                                ])
                                            @endcanAny
                                            @canAny('coupons.destroy')
                                                @include('admin.component.buttons.delete_actions', [
                                                    'url' => route('admin.coupons.destroy',$list->id),
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
                    'name' => __('coupons')
                ])
            @endif
        </div>
    </div>
</div>

@include('admin.component.modals.filter', [
    'fields' => [
        [
            'name' => 'name',
            'label' => 'البحث بكود الكوبون',
            'type' => 'text'
        ],
    ],
    'url' => route('admin.coupons.index')
])

@include('admin.component.modals.delete')
@endsection
