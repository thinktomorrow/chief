@php
    $title = isset($title) ? 'Chief • ' . $title : 'Chief';
@endphp

<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

<title>{{ $title }}</title>
<meta name="title" content="{{ $title }}">
<meta name="author" content="Think Tomorrow">
