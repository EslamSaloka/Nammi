@if (isset($breadcrumb))
<div>
    <h2 class="main-content-title tx-24 mg-b-5">{{ $breadcrumb['title'] }}</h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.home.index') }}">
                @lang('Dashboard')
            </a>
        </li>
        @foreach ($breadcrumb['items'] as $item)
            <li class="breadcrumb-item text-muted">
                @if ($item['url'] == "#!")
                    {{$item['title']}}
                @else
                    <a href="{{$item['url']}}">{{$item['title']}}</a>
                @endif
            </li>
        @endforeach
        <!--<li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['title'] }}</li>-->
    </ol>
</div>
@endif
