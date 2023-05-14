@extends('theme::layouts.master')

@section('template_title')
	{{ trans('installer_messages.permissions.templateTitle') }}
@endsection

@section('title')
	<i class="fa fa-key fa-fw" aria-hidden="true"></i>
	{{ trans('installer_messages.permissions.title') }}
@endsection

@section('container')

	<ul class="list">
		@foreach($permissions['permissions'] as $permission)
		<li class="list__item list__item--permissions {{ $permission['isSet'] ? 'success' : 'error' }}">
			{{ $permission['folder'] }}
			<span>
				<i class="fa fa-fw fa-{{ $permission['isSet'] ? 'check-circle-o' : 'exclamation-circle' }}"></i>
				{{ $permission['permission'] }}
			</span>
		</li>
		@endforeach
	</ul>

	@if ( ! isset($permissions['errors']))
		<div class="buttons">
			<a class="button button--default" href="{{ route('LaravelInstaller::requirements') }}">
				<i class="fa fa-angle-left fa-fw" aria-hidden="true"></i>
				Back
			</a>
			<a href="{{ route('LaravelInstaller::environmentWizard') }}" class="button">
				{{ trans('installer_messages.permissions.next') }}
				<i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
			</a>
		</div>
	@else
		<div class="form-group has-error">
			<span class="error-block">Folders have not required permissions.<br></span>
			<span class="error-block">You have to set fully permission(read, write, execute) to above folder(via command or manually).<br></span>
		</div>
		<div class="buttons">
			<a class="button button--default" href="{{ route('LaravelInstaller::permissions') }}">
				<i class="fa fa-refresh fa-fw" aria-hidden="true"></i>
				Refresh
			</a>
		</div>
	@endif

@endsection
