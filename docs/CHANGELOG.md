# Changelog

## 1.2.2 · 2026-10-22

- **Improved:** Separates updater caches by implementation version.
- **Fixed:** Updates the embedded deckerweb Updater to 2.2.1 so this plugin retains its update engine when another plugin loads an older updater first.

## 1.2.1 · 2026-10-21

- **Improved:** Updates the embedded deckerweb Library to 1.1.0 and Updater to 2.2.0, including host translations and verified catalog-based updates.
- **Fixed:** Validates packages during native bulk updates as well as individual updates.
- **Fixed:** Keeps closed changelog dialogs hidden under conflicting styles and preserves native close and documentation-link fallbacks.
- **Misc:** Requires PHP 8.1; WordPress 6.7 remains supported. Existing settings, favorites and builder content are retained.

## 1.2.0 · 2026-10-10

- **New:** Adds a multiple-builder filter, optional inactive-trace audit, personal saved views, compact user display choice, minimal copyable diagnostics and an explicit CSV handover export.
- **Improved:** Explains empty results with independent search/builder recovery and clearer badge actions; adds permission-checked native Bricks editor links.
- **Improved:** Preserves personal favorites and preferences across deactivation and uninstall; limits exports to permitted items and neutralizes spreadsheet formulas.
- **Improved:** Updates bilingual banners and icons using the approved blue-petrol vector artwork and local Inter font outlines with the font license.
- **Improved:** Integrates deckerweb Library 0.9.0 and Updater 2.1.0 with complete German informal/formal host translations and synchronized local documentation.
- **Improved:** Displays the plugin icon once in the settings header; keeps version, documentation and author information in the footer.
- **Fixed:** Places native admin notices below the complete settings header and clarifies the column and post-type admin menu labels.
- **Misc:** Preserves existing site settings, builder content and minimum requirements; includes the local Inter font license.

## 1.1.0 · 2026-10-01

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
- **Fixed:** Prevents WP Admin Cleaner from incorrectly blocking builder lists when a hidden Bricks menu slug matches the end of their URL. Existing list links are normalized automatically while preserving search and filters.
- **Misc:** Integrates the shared deckerweb GitHub Updater V2 and embedded Plugin Library 0.2.0.
- **Misc:** Raises minimum PHP to 8.0 for the embedded Library; WordPress 6.7 remains the minimum.
- **Misc:** Adds bilingual readmes, guides, FAQs, translations, release tooling and regression checks.

## 1.0.0 · 2025-04-11

- **New:** Initial public release with views and submenus for eleven builders.
