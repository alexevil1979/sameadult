{{--
    Video Catalog — AIVidCatalog18
    Public catalog with filter/search, showing thumbnails and metadata.
    Strictly fictional AI-generated content — no illegal/prohibited material.
--}}
@extends('layouts.app')

@section('title', __('video_catalog'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">{{ __('video_catalog') }}</h1>
        <p class="text-gray-400 mt-2">{{ __('site_tagline') }}</p>
    </div>

    {{-- Filters --}}
    <div class="bg-dark-900 border border-gray-800 rounded-xl p-4 mb-8">
        <form action="{{ route('videos.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            {{-- Search --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm text-gray-400 mb-1">{{ __('search_videos') }}</label>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="{{ __('search_videos') }}"
                       class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm focus:ring-2 focus:ring-primary-500">
            </div>

            {{-- Category --}}
            <div class="min-w-[160px]">
                <label class="block text-sm text-gray-400 mb-1">{{ __('category') }}</label>
                <select name="category" class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm">
                    <option value="">{{ __('all_categories') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                            {{ ucfirst($cat) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Premium filter --}}
            <div class="min-w-[140px]">
                <label class="block text-sm text-gray-400 mb-1">{{ __('premium_access') }}</label>
                <select name="premium" class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm">
                    <option value="">{{ __('all_videos') }}</option>
                    <option value="1" {{ request('premium') === '1' ? 'selected' : '' }}>{{ __('premium_only') }}</option>
                    <option value="0" {{ request('premium') === '0' ? 'selected' : '' }}>{{ __('free_only') }}</option>
                </select>
            </div>

            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg text-sm transition">
                🔍
            </button>
        </form>
    </div>

    {{-- Video Grid --}}
    @if($videos->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($videos as $video)
                <a href="{{ route('videos.show', $video) }}" 
                   class="group bg-dark-900 border border-gray-800 rounded-xl overflow-hidden hover:border-primary-600 transition-all duration-300 hover:shadow-xl hover:shadow-primary-900/20">
                    
                    {{-- Thumbnail --}}
                    <div class="relative aspect-video bg-dark-800 overflow-hidden">
                        @if($video->thumbnail_path)
                            <img src="{{ $video->thumbnailUrl() }}" 
                                 alt="{{ $video->localizedTitle() }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl text-gray-600">🎬</div>
                        @endif

                        {{-- Duration badge --}}
                        <div class="absolute bottom-2 right-2 bg-black/80 text-white text-xs px-2 py-1 rounded">
                            {{ $video->formattedDuration() }}
                        </div>

                        {{-- Premium badge --}}
                        @if($video->is_premium)
                            <div class="absolute top-2 left-2 bg-yellow-500 text-black text-xs font-bold px-2 py-1 rounded">
                                ⭐ {{ __('premium_content') }}
                            </div>
                        @else
                            <div class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-1 rounded">
                                {{ __('free_content') }}
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-3">
                        <h3 class="text-white text-sm font-medium line-clamp-2 group-hover:text-primary-400 transition">
                            {{ $video->localizedTitle() }}
                        </h3>
                        <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                            <span>{{ ucfirst($video->category) }}</span>
                            <span>{{ number_format($video->views_count) }} {{ __('views') }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $videos->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <div class="text-5xl mb-4">📭</div>
            <p class="text-gray-400 text-lg">{{ __('no_videos_found') }}</p>
        </div>
    @endif
</div>
@endsection
