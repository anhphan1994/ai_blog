@extends('auth')

@section('title')
    Login
@endsection

@section('content')
    <div class="box">
        <div class="form">
            <form action="{{ route('auth.forgot') }}" method="POST">
                @csrf
                <a class="f_logo" href="./"><img src="{{ asset('img/logo.png') }}" alt=""></a>
                <p class="l_txt">
                    <span>パスワードをお忘れですか</span>
                    <small>すでにアカウントをお持ちの方は<a href="{{ route('login') }}">ログイン</a></small>
                </p>
                <input type="email" name="email" value="{{ old('email') ?? '' }}" placeholder="メールアドレス*">
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <button class="btn">提出する</button>
            </form>
        </div>
    </div>
@endsection
