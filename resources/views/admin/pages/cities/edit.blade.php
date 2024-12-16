@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            @if(isset($city))
                <form action="{{ route('admin.countries.cities.update', [$country->id,$city->id]) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form action="{{ route('admin.countries.cities.store',$country->id) }}" method="post" enctype="multipart/form-data">
            @endif
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                @include('admin.component.inc.lang', [
                                    'data' => \App\Models\City::LOCALIZATION_INPUTS,
                                    'model' => isset($city) ? $city : null,
                                    'group_key' => 'countries'
                                ])
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer mb-1">
                    @csrf
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    <a href="{{ route('admin.countries.cities.index',$country->id)}}" class="btn btn-danger">@lang('Cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    @if (isset($city))
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Cities\UpdateRequest') !!}
    @else
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Cities\CreateRequest') !!}
    @endif
@endpush
