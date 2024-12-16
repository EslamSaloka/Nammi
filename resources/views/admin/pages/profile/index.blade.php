@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')
@section('buttons')

@endsection

<div class="row row-sm">
    <div class="col-lg-12 col-md-12 col-md-12">
        <div class="card custom-card">
            <form action="{{ route('admin.profile.store') }}" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => (isAdmin()) ? 'Name' : 'Club Name Arabic',
                                'name' => 'name',
                                'type' => 'text',
                                'value' => old('name', $user->name)
                            ])
                        </div>
                        @if (!isAdmin())
                            <div class="col-md-6">
                                @include('admin.component.form_fields.input', [
                                    'label' => (isAdmin()) ? 'Name' : 'Club Name English',
                                    'name' => 'name_en',
                                    'type' => 'text',
                                    'value' => old('name_en', $user->name_en)
                                ])
                            </div>
                        @endif
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Email',
                                'name' => 'email',
                                'type' => 'email',
                                'value' => old('email', $user->email)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Phone',
                                'name' => 'phone',
                                'placeholder' => 'Enter Phone',
                                'type' => 'number',
                                'value' => old('phone', $user->phone)
                            ])
                        </div>
                        <div class="col-md-12">
                            @include('admin.component.form_fields.image', [
                                'label' => (isAdmin()) ? 'Image' : 'Club Logo',
                                'name' => 'avatar',
                                'value' => auth()->user()->display_image
                            ])
                        </div>
                    </div>
                </div>
                <div class="card-footer mb-1">
                    @csrf
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    <a href="{{ route('admin.home.index')}}" class="btn btn-danger">@lang('Cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
