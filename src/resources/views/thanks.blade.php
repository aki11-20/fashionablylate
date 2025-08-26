<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashionably Late</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/thanks.css') }}" />
</head>
<body>
    <div class="thanks__content">
        <p class="thanks__text">Thank you</p>
        <div class="thanks__heading">
            <h3>お問い合わせありがとうございました</h3>
        </div>
        <div class="form__button">
            <a class="form__button-submit" href="{{ url('/') }}">HOME</a>
        </div>
    </div>
</body>
</html>