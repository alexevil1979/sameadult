{{--
    Video Player Page — AIVidCatalog18
    Shows video player (if accessible) or upgrade prompt.
    Strictly fictional AI-generated content — no illegal/prohibited material.
--}}
@extends('layouts.app')

@section('title', $video->localizedTitle())

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    {{-- Back link --}}
    <a href="{{ route('videos.index') }}" class="text-gray-400 hover:text-white text-sm mb-6 inline-block">
        ← {{ __('back') }} {{ __('video_catalog') }}
    </a>

    {{-- Video Player --}}
    <div class="bg-dark-900 border border-gray-800 rounded-xl overflow-hidden">
        @if($canWatch)
            {{-- Accessible: Show video player --}}
            <div class="aspect-video bg-black">
                @auth
                    <video controls 
                           class="w-full h-full"
                           preload="metadata"
                           controlsList="nodownload">
                        <source src="{{ route('videos.stream', $video) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    {{-- Free video but not logged in — still show player --}}
                    @if(!$video->is_premium)
                        <div class="w-full h-full flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-gray-400 mb-4">{{ __('login') }} to watch this video</p>
                                <a href="{{ route('login') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg">{{ __('login') }}</a>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
        @else
            {{-- Not accessible: Show upgrade prompt --}}
            <div class="aspect-video bg-dark-800 flex items-center justify-center relative">
                {{-- Blurred thumbnail background --}}
                @if($video->thumbnail_path)
                    <img src="{{ $video->thumbnailUrl() }}" 
                         class="absolute inset-0 w-full h-full object-cover blur-xl opacity-30">
                @endif
                
                <div class="relative z-10 text-center p-8">
                    <div class="text-6xl mb-4">🔒</div>
                    <h2 class="text-2xl font-bold text-white mb-2">{{ __('premium_content') }}</h2>
                    <p class="text-gray-400 mb-6">{{ __('upgrade_to_watch') }}</p>
                    <a href="{{ route('plans.index') }}" 
                       class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold px-8 py-3 rounded-lg transition inline-block">
                        {{ __('subscribe_now') }}
                    </a>
                </div>
            </div>
        @endif

        {{-- Video Info --}}
        <div class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $video->localizedTitle() }}</h1>
                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-400">
                        <span>{{ number_format($video->views_count) }} {{ __('views') }}</span>
                        <span>{{ __('duration') }}: {{ $video->formattedDuration() }}</span>
                        <span>{{ __('category') }}: {{ ucfirst($video->category) }}</span>
                    </div>
                </div>

                @if($video->is_premium)
                    <span class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-sm font-medium">
                        ⭐ {{ __('premium_content') }}
                    </span>
                @else
                    <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm font-medium">
                        {{ __('free_content') }}
                    </span>
                @endif
            </div>

            {{-- Description --}}
            @if($video->localizedDescription())
                <div class="mt-4 text-gray-300 leading-relaxed border-t border-gray-800 pt-4">
                    {{ $video->localizedDescription() }}
                </div>
            @endif

            {{-- Tags --}}
            @if($video->tags && count($video->tags) > 0)
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($video->tags as $tag)
                        <span class="bg-dark-800 text-gray-400 text-xs px-3 py-1 rounded-full border border-gray-700">
                            #{{ $tag }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
