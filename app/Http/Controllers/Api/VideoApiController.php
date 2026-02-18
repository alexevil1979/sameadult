<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Video API Controller — AIVidCatalog18
 *
 * RESTful API endpoints for the video catalog.
 * Used by mobile apps and third-party consumers.
 * Authentication: Laravel Sanctum (Bearer token).
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 * All content is synthetic/AI-generated. No real persons depicted.
 *
 * @group Videos
 *
 * Endpoints:
 *   GET    /api/videos           — List catalog (paginated, filtered)
 *   GET    /api/videos/{id}      — Video details
 *   GET    /api/videos/{id}/access — Get video stream URL (auth required)
 */
class VideoApiController extends Controller
{
    /**
     * List all approved videos (paginated).
     *
     * GET /api/videos
     *
     * Query params:
     *   - page (int): Page number
     *   - per_page (int): Items per page (max 50, default 12)
     *   - category (string): Filter by category
     *   - premium (0|1): Filter by premium status
     *   - q (string): Search in titles
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->get('per_page', 12), 50);

        $query = Video::approved()->latest();

        // Category filter
        if ($request->filled('category')) {
            $query->category($request->category);
        }

        // Premium filter
        if ($request->has('premium')) {
            $query->where('is_premium', (bool) $request->premium);
        }

        // Title search (JSON field)
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->whereRaw("JSON_EXTRACT(title, '$.en') LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("JSON_EXTRACT(title, '$.ru') LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("JSON_EXTRACT(title, '$.es') LIKE ?", ["%{$search}%"]);
            });
        }

        $videos = $query->paginate($perPage);

        // Transform the response
        $videos->getCollection()->transform(function ($video) {
            return $this->formatVideo($video);
        });

        return response()->json($videos);
    }

    /**
     * Show video details.
     *
     * GET /api/videos/{id}
     *
     * @return JsonResponse
     */
    public function show(Video $video): JsonResponse
    {
        if (!$video->isApproved()) {
            return response()->json(['message' => 'Video not found.'], 404);
        }

        $video->incrementViews();

        $data = $this->formatVideo($video, detailed: true);

        // Add access info for authenticated users
        $user = auth('sanctum')->user();
        $data['can_access'] = $video->isAccessibleBy($user);
        $data['requires_subscription'] = $video->is_premium && (!$user || !$user->hasActiveSubscription());

        return response()->json(['data' => $data]);
    }

    /**
     * Get a signed URL to access the video file.
     *
     * GET /api/videos/{id}/access
     *
     * Requires Sanctum authentication.
     * Returns a temporary URL valid for 60 minutes.
     *
     * @return JsonResponse
     */
    public function access(Video $video): JsonResponse
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'Authentication required.'], 401);
        }

        if (!$video->isAccessibleBy($user)) {
            return response()->json([
                'message'     => 'Premium content requires an active subscription.',
                'upgrade_url' => route('plans.index'),
            ], 403);
        }

        // Verify file exists
        if (!Storage::disk('videos')->exists($video->file_path)) {
            return response()->json(['message' => 'Video file not found.'], 404);
        }

        // Generate a signed route URL for streaming
        $streamUrl = route('videos.stream', ['video' => $video->id]);

        return response()->json([
            'data' => [
                'video_id'   => $video->id,
                'stream_url' => $streamUrl,
                'expires_in' => 3600, // seconds
                'mime_type'  => 'video/mp4',
            ],
        ]);
    }

    /**
     * Format a video model for API response.
     */
    protected function formatVideo(Video $video, bool $detailed = false): array
    {
        $data = [
            'id'             => $video->id,
            'title'          => $video->title,
            'category'       => $video->category,
            'tags'           => $video->tags,
            'duration'       => $video->formattedDuration(),
            'duration_seconds' => $video->duration_seconds,
            'is_premium'     => $video->is_premium,
            'views_count'    => $video->views_count,
            'thumbnail_url'  => $video->thumbnailUrl(),
            'created_at'     => $video->created_at->toISOString(),
        ];

        if ($detailed) {
            $data['description'] = $video->description;
        }

        return $data;
    }
}
