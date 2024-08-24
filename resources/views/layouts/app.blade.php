<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Link Harvester')</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet">
</head>
<body>

<!-- Header Section -->
@include('layouts.header')

<div class="container my-4">
    <!-- Response message section -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')

</div>

<!-- Footer Section -->
@include('layouts.footer')

</body>
</html>
