<!DOCTYPE html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ env('APP_NAME')}}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
	<!-- <link rel="stylesheet" href="{{ asset('/css/font-awesome/css/font-awesome.min.css')}}"> -->
    @section('head')
        <meta name="csrf_token" content="{{ csrf_token() }}" />
     @endsection
     @vite(['resources/css/app.css','resources/sass/app.scss','resources/js/app.js'])
</head>
<body>
        @if (Auth::check())
            @php
            $user_auth_data = [
                'isLoggedin' => true,
                'user' =>  Auth::user(),
            ];
            @endphp
        @else
            @php
            $user_auth_data = [
                'isLoggedin' => false
            ];
            @endphp
        @endif
    <div id="app" class="app-body">
    
    </div>
    <script>
  
        window.winsdev = JSON.parse(atob('{{ base64_encode(json_encode($user_auth_data)) }}'));
    </script>


</body>
</html>