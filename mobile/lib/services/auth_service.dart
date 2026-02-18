import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:shared_preferences/shared_preferences.dart';

import '../models/user.dart';
import 'api_service.dart';

/// Auth Service — AIVidCatalog18 Flutter App
///
/// Manages authentication state, tokens, age verification,
/// and user preferences using ChangeNotifier for Provider.
///
/// Strictly fictional AI-generated content — no illegal/prohibited material.

class AuthService extends ChangeNotifier {
  final ApiService _api;
  final FlutterSecureStorage _secureStorage = const FlutterSecureStorage();

  User? _currentUser;
  bool _isLoading = false;
  bool _isAgeVerified = false;
  String _currentLocale = 'en';
  String? _errorMessage;

  AuthService(this._api);

  // =====================================================================
  // Getters
  // =====================================================================

  User? get currentUser => _currentUser;
  bool get isAuthenticated => _currentUser != null;
  bool get isLoading => _isLoading;
  bool get isAgeVerified => _isAgeVerified;
  String get currentLocale => _currentLocale;
  String? get errorMessage => _errorMessage;
  bool get isSubscribed => _currentUser?.isSubscribed ?? false;

  // =====================================================================
  // Initialization
  // =====================================================================

  /// Initialize auth state from stored data
  Future<void> initialize() async {
    final prefs = await SharedPreferences.getInstance();

    // Restore age verification
    _isAgeVerified = prefs.getBool('age_verified') ?? false;

    // Restore locale
    _currentLocale = prefs.getString('locale') ?? 'en';

    // Restore auth token and try to fetch user
    final token = await _secureStorage.read(key: 'auth_token');
    if (token != null && token.isNotEmpty) {
      _api.setToken(token);
      await _fetchCurrentUser();
    }

    notifyListeners();
  }

  // =====================================================================
  // Age Verification
  // =====================================================================

  /// Mark age as verified (stores in SharedPreferences)
  Future<void> verifyAge() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool('age_verified', true);
    _isAgeVerified = true;
    notifyListeners();
  }

  // =====================================================================
  // Locale
  // =====================================================================

  /// Change the app locale
  Future<void> setLocale(String locale) async {
    if (!['en', 'ru', 'es'].contains(locale)) return;

    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('locale', locale);
    _currentLocale = locale;
    notifyListeners();
  }

  // =====================================================================
  // Authentication
  // =====================================================================

  /// Login with email and password
  Future<bool> login(String email, String password) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final response = await _api.login(email, password);
      final data = response.data;

      // Store token securely
      final token = data['token'] as String;
      await _secureStorage.write(key: 'auth_token', value: token);
      _api.setToken(token);

      // Parse user
      _currentUser = User.fromJson(data['user']);

      // Update locale to user preference
      if (_currentUser!.language != _currentLocale) {
        await setLocale(_currentUser!.language);
      }

      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _errorMessage = _parseError(e);
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  /// Register a new user
  Future<bool> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String language = 'en',
  }) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final response = await _api.register(
        name: name,
        email: email,
        password: password,
        passwordConfirmation: passwordConfirmation,
        language: language,
      );
      final data = response.data;

      // Store token
      final token = data['token'] as String;
      await _secureStorage.write(key: 'auth_token', value: token);
      _api.setToken(token);

      // Parse user
      _currentUser = User.fromJson(data['user']);

      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _errorMessage = _parseError(e);
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  /// Logout — revoke token and clear state
  Future<void> logout() async {
    try {
      await _api.logout();
    } catch (_) {
      // Ignore network errors on logout
    }

    await _secureStorage.delete(key: 'auth_token');
    _api.setToken(null);
    _currentUser = null;
    _errorMessage = null;
    notifyListeners();
  }

  /// Refresh current user data from API
  Future<void> refreshUser() async {
    await _fetchCurrentUser();
    notifyListeners();
  }

  // =====================================================================
  // Private Helpers
  // =====================================================================

  /// Fetch current user from API
  Future<void> _fetchCurrentUser() async {
    try {
      final response = await _api.getUser();
      _currentUser = User.fromJson(response.data['data']);
    } catch (_) {
      // Token might be expired — clear it
      await _secureStorage.delete(key: 'auth_token');
      _api.setToken(null);
      _currentUser = null;
    }
  }

  /// Parse error message from Dio exceptions
  String _parseError(dynamic error) {
    if (error is Exception) {
      try {
        // Try to extract validation errors from API response
        final dynamic response = (error as dynamic).response;
        if (response != null) {
          final data = response.data;
          if (data is Map) {
            if (data.containsKey('message')) return data['message'];
            if (data.containsKey('errors')) {
              final errors = data['errors'] as Map;
              final firstError = errors.values.first;
              if (firstError is List && firstError.isNotEmpty) {
                return firstError.first.toString();
              }
            }
          }
        }
      } catch (_) {}
    }
    return 'An error occurred. Please try again.';
  }
}
