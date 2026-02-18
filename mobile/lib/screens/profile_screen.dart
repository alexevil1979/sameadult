import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/theme.dart';
import '../services/auth_service.dart';
import '../l10n/app_localizations.dart';

/// Profile Screen — AIVidCatalog18
///
/// User profile, subscription status, language settings, logout.
/// Strictly fictional AI-generated content — no illegal/prohibited material.

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final auth = context.watch<AuthService>();

    return Scaffold(
      appBar: AppBar(title: Text(l10n.profile)),
      body: auth.isAuthenticated
          ? _buildAuthenticatedProfile(context, auth, l10n)
          : _buildGuestProfile(context, l10n),
    );
  }

  Widget _buildGuestProfile(BuildContext context, AppLocalizations l10n) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(32),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.person_outline, size: 80, color: AppTheme.textMuted),
            const SizedBox(height: 16),
            Text(l10n.login,
                style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () => Navigator.pushNamed(context, '/login'),
                child: Text(l10n.login),
              ),
            ),
            const SizedBox(height: 12),
            SizedBox(
              width: double.infinity,
              child: OutlinedButton(
                onPressed: () => Navigator.pushNamed(context, '/register'),
                child: Text(l10n.register),
              ),
            ),

            // Language selector (even for guests)
            const SizedBox(height: 32),
            _LanguageSelector(),
          ],
        ),
      ),
    );
  }

  Widget _buildAuthenticatedProfile(
      BuildContext context, AuthService auth, AppLocalizations l10n) {
    final user = auth.currentUser!;

    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          // Avatar & name
          CircleAvatar(
            radius: 40,
            backgroundColor: AppTheme.primary.withOpacity(0.2),
            child: Text(
              user.name[0].toUpperCase(),
              style: const TextStyle(
                  fontSize: 32, color: AppTheme.primary, fontWeight: FontWeight.bold),
            ),
          ),
          const SizedBox(height: 12),
          Text(user.name,
              style:
                  const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
          Text(user.email,
              style: const TextStyle(color: AppTheme.textMuted, fontSize: 14)),
          const SizedBox(height: 20),

          // Subscription status
          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: user.isSubscribed
                  ? AppTheme.success.withOpacity(0.1)
                  : AppTheme.surfaceLight,
              border: Border.all(
                color: user.isSubscribed
                    ? AppTheme.success.withOpacity(0.3)
                    : AppTheme.border,
              ),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Row(
              children: [
                Icon(
                  user.isSubscribed ? Icons.check_circle : Icons.cancel,
                  color: user.isSubscribed
                      ? AppTheme.success
                      : AppTheme.textMuted,
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        user.isSubscribed
                            ? l10n.activeSubscription
                            : l10n.noSubscription,
                        style: TextStyle(
                          fontWeight: FontWeight.w600,
                          color: user.isSubscribed
                              ? AppTheme.success
                              : AppTheme.textSecondary,
                        ),
                      ),
                      if (user.isSubscribed && user.subscriptionEnd != null)
                        Text(
                          'Until ${user.subscriptionEnd!.toLocal().toString().substring(0, 10)}',
                          style: const TextStyle(
                              color: AppTheme.textMuted, fontSize: 12),
                        ),
                    ],
                  ),
                ),
                if (!user.isSubscribed)
                  TextButton(
                    onPressed: () => Navigator.pushNamed(context, '/plans'),
                    child: Text(l10n.subscribeNow),
                  ),
              ],
            ),
          ),
          const SizedBox(height: 24),

          // Language selector
          _LanguageSelector(),
          const SizedBox(height: 24),

          // Logout
          SizedBox(
            width: double.infinity,
            child: OutlinedButton.icon(
              onPressed: () async {
                await auth.logout();
                if (context.mounted) {
                  Navigator.of(context).pushReplacementNamed('/home');
                }
              },
              icon: const Icon(Icons.logout, color: AppTheme.error),
              label: Text(l10n.logout,
                  style: const TextStyle(color: AppTheme.error)),
              style: OutlinedButton.styleFrom(
                side: BorderSide(color: AppTheme.error.withOpacity(0.3)),
              ),
            ),
          ),
          const SizedBox(height: 32),

          // Disclaimer
          Text(
            l10n.siteDisclaimer,
            style: const TextStyle(color: AppTheme.textMuted, fontSize: 10),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }
}

class _LanguageSelector extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthService>();
    final l10n = AppLocalizations.of(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(l10n.language,
            style: const TextStyle(
                fontWeight: FontWeight.w600, color: AppTheme.textSecondary)),
        const SizedBox(height: 8),
        Row(
          children: [
            _LangButton('🇺🇸', 'EN', 'en', auth),
            const SizedBox(width: 8),
            _LangButton('🇷🇺', 'RU', 'ru', auth),
            const SizedBox(width: 8),
            _LangButton('🇪🇸', 'ES', 'es', auth),
          ],
        ),
      ],
    );
  }
}

class _LangButton extends StatelessWidget {
  final String flag;
  final String label;
  final String locale;
  final AuthService auth;

  const _LangButton(this.flag, this.label, this.locale, this.auth);

  @override
  Widget build(BuildContext context) {
    final isSelected = auth.currentLocale == locale;

    return Expanded(
      child: InkWell(
        onTap: () => auth.setLocale(locale),
        borderRadius: BorderRadius.circular(8),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 12),
          decoration: BoxDecoration(
            color: isSelected
                ? AppTheme.primary.withOpacity(0.15)
                : AppTheme.surfaceLight,
            border: Border.all(
              color: isSelected ? AppTheme.primary : AppTheme.border,
            ),
            borderRadius: BorderRadius.circular(8),
          ),
          child: Column(
            children: [
              Text(flag, style: const TextStyle(fontSize: 20)),
              const SizedBox(height: 4),
              Text(label,
                  style: TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w600,
                    color: isSelected ? AppTheme.primary : AppTheme.textMuted,
                  )),
            ],
          ),
        ),
      ),
    );
  }
}
