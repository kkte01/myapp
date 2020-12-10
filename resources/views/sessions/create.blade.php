@extends('layouts.app')

@section('content')
  <form action="{{ route('sessions.store') }}" method="POST" role="form" class="form__auth">
    {!! csrf_field() !!}

    @if ($return = request('return'))
      <input type="hidden" name="return" value="{{ $return }}">
    @endif

    <div class="page-header">
      <h4>
        {{ trans('auth.sessions.title') }}
      </h4>
      <p class="text-muted">
        {{ trans('auth.sessions.description') }}
      </p>
    </div>

    <div class="form-group">
      <a class="btn btn-default btn-lg btn-block" href="{{ route('social.login', ['github']) }}">
        <strong>
          <i class="fa fa-github"></i>
          <!-- {{ trans('auth.sessions.login_with_github') }} -->
          깃 허브 로그인해버리깅
        </strong>
      </a>
    </div>

    <div class="login-or">
      <hr class="hr-or">
      <span class="span-or">or</span>
    </div>

    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
      <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" autofocus/>
      {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
      <input type="password" name="password" class="form-control" placeholder="Password">
      {!! $errors->first('password', '<span class="form-error">:message</span>')!!}
    </div>

    <div class="form-group">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="remember" value="{{ old('remember', 1) }}" checked>
          {{ trans('auth.sessions.remember') }}
          <span class="text-danger">
            {{ trans('auth.sessions.remember_help') }}
          </span>
        </label>
      </div>
    </div>

    <div class="form-group">
      <button class="btn btn-primary btn-lg btn-block" type="submit">
        login
      </button>
    </div>

    <div>
      <a class="text-center" href="{{ route('users.create') }}">
        회원가입하기
      </a>
      <a class="text-center" href="{{ route('remind.create') }}">
        {!! trans('auth.sessions.ask_forgot', ['url' => route('remind.create')]) !!}
      </a>
      <a class="text-center">
        <small class="help-block">
          {{  trans('auth.sessions.caveat_for_social') }}
        </small>
      </a>
    </div>
  </form>