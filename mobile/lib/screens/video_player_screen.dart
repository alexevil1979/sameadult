import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:video_player/video_player.dart';
import 'package:chewie/chewie.dart';
import '../config/theme.dart';
import '../models/video.dart';
import '../services/api_service.dart';
import '../services/auth_service.dart';
import '../l10n/app_localizations.dart';

/// Video Player Screen — AIVidCatalog18
///
/// Displays video details and player. For premium content,
/// shows upgrade prompt if user is not subscribed.
///
/// Strictly fictional AI-generated content — no illegal/prohibited material.
/// All content is synthetic. No real persons depicted.

class VideoPlayerScreen extends StatefulWidget {
  final int videoId;

  const VideoPlayerScreen({super.key, required this.videoId});

  @override
  State<VideoPlayerScreen> createState() => _VideoPlayerScreenState();
}

class _VideoPlayerScreenState extends State<VideoPlayerScreen> {
  Video? _video;
  bool _isLoading = true;
  String? _streamUrl;
  String? _error;

  VideoPlayerController? _videoController;
  ChewieController? _chewieController;

  @override
  void initState() {
    super.initState();
    _loadVideo();
  }

  @override
  void dispose() {
    _chewieController?.dispose();
    _videoController?.dispose();
    super.dispose();
  }

  Future<void> _loadVideo() async {
    try {
      final api = context.read<ApiService>();
      final response = await api.getVideo(widget.videoId);
      final videoData = response.data['data'] as Map<String, dynamic>;

      setState(() {
        _video = Video.fromJson(videoData);
        _isLoading = false;
      });

      // If user can access, get stream URL
      if (_video!.canAccess == true) {
        await _loadStreamUrl();
      }
    } catch (e) {
      setState(() {
        _error = 'Failed to load video';
        _isLoading = false;
      });
    }
  }

  Future<void> _loadStreamUrl() async {
    final auth = context.read<AuthService>();
    if (!auth.isAuthenticated) return;

    try {
      final api = context.read<ApiService>();
      final response = await api.getVideoAccess(widget.videoId);
      final data = response.data['data'] as Map<String, dynamic>;
      _streamUrl = data['stream_url'] as String?;

      if (_streamUrl != null) {
        _initializePlayer();
      }
    } catch (e) {
      // User might not have access — that's OK
    }
  }

  void _initializePlayer() {
    if (_streamUrl == null) return;

    _videoController = VideoPlayerController.networkUrl(
      Uri.parse(_streamUrl!),
    );

    _chewieController = ChewieController(
      videoPlayerController: _videoController!,
      autoPlay: false,
      looping: false,
      aspectRatio: 16 / 9,
      allowFullScreen: true,
      allowMuting: true,
      showControls: true,
      materialProgressColors: ChewieProgressColors(
        playedColor: AppTheme.primary,
        handleColor: AppTheme.primary,
        bufferedColor: AppTheme.primary.withOpacity(0.3),
        backgroundColor: AppTheme.border,
      ),
    );

    setState(() {});
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final locale = context.watch<AuthService>().currentLocale;

    return Scaffold(
      appBar: AppBar(
        title: Text(_video?.localizedTitle(locale) ?? l10n.videos),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _error != null
              ? Center(
                  child: Text(_error!,
                      style: const TextStyle(color: AppTheme.error)))
              : _buildContent(l10n, locale),
    );
  }

  Widget _buildContent(AppLocalizations l10n, String locale) {
    final video = _video!;
    final auth = context.watch<AuthService>();

    return SingleChildScrollView(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Video Player or Lock Screen
          AspectRatio(
            aspectRatio: 16 / 9,
            child: video.canAccess == true && _chewieController != null
                ? Chewie(controller: _chewieController!)
                : _buildLockedOverlay(l10n, video),
          ),

          // Video Info
          Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Title
                Text(
                  video.localizedTitle(locale),
                  style: const TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                    color: AppTheme.textPrimary,
                  ),
                ),
                const SizedBox(height: 8),

                // Meta row
                Row(
                  children: [
                    _MetaChip(
                        icon: Icons.visibility,
                        text: '${video.formattedViews} ${l10n.views}'),
                    const SizedBox(width: 12),
                    _MetaChip(
                        icon: Icons.timer, text: video.duration),
                    const SizedBox(width: 12),
                    _MetaChip(
                        icon: Icons.category,
                        text: video.category[0].toUpperCase() +
                            video.category.substring(1)),
                  ],
                ),
                const SizedBox(height: 12),

                // Premium badge
                if (video.isPremium)
                  Container(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(
                      color: AppTheme.premium.withOpacity(0.15),
                      borderRadius: BorderRadius.circular(20),
                      border: Border.all(
                          color: AppTheme.premium.withOpacity(0.3)),
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Icon(Icons.star,
                            color: AppTheme.premium, size: 16),
                        const SizedBox(width: 4),
                        Text(l10n.premiumContent,
                            style: const TextStyle(
                                color: AppTheme.premium, fontSize: 12)),
                      ],
                    ),
                  ),
                const SizedBox(height: 16),

                // Description
                if (video.localizedDescription(locale).isNotEmpty) ...[
                  const Divider(color: AppTheme.border),
                  const SizedBox(height: 8),
                  Text(
                    video.localizedDescription(locale),
                    style: const TextStyle(
                      color: AppTheme.textSecondary,
                      fontSize: 14,
                      height: 1.5,
                    ),
                  ),
                ],

                // Tags
                if (video.tags != null && video.tags!.isNotEmpty) ...[
                  const SizedBox(height: 16),
                  Wrap(
                    spacing: 6,
                    runSpacing: 6,
                    children: video.tags!
                        .map((tag) => Chip(
                              label: Text('#$tag',
                                  style: const TextStyle(fontSize: 11)),
                              backgroundColor: AppTheme.surfaceLight,
                              side: const BorderSide(color: AppTheme.border),
                              padding: EdgeInsets.zero,
                              materialTapTargetSize:
                                  MaterialTapTargetSize.shrinkWrap,
                            ))
                        .toList(),
                  ),
                ],
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildLockedOverlay(AppLocalizations l10n, Video video) {
    return Container(
      color: AppTheme.surface,
      child: Stack(
        children: [
          // Thumbnail background (blurred effect)
          if (video.thumbnailUrl.isNotEmpty)
            Positioned.fill(
              child: Opacity(
                opacity: 0.2,
                child: Image.network(
                  video.thumbnailUrl,
                  fit: BoxFit.cover,
                  errorBuilder: (_, __, ___) => const SizedBox(),
                ),
              ),
            ),

          // Lock content
          Center(
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const Icon(Icons.lock, size: 48, color: AppTheme.premium),
                const SizedBox(height: 12),
                Text(
                  l10n.premiumContent,
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: AppTheme.textPrimary,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  l10n.upgradeToWatch,
                  style: const TextStyle(
                      color: AppTheme.textSecondary, fontSize: 13),
                ),
                const SizedBox(height: 16),
                ElevatedButton.icon(
                  onPressed: () => Navigator.pushNamed(context, '/plans'),
                  icon: const Icon(Icons.star, size: 18),
                  label: Text(l10n.subscribeNow),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppTheme.premium,
                    foregroundColor: Colors.black,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _MetaChip extends StatelessWidget {
  final IconData icon;
  final String text;

  const _MetaChip({required this.icon, required this.text});

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: 14, color: AppTheme.textMuted),
        const SizedBox(width: 4),
        Text(text,
            style: const TextStyle(color: AppTheme.textMuted, fontSize: 12)),
      ],
    );
  }
}
