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

    <link
        rel="stylesheet"
        href="{{asset('css/app.css')}}"
    >

    <!-- TODO: faviconをここに追加 -->

</head>

<body>
    <div id="app"></div>
</body>

</html>
