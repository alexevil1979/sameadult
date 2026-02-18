/// User Model — AIVidCatalog18 Flutter App
///
/// Mirrors the Laravel User model for API responses.
/// Strictly fictional AI-generated content — no illegal/prohibited material.

class User {
  final int id;
  final String name;
  final String email;
  final String language;
  final bool isAdmin;
  final bool hasActiveSubscription;
  final DateTime? subscriptionEnd;
  final DateTime createdAt;

  User({
    required this.id,
    required this.name,
    required this.email,
    required this.language,
    required this.isAdmin,
    required this.hasActiveSubscription,
    this.subscriptionEnd,
    required this.createdAt,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] as int,
      name: json['name'] as String,
      email: json['email'] as String,
      language: json['language'] as String? ?? 'en',
      isAdmin: json['is_admin'] as bool? ?? false,
      hasActiveSubscription: json['has_active_subscription'] as bool? ?? false,
      subscriptionEnd: json['subscription_end'] != null
          ? DateTime.parse(json['subscription_end'])
          : null,
      createdAt: DateTime.parse(json['created_at']),
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'email': email,
        'language': language,
        'is_admin': isAdmin,
        'has_active_subscription': hasActiveSubscription,
        'subscription_end': subscriptionEnd?.toIso8601String(),
        'created_at': createdAt.toIso8601String(),
      };

  /// Check if subscription is still valid
  bool get isSubscribed =>
      hasActiveSubscription &&
      subscriptionEnd != null &&
      subscriptionEnd!.isAfter(DateTime.now());
}
