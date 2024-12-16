<table>
    <thead>
        <tr style="background-color: burlywood">
            <th>@lang('Club Name')</th>
            <th>@lang('Payment Type')</th>
            <th>@lang('Order Number')</th>
            <th>@lang('Order Total Price')</th>
            <th>@lang('Total Revenue')</th>
            <th>@lang('Revenue Confirmed')</th>
            <th>@lang('Revenue Confirmed At')</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lists as $list)
            <tr>
                <td>{{ $list->club->name_en }}</td>
                <td>{{ $list->order->payment_type }}</td>
                <td>{{ $list->order_id }}</td>
                <td>{{ $list->order->total ?? 0 }} @lang('EGP')</td>
                <td>{{ ($list->order->total  - $list->price) }} @lang('EGP')</td>
                <td>{{ ($list->confirmed == 1) ? __("Yes") : __("No") }}</td>
                <td>{{ (is_null($list->updated_at)) ? '' :\Carbon\Carbon::parse($list->updated_at) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
