import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/theme.dart';
import '../models/video.dart';
import '../services/api_service.dart';
import '../services/auth_service.dart';
import '../widgets/video_card.dart';
import '../l10n/app_localizations.dart';

/// Catalog Screen — AIVidCatalog18
///
/// Scrollable grid of video thumbnails with search, category filter,
/// and infinite pagination.
///
/// Strictly fictional AI-generated content — no illegal/prohibited material.
/// All content is synthetic. No real persons depicted.

class CatalogScreen extends StatefulWidget {
  const CatalogScreen({super.key});

  @override
  State<CatalogScreen> createState() => _CatalogScreenState();
}

class _CatalogScreenState extends State<CatalogScreen> {
  final ScrollController _scrollController = ScrollController();
  final TextEditingController _searchController = TextEditingController();

  List<Video> _videos = [];
  bool _isLoading = false;
  bool _hasMore = true;
  int _currentPage = 1;
  String? _selectedCategory;
  bool? _premiumFilter;
  String _searchQuery = '';

  // Available categories
  final List<String> _categories = [
    'general',
    'fantasy',
    'anime',
    'roleplay',
    'artistic',
    'cinematic',
  ];

  @override
  void initState() {
    super.initState();
    _loadVideos();
    _scrollController.addListener(_onScroll);
  }

  @override
  void dispose() {
    _scrollController.dispose();
    _searchController.dispose();
    super.dispose();
  }

  void _onScroll() {
    if (_scrollController.position.pixels >=
            _scrollController.position.maxScrollExtent - 200 &&
        !_isLoading &&
        _hasMore) {
      _loadMoreVideos();
    }
  }

  Future<void> _loadVideos() async {
    setState(() {
      _isLoading = true;
      _currentPage = 1;
      _videos = [];
    });

    try {
      final api = context.read<ApiService>();
      final response = await api.getVideos(
        page: 1,
        category: _selectedCategory,
        premium: _premiumFilter,
        query: _searchQuery.isNotEmpty ? _searchQuery : null,
      );

      final videoList = VideoListResponse.fromJson(response.data);
      setState(() {
        _videos = videoList.videos;
        _hasMore = videoList.hasMore;
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
    }
  }

  Future<void> _loadMoreVideos() async {
    if (_isLoading || !_hasMore) return;

    setState(() => _isLoading = true);
    _currentPage++;

    try {
      final api = context.read<ApiService>();
      final response = await api.getVideos(
        page: _currentPage,
        category: _selectedCategory,
        premium: _premiumFilter,
        query: _searchQuery.isNotEmpty ? _searchQuery : null,
      );

      final videoList = VideoListResponse.fromJson(response.data);
      setState(() {
        _videos.addAll(videoList.videos);
        _hasMore = videoList.hasMore;
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
    }
  }

  void _onSearch(String query) {
    _searchQuery = query;
    _loadVideos();
  }

  void _onCategoryChanged(String? category) {
    _selectedCategory = category;
    _loadVideos();
  }

  void _onPremiumFilterChanged(bool? premium) {
    _premiumFilter = premium;
    _loadVideos();
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final locale = context.watch<AuthService>().currentLocale;

    return Scaffold(
      appBar: AppBar(
        title: Text(l10n.videoCatalog),
        actions: [
          // Premium filter
          PopupMenuButton<bool?>(
            icon: Icon(
              Icons.filter_list,
              color: _premiumFilter != null
                  ? AppTheme.primary
                  : AppTheme.textMuted,
            ),
            onSelected: _onPremiumFilterChanged,
            itemBuilder: (_) => [
              PopupMenuItem(value: null, child: Text(l10n.allVideos)),
              PopupMenuItem(
                  value: true,
                  child: Row(children: [
                    const Icon(Icons.star, color: AppTheme.premium, size: 18),
                    const SizedBox(width: 8),
                    Text(l10n.premiumOnly),
                  ])),
              PopupMenuItem(value: false, child: Text(l10n.freeOnly)),
            ],
          ),
        ],
      ),
      body: Column(
        children: [
          // Search bar
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 4),
            child: TextField(
              controller: _searchController,
              decoration: InputDecoration(
                hintText: l10n.searchVideos,
                prefixIcon: const Icon(Icons.search),
                suffixIcon: _searchQuery.isNotEmpty
                    ? IconButton(
                        icon: const Icon(Icons.clear),
                        onPressed: () {
                          _searchController.clear();
                          _onSearch('');
                        },
                      )
                    : null,
              ),
              onSubmitted: _onSearch,
            ),
          ),

          // Category chips
          SizedBox(
            height: 48,
            child: ListView(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 12),
              children: [
                _CategoryChip(
                  label: l10n.allCategories,
                  selected: _selectedCategory == null,
                  onTap: () => _onCategoryChanged(null),
                ),
                ..._categories.map((cat) => _CategoryChip(
                      label: cat[0].toUpperCase() + cat.substring(1),
                      selected: _selectedCategory == cat,
                      onTap: () => _onCategoryChanged(cat),
                    )),
              ],
            ),
          ),

          // Video grid
          Expanded(
            child: _videos.isEmpty && !_isLoading
                ? Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Icon(Icons.videocam_off,
                            size: 64, color: AppTheme.textMuted),
                        const SizedBox(height: 16),
                        Text(l10n.noVideosFound,
                            style:
                                const TextStyle(color: AppTheme.textSecondary)),
                      ],
                    ),
                  )
                : RefreshIndicator(
                    onRefresh: () async => _loadVideos(),
                    child: GridView.builder(
                      controller: _scrollController,
                      padding: const EdgeInsets.all(12),
                      gridDelegate:
                          const SliverGridDelegateWithFixedCrossAxisCount(
                        crossAxisCount: 2,
                        childAspectRatio: 0.72,
                        crossAxisSpacing: 10,
                        mainAxisSpacing: 10,
                      ),
                      itemCount: _videos.length + (_hasMore ? 1 : 0),
                      itemBuilder: (context, index) {
                        if (index >= _videos.length) {
                          return const Center(
                            child: Padding(
                              padding: EdgeInsets.all(16),
                              child: CircularProgressIndicator(),
                            ),
                          );
                        }
                        return VideoCard(
                          video: _videos[index],
                          locale: locale,
                          onTap: () => Navigator.pushNamed(
                            context,
                            '/video/${_videos[index].id}',
                          ),
                        );
                      },
                    ),
                  ),
          ),
        ],
      ),
    );
  }
}

class _CategoryChip extends StatelessWidget {
  final String label;
  final bool selected;
  final VoidCallback onTap;

  const _CategoryChip({
    required this.label,
    required this.selected,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 4),
      child: FilterChip(
        label: Text(label),
        selected: selected,
        onSelected: (_) => onTap(),
        selectedColor: AppTheme.primary.withOpacity(0.2),
        checkmarkColor: AppTheme.primary,
        backgroundColor: AppTheme.surfaceLight,
        side: BorderSide(
          color: selected ? AppTheme.primary : AppTheme.border,
        ),
        labelStyle: TextStyle(
          color: selected ? AppTheme.primary : AppTheme.textSecondary,
          fontSize: 12,
        ),
      ),
    );
  }
}
