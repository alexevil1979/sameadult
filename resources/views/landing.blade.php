{{--
    Landing Page — AIVidCatalog18
    Main promotional page: hero, features, showcase, pricing, FAQ, CTA.
    Strictly fictional AI-generated content — no illegal/prohibited material.
--}}
@extends('layouts.app')

@section('title', __('landing_hero_title'))

@section('content')

{{-- ====================================================================== --}}
{{-- HERO SECTION --}}
{{-- ====================================================================== --}}
<section class="relative overflow-hidden">
    {{-- Gradient background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-primary-900/30 via-dark-950 to-purple-900/20"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary-500/10 via-transparent to-transparent"></div>

    {{-- Animated grid pattern --}}
    <div class="absolute inset-0 opacity-5" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%239C92AC&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <div class="relative max-w-7xl mx-auto px-4 py-24 sm:py-32 lg:py-40">
        <div class="text-center max-w-4xl mx-auto">
            {{-- Badge --}}
            <div class="inline-flex items-center space-x-2 bg-primary-500/10 border border-primary-500/20 rounded-full px-4 py-1.5 mb-8">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-primary-300 text-sm font-medium">{{ __('landing_badge') }}</span>
            </div>

            {{-- Headline --}}
            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold tracking-tight leading-tight">
                <span class="text-white">{{ __('landing_hero_title') }}</span>
                <br>
                <span class="bg-gradient-to-r from-primary-400 via-blue-400 to-purple-400 bg-clip-text text-transparent">{{ __('landing_hero_highlight') }}</span>
            </h1>

            {{-- Subheadline --}}
            <p class="mt-6 text-lg sm:text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed">
                {{ __('landing_hero_subtitle') }}
            </p>

            {{-- CTA buttons --}}
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('videos.index') }}"
                   class="w-full sm:w-auto bg-primary-600 hover:bg-primary-500 text-white font-bold text-lg px-8 py-4 rounded-xl transition-all duration-300 hover:shadow-xl hover:shadow-primary-500/25 hover:-translate-y-0.5">
                    {{ __('landing_cta_browse') }}
                    <span class="ml-2">→</span>
                </a>
                <a href="{{ route('plans.index') }}"
                   class="w-full sm:w-auto bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 text-white font-semibold text-lg px-8 py-4 rounded-xl transition-all duration-300">
                    {{ __('landing_cta_plans') }}
                </a>
            </div>

            {{-- Trust indicators --}}
            <div class="mt-12 flex flex-wrap items-center justify-center gap-8 text-gray-500 text-sm">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ __('landing_trust_ai') }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ __('landing_trust_crypto') }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ __('landing_trust_privacy') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Decorative bottom wave --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 60" class="w-full h-auto text-dark-950 fill-current"><path d="M0,40 C360,80 720,0 1080,40 C1260,60 1380,40 1440,40 L1440,60 L0,60 Z"></path></svg>
    </div>
</section>

{{-- ====================================================================== --}}
{{-- STATS BAR --}}
{{-- ====================================================================== --}}
<section class="bg-dark-900/50 border-y border-gray-800/50 py-8">
    <div class="max-w-5xl mx-auto px-4">
        <div class="grid grid-cols-3 gap-8 text-center">
            <div>
                <div class="text-3xl sm:text-4xl font-extrabold text-white">{{ $stats['videos'] ?: '100+' }}</div>
                <div class="text-sm text-gray-400 mt-1">{{ __('landing_stat_videos') }}</div>
            </div>
            <div>
                <div class="text-3xl sm:text-4xl font-extrabold text-yellow-400">{{ $stats['premium'] ?: '50+' }}</div>
                <div class="text-sm text-gray-400 mt-1">{{ __('landing_stat_premium') }}</div>
            </div>
            <div>
                <div class="text-3xl sm:text-4xl font-extrabold text-purple-400">{{ $stats['categories'] ?: '6' }}</div>
                <div class="text-sm text-gray-400 mt-1">{{ __('landing_stat_categories') }}</div>
            </div>
        </div>
    </div>
