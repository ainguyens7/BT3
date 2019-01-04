@extends('emails.master')

@section('mail_content')
    You got a new comment from your customer!
    <br><br>
    Social proof from customer reviews is an important part of your business success. Don't let your customers wait, show them some responses.
    <br><br>
    - If it is a good one, approve and pin it to the top.<br>
    - If it is a bad one, hide it and send an apology email.
    <div style="background-color: #f2f2f2; padding: 10px 10px;">
        <div><strong>Author:</strong> {{ isset($data['author']) ?  $data['author'] : '' }}</div>
        <div><strong>Star:</strong> {{ isset($data['star']) ?  $data['star'] : '' }}</div>
        @if(isset($data['email']) && $data['email'] != '')
            <div><strong>Email:</strong> {{ $data['email'] }}</div>
        @endif
        <div><strong>Content:</strong> {{ isset($data['content']) ?  $data['content'] : '' }}</div>
    </div>

    <a href="{{ route('reviews.review_approve') }}" style="    display: block;
    width: 200px;
    background: #F63663;
    margin: 20px auto;
    padding: 10px 0;
    text-align: center;
    color: white;
    text-decoration: none;
    font-weight: bold;">
        Approve the review
    </a>
    Remember, you can always get more reviews by using Ali Reviews with just 1 click!
@endSection

@section('mail_banner')
    {{ cdn('images/backend/email-banner-new-review.jpg') }}
@endSection
