@extends('layouts.app')
@section('content')
    <div class="post_ctn">
        <div class="post_top">
            <ul>
                <li class="active">詳細設定</li>
                <li>生成結果</li>
                <li>投稿設定</li>
            </ul>
        </div>
        <div class="post_bot">
            <div class=" pbm">
                <div class="post_bot_l">
                    <h3 class="ttl">記事生成</h3>
                    <div class="form formScroll">
                        <input type="hidden" id="post_id" value="{{ $post->id }}">
                        <dl>
                            <dt>トピック（書きたいこと） <span>*</span></dt>
                            <dd>
                                <textarea placeholder="人気の東京の映画ロケ地と、地元の人しか知らないスポットを組み合わせた東京ガイドを作りたい" id="short_description"></textarea>
                                <p class="f_txt tar">0/5000</p>
                            </dd>
                        </dl>
                        <dl>
                            <dt>含めたいキーワード</dt>
                            <dd>
                                <input type="text" placeholder="東京,観光,穴場" id="keywords">
                            </dd>
                        </dl>
                        <dl>
                            <dt>トーン <span>*</span></dt>
                            <dd>
                                <div class="select">
                                    <label>
                                        @php
                                            $post_style = config('constant.post_style');
                                        @endphp
                                        <select id="post_style">
                                            @foreach ($post_style as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                            </dd>
                        </dl>
                        {{-- <dl>
                            <dt>文字数 <span>*</span></dt>
                            <dd>
                                <div class="select">
                                    <label>
                                        @php
                                            $max_characters = config('constant.max_characters');
                                        @endphp
                                        <select id="max_characters">
                                            @foreach ($max_characters as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                            </dd>
                        </dl> --}}
                        <dl>
                            <dt>セクションの数(目次)</dt>
                            <dd>
                                <div class="select">
                                    <label>
                                        @php
                                            $section_number = config('constant.section_number');
                                        @endphp
                                        <select id="section_number">
                                            @foreach ($section_number as $value)
                                                <option value="{{ $value }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                            </dd>
                        </dl>
                        <button class="btn js_modal" data-modal="#modal01" id="generateButton">生成</button>
                        <p class="f_txt tac"><span>500ポイント</span>を使用します。</p>
                    </div>
                </div>
                <div class="post_bot_r">
                    <div class="b_results">
                        <img src="{{ asset('img/ic_stars.svg') }}" alt="">
                        <p>ここに生成結果が表示されます。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_modal')
    <div class="jquery-modal current">
        <div id="modal01" class="modal st2" style="display: none;">
            <h3 class="ttl">
                <img src="{{ asset('img/ic_pen.svg') }}" alt="">
                <span>記事生成を開始しました</span>
            </h3>
            <p class="m_txt">完了までしばらくお待ちください。記事作成中もダッシュボードへ戻ったり、他の作業を行うことができます。</p>
            <a href="{{route('post.dashboard')}}" class="m_btn">ダッシュボードへ戻る</a>
        </div>
    </div>
@endsection
@section('custom_js')
    <script>
        $(document).ready(function() {
            URL_GENERATE_POST = "{{ route('post.ajax.generate') }}";
            URL_CHECK_STATUS = "{{ route('post.ajax.check_status') }}";

            $('#generateButton').click(function() {
                var short_description = $('#short_description').val();
                var post_style = $('#post_style').val();
                var keywords = $('#keywords').val();
                var section_number = $('#section_number').val();
                var post_id = $('#post_id').val();
                generatePost(post_id, short_description, post_style, keywords, section_number);
            });
        });

        function generatePost(post_id, short_description, post_style, keywords, section_number) {
            $.ajax({
                type: 'POST',
                url: URL_GENERATE_POST,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    post_id: post_id,
                    short_description: short_description,
                    post_style: post_style,
                    keywords: keywords,
                    section_number: section_number
                },
                success: function(data) {
                    if (data.status == 'success') {
                        $('#modal01').show();
                    }
                    setInterval(function() {
                        checkStatus();
                    }, 10000);
                }
            });
        }

        function checkStatus() {
            var post_id = $('#post_id').val();
            $.ajax({
                type: 'GET',
                url: URL_CHECK_STATUS,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    post_id: post_id
                },
                success: function(data) {
                    if (data.status == "{{ \App\Models\BlogPost::STATUS_GENERATED }}") {
                        location.href = "{{ route('post.result', $post->id) }}";
                    }
                }
            });
        }
    </script>
@endsection
