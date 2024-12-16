<label for="{{$name}}">@lang($label)</label>
<select name="{{$name}}" class="form-control select2-with-search" id="{{$name}}">
    <option value="-1">@lang('Choose')</option>
    @foreach ($data as $item)
        <option value="{{$item['id']}}">
            @if (!is_null($keyV))
                {{ $translate ? __($item[$keyV]) : $item[$keyV] }}
            @else
                {{ $translate ? __($item['name']) : $item['name'] }}
            @endif
        </option>
    @endforeach
</select>
