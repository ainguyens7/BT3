@extends('emails.master')

@section('mail_content')
    Hi <b>{{ !empty($data->shop_owner) ? $data->shop_owner : '' }}</b>,<br><br>

    Welcome on board! Glad to have you as a <b>Ali Reviews</b> user.<br><br>

    Just by 1-clicks, Ali Reviews will help to import reviews from AliExpress into your store in less than 3 minutes! Customer's reviews will create a social proof to your product and motivate your visitors to make decision quicker.<br><br>

    If you need any assistance, feel free to contact us. We're more than happy to support you.<br><br>

    Again, thank you for choosing us, hope you enjoy our apps.<br><br>
@endSection

@section('mail_banner')
    {{ cdn('images/backend/email-banner-install.jpg') }}
@endSection
