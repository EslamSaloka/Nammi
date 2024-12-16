@foreach ($notifications as $notification)
    <div class="media">
        @if($notification->seen == 1)
            <i class="fa fa-bell"></i>
        @else
            <i class="far fa-bell"></i>
        @endif
        @php
            $data = $notification->getWebNotification()
        @endphp
        <div class="media-body">
            <a href="{{ $data['nRoute'] }}">
                <p>{{ $data['title'] }}</p>
                <span>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
            </a>
        </div>
    </div>
@endforeach
