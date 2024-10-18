@extends('layouts.app')
@section('custom_css')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"
        integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            .btn_publish_now, .btn_schedule {
                width: 49%;
            }
            .public-action {
                display: flex;
                justify-content: space-between;
            }
        </style>
@endsection
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
                                    <dd><a href="javascript:void(0);">下書き</a></dd>
                                </dl>
                                <dl>
                                    <dt>公開</dt>
                                    <dd><a href="javascript:void(0);">10月31日 9:00 AM</a></dd>
                                </dl>
                                <dl>
                                    <dt>記事URL</dt>
                                    <dd><a href="javascript:void(0);">/01</a></dd>
                                </dl>
                                <dl>
                                    <dt>投稿者</dt>
                                    <dd><a href="javascript:void(0);">管理人</a></dd>
                                </dl>
                                <p>単語数：580単語<br> 読了時間：約3分</p>
                            </div>
                            <div class="blk03">
                                <dl>
                                    <dt>アイキャッチ画像</dt>
                                    <dd>
                                        <button class="btn_create_img js_modal js_create_img">アイキャッチ画像を生成</button>
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
                                            <textarea id="meta_description">{{ $seo_setting->meta_description ?? '' }}</textarea>
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
    <div id="modal01" class="modal js_modal_image">
        <figure>
            <img class="js_img_render" src="{{ asset('img/dum2.png') }}" alt="">
        </figure>
    </div>
    <div id="scheduleModal" class="modal st3">
        <div class="form">
            <h3 class="ttl tac">
                投稿を予約する
            </h3>
            <dl>
                <dt>
                    公開時間
                </dt>
                <dd>
                    <input type="text" id="scheduleTime" />
                </dd>
            </dl>
            <dl>
                <dt>ステータス</dt>
                <dd>
                    <div class="select">
                        <label>
                            <select name="" id="publicStatus">
                                <option value="">下書き</option>
                                <option value="">公開</option>
                            </select>
                        </label>
                    </div>
                </dd>
            </dl>
            
            <dl class="public-action">
                <button class="btn btn_publish_now">今すぐ公開</button>
                <button class="btn btn_schedule">予約する</button>
            </dl>
        </div>
    </div>
@endsection
@section('custom_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"
        integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
            images_upload_url: "{{ route('post.upload_image') }}",
            file_picker_callback: function(callback, value, meta) {
                if (meta.filetype === 'image') {
                    let url = prompt('画像URLを入力してください');
                    callback(url);
                }
            }
        });

        $(document).ready(function() {
            $('#scheduleTime').datetimepicker({

                ownerDocument: document,
                contentWindow: window,

                value: '',
                rtl: false,

                format: 'Y/m/d H:i',
                formatTime: 'H:i',
                formatDate: 'Y/m/d',

                // new Date(), '1986/12/08', '-1970/01/05','-1970/01/05',
                startDate: false,

                step: 60,
                monthChangeSpinner: true,

                closeOnDateSelect: false,
                closeOnTimeSelect: true,
                closeOnWithoutClick: true,
                closeOnInputClick: true,
                openOnFocus: true,

                timepicker: true,
                datepicker: true,
                weeks: false,

                // use formatTime format (ex. '10:00' for formatTime: 'H:i')
                defaultTime: false,

                // use formatDate format (ex new Date() or '1986/12/08' or '-1970/01/05' or '-1970/01/05')
                defaultDate: false,

                minDate: false,
                maxDate: false,
                minTime: false,
                maxTime: false,
                minDateTime: false,
                maxDateTime: false,

                allowTimes: [],
                opened: false,
                initTime: true,
                inline: false,
                theme: '',
                touchMovedThreshold: 5,

                // callbacks
                onSelectDate: function() {},
                onSelectTime: function() {},
                onChangeMonth: function() {},
                onGetWeekOfYear: function() {},
                onChangeYear: function() {},
                onChangeDateTime: function() {},
                onShow: function() {},
                onClose: function() {},
                onGenerate: function() {},

                withoutCopyright: true,
                inverseButton: false,
                hours12: false,
                next: 'xdsoft_next',
                prev: 'xdsoft_prev',
                dayOfWeekStart: 0,
                parentID: 'body',
                timeHeightInTimePicker: 25,
                timepickerbar: true,
                todayButton: true,
                prevButton: true,
                nextButton: true,
                defaultSelect: true,

                scrollMonth: true,
                scrollTime: true,
                scrollInput: true,

                lazyInit: false,
                mask: false,
                validateOnBlur: true,
                allowBlank: true,
                yearStart: 1950,
                yearEnd: 2050,
                monthStart: 0,
                monthEnd: 11,
                style: '',
                id: '',
                fixed: false,
                roundTime: 'round', // ceil, floor
                className: '',
                weekends: [],
                highlightedDates: [],
                highlightedPeriods: [],
                allowDates: [],
                allowDateRe: null,
                disabledDates: [],
                disabledWeekDays: [],
                yearOffset: 0,
                beforeShowDay: null,

                enterLikeTab: true,
                showApplyButton: false,
                insideParent: false,

            });
        });
    </script>
    <script>
        var URL_UPDATE_BLOG_POST = "{{ route('post.ajax.updateBlogPost') }}";
        var URL_RENDER_IMAGE_BLOG_POST = "{{ route('post.ajax.ajaxRenderImage') }}";

        $(document).ready(function() {
            $('.btn_preview').click(function() {
                var content = tinymce.get('editor').getContent();
                var meta_description = $('#meta_description').val();
                var status = "{{ \App\Models\BlogPost::STATUS_DRAFT }}";
                var data = {
                    content: content,
                    meta_description: meta_description,
                    status: status,
                    post_id: '{{ $post->id }}',
                    source_from: 'seo_setting'
                };
                updateBlogPost(data);
            });

            $('.btn_scheduled').click(function() {
                $('#scheduleModal').modal('show');
            });

            //scheduleTime get value on change
            $('#scheduleTime').on('change', function() {
                var scheduleTime = $(this).val();
                console.log(scheduleTime);
            });
            
            $('.js_create_img').click(function() {
                var content = tinymce.get('editor').getContent();
                var data = {
                    post_id: '{{ $post->id }}'
                };

                showSpinner();
                renderImgBlogPost(data, content);
            });

        });

        function updateBlogPost(data) {
            $.ajax({
                url: URL_UPDATE_BLOG_POST,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('body').append('<div class="loader-wrapper"><div class="loader"></div></div>');
                },
                data: data,
                success: function(response) {
                    if (response.status == 'success') {
                        location.reload();
                    }
                },
                complete: function() {
                    $('.loader-wrapper').remove();
                }
            });
        }

        function renderImgBlogPost(data) {
            $.ajax({
                url: URL_RENDER_IMAGE_BLOG_POST,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                success: function(response) {
                    if (response.status == 'success') {

                        var file_url = response.file_url;
                        // location.reload();
                        $('.js_modal_image').modal('show');
                        $('.js_img_render').attr('src', response.file_url);

                        var editor = tinymce.get('editor');

                        // Append the image at the current cursor position
                        editor.insertContent('<img width="1024px" height="1024px" src="' + file_url + '" alt="Image" />');
                    }

                    hideSpinner();
                }
            });
        }
    </script>
@endsection
