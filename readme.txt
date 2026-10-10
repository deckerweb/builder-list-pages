=== Builder List Pages ===
Contributors: deckerweb
Tags: admin, pages, page builder, filter, post types
Requires at least: 6.7
Requires PHP: 8.0
Stable tag: 1.1.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Find pages by builder and keep working in familiar WordPress content lists.

== Description ==

Your pages. Your builders. One clear overview.

= At a glance =

- Recognize builders: clickable Builder column, including multiple matches per item.
- Filter quickly: independent views for multiple active builders and “No recognized builder”.
- Keep working: builder selection stays in searches and standard list filters.
- Familiar navigation: builder submenus respect the post type’s editing permissions.
- Little setup: three optional switches, German translations, local guide and full changelog.
- DECKERWEB integrated: embedded GitHub Updater 2.1.0 and Plugin Library 0.9.0.

= Builders and recognition =

Elementor, Bricks, Breakdance, Oxygen 6+, Oxygen Classic, Brizy, Beaver Builder, ZionBuilder, Thrive Architect, Pagelayer and Visual Composer. Visual Composer retains the free edition’s page/post scope. Astra and OceanWP post types retain their special menu navigation when available.

Recognition reads existing metadata for active supported builders enabled on the post type. “No recognized builder” does not automatically mean Gutenberg. Inactive-builder data and global template assignments are not analyzed. Existence-based integrations retain their previous semantics even for empty values.

Counts describe the complete readable group outside trash, including unpublished content. Search and other list filters can reduce visible results. Builder groups can overlap.

= Settings =

Enable or disable the Builder column, builder submenus and no-builder filter site-wide. Users can also hide the column individually through Screen Options. Administrators change settings; editors use list features according to normal editing permissions.

The local changelog opens in a keyboard-accessible dialog. Close it with Escape, the Close button or a click outside the dialog; focus returns to the link.

= Updates and Library =

Stable GitHub releases appear in the regular WordPress update system through deckerweb Updater 2.1.0. No separate updater plugin is needed; automatic updates stay under your control.

The embedded deckerweb Library 0.9.0 adds Plugins → Add New → deckerweb, with a local approved catalog, series filters and plugin details. The standard WordPress tab remains the default. Installation, activation and updates require an explicit permitted action. Online catalog refresh is optional and off by default.

The Library follows the active compatible copy when multiple deckerweb plugins are installed. Its preferences are separate from the three Builder List Pages display settings.

= About =

Created and maintained by David Decker – DECKERWEB. © 2019–2026. Builder List Pages began in 2019 and complements native content lists; it reads metadata without converting pages or loading frontend assets.

= Issues, security and support =

Report reproducible problems through [GitHub Issues](https://github.com/deckerweb/builder-list-pages/issues). Report security vulnerabilities privately using the [security policy](https://github.com/deckerweb/builder-list-pages/blob/main/SECURITY.md); never publish exploit details or credentials in an issue.

Support development through [Ko-fi](https://ko-fi.com/deckerweb), [Buy Me a Coffee](https://buymeacoffee.com/daveshine) or [PayPal](https://paypal.me/deckerweb).

= License and included components =

GPL v2 or later. [License](https://github.com/deckerweb/builder-list-pages/blob/main/LICENSE). Includes deckerweb Plugin Library 0.9.0 and deckerweb Updater 2.1.0 by David Decker – DECKERWEB, both GPL-2.0-or-later. The shared changelog renderer originates from [Advanced Scripts QuickNav](https://github.com/deckerweb/advanced-scripts-quicknav), `includes/deckerweb-changelog-v1.php`, by the same author under GPL-2.0-or-later. No premium builder or Admin Cleaner code is included.

The existing banners and icon use native SVG shapes with solid fills and transparency; banners contain live system-font text, with no embedded font or raster files. PNG assets are local derivatives of the approved artwork.

== Installation ==

1. Upload the plugin ZIP through Plugins → Add New → Upload Plugin, replacing the old plugin version if installed.
2. Activate it and open Pages or another content list enabled by an active supported builder.
3. Click a builder above the table. Then use the normal search if needed.
4. Adjust optional choices under Settings → Builder List Pages.

== Frequently Asked Questions ==

= Do I need to configure anything first? =

No. Activate the plugin and open a content list that an active supported builder can edit. Views, the Builder column and builder submenus are enabled by default.

= Which builders are supported? =

Elementor, Bricks, Breakdance, Oxygen 6+, Oxygen Classic, Brizy, Beaver Builder, ZionBuilder, Thrive Architect, Pagelayer and Visual Composer. Visual Composer currently uses the free edition’s page/post scope.

= What does “No recognized builder” mean? =

No configured condition matched among active supported builders enabled for this post type. It does not prove the page uses Gutenberg.

= Can I hide the column? =

Yes, individually in Screen Options on the list, or site-wide in Settings → Builder List Pages.

= What about Multisite? =

The three display choices are stored separately for each site. Network activation loads the plugin on all sites, including newly created sites. The Library uses its own site and network controls; site administrators cannot manage its network preferences.

= Are prereleases installed automatically? =

No. The updater offers stable GitHub releases only. Install test packages manually when needed. It does not turn on automatic updates.

= What happens when I deactivate or uninstall it? =

Deactivation keeps all data. Uninstallation removes this plugin’s temporary update cache while keeping its three site settings and all builder content. Shared Library caches and tasks are removed only when no other installed host needs them, including inactive hosts. Library preferences remain unless its separate cleanup option is enabled. Plugins installed through the Library are never removed.

[Complete FAQ by topic](https://github.com/deckerweb/builder-list-pages/wiki/FAQ-English)

== Changelog ==

= 1.1.0 · 2026-10-01 =

* New: Optional Builder column with clickable builder names and overlapping matches.
* New: Independent views and submenus for multiple active builders.
* New: “No recognized builder” filter for content outside the detected builder groups.
* New: Compact settings page with deckerweb header, footer, local documentation and changelog dialog.
* Improved: Retains the builder selection when searching and filtering the content list.
* Improved: Centralizes builder metadata rules and resolves settings once per request.
* Improved: Counts readable published and unpublished content outside trash using ID-only queries.
* Fixed: Validates URL parameters and targets only the main supported admin list.
* Fixed: Preserves existing meta query groups and handles missing or malformed builder options.
* Fixed: Uses post-type editing permissions for builder submenus instead of theme permissions.
* Fixed: Prevents WP Admin Cleaner from incorrectly blocking builder lists when a hidden Bricks menu slug matches the end of their URL. Existing list links are normalized automatically while preserving search and filters.
* Misc: Integrates the shared deckerweb GitHub Updater V2 and embedded Plugin Library 0.2.0.
* Misc: Raises minimum PHP to 8.0 for the embedded Library; WordPress 6.7 remains the minimum.
* Misc: Adds bilingual readmes, guides, FAQs, translations, release tooling and regression checks.

= 1.0.0 · 2025-04-11 =

* New: Initial public release with views and submenus for eleven builders.
