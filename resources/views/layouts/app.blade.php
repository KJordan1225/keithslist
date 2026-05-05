<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'HomeConnect') }} - @yield('title', 'Find Local Pros')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    {{-- Navigation --}}
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-2xl font-extrabold text-green-600 tracking-tight">
                        HomeConnect
                    </a>
                    <a href="{{ route('businesses.search') }}" class="text-sm font-medium text-gray-600 hover:text-green-600">Find a Pro</a>
                    <a href="{{ route('service-requests.create') }}" class="text-sm font-medium text-gray-600 hover:text-green-600">Get Quotes</a>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        @if(auth()->user()->isProvider())
                            <a href="{{ route('dashboard.provider') }}" class="text-sm font-medium text-gray-600 hover:text-green-600">Dashboard</a>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-red-600 hover:text-red-800">Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-gray-800">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-green-600">Log in</a>
                        <a href="{{ route('register') }}" class="bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-green-700 transition">Join Free</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-6 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 px-6 py-3 text-sm">
            {{ session('info') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-3 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-gray-400 mt-16 py-10 text-sm">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div>
                <h4 class="text-white font-semibold mb-3">HomeConnect</h4>
                <p>Find trusted local professionals for any home service.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">For Homeowners</h4>
                <ul class="space-y-1">
                    <li><a href="{{ route('businesses.search') }}" class="hover:text-white">Find a Pro</a></li>
                    <li><a href="{{ route('service-requests.create') }}" class="hover:text-white">Get Quotes</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">For Pros</h4>
                <ul class="space-y-1">
                    <li><a href="{{ route('register') }}" class="hover:text-white">Join as a Pro</a></li>
                    <li><a href="{{ route('dashboard.provider') }}" class="hover:text-white">Pro Dashboard</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Company</h4>
                <ul class="space-y-1">
                    <li><a href="#" class="hover:text-white">About</a></li>
                    <li><a href="#" class="hover:text-white">Privacy</a></li>
                    <li><a href="#" class="hover:text-white">Terms</a></li>
                </ul>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
