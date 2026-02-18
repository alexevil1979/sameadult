/// API Configuration — AIVidCatalog18 Flutter App
///
/// Strictly fictional AI-generated content — no illegal/prohibited material.

class ApiConfig {
  /// Base URL of the Laravel backend API
  /// Change this to your production server URL
  static const String baseUrl = 'http://10.0.2.2:8000'; // Android emulator → localhost
  static const String apiUrl = '$baseUrl/api';

  /// API Endpoints
  static const String login = '/auth/login';
  static const String register = '/auth/register';
  static const String logout = '/auth/logout';
  static const String user = '/auth/user';
  static const String videos = '/videos';
  static const String videoAccess = '/videos/{id}/access';
  static const String ping = '/ping';

  /// Web URLs (opened in browser)
  static const String plansUrl = '$baseUrl/plans';
  static const String ageGateUrl = '$baseUrl/age-gate';

  /// Timeouts
  static const Duration connectTimeout = Duration(seconds: 15);
  static const Duration receiveTimeout = Duration(seconds: 30);

  /// Pagination
  static const int defaultPerPage = 12;
  static const int maxPerPage = 50;
}
