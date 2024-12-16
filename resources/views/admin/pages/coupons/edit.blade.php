@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')
<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            @if(isset($coupon))
                <form action="{{ route('admin.coupons.update', [$coupon->id, 'page' => request()->query('page')]) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form action="{{ route('admin.coupons.store') }}" method="post" enctype="multipart/form-data">
            @endif
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Coupon Code',
                                'name' => 'name',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('name') ??(isset($coupon) ? $coupon->name : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Expire Date',
                                'name' => 'expire',
                                'type' => 'date',
                                'min' => 1,
                                'required' => true,
                                'value' => old('expire') ??(isset($coupon) ? Carbon\Carbon::parse($coupon->expire)->format("Y-m-d") : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Value',
                                'name' => 'value',
                                'type' => 'number',
                                'required' => true,
                                'value' => old('value') ??(isset($coupon) ? $coupon->value : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">@lang('Type') <span class="tx-danger">*</span></label>
                                <select name="type" id="type" class="form-control select2 @error('role') is-invalid @enderror">
                                    <option @if (isset($coupon))
                                        @if ($coupon->type == "fixed")
                                            selected
                                        @endif
                                    @endif value="fixed">@lang("Fixed")</option>
                                    <option @if (isset($coupon))
                                        @if ($coupon->type == "prec")
                                            selected
                                        @endif
                                    @endif value="prec">@lang("Percentage")</option>
                                </select>
                                @error('type')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer mb-1">
                    @csrf
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    <a href="{{ route('admin.coupons.index')}}" class="btn btn-danger">@lang('Cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    @if (isset($coupon))
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Coupons\UpdateRequest') !!}
    @else
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Coupons\CreateRequest') !!}
    @endif
@endpush
