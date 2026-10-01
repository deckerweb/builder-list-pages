# Frequently asked questions

[Guide](English.md) · [Deutsch](FAQ-Deutsch.md)

Answers for test release 1.1.0-rc2. Planned features are not described as available.

## Getting started

### Do I need to configure anything first?

No. Activate the plugin and open a content list that an active supported builder can edit. Views, the Builder column and builder submenus are enabled by default.

### Which requirements apply?

WordPress 6.7+ and PHP 8.0+. This test release was checked on WordPress 6.7 and PHP 8.4.5 using a disposable SQLite installation. Other versions and real builder installations need testing.

### Is it free?

Yes. GPL v2 or later. Some supported builders require their own paid license.

### Which builders are supported?

Elementor, Bricks, Breakdance, Oxygen 6+, Oxygen Classic, Brizy, Beaver Builder, ZionBuilder, Thrive Architect, Pagelayer and Visual Composer. Visual Composer currently uses the free edition’s page/post scope.

### Why is no builder view visible?

The builder must be active, allow the current post type, and provide recognized settings. Your user also needs editing access to that type. Invalid, missing and hidden post types are excluded.

### Does it work with custom post types?

Yes, where an active builder allows them and the post type has an admin interface. Attachments are excluded.

## Recognition and filters

### What does the Builder column tell me?

It shows matching metadata for active supported builders allowed on that post type. It is not a full analysis of frontend rendering or template assignments.

### Can one page show two builders?

Yes. Each matching builder is shown; old metadata can remain after a migration. Builder groups may overlap and their counts cannot simply be added.

### What does “No recognized builder” mean?

No configured condition matched among active supported builders enabled for this post type. It does not prove the page uses Gutenberg.

### Are inactive builders detected?

Not in 1.1.0. Their remaining data does not form a detected group. An inventory of inactive-builder data is a future feature.

### Does it recognize builder templates applied to normal pages?

No. Recognition is based on the page’s own metadata, not on global templates or theme-builder conditions.

### Why can an empty page still appear under Oxygen or Breakdance?

These integrations retain the existing metadata-existence rule. An empty stored value still counts as present; it is not a content-quality check.

### How can I combine builder and search?

Select a builder, then search the list or use its normal filters. The builder ID is carried in the native list form. All or another standard view resets the builder selection.

### Do draft and private pages count?

The badges count readable published, future, draft, pending and private content, according to registered admin statuses. Trash and auto-drafts are excluded. Users unable to edit other authors’ content receive counts for their own content.

### Why does the count exceed the visible search results?

Badges describe the complete readable builder group outside trash. Search, date, author and other list constraints can reduce the rows currently shown.

### Do other plugin filters still work?

Existing meta query groups are kept and combined with the selected builder using AND. The no-builder filter adds its exclusion separately. Third-party query hooks can still affect results.

## Settings and permissions

### Who can change settings?

Users with manage_options, normally administrators. Content filtering uses each post type’s own editing permissions.

### Can editors use the builder submenus?

Yes, when they can edit the corresponding post type. They do not need edit_theme_options.

### Can I hide the column?

Yes, individually in Screen Options on the list, or site-wide in Settings → Builder List Pages.

### Can I hide the extra submenus?

Yes, site-wide in Settings → Builder List Pages. Builder views above the list remain available.

### Are these settings per user or per site?

The three plugin switches apply to the current site. WordPress Screen Options provide each user’s separate column visibility.

### What about Multisite?

Settings stay per site; network activation loads the plugin on each site. The embedded Library provides its own network-aware controls. This RC has not been fully tested in Multisite.

## Updates, Library and troubleshooting

### How do updates work?

The bundled deckerweb GitHub Updater V2 checks public stable releases and integrates with the regular WordPress update screens. No separate updater plugin is required. The updater does not enable automatic updates.

### Will this RC automatically install other RCs?

No. Prereleases are excluded by the stable-release updater. Install test ZIPs manually; a newer stable 1.1.0 can replace this RC later.

### What is the deckerweb Library?

An embedded discovery and installation catalog under Plugins → Add New → deckerweb. The standard WordPress tab remains the default. Library visibility and online catalog preferences have their own settings.

### Does the Library download plugins automatically?

No. Installation requires an explicit action, capabilities and nonce checks. The embedded catalog is local; optional online catalog refresh must be enabled separately.

### Does the Library overwrite newer installed plugins?

No. The embedded installer does not replace an existing plugin directory or downgrade this RC to the pinned 1.0.0 catalog release.

### Does it send usage tracking?

Builder List Pages contains no usage telemetry. GitHub update checks contact GitHub; optional catalog requests contact the configured approved endpoint. Servers see the requesting IP. Clicking Install downloads the selected release.

### Can I use it as a code snippet?

A separately generated PHP and Code Snippets JSON export provides views, columns and submenus with the default switches. It excludes plugin settings, bundled assets, updater and Library. Activate either the plugin or the snippet, never both. The modular plugin main file is no longer a standalone snippet.

### Does it change or clean up my page content?

No. It reads builder metadata and filters admin lists. No conversion, builder cleanup or frontend assets are included.

### Is ClassicPress supported?

The previous version documented ClassicPress compatibility. This RC, its native Update URI integration and the embedded Library have not been verified on ClassicPress; do not assume support from old documentation.

### What should I report when recognition looks wrong?

Include plugin and builder versions, post type, user role, selected filter and expected result. Use a test copy and avoid sharing private page content or credentials.


## Why does WP Admin Cleaner block the Bricks page list?

WP Admin Cleaner 2.0.1 matches hidden menu entries against the end of the URL. Hiding the Bricks Getting Started menu makes its `bricks` slug also match `edit.php?post_type=page&builder=bricks`. Starting with 1.1.0-rc2, Builder List Pages automatically appends `&blp_view=1` to its list URLs while retaining search and filters. The hidden Bricks screen stays blocked, and Admin Cleaner can remain active.
