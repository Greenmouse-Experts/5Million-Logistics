@component('mail::message')
<h1>Thank you for signing up with  {{config('app.name')}}.</h1>
<p>Before you get started we need you to confirm your email
address. Please enter the 4-digit code to verify/activate your account.:</p>

@component('mail::panel')
Your verification code is: {{ $code }}
@endcomponent

<p>If you didn't make this request, there’s nothing to worry about — you can safely ignore this email.</p>
<p>If you have any questions, please visit our FAQ page or contact us.</p>
@endcomponent