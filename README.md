# Builder List Pages

**Simple & lightweight:** List those pages and post types which were edited with your favorite Page Builder. Adds additional _**Views**_ to the post type list tables, plus: _**Submenus**_.

![Builder List Pages plugin banner](https://repository-images.githubusercontent.com/964191686/df3eaa01-7f0a-4ba3-b272-4a19d44dbea1)

* Contributors: [David Decker](https://github.com/deckerweb), [contributors](https://github.com/deckerweb/builder-list-pages/graphs/contributors)
* Tags: pages, post-type, post, page builder, site builder, view, list, listing, admin
* Requires at least: 6.7
* Requires PHP: 7.4
* Stable tag: [main](https://github.com/deckerweb/builder-list-pages/releases/latest)
* Donate link: https://paypal.me/deckerweb
* License: GPL v2 or later

---

[Support Project](#support-the-project) | [Installation](#installation) | [Updates](#updates) | [Description](#description) | [Features](#features) | [Page Builders](#supported-page-builders-%EF%B8%8F) | [FAQ](#frequently-asked-questions) | [Changelog](#changelog) | [Plugin Scope / Disclaimer](#plugin-scope--disclaimer)

---

## Support the Project

If you find this project helpful, consider showing your support by buying me a coffee! Your contribution helps me keep developing and improving this plugin.

Enjoying the plugin? Feel free to treat me to a cup of coffee ☕🙂 through the following options:

- [![ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/W7W81BNTZE)
- [Buy me a coffee](https://buymeacoffee.com/daveshine)
- [PayPal donation](https://paypal.me/deckerweb)
- [Join my **newsletter** for DECKERWEB WordPress Plugins](https://eepurl.com/gbAUUn)

---

## Installation 

#### **Quick Install – as Plugin**
1. **Download ZIP:** [**builder-list-pages.zip**](https://github.com/deckerweb/builder-list-pages/releases/latest/download/builder-list-pages.zip)
2. Upload via WordPress Plugins > Add New > Upload Plugin
3. Once activated, you can see the special page/post type listings under each type in the Admin
 
#### **Alternative: Use as Code Snippet**
1. Below, download the appropriate snippet version
2. activate or deactivate in your snippets plugin

[**Download .json**](https://github.com/deckerweb/builder-list-pages/releases/latest/download/ddw-builder-list-pages.code-snippets.json) version for: _Code Snippets_ (free & Pro), _Advanced Scripts_ (Premium), _Scripts Organizer_ (Premium)  
--> just use their elegant script import features  
--> in _Scripts Organizer_ use the "Code Snippets Import"  

For all other snippet manager plugins just use our plugin's main .php file [`builder-list-pages.php`](https://github.com/deckerweb/builder-list-pages/blob/master/builder-list-pages.php) and use its content as snippet (bevor saving your snippet: please check for your plugin if the opening php tag needs to be removed or not!).

--> Please decide for one of both alternatives!

### Tested Compatibility
- **WordPress**: 6.7.2 / 6.8 Beta
- **ClassicPress:** 2.4.0 / 2.4.1
- **PHP**: 8.0 – 8.3
- Requires at least: WP 6.7 or CP 2.0 / PHP 7.4

---

## Updates 

#### For Plugin Version:

1) Alternative 1: Just download a new [ZIP file](https://github.com/deckerweb/builder-list-pages/releases/latest/download/builder-list-pages.zip) (see above), upload and override existing version. Done.

2) Alternative 2: Use the (free) [**_Git Updater_ plugin**](https://git-updater.com/) and get updates automatically.

3) Alternative 3: Upcoming! – In future I will built-in our own deckerweb updater. This is currently being worked on for my plugins. Stay tuned!

#### For Code Snippet Version:

Just manually: Download the latest Snippet version (see above) and import it in your favorite snippets manager plugin. – You can delete the old snippet; then just activate the new one. Done.

---

## Description 

#### 💖 Currently 11 popular Page Builders are supported!

Very useful to "filter" for all pages or post types that were edited with your favorite Page Builder. For example, you have 30 pages and 10 of them were edited with Elementor, the rest with the default block editor. Now you, or your client, wants to filter only those 10 Elementor-edited pages. Builder List Pages does exactly that.

You get an additional **View** above the post list table, additional to: All / Draft / Deleted etc. – now it adds Elementor (10) or Bricks (7) – you get the idea.

Furthermore, in the Admin you get a new **Submenu** under that post type, that is linked to this **View** (= filter). It is always shown that way: "With {Name of Builder}", for example: "With Breakdance"

NOTE: All of that is only shown when the supported Builder is active, and in this Builder you selected the post types which are allowed to be edited with that Builder.

The plugin does support translations, so you can add your own language files to translate / adjust its few strings.

---

## Supported Page Builders 👷‍♂️ 

#### These Builders are Supported for Views & Submenus:
* _Elementor_ (free & Pro)
* _Bricks Builder_ (Premium)
* _Breakdance Builder_ (Premium)
* _Oxygen Builder_ (v6+) (Premium)
* _Oxygen Classic_ (Premium)
* _Brizy_ (free & Pro)
* _Beaver Builder_ (free & Pro)
* _ZionBuilder_ (free & Pro)
* _Thrive Architect_ (Premium)
* _Pagelayer Builder_ (free & Pro)
* _Visual Composer_ (free & Pro)
(currently only free version is supported, with Pages & Posts)

#### Compatibilty for special Post Types:
* _Astra Site Builder_ (Layouts) (part of _Astra Pro_ for the _Astra_ Theme)
* _OceanWP Library_ (part of _OceanWP_ Theme)

#### Compatibility with ClassicPress (fork of WP)
* This plugin itself is compatible!
* As long as the _Page Builder_ is compatible then you can use this plugin here as a perfect helper tool
* Current compatible Builders:
  * ZionBuilder - tested the free version
  * Beaver Builder - works (CP Forum)
  * Breakdance – I played around with the Pro version v2.3.0 and to my surprise it worked in CP 2.4.1 without any issues. So it also worked with my plugin.
  * I am sure, more builders from the above list will work fine. I just have not the time to test them all everytime...
  * Bricks Builder – I guess this one might work also, but note it needs a [compat plugin](https://github.com/Hakira-Shymuy/cpbricksfixes) (to make Bricks work with CP)

---

## Frequently Asked Questions 

### Why should it be important to have these Views/ Submenus?
Good question. This could be extremely useful if you have a lot of pages for example and only a few of them are built with your Page Builder. Then the additional **view** and **submenu** offer a "filter" to just query for those few pages. That makes total sense for Administrators, Editors, Clients and other use cases. This should be a default, to have these views. Sadly, most Builders just don't offer that.

### The View is showing (0) items?
That can happen if you have only items of that post type edited in your Builder but in _Draft_ state. Just **publish** this post type item. And also make sure that it contains at least _one_ element/widget of the Builder _in it_. Then it will all make sense. (Meaning, WordPress needs to see a hidden meta key for that page/item, that mostly gets set when adding an element and publish – or at least save – the whole thing.)

### Will more Builders be supported?
Mostly not. Only when another Builder is easy to integrate and works (like the others) with the principle of meta key/value pair. If I missed such a Builder, please [create an Issue](https://github.com/deckerweb/builder-list-pages/issues) on the GitHub repository of this plugin so I can consider integration. Otherwise no further integration is planned. (The Builders I personally need are already covered: _Bricks_, _Oxygen_, _Breakdance_, _Elementor_.)

### Why did you create this plugin?
Back in 2019 I needed it myself for a few sites I maintained. Those sites were powered by Elementor (Pro). Once I discovered a code snippet offering these views, I enhanced the snippet (security, better labels, etc.) and made a plugin out of it. It was just for "private" use. A few years later I needed it again but now for other builders (Oxygen, Bricks, Breakdance ...), so I developed the plugin further and made a more robust solution out of it, working with more Builders. And this is now the result.

### Why is this plugin not on wordpress.org Plugin Repository?
Because the restrictions there for plugin authors are becoming more and more. It would be possible, yes, but I don't want that anymore. The same for limited support forums for plugin authors on .org. I have decided to leave this whole thing behind me.

---

## Changelog 

#### Version History 

### 🎉 v1.0.0 – 2025-04-11
* Initial _public_ release
* With support for 11 popular Page Builders
* Plugin is compatible with _ClassicPress_ (fork of WordPress) when the supported Page Builder is compatible with _ClassicPress_
* Installable and updateable via [Git Updater plugin](https://git-updater.com/)
* Includes `.pot` file, plus packaged German translations


### 🛠 v0.5.0 – 2019-08-12
* _Private_ _alpha_ release

---

## Plugin Scope / Disclaimer 

This plugin comes as is.

_Disclaimer 1:_ So far I will support the plugin for breaking errors to keep it working. Otherwise support will be very limited. Also, it will NEVER be released to WordPress.org Plugin Repository for a lot of reasons (ah, thanks, Matt!).

_Disclaimer 2:_ All of the above might change. I do all this stuff only in my spare time.

_Most of all:_ Be blessed and have fun building great sites!!! 😉

---

Icon used in promo graphics: [© Remix Icon](https://remixicon.com/)

Readme & Plugin Copyright: © 2019-2025, David Decker – DECKERWEB.de