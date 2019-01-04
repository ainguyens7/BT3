<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta http-equiv="cache-control" content="no-cache" />

<title>{{ isset($page_title) ? $page_title : 'Dashboard' }} | Alireviews</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="shortcut icon" type="image/x-icon" href="{{ url('favicon.ico') }}" />
<link href="https://fonts.googleapis.com/css?family=Raleway:400,600,700" rel="stylesheet">
<link href="{{ URL::asset('css/backend/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/backend/jquery.mCustomScrollbar.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/backend/bootstrap-multiselect.css') }}" rel="stylesheet">
<link href="{{ mix_cdn('css/backend/main.css') }}" rel="stylesheet">
<link href="{{ mix_cdn('css/backend/extension-chrome.css') }}" rel="stylesheet">
<script>
    var appUrl = "{{ url('') }}";
    var extensionIdDefine = "{{ env('EXTENSION_ID') }}";
</script>

<link rel="chrome-webstore-item" href="https://chrome.google.com/webstore/detail/bbaogjaeflnjolejjcpceoapngapnbaj">