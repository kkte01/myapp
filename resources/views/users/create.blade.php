@extends('layouts.app')
@section('content')
    <form action="{{ route('users.store') }}" method="POST" class="form__auth">
        {!! csrf_field() !!}
        <div class="form-group {{ $errors->has('name') ? 'has error' : '' }}">
            <input type="text" name="name" id="name" class="form-control" placeholder="이름" value="{{ old('name') }}" autofocus>
            {!! $errors->first('name', '<span class="form-error">:message</span>') !!}
        </div>
        <div class="form-group {{ $errors->has('name') ? 'has error' : '' }}">
            <input type="text" name="email" id="email" class="form-control" placeholder="이메일" value="{{ old('email') }}" autofocus>
            {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
        </div>
        <div class="form-group {{ $errors->has('name') ? 'has error' : '' }}">
            <input type="password" name="password" id="password" class="form-control" placeholder="비밀번호" value="{{ old('password') }}" autofocus>
            {!! $errors->first('password', '<span class="form-error">:message</span>') !!}
        </div>
        <div class="form-group {{ $errors->has('name') ? 'has error' : '' }}">
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="비밀번호확인" value="" autofocus>
            {!! $errors->first('password_confirmation', '<span class="form-error">:message</span>') !!}
        </div>

        <div class="form-group">
            <button class="btn btn-primary btn-lg btn-block" type="submit">가입하기</button>
        </div>
    </form>
@endsection