@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            @if(isset($admin))
                <form action="{{ route('admin.admins.update', [$admin->id, 'page' => request()->query('page')]) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form action="{{ route('admin.admins.store') }}" method="post" enctype="multipart/form-data">
            @endif
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'User Name',
                                'name' => 'name',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('name') ??(isset($admin) ? $admin->name : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Email',
                                'name' => 'email',
                                'placeholder' => 'Email',
                                'type' => 'email',
                                'required' => true,
                                'value' => old('email') ??(isset($admin) ? $admin->email : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Phone',
                                'name' => 'phone',
                                'placeholder' => 'Enter Phone',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('phone') ??(isset($admin) ? $admin->phone : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => isset($admin) ? 'New Password' : 'Password',
                                'name' => 'password',
                                'placeholder' => 'Enter password',
                                'type' => 'password',
                                'required' => !isset($admin) ? true : false,
                                'value' => null
                            ])
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="suspend">@lang('Status') <span class="tx-danger">*</span></label>
                                <select name="suspend" id="suspend" class="form-control select2 @error('suspend') is-invalid @enderror">
                                    <option @if(isset($admin)) @if($admin->suspend == 1) selected @endif @endif value="1">@lang('Suspend')</option>
                                    <option @if(isset($admin)) @if($admin->suspend == 0) selected @endif @endif value="0">@lang('UnSuspend')</option>
                                </select>
                                @error('suspend')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.select', [
                                'label' => 'Role',
                                'name' => 'role',
                                'data'  => $roles,
                                'required' => true,
                                'value' => (isset($admin)) ? $admin->roles()->first()->id ?? 0 : 0
                            ])
                        </div>

                        <div class="col-md-12">
                            @include('admin.component.form_fields.image', [
                                'label' => 'Image',
                                'name' => 'avatar',
                                'value' => old('img_base64') ?? (isset($admin) && $admin->avatar ? asset($admin->avatar) : null)
                            ])
                        </div>

                    </div>
                </div>
                <div class="card-footer mb-1">
                    @csrf
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    <a href="{{ route('admin.admins.index')}}" class="btn btn-danger">@lang('Cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    @if (isset($admin))
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Admins\UpdateAdminsRequest') !!}
    @else
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Admins\CreateAdminsRequest') !!}
    @endif
@endpush
