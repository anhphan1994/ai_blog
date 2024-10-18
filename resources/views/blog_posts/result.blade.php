@extends('layouts.app')
@section('content')
    <div class="post_ctn">
        <div class="post_top">
            <ul>
                <li>詳細設定</li>
                <li class="active">生成結果</li>
                <li>投稿設定</li>
            </ul>
        </div>
        <div class="post_bot">
            <div class=" pbm">
                <div class="post_bot_l">
                    <a href="" class="btn_back">前に戻る</a>
                    <p class="title">見出し</p>
                    @php
                        $outline = $post->outline;
                        $outline = str_replace("\n", '<br>', $outline);
                    @endphp
                    <h2>{!! $outline !!}</h2>
                </div>
                <div class="post_bot_r noBot">
                    <div class="p_edit">
                        <div class="e_it">
                            <h3>タイトル</h3>
                            <div class="e_action">
                                <p class="e_txt">記事のタイトルを入力します。</p>
                                <div class="e_action_r">
                                    <span>50ポイントを使用</span>
                                    <button id="generate_title_btn">タイトルを再生成</button>
                                </div>
                            </div>
                            <div class="box_gr">
                                <textarea oninput="auto_grow(this)" class="textareaOnload" id="title_value">{{ $post->title }}</textarea>
                            </div>
                        </div>
                        <div class="e_it">
                            <h3>セクション（見出し）</h3>
                            <div class="e_action">
                                <p class="e_txt">記事内のセクションを入力します。</p>
                                <div class="e_action_r">
                                    <span>50ポイントを使用</span>
                                    <button id="generate_outline_btn">セクションを再生成</button>
                                </div>
                            </div>
                            <div class="box_gr">
                                <textarea oninput="auto_grow(this)" class="textareaOnload" id="outline_value">{{ $post->outline }}</textarea>
                            </div>
                        </div>
                        <div class="e_it">
                            <h3>本文</h3>
                            <p class="e_txt full">本文のテキストを入力します。見出しの設定や画像挿入、文字装飾などは「投稿設定」にて編集可能です。<br>
                                下記に表示されている「トレンドワード」をクリックすると、そのキーワードを交えた最適な文章が再生成されます。</p>
                            <div class="e_action">
                                <div class="e_action_l">
                                    <div class="tags">
                                        @foreach($tags as $tag)
                                            <span>{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="e_action_r">
                                    <span>50ポイントを使用</span>
                                    <button id="generate_content_btn">本文を再生成</button>
                                </div>
                            </div>
                            <div class="box_gr">
                                <textarea oninput="auto_grow(this)" class="textareaOnload" id="content_value">{{ $post->content }}</textarea>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:void(0);" class="btn st2" id="updateBlogPost">保存して投稿設定へ進む</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_modal')
@endsection
@section('custom_js')
    <script>
        var URL_GENERATE_TITLE = "{{ route('post.ajax.generateBlogTitle') }}";
        var URL_GENERATE_OUTLINE = "{{ route('post.ajax.generateBlogOutline') }}";
        var URL_GENERATE_CONTENT = "{{ route('post.ajax.generateBlogContent') }}";
        var URL_UPDATE_BLOG_POST = "{{ route('post.ajax.updateBlogPost') }}";
        var URL_UPDATE_TAG = "{{ route('post.ajax.updateTag') }}";

        $(document).ready(function() {
            $('#generate_title_btn').click(function() {
                generateTitle();
            });

            $('#generate_outline_btn').click(function() {
                generateOutline();
            });

            $('#generate_content_btn').click(function() {
                generateContent();
            });

            $('#updateBlogPost').click(function() {
                var title = $('#title_value').val();
                var outline = $('#outline_value').val();
                var content = $('#content_value').val();
                updateBlogPost(title, outline, content);
            });

            $('.tags').find('span').click(function() {
                var tag = $(this).text();
                // saveTag(tag);
            });
        });

        function generateTitle() {
            $.ajax({
                url: URL_GENERATE_TITLE,
                type: 'POST',
                data: {
                    post_id: '{{ $post->id }}',
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('body').append('<div class="loader-wrapper"><div class="loader"></div></div>');
                    $('#title_value').val('生成中...');
                },
                success: function(data) {
                    $('#title_value').val(data.title);
                },
                complete: function() {
                    $('.loader-wrapper').remove();
                }
            });
        }

        function generateOutline() {
            $.ajax({
                url: URL_GENERATE_OUTLINE,
                type: 'POST',
                data: {
                    post_id: '{{ $post->id }}',
                    title: $('#title_value').val(),
                },
                beforeSend: function() {
                    $('body').append('<div class="loader-wrapper"><div class="loader"></div></div>');
                    $('#outline_value').val('生成中...');
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#outline_value').val(data.outline);
                },
                complete: function() {
                    $('.loader-wrapper').remove();
                }
            });
        }

        function generateContent() {

            var tags = $('.tags').find('span');
            var tagsList = [];
            tags.each(function() {
                if ($(this).hasClass('active')) {
                    tagsList.push($(this).text());
                }
            });

            $.ajax({
                url: URL_GENERATE_CONTENT,
                type: 'POST',
                data: {
                    post_id: '{{ $post->id }}',
                    title: $('#title_value').val(),
                    outline: $('#outline_value').val(),
                    tags: tagsList,
                },
                beforeSend: function() {
                    $('body').append('<div class="loader-wrapper"><div class="loader"></div></div>');
                    $('#content_value').val('生成中...');
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#content_value').val(data.content);
                },
                complete: function() {
                    $('.loader-wrapper').remove();
                }
            });
        }

        function updateBlogPost(title, outline, content) {

            var tags = $('.tags').find('span');
            var tagsList = [];
            tags.each(function() {
                if ($(this).hasClass('active')) {
                    tagsList.push($(this).text());
                }
            });

            $.ajax({
                url: URL_UPDATE_BLOG_POST,
                type: 'POST',
                data: {
                    post_id: '{{ $post->id }}',
                    title: title,
                    outline: outline,
                    content: content,
                    tags: tagsList,
                },
                beforeSend: function() {
                    $('body').append('<div class="loader-wrapper"><div class="loader"></div></div>');
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 'success') {
                        window.location.href = '{{ route('post.postSetting', $post->id) }}';
                    }
                },
                complete: function() {
                    $('.loader-wrapper').remove();
                }
            });
        }

        function saveTag(tag) {
            var post_id = '{{ $post->id }}';
            $.ajax({
                url: URL_UPDATE_TAG,
                type: 'POST',
                data: {
                    post_id: post_id,
                    tag: tag,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 'success') {
                        if ($(this).hasClass('active')) {
                            $(this).removeClass('active');
                        } else {
                            $(this).addClass('active');
                        }
                    }
                }
            });
        }
    </script>
@endsection
