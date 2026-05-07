<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MMU's Club & Society</title>
</head>
<x-top-nav></x-top-nav>
<body>
    <h1>WELCOME TO MMU's CLUB & SOCIETY OFFICIAL WEBPAGE</h1>
    
    <a href="/navigation" class="btn">
        Find Clubs or Society!
    </a>

    <nav>
        @if (Route::has('login'))
    <div class="fixed top-0 right-0 p-6 text-right z-10">
        @auth
            <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900">Log in</a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900">Register</a>
            @endif
        @endauth
    </div>
@endif
<nav>
</body>
</html>