@extends('admin.layouts.master')
@section('title',$breadcrumb['title'])
@section('PageContent')

@if($order->order_status == \App\Models\Order::STATUS_REJECTED)
    <div class="alert alert-danger" role="alert">
        @lang('The order was canceled by')
        @php
            echo __(ucfirst($order->cancel_by))
        @endphp
    </div>
@endif

<div class="row">


    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif



    <h2>
        @lang('Order Number') : # {{ $order->id }}
    </h2>

        <hr>

    <div class="col-lg-8">
        @if (!in_array(\App\Models\User::TYPE_CLUB,\Auth::user()->roles()->pluck("name")->toArray()))
            <h4 style="background: #c4c4c4;padding:10px;">
                @lang('Club Information')
            </h4>
            <div class="">
                <div class="table-responsive">
                    <table class="table project-list-table table-nowrap align-middle table-borderless">
                        <tbody>
                            <tr>
                                <td>
                                    @lang('Club Name')
                                </td>
                                <td>
                                    @lang('Phone')
                                </td>
                                <td>
                                    @lang('Email')
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    @if (!in_array(\App\Models\User::TYPE_CLUB,\Auth::user()->roles()->pluck("name")->toArray()))
                                        @canAny('clubs.edit')
                                            <a href="{{ route('admin.clubs.edit', $order->club_id) }}">
                                                {{ (App::getLocale() == "ar") ? $order->club->name ?? '' : $order->club->name_en ?? '' }}
                                            </a>
                                        @else
                                            {{ (App::getLocale() == "ar") ? $order->club->name ?? '' : $order->club->name_en ?? '' }}
                                        @endcanAny
                                    @else
                                        {{ $order->club->name }}
                                    @endif
                                </td>
                                <td>
                                    {{ $order->club->phone }}
                                </td>
                                <td>
                                    {{ $order->club->email }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        @endif

        <h4 style="background: #c4c4c4;padding:10px;">
            @lang('Customer Information')
        </h4>
        <div class="">
            <div class="table-responsive">
                <table class="table project-list-table table-nowrap align-middle table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                @lang('Nane')
                            </td>
                            <td>
                                @lang('Phone')
                            </td>
                            <td>
                                @lang('Email')
                            </td>
                        </tr>
                        <tr>
                            <td>
                                @if (!in_array(\App\Models\User::TYPE_CLUB,\Auth::user()->roles()->pluck("name")->toArray()))
                                    @canAny('customers.edit')
                                        <a href="{{ route('admin.customers.edit', $order->customer_id) }}">
                                            {{ $order->customer->name }}
                                        </a>
                                    @else
                                        {{ $order->customer->name }}
                                    @endcanAny
                                @else
                                    {{ $order->customer->name }}
                                @endif
                            </td>
                            <td>
                                {{ $order->customer->phone }} || {{ $order->mobile ?? '' }}
                            </td>
                            <td>
                                {{ $order->customer->email }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <h4 style="background: #c4c4c4;padding:10px;">
            @lang('Club Branch')
        </h4>
        <div class="">
            <div class="table-responsive">
                <table class="table project-list-table table-nowrap align-middle table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                @lang('Country')
                            </td>
                            <td>
                                @lang('City')
                            </td>
                            <td>
                                @lang('Map')
                            </td>
                            <td>
                                @lang('Address')
                            </td>
                            <td>
                                @lang('Contact')
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ $order->branch->country->name ?? '' }}
                            </td>
                            <td>
                                {{ $order->branch->city->name ?? '' }}
                            </td>
                            <td>
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->branch->lat ?? '' }},{{ $order->branch->lng ?? '' }}" target="_blank" rel="noopener noreferrer">
                                    @lang('Destination')
                                </a>
                            </td>
                            <td>
                                {{ $order->branch->address ?? '' }}
                            </td>
                            <td>
                                <ul>
                                    <li>
                                        @lang('Email') :
                                        <a href="mailto:{{ $order->branch->email ?? '' }}">{{ $order->branch->email ?? '' }}</a>
                                    </li>
                                    <li>
                                        @lang('Phone') : <a href="tel:{{ $order->branch->phone ?? '' }}">{{ $order->branch->phone ?? '' }}</a>
                                    </li>
                                    <li>
                                        @lang("What's App") : <a href="https://wa.me/{{ $order->branch->what_app ?? '' }}" target="_blank" rel="noopener noreferrer">{{ $order->branch->what_app ?? '' }}</a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="">
            <h4 style="background: #c4c4c4;padding:10px;">
                @lang('Activity Info')
            </h4>
            <div class="table-responsive">
                <table class="table project-list-table table-nowrap align-middle table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                @lang('Name')
                            </td>
                            <td>
                                @lang('Price')
                            </td>
                            <td>
                                @lang('Disabilities')
                            </td>
                            <td>
                                @lang('Order One Time')
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ $order->activity->name }}
                            </td>
                            <td>
                                {{ $order->sub_price }} @lang('EGP')
                            </td>
                            <td>
                                {!! $order->activity->showDisabilities() !!}
                            </td>
                            <td>
                                {!! $order->activity->showOrderOneTime() !!}
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="">
            <h4 style="background: #c4c4c4;padding:10px;">
                @lang('Notes')
            </h4>
            <table class="table project-list-table table-nowrap align-middle table-borderless">
                <tbody>
                <tr>
                    <td>
                        {!! $order->notes ?? '' !!}
                    </td>
                </tr>
                </tbody>
            </table>

        </div>

    </div>
    <div class="col-lg-4">
        <div class="">
            <h4 style="background: #c3d87d;padding:10px;">
                @lang('Order history')
            </h4>
            <div class="table-responsive">
                <table class="table project-list-table table-nowrap align-middle table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                @lang('Status')
                            </td>
                            <td>
                                @lang('Date')
                            </td>
                        </tr>
                        @foreach ($order->histories()->get() as $history)
                            <tr>
                                <td>
                                    {{ __(ucwords(str_replace("_"," ",$history->order_status))) }}
                                </td>
                                <td>
                                    {{ $history->created_at }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($order->order_status != \App\Models\Order::STATUS_REJECTED)
                @if ($order->order_status != \App\Models\Order::STATUS_COMPLETED)
                    @if ($order->order_status != \App\Models\Order::STATUS_WAITING_CUSTOMER_COMPLETED)
                        <br>
                        <div class="table-responsive">
                            <table class="table project-list-table table-nowrap align-middle table-borderless">
                                <tbody>
                                    <tr>
                                        <td>
                                            @lang('Change Order Status')
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            @if ($order->order_status == \App\Models\Order::STATUS_PENDING)
                                                @if ($order->activity->order_one_time == 1)
                                                    @if ($order->payment_type == "visa")
                                                        @if ($order->payment_status == "paid")
                                                            <a class="modelOpen btn btn-primary" data-title="{{ \App\Models\Order::STATUS_CONFIRMED }}" data-openDate="false" href="{{ route('admin.orders.change-status',$order->id) }}"> {{ ucwords(str_replace("_"," ",\App\Models\Order::STATUS_CONFIRMED)) }} </a>
                                                        @elseif ($order->payment_status == "unpaid")
                                                            @lang('Oops, The Payment Is Un Paid')
                                                        @else
                                                            @lang('Waiting user to pay')
                                                        @endif
                                                    @else
                                                        <a class="modelOpen btn btn-primary" data-title="{{ \App\Models\Order::STATUS_ACCEPTED }}" data-openDate="false" href="{{ route('admin.orders.change-status',$order->id) }}"> {{ ucwords(str_replace("_"," ",\App\Models\Order::STATUS_ACCEPTED)) }} </a>
                                                        <a class="modelOpen btn btn-pink" data-title="{{ \App\Models\Order::STATUS_TIME_CHANGE }}" data-openDate="true" href="{{ route('admin.orders.change-status',$order->id) }}"> {{ ucwords(str_replace("_"," ",\App\Models\Order::STATUS_TIME_CHANGE)) }} </a>
                                                    @endif
                                                @else
                                                    @if ($order->payment_type == "visa")
                                                        @if ($order->payment_status == "paid")
                                                            <a class="modelOpen btn btn-primary" data-title="{{ \App\Models\Order::STATUS_ACCEPTED }}" data-openDate="false" href="{{ route('admin.orders.change-status',$order->id) }}"> {{ ucwords(str_replace("_"," ",\App\Models\Order::STATUS_ACCEPTED)) }} </a>
                                                            <a class="modelOpen btn btn-pink" data-title="{{ \App\Models\Order::STATUS_TIME_CHANGE }}" data-openDate="true" href="{{ route('admin.orders.change-status',$order->id) }}"> {{ ucwords(str_replace("_"," ",\App\Models\Order::STATUS_TIME_CHANGE)) }} </a>
                                                        @elseif ($order->payment_status == "unpaid")
                                                            @lang('Oops, The Payment Is Un Paid')
                                                        @else
                                                            <a class="modelOpen btn btn-primary" data-title="{{ \App\Models\Order::STATUS_ACCEPTED }}" data-openDate="false" href="{{ route('admin.orders.change-status',$order->id) }}"> {{ ucwords(str_replace("_"," ",\App\Models\Order::STATUS_ACCEPTED)) }} </a>
                                                            <a class="modelOpen btn btn-pink" data-title="{{ \App\Models\Order::STATUS_TIME_CHANGE }}" data-openDate="true" href="{{ route('admin.orders.change-status',$order->id) }}"> {{ ucwords(str_replace("_"," ",\App\Models\Order::STATUS_TIME_CHANGE)) }} </a>
                                                        @endif
                                                    @else
                                                        <a class="modelOpen btn btn-primary" data-title="{{ \App\Models\Order::STATUS_ACCEPTED }}" data-openDate="false" href="{{ route('admin.orders.change-status',$order->id) }}"> {{ ucwords(str_replace("_"," ",\App\Models\Order::STATUS_ACCEPTED)) }} </a>
                                                        <a class="modelOpen btn btn-pink" data-title="{{ \App\Models\Order::STATUS_TIME_CHANGE }}" data-openDate="true" href="{{ route('admin.orders.change-status',$order->id) }}"> {{ ucwords(str_replace("_"," ",\App\Models\Order::STATUS_TIME_CHANGE)) }} </a>
                                                    @endif
                                                @endif
                                            @elseif ($order->order_status == \App\Models\Order::STATUS_ACCEPTED)
                                                @if ($order->payment_type == "visa")
                                                    @if ($order->payment_status == "paid")
                                                        <a class="modelOpen btn btn-primary" data-title="{{ \App\Models\Order::STATUS_REJECTED }}" data-openDate="false" href="{{ route('admin.orders.change-status',$order->id) }}"> {{ ucwords(str_replace("_"," ",\App\Models\Order::STATUS_REJECTED)) }} </a>
                                                        <a class="modelOpen btn btn-primary" data-title="{{ \App\Models\Order::STATUS_COMPLETED }}" data-openDate="false" href="{{ route('admin.orders.change-status',$order->id) }}"> {{ ucwords(str_replace("_"," ",\App\Models\Order::STATUS_COMPLETED)) }} </a>
                                                    @elseif ($order->payment_status == "unpaid")
                                                        @lang('Oops, The Payment Is Un Paid')
                                                    @elseif ($order->payment_status == "pending")
                                                        @lang('Waiting Customer Pay')
                                                    @else
                                                        @lang('Oops, The Payment In Process')
                                                    @endif
                                                @else
                                                    <a class="modelOpen btn btn-primary" data-title="{{ \App\Models\Order::STATUS_REJECTED }}" data-openDate="false" href="{{ route('admin.orders.change-status',$order->id) }}"> {{ ucwords(str_replace("_"," ",\App\Models\Order::STATUS_REJECTED)) }} </a>
                                                    <a class="modelOpen btn btn-primary" data-title="{{ \App\Models\Order::STATUS_COMPLETED }}" data-openDate="false" href="{{ route('admin.orders.change-status',$order->id) }}"> {{ ucwords(str_replace("_"," ",\App\Models\Order::STATUS_COMPLETED)) }} </a>
                                                @endif
                                            @elseif ($order->order_status == \App\Models\Order::STATUS_TIME_CHANGE)
                                                @lang('Waiting Customer Response')
                                            @else
                                                <a class="modelOpen btn btn-primary" data-title="{{ \App\Models\Order::STATUS_REJECTED }}" data-openDate="false" href="{{ route('admin.orders.change-status',$order->id) }}"> {{ ucwords(str_replace("_"," ",\App\Models\Order::STATUS_REJECTED)) }} </a>
                                                <a class="modelOpen btn btn-primary" data-title="{{ \App\Models\Order::STATUS_COMPLETED }}" data-openDate="false" href="{{ route('admin.orders.change-status',$order->id) }}"> {{ ucwords(str_replace("_"," ",\App\Models\Order::STATUS_COMPLETED)) }} </a>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        @if ($order->order_status == \App\Models\Order::STATUS_WAITING_CUSTOMER_COMPLETED)
                            <br>
                            <h4 style="background: #c3d87d;padding:10px;">
                                @lang('Customer Confirmation')
                            </h4>
                            <div class="table-responsive">
                                <table class="table project-list-table table-nowrap align-middle table-borderless">
                                    <tbody>
                                        <tr>
                                            <td>
                                                @if (is_null($order->otp_verified_at))
                                                    @lang('Status')
                                                @else
                                                    @lang('confirmation Time')
                                                @endif
                                            </td>
                                            <td>
                                                {{ $order->otp_verified_at ?? __("Waiting for confirmation") }}
                                            </td>
                                        </tr>
                                        @if (is_null($order->otp_verified_at))
                                            <tr>
                                                <td colspan="2">
                                                    <form action="{{ route('admin.orders.check-otp',$order->id) }}" method="post">
                                                        <input placeholder="{{__("Enter OTP Sent to Client")}}" type="number" name="otp" style="text-align:center;" class="form-control"/>
                                                        <div style="padding-top:10px;">
                                                            @lang('Type the OTP then press enter')
                                                        </div>
                                                        @csrf
                                                    </form>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @lang('Re-Send Otp')
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.orders.re-send',$order->id) }}">
                                                        @lang('Send')
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endif
                @else
                    <br>
                    <h4 style="background: #c3d87d;padding:10px;">
                        @lang('Customer Confirmation')
                    </h4>
                    <div class="table-responsive">
                        <table class="table project-list-table table-nowrap align-middle table-borderless">
                            <tbody>
                                <tr>
                                    <td>
                                        @if (is_null($order->otp_verified_at))
                                            @lang('Status')
                                        @else
                                            @lang('confirmation Time')
                                        @endif
                                    </td>
                                    <td>
                                        {{ $order->otp_verified_at ?? __("Waiting for confirmation") }}
                                    </td>
                                </tr>
                                @if (is_null($order->otp_verified_at))
                                    <tr>
                                        <td colspan="2">
                                            <form action="{{ route('admin.orders.check-otp',$order->id) }}" method="post">
                                                <input placeholder="{{__("Enter OTP Sent to Client")}}" type="number" name="otp" style="text-align:center;" class="form-control"/>
                                                <div style="padding-top:10px;">
                                                    @lang('Type the OTP then press enter')
                                                </div>
                                                @csrf
                                            </form>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            @lang('Re-Send Otp')
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.re-send',$order->id) }}">
                                                @lang('Send')
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif

        </div>

        <div class="">
            <h4 style="background: #c3d87d;padding:10px;">
                @lang('Payment')
            </h4>
            <div class="table-responsive">
                <table class="table project-list-table table-nowrap align-middle table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                @lang('Payment Type')
                            </td>
                            <td>
                                @if ($order->payment_type == "visa")
                                    <img src="{{ url('visa.jpg') }}" style=" width: 50px; "> @lang("Online")
                                @else
                                    <img src="{{ url('cod.png') }}" style=" width: 50px; "> @lang("COD")
                                @endif
                            </td>
                        </tr>
                        @if ($order->payment_type == "visa")
                            <tr>
                                <td>
                                    @lang('Payment Status')
                                </td>
                                <td>
                                    {{ $order->payment_status }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    @lang('Invoice ID')
                                </td>
                                <td>
                                    {{ $order->invoiceId }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    @lang('Transaction ID')
                                </td>
                                <td>
                                    {{ $order->transaction_id }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @if($order->activity->order_one_time == 0)
        <div class="">
            <h4 style="background: #c3d87d;padding:10px;">
                @lang('Data')
            </h4>

            <div class="table-responsive">
                <table class="table project-list-table table-nowrap align-middle table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                @lang('Order Date')
                            </td>
                            <td>
                                {{ $order->date }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                @lang('Order Time')
                            </td>
                            <td>
                                {{ $order->time }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="">
            <h4 style="background: #c3d87d;padding:10px;">
                @lang('Prices')
            </h4>
            <div class="table-responsive">
                <table class="table project-list-table table-nowrap align-middle table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                @lang('Sub Total')
                            </td>
                            <td>
                                {{ $order->sub_price }} @lang('EGP')
                            </td>
                        </tr>
                        @if ($order->coupon_price != 0)
                            <tr>
                                <td>
                                    @lang('Coupon')
                                </td>
                                <td>
                                    {{ $order->coupon_price }} @lang('EGP')
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>
                                @lang('Total')
                            </td>
                            <td>
                                {{ $order->total }} @lang('EGP')
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection



<div class="modal" id="AcceptedModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button aria-label="Close" class="btn btn-sm" data-bs-dismiss="modal" type="button">
                    <i class="fa fa-close"></i>
                </button>
            </div>
            <form id="AcceptedModalForm" action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div id="12">
                            @lang('Are You Sure ?')
                        </div>
                        <div id="123">
                            <div class="col-md-6">
                                @include('admin.component.form_fields.input', [
                                    'label' => 'Date',
                                    'name' => 'date',
                                    'type' => 'date',
                                    'required' => true,
                                    'value' => null
                                ])
                            </div>
                            <div class="col-md-6">
                                @include('admin.component.form_fields.input', [
                                    'label' => 'Time',
                                    'name' => 'time',
                                    'type' => 'time',
                                    'required' => true,
                                    'value' => null
                                ])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="button-group">
                        @csrf
                        <input type="hidden" name="order_status" class="ooChnage" value="">
                        <button class="btn ripple btn-primary" type="submit">@lang('Save Action')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(".modelOpen").on("click",function(e){
            e.preventDefault();
            $("#AcceptedModalForm").attr("action",$(this).attr("href"));
            $('.modal-title').html('').html($(this).attr("data-title"));
            $(".ooChnage").attr("value",$(this).attr("data-title"));
            if($(this).attr("data-openDate") == "true") {
                $("#123").css("display","contents");
                $("#12").css("display","none");
            } else {
                $("#12").css("display","contents");
                $("#123").css("display","none");
            }
            // ========================== //
            if($(this).attr("data-title") == "completed") {
                $("#12").html(" ").html("{{ __('OTP will be sent to the customer for confirmation') }}");
            } else {
                $("#12").html(" ").html("{{ __('Are You Sure ?') }}");
            }
            // ========================== //
            $("#AcceptedModal").modal("show");
        });
    </script>
@endpush
