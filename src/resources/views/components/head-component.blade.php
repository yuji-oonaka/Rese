<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title')</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<script src="https://kit.fontawesome.com/9d85be4431.js" crossorigin="anonymous"></script>
@yield('css')