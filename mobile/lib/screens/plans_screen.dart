import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';
import '../config/api_config.dart';
import '../config/theme.dart';
import '../services/auth_service.dart';
import '../l10n/app_localizations.dart';

/// Plans Screen — AIVidCatalog18
///
/// Displays subscription plans. Purchase redirects to web checkout
/// (NOWPayments crypto payment is handled server-side).
///
/// Strictly fictional AI-generated content — no illegal/prohibited material.

class PlansScreen extends StatelessWidget {
  const PlansScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final auth = context.watch<AuthService>();

    return Scaffold(
      appBar: AppBar(title: Text(l10n.choosePlan)),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            // Subscription status
            if (auth.isSubscribed)
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(16),
                margin: const EdgeInsets.only(bottom: 20),
                decoration: BoxDecoration(
                  color: AppTheme.success.withOpacity(0.1),
                  border: Border.all(color: AppTheme.success.withOpacity(0.3)),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Row(
                  children: [
                    const Icon(Icons.check_circle, color: AppTheme.success),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Text(
                        l10n.yourSubscriptionUntil(
                          auth.currentUser!.subscriptionEnd!
                              .toLocal()
                              .toString()
                              .substring(0, 10),
                        ),
                        style: const TextStyle(color: AppTheme.success),
                      ),
                    ),
                  ],
                ),
              ),

            // Description
            Text(
              l10n.plansDescription,
              style: const TextStyle(
                  color: AppTheme.textSecondary, fontSize: 14),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 24),

            // Basic plan
            _PlanCard(
              name: 'Basic',
              price: '\$9.99',
              duration: '30 ${l10n.daysAccess('')}'.replaceAll(' ', ' '),
              description:
                  'Access to standard AI-generated video catalog. HD streaming.',
              isPremium: false,
              isSubscribed: auth.isSubscribed,
              onSubscribe: () => _openCheckout(context),
            ),
            const SizedBox(height: 16),

            // Premium plan
            _PlanCard(
              name: 'Premium',
              price: '\$19.99',
              duration: '30 ${l10n.daysAccess('')}'.replaceAll(' ', ' '),
              description:
                  'Full access to all content including exclusive premium videos. 4K streaming.',
              isPremium: true,
              isSubscribed: auth.isSubscribed,
              onSubscribe: () => _openCheckout(context),
            ),

            const SizedBox(height: 24),

            // Crypto note
            const Text(
              '💰 Payments via NOWPayments (cryptocurrency)\nBitcoin, Ethereum, USDT, and 200+ coins accepted',
              style: TextStyle(color: AppTheme.textMuted, fontSize: 12),
              textAlign: TextAlign.center,
            ),
          ],
        ),
      ),
    );
  }

  void _openCheckout(BuildContext context) async {
    final auth = context.read<AuthService>();
    if (!auth.isAuthenticated) {
      Navigator.pushNamed(context, '/login');
      return;
    }

    // Open web checkout in browser (payment handled server-side)
    final url = Uri.parse(ApiConfig.plansUrl);
    if (await canLaunchUrl(url)) {
      await launchUrl(url, mode: LaunchMode.externalApplication);
    }
  }
}

class _PlanCard extends StatelessWidget {
  final String name;
  final String price;
  final String duration;
  final String description;
  final bool isPremium;
  final bool isSubscribed;
  final VoidCallback onSubscribe;

  const _PlanCard({
    required this.name,
    required this.price,
    required this.duration,
    required this.description,
    required this.isPremium,
    required this.isSubscribed,
    required this.onSubscribe,
  });

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: AppTheme.surface,
        border: Border.all(
          color: isPremium ? AppTheme.premium : AppTheme.border,
          width: isPremium ? 2 : 1,
        ),
        borderRadius: BorderRadius.circular(16),
      ),
      child: Column(
        children: [
          if (isPremium)
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
              margin: const EdgeInsets.only(bottom: 12),
              decoration: BoxDecoration(
                color: AppTheme.premium,
                borderRadius: BorderRadius.circular(20),
              ),
              child: Text(l10n.mostPopular,
                  style: const TextStyle(
                      color: Colors.black,
                      fontSize: 11,
                      fontWeight: FontWeight.bold)),
            ),
          Text(name,
              style: const TextStyle(
                  fontSize: 22, fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          Text(price,
              style: const TextStyle(
                  fontSize: 40, fontWeight: FontWeight.w800)),
          Text('/month',
              style: const TextStyle(
                  color: AppTheme.textMuted, fontSize: 14)),
          const SizedBox(height: 12),
          Text(description,
              style: const TextStyle(
                  color: AppTheme.textSecondary, fontSize: 13),
              textAlign: TextAlign.center),
          const SizedBox(height: 20),
          SizedBox(
            width: double.infinity,
            child: isSubscribed
                ? OutlinedButton(
                    onPressed: null,
                    child: Text(l10n.currentPlan),
                  )
                : ElevatedButton(
                    onPressed: onSubscribe,
                    style: isPremium
                        ? ElevatedButton.styleFrom(
                            backgroundColor: AppTheme.premium,
                            foregroundColor: Colors.black,
                          )
                        : null,
                    child: Text(l10n.selectPlan),
                  ),
          ),
        ],
      ),
    );
  }
}
