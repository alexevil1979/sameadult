import 'package:dio/dio.dart';
import '../config/api_config.dart';

/// API Service — AIVidCatalog18 Flutter App
///
/// Central HTTP client for all API communications with the Laravel backend.
/// Uses Dio for HTTP requests with interceptors for auth tokens.
///
/// Strictly fictional AI-generated content — no illegal/prohibited material.

class ApiService {
  late final Dio _dio;
  String? _authToken;

  ApiService() {
    _dio = Dio(BaseOptions(
      baseUrl: ApiConfig.apiUrl,
      connectTimeout: ApiConfig.connectTimeout,
      receiveTimeout: ApiConfig.receiveTimeout,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
    ));

    // Request interceptor — add auth token
    _dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) {
        if (_authToken != null) {
          options.headers['Authorization'] = 'Bearer $_authToken';
        }
        return handler.next(options);
      },
      onError: (error, handler) {
        // Log errors for debugging
        print('API Error: ${error.requestOptions.path} → ${error.response?.statusCode}');
        return handler.next(error);
      },
    ));
  }

  /// Set the authentication token (from login/register)
  void setToken(String? token) {
    _authToken = token;
  }

  /// Check if we have an auth token set
  bool get hasToken => _authToken != null && _authToken!.isNotEmpty;

  // =====================================================================
  // Auth Endpoints
  // =====================================================================

  /// POST /api/auth/login
  Future<Response> login(String email, String password) async {
    return _dio.post(ApiConfig.login, data: {
      'email': email,
      'password': password,
    });
  }

  /// POST /api/auth/register
  Future<Response> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String language = 'en',
  }) async {
    return _dio.post(ApiConfig.register, data: {
      'name': name,
      'email': email,
      'password': password,
      'password_confirmation': passwordConfirmation,
      'language': language,
    });
  }

  /// POST /api/auth/logout
  Future<Response> logout() async {
    return _dio.post(ApiConfig.logout);
  }

  /// GET /api/auth/user
  Future<Response> getUser() async {
    return _dio.get(ApiConfig.user);
  }

  // =====================================================================
  // Video Endpoints
  // =====================================================================

  /// GET /api/videos — Paginated video catalog
  Future<Response> getVideos({
    int page = 1,
    int perPage = 12,
    String? category,
    bool? premium,
    String? query,
  }) async {
    final params = <String, dynamic>{
      'page': page,
      'per_page': perPage,
    };
    if (category != null && category.isNotEmpty) params['category'] = category;
    if (premium != null) params['premium'] = premium ? '1' : '0';
    if (query != null && query.isNotEmpty) params['q'] = query;

    return _dio.get(ApiConfig.videos, queryParameters: params);
  }

  /// GET /api/videos/{id} — Video details
  Future<Response> getVideo(int id) async {
    return _dio.get('${ApiConfig.videos}/$id');
  }

  /// GET /api/videos/{id}/access — Get stream URL (auth required)
  Future<Response> getVideoAccess(int id) async {
    return _dio.get(
      ApiConfig.videoAccess.replaceFirst('{id}', id.toString()),
    );
  }

  // =====================================================================
  // Health Check
  // =====================================================================

  /// GET /api/ping
  Future<Response> ping() async {
    return _dio.get(ApiConfig.ping);
  }
}
