# Builder List Pages

![Builder List Pages](assets/banner-1544x500.png)

**Your pages. Your builders. One clear overview.** Recognize builders in your content lists, filter Elementor, Bricks and other builder pages, and keep working in familiar WordPress screens. Useful for mixed websites, handovers and builder migrations.

**Version:** 1.1.0 · **Requires:** WordPress 6.7+ / PHP 8.0+ · **License:** GPL v2 or later

[Deutsch](README-de.md) · [User guide](docs/wiki/English.md) · [Extended FAQ](docs/wiki/FAQ-English.md) · [GitHub Releases](https://github.com/deckerweb/builder-list-pages/releases)

## Contents

- [At a glance](#at-a-glance)
- [Installation and first filter](#installation-and-first-filter)
- [Builders and recognition](#builders-and-recognition)
- [Settings](#settings)
- [Updates and Library](#updates-and-library)
- [FAQ](#faq)
- [Changelog](#changelog)
- [About](#about)

## At a glance

- **Recognize builders:** clickable Builder column, including multiple matches per item.
- **Filter quickly:** independent views for multiple active builders and “No recognized builder”.
- **Keep working:** builder selection stays in searches and standard list filters.
- **Familiar navigation:** builder submenus respect the post type’s editing permissions.
- **Little setup:** three optional switches, German translations, local guide and full changelog.
- **DECKERWEB integrated:** embedded GitHub Updater V2 and Plugin Library 0.2.0.

## Installation and first filter

1. Upload the test ZIP through **Plugins → Add New → Upload Plugin**, replacing the old plugin version if installed.
2. Activate it and open **Pages** or another content list enabled by an active supported builder.
3. Click a builder above the table. Then use the normal search if needed.
4. Adjust optional choices under **Settings → Builder List Pages**.

Use either the plugin or the separately generated snippet export. The modular main PHP file is no longer a standalone snippet. The export contains list functionality with default switches, without settings, assets, Library or updates.

## Builders and recognition

Elementor, Bricks, Breakdance, Oxygen 6+, Oxygen Classic, Brizy, Beaver Builder, ZionBuilder, Thrive Architect, Pagelayer and Visual Composer. Visual Composer retains the free edition’s page/post scope. Astra and OceanWP post types retain their special menu navigation when available.

Recognition reads existing metadata for active supported builders enabled on the post type. “No recognized builder” does not automatically mean Gutenberg. Inactive-builder data and global template assignments are not analyzed. Existence-based integrations retain their previous semantics even for empty values.

Counts describe the complete readable group outside trash, including unpublished content. Search and other list filters can reduce visible results. Builder groups can overlap.

## Settings

Enable or disable the Builder column, builder submenus and no-builder filter site-wide. Users can also hide the column individually through **Screen Options**. Administrators change settings; editors use list features according to normal editing permissions.

## Updates and Library

Stable GitHub releases appear through the deckerweb updater in the regular WordPress update system. Install prereleases manually. The updater does not enable automatic updates.

The embedded Library adds **Plugins → Add New → deckerweb**. It installs nothing automatically and has its own visibility and online catalog settings. Its local catalog contains approved releases with checksums; this RC does not replace the existing stable 1.0.0 catalog entry.

## FAQ

**Do I need to configure anything first?** No. Activate the plugin and open a content list that an active supported builder can edit. Views, the Builder column and builder submenus are enabled by default.

**Why is no builder view visible?** The builder must be active, allow the current post type, and provide recognized settings. Your user also needs editing access to that type. Invalid, missing and hidden post types are excluded.

**What does “No recognized builder” mean?** No configured condition matched among active supported builders enabled for this post type. It does not prove the page uses Gutenberg.

**Can one page show two builders?** Yes. Each matching builder is shown; old metadata can remain after a migration. Builder groups may overlap and their counts cannot simply be added.

**Can I hide the column?** Yes, individually in Screen Options on the list, or site-wide in Settings → Builder List Pages.

**How do updates work?** The bundled deckerweb GitHub Updater V2 checks public stable releases and integrates with the regular WordPress update screens. No separate updater plugin is required. The updater does not enable automatic updates.

**Does it change or clean up my page content?** No. It reads builder metadata and filters admin lists. No conversion, builder cleanup or frontend assets are included.

[More answers by topic](docs/wiki/FAQ-English.md)

## Changelog

### 1.1.0 · 2026-10-01

- **Misc:** Publishes 1.1.0 as a stable release after successful customer validation of the Admin Cleaner fix. Includes the features and fixes from rc1 and rc2.

### 1.1.0-rc2 · 2026-10-01

- **Fixed:** Prevents WP Admin Cleaner from incorrectly blocking builder lists when a hidden Bricks menu slug matches the end of their URL. Existing list links are normalized automatically while preserving search and filters.
- **Misc:** Confirms artwork A as the final icon and banner.

### 1.1.0-rc1 · 2026-10-01

- **New:** Optional Builder column with clickable builder names and overlapping matches.
- **New:** Independent views and submenus for multiple active builders.
- **New:** “No recognized builder” filter for content outside the detected builder groups.
- **New:** Compact settings page with deckerweb header, footer, local documentation and changelog dialog.
- **Improved:** Retains the builder selection when searching and filtering the content list.
- **Improved:** Centralizes builder metadata rules and resolves settings once per request.
- **Improved:** Counts readable published and unpublished content outside trash using ID-only queries.
- **Fixed:** Validates URL parameters and targets only the main supported admin list.
- **Fixed:** Preserves existing meta query groups and handles missing or malformed builder options.
- **Fixed:** Uses post-type editing permissions for builder submenus instead of theme permissions.
- **Misc:** Integrates the shared deckerweb GitHub Updater V2 and embedded Plugin Library 0.2.0.
- **Misc:** Raises minimum PHP to 8.0 for the embedded Library; WordPress 6.7 remains the minimum.
- **Misc:** Adds bilingual readmes, guides, FAQs, translations, release tooling and regression checks.
- **Misc:** Refreshes icon and English/German banners; includes three design alternatives in the source package.

### 1.0.0 · 2025-04-11

- **New:** Initial public release.

[Complete changelog](docs/CHANGELOG.md)

## About

Created by David Decker – DECKERWEB. © 2019–2026. [Support the project](https://ko-fi.com/deckerweb).

The plugin reads builder data; it does not change content or load frontend assets. Real builders, other PHP/WordPress versions, Multisite and ClassicPress have not been comprehensively checked for this RC.
