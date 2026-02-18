{{-- Admin Upload Video — AIVidCatalog18 --}}
@extends('layouts.app')

@section('title', __('upload_video'))

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-white">{{ __('upload_video') }}</h1>
        <a href="{{ route('admin.videos.index') }}" class="text-gray-400 hover:text-white text-sm">← {{ __('back') }}</a>
    </div>

    @if($errors->any())
        <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-lg mb-6 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Reminder: AI-generated content only --}}
    <div class="bg-blue-900/30 border border-blue-700 text-blue-300 px-4 py-3 rounded-lg mb-6 text-sm">
        ⚠️ Only upload AI-generated fictional content. No real persons. All content must comply with platform guidelines.
    </div>

    <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-dark-900 border border-gray-800 rounded-xl p-6 space-y-6">
            {{-- Titles (multilang) --}}
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">{{ __('title') }} (Multilingual)</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">English *</label>
                        <input type="text" name="title_en" value="{{ old('title_en') }}" required
                               class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Русский</label>
                        <input type="text" name="title_ru" value="{{ old('title_ru') }}"
                               class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Español</label>
                        <input type="text" name="title_es" value="{{ old('title_es') }}"
                               class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                    </div>
                </div>
            </div>

            {{-- Descriptions (multilang) --}}
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">{{ __('description') }} (Multilingual)</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">English</label>
                        <textarea name="description_en" rows="3"
                                  class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white">{{ old('description_en') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Русский</label>
                        <textarea name="description_ru" rows="3"
                                  class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white">{{ old('description_ru') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Español</label>
                        <textarea name="description_es" rows="3"
                                  class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white">{{ old('description_es') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Category & Tags --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">{{ __('category') }} *</label>
                    <select name="category" required
                            class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                        <option value="general">General</option>
                        <option value="fantasy">Fantasy</option>
                        <option value="anime">Anime</option>
                        <option value="roleplay">Roleplay</option>
                        <option value="artistic">Artistic</option>
                        <option value="cinematic">Cinematic</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">{{ __('tags') }} (comma-separated)</label>
                    <input type="text" name="tags" value="{{ old('tags') }}" placeholder="ai, fantasy, cinematic"
                           class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                </div>
            </div>

            {{-- Duration & Premium --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">{{ __('duration') }} (seconds)</label>
                    <input type="number" name="duration_seconds" value="{{ old('duration_seconds') }}" min="1"
                           class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="hidden" name="is_premium" value="0">
                        <input type="checkbox" name="is_premium" value="1" {{ old('is_premium') ? 'checked' : '' }}
                               class="w-5 h-5 rounded bg-dark-800 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                        <span class="text-gray-300">⭐ {{ __('premium_content') }}</span>
                    </label>
                </div>
            </div>

            {{-- File uploads --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">{{ __('video_file') }} * (MP4/WebM, max 500MB)</label>
                    <input type="file" name="video_file" accept="video/mp4,video/webm" required
                           class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white text-sm file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-primary-600 file:text-white hover:file:bg-primary-700">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">{{ __('thumbnail') }} (JPG/PNG/WebP, max 5MB)</label>
                    <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp"
                           class="w-full bg-dark-800 border border-gray-700 rounded-lg px-4 py-3 text-white text-sm file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-primary-600 file:text-white hover:file:bg-primary-700">
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.videos.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition">
                {{ __('cancel') }}
            </a>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-lg transition">
                {{ __('upload_video') }}
            </button>
        </div>
    </form>
</div>
@endsection
