@component('mail::message')
# Someone has reset your password

Please go to this <a href="{{ config('app.frontendurl').'/password/reset/'.$data->token }}"> Link </a> To reset a password
<br>
If the link is not working, please copy this url to ur web browser and go
<br>
<code>{{ config('app.frontendurl').'/password/reset/'.$data->token }}</code>
<br>
But, if you don't think that you are reseting your password, then just ignore this email
<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
