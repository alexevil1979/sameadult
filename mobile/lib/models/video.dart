/// Video Model — AIVidCatalog18 Flutter App
///
/// Represents an AI-generated video from the catalog API.
/// Multilingual title/description stored as Map.
///
/// Strictly fictional AI-generated content — no illegal/prohibited material.
/// All content is synthetic. No real persons depicted.

class Video {
  final int id;
  final Map<String, String> title;
  final Map<String, String>? description;
  final String category;
  final List<String>? tags;
  final String duration; // formatted "m:ss"
  final int durationSeconds;
  final bool isPremium;
  final int viewsCount;
  final String thumbnailUrl;
  final DateTime createdAt;

  // Access info (from /show endpoint)
  final bool? canAccess;
  final bool? requiresSubscription;

  Video({
    required this.id,
    required this.title,
    this.description,
    required this.category,
    this.tags,
    required this.duration,
    required this.durationSeconds,
    required this.isPremium,
    required this.viewsCount,
    required this.thumbnailUrl,
    required this.createdAt,
    this.canAccess,
    this.requiresSubscription,
  });

  factory Video.fromJson(Map<String, dynamic> json) {
    return Video(
      id: json['id'] as int,
      title: _parseStringMap(json['title']),
      description: json['description'] != null
          ? _parseStringMap(json['description'])
          : null,
      category: json['category'] as String? ?? 'general',
      tags: (json['tags'] as List<dynamic>?)?.cast<String>(),
      duration: json['duration'] as String? ?? '0:00',
      durationSeconds: json['duration_seconds'] as int? ?? 0,
      isPremium: json['is_premium'] as bool? ?? false,
      viewsCount: json['views_count'] as int? ?? 0,
      thumbnailUrl: json['thumbnail_url'] as String? ?? '',
      createdAt: DateTime.parse(json['created_at']),
      canAccess: json['can_access'] as bool?,
      requiresSubscription: json['requires_subscription'] as bool?,
    );
  }

  /// Get localized title based on locale code
  String localizedTitle(String locale) {
    return title[locale] ?? title['en'] ?? 'Untitled';
  }

  /// Get localized description
  String localizedDescription(String locale) {
    if (description == null) return '';
    return description![locale] ?? description!['en'] ?? '';
  }

  /// Format views count (e.g., 1.2K, 3.4M)
  String get formattedViews {
    if (viewsCount >= 1000000) {
      return '${(viewsCount / 1000000).toStringAsFixed(1)}M';
    } else if (viewsCount >= 1000) {
      return '${(viewsCount / 1000).toStringAsFixed(1)}K';
    }
    return viewsCount.toString();
  }

  static Map<String, String> _parseStringMap(dynamic data) {
    if (data is Map) {
      return data.map((k, v) => MapEntry(k.toString(), v.toString()));
    }
    return {'en': data?.toString() ?? ''};
  }
}

/// Paginated response wrapper for video lists
class VideoListResponse {
  final List<Video> videos;
  final int currentPage;
  final int lastPage;
  final int total;

  VideoListResponse({
    required this.videos,
    required this.currentPage,
    required this.lastPage,
    required this.total,
  });

  factory VideoListResponse.fromJson(Map<String, dynamic> json) {
    return VideoListResponse(
      videos: (json['data'] as List<dynamic>)
          .map((v) => Video.fromJson(v as Map<String, dynamic>))
          .toList(),
      currentPage: json['current_page'] as int? ?? 1,
      lastPage: json['last_page'] as int? ?? 1,
      total: json['total'] as int? ?? 0,
    );
  }

  bool get hasMore => currentPage < lastPage;
}
