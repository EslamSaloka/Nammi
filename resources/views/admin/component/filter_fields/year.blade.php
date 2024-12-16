<div class="form-group">
    <label for="{{$name}}">@lang($label) @if(isset($required))<span class="tx-danger">*</span>@endif</label>
    <input type="date" class="form-control datepicker" id="{{$name}}" name="{{$name}}" placeholder="@lang($label)">
    @error($name)
        <span class="text-danger">
            {{ $message }}
        </span>
    @enderror
</div>

@push('styles')
    <style>
        .ui-datepicker-calendar {
        display: none;
        }
    </style>
@endpush

@push('scripts')
<script>
    $(function() {
            $( ".datepicker" ).datepicker({dateFormat: 'yy'});
        });​
    </script>
@endpush
