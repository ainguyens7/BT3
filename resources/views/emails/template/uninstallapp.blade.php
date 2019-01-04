@extends('emails.master')

@section('mail_content')
    Hi <b>{{ !empty($data->shop_owner) ? $data->shop_owner : '' }}</b>,<br>
    Have you just stopped using our app :(
    <br><br>
    We are really sorry if your using experience with our app was not
    what you expected. If you don't mind, please share with us some
    details on your problem that bother you. We will try our best to
    come up with a better solution for you.
    Remember, we have <a href="https://help.fireapps.io/welcome-to-fireapps/get-started/payment-refund-policy">Refund Policy</a> so be sure to check it to claim your money back.
    <br><br>
    Look forward to seeing you again. Have a great day!
@endSection

@section('mail_banner')
    {{ cdn('images/backend/email-banner-uninstall.jpg') }}
@endSection
