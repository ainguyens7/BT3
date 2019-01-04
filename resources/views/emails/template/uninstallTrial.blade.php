@extends('emails.master')

@section('mail_content')
    Hi <b>{{ !empty($data->shop_owner) ? $data->shop_owner : '' }}</b>,<br>
    Have you just stopped using our app :(
    <br><br>
    We are really sorry if your using experience with our app was not
    what you expected. If you don't mind, please share with us some
    details on your problem that bother you. We will try our best to
    come up with a better solution for you.
    <br><br>
    Your discount code 15% is : <b>ALIAFFTERTRIAL</b>
    <br><br>
    Look forward to seeing you again. Have a great day!
@endSection

@section('mail_banner')
    {{ url('images/backend/email-banner-uninstall.jpg') }}
@endSection
