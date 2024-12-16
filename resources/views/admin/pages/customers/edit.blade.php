@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            @if(isset($customer))
                <form action="{{ route('admin.customers.update', [$customer->id, 'page' => request()->query('page')]) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form action="{{ route('admin.customers.store') }}" method="post" enctype="multipart/form-data">
            @endif
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'User Name',
                                'name' => 'name',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('name') ??(isset($customer) ? $customer->name : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Email',
                                'name' => 'email',
                                'placeholder' => 'Email',
                                'type' => 'email',
                                'required' => true,
                                'value' => old('email') ??(isset($customer) ? $customer->email : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Phone',
                                'name' => 'phone',
                                'placeholder' => 'Enter Phone',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('phone') ??(isset($customer) ? $customer->phone : null)
                            ])
                        </div>


                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'birthday',
                                'name' => 'birthday',
                                'placeholder' => 'birthday',
                                'type' => 'date',
                                'required' => true,
                                'value' => old('birthday') ??(isset($customer) ? Carbon\Carbon::parse($customer->birthday)->format("Y-m-d") : null)
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('admin.component.form_fields.select', [
                                'label' => 'gender',
                                'name' => 'gender',
                                'data'  => [
                                    (object)[
                                        "id"      => "male",
                                        "name"    => __("Male"),
                                    ],
                                    (object)[
                                        "id"      => "female",
                                        "name"    => __("Female"),
                                    ],
                                ],
                                'required' => true,
                                'value' => (isset($customer) ? $customer->gender : null)
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('admin.component.form_fields.select', [
                                'label' => 'disabilities',
                                'name' => 'disabilities',
                                'data'  => [
                                    (object)[
                                        "id"      => 0,
                                        "name"    => __("No"),
                                    ],
                                    (object)[
                                        "id"      => 1,
                                        "name"    => __("Yes"),
                                    ],
                                ],
                                'required' => true,
                                'value' => (isset($customer) ? $customer->disabilities : null)
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('admin.component.form_fields.select', [
                                'label' => 'time',
                                'name' => 'time_id',
                                'data'  => \App\Models\Time::all(),
                                'required' => true,
                                'value' => (isset($customer) ? $customer->time_id : null)
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => isset($customer) ? 'New Password' : 'Password',
                                'name' => 'password',
                                'placeholder' => 'Enter password',
                                'type' => 'password',
                                'required' => !isset($customer) ? true : false,
                                'value' => null
                            ])
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="suspend">@lang('Status') <span class="tx-danger">*</span></label>
                                <select name="suspend" id="suspend" class="form-control select2 @error('suspend') is-invalid @enderror">
                                    <option @if(isset($customer)) @if($customer->suspend == 1) selected @endif @endif value="1">@lang('Suspend')</option>
                                    <option @if(isset($customer)) @if($customer->suspend == 0) selected @endif @endif value="0">@lang('UnSuspend')</option>
                                </select>
                                @error('suspend')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            @include('admin.component.form_fields.image', [
                                'label' => 'Image',
                                'name' => 'avatar',
                                'value' => old('img_base64') ?? (isset($customer) && $customer->avatar ? asset($customer->avatar) : null)
                            ])
                        </div>


                    </div>
                </div>
                <div class="card-footer mb-1">
                    @csrf
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    <a href="{{ route('admin.customers.index')}}" class="btn btn-danger">@lang('Cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    @if (isset($customer))
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Customers\UpdateCustomersRequest') !!}
    @else
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Customers\CreateCustomersRequest') !!}
    @endif
@endpush
