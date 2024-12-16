<table>
    <thead>
    <tr style="background-color: burlywood">
        <th>@lang('Club Name')</th>
        <th>@lang('Revenue Price All')</th>
        <th>@lang('Revenue Price Confirmed')</th>
        <th>@lang('Revenue Price Un confirmed')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($lists as $list)
        @php
            $dues = getClubRevenue($list);
        @endphp
        <tr>
            <td>{{ $list->name_en }}</td>
            <td>{{ $dues['total'] }} @lang('EGP')</td>
            <td>{{ $dues['confirmed'] }} @lang('EGP')</td>
            <td>{{ $dues['un_confirmed'] }} @lang('EGP')</td>
        </tr>
    @endforeach
    </tbody>
</table>
