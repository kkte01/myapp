<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    @yield('style') 
    <title>라라벨 템플릿상속</title>
</head>
    <!-- @ yield 상속받는 자식의 가진 ('')안에 이름붙여진 내용을 이 위치에 넣겠다.  -->
<body>
    @yield('content')
    @yield('script')
</body>
</html>