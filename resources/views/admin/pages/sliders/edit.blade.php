@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            @if(isset($slider))
                <form action="{{ route('admin.sliders.update', $slider->id) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form action="{{ route('admin.sliders.store') }}" method="post" enctype="multipart/form-data">
            @endif

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6">
                            @include('admin.component.form_fields.select', [
                                'label' => 'Choose Activity',
                                'name' => 'activity_id',
                                'data'  => $activities,
                                'required' => true,
                                'value' => old('activity_id') ?? (isset($slider) ? $slider->activity_id : null)
                                ])
                        </div>

                        <div class="col-md-12">
                            @include('admin.component.form_fields.image', [
                                'label' => 'Image',
                                'name' => 'image',
                                'value' => old('img_base64') ?? (isset($slider) ? $slider->display_image : null)
                            ])
                        </div>


                    </div>
                </div>
                <div class="card-footer mb-1">
                    @csrf
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    <a href="{{ route('admin.sliders.index')}}" class="btn btn-danger">@lang('Cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
@endpush
