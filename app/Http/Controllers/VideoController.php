<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Video Controller (Public) — AIVidCatalog18
 *
 * Handles the public video catalog: browsing, filtering, and viewing.
 * Video files are never exposed directly — served via signed URLs.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 * All content is synthetic/AI-generated. No real persons depicted.
 */
class VideoController extends Controller
{
    /**
     * Display the public video catalog.
     *
     * GET /videos
     *
     * Supports filters: ?category=anime&premium=0&q=search
     */
    public function index(Request $request)
    {
        $query = Video::approved()->latest();

        // Filter by category
        if ($request->filled('category')) {
            $query->category($request->category);
        }

        // Filter by premium status
        if ($request->has('premium')) {
            $query->where('is_premium', (bool) $request->premium);
        }

        // Search by title (JSON field search)
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->whereRaw("JSON_EXTRACT(title, '$.en') LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("JSON_EXTRACT(title, '$.ru') LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("JSON_EXTRACT(title, '$.es') LIKE ?", ["%{$search}%"]);
            });
        }

        $videos = $query->paginate(12);

        // Get available categories for filter dropdown
        $categories = Video::approved()
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('videos.index', compact('videos', 'categories'));
    }

    /**
     * Display a single video page.
     *
     * GET /videos/{video}
     *
     * Shows video player (if accessible) or upgrade prompt (if premium).
     */
    public function show(Video $video)
    {
        // Only approved videos are publicly viewable
        if (!$video->isApproved() && (!auth()->check() || !auth()->user()->is_admin)) {
            abort(404);
        }

        $user = auth()->user();
        $canWatch = $video->isAccessibleBy($user);

        // Increment view counter
        $video->incrementViews();

        return view('videos.show', compact('video', 'canWatch'));
    }

    /**
     * Stream/serve video file via authenticated route.
     *
     * GET /videos/{video}/stream
     *
     * Security: Only authenticated users with proper access can stream.
     * Files are served from the private 'videos' disk.
     */
    public function stream(Video $video, Request $request)
    {
        $user = auth()->user();

        // Verify access
        if (!$video->isAccessibleBy($user)) {
            abort(403, __('Premium content requires an active subscription.'));
        }

        // Verify the video file exists
        if (!Storage::disk('videos')->exists($video->file_path)) {
            abort(404, __('Video file not found.'));
        }

        // Return the file as a streamed response with proper headers
        $filePath = Storage::disk('videos')->path($video->file_path);
        $mimeType = mime_content_type($filePath) ?: 'video/mp4';

        return response()->file($filePath, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline',
            'Cache-Control'       => 'private, max-age=3600',
            'Accept-Ranges'       => 'bytes',
        ]);
    }
}
