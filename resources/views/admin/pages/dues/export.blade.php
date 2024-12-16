<table>
    <thead>
        <tr style="background-color: burlywood">
            <th>@lang('Club Name')</th>
            <th>@lang('Payment Type')</th>
            <th>@lang('Order Number')</th>
            <th>@lang('Order Price')</th>
            <th>@lang('Due Price')</th>
            <th>@lang('Due Confirmed')</th>
            <th>@lang('Due Confirmed At')</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lists as $list)
            <tr>
                <td>{{ $list->club->name_en }}</td>
                <td>{{ $list->order->payment_type }}</td>
                <td>{{ $list->order_id }}</td>
                <td>{{ $list->order->total ?? 0 }} @lang('EGP')</td>
                <td>{{ $list->price }} @lang('EGP')</td>
                <td>{{ ($list->confirmed == 1) ? __("Yes") : __("No") }}</td>
                <td>{{ (is_null($list->updated_at)) ? '' :\Carbon\Carbon::parse($list->updated_at) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
