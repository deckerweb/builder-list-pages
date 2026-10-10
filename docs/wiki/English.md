# User guide

Builder List Pages 1.1.0 · [FAQ](FAQ-English.md) · [Deutsch](Deutsch.md)

## At a glance

- **Recognize builders:** clickable Builder column, including multiple matches per item.
- **Filter quickly:** independent views for multiple active builders and “No recognized builder”.
- **Keep working:** builder selection stays in searches and standard list filters.
- **Familiar navigation:** builder submenus respect the post type’s editing permissions.
- **Little setup:** three optional switches, German translations, local guide and full changelog.
- **DECKERWEB integrated:** embedded GitHub Updater 2.1.0 and Plugin Library 0.9.0.

## Installation and first filter

1. Upload the plugin ZIP through **Plugins → Add New → Upload Plugin**, replacing the old plugin version if installed.
2. Activate it and open **Pages** or another content list enabled by an active supported builder.
3. Click a builder above the table. Then use the normal search if needed.
4. Adjust optional choices under **Settings → Builder List Pages**.

## Builders and recognition

Elementor, Bricks, Breakdance, Oxygen 6+, Oxygen Classic, Brizy, Beaver Builder, ZionBuilder, Thrive Architect, Pagelayer and Visual Composer. Visual Composer retains the free edition’s page/post scope. Astra and OceanWP post types retain their special menu navigation when available.

Recognition reads existing metadata for active supported builders enabled on the post type. “No recognized builder” does not automatically mean Gutenberg. The default views recognize active builders; the optional trace view checks inactive signatures. Global template assignments are not analyzed. Existence-based integrations retain their previous semantics even for empty values.

Counts describe the complete readable group outside trash, including unpublished content. Search and other list filters can reduce visible results. Builder groups can overlap.

## Settings

Enable or disable the Builder column, builder submenus and no-builder filter site-wide. Users can also hide the column individually through **Screen Options**. Administrators change settings; editors use list features according to normal editing permissions.

The local changelog opens in a keyboard-accessible dialog. Close it with Escape, the Close button or a click outside the dialog; focus returns to the link.

## Updates and Library

Stable GitHub releases appear in the regular WordPress update system through deckerweb Updater 2.1.0. No separate updater plugin is needed; automatic updates stay under your control.

The embedded deckerweb Library 0.9.0 adds **Plugins → Add New → deckerweb**, with a local approved catalog, series filters and plugin details. The standard WordPress tab remains the default. Installation, activation and updates require an explicit permitted action. Online catalog refresh is optional and off by default.

The Library follows the active compatible copy when multiple deckerweb plugins are installed. Its preferences are separate from the three Builder List Pages display settings.

## New tools and personal views

The **Multiple builders recognized** view collects content with at least two distinct active builder signatures. Duplicate copies of the same metadata field count once.

Open **Builder tools and personal views** above the native content list to select a view, save a named favorite, switch to a compact selector, inspect inactive builder traces, copy diagnostics or export a CSV. The tools remain collapsed by default; existing builder views remain the default. Bricks badges provide a separate editor link only when native Bricks permissions allow it.

**Inactive traces:** the optional read-only view checks known metadata signatures even when the corresponding builder is inactive. It does not activate builders, change content or prove which builder renders the frontend. Empty fields can count for existence-based signatures. Active builders disabled for a post type are not classified as inactive.

**Personal favorites:** save post type, builder, status and search for your own account on this site. Save the same name to update a favorite; up to 20 names are supported. Opening a favorite rechecks your access. Delete favorites individually; deactivation and uninstall retain them.

**Diagnostics:** select and copy the local text containing plugin, WordPress and PHP versions, supported active builder names and accessible post types. It omits website URLs, usernames, credentials and page contents. Nothing is automatically sent.

**CSV:** download title, status, recognized builders and edit link for the explicitly displayed builder/status/search selection, up to 5,000 readable and editable items. Other list filters are not included; the tool states this before download. CSV formulas are neutralized. Edit links contain the site URL, so the CSV is intended for a trusted handover, unlike the minimal support diagnostics.

An empty builder result explains the current builder, search and status. **Reset search** or **Remove builder filter** clears that one restriction while preserving the post type and other list filters.

Open **Recognition details** in a row to see the matched metadata field names and whether an empty value exists. No metadata values or builder content are displayed.

## About

Created and maintained by David Decker – DECKERWEB. © 2019–2026. Builder List Pages began in 2019 and complements native content lists; it reads metadata without converting pages or loading frontend assets.

## Issues, security and support

Report reproducible problems through [GitHub Issues](https://github.com/deckerweb/builder-list-pages/issues). Report security vulnerabilities privately using the [security policy](https://github.com/deckerweb/builder-list-pages/blob/main/SECURITY.md); never publish exploit details or credentials in an issue.

Support development through [Ko-fi](https://ko-fi.com/deckerweb), [Buy Me a Coffee](https://buymeacoffee.com/daveshine) or [PayPal](https://paypal.me/deckerweb).

## License and included components

GPL v2 or later. [License](https://github.com/deckerweb/builder-list-pages/blob/main/LICENSE). Includes deckerweb Plugin Library 0.9.0 and deckerweb Updater 2.1.0 by David Decker – DECKERWEB, both GPL-2.0-or-later. The shared changelog renderer originates from [Advanced Scripts QuickNav](https://github.com/deckerweb/advanced-scripts-quicknav), `includes/deckerweb-changelog-v1.php`, by the same author under GPL-2.0-or-later. No premium builder or Admin Cleaner code is included.

The banners and icon use native vector shapes. Banners include Inter lettering converted to paths, linear background gradients and transparency; no raster images, live fonts, SVG filters or external resources. Icons use native shapes and solid fills. PNGs are rendered from those same SVGs. Inter is free for commercial use under SIL Open Font License 1.1; [font license](https://github.com/deckerweb/builder-list-pages/blob/main/assets/Inter-OFL.txt) is included, without font files.