</section>

{{-- ====================================================================== --}}
{{-- FEATURES SECTION --}}
{{-- ====================================================================== --}}
<section class="py-20 sm:py-28">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-white">{{ __('landing_features_title') }}</h2>
            <p class="text-gray-400 mt-4 max-w-xl mx-auto">{{ __('landing_features_subtitle') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {{-- Feature 1: AI Generated --}}
            <div class="bg-dark-900 border border-gray-800 rounded-2xl p-8 hover:border-primary-600/50 transition-all duration-300 group">
                <div class="w-14 h-14 bg-primary-500/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-primary-500/20 transition">
                    <span class="text-3xl">🤖</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">{{ __('landing_feat1_title') }}</h3>
                <p class="text-gray-400 leading-relaxed">{{ __('landing_feat1_desc') }}</p>
            </div>

            {{-- Feature 2: Premium Quality --}}
            <div class="bg-dark-900 border border-gray-800 rounded-2xl p-8 hover:border-yellow-500/50 transition-all duration-300 group">
                <div class="w-14 h-14 bg-yellow-500/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-yellow-500/20 transition">
                    <span class="text-3xl">⭐</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">{{ __('landing_feat2_title') }}</h3>
                <p class="text-gray-400 leading-relaxed">{{ __('landing_feat2_desc') }}</p>
            </div>

            {{-- Feature 3: Crypto Payments --}}
            <div class="bg-dark-900 border border-gray-800 rounded-2xl p-8 hover:border-green-500/50 transition-all duration-300 group">
                <div class="w-14 h-14 bg-green-500/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-green-500/20 transition">
                    <span class="text-3xl">💰</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">{{ __('landing_feat3_title') }}</h3>
                <p class="text-gray-400 leading-relaxed">{{ __('landing_feat3_desc') }}</p>
            </div>

            {{-- Feature 4: Privacy --}}
            <div class="bg-dark-900 border border-gray-800 rounded-2xl p-8 hover:border-purple-500/50 transition-all duration-300 group">
                <div class="w-14 h-14 bg-purple-500/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-purple-500/20 transition">
                    <span class="text-3xl">🔒</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">{{ __('landing_feat4_title') }}</h3>
                <p class="text-gray-400 leading-relaxed">{{ __('landing_feat4_desc') }}</p>
            </div>

            {{-- Feature 5: Multi-language --}}
            <div class="bg-dark-900 border border-gray-800 rounded-2xl p-8 hover:border-blue-500/50 transition-all duration-300 group">
                <div class="w-14 h-14 bg-blue-500/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-blue-500/20 transition">
                    <span class="text-3xl">🌍</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">{{ __('landing_feat5_title') }}</h3>
                <p class="text-gray-400 leading-relaxed">{{ __('landing_feat5_desc') }}</p>
            </div>

            {{-- Feature 6: Mobile App --}}
            <div class="bg-dark-900 border border-gray-800 rounded-2xl p-8 hover:border-pink-500/50 transition-all duration-300 group">
                <div class="w-14 h-14 bg-pink-500/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-pink-500/20 transition">
                    <span class="text-3xl">📱</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">{{ __('landing_feat6_title') }}</h3>
                <p class="text-gray-400 leading-relaxed">{{ __('landing_feat6_desc') }}</p>
            </div>
        </div>
    </div>
</section>

{{-- ====================================================================== --}}
{{-- VIDEO SHOWCASE --}}
{{-- ====================================================================== --}}
@if($featuredVideos->count() > 0)
<section class="py-20 bg-dark-900/30">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-10">
            <div>
                <h2 class="text-3xl sm:text-4xl font-bold text-white">{{ __('landing_showcase_title') }}</h2>
                <p class="text-gray-400 mt-2">{{ __('landing_showcase_subtitle') }}</p>
            </div>
            <a href="{{ route('videos.index') }}" class="hidden sm:inline-flex text-primary-400 hover:text-primary-300 font-medium transition">
                {{ __('landing_showcase_viewall') }} →
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
            @foreach($featuredVideos as $video)
                <a href="{{ route('videos.show', $video) }}" class="group relative aspect-video rounded-xl overflow-hidden bg-dark-800 border border-gray-800 hover:border-primary-500/50 transition-all duration-300">
                    @if($video->thumbnail_path)
                        <img src="{{ $video->thumbnailUrl() }}" alt="{{ $video->localizedTitle() }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-5xl text-gray-700 group-hover:text-gray-600">🎬</div>
                    @endif

                    {{-- Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                    {{-- Play button --}}
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center border border-white/30">
                            <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                        </div>
                    </div>

                    {{-- Info overlay --}}
                    <div class="absolute bottom-0 left-0 right-0 p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <h3 class="text-white text-sm font-medium truncate">{{ $video->localizedTitle() }}</h3>
                        <div class="flex items-center space-x-3 mt-1 text-xs text-gray-300">
                            <span>{{ $video->formattedDuration() }}</span>
                            <span>{{ number_format($video->views_count) }} {{ __('views') }}</span>
                        </div>
                    </div>

                    {{-- Badges --}}
                    @if($video->is_premium)
                        <div class="absolute top-2 left-2 bg-yellow-500 text-black text-[10px] font-bold px-2 py-0.5 rounded">⭐ PREMIUM</div>
                    @endif
                    <div class="absolute bottom-2 right-2 bg-black/70 text-white text-[10px] px-1.5 py-0.5 rounded">{{ $video->formattedDuration() }}</div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-8 sm:hidden">
            <a href="{{ route('videos.index') }}" class="text-primary-400 hover:text-primary-300 font-medium">{{ __('landing_showcase_viewall') }} →</a>
        </div>
    </div>
</section>
@endif

{{-- ====================================================================== --}}
{{-- HOW IT WORKS --}}
{{-- ====================================================================== --}}
<section class="py-20 sm:py-28">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-white">{{ __('landing_how_title') }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['step' => '01', 'icon' => '📝', 'title' => __('landing_how1_title'), 'desc' => __('landing_how1_desc')],
                ['step' => '02', 'icon' => '💳', 'title' => __('landing_how2_title'), 'desc' => __('landing_how2_desc')],
                ['step' => '03', 'icon' => '🎬', 'title' => __('landing_how3_title'), 'desc' => __('landing_how3_desc')],
            ] as $item)
                <div class="text-center relative">
                    <div class="text-6xl font-extrabold text-dark-800 absolute -top-4 left-1/2 -translate-x-1/2 select-none">{{ $item['step'] }}</div>
                    <div class="relative z-10">
                        <div class="text-4xl mb-4">{{ $item['icon'] }}</div>
                        <h3 class="text-lg font-bold text-white mb-2">{{ $item['title'] }}</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ====================================================================== --}}
{{-- PRICING SECTION --}}
{{-- ====================================================================== --}}
<section class="py-20 sm:py-28 bg-dark-900/30">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-white">{{ __('choose_plan') }}</h2>
            <p class="text-gray-400 mt-4 max-w-xl mx-auto">{{ __('plans_description') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">
            @foreach($plans as $plan)
                <div class="relative bg-dark-900 border {{ $plan->slug === 'premium' ? 'border-yellow-500 shadow-xl shadow-yellow-500/5' : 'border-gray-800' }} rounded-2xl p-8 hover:shadow-2xl transition-all duration-300">
                    @if($plan->slug === 'premium')
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-yellow-500 text-black text-xs font-bold px-4 py-1 rounded-full">{{ __('most_popular') }}</div>
                    @endif

                    <h3 class="text-2xl font-bold text-white text-center">{{ $plan->name }}</h3>
                    <div class="text-center mt-4">
                        <span class="text-5xl font-extrabold text-white">{{ $plan->formattedPrice() }}</span>
                        <span class="text-gray-400 text-sm">{{ __('per_month') }}</span>
                    </div>
                    <p class="text-gray-400 text-center text-sm mt-2">{{ __('days_access', ['days' => $plan->duration_days]) }}</p>
                    <p class="text-gray-400 text-center text-sm mt-4 min-h-[48px]">{{ $plan->description }}</p>

                    <div class="mt-8">
                        @auth
                            <form action="{{ route('plans.buy', $plan) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full {{ $plan->slug === 'premium' ? 'bg-yellow-500 hover:bg-yellow-400 text-black' : 'bg-primary-600 hover:bg-primary-500 text-white' }} font-bold py-3 px-6 rounded-xl transition">
                                    {{ __('select_plan') }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('register') }}" class="block text-center w-full {{ $plan->slug === 'premium' ? 'bg-yellow-500 hover:bg-yellow-400 text-black' : 'bg-primary-600 hover:bg-primary-500 text-white' }} font-bold py-3 px-6 rounded-xl transition">
                                {{ __('subscribe_now') }}
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <p class="text-center text-gray-500 text-sm mt-8">💰 {{ __('landing_crypto_note') }}</p>
    </div>
</section>

{{-- ====================================================================== --}}
{{-- FAQ --}}
{{-- ====================================================================== --}}
<section class="py-20 sm:py-28">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-3xl sm:text-4xl font-bold text-white text-center mb-12">{{ __('landing_faq_title') }}</h2>

        <div class="space-y-4">
            @foreach([
                ['q' => __('landing_faq1_q'), 'a' => __('landing_faq1_a')],
                ['q' => __('landing_faq2_q'), 'a' => __('landing_faq2_a')],
                ['q' => __('landing_faq3_q'), 'a' => __('landing_faq3_a')],
                ['q' => __('landing_faq4_q'), 'a' => __('landing_faq4_a')],
            ] as $faq)
                <details class="bg-dark-900 border border-gray-800 rounded-xl group">
                    <summary class="flex items-center justify-between cursor-pointer px-6 py-5 text-white font-medium hover:text-primary-400 transition list-none">
                        <span>{{ $faq['q'] }}</span>
                        <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </summary>
                    <div class="px-6 pb-5 text-gray-400 text-sm leading-relaxed border-t border-gray-800 pt-4">
                        {{ $faq['a'] }}
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>

{{-- ====================================================================== --}}
{{-- FINAL CTA --}}
{{-- ====================================================================== --}}
<section class="py-20 sm:py-28 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-primary-900/20 to-purple-900/20"></div>
    <div class="relative max-w-3xl mx-auto px-4 text-center">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight">
            {{ __('landing_final_title') }}
        </h2>
        <p class="text-gray-400 text-lg mt-6 max-w-xl mx-auto">{{ __('landing_final_subtitle') }}</p>

        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            @guest
                <a href="{{ route('register') }}" class="w-full sm:w-auto bg-primary-600 hover:bg-primary-500 text-white font-bold text-lg px-10 py-4 rounded-xl transition-all hover:shadow-xl hover:shadow-primary-500/25">
                    {{ __('landing_final_cta') }}
                </a>
            @else
                <a href="{{ route('videos.index') }}" class="w-full sm:w-auto bg-primary-600 hover:bg-primary-500 text-white font-bold text-lg px-10 py-4 rounded-xl transition-all hover:shadow-xl hover:shadow-primary-500/25">
                    {{ __('landing_cta_browse') }}
                </a>
            @endguest
        </div>

        <p class="text-gray-600 text-xs mt-8">{{ __('site_disclaimer') }}</p>
    </div>
</section>

@endsection
