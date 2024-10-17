<span>公開ステータス</span>
<ul>
    <li data-status_code="all">すべて</li>
    @foreach($statuses as $status)
        <li data-status_code="{{$status}}">{{ getStatusName($status) }}</li>
    @endforeach
</ul>
