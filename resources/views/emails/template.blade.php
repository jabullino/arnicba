<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $user->nombre }}</title>
</head>
<body>
    <p>Estimado/a {{ $user->nombre }} {{ $user->apellido }},</p>
    <p>{!! nl2br(e($content)) !!}</p>
    <p>Saludos,<br>{{ config('app.name') }}</p>
</body>
</html>
