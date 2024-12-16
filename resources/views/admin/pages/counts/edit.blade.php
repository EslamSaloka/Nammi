@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            @if(isset($count))
                <form action="{{ route('admin.counts.update', [$count->id, 'page' => request()->query('page')]) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form action="{{ route('admin.counts.store') }}" method="post" enctype="multipart/form-data">
            @endif
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                @include('admin.component.inc.lang', [
                                    'data' => \App\Models\Landing\Count::LOCALIZATION_INPUTS,
                                    'model' => isset($count) ? $count : null,
                                    'group_key' => 'counts',
                                    "is_editor" => true
                                ])
                            </div>
                        </div>

                        <div class="col-md-12">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Count',
                                'name' => 'count',
                                'placeholder' => 'Enter Count',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('count') ??(isset($count) ? $count->count : null)
                            ])
                        </div>

                        <div class="col-md-12">
                            @include('admin.component.form_fields.image', [
                                'label' => 'Image',
                                'name' => 'image',
                                'value' => old('img_base64') ?? (isset($count) ? $count->display_image : null)
                            ])
                        </div>

                    </div>
                </div>
                <div class="card-footer mb-1">
                    @csrf
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    <a href="{{ route('admin.counts.index')}}" class="btn btn-danger">@lang('Cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    @if (isset($count))
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Counts\UpdateRequest') !!}
    @else
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Counts\CreateRequest') !!}
    @endif
@endpush
