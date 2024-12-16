@component('mail::message')

@component('mail::panel')
    {!! $message !!}
@endcomponent

@component('mail::button', [
    'url' => $url,
])
Click Button
@endcomponent

@lang('Thanks'),<br>
{{ getSettings("system_name",env('APP_NAME')) }}
@endcomponent
