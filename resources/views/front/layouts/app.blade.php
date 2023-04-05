<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('metaTitle', config('app.name')) - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('metaDescription', config('app.name')) - {{ config('app.name') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="assets/imgs/theme/athena-favicon.svg">
    <!-- Theme CSS  -->
    <link rel="stylesheet" href="{{ asset('front/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('front/assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('front/assets/css/widgets.css') }}">
    <link rel="stylesheet" href="{{ asset('front/assets/css/responsive.css') }}">

    @yield('styles')
</head>

<body>
    <div class="scroll-progress bg-dark"></div>
    <!-- Start Preloader -->
    <div class="preloader text-center">
        <div class="gooey">
            <span class="dot"></span>
            <div class="dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>

    @include('front.layouts.sidebar')

    @include('front.layouts.header')

    @include('front.layouts.search')

    <!-- Start Main content -->
    @yield('content')
    <!-- End Main content -->

    @include('front.layouts.footer')
</body>

</html>