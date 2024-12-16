@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')


@canAny('notifications.create')

<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            <form action="{{ route('admin.notifications.store') }}" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @include('admin.component.form_fields.select', [
                                'label' => 'Users',
                                'name' => 'users[]',
                                'data'  => $users,
                                'required' => true,
                                'multiple' => true,
                                'value' => null
                            ])
                        </div>
                        <div class="col-md-12">
                            @include('admin.component.form_fields.textarea', [
                                'label' => 'Notifications Messages',
                                'name' => 'body',
                                'type' => 'textarea',
                                'required' => true,
                                'value' => null
                            ])
                        </div>
                    </div>
                </div>
                <div class="card-footer mb-1">
                    @csrf
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endcanAny


@if (!isAdmin())
    <div class="row row-sm">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
            <div class="card custom-card">
                @if ($lists->count() > 0)
                <div class="card-body">
                    <div class="table-responsive border userlist-table">
                        <table class="table card-table table-striped table-vcenter text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th class="wd-lg-8p"><span>@lang('Body')</span></th>
                                    <th class="wd-lg-8p"><span>@lang('Seen')</span></th>
                                    <th class="wd-lg-8p"><span>@lang('Created At')</span></th>
                                    <th class="wd-lg-20p">@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lists as $list)
                                    <tr>
                                        <td>
                                            {{ $list->body ?? '' }}
                                        </td>
                                        <td>
                                            {!! $list->showSeen() !!}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($list->created_at)->diffForHumans() ?? '' }}
                                        </td>
                                        <td>
                                            <div class="hstack gap-2 fs-15">
                                                @canAny('notifications.show')
                                                    @include('admin.component.buttons.show_actions', [
                                                        'url' => route('admin.notifications.show',$list->id),
                                                    ])
                                                @endcanAny
                                                @canAny('notifications.destroy')
                                                    @include('admin.component.buttons.delete_actions', [
                                                        'url' => route('admin.notifications.destroy',$list->id),
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
                        'name' => __('Notifications')
                    ])
                @endif
            </div>
        </div>
    </div>
    @include('admin.component.modals.delete')
@endif

@endsection
