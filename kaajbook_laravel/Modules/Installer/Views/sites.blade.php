@extends('theme::layouts.master')

@section('template_title')
    {{ trans('installer_messages.environment.wizard.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-users fa-fw" aria-hidden="true"></i>
    Configure Site User
@endsection

@section('container')
    <div class="tabs tabs-full">

        <form method="post" action="{{ route('LaravelInstaller::saveSites') }}" class="tabs-wrap" autocomplete="off">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group {{ $errors->has('username') ? ' has-error ' : '' }}">
                <label for="username">Username<span class="text-danger">&nbsp;*</span></label>
                <input type="text" name="username" class="form-control" id="username" placeholder="Enter Username" value="{{ @$input['username'] }}" />
                @if ($errors->has('username'))
                    <span class="error-block">
                        <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                        {{ $errors->first('username') }}
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('firstname') ? ' has-error ' : '' }}">
                <label for="firstname">First Name<span class="text-danger">&nbsp;*</span></label>
                <input type="text" name="firstname" class="form-control" id="firstname" placeholder="Enter First Name" value="{{ @$input['firstname'] }}" />
                @if ($errors->has('firstname'))
                    <span class="error-block">
                        <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                        {{ $errors->first('firstname') }}
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('lastname') ? ' has-error ' : '' }}">
                <label for="lastname">Last Name<span class="text-danger">&nbsp;*</span></label>
                <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Enter First Name" value="{{ @$input['lastname'] }}" />
                @if ($errors->has('lastname'))
                    <span class="error-block">
                        <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                        {{ $errors->first('lastname') }}
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('currency') ? ' has-error ' : '' }}">
                <label for="currency">Currency<span class="text-danger">&nbsp;*</span></label>
                <select name="currency" id="currency" class="select2">
                    <option value="">-- Select Currency --</option>
                    @foreach($currencies as $key => $value)
                        @if(@$input['currency'] == $value->id )
                        <option value="{{$value->id}}" selected>{{ $value->name }} - {{ $value->symbol }}</option>
                        @else
                        <option value="{{$value->id}}">{{ $value->name }} - {{ $value->symbol }}</option>
                        @endif
                    @endforeach
                </select>
                @if ($errors->has('currency'))
                    <span class="error-block">
                        <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                        {{ $errors->first('currency') }}
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('email') ? ' has-error ' : '' }}">
                <label for="email">Email<span class="text-danger">&nbsp;*</span></label>
                <input type="text" name="email" class="form-control" id="email" placeholder="Enter Email" value="{{ @$input['email'] }}" />
                @if ($errors->has('email'))
                    <span class="error-block">
                        <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                        {{ $errors->first('email') }}
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('password') ? ' has-error ' : '' }}">
                <label for="password">Password<span class="text-danger">&nbsp;*</span></label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" value="{{ @$input['password'] }}" />
                @if ($errors->has('password'))
                    <span class="error-block">
                        <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                        {{ $errors->first('password') }}
                    </span>
                @endif
            </div>

            <div class="buttons">
                <button class="button">
                    Save and Finish
                    <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                </button>
            </div>
        </form>

    </div>
@endsection
