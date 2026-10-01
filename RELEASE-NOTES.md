# Builder List Pages 1.1.0-rc1

Test release for WordPress 6.7+ and PHP 8.0+. Upload the installable
`builder-list-pages-1.1.0-rc1.zip` and replace the existing plugin.
Disable any older snippet version first.

See [the German test plan](docs/TESTING-de.md) for the manual checks.
Builder integration checks use metadata fixtures; real builder versions still
need testing. Artwork A is provisional; three alternatives are included.

## Changes

## 1.1.0-rc1 · 2026-10-01

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

## 1.0.0 · 2025-04-11

- **New:** Initial public release with views and submenus for eleven builders.
