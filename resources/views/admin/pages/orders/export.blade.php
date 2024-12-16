<table dir="rtl">
    <thead>
    <tr style="background-color: burlywood">
        <th>@lang('Order ID')</th>
        <th>@lang('Club Name')</th>
        <th>@lang('Customer Name')</th>
        <th>@lang('Email')</th>
        <th>@lang('Phone')</th>
        <th>@lang('Activity')</th>
        <th>@lang('Branch')</th>
        <th>@lang('Price')</th>
        <th>@lang('Created At')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($lists as $list)
        <tr>
            <td>{{ $list->id }}</td>
            <td>{{ $list->club->name }}</td>
            <td>{{ $list->customer->name }}</td>
            <td>{{ $list->customer->email }}</td>
            <td>{{ $list->customer->phone }}</td>
            <td>{{ $list->activity->name }}</td>
            <td>{{ $list->branch->country->name }} - {{ $list->branch->city->name }}</td>
            <td>{{ $list->total }}</td>
            <td>{{ $list->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
