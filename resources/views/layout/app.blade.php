<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Laravel</title>
    <link href="{{asset('css/app.css')}}" rel="stylesheet"/>
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">--}}
</head>
<body>
<div class="container">
    @yield('container')
</div>
<script>
    var BASE_URL = "{{url('/')}}";
</script>
<script src="{{asset('js/app.js')}}" type="text/javascript"></script>
</body>
</html>
