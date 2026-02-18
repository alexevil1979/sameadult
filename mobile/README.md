# AIVidCatalog18 — Flutter Mobile App

Premium AI-Generated Video Catalog mobile client for Android.

> **Strictly fictional AI-generated content — no illegal/prohibited material.**
> All content is synthetic. No real persons depicted.

## Setup

### Prerequisites
- Flutter SDK 3.2+ ([install](https://docs.flutter.dev/get-started/install))
- Android Studio / VS Code with Flutter plugin
- Running Laravel backend (see root project)

### Installation

```bash
# 1. Navigate to mobile directory
cd mobile

# 2. Initialize Flutter project (first time only)
flutter create --org com.aividcatalog18 . --platforms android

# 3. Install dependencies
flutter pub get

# 4. Update API base URL in lib/config/api_config.dart
#    - Emulator: http://10.0.2.2:8000 (default)
#    - Physical device: http://YOUR_LOCAL_IP:8000

# 5. Run on connected device/emulator
flutter run
```

### Build APK

```bash
flutter build apk --release
# Output: build/app/outputs/flutter-apk/app-release.apk
```

## Features

- Age verification gate (18+)
- User registration & login (Sanctum API tokens)
- Video catalog with search, filters, pagination
- Video player (Chewie + video_player)
- Premium content lock with subscription prompt
- Subscription plans display
- Multi-language support (EN/RU/ES)
- Dark theme UI
- Secure token storage (flutter_secure_storage)

## Architecture

```
lib/
├── main.dart              # App entry point
├── config/
│   ├── api_config.dart    # API endpoints & settings
│   └── theme.dart         # Dark theme definition
├── models/
│   ├── user.dart          # User model
│   ├── video.dart         # Video model + pagination
│   └── plan.dart          # Subscription plan model
├── services/
│   ├── api_service.dart   # Dio HTTP client
│   └── auth_service.dart  # Auth state + token management
├── screens/
│   ├── age_gate_screen.dart
│   ├── login_screen.dart
│   ├── register_screen.dart
│   ├── home_screen.dart   # Bottom navigation hub
│   ├── catalog_screen.dart
│   ├── video_player_screen.dart
│   ├── plans_screen.dart
│   └── profile_screen.dart
├── widgets/
│   └── video_card.dart    # Thumbnail card widget
└── l10n/
    └── app_localizations.dart  # i18n (en/ru/es)
```

## API Connection

The app connects to the Laravel backend API:

| Endpoint | Method | Auth | Description |
|---|---|---|---|
| `/api/auth/register` | POST | No | Register user |
| `/api/auth/login` | POST | No | Login, get token |
| `/api/auth/logout` | POST | Bearer | Revoke token |
| `/api/auth/user` | GET | Bearer | Get profile |
| `/api/videos` | GET | No | Catalog (paginated) |
| `/api/videos/{id}` | GET | No | Video details |
| `/api/videos/{id}/access` | GET | Bearer | Get stream URL |
