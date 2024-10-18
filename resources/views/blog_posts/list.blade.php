@extends('layouts.app')
@section('content')
    <div class="list_ctn">
        <div class="row">
            <div class="list_head">
                <p>
                    <span>マイキャンプブログA</span>
                    <a target="_blank" href="https://mycamp-a.blog">https://mycamp-a.blog</a>
                </p>
                <div class="list_head_r">
                    <button class="btn_create_art">記事を作成</button>
                    <button class="btn_st"></button>
                </div>
            </div>
            <div class="list_bd">
                <div class="list_bd_head">
                    <div class="list_bd_head_l">
                        <span><span id="total_post">8</span>記事</span>
                    </div>
                    <div class="sl_gr">
                        <div class="select selectEvent" id="status_filter"></div>
                        <div class="select selectEvent" id="period_filter"></div>
                    </div>
                </div>
                <div class="list_bd_bd" id="blog_posts_list">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_modal')
    <div class="jquery-modal current">
        <div id="modal01" class="modal st4" style="display: none;">
            <div class="md_art_ctn">
                <h2>記事内容</h2>
                <div class="dlist">
                    <dl>
                        <dt>タイトル：</dt>
                        <dd class="meta_title">キャンピングカー買取業者の失敗しない選び方</dd>
                    </dl>
                    <dl>
                        <dt>メタディスクリプション：</dt>
                        <dd class="meta_description">キャンピングカーの買取業者選びで失敗しないコツを解説。信頼性、査定額、サービス内容など、重要なポイントを詳しく紹介。安心して高額売却するための業者選定方法を学べます。</dd>
                    </dl>
                    <dl>
                        <dt>キーワード：</dt>
                        <dd class="meta_keywords">キャンピングカー買取, 買取業者選び, 高額査定, 信頼性, 口コミ評価, 無料査定, 出張買取, 即日現金化, 買取相場, 売却のコツ</dd>
                    </dl>
                    <dl>
                        <dt>記事URL：</dt>
                        <dd class="url"><a target="_blank" href="https:/mycamp.blog/07">https:/mycamp.blog/07</a></dd>
                    </dl>
                    <dl>
                        <dt>参照URL：</dt>
                        <dd class="related-url">
                            <div class="box_bd">
                                <p><a target="_blank"
                                        href="https://www.camping-car-guide.jp/articles/how-to-choose-buyer">https://www.camping-car-guide.jp/articles/how-to-choose-buyer</a>
                                </p>
                                <p><a target="_blank"
                                        href="https://auto-kaitori.com/campingcar/tips/selecting-reliable-company">https://auto-kaitori.com/campingcar/tips/selecting-reliable-company</a>
                                </p>
                                <p><a target="_blank"
                                        href="https://camper-life.net/sale/success-and-failure-stories">https://camper-life.net/sale/success-and-failure-stories</a>
                                </p>
                                <p><a target="_blank"
                                        href="https://rv-navi.com/market-price/camper-van-models">https://rv-navi.com/market-price/camper-van-models</a>
                                </p>
                                <p><a target="_blank"
                                        href="https://carstory.jp/campingcar/buyback-faq">https://carstory.jp/campingcar/buyback-faq</a>
                                </p>
                            </div>
                        </dd>
                    </dl>
                    <dl>
                        <dt></dt>
                        <dd></dd>
                    </dl>
                </div>
            </div>
            <a href="javascript:void(0);" rel="modal:close" class="close-modal">Close</a>
        </div>
    </div>
@endsection
@section('custom_js')
    <script>
        var URL_GET_BLOG_POSTS = "{{ route('post.ajax.list') }}";
        var URL_GET_STATUS_FILTER = "{{ route('post.ajax.status') }}";
        var URL_GET_PERIOD_FILTER = "{{ route('post.ajax.period') }}";
        var URL_SHOW_POST = "{{ route('post.edit', '') }}";
        var URL_DUPPLICATE_POST = "{{ route('post.duplicate', '') }}";
        var URL_DELETE_POST = "{{ route('post.ajax.delete', '') }}";
        var URL_DELETE_MULTI_POSTS = "{{ route('post.ajax.delete.multi') }}";
        var URL_PREVIEW_POST = "{{ route('post.ajax.preview') }}";
        var URL_CREATE_POST = "{{ route('post.create') }}";

        var status, period;

        $(function() {
            status = 'all';
            period = 'all';

            getBlogPosts();
            getStatusFilter();
            getPeriodFilter();

            $(document).on('click', '#status_filter ul li', function() {
                var statusText = $(this).text();
                $('#status_filter span').text(statusText);
                status = $(this).data('status_code');
                getBlogPosts();
            });

            $(document).on('click', '#period_filter ul li', function() {
                var periodText = $(this).text();
                $('#period_filter span').text(periodText);
                period = $(this).data('period');
                getBlogPosts();
            });

            $(document).on('click', '.action li.show', function() {
                var id = $(this).data('id');
                window.location.href = URL_SHOW_POST + '/' + id;
            });

            $(document).on('click', '.action li.duplicate', function() {
                var id = $(this).data('id');
                window.location.href = URL_DUPPLICATE_POST + '/' + id;
            });

            $(document).on('click', '.action li.del', function() {
                var id = $(this).data('id');
                if (confirm('Are you sure you want to delete this post?')) {
                    $.ajax({
                        url: URL_DELETE_POST + '/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            getBlogPosts();
                        }
                    });
                }
            });

            $(document).on('click', '.btn_eye.preview', function() {
                var id = $(this).data('id');
                previewBlogPost(id);
            });

            // .btn_del delete multi posts
            $(document).on('click', '.btn_del', function() {
                var ids = [];

                $('.chk-post:checked').each(function() {
                    ids.push($(this).data('id'));
                });

                if (ids.length == 0) {
                    alert('Please select at least one post to delete.');
                    return;
                }
                if (confirm('Are you sure you want to delete selected posts?')) {
                    deleteMultiPosts(ids);
                }
            });

            $(document).on('click', '.btn_create_art', function() {
                window.location.href = URL_CREATE_POST;
            });
        });

        function getBlogPosts() {
            $.ajax({
                url: URL_GET_BLOG_POSTS,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('body').append('<div class="loader-wrapper"><div class="loader"></div></div>');
                },
                data: {
                    status: status ?? 'all',
                    period: period ?? 'all'
                },
                success: function(response) {
                    $('#blog_posts_list').html(response.html);
                    $('#total_post').text(response.total_post);
                },
                complete: function() {
                    $('.loader-wrapper').remove();
                }
            });
        }

        function getStatusFilter() {
            $.ajax({
                url: URL_GET_STATUS_FILTER,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#status_filter').html(response.html);
                }
            });
        }

        function getPeriodFilter() {
            $.ajax({
                url: URL_GET_PERIOD_FILTER,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#period_filter').html(response.html);
                }
            });
        }

        function previewBlogPost(id) {
            $.ajax({
                url: URL_PREVIEW_POST,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id
                },
                success: function(response) {
                    var post = response.post;
                    $('.meta_title').text(post.meta_title);
                    $('.meta_description').text(post.meta_description);
                    $('.meta_keywords').text(post.meta_keywords);
                    $('.url a').attr('href', post.url).text(post.url);
                    $('.related-url .box_bd').html('');
                    $("#modal01").modal({ showClose: true });
                }
            });
        }

        function deleteMultiPosts(ids) {
            $.ajax({
                url: URL_DELETE_MULTI_POSTS,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    ids: ids
                },
                success: function(response) {
                    alert('Selected posts have been deleted successfully.');
                    location.reload();
                }
            });
        }
    </script>
@endsection
