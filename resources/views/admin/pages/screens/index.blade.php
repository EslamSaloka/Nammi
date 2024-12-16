@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')


@canAny('screens.create')

<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            <form action="{{ route('admin.screens.store') }}" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @include('admin.component.form_fields.image', [
                                'label' => 'Application Images',
                                'name' => 'images',
                                'multi' => true,
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

<div class="row row-sm">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
        <div class="card custom-card">
            @if ($lists->count() > 0)
            <div class="card-body">
                <div class="table-responsive border userlist-table">
                    <table class="table card-table table-striped table-vcenter text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th class="wd-lg-20p"><span>@lang('Image')</span></th>
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
                                        <div class="hstack gap-2 fs-15">
                                            @canAny('screens.destroy')
                                                @include('admin.component.buttons.delete_actions', [
                                                    'url' => route('admin.screens.destroy',$list->id),
                                                ])
                                            @endcanAny
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
                @include('admin.component.inc.nodata', [
                    'name' => __('screens')
                ])
            @endif
        </div>
    </div>
</div>
@include('admin.component.modals.delete')
@endsection
