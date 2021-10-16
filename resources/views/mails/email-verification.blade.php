<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Verification</title>

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

        .button {
            width: 100%;
            position: relative;
            padding: 1em;
            background: #3B82F6;
            text-align: center;
            color: #FFFFFF !important;
            border-radius: 0.5em;
            font-size: 1.2em;
        }
    </style>

    <!-- Font connection -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;600&display=swap" rel="stylesheet">
</head>
<body>
    <h3>Вы успешно создали аккаунт Coin Exchange!</h3>

    <p>Чтобы получить доступ к своему аккаунту, вам необходимо подтвердить E-mail адрес.</p>

    <div class="button-container">
        <a href="{{ $verificationUrl }}" class="button">Подтвердить</a>
    </div>
</body>
</html>