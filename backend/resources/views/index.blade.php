<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Recordable</title>

    <script
        src="{{ asset('js/app.js') }}"
        defer
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.2.0/dist/confetti.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@4.1.0/dist/echarts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-echarts@4.0.2"></script>

    <link
        rel="stylesheet"
        href="{{asset('css/app.css')}}"
    >

    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="{{asset('favicon/favicon-32x32.png')}}"
    >
    <link
        rel="icon"
        type="image/png"
        sizes="96x96"
        href="{{asset('favicon/favicon-96x96.png')}}"
    >
    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="{{asset('favicon/favicon-16x16.png')}}"
    >
    <link
        rel="manifest"
        href="{{asset('favicon/favicon/manifest.json')}}"
    >
    <meta
        name="msapplication-TileColor"
        content="#ffffff"
    >
    <meta
        name="msapplication-TileImage"
        content="/ms-icon-144x144.png"
    >
    <meta
        name="theme-color"
        content="#ffffff"
    >

</head>

<body>
    <div id="app"></div>
</body>

</html>
