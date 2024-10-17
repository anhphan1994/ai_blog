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
                        $outline = str_replace("\n", "<br>", $outline);
                    @endphp
                    <h2>{!!$outline!!}</h2>
                </div>
                <div class="post_bot_r noBot">
                    <div class="p_edit">
                        <div class="e_it">
                            <h3>タイトル</h3>
                            <div class="e_action">
                                <p class="e_txt">記事のタイトルを入力します。</p>
                                <div class="e_action_r">
                                    <span>50ポイントを使用</span>
                                    <button>タイトルを再生成</button>
                                </div>
                            </div>
                            <div class="box_gr">
                                <textarea oninput="auto_grow(this)" class="textareaOnload" id="title_value">{{$post->title}}</textarea>
                            </div>
                        </div>
                        <div class="e_it">
                            <h3>セクション（見出し）</h3>
                            <div class="e_action">
                                <p class="e_txt">記事内のセクションを入力します。</p>
                                <div class="e_action_r">
                                    <span>50ポイントを使用</span>
                                    <button>セクションを再生成</button>
                                </div>
                            </div>
                            <div class="box_gr">
                                <textarea oninput="auto_grow(this)" class="textareaOnload" id="outline_value">{{$post->outline}}</textarea>
                            </div>
                        </div>
                        <div class="e_it">
                            <h3>本文</h3>
                            <p class="e_txt full">本文のテキストを入力します。見出しの設定や画像挿入、文字装飾などは「投稿設定」にて編集可能です。<br>
                                下記に表示されている「トレンドワード」をクリックすると、そのキーワードを交えた最適な文章が再生成されます。</p>
                            <div class="e_action">
                                <div class="e_action_l">
                                    <div class="tags">
                                        <span>映画ロケ地巡り</span>
                                        <span>インスタ映え</span>
                                        <span>フォトツアー</span>
                                        <span>聖地</span>
                                        <span>新海誠</span>
                                        <span>ストリートアート</span>
                                        <span>東京の風景</span>
                                        <span>隠れスポット</span>
                                        <span>レトロスポット</span>
                                        <span>渋谷スカイ</span>
                                    </div>
                                </div>
                                <div class="e_action_r">
                                    <span>50ポイントを使用</span>
                                    <button>本文を再生成</button>
                                </div>
                            </div>
                            <div class="box_gr">
                                <textarea oninput="auto_grow(this)" class="textareaOnload" id="content_value">{{$post->content}}</textarea>
                            </div>
                        </div>
                    </div>
                    <a href="" class="btn st2">保存して投稿設定へ進む</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_modal')
@endsection
@section('custom_js')
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
