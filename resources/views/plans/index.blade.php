{{--
    Subscription Plans — AIVidCatalog18
    Displays available plans with pricing and subscribe buttons.
    Strictly fictional AI-generated content — no illegal/prohibited material.
--}}
@extends('layouts.app')

@section('title', __('choose_plan'))

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12">
    {{-- Header --}}
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-white mb-4">{{ __('choose_plan') }}</h1>
        <p class="text-gray-400 text-lg max-w-2xl mx-auto">{{ __('plans_description') }}</p>
    </div>

    {{-- Current subscription status --}}
    @auth
        @if(auth()->user()->hasActiveSubscription())
            <div class="bg-green-900/30 border border-green-700 text-green-300 px-6 py-4 rounded-xl mb-8 text-center">
                ✓ {{ __('your_subscription_until', ['date' => auth()->user()->subscription_end->format('M d, Y')]) }}
            </div>
        @endif
    @endauth

    {{-- Plans Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">
        @foreach($plans as $plan)
            <div class="relative bg-dark-900 border {{ $plan->slug === 'premium' ? 'border-yellow-500' : 'border-gray-800' }} rounded-2xl p-8 hover:shadow-xl transition-all duration-300 {{ $plan->slug === 'premium' ? 'hover:shadow-yellow-900/20' : '' }}">
                
                {{-- Popular badge --}}
                @if($plan->slug === 'premium')
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-yellow-500 text-black text-xs font-bold px-4 py-1 rounded-full">
                        {{ __('most_popular') }}
                    </div>
                @endif

                {{-- Plan name --}}
                <h2 class="text-2xl font-bold text-white text-center mb-2">{{ $plan->name }}</h2>

                {{-- Price --}}
                <div class="text-center mb-6">
                    <span class="text-5xl font-extrabold text-white">{{ $plan->formattedPrice() }}</span>
                    <span class="text-gray-400 text-sm">{{ __('per_month') }}</span>
                </div>

                {{-- Duration --}}
                <p class="text-center text-gray-400 text-sm mb-6">
                    {{ __('days_access', ['days' => $plan->duration_days]) }}
                </p>

                {{-- Description --}}
                <p class="text-gray-400 text-sm text-center mb-8 min-h-[48px]">
                    {{ $plan->description }}
                </p>

                {{-- Subscribe button --}}
                @auth
                    @if(auth()->user()->hasActiveSubscription())
                        <button disabled class="w-full bg-gray-700 text-gray-400 font-semibold py-3 px-6 rounded-xl cursor-not-allowed">
                            {{ __('current_plan') }}
                        </button>
                    @else
                        <form action="{{ route('plans.buy', $plan) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full {{ $plan->slug === 'premium' ? 'bg-yellow-500 hover:bg-yellow-600 text-black' : 'bg-primary-600 hover:bg-primary-700 text-white' }} font-semibold py-3 px-6 rounded-xl transition duration-200">
                                {{ __('select_plan') }}
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" 
                       class="block text-center w-full {{ $plan->slug === 'premium' ? 'bg-yellow-500 hover:bg-yellow-600 text-black' : 'bg-primary-600 hover:bg-primary-700 text-white' }} font-semibold py-3 px-6 rounded-xl transition duration-200">
                        {{ __('login') }} & {{ __('subscribe_now') }}
                    </a>
                @endauth
            </div>
        @endforeach
    </div>

    {{-- Crypto payment note --}}
    <div class="text-center mt-12 text-sm text-gray-500">
        <p>💰 Payments processed securely via NOWPayments (cryptocurrency)</p>
        <p class="mt-1">Bitcoin, Ethereum, USDT, and 200+ other cryptocurrencies accepted</p>
    </div>
</div>
@endsection
