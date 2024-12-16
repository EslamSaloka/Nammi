<table dir="rtl">
    <thead>
    <tr style="background-color: burlywood">
        <th>@lang('Name')</th>
        <th>@lang('Email')</th>
        <th>@lang('Phone')</th>
        <th>@lang('Seen')</th>
        <th>@lang('Message')</th>
        <th>@lang('Created At')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($lists as $list)
        <tr>
            <td>{{ $list->name }}</td>
            <td>{{ $list->email }}</td>
            <td>{{ $list->phone }}</td>
            <td>{{ ($list->seen == 1) ? __("Yes") : __("No") }}</td>
            <td>{{ $list->message }}</td>
            <td>{{ $list->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
