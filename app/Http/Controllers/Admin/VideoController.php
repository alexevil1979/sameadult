<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

/**
 * Admin Video Controller — AIVidCatalog18
 *
 * CRUD operations for managing videos in the admin panel.
 * Only accessible by admin users (is_admin=true).
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 * All uploaded content must be AI-generated. No real persons permitted.
 */
class VideoController extends Controller
{
    /**
     * Display video management list.
     *
     * GET /admin/videos
     */
    public function index(Request $request)
    {
        $query = Video::latest();

        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->approved();
            } elseif ($request->status === 'pending') {
                $query->whereNull('approved_at');
            }
        }

        $videos = $query->paginate(20);

        return view('admin.videos.index', compact('videos'));
    }

    /**
     * Show video upload form.
     *
     * GET /admin/videos/create
     */
    public function create()
    {
        return view('admin.videos.create');
    }

    /**
     * Store a new video.
     *
     * POST /admin/videos
     *
     * Validates file type (mp4/webm), generates thumbnail,
     * stores video on private disk.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_en'      => 'required|string|max:255',
            'title_ru'      => 'nullable|string|max:255',
            'title_es'      => 'nullable|string|max:255',
            'description_en' => 'nullable|string|max:2000',
            'description_ru' => 'nullable|string|max:2000',
            'description_es' => 'nullable|string|max:2000',
            'category'       => 'required|string|max:50',
            'tags'           => 'nullable|string|max:500',
            'is_premium'     => 'boolean',
            'video_file'     => 'required|file|mimes:mp4,webm|max:512000', // 500MB max
            'thumbnail'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', // 5MB
            'duration_seconds' => 'nullable|integer|min:1',
        ]);

        // Store video file on private disk
        $videoPath = $request->file('video_file')->store('', 'videos');

        // Generate or store thumbnail
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $this->processThumbnail($request->file('thumbnail'));
        }

        // Build multilingual title/description
        $title = array_filter([
            'en' => $request->title_en,
            'ru' => $request->title_ru,
            'es' => $request->title_es,
        ]);

        $description = array_filter([
            'en' => $request->description_en,
            'ru' => $request->description_ru,
            'es' => $request->description_es,
        ]);

        // Parse tags
        $tags = $request->tags
            ? array_map('trim', explode(',', $request->tags))
            : [];

        $video = Video::create([
            'title'            => $title,
            'description'      => $description,
            'tags'             => $tags,
            'category'         => $request->category,
            'duration_seconds' => $request->duration_seconds ?? 0,
            'file_path'        => $videoPath,
            'thumbnail_path'   => $thumbnailPath,
            'is_premium'       => $request->boolean('is_premium'),
            'approved_at'      => null, // Requires manual approval
        ]);

        return redirect()->route('admin.videos.index')
            ->with('success', __('Video uploaded successfully. Awaiting approval.'));
    }

    /**
     * Show video edit form.
     *
     * GET /admin/videos/{video}/edit
     */
    public function edit(Video $video)
    {
        return view('admin.videos.edit', compact('video'));
    }

    /**
     * Update video metadata.
     *
     * PUT /admin/videos/{video}
     */
    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title_en'       => 'required|string|max:255',
            'title_ru'       => 'nullable|string|max:255',
            'title_es'       => 'nullable|string|max:255',
            'description_en' => 'nullable|string|max:2000',
            'description_ru' => 'nullable|string|max:2000',
            'description_es' => 'nullable|string|max:2000',
            'category'       => 'required|string|max:50',
            'tags'           => 'nullable|string|max:500',
            'is_premium'     => 'boolean',
            'thumbnail'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'duration_seconds' => 'nullable|integer|min:1',
        ]);

        $data = [
            'title' => array_filter([
                'en' => $request->title_en,
                'ru' => $request->title_ru,
                'es' => $request->title_es,
            ]),
            'description' => array_filter([
                'en' => $request->description_en,
                'ru' => $request->description_ru,
                'es' => $request->description_es,
            ]),
            'tags'             => $request->tags ? array_map('trim', explode(',', $request->tags)) : [],
            'category'         => $request->category,
            'is_premium'       => $request->boolean('is_premium'),
            'duration_seconds' => $request->duration_seconds ?? $video->duration_seconds,
        ];

        // Update thumbnail if provided
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($video->thumbnail_path) {
                Storage::disk('thumbnails')->delete($video->thumbnail_path);
            }
            $data['thumbnail_path'] = $this->processThumbnail($request->file('thumbnail'));
        }

        $video->update($data);

        return redirect()->route('admin.videos.index')
            ->with('success', __('Video updated successfully.'));
    }

    /**
     * Delete a video and its files.
     *
     * DELETE /admin/videos/{video}
     */
    public function destroy(Video $video)
    {
        // Delete video file from private storage
        if ($video->file_path) {
            Storage::disk('videos')->delete($video->file_path);
        }

        // Delete thumbnail from public storage
        if ($video->thumbnail_path) {
            Storage::disk('thumbnails')->delete($video->thumbnail_path);
        }

        $video->delete();

        return redirect()->route('admin.videos.index')
            ->with('success', __('Video deleted successfully.'));
    }

    /**
     * Approve a video for public display.
     *
     * POST /admin/videos/{video}/approve
     */
    public function approve(Video $video)
    {
        $video->update(['approved_at' => now()]);

        return back()->with('success', __('Video approved and now visible in the catalog.'));
    }

    /**
     * Process and store a thumbnail image.
     *
     * Resizes to 320x180 (16:9) and stores on the thumbnails disk.
     */
    protected function processThumbnail($file): string
    {
        $filename = uniqid('thumb_') . '.jpg';

        // Use Intervention Image to resize
        $image = Image::read($file->getRealPath());
        $image->cover(320, 180);

        // Save to thumbnails disk
        $path = Storage::disk('thumbnails')->path($filename);
        $image->toJpeg(quality: 80)->save($path);

        return $filename;
    }
}
