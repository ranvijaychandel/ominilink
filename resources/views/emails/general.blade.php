
<x-mail::message>
# Hello {{ $name }}

{{ $message }}

<x-mail::button :url="$url" color="primary">
    Click Here
</x-mail::button>

Thanks,
{{ config('app.name') }}
</x-mail::message>
