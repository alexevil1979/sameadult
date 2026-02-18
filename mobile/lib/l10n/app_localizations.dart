import 'package:flutter/material.dart';

/// App Localizations — AIVidCatalog18
///
/// Simple localization system supporting en, ru, es.
/// Strictly fictional AI-generated content — no illegal/prohibited material.

class AppLocalizations {
  final Locale locale;

  AppLocalizations(this.locale);

  static AppLocalizations of(BuildContext context) {
    return Localizations.of<AppLocalizations>(context, AppLocalizations) ??
        AppLocalizations(const Locale('en'));
  }

  static const LocalizationsDelegate<AppLocalizations> delegate =
      _AppLocalizationsDelegate();

  String get _lang => locale.languageCode;

  // =========================================================================
  // Translations Map
  // =========================================================================

  static const Map<String, Map<String, String>> _translations = {
    'site_name': {
      'en': 'AIVidCatalog18',
      'ru': 'AIVidCatalog18',
      'es': 'AIVidCatalog18',
    },
    'site_disclaimer': {
      'en': 'All content is strictly fictional and AI-generated. No real persons depicted. For adults 18+ only.',
      'ru': 'Весь контент является строго вымышленным и созданным ИИ. Реальные лица не изображены. Только 18+.',
      'es': 'Todo el contenido es estrictamente ficticio y generado por IA. No se representan personas reales. Solo 18+.',
    },
    'age_verification_title': {
      'en': 'Age Verification Required',
      'ru': 'Требуется подтверждение возраста',
      'es': 'Verificación de Edad Requerida',
    },
    'age_verification_description': {
      'en': 'This app contains AI-generated adult content for viewers aged 18+.',
      'ru': 'Это приложение содержит контент для взрослых, созданный ИИ, для зрителей от 18 лет.',
      'es': 'Esta app contiene contenido para adultos generado por IA para mayores de 18.',
    },
    'age_verification_confirm': {
      'en': 'I confirm that I am 18 years of age or older',
      'ru': 'Я подтверждаю, что мне 18 лет или больше',
      'es': 'Confirmo que tengo 18 años o más',
    },
    'age_verification_enter': {
      'en': 'Enter',
      'ru': 'Войти',
      'es': 'Entrar',
    },
    'age_verification_leave': {
      'en': 'Leave',
      'ru': 'Покинуть',
      'es': 'Salir',
    },
    'age_verification_legal': {
      'en': 'By entering, you agree to our Terms and confirm viewing AI-generated adult content is legal in your jurisdiction.',
      'ru': 'Входя, вы соглашаетесь с Условиями и подтверждаете, что просмотр контента для взрослых, созданного ИИ, является законным в вашей юрисдикции.',
      'es': 'Al ingresar, acepta nuestros Términos y confirma que ver contenido para adultos generado por IA es legal en su jurisdicción.',
    },
    'login': {'en': 'Login', 'ru': 'Войти', 'es': 'Iniciar Sesión'},
    'register': {'en': 'Register', 'ru': 'Регистрация', 'es': 'Registrarse'},
    'logout': {'en': 'Logout', 'ru': 'Выйти', 'es': 'Cerrar Sesión'},
    'email': {'en': 'Email', 'ru': 'Эл. почта', 'es': 'Correo'},
    'password': {'en': 'Password', 'ru': 'Пароль', 'es': 'Contraseña'},
    'confirm_password': {'en': 'Confirm Password', 'ru': 'Подтвердите пароль', 'es': 'Confirmar Contraseña'},
    'name': {'en': 'Name', 'ru': 'Имя', 'es': 'Nombre'},
    'videos': {'en': 'Videos', 'ru': 'Видео', 'es': 'Videos'},
    'video_catalog': {'en': 'Video Catalog', 'ru': 'Каталог видео', 'es': 'Catálogo de Videos'},
    'all_categories': {'en': 'All', 'ru': 'Все', 'es': 'Todos'},
    'all_videos': {'en': 'All Videos', 'ru': 'Все видео', 'es': 'Todos'},
    'premium_only': {'en': 'Premium Only', 'ru': 'Только премиум', 'es': 'Solo Premium'},
    'free_only': {'en': 'Free Only', 'ru': 'Только бесплатные', 'es': 'Solo Gratis'},
    'search_videos': {'en': 'Search videos...', 'ru': 'Поиск видео...', 'es': 'Buscar videos...'},
    'no_videos_found': {'en': 'No videos found', 'ru': 'Видео не найдены', 'es': 'No se encontraron videos'},
    'views': {'en': 'views', 'ru': 'просмотров', 'es': 'vistas'},
    'premium_content': {'en': 'Premium Content', 'ru': 'Премиум контент', 'es': 'Contenido Premium'},
    'free_content': {'en': 'Free', 'ru': 'Бесплатно', 'es': 'Gratis'},
    'upgrade_to_watch': {'en': 'Subscribe to watch', 'ru': 'Подпишитесь для просмотра', 'es': 'Suscríbete para ver'},
    'subscribe_now': {'en': 'Subscribe Now', 'ru': 'Подписаться', 'es': 'Suscríbete'},
    'plans': {'en': 'Plans', 'ru': 'Тарифы', 'es': 'Planes'},
    'choose_plan': {'en': 'Choose Plan', 'ru': 'Выберите тариф', 'es': 'Elige Plan'},
    'plans_description': {
      'en': 'Get unlimited access to AI-generated video catalog.',
      'ru': 'Получите неограниченный доступ к каталогу AI-видео.',
      'es': 'Obtén acceso ilimitado al catálogo de videos IA.',
    },
    'most_popular': {'en': 'Most Popular', 'ru': 'Популярный', 'es': 'Más Popular'},
    'select_plan': {'en': 'Select Plan', 'ru': 'Выбрать', 'es': 'Seleccionar'},
    'current_plan': {'en': 'Current Plan', 'ru': 'Текущий тариф', 'es': 'Plan Actual'},
    'active_subscription': {'en': 'Active Subscription', 'ru': 'Активная подписка', 'es': 'Suscripción Activa'},
    'no_subscription': {'en': 'No active subscription', 'ru': 'Нет подписки', 'es': 'Sin suscripción'},
    'profile': {'en': 'Profile', 'ru': 'Профиль', 'es': 'Perfil'},
    'language': {'en': 'Language', 'ru': 'Язык', 'es': 'Idioma'},
    'days_access': {'en': 'days access', 'ru': 'дней доступа', 'es': 'días de acceso'},
  };

