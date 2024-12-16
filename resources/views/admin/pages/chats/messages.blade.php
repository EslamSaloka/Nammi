<div class="card custom-card">
    <div class="main-content-app pt-0">
        <div class="main-content-body main-content-body-chat">
            <div class="main-chat-header pt-3">
                <div class="main-img-user online"><img aalt="{{ $list->customer->name }}" src="{{ $list->customer->display_image }}" /></div>
                <div class="main-chat-msg-name">
                    <h6>{{ $list->customer->name }}</h6>
                </div>
            </div>
            <!-- main-chat-header -->
            <div class="main-chat-body" id="ChatBody">
                <div class="content-inner chat-content" id="main-chat-content" data-simplebar="init">
                    <div class="simplebar-wrapper" style="margin: -20px;">
                        <div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div>
                        <div class="simplebar-mask">
                            <div class="simplebar-offset">
                                <div class="simplebar-content-wrapper" tabindex="0" role="region">
                                    <div class="simplebar-content" style="padding: 20px;">



                                        @foreach ($chat->messages()->get() as $message)

                                            @if ($message->user_id != Auth::user()->id)
                                                <div class="media">
                                                    <div class="main-img-user online">
                                                        <img alt="{{ $message->user->name }}" src="{{ $message->user->display_image }}" />
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="main-msg-wrapper">
                                                            {!! $message->message !!}
                                                        </div>
                                                        <div>
                                                            <span>{{ \Carbon\Carbon::parse($message->create_at)->diffForHumans() }}</span> <a href="javascript:void(0);"><i class="icon ion-android-more-horizontal"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else

                                                <div class="media flex-row-reverse">
                                                    <div class="main-img-user online">
                                                        <img alt="{{ $message->user->name }}" src="{{ $message->user->display_image }}" />
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="main-msg-wrapper">
                                                            {!! $message->message !!}
                                                        </div>
                                                        <div>
                                                            <span>{{ \Carbon\Carbon::parse($message->create_at)->diffForHumans() }}</span> <a href="javascript:void(0);"><i class="icon ion-android-more-horizontal"></i></a>
                                                        </div>
                                                    </div>
                                                </div>

                                            @endif

                                        @endforeach




                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="simplebar-placeholder" style="width: auto; height: 1072px;"></div>
                    </div>
                </div>
            </div>
            <div class="main-chat-footer">
                <form action="{{ route("admin.chats.update",$chat->id) }}" method="post">
                    @csrf
                    @method("put")
                    <input class="form-control" name="message" placeholder="Type your message here..." type="text" />
                    <button style=" border: 0; background: white; " class="main-msg-send" type="submit"><i class="far fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
