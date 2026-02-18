<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;

/**
 * Video Policy — AIVidCatalog18
 *
 * Authorization logic for viewing, creating, updating, and deleting videos.
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
class VideoPolicy
{
    /**
     * Determine if the given video can be viewed by the user.
     *
     * Free videos: accessible by anyone (even guests).
     * Premium videos: only for users with active subscriptions.
     */
    public function view(?User $user, Video $video): bool
    {
        // Only approved videos are viewable in public
        if (!$video->isApproved()) {
            // Admins can view unapproved videos
            return $user && $user->is_admin;
        }

        // Free content is accessible to all
        if (!$video->is_premium) {
            return true;
        }

        // Premium content requires active subscription
        return $user && $user->hasActiveSubscription();
    }

    /**
     * Determine if the user can view the video list (catalog).
     * Everyone can browse the catalog (previews shown).
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can create videos (admin only).
     */
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine if the user can update the video (admin only).
     */
    public function update(User $user, Video $video): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine if the user can delete the video (admin only).
     */
    public function delete(User $user, Video $video): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine if the user can stream/download the video file.
     * Same as view, but explicitly for file access.
     */
    public function stream(?User $user, Video $video): bool
    {
        return $this->view($user, $video);
    }
}
