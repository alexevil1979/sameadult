<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Video;

/**
 * Landing Controller — AIVidCatalog18
 *
 * Serves the main promotional landing page with dynamic data:
 * featured videos, plan pricing, and platform statistics.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
class LandingController extends Controller
{
    /**
     * Display the landing page.
     *
     * GET /
     */
    public function index()
    {
        // Featured videos for the showcase section (latest 6 approved)
        $featuredVideos = Video::approved()
            ->latest()
            ->take(6)
            ->get();

        // Active plans for pricing section
        $plans = Plan::active()->orderBy('price_usd')->get();

        // Platform stats
        $stats = [
            'videos'      => Video::approved()->count(),
            'premium'     => Video::approved()->premium()->count(),
            'categories'  => Video::approved()->distinct('category')->count('category'),
        ];

        return view('landing', compact('featuredVideos', 'plans', 'stats'));
    }
}
