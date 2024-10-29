<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-adsense-account" content="{{ env('GOOGLE_ADSENSE_ACCOUNT') }}" />

        @vite(['resources/js/app.js', 'resources/css/app.css'])
        @inertiaHead
    </head>
    <body class="max-w-screen-md mx-auto min-h-screen bg-white">
        @inertia


        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

        <!-- Font Awesome 6 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    </body>
</html>
