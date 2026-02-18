{{-- Admin Dashboard — AIVidCatalog18 --}}
@extends('layouts.app')

@section('title', __('admin_dashboard'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-white">{{ __('admin_dashboard') }}</h1>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-dark-900 border border-gray-800 rounded-xl p-6">
            <div class="text-3xl font-bold text-white">{{ \App\Models\Video::count() }}</div>
            <div class="text-gray-400 text-sm mt-1">Total Videos</div>
        </div>
        <div class="bg-dark-900 border border-gray-800 rounded-xl p-6">
            <div class="text-3xl font-bold text-yellow-400">{{ \App\Models\Video::whereNull('approved_at')->count() }}</div>
            <div class="text-gray-400 text-sm mt-1">Pending Approval</div>
        </div>
        <div class="bg-dark-900 border border-gray-800 rounded-xl p-6">
            <div class="text-3xl font-bold text-green-400">{{ \App\Models\User::count() }}</div>
            <div class="text-gray-400 text-sm mt-1">Users</div>
        </div>
        <div class="bg-dark-900 border border-gray-800 rounded-xl p-6">
            <div class="text-3xl font-bold text-primary-400">{{ \App\Models\Subscription::active()->count() }}</div>
            <div class="text-gray-400 text-sm mt-1">Active Subscriptions</div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('admin.videos.index') }}" class="bg-dark-900 border border-gray-800 rounded-xl p-6 hover:border-primary-600 transition group">
            <div class="text-2xl mb-2">🎬</div>
            <h2 class="text-lg font-bold text-white group-hover:text-primary-400 transition">{{ __('admin_videos') }}</h2>
            <p class="text-gray-400 text-sm mt-1">Manage, approve, and upload videos</p>
        </a>
        <a href="{{ route('admin.videos.create') }}" class="bg-dark-900 border border-gray-800 rounded-xl p-6 hover:border-primary-600 transition group">
            <div class="text-2xl mb-2">⬆️</div>
            <h2 class="text-lg font-bold text-white group-hover:text-primary-400 transition">{{ __('upload_video') }}</h2>
            <p class="text-gray-400 text-sm mt-1">Upload new AI-generated content</p>
        </a>
    </div>
</div>
@endsection
