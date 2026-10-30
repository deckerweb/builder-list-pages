# Special cases and recognition limits

[Deutsch](Recognition-Deutsch.md) · [Builder overview](Builders-English.md)

## Mosaic

Recognizes direct stored layout assignments in the current Mosaic theme. Automatic template assignments, deleted layouts and global headers/footers do not count. Mosaic is not evaluated while WordPress is switched to another multisite site within the same request. Mosaic requires WordPress 6.9 and PHP 8.2; Builder List Pages retains WordPress 6.7 and PHP 8.1.

## Template imports and theme extensions

Kadence Starter Templates and Brainstorm Force Starter Templates are not separate builders. Recognition follows the imported Kadence, Spectra or other builder content. The importers need not remain active. Kadence Theme Kit Pro and Beaver Themer can assign global layouts; that does not prove direct use in page content.

## Block usage and premium features

A recognized block proves use of a block family. It confirms neither a premium license nor availability of every dynamic feature. Extensions of existing blocks are not always separately detectable. The Kadence page wizard alone does not count as page content. Generic CSS and the shared Spectra AI block are not treated as unique builder evidence.

## Reusable content

Nested blocks and readable published synced patterns are inspected. Traversal is limited to 20 reference levels and 200 pattern references per content item to avoid cycles. Other external templates and content returned by queries are not automatically followed.

## Counts, privacy and performance

Counts cover readable content outside trash. Search and other list filters may reduce visible results. Mixed pages can appear in several builder groups. Recognition does not change content, render builders or send page content to external services. Results are cached only for the current request.

## Genesis Blocks and Atomic Blocks

Genesis Blocks is recognized from stored Genesis blocks. Unchanged older Atomic Blocks content is not automatically attributed to Genesis. Builder List Pages does not convert content.

## Thrive Architect and Theme Builder

Recognition follows Architect markers stored directly on content. Thrive Theme Builder can also load the Architect core. Its activation or a globally assigned theme template therefore does not prove direct Architect editing of every page. Elementor Pro is similarly recognized within the Elementor family; global Pro templates do not automatically count as directly edited page content.


## Content-type selection

Automatic selection combines native builder support with WordPress type properties. Known administrative records such as menus, redirect rules, consent records, form management and logs are omitted. `post_content` alone is not an eligibility test: builders may store content in metadata, and technical records may contain text.

Under **Settings → Builder List Pages → Adjust content types**, each explicit site override takes precedence over automatic classification. **Include** checks stored signatures; native builder support, editing permissions and hard system exclusions still apply. Explicit choices can include non-public custom types without the standard editor. **Hide** applies to the overview, column, filters, submenus, commands and accessible CSV/favorite types. Content and builder settings are retained.

### Developer filter

`deckerweb_builder_list_pages_content_type_allowed` receives the automatic decision, the `WP_Post_Type` object and the builder ID. Return a Boolean to change the automatic selection. Explicit site overrides apply afterward. The filter cannot bypass permissions, system exclusions or native builder type restrictions.

```php
add_filter( 'deckerweb_builder_list_pages_content_type_allowed', function ( $allowed, $type, $builder ) {
    if ( 'my_private_portfolio' === $type->name && 'oxygen-classic' === $builder ) {
        return true;
    }
    return $allowed;
}, 10, 3 );
```

The `blp_settings[post_type_rules]` settings belong to the current site. Temporarily unregistered types retain their choices. Automatic removes an override. Deactivation and uninstall retain these settings; foreign content is never modified.

## Etch

Directly stored Etch blocks are recognized, including mixed content and explicitly embedded synced patterns. Etch must be loaded and available for active filters. The Etch theme alone, global headers and footers, and automatically applied templates are not attributed to a page.

## CrocoBuilder

The content’s native builder activation flag determines recognition. After returning to the WordPress editor, retained layout data alone does not count. An enabled item without a saved layout can therefore also appear as CrocoBuilder. CrocoBuilder’s allowed content types apply in addition to this plugin’s settings. Requires WordPress 7.0 and PHP 8.2.

## Builderius

Free and Pro form one integration. Recognition covers enabled page templates directly owned by a content item with an explicit matching content-ID rule. Additional contextual rules, global templates and automatic assignments do not count. Assignment describes the saved editor state, not the live deployed release or its content. Disabled templates do not match. Custom content types require the corresponding Pro setting. Requires PHP 8.2; tested with Free and Pro 1.4-beta. On older PHP this integration stays paused without disabling other builders.
