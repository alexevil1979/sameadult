import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/theme.dart';
import '../services/auth_service.dart';
import '../l10n/app_localizations.dart';

/// Age Gate Screen — AIVidCatalog18
///
/// Displayed on first launch. User must confirm they are 18+.
/// Strictly fictional AI-generated content — no illegal/prohibited material.

class AgeGateScreen extends StatefulWidget {
  const AgeGateScreen({super.key});

  @override
  State<AgeGateScreen> createState() => _AgeGateScreenState();
}

class _AgeGateScreenState extends State<AgeGateScreen> {
  bool _confirmed = false;

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);

    return Scaffold(
      body: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(24),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                // Warning icon
                const Icon(Icons.warning_amber_rounded,
                    size: 80, color: AppTheme.accent),
                const SizedBox(height: 24),

                // Title
                Text(
                  l10n.ageVerificationTitle,
                  style: const TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                    color: AppTheme.textPrimary,
                  ),
                  textAlign: TextAlign.center,
                ),
                const SizedBox(height: 12),

                // Description
                Text(
                  l10n.ageVerificationDescription,
                  style: const TextStyle(
                    fontSize: 14,
                    color: AppTheme.textSecondary,
                  ),
                  textAlign: TextAlign.center,
                ),
                const SizedBox(height: 32),

                // Confirmation checkbox
                CheckboxListTile(
                  value: _confirmed,
                  onChanged: (val) => setState(() => _confirmed = val ?? false),
                  title: Text(
                    l10n.ageVerificationConfirm,
                    style: const TextStyle(
                      fontSize: 14,
                      color: AppTheme.textPrimary,
                    ),
                  ),
                  activeColor: AppTheme.primary,
                  controlAffinity: ListTileControlAffinity.leading,
                  contentPadding: EdgeInsets.zero,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
                const SizedBox(height: 16),

                // Legal notice
                Text(
                  l10n.ageVerificationLegal,
                  style: const TextStyle(
                    fontSize: 11,
                    color: AppTheme.textMuted,
                  ),
                  textAlign: TextAlign.center,
                ),
                const SizedBox(height: 32),

                // Enter button
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: _confirmed
                        ? () async {
                            await context.read<AuthService>().verifyAge();
                            if (context.mounted) {
                              Navigator.of(context)
                                  .pushReplacementNamed('/home');
                            }
                          }
                        : null,
                    child: Text(l10n.ageVerificationEnter),
                  ),
                ),
                const SizedBox(height: 12),

                // Leave button
                SizedBox(
                  width: double.infinity,
                  child: OutlinedButton(
                    onPressed: () {
                      // Close the app
                      Navigator.of(context).maybePop();
                    },
                    child: Text(l10n.ageVerificationLeave),
                  ),
                ),
                const SizedBox(height: 24),

                // Disclaimer
                Text(
                  l10n.siteDisclaimer,
                  style: const TextStyle(
                    fontSize: 10,
                    color: AppTheme.textMuted,
                  ),
                  textAlign: TextAlign.center,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
