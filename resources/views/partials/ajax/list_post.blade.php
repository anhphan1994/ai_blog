<ul>
    @foreach ($blog_posts as $item)
        <li>
            <div class="form">
                <div class="list_checkbox">
                    <div>
                        <label class="checkbox">
                            <input type="checkbox" class="chk-post" data-id="{{ $item->id }}">
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="blk_txt">
                <span @if ($item->status == \App\Models\BlogPost::STATUS_GENERATED) class="st2" @endif>{{ $item->title }}
                </span>
                <p><span class="{{ getStatusClass($item->status) }}">
                        ●{{ getStatusName($item->status) }}</span>
                    @if ($item->status != \App\Models\BlogPost::STATUS_GENERATED)
                        作成：{{ \Carbon\Carbon::parse($item->created_at)->format('Y年m月d日 H:i') }}
                    @endif
                    @if ($item->status == \App\Models\BlogPost::STATUS_PUBLISHED || $item->status == \App\Models\BlogPost::STATUS_SCHEDULED)
                        公開：{{ \Carbon\Carbon::parse($item->published_at)->format('Y年m月d日 H:i') }}
                    @endif
                </p>
            </div>
            <div class="blk_r">
                @if ($item->status != \App\Models\BlogPost::STATUS_GENERATED)
                    <figure>
                        <img src="{{ asset('img/list_img1.png') }}" alt="">
                    </figure>
                    <div class="btn_eye preview" data-id="{{ $item->id}}"></div>
                @endif
                <div class="btn_dots">
                    <span></span>
                    <ul class="action">
                        <li class="show" data-id="{{ $item->id }}">詳細</li>
                        <li class="duplicate" data-id="{{ $item->id }}">複製</li>
                        <li class="del" data-id="{{ $item->id }}">削除</li>
                    </ul>
                </div>
            </div>
        </li>
    @endforeach
</ul>
