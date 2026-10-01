# User guide

Builder List Pages 1.1.0-rc1 · [FAQ](FAQ-English.md) · [Deutsch](Deutsch.md)

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

