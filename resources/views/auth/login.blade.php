@extends('auth')

@section('title')
    Login
@endsection

@section('content')
    <div class="box">
        <div class="form">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <a class="f_logo" href="./"><img src="{{ asset('img/logo.png') }}" alt=""></a>
                <p class="l_txt">
                    <span>ログイン</span>
                    <small>アカウントをお持ちでない方は<a href="{{ route('register') }}">新規登録</a></small>
                </p>
                <input type="email" name="email" value="{{ old('email') ?? '' }}" placeholder="メールアドレス*">
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <div class="input_group">
                    <input type="password" name="password" placeholder="パスワード*">
                    <span class="btn_toogle_pass"></span>
                </div>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                <p class="f_txt"><a href="{{ route('auth.showForgot') }}">パスワードをお忘れですか？</a></p>
                <button class="btn st2">ログイン</button>
            </form>
        </div>
    </div>
@endsection
