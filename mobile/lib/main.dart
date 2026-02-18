import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';
import 'package:flutter_localizations/flutter_localizations.dart';

import 'config/theme.dart';
import 'l10n/app_localizations.dart';
import 'services/auth_service.dart';
import 'services/api_service.dart';
import 'screens/age_gate_screen.dart';
import 'screens/login_screen.dart';
import 'screens/register_screen.dart';
import 'screens/catalog_screen.dart';
import 'screens/video_player_screen.dart';
import 'screens/plans_screen.dart';
import 'screens/profile_screen.dart';
import 'screens/home_screen.dart';

/// AIVidCatalog18 — Flutter Mobile App
///
/// Premium AI-Generated Video Catalog
/// Strictly fictional AI-generated content — no illegal/prohibited material.
/// All content is synthetic. No real persons depicted.

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // Lock to portrait mode
  await SystemChrome.setPreferredOrientations([
    DeviceOrientation.portraitUp,
    DeviceOrientation.portraitDown,
  ]);

  // Set system UI style
  SystemChrome.setSystemUIOverlayStyle(const SystemUiOverlayStyle(
    statusBarColor: Colors.transparent,
    statusBarIconBrightness: Brightness.light,
    systemNavigationBarColor: AppTheme.background,
  ));

  // Initialize services
  final apiService = ApiService();
  final authService = AuthService(apiService);
  await authService.initialize();

  runApp(
    MultiProvider(
      providers: [
        Provider<ApiService>.value(value: apiService),
        ChangeNotifierProvider<AuthService>.value(value: authService),
      ],
      child: const AIVidCatalogApp(),
    ),
  );
}

class AIVidCatalogApp extends StatelessWidget {
  const AIVidCatalogApp({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<AuthService>(
      builder: (context, auth, _) {
        return MaterialApp(
          title: 'AIVidCatalog18',
          debugShowCheckedModeBanner: false,
          theme: AppTheme.darkTheme,

          // Localization
          locale: Locale(auth.currentLocale),
          supportedLocales: const [
            Locale('en'),
            Locale('ru'),
            Locale('es'),
          ],
          localizationsDelegates: const [
            AppLocalizations.delegate,
            GlobalMaterialLocalizations.delegate,
            GlobalWidgetsLocalizations.delegate,
            GlobalCupertinoLocalizations.delegate,
          ],

          // Routes
          initialRoute: auth.isAgeVerified ? '/home' : '/age-gate',
          routes: {
            '/age-gate': (_) => const AgeGateScreen(),
            '/login': (_) => const LoginScreen(),
            '/register': (_) => const RegisterScreen(),
            '/home': (_) => const HomeScreen(),
            '/plans': (_) => const PlansScreen(),
            '/profile': (_) => const ProfileScreen(),
          },
          onGenerateRoute: (settings) {
            // Dynamic routes (e.g., /video/123)
            if (settings.name?.startsWith('/video/') ?? false) {
              final videoId = int.tryParse(
                settings.name!.replaceFirst('/video/', ''),
              );
              if (videoId != null) {
                return MaterialPageRoute(
                  builder: (_) => VideoPlayerScreen(videoId: videoId),
                );
              }
            }
            return null;
          },
        );
      },
    );
  }
}
