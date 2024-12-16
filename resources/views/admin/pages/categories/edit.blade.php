@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            @if(isset($category))
                <form action="{{ route('admin.categories.update', [$category->id, 'page' => request()->query('page')]) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form action="{{ route('admin.categories.store') }}" method="post" enctype="multipart/form-data">
            @endif
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                @include('admin.component.inc.lang', [
                                    'data' => \App\Models\Category::LOCALIZATION_INPUTS,
                                    'model' => isset($category) ? $category : null,
                                    'group_key' => 'categories'
                                ])
                            </div>
                        </div>

                        <div class="col-md-12">
                            @include('admin.component.form_fields.input', [
                                'label' => 'HexaCode Color',
                                'name' => 'hexacode_color',
                                'type' => 'text',
                                'required' => false,
                                'value' => old('hexacode_color') ??(isset($category) ? $category->hexacode_color : null)
                            ])
                        </div>

                        <div class="col-md-12">
                            @include('admin.component.form_fields.image', [
                                'label' => 'IMAGE',
                                'name' => 'image',
                                'value' => old('img_base64') ?? (isset($category) ? $category->display_image : null)
                            ])
                        </div>

                    </div>
                </div>
                <div class="card-footer mb-1">
                    @csrf
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    <a href="{{ route('admin.categories.index')}}" class="btn btn-danger">@lang('Cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    @if (isset($category))
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Categories\UpdateRequest') !!}
    @else
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Categories\CreateRequest') !!}
    @endif
@endpush
