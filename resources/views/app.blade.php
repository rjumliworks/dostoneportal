<!DOCTYPE html>
<html lang="en" data-theme="default" >
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title inertia>DOST-IX</title>
        <meta name="description" content="OneApp - Unified Information Management System integrating multiple DOST information systems into one centralized platform.">
        <meta name="keywords" content="DOST, OneApp, Unified Information Management System">
        <meta name="author" content="Krad">
        <meta property="og:title" content="OneApp - Unified Information Management System">
        <meta property="og:description" content="A unified platform that brings together multiple DOST information systems into one centralized application.">
        <meta property="og:type" content="website">
        <meta property="og:image" content="URL to the template's logo or featured image">
        <meta property="og:url" content="URL to the template's webpage">
        <meta name="twitter:card" content="summary_large_image">
        <link rel="shortcut icon" href="{{ URL::asset('images/favicon.ico') }}">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/1.8.349/pdf.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
        @vite(['resources/js/app.js'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
