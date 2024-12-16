<table dir="rtl">
    <thead>
    <tr style="background-color: burlywood">
        <th>@lang('Name')</th>
        <th>@lang('Email')</th>
        <th>@lang('Phone')</th>
        <th>@lang('Created At')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($lists as $list)
        <tr>
            <td>{{ $list->name }}</td>
            <td>{{ $list->email }}</td>
            <td>{{ $list->phone }}</td>
            <td>{{ $list->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
