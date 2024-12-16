@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            @if(isset($feedback))
                <form action="{{ route('admin.feedbacks.update', [$feedback->id, 'page' => request()->query('page')]) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form action="{{ route('admin.feedbacks.store') }}" method="post" enctype="multipart/form-data">
            @endif
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                @include('admin.component.inc.lang', [
                                    'data' => \App\Models\Landing\FeedBack::LOCALIZATION_INPUTS,
                                    'model' => isset($feedback) ? $feedback : null,
                                    'group_key' => 'feedbacks',
                                    "is_editor" => true
                                ])
                            </div>
                        </div>

                        <div class="col-md-12">
                            @include('admin.component.form_fields.input', [
                                'label' => 'star',
                                'name' => 'star',
                                'placeholder' => 'Enter star',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('star') ??(isset($feedback) ? $feedback->star : null)
                            ])
                        </div>

                    </div>
                </div>
                <div class="card-footer mb-1">
                    @csrf
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    <a href="{{ route('admin.feedbacks.index')}}" class="btn btn-danger">@lang('Cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    @if (isset($count))
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Feedbacks\UpdateRequest') !!}
    @else
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Feedbacks\CreateRequest') !!}
    @endif
@endpush
