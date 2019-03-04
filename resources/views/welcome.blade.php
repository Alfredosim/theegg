<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Theegg - Test</title>

        <!-- Fonts 
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        -->
        <link href="{{ asset('css/app.css')}}" rel="stylesheet">
        <!-- Font Awesome v5.2.0 -->
        <link href="{{asset('css/all.css')}}" rel="stylesheet">
    </head>
    <body>
       <div id="app">
           <main-app/>
        </div>
       <script src="{{asset('js/app.js')}}"></script>
    </body>

@if (getenv('APP_ENV') === 'local')
    <script id="__bs_script__">//<![CDATA[
    document.write("<script async src='http://theegg.test:8000/browser-sync/browser-sync-client.js?v=2.26.3'><\/script>".replace("HOST", location.hostname));
        //]]>
    </script>
@endif
</html>
