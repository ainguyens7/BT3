

@extends('emails.master')

@section('mail_content')
    Store : {{ $data['shop_domain'] }}<br>
    Email : {{ $data['shop_email'] }}<br><br>
    <b>Feedback</b> : {{ $data['feedback'] }}
@endSection

@section('mail_banner')
    {{ 'https://gallery.mailchimp.com/485095222da43f30e5bfab9f3/images/9f8f3926-e661-4003-b08f-a7342e4637f7.jpg' }}
@endSection
