<div class="form-group">
    <label for="{{$name}}">@lang($label) @if(isset($required))<span class="tx-danger">*</span>@endif</label>
    <textarea class="form-control summernote" id="{{$name}}" name="{{$name}}" @if(isset($height)) style="height: {{$height}}px" @else style="height: 200px" @endif placeholder="@lang($label)">{{ $value }}</textarea>
    @error($name)
        <span class="text-danger">
            {{ $message }}
        </span>
    @enderror
</div>

@if(isset($is_editor))
@push('scripts')
    <!-- INTERNAL Summernote Editor js -->
    <script src="{{ asset('assets/plugins/summernote-editor/summernote1.js') }}"></script>
    <script src="{{ asset('assets/js/summernote.js') }}"></script>
    <script>

        $('.summernote').summernote({

            tabsize: 3,
            height: 300,
        });
        </script>
@endpush
@push('css')
    <!-- Internal Summernote css-->
    <link rel="stylesheet" href="{{ asset('assets/plugins/summernote-editor/summernote.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/summernote-editor/summernote1.css') }}">
@endpush
@endif
