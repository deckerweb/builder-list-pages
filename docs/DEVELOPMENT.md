# Development

The source requires no Composer, npm build or external dependency at runtime.
Registry owns the builder data; Query owns query scope, combination and counts;
Admin owns native lists and menus; Settings owns the three switches and documentation.
The original `blp/filter/builder-data` filter and builder URL IDs are retained.

## Extend a builder

Add or modify entries on `blp/filter/builder-data` with `builder-id`, `is-active`,
`builder-types` (array of registered post type names), `meta-key`, `meta-value`,
`meta-compare` (`=` or `EXISTS`), `label`, and optional `with-label`.
Inactive entries are ignored. Resolved data is cached for one request, after
initialization and for the current user. Apply extensions before admin_menu.
The reserved builder ID `none` represents the complement of all active rules.
No unrestricted SQL or arbitrary callbacks are accepted through this filter.

## Run integration checks

Provide a disposable, installed WordPress 6.7+ instance with this plugin active:

```sh
php tests/integration.php /absolute/path/to/wordpress/wp-load.php
```

The tests insert and delete their own BLP-named fixtures and two temporary users;
never run them on a production database. They deliberately simulate builder
constants and metadata. They do not replace tests with real builder products.

## Build

```sh
python3 tools/build-release.py --output /absolute/path/to/output-directory
```

The build creates an installable ZIP with the stable `builder-list-pages/` root,
a standalone list-only PHP snippet, a Code Snippets JSON export, source ZIP,
artwork ZIP and SHA256SUMS.txt. Test fixtures, editor files and artwork alternatives
are omitted from the plugin ZIP. All required runtime files are checked before packaging.

## Shared components

Updater V2 is the unmodified shared file from Brand Admin Schemes main, with a
plugin-scoped integration for artwork, HTTP bounds and candidate validation.
Library 0.2.0 is the local approved embedding kit from 2026-10-01, not a newly
published central library release. It elects one runtime alongside other hosts.
The pinned catalog stays intact; RCs are not added to its approval list.
