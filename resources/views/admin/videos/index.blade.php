{{-- Admin Video List — AIVidCatalog18 --}}
@extends('layouts.app')

@section('title', __('admin_videos'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-white">{{ __('admin_videos') }}</h1>
        <a href="{{ route('admin.videos.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg text-sm transition">
            + {{ __('upload_video') }}
        </a>
    </div>

    {{-- Status filter --}}
    <div class="flex space-x-4 mb-6">
        <a href="{{ route('admin.videos.index') }}" 
           class="px-4 py-2 rounded-lg text-sm {{ !request('status') ? 'bg-primary-600 text-white' : 'bg-dark-900 text-gray-400 hover:text-white' }}">
            All
        </a>
        <a href="{{ route('admin.videos.index', ['status' => 'approved']) }}" 
           class="px-4 py-2 rounded-lg text-sm {{ request('status') === 'approved' ? 'bg-green-600 text-white' : 'bg-dark-900 text-gray-400 hover:text-white' }}">
            {{ __('video_approved') }}
        </a>
        <a href="{{ route('admin.videos.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-lg text-sm {{ request('status') === 'pending' ? 'bg-yellow-600 text-white' : 'bg-dark-900 text-gray-400 hover:text-white' }}">
            {{ __('video_pending') }}
        </a>
    </div>

    {{-- Videos table --}}
    <div class="bg-dark-900 border border-gray-800 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-dark-800 text-gray-400 text-left">
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">{{ __('thumbnail') }}</th>
                    <th class="px-4 py-3">{{ __('title') }}</th>
                    <th class="px-4 py-3">{{ __('category') }}</th>
                    <th class="px-4 py-3">{{ __('premium_access') }}</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">{{ __('views') }}</th>
                    <th class="px-4 py-3">{{ __('actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($videos as $video)
                    <tr class="hover:bg-dark-800/50">
                        <td class="px-4 py-3 text-gray-400">{{ $video->id }}</td>
                        <td class="px-4 py-3">
                            @if($video->thumbnail_path)
                                <img src="{{ $video->thumbnailUrl() }}" class="w-16 h-9 object-cover rounded" alt="">
                            @else
                                <div class="w-16 h-9 bg-dark-800 rounded flex items-center justify-center text-gray-600">🎬</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-white">{{ $video->localizedTitle('en') }}</td>
                        <td class="px-4 py-3 text-gray-400">{{ ucfirst($video->category) }}</td>
                        <td class="px-4 py-3">
                            @if($video->is_premium)
                                <span class="text-yellow-400 text-xs">⭐ Premium</span>
                            @else
                                <span class="text-green-400 text-xs">Free</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($video->isApproved())
                                <span class="bg-green-900/50 text-green-400 text-xs px-2 py-1 rounded">{{ __('video_approved') }}</span>
                            @else
                                <span class="bg-yellow-900/50 text-yellow-400 text-xs px-2 py-1 rounded">{{ __('video_pending') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-400">{{ number_format($video->views_count) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-2">
                                @if(!$video->isApproved())
                                    <form action="{{ route('admin.videos.approve', $video) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-400 hover:text-green-300 text-xs" title="Approve">✓</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.videos.edit', $video) }}" class="text-primary-400 hover:text-primary-300 text-xs">Edit</a>
                                <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete this video?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">No videos yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $videos->withQueryString()->links() }}
    </div>
</div>
@endsection
