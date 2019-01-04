@extends('layout.dashboard',['page_title' => 'Product not found'])


@section('body_content')
    <div class="wrapper-space">
        <p class="empty-space">
            <i class="material-icons">speaker_notes_off</i> <br>
            <span class="mar-b-25">{{ $message }}</span>
        </p>
    </div>
@endsection