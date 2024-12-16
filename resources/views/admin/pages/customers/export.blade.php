<table dir="rtl">
    <thead>
    <tr style="background-color: burlywood">
        <th>@lang('Name')</th>
        <th>@lang('Email')</th>
        <th>@lang('Phone')</th>
        <th>@lang('Birthday')</th>
        <th>@lang('Gender')</th>
        <th>@lang('Has Disabilities')</th>
        <th>@lang('AccountBy')</th>
        <th>@lang('Time Hobby Practise')</th>
        <th>@lang('Created At')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($lists as $list)
        <tr>
            <td>{{ $list->name }}</td>
            <td>{{ $list->email }}</td>
            <td>{{ $list->phone }}</td>
            <td>{{ $list->birthday }}</td>
            <td>{{ $list->gender }}</td>
            <td>{{ ($list->disabilities == 1) ? __("Yes") : __("No") }}</td>
            <td>{{ $list->account_by }}</td>
            <td>{{ $list->time->name ?? '' }}</td>
            <td>{{ $list->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
