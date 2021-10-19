<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Coin Exchange Mail')</title>

    <style>
        body {
            width: 100vw;
            position: relative;
            background: #F3F4F6;
            font-family: 'Source Sans Pro', sans-serif;
        }

        h3 {
            margin-bottom: 1em;
            font-size: 1.6em;
        }

        p {
            position: relative;
            margin-bottom: 2em;
            font-size: 1.2em;
        }
    </style>

    @yield('styles')

    <!-- Font connection -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;600&display=swap" rel="stylesheet">
</head>
<body>
    @yield('body')
</body>
</html>