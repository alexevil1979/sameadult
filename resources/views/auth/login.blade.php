{{-- Login Form — AIVidCatalog18 --}}
@extends('layouts.app')

@section('title', __('login'))

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-dark-900 border border-gray-800 rounded-2xl p-8">
        <h1 class="text-2xl font-bold text-white text-center mb-8">{{ __('login') }}</h1>

        @if($errors->any())
            <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-lg mb-6 text-sm">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">{{ __('email') }}</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">{{ __('password') }}</label>
                <input type="password" name="password" id="password" required
                       class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-dark-800 border-gray-600 text-primary-600 focus:ring-primary-500">
                    <span class="text-sm text-gray-400">{{ __('remember_me') }}</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                {{ __('login') }}
            </button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-6">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-primary-400 hover:text-primary-300">{{ __('register') }}</a>
        </p>
    </div>
</div>
@endsection
