@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

@section('buttons')
    @include('admin.component.buttons.filter')
@endsection

<div class="row row-sm">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
        <div class="card custom-card">
            @if ($lists->count() > 0)
            <div class="card-body">
                @canAny('contacts.export')
                    <div class="d-flex mg-b-20">
                        <div class="pd-10 order-3">
                            <form id="export_excel_form" action="{{route('admin.contacts.export.excel', [request()->getQueryString()])}}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    @lang('Export as Excel')
                                </button>
                            </form>
                        </div>
                        <div class="pd-10 order-2">
                            <form action="{{route('admin.contacts.export.pdf')}}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    @lang('Export as PDF')
                                </button>
                            </form>
                        </div>
                    </div>
                @endcanAny
                <div class="table-responsive border newlist-table">
                    <table class="table card-table table-striped table-vcenter text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th class="wd-lg-8p"><span>@lang('Username')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Phone')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Email')</span></th>
                                <th class="wd-lg-8p"><span>@lang('Status')</span></th>
                                <th class="wd-lg-20p"><span>@lang('Created At')</span></th>
                                <th class="wd-lg-20p">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                <tr>
                                    <td>
                                        {{ $list->name ?? '-----' }}
                                    </td>
                                    <td>
                                        <a href="tel:{{ $list->phone ?? '-----' }}">{{ $list->phone ?? '-----' }}</a>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $list->email }}">{{ $list->email }}</a>
                                    </td>
                                    <td>
                                        {!! $list->showStatus() !!}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($list->created_at)->diffForHumans() ?? '' }}
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @canAny('contacts.show')
                                                @include('admin.component.buttons.show_actions', [
                                                    'url' => route('admin.contacts.show',$list->id),
                                                ])
                                            @endcanAny
                                            @canAny('contacts.destroy')
                                                @include('admin.component.buttons.delete_actions', [
                                                    'url' => route('admin.contacts.destroy',$list->id),
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
                'name' => __('contacts')
            ])
            @endif
        </div>
    </div>
</div>

@include('admin.component.modals.filter', [
    'fields' => [
        [
            'name' => 'name',
            'label' => 'Search by name or email or phone number',
            'type' => 'text'
        ],
        [
            'name' => 'seen',
            'label' => 'المشاهده',
            'type' => 'select',
            'data' => [
                [
                    'id' => 1,
                    'name' => 'مشاهده'
                ],
                [
                    'id' => 0,
                    'name' => 'لم يتم المشاهده'
                ]
            ],
            'translate' => true
        ]
    ],
    'url' => route('admin.contacts.index')
])
@include('admin.component.modals.delete')

@endsection
