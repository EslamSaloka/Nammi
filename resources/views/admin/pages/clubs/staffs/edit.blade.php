@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            @if(isset($staff))
                <form action="{{ route('admin.clubs.staffs.update', [$staff->id, 'page' => request()->query('page')]) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form action="{{ route('admin.clubs.staffs.store') }}" method="post" enctype="multipart/form-data">
            @endif
                <div class="card-body">
                    <div class="row">

                        @if (isAdmin())
                            <div class="col-md-12">
                                @include('admin.component.form_fields.select', [
                                    'label' => 'Club',
                                    'name' => 'club_id',
                                    'data'  => $clubs,
                                    'required' => true,
                                    'keyV'      => (App::getLocale() == "ar") ? "name" : "name_en",
                                    'value' => old('club_id') ?? (isset($staff) ? $staff->getStaffClub->club_id ?? null : null)
                                ])
                            </div>
                        @endif

                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'User Name',
                                'name' => 'name',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('name') ??(isset($staff) ? $staff->name : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Email',
                                'name' => 'email',
                                'placeholder' => 'Email',
                                'type' => 'email',
                                'required' => true,
                                'value' => old('email') ??(isset($staff) ? $staff->email : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => 'Phone',
                                'name' => 'phone',
                                'placeholder' => 'Enter Phone',
                                'type' => 'text',
                                'required' => true,
                                'value' => old('phone') ??(isset($staff) ? $staff->phone : null)
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.component.form_fields.input', [
                                'label' => isset($staff) ? 'New Password' : 'Password',
                                'name' => 'password',
                                'placeholder' => 'Enter password',
                                'type' => 'password',
                                'required' => !isset($staff) ? true : false,
                                'value' => null
                            ])
                        </div>

                        <div class="col-md-12">
                            @include('admin.component.form_fields.image', [
                                'label' => 'Image',
                                'name' => 'avatar',
                                'value' => old('img_base64') ?? (isset($staff) && $staff->avatar ? asset($staff->avatar) : null)
                            ])
                        </div>

                    </div>
                </div>
                <div class="card-footer mb-1">
                    @csrf
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    <a href="{{ route('admin.clubs.staffs.index')}}" class="btn btn-danger">@lang('Cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    @if (isset($staff))
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Clubs\Staffs\UpdateRequest') !!}
    @else
        {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Clubs\Staffs\CreateRequest') !!}
    @endif
@endpush