  // =========================================================================
  // Accessor methods
  // =========================================================================

  String _t(String key) => _translations[key]?[_lang] ?? _translations[key]?['en'] ?? key;

  String get siteName => _t('site_name');
  String get siteDisclaimer => _t('site_disclaimer');
  String get ageVerificationTitle => _t('age_verification_title');
  String get ageVerificationDescription => _t('age_verification_description');
  String get ageVerificationConfirm => _t('age_verification_confirm');
  String get ageVerificationEnter => _t('age_verification_enter');
  String get ageVerificationLeave => _t('age_verification_leave');
  String get ageVerificationLegal => _t('age_verification_legal');
  String get login => _t('login');
  String get register => _t('register');
  String get logout => _t('logout');
  String get email => _t('email');
  String get password => _t('password');
  String get confirmPassword => _t('confirm_password');
  String get name => _t('name');
  String get videos => _t('videos');
  String get videoCatalog => _t('video_catalog');
  String get allCategories => _t('all_categories');
  String get allVideos => _t('all_videos');
  String get premiumOnly => _t('premium_only');
  String get freeOnly => _t('free_only');
  String get searchVideos => _t('search_videos');
  String get noVideosFound => _t('no_videos_found');
  String get views => _t('views');
  String get premiumContent => _t('premium_content');
  String get freeContent => _t('free_content');
  String get upgradeToWatch => _t('upgrade_to_watch');
  String get subscribeNow => _t('subscribe_now');
  String get plans => _t('plans');
  String get choosePlan => _t('choose_plan');
  String get plansDescription => _t('plans_description');
  String get mostPopular => _t('most_popular');
  String get selectPlan => _t('select_plan');
  String get currentPlan => _t('current_plan');
  String get activeSubscription => _t('active_subscription');
  String get noSubscription => _t('no_subscription');
  String get profile => _t('profile');
  String get language => _t('language');

  String daysAccess(String days) => '$days ${_t('days_access')}';
  String yourSubscriptionUntil(String date) {
    switch (_lang) {
      case 'ru': return 'Подписка активна до $date';
      case 'es': return 'Suscripción activa hasta $date';
      default: return 'Subscription active until $date';
    }
  }
}

class _AppLocalizationsDelegate extends LocalizationsDelegate<AppLocalizations> {
  const _AppLocalizationsDelegate();

  @override
  bool isSupported(Locale locale) =>
      ['en', 'ru', 'es'].contains(locale.languageCode);

  @override
  Future<AppLocalizations> load(Locale locale) async =>
      AppLocalizations(locale);

  @override
  bool shouldReload(covariant LocalizationsDelegate<AppLocalizations> old) =>
      false;
}
