<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(\App::getLocale() == "ar")  style="direction:rtl" @endif>
	<head>
		<meta charset="utf-8">
		<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
		<!-- Favicon -->
		<link rel="icon" href="{{ asset('icon.png') }}" type="image/x-icon"/>

		<!-- Title -->
		<title>
            @lang('Reset Password') | {{ env('APP_NAME') }} - @lang('Dashboard')
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

	<body class="@if(App::isLocale('ar')) rtl @else ltr @endif main-body leftmenu error-1">
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
							<div class="col-lg-6 col-xl-5 d-none d-lg-block text-center bg-primary details">
								<div class="mt-5 pt-4 p-2 pos-absolute">
									<img src="{{ getSettings("logo",asset('/dashboard.svg')) }}" style=" position: relative;" class="d-lg-none header-brand-img text-start float-start mb-4 error-logo-light" alt="logo">
									<img src="{{ getSettings("logo",asset('/dashboard.svg')) }}" style=" position: relative;" class=" d-lg-none header-brand-img text-start float-start mb-4 error-logo" alt="logo">
									<div class="clearfix"></div>
									<img src="{{ getSettings("logo",asset('/dashboard.svg')) }}" class="ht-100 mb-0" alt="user" style="">
                                    <h5 class="mt-4 text-white">@lang('Reset Password')</h5>
                                    <span class="tx-white-6 tx-13 mb-5 mt-xl-0"></span>
								</div>
							</div>
							<div class="col-lg-6 col-xl-7 col-xs-12 col-sm-12 login_form xxsl">
								<div class="main-container container-fluid">
									<div class="row row-sm">
										<div class="card-body mt-2 mb-2">
											<img src="{{ getSettings("logo",asset('/dashboard.svg')) }}" style=" position: relative; left: 22px; " class=" d-lg-none header-brand-img text-start float-start mb-4" alt="logo">
											<div class="clearfix"></div>
                                            <form style="direction: ltr;" class="form-horizontal" method="POST" action="{{ route('admin.resetPassword.send') }}">
                                                @csrf
												<h5 class="text-start mb-2">
                                                    @if(App::isLocale('ar'))
                                                        @lang('Reset Password')
                                                        <a href="{{ route("admin.forgetPassword.index") }}">
                                                            <i class="ti-arrow-circle-right"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route("admin.forgetPassword.index") }}">
                                                            <i class="ti-arrow-circle-left"></i>
                                                        </a>
                                                        @lang('Reset Password')
                                                    @endif
												</h5>

                                                <br><br>
                                                <div class="mb-3" style="text-align: end; display:none">
                                                    <label for="phone" class="form-label">
                                                        @lang('Phone Number')
                                                    </label>
                                                    <input
                                                        type="phone"
                                                        class="form-control"
                                                        name="phone"
                                                        id="phone"
                                                        @if(App::isLocale('ar'))
														style=" direction: rtl; "
                                                        @endif
                                                        value="{{ request("phone") }}"
                                                        placeholder="@lang('Phone Number')">
                                                        @error('phone')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                </div>

                                                <div class="mb-3" @if(App::isLocale('ar')) style="text-align: end;" @endif>
                                                    <label for="otp" class="form-label">
                                                        @lang('OTP')
                                                    </label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        name="otp"
                                                        id="otp"
														@if(App::isLocale('ar'))
														style=" direction: rtl; "
                                                        @endif
                                                        value="{{ old('otp') }}"
                                                        placeholder="@lang('OTP')">
                                                        @error('otp')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
														@if(Session::get('danger'))
															<span class="text-danger">
																{{ Session::get('danger') }}
															</span>
														@endif
                                                </div>

                                                <div class="mb-3" @if(App::isLocale('ar')) style="text-align: end;" @endif>
                                                    <label for="password" class="form-label">
                                                        @lang('Enter New Password')
                                                    </label>
                                                    <input
                                                        type="password"
                                                        class="form-control"
                                                        name="password"
                                                        id="password"
														@if(App::isLocale('ar'))
														style=" direction: rtl; "
                                                        @endif
                                                        value="{{ old('password') }}"
                                                        placeholder="@lang('Enter New Password')">
                                                        @error('password')
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                </div>

                                                <br>
                                                <div class="mt-3 d-grid">
                                                    <button class="btn btn-primary waves-effect waves-light" type="submit">@lang('Save Changes')</button>
                                                </div>
                                            </form>
										</div>
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

		<!-- Select2 js-->
		<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
		@include('admin.layouts.scripts.select2')

		<!-- Perfect-scrollbar js -->
		<script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

		<!-- Color Theme js -->
		<script src="{{ asset('assets/js/themeColors.js') }}"></script>

		<!-- Custom js -->
		<script src="{{ asset('assets/js/custom.js') }}"></script>

		<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
        {{-- {!! JsValidator::formRequest('App\Http\Requests\Dashboard\Auth\AuthRequest') !!} --}}

	</body>
</html>
