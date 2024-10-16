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
                        <!-- <div class="btn_del">× 選択した記事を削除</div> -->
                    </div>
                    <div class="sl_gr">
                        <div class="select selectEvent">
                            <span>公開ステータス</span>
                            <ul>
                                <li>公開中</li>
                                <li>予約投稿</li>
                                <li>下書き</li>
                            </ul>
                            <!-- <label>
          <select>
            <option value="">公開ステータス</option>
            <option value="公開中">公開中</option>
            <option value="予約投稿">予約投稿</option>
            <option value="下書き">下書き</option>
          </select>
        </label> -->
                        </div>
                        <div class="select selectEvent">
                            <span>期間</span>
                            <ul>
                                <li>すべて</li>
                                <li>2024年12月</li>
                                <li>2024年11月</li>
                                <li>2024年10月</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="list_bd_bd">
                    <ul>
                        @foreach($blog_posts as $item)
                            <li>
                                <div class="form">
                                    <div class="list_checkbox">
                                        <div>
                                            <label class="checkbox">
                                                <input type="checkbox">
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="blk_txt">
                                    <span 
                                        @if($item->status == \App\Models\BlogPost::STATUS_GENERATED)
                                            class="st2"
                                        @endif
                                        >{{ $item->title }}
                                    </span>
                                    <p><span class="{{getStatusClass($item->status)}}">
                                        ●{{ getStatusName($item->status) }}</span>
                                        @if($item->status != \App\Models\BlogPost::STATUS_GENERATED)
                                        作成：{{ \Carbon\Carbon::parse($item->created_at)->format('Y年m月d日 H:i') }}
                                        @endif
                                        @if($item->status == \App\Models\BlogPost::STATUS_PUBLISHED || $item->status == \App\Models\BlogPost::STATUS_SCHEDULED)
                                        公開：{{ \Carbon\Carbon::parse($item->published_at)->format('Y年m月d日 H:i') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="blk_r">
                                    @if($item->status != \App\Models\BlogPost::STATUS_GENERATED)
                                        <figure>
                                            <img src="{{ asset('img/list_img1.png') }}" alt="">
                                        </figure>
                                        <div class="btn_eye"></div>
                                    @endif
                                    <div class="btn_dots">
                                        <span></span>
                                        <ul>
                                            <li>詳細</li>
                                            <li>複製</li>
                                            <li class="del">削除</li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
