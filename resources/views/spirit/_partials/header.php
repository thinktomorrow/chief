<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Spirit - A design system that works for you</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../../../../public/favicon.ico" type="image/x-icon">

    <!-- hide vue elements until vue is loaded -->
    <style type="text/css">[v-cloak]{ display:none; }</style>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Andada|Montserrat:300">

    <!-- temp links when spirit is still inside chief -->
    <link rel="stylesheet" href="/chief-assets/back/css/main.css">
    <link rel="stylesheet" href="/chief-assets/spirit/css/layout.css">
    <link rel="stylesheet" href="/chief-assets/back/css/vendors/solarized-dark.css">

    <!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-5NGHP2D');</script>
	<!-- End Google Tag Manager -->

    <script src="/chief-assets/back/js/vendors/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5NGHP2D"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <main class="row" id="main">

        <?php include(__DIR__.'/sidebar.php'); ?>

        <?php

        $pagetitles = [
            'home' => 'Spirit design system',
            'icons' => 'Spirit icons',
            'settings' => 'Spirit settings',
            'colors' => 'Spirit colors',
            'elements' => 'Spirit elementen',
            'components' => 'Spirit componenten',
        ];

        $extracted = array_filter(explode("/",parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH)));
        $current_section = isset($extracted[2]) ? $extracted[2] : 'home';
//        $current_item = isset($extracted[3]) ? $extracted[3] : null;

        ?>

        <article class="column-10">
            <header class="hero squished-xl">
                <h1 class="title"><?= $pagetitles[$current_section]; ?></h1>
            </header>
            <div class="squished-xl">

