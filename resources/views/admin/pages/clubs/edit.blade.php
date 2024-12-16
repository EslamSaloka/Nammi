@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            @if(isset($club))
                <form action="{{ route('admin.clubs.update', $club->id) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form action="{{ route('admin.clubs.store') }}" method="post" enctype="multipart/form-data">
            @endif

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Club Name Arabic',
                                'name' => 'name',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('name') ??(isset($club) ? $club->name : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Club Name English',
                                'name' => 'name_en',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('name_en') ??(isset($club) ? $club->name_en : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.textarea', [
                                'label' => 'Club About Arabic',
                                'name' => 'about',
                                'type' => 'textarea',
                                'required' => true,
                                'value' => old('about') ??(isset($club) ? $club->about : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.textarea', [
                                'label' => 'Club About English',
                                'name' => 'about_en',
                                'type' => 'textarea',
                                'required' => true,
                                'value' => old('about_en') ??(isset($club) ? $club->about_en : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Email',
                                'name' => 'email',
                                'placeholder' => 'Email',
                                'type' => 'email',
                                'required' => true,
                                'value' => old('email') ??(isset($club) ? $club->email : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Phone',
                                'name' => 'phone',
                                'placeholder' => 'Enter Phone',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('phone') ??(isset($club) ? $club->phone : null)
                            ])
                        </div>
                        @if (isAdmin())

                            <div class="col-md-6">
                                @include('admin.component.form_fields.input', [
                                    'label' => 'Commission',
                                    'name' => 'vat',
                                    'placeholder' => 'Enter Commission Value',
                                    'type' => 'number',
                                    'required' => true,
                                    'value' => old('vat') ??(isset($club) ? $club->vat : null)
                                ])
                            </div>


                            <div class="col-md-6">
                                @include('admin.component.form_fields.input', [
                                    'label' => isset($club) ? 'New Password' : 'Password',
                                    'name' => 'password',
                                    'placeholder' => 'Enter password',
                                    'type' => 'password',
                                    'required' => !isset($club) ? true : false,
                                    'value' => null
                                ])
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="suspend">@lang('Status') <span class="tx-danger">*</span></label>
                                    <select name="suspend" id="suspend" class="form-control select2 @error('suspend') is-invalid @enderror">
                                        <option @if(isset($club)) @if($club->suspend == 1) selected @endif @endif value="1">@lang('Suspend')</option>
                                        <option @if(isset($club)) @if($club->suspend == 0) selected @endif @endif value="0">@lang('UnSuspend')</option>
                                    </select>
                                    @error('suspend')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <div class="col-md-6">
                            @include('admin.component.form_fields.select', [
                                'label' => 'Categories',
                                'name' => 'categories[]',
                                'data'  => \App\Models\Category::where("parent_id",0)->get(),
                                'required' => true,
                                'multiple' => true,
                                'value' => (isset($club) ? $club->categories()->pluck("category_id")->toArray() : null)
                            ])
                        </div>

                        <div class="col-md-12">
                            @include('admin.component.form_fields.image', [
                                'label' => 'Club Logo',
                                'name' => 'logo',
                                'value' => isset($club) ? $club->display_image : null,
                            ])
                        </div>

                        <div class="col-md-12">
                            @include('admin.component.form_fields.image', [
                                'label' => 'Club Images',
                                'name' => 'images',
                                'multi' => true,
                                'value' => null
                            ])
                        </div>

                        @if (isset($club))
                            @if ($club->clubImages()->count() > 0)
                                <div class="col-md-12">
                                    <div class="form-group" style="">
                                        <label >
                                            @lang('Club Images')
                                        </label>
                                        <br>
                                        @foreach ($club->clubImages as $image)
                                            <div class="" style="margin-top:15px;display: inline-flex;">
                                                <a href="{{ route('admin.clubs.images.destroy',[$club->id,$image->id]) }}">
                                                    <img src="{{ $image->display_image }}" alt="delete-Image" title="delete-Image" style="background-size:cover;width:200px;height:100px;max-height:200px;">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif


                    </div>
                </div>
                <div class="card-footer mb-1">
                    @csrf
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    <a href="{{ route('admin.clubs.index')}}" class="btn btn-danger">@lang('Cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    @if (isset($club))
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Clubs\UpdateClubsRequest') !!}
    @else
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Clubs\CreateClubsRequest') !!}
    @endif
@endpush
