{{-- Payment Cancelled — AIVidCatalog18 --}}
@extends('layouts.app')

@section('title', __('payment_cancel'))

@section('content')
<div class="max-w-lg mx-auto px-4 py-16 text-center">
    <div class="bg-dark-900 border border-gray-800 rounded-2xl p-12">
        <div class="text-6xl mb-6">😔</div>
        <h1 class="text-3xl font-bold text-white mb-4">{{ __('payment_cancel') }}</h1>
        <p class="text-gray-400 mb-8">{{ __('payment_cancel_message') }}</p>
        <a href="{{ route('plans.index') }}" 
           class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-xl transition inline-block">
            {{ __('plans') }} →
        </a>
    </div>
</div>
@endsection
