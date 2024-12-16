@extends('admin.layouts.master')
@section('title', $breadcrumb['title'])
@section('PageContent')


@if ($lists->count() > 0)


<div class="row row-sm">
    <div class="col-lg-4 col-xl-3 col-md-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="">
                    <div class="main-mail-menu pd-r-0 mg-t-20">

                        <div style="padding: 0px">
                            <div class="main-chat-list tab-pane p-0">

                                @foreach ($lists as $list)
                                    <a class="media new" href="{{ route('admin.chats.show',$list->id) }}">
                                        <div class="main-img-user online">
                                            <img alt="{{ $list->customer->name }}" src="{{ $list->customer->display_image }}" />
                                            @php
                                                $count = $list->messages()->where("seen",0)->where("user_id","!=",\Auth::user()->id)->count();
                                            @endphp
                                            @if ($count > 0)
                                                <span>{{ $count }}</span>
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            <div class="media-contact-name">
                                                <span>{{ $list->customer->name }}</span>
                                                <span>{{ $list->updated_at->diffForHumans() }}</span>
                                            </div>
                                            <p>
                                                {{ $list->messages()->orderBy('id',"desc")->first()->message ?? '' }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-xl-9 col-md-12">
        <div class="card custom-card">
            @if (isset($chat))
                @include('admin.pages.chats.messages',["chat"=>$chat])
            @else
                <div class="card-body h-100">
                    @include('admin.component.inc.nodata', [
                        'name' => __('Chat Messages')
                    ])
                </div>
            @endif
        </div>
    </div>
</div>


@else


<div class="row row-sm">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
        <div class="card custom-card">
            @include('admin.component.inc.nodata', [
                'name' => __('Chats')
            ])
        </div>
    </div>
</div>

@endif


@endsection
