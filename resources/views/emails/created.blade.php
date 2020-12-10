<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>
        제목이지롱 : 
        {{ $article -> title}}
        <small>
            {{ $article->user->name }}
        </small>
    </h1>
    <hr>
    <p>
        {{ $article->content }}
        <small> {{ $article->created_at }}</small>
        <br>
        <br>
        <div style="text-align: center;">
            <img src="{{ $message->embed(storage_path('download.jpeg')) }}" alt="">
        </div>
    </p>
    <footer>
        이 메일은 {{ config('app.url') }}에서 보냈습니다.
    </footer>
</body>
</html>