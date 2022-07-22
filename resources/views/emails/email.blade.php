@component('mail::message')
<!DOCTYPE html>
<html>
<body>
    {!! $html !!}
    We aim to provide a personalized service, just like having a personal trainer in your pocket. So don't hesitate to get in touch for any fitness related assistance.
</body>
</html>
<br><br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent