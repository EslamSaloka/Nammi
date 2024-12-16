<table dir="rtl">
    <thead>
    <tr style="background-color: burlywood">
        <th>@lang('Club Name')</th>
        <th>@lang('Activity Name')</th>
        <th>@lang('Branch Name')</th>
        <th>@lang('Price')</th>
        <th>@lang('Disabilities')</th>
        <th>@lang('Order One Time')</th>
        <th>@lang('Rates')</th>
        <th>@lang('Created At')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($lists as $list)
        <tr>
            <td>
                {{ (App::getLocale() == "ar") ? $list->club->name ?? '' : $list->club->name_en ?? '' }}
            </td>
            <td>
                {{ $list->name }}
            </td>
            <td>
                {{ $list->branch->country->name }} - {{ $list->branch->city->name }}
            </td>
            <td>
                @if ($list->offer > 0)
                    {{ $list->offer }} @lang('EGP')
                @else
                    {{ $list->price }} @lang('EGP')
                @endif
            </td>
            <td>
                {{ ($list->disabilities == 1) ? __('Yes') : __('No') }}
            </td>
            <td>
                {{ ($list->order_one_time == 1) ? __('Yes') : __('No') }}
            </td>
            <td>
                {!! $list->rates !!}
            </td>
            <td>{{ $list->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
