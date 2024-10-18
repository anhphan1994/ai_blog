<aside class="aside" id="aside">
    <div class="aside_top">
        <a class="aside_logo" href="{{route('post.dashboard')}}"><img src="{{ asset('img/logo.png') }}" alt=""></a>
        <button class="btn_action" id="btn_action"></button>
    </div>
    <div class="aside_blog">
        <ul>
            <li>
                <a class="active" href="">
                    <span>マイキャンプブログA</span>
                    <small>mycamp-a.blog</small>
                </a>
            </li>
            <li>
                <a href="">
                    <span>マイキャンプブログB</span>
                    <small>mycamp-b.blog</small>
                </a>
            </li>
            <li>
                <a href="">
                    <span>マイキャンプブログC</span>
                    <small>mycamp-c.blog</small>
                </a>
            </li>
        </ul>
        <button class="btn_add">＋<span>新しくブログを連携する</span></button>
    </div>
    <div class="aside_user">
        <div class="aside_user_info">
            <figure>
                <img src="{{ asset('img/user.png') }}" alt="">
            </figure>
            <span>User Name</span>
        </div>
        <a class="btn_setting" href="{{ route('auth.logout') }}"></a>
    </div>
</aside>
