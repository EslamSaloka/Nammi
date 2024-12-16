@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            @if(isset($banner))
                <form action="{{ route('admin.categories.banners.update', [$category->id, $banner->id]) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form action="{{ route('admin.categories.banners.store',$category->id) }}" method="post" enctype="multipart/form-data">
            @endif
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12">
                            @include('admin.component.form_fields.image', [
                                'label' => 'IMAGE',
                                'name' => 'image',
                                'value' => old('img_base64') ?? (isset($banner) ? $banner->display_image : null)
                            ])
                        </div>

                    </div>
                </div>
                <div class="card-footer mb-1">
                    @csrf
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    <a href="{{ route('admin.categories.banners.index',$category->id)}}" class="btn btn-danger">@lang('Cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    @if (isset($category))
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Categories\Banners\UpdateRequest') !!}
    @else
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Categories\Banners\CreateRequest') !!}
    @endif
@endpush
