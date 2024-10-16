<span>期間</span>
<ul>
    <li data-period="all">すべて</li>
    @foreach($periods as $itm)
        <li data-period="{{ \Carbon\Carbon::parse($itm)->format('Y-m') }}">{{ \Carbon\Carbon::parse($itm)->format('Y年m月') }}</li>       
    @endforeach
</ul>