<table dir="rtl">
    <thead>
    <tr style="background-color: burlywood">
        <th>@lang('Name')</th>
        <th>@lang('Created At')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($lists as $list)
        <tr>
            <td>{{ $list->name }}</td>
            <td>{{ $list->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
