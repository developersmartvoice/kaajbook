<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" />
		<meta name="author" content="Chetsapp PVT LTD" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>@if (trim($__env->yieldContent('template_title')))@yield('template_title') | @endif {{ trans('installer_messages.title') }}</title>
		<link rel="shortcut icon" href="{{ asset('/img/favicon/vipspm-dark-logo.png') }}" sizes="16x16"/>
		<link rel="shortcut icon" href="{{ asset('/img/favicon/vipspm-dark-logo.png') }}" sizes="32x32"/>
		<link rel="shortcut icon" href="{{ asset('/img/favicon/vipspm-dark-logo.png') }}" sizes="96x96"/>

		<!-- Stylesheet -->
		<link rel="stylesheet" href="{{ asset('/css/vendor/font-awesome/css/font-awesome.min.css') }}"/>
		<link rel="stylesheet" href="{{ asset('/css/vendor/bootstrap.min.css') }}"/>
		<link rel="stylesheet" href="{{ asset('/css/vendor/animate.css') }}"/>
		<link rel="stylesheet" href="{{ asset('/css/vendor/installer.css') }}"/>
		<link rel="stylesheet" href="{{ asset('/css/vendor/select2.min.css') }}"/>
		@yield('style')
		<script>
			// CSRF token
			window.Laravel = <?php
				echo json_encode(
					[
					'csrfToken' => csrf_token(),
					]
				);
				?>
		</script>
	</head>
	<body>
		<div class="master">
			<div class="box">
				<div class="header">
					<h1 class="header__title">@yield('title')</h1>
				</div>
				<ul class="step">
					<li class="step__divider"></li>
					<li class="step__item {{ isActive('LaravelInstaller::sites') }}">
						<i class="step__icon fa fa-users" aria-hidden="true"></i>
					</li>
					<li class="step__divider"></li>
					<li class="step__item {{ isActive('LaravelInstaller::final') }}">
						<i class="step__icon fa fa-server" aria-hidden="true"></i>
					</li>
					<li class="step__divider"></li>
					<li class="step__item {{ isActive('LaravelInstaller::environmentWizard')}}">
						@if(Request::is('install/environment/wizard'))
						<a href="{{ route('LaravelInstaller::environmentWizard') }}">
							<i class="step__icon fa fa-cog" aria-hidden="true"></i>
						</a>
						@else
						<i class="step__icon fa fa-cog" aria-hidden="true"></i>
						@endif
					</li>
					<li class="step__divider"></li>
					<li class="step__item {{ isActive('LaravelInstaller::permissions') }}">
						@if(Request::is('install/permissions') || Request::is('install/environment/wizard'))
						<a href="{{ route('LaravelInstaller::permissions') }}">
							<i class="step__icon fa fa-key" aria-hidden="true"></i>
						</a>
						@else
						<i class="step__icon fa fa-key" aria-hidden="true"></i>
						@endif
					</li>
					<li class="step__divider"></li>
					<li class="step__item {{ isActive('LaravelInstaller::requirements') }}">
						@if(Request::is('install') || Request::is('install/requirements') || Request::is('install/permissions') || Request::is('install/environment/wizard'))
						<a href="{{ route('LaravelInstaller::requirements') }}">
							<i class="step__icon fa fa-list" aria-hidden="true"></i>
						</a>
						@else
						<i class="step__icon fa fa-list" aria-hidden="true"></i>
						@endif
					</li>
					<li class="step__divider"></li>
					<li class="step__item {{ isActive('LaravelInstaller::welcome') }}">
						@if(Request::is('install') || Request::is('install/requirements') || Request::is('install/permissions') || Request::is('install/environment/wizard'))
						<a href="{{ route('LaravelInstaller::welcome') }}">
							<i class="step__icon fa fa-home" aria-hidden="true"></i>
						</a>
						@else
						<i class="step__icon fa fa-home" aria-hidden="true"></i>
						@endif
					</li>
					<li class="step__divider"></li>
				</ul>
				<div class="main">
					@if (session('message'))
					<p class="alert text-center">
						<strong>
							@if(is_array(session('message')))
							{{ session('message')['message'] }}
							@else
							{{ session('message') }}
							@endif
						</strong>
					</p>
					@endif
					@if(session()->has('errors'))
					<div class="alert alert-danger" id="error_alert">
						<button type="button" class="close" id="close_alert" data-dismiss="alert" aria-hidden="true">
							<i class="fa fa-close" aria-hidden="true"></i>
						</button>
						<h4>
							<i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
							{{ trans('installer_messages.forms.errorTitle') }}
						</h4>
						<ul>
							@foreach($errors->all() as $error)
							<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
					@endif
					@yield('container')
				</div>
			</div>
		</div>
		@yield('scripts')

		<!-- Script -->
		<script type="text/javascript" src="{{ asset('/js/vendor/jquery-bundle.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/js/vendor/select2.min.js') }}"></script>
		<script type="text/javascript">
			// Display error messages
			var x = document.getElementById('error_alert');
			var y = document.getElementById('close_alert');
			if (y) {
				y.onclick = function () {
					x.style.display = "none";
				};
			}

			// Load select2
			$(document).ready(function() {
				$('.select2').select2();
			});
		</script>
	</body>
</html>
