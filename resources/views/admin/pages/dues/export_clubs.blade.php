<table>
    <thead>
    <tr style="background-color: burlywood">
        <th>@lang('Club Name')</th>
        <th>@lang('Dues Price All')</th>
        <th>@lang('Dues Price Confirmed')</th>
        <th>@lang('Dues Price Un confirmed')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($lists as $list)
        @php
            $dues = getDues($list);
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
