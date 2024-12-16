@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            @if(isset($branch))
                <form action="{{ route('admin.clubs.branches.update', $branch->id) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form action="{{ route('admin.clubs.branches.store') }}" method="post" enctype="multipart/form-data">
            @endif

                <div class="card-body">
                    <div class="row">


                        <div class="col-md-12">
                            <div class="form-group">
                                @include('admin.component.inc.lang', [
                                    'data' => \App\Models\Club\Branch::LOCALIZATION_INPUTS,
                                    'model' => isset($branch) ? $branch : null,
                                    'group_key' => 'branchs'
                                ])
                            </div>
                        </div>


                        @if (isAdmin())
                            <div class="col-md-6">
                                @include('admin.component.form_fields.select', [
                                    'label' => 'Club',
                                    'name' => 'club_id',
                                    'data'  => $clubs,
                                    'required' => true,
                                    'keyV'      => (App::getLocale() == "ar") ? "name" : "name_en",
                                    'value' => old('club_id') ?? (isset($branch) ? $branch->club_id : null)
                                    ])
                            </div>
                            <div class="col-md-6" id="menus2">
                                @if (isset($branch))
                                    @php
                                        $ids    = \App\Models\Club\Staff::where("club_id",$branch->club_id)->pluck("user_id")->toArray();
                                        $users  = \App\Models\User::whereIn("id",$ids)->get();
                                    @endphp
                                    @include('admin.component.form_fields.select', [
                                        'label' => 'Branch Master',
                                        'name' => 'user_id',
                                        'data'  => $users,
                                        'required' => true,
                                        'keyV'      => "name",
                                        'value' => old('club_id') ?? (isset($branch) ? $branch->user_id : null)
                                    ])
                                @else
                                    <div class="form-group">
                                        <label>
                                            @lang('Branch Master')
                                            <span class="tx-danger">*</span>
                                        </label>
                                        <select class="form-control" disabled>
                                            <option value="0">
                                                @lang('Please Choose Club First')
                                            </option>
                                        </select>
                                    </div>
                                @endif
                            </div>
                        @else

                            <div class="col-md-12" id="menus2">
                                @php
                                    $ids    = \App\Models\Club\Staff::where("club_id",\Auth::user()->id)->pluck("user_id")->toArray();
                                    $users  = \App\Models\User::whereIn("id",$ids)->get();
                                @endphp
                                @include('admin.component.form_fields.select', [
                                    'label' => 'Branch Master',
                                    'name' => 'user_id',
                                    'data'  => $users,
                                    'required' => true,
                                    'keyV'      => "name",
                                    'value' => old('club_id') ?? (isset($branch) ? $branch->user_id : null)
                                ])
                            </div>

                        @endif


                        <div class="col-md-6">
                            @include('admin.component.form_fields.select', [
                                'label' => 'Country',
                                'name' => 'country_id',
                                'data'  => $countries,
                                'required' => true,
                                'value' => old('country_id') ??(isset($branch) ? $branch->country_id : null)
                            ])
                        </div>
                        <div class="col-md-6" id="menus">
                            @if (isset($branch))
                                @include('admin.component.form_fields.select', [
                                    'label' => 'City',
                                    'name' => 'city_id',
                                    'data'  => \App\Models\City::where("country_id",$branch->country_id)->get(),
                                    'required' => true,
                                    'value' => $branch->city_id
                                ])
                            @else
                                <div class="form-group">
                                    <label>
                                        @lang('City')
                                        <span class="tx-danger">*</span>
                                    </label>
                                    <select class="form-control" disabled>
                                        <option value="0">
                                            @lang('Please Choose Country First')
                                        </option>
                                    </select>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Email',
                                'name' => 'email',
                                'placeholder' => 'Email',
                                'type' => 'email',
                                'required' => true,
                                'value' => old('email') ??(isset($branch) ? $branch->email : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Phone',
                                'name' => 'phone',
                                'placeholder' => 'Enter Phone',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('phone') ??(isset($branch) ? $branch->phone : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'WhatApp',
                                'name' => 'what_app',
                                'placeholder' => 'Enter WhatApp',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('what_app') ??(isset($branch) ? $branch->what_app : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Lat',
                                'name' => 'lat',
                                'placeholder' => 'Enter Lat',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('lat') ??(isset($branch) ? $branch->lat : null)
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Lng',
                                'name' => 'lng',
                                'placeholder' => 'Enter Lng',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('lng') ??(isset($branch) ? $branch->lng : null)
                            ])
                        </div>


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
    @if (isset($branch))
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Clubs\Branches\UpdateRequest') !!}
    @else
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Clubs\Branches\CreateRequest') !!}
    @endif
    <script>
        $('#country_id').change(function(){
            $.get("{{ route('admin.countries.getCities') }}?country="+$(this).val(), function(data, status){
                $("#menus").html(data);
                $('.select2').select2();
            });
        });
        $('#club_id').change(function(){
            $.get("{{ route('admin.clubs.staffs.getAll') }}?club_id="+$(this).val(), function(data, status){
                $("#menus2").html(data);
                $('.select2').select2();
            });
        });
    </script>
@endpush
