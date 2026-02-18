import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/theme.dart';
import '../services/auth_service.dart';
import '../l10n/app_localizations.dart';
import 'catalog_screen.dart';
import 'plans_screen.dart';
import 'profile_screen.dart';

/// Home Screen — AIVidCatalog18
///
/// Main navigation hub with bottom tab bar.
/// Tabs: Catalog, Plans, Profile
///
/// Strictly fictional AI-generated content — no illegal/prohibited material.

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentIndex = 0;

  final List<Widget> _screens = const [
    CatalogScreen(),
    PlansScreen(),
    ProfileScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final auth = context.watch<AuthService>();

    return Scaffold(
      body: IndexedStack(
        index: _currentIndex,
        children: _screens,
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _currentIndex,
        onTap: (i) => setState(() => _currentIndex = i),
        items: [
          BottomNavigationBarItem(
            icon: const Icon(Icons.video_library_outlined),
            activeIcon: const Icon(Icons.video_library),
            label: l10n.videos,
          ),
          BottomNavigationBarItem(
            icon: const Icon(Icons.card_membership_outlined),
            activeIcon: const Icon(Icons.card_membership),
            label: l10n.plans,
          ),
          BottomNavigationBarItem(
            icon: Stack(
              children: [
                const Icon(Icons.person_outlined),
                if (auth.isSubscribed)
                  Positioned(
                    right: 0,
                    top: 0,
                    child: Container(
                      width: 8,
                      height: 8,
                      decoration: const BoxDecoration(
                        color: AppTheme.success,
                        shape: BoxShape.circle,
                      ),
                    ),
                  ),
              ],
            ),
            activeIcon: const Icon(Icons.person),
            label: l10n.profile,
          ),
        ],
      ),
    );
  }
}
