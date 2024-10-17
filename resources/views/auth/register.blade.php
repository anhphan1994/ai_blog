@extends('auth')

@section('title')
    Login
@endsection

@section('content')
    <div class="box">
        <div class="form">
            <form action="{{ route('auth.register') }}" method="POST">
                @csrf
                <a class="f_logo" href="./"><img src="{{ asset('img/logo.png') }}" alt=""></a>
                <p class="l_txt">
                    <span>新規登録</span>
                    <small>すでにアカウントをお持ちの方は<a href="{{ route('login') }}">ログイン</a></small>
                </p>
                <input type="email" name="email" placeholder="メールアドレス*">
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
                <input type="password" name="password_confirm" placeholder="パスワード（確認用）*">
                @if ($errors->has('password_confirm'))
                    <span class="text-danger">{{ $errors->first('password_confirm') }}</span>
                @endif
                <button class="btn">新規登録</button>
            </form>
        </div>
    </div>
@endsection
