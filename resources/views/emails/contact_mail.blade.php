@component('mail::message')
<h3>Hi {{$body['name']}},</h3>
<h3>{{$body['message']}}</h3>



Thanks,<br>
{{ config('app.name') }}
@endcomponent
