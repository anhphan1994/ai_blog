@extends('layouts.app')
@section('content')
    <div class="post_ctn">
        <div class="post_top">
            <ul>
                <li>詳細設定</li>
                <li>生成結果</li>
                <li class="active">投稿設定</li>
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
                <div class="post_bot_r noPd">
                    <div class="ck_block">
                        <div class="ck_block_l">
                            <div class="bl_head">
                                <h2>
                                    <span>{{ $post->title }}</span>
                                    <small>パーマリンク：<a target="_blank"
                                            href="{{ $post->url }}">{{ $post->url }}</a></small>
                                </h2>
                                @php
                                    $content = $post->content;
                                    $content = str_replace("\n", '<br>', $content);
                                @endphp
                                <textarea id="editor" cols="30" rows="10" oninput="auto_grow(this)">{{ $content }}</textarea>
                            </div>
                        </div>
                        <div class="ck_block_r">
                            <div class="blk01">
                                <a class="btn_preview" href="javascript:void(0);">プレビュー</a>
                                <button class="btn_scheduled">予約投稿</button>
                            </div>
                            <div class="blk02">
                                <dl>
                                    <dt>ステータス</dt>
                                    <dd><a href="">下書き</a></dd>
                                </dl>
                                <dl>
                                    <dt>公開</dt>
                                    <dd><a href="">10月31日 9:00 AM</a></dd>
                                </dl>
                                <dl>
                                    <dt>記事URL</dt>
                                    <dd><a href="">/01</a></dd>
                                </dl>
                                <dl>
                                    <dt>投稿者</dt>
                                    <dd><a href="">管理人</a></dd>
                                </dl>
                                <p>単語数：580単語<br> 読了時間：約3分</p>
                            </div>
                            <div class="blk03">
                                <dl>
                                    <dt>アイキャッチ画像</dt>
                                    <dd>
                                        <button class="btn_create_img js_modal" data-modal="#modal01">アイキャッチ画像を生成</button>
                                        <p>50ポイントを使用</p>
                                    </dd>
                                </dl>
                            </div>
                            <div class="blk04">
                                <dl>
                                    <dt>カテゴリー</dt>
                                    <dd>
                                        <ul class="list_checkbox">
                                            <li>
                                                <label class="checkbox">
                                                    <input type="checkbox" checked>
                                                    <span>旅行ガイド</span>
                                                </label>
                                            </li>
                                            <li>
                                                <label class="checkbox">
                                                    <input type="checkbox">
                                                    <span>ライフスタイル</span>
                                                </label>
                                            </li>
                                            <li>
                                                <label class="checkbox">
                                                    <input type="checkbox">
                                                    <span>カルチャー</span>
                                                </label>
                                            </li>
                                        </ul>
                                        <button class="btn_add_check">＋新規カテゴリーを追加</button>
                                    </dd>
                                </dl>
                            </div>
                            <div class="blk05">
                                <dl>
                                    <dt>メタディスクリプション</dt>
                                    <dd>
                                        <div class="form">
                                            <textarea>東京の隠れた映画ロケ地を探検しよう！「君の名は。」や「るろうに剣心」など人気作品のシーンを再現できるスポットを紹介。映画ファン必見の東京観光ガイド。インスタ映えする写真スポットも満載！</textarea>
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_modal')
    <div id="modal01" class="modal">
        <figure>
            <img src="{{ asset('img/dum2.png') }}" alt="">
        </figure>
    </div>
@endsection
@section('custom_js')
    <script src="https://cdn.tiny.cloud/1/tmi2sf2d52osils6xs1lfzzrg0pv4yommkrx0pmu9yak23v6/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#editor',
            menubar: false,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            toolbar_mode: 'wrap',
            language_url: "{{ asset('js/ja.js') }}",
            language: 'ja',
            resize: false,
            height: '90%',
            images_upload_url: "{{route('post.upload_image')}}",
            file_picker_callback: function(callback, value, meta) {
                if (meta.filetype === 'image') {
                    let url = prompt('画像URLを入力してください');
                    callback(url);
                }
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            //btn_preview click, save post then preview
            $('.btn_preview').click(function() {
                var content = tinymce.get('editor').getContent();
                var url = "{{ route('post.ajax.updateBlogPost') }}";
                var data = {
                    content: content,
                    status: "{{ \App\Models\BlogPost::STATUS_DRAFT }}",
                    post_id: '{{ $post->id }}'
                };
                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: data,
                    success: function(response) {
                        if (response.status == 'success') {
                            location.reload();
                        }
                    }
                });
            });
        });
    </script>
@endsection
