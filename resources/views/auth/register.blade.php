{{-- Register Form — AIVidCatalog18 --}}
@extends('layouts.app')

@section('title', __('register'))

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-dark-900 border border-gray-800 rounded-2xl p-8">
        <h1 class="text-2xl font-bold text-white text-center mb-8">{{ __('register') }}</h1>

        @if($errors->any())
            <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-lg mb-6 text-sm">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">{{ __('name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">{{ __('email') }}</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">{{ __('password') }}</label>
                <input type="password" name="password" id="password" required
                       class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">{{ __('confirm_password') }}</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <div>
                <label for="language" class="block text-sm font-medium text-gray-300 mb-2">{{ __('language') }}</label>
                <select name="language" id="language"
                        class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-500">
                    <option value="en">🇺🇸 {{ __('english') }}</option>
                    <option value="ru">🇷🇺 {{ __('russian') }}</option>
                    <option value="es">🇪🇸 {{ __('spanish') }}</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                {{ __('register') }}
            </button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-6">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-primary-400 hover:text-primary-300">{{ __('login') }}</a>
        </p>
    </div>
</div>
@endsection
