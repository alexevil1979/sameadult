/// Plan Model — AIVidCatalog18 Flutter App
///
/// Subscription plan data from the API.
/// Strictly fictional AI-generated content — no illegal/prohibited material.

class Plan {
  final int id;
  final String name;
  final String slug;
  final double priceUsd;
  final int durationDays;
  final String description;
  final bool isActive;

  Plan({
    required this.id,
    required this.name,
    required this.slug,
    required this.priceUsd,
    required this.durationDays,
    required this.description,
    required this.isActive,
  });

  factory Plan.fromJson(Map<String, dynamic> json) {
    return Plan(
      id: json['id'] as int,
      name: json['name'] as String,
      slug: json['slug'] as String,
      priceUsd: double.tryParse(json['price_usd'].toString()) ?? 0.0,
      durationDays: json['duration_days'] as int? ?? 30,
      description: json['description'] as String? ?? '',
      isActive: json['is_active'] as bool? ?? true,
    );
  }

  /// Formatted price string
  String get formattedPrice => '\$${priceUsd.toStringAsFixed(2)}';

  /// Is this the premium plan?
  bool get isPremium => slug == 'premium';
}
