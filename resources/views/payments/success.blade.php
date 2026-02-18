{{-- Payment Success — AIVidCatalog18 --}}
@extends('layouts.app')

@section('title', __('payment_success'))

@section('content')
<div class="max-w-lg mx-auto px-4 py-16 text-center">
    <div class="bg-dark-900 border border-green-700 rounded-2xl p-12">
        <div class="text-6xl mb-6">🎉</div>
        <h1 class="text-3xl font-bold text-white mb-4">{{ __('payment_success') }}</h1>
        <p class="text-gray-400 mb-8">{{ __('payment_success_message') }}</p>
        <a href="{{ route('videos.index') }}" 
           class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-xl transition inline-block">
            {{ __('video_catalog') }} →
        </a>
    </div>
</div>
@endsection
