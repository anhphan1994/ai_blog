<aside class="aside" id="aside">
    <div class="aside_top">
        <a class="aside_logo" href="{{ route('post.dashboard') }}"><img src="{{ asset('img/logo.png') }}"
                alt=""></a>
        <button class="btn_action" id="btn_action"></button>
    </div>
    <div class="aside_blog">
        <ul>
            @php
                $platform_accounts = getPlatformAccountName();
            @endphp
            @foreach ($platform_accounts as $item)
                <li>
                    <a @if (!empty(request()->platform_id) && request()->platform_id == $item->id) class="active" @endif
                        href="{{ route('post.dashboard', ['platform_id' => $item->id]) }}">
                        <span>{{ $item->username }}</span>
                        <small>{{ $item->url }}</small>
                    </a>
                </li>
            @endforeach
        </ul>
        <button class="btn_add st2 js_modal2" data-modal="#connectWPModal">＋<span>新しくブログを連携する</span></button>

    </div>
    <div class="aside_user">
        <div class="aside_user_info">
            <figure>
                <img src="{{ asset('img/user.png') }}" alt="">
            </figure>
            <span
                class="user_name">{{ !empty(Auth::user()->user_name) ? Auth::user()->user_name : Auth::user()->email }}</span>
        </div>
        <a class="btn_setting" id="toggleButton"></a>
        <div id="menu" class="hidden">
            <a id="logoutButton" href="{{ route('auth.logout') }}">Logout</a>
        </div>
    </div>
</aside>
