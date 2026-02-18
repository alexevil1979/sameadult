{{-- 
    Main Layout — AIVidCatalog18
    Strictly fictional AI-generated content — no illegal/prohibited material.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ __('site_tagline') }}">
    
    <title>@yield('title', __('site_name')) — {{ __('site_name') }}</title>

    <!-- Tailwind CSS via CDN (replace with Vite build in production) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd', 300: '#7dd3fc', 400: '#38bdf8', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1', 800: '#075985', 900: '#0c4a6e' },
                        dark: { 50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0', 700: '#334155', 800: '#1e293b', 900: '#0f172a', 950: '#020617' }
                    }
                }
            }
        }
    </script>
    
    @stack('styles')
</head>
<body class="bg-dark-950 text-gray-100 min-h-screen flex flex-col">

    {{-- Navigation Bar --}}
    <nav class="bg-dark-900 border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-primary-400 hover:text-primary-300 transition">
                        🎬 {{ __('site_name') }}
                    </a>
                    <a href="{{ route('videos.index') }}" class="text-gray-300 hover:text-white transition text-sm">
                        {{ __('videos') }}
                    </a>
                    <a href="{{ route('plans.index') }}" class="text-gray-300 hover:text-white transition text-sm">
                        {{ __('plans') }}
                    </a>
                </div>

                {{-- Right side: Auth + Lang --}}
                <div class="flex items-center space-x-4">
                    {{-- Language Switcher --}}
                    <div class="relative group">
                        <button class="text-gray-400 hover:text-white text-sm flex items-center space-x-1">
                            <span>🌐</span>
                            <span>{{ strtoupper(app()->getLocale()) }}</span>
                        </button>
                        <div class="absolute right-0 mt-2 w-32 bg-dark-800 border border-gray-700 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="{{ route('locale.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-dark-700 hover:text-white rounded-t-lg">🇺🇸 English</a>
                            <a href="{{ route('locale.switch', 'ru') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-dark-700 hover:text-white">🇷🇺 Русский</a>
                            <a href="{{ route('locale.switch', 'es') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-dark-700 hover:text-white rounded-b-lg">🇪🇸 Español</a>
                        </div>
                    </div>

                    {{-- Auth Links --}}
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white text-sm transition">{{ __('login') }}</a>
                        <a href="{{ route('register') }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm px-4 py-2 rounded-lg transition">{{ __('register') }}</a>
                    @else
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="text-yellow-400 hover:text-yellow-300 text-sm transition">{{ __('admin_panel') }}</a>
                        @endif

                        @if(auth()->user()->hasActiveSubscription())
                            <span class="text-green-400 text-xs">✓ {{ __('premium_access') }}</span>
                        @endif

                        <span class="text-gray-400 text-sm">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-400 text-sm transition">{{ __('logout') }}</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-900/50 border border-green-700 text-green-300 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-yellow-900/50 border border-yellow-700 text-yellow-300 px-4 py-3 rounded-lg">
                {{ session('warning') }}
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-blue-900/50 border border-blue-700 text-blue-300 px-4 py-3 rounded-lg">
                {{ session('info') }}
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-dark-900 border-t border-gray-800 mt-12">
        <div class="max-w-7xl mx-auto px-4 py-8 text-center text-gray-500 text-sm">
            <p>{{ __('copyright', ['year' => date('Y')]) }}</p>
            <p class="mt-2 text-xs text-gray-600">{{ __('site_disclaimer') }}</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
