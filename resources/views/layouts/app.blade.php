<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=960, user-scalable=yes">
    <meta name="description" content="">
    <meta name="Keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Title</title>
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.modal.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
    @include('partials.spinner')
    @yield('custom_css')
</head>

<body class="p_list">
    <div id="container" class="container">
        <div id="overlay" class="overlay">
            <div id="spinner" class="spinner"></div>
        </div>
        <main class="p_post">
            @include('layouts.sidebar')
            <div class="ctRight">
                @yield('content')
            </div>
        </main>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.modal.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        //ajax setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });
    </script>
    @yield('custom_js')
</body>
@yield('custom_modal')
</html>
