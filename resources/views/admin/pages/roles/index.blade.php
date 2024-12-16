@extends('admin.layouts.master')
@section('title') {{ $breadcrumb['title'] }} @endsection
@section('PageContent')

@section('buttons')
    @canAny('roles.create')
        @include('admin.component.buttons.btn_href', [
            'title' => __('create new role'),
            'color_class' => 'primary',
            'url' => route('admin.roles.create'),
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
                                <th class="wd-lg-20p">@lang('Name')</th>
                                @canAny(['roles.edit','roles.destroy'])
                                    <th class="wd-lg-20p">@lang('Actions')</th>
                                @endcanAny
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                <tr>
                                    <td>
                                        {{ $list->name ? __($list->name) : '' }}
                                    </td>
                                    @canAny(['roles.edit','roles.destroy'])
                                        <td>
                                            <div class="hstack gap-2 fs-15">
                                                @canAny('roles.edit')
                                                    {{-- @if(!in_array($list->id,[1,2,3])) --}}
                                                        @include('admin.component.buttons.edit_actions', [
                                                            'url' => route('admin.roles.edit',$list->id),
                                                        ])
                                                    {{-- @endif --}}
                                                @endcanAny
                                                @canAny('roles.destroy')
                                                    {{-- @if(!in_array($list->id,[1,2,3])) --}}
                                                        @include('admin.component.buttons.delete_actions', [
                                                            'url' => route('admin.roles.destroy',$list->id),
                                                        ])
                                                    {{-- @endif --}}
                                                @endcanAny
                                            </div>
                                        </td>
                                    @endcanAny
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
                @include('admin.component.inc.nodata', [
                    'name' => __('Roles')
                ])
            @endif
        </div>
    </div>
</div>
@include('admin.component.modals.delete')
@endsection
