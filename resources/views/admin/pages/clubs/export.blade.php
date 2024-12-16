<table dir="rtl">
    <thead>
    <tr style="background-color: burlywood">
        <th>@lang('Club Name Arabic')</th>
        <th>@lang('Club Name English')</th>
        <th>@lang('Club About Arabic')</th>
        <th>@lang('Club About English')</th>
        <th>@lang('Email')</th>
        <th>@lang('Phone')</th>
        <th>@lang('Created At')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($lists as $list)
        <tr>
            <td>{{ $list->name }}</td>
            <td>{{ $list->name_en }}</td>
            <td>{{ $list->about }}</td>
            <td>{{ $list->about_en }}</td>
            <td>{{ $list->email }}</td>
            <td>{{ $list->phone }}</td>
            <td>{{ $list->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
