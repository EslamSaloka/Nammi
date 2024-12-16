<table dir="rtl">
    <thead>
    <tr style="background-color: burlywood">
        <th>@lang('Club Name')</th>
        <th>@lang('Country Name')</th>
        <th>@lang('City Name')</th>
        <th>@lang('Branch Master')</th>
        <th>@lang('Branch Name')</th>
        <th>@lang('Branch Address')</th>
        <th>@lang('Email')</th>
        <th>@lang('Phone')</th>
        <th>@lang('whats_app')</th>
        <th>@lang('Lat')</th>
        <th>@lang('Lng')</th>
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
                {{ $list->country->name ?? '' }}
            </td>
            <td>
                {{ $list->city->name ?? '' }}
            </td>
            <td>
                {{ $list->user->name ?? '' }}
            </td>
            <td>
                {{ $list->name }}
            </td>
            <td>
                {{ $list->address }}
            </td>
            <td>
                {{ $list->email }}
            </td>
            <td>
                {{ $list->phone }}
            </td>
            <td>
                {{ $list->what_app }}
            </td>
            <td>
                {{ $list->lat }}
            </td>
            <td>
                {{ $list->lng }}
            </td>
            <td>{{ $list->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
