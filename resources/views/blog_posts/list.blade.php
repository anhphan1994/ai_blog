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
                        <span>8記事</span>
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
@section('custom_js')
    <script>
        var URL_GET_BLOG_POSTS = "{{ route('post.ajax.list') }}";
        var URL_GET_STATUS_FILTER = "{{ route('post.ajax.status') }}";
        var URL_GET_PERIOD_FILTER = "{{ route('post.ajax.period') }}";
        var URL_SHOW_POST = "{{ route('post.edit', '') }}";
        var URL_DUPPLICATE_POST = "{{ route('post.duplicate', '') }}";
        var URL_DELETE_POST = "{{ route('post.ajax.delete', '') }}";

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
                console.log(status);
                getBlogPosts();
            });

            $(document).on('click', '#period_filter ul li', function() {
                var periodText = $(this).text();
                $('#period_filter span').text(periodText);
                period = $(this).data('period');
                console.log(period);
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

           
        });

        function getBlogPosts() {
            $.ajax({
                url: URL_GET_BLOG_POSTS,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    status: status ?? 'all',
                    period: period ?? 'all'
                },
                success: function(response) {
                    $('#blog_posts_list').html(response.html);
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
    </script>
@endsection
