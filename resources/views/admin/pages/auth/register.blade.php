<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(\App::getLocale() == "ar")  style="direction:rtl" @endif>
	<head>
		<meta charset="utf-8">
		<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
		<!-- Favicon -->
		<link rel="icon" href="{{ asset('icon.png') }}" type="image/x-icon"/>

		<!-- Title -->
		<title>
            @lang('Register') | {{ env('APP_NAME') }} - @lang('Dashboard')
        </title>

		<!-- Base  -->
		<base href="{{ url('/') }}">

		@if(App::isLocale('ar'))
			<!-- Bootstrap css-->
			<link  id="style" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet"/>
		@else
			<!-- Bootstrap css-->
			<link  id="style" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"/>
		@endif

		<!-- Icons css-->
		<link href="{{ asset('assets/plugins/web-fonts/icons.css') }}" rel="stylesheet"/>
		<link href="{{ asset('assets/plugins/web-fonts/font-awesome/font-awesome.min.css') }}" rel="stylesheet">
		<link href="{{ asset('assets/plugins/web-fonts/plugin.css') }}" rel="stylesheet"/>

		<!-- Style css-->
		<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

        <!-- Select2 css -->
        <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />

		@if(App::isLocale('ar'))
			<link rel="preconnect" href="https://fonts.googleapis.com">
			<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
			<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@500&display=swap" rel="stylesheet">
			<style>
				* {
					font-family: 'Tajawal', sans-serif;
				}
			</style>
		@endif
		<style>
			.help-block {
				color: red;
			}
			.rtl .signpages .details:before {
				background-color: black !important;
			}
			@media only screen and (max-width: 800px) {
				.xxsl {
					background: black !important;
					color: white !important;
				}
			}
		</style>
	</head>


    <body class="@if(App::isLocale('ar')) rtl @else ltr @endif main-body leftmenu error-1" style="background-image: url({{asset('assets_website/landing/images/bg4.png')}});background-repeat:no-repeat;background-size: cover;">
    <!-- Loader -->
    {{--<div id="global-loader">
        <img src="{{ asset('assets/img/loader.svg') }}" class="loader-img" alt="Loader">
    </div>--}}
    <!-- End Loader -->

		<!-- Page -->
		<div class="page main-signin-wrapper">

			<!-- Row -->
			<div class="row signpages">
				<div class="col-md-12">
					<div class="card">
						<div class="row row-sm">
							<div class="col-lg-12 col-xl-12 col-xs-12 col-sm-12 login_form  xxsl">
								<div class="main-container container-fluid">
									<div class="row row-sm">
										<div class="card-body mt-2 mb-2">


                                            <div class="card-body">
                                                @foreach (config('laravellocalization.supportedLocales') as $key=>$value)
                                                    <a class="btn btn-flat-success btn-sm"
                                                       href="{{ LaravelLocalization::getLocalizedURL($key, null, [], true) }}"
                                                       hreflang="{{ $key }}"
                                                       data-language="{{ $key }}" >
                                                        <span class="align-middle">
                                                            @if($value["name"] == "Arabic") العربية @else English @endif
                                                        </span>
                                                    </a>
                                            @endforeach


                                            <!-- Logo -->
                                                <div class="app-brand justify-content-center mb-4 mt-2">
                                                    <a href="{{url('/')}}" class="app-brand-link gap-2">
                                                        <img src="{{ url(getSettings('logo','/logo.png')) }}" style=" width: 75px; ">
                                                    </a>
                                                </div>
                                                <!-- /Logo -->



											<div class="clearfix"></div>

                                                    <h4 class="text-start mb-4 pt-2">
                                                        @if(App::isLocale('ar'))

                                                            <a href="{{ url('/') }}" style="vertical-align: middle;">
                                                                <i class="ti-arrow-right"></i>
                                                            </a>
                                                            @lang('Register New Club') 👋
                                                        @else
                                                            <a href="{{ url('/') }}">
                                                                <i class="ti-arrow-left" style="vertical-align: middle;"></i>
                                                            </a>
                                                             @lang('Register New Club') 👋
                                                        @endif
                                                    </h4>
                                                    <hr> <br>

                                            <form @if (App::isLocale('ar'))style="direction: ltr;"
                                            @endif method="POST" action="{{ route('admin.register.store') }}" enctype="multipart/form-data">
												@csrf

                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group ">
                                                            <label for="name">
                                                                @lang('Club Name Arabic')
                                                            </label>
                                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                                name="name"
                                                                id="name"
                                                                value="{{ old('name') }}"
                                                                placeholder="">
                                                                @error('name')
                                                                    <span class="text-danger">
                                                                        {{ $message }}
                                                                    </span>
                                                                @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group ">
                                                            <label for="name_en">
                                                                @lang('Club Name English')
                                                            </label>
                                                            <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                                                                name="name_en"
                                                                id="name_en"
                                                                value="{{ old('name_en') }}"
                                                                placeholder="">
                                                                @error('name_en')
                                                                    <span class="text-danger">
                                                                        {{ $message }}
                                                                    </span>
                                                                @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group ">
                                                            <label for="about">
                                                                @lang('Club About Arabic')
                                                            </label>
                                                            <textarea placeholder="" style=" height: 150px; " class="form-control @error('about') is-invalid @enderror" name="about" id="about">{{ old('about') }}</textarea>
                                                            @error('about')
                                                                <span class="text-danger">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group ">
                                                            <label for="about_en">
                                                                @lang('Club About English')
                                                            </label>
                                                            <textarea placeholder="" style=" height: 150px; " class="form-control @error('about_en') is-invalid @enderror" name="about_en" id="about_en">{{ old('about_en') }}</textarea>
                                                            @error('about_en')
                                                                <span class="text-danger">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>


                                                    <div class="col-md-6">
                                                        <div class="form-group ">
                                                            <label for="email">
                                                                @lang('Club Email')
                                                            </label>
                                                            <input type="text" class="form-control @error('email') is-invalid @enderror"
                                                                name="email"
                                                                id="email"
                                                                value="{{ old('email') }}"
                                                                placeholder="">
                                                                @error('email')
                                                                    <span class="text-danger">
                                                                        {{ $message }}
                                                                    </span>
                                                                @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group ">
                                                            <label for="phone">
                                                                @lang('Club Phone')
                                                            </label>
                                                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                                                name="phone"
                                                                id="phone"
                                                                value="{{ old('phone') }}"
                                                                placeholder="">
                                                                @error('phone')
                                                                    <span class="text-danger">
                                                                        {{ $message }}
                                                                    </span>
                                                                @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Password')</label>
                                                            <div class="input-group auth-pass-inputgroup">
                                                                <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="" name="password">
                                                            </div>
                                                            @error('password')
                                                                <span class="text-danger text-start">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Password Confirmation')</label>
                                                            <div class="input-group auth-pass-inputgroup">
                                                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="" name="password_confirmation">
                                                            </div>
                                                            @error('password_confirmation')
                                                                <span class="text-danger text-start">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        @include('admin.component.form_fields.select', [
                                                            'label' => 'Categories',
                                                            'name' => 'categories[]',
                                                            'data'  => \App\Models\Category::where("parent_id",0)->get(),
                                                            'required' => true,
                                                            'multiple' => true,
                                                            'value' => null
                                                        ])
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>@lang('Club Logo')</label>
                                                            <div class="input-group auth-pass-inputgroup">
                                                                <input type="file" class="form-control @error('logo') is-invalid @enderror" name="logo">
                                                            </div>
                                                            @error('logo')
                                                                <span class="text-danger text-start">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="mt-3 d-grid">
                                                    <button class="btn ripple btn-main-primary btn-block" type="submit">@lang('Register')</button>
                                                </div>
                                            </form>

										</div>
                                            <p class="text-center mt-2">
                                                <a href="{{ route('admin.login') }}"> <i data-feather="chevron-left"></i> @lang('Back to login') </a>
                                            </p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="mt-5 text-center">
						<span>{{ date('Y') }}  <a href="{{ env("COMPANY_URL") }}">{{ env("COMPANY_NAME") }}</a></span>
                	</div>
				</div>
			</div>
			<!-- End Row -->

		</div>
		<!-- End Page -->

		<!-- Jquery js-->
		<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>

		<!-- Bootstrap js-->
		<script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
		<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

		<!-- Perfect-scrollbar js -->
		<script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

		<!-- Color Theme js -->
		<script src="{{ asset('assets/js/themeColors.js') }}"></script>

		<!-- Custom js -->
		<script src="{{ asset('assets/js/custom.js') }}"></script>
        <!-- Select2 js-->
		<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
		@include('admin.layouts.scripts.select2')
	</body>
</html>
