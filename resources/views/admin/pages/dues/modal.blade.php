<div class="table-responsive border userlist-table">
    <table class="table card-table table-striped table-vcenter text-nowrap mb-0">
        <thead>
            <tr>
                <th class="wd-lg-8p">#</th>
                <th class="wd-lg-8p">##</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <span>@lang('Order ID')</span>
                </td>
                <td>
                    <a href="{{ route('admin.orders.show',$show->order_id) }}">
                        {{ $show->order_id ?? '' }}
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <span>@lang('Order Number')</span>
                </td>
                <td>
                    {{ $show->order->total ?? '' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span>@lang('Due Price')</span>
                </td>
                <td>
                    {{ $show->price ?? '' }} @lang('EGP')
                </td>
            </tr>
        </tbody>
    </table>
</div>
