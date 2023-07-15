<x-mail::message>
# Reset Password Settings

Hi, {{$name}}Regardin to your requset , we sent reset

<x-mail::panel >
Reset code is : {{$randomCode}}
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
