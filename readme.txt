=== WEBKIH Site Builder Kit ===
Contributors: jubayer1
Tags: website builder, stories, slider, team, maps
Requires at least: 6.0
Tested up to: 6.9
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Modular WordPress sections with shortcodes for sliders, team, map, success stories, and packages managed from one admin hub.

== Description ==
WEBKIH Site Builder Kit is a modular plugin designed for service companies, education consultancies, travel brands, and agencies that need editable frontend sections without page-builder lock-in.

This plugin includes custom modules for:

* **Slider 1** (`[wbk_slider1]`) – Hero style slider driven by a custom post type.
* **Slider 2** (`[wbk_slider2]`) – Lightweight content/image slider with navigation dots.
* **Team Section** (`[wbk_team1]`) – Team member cards with role/designation support.
* **Map Section** (`[wbk_map1]`) – Contact/location section with map embed URL and CTA button.
* **Success Stories** (`[wbk_success1_3]`, `[wbk_success1_all]`) – Case/story cards with category filtering support.
* **Packages** (`[wbk_package1]`) – Package grid and dedicated single-package template.

### Why use this plugin?

* Centralized module management under one admin menu (`WEBKIH Kit`).
* Shortcode-first architecture that works with classic editor, block editor shortcode block, and page builders.
* Built-in settings hub for module-level text, style, and display controls.
* WordPress-standard sanitization, escaping, and nonce validation in settings and save flows.

== Installation ==
1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate **WEBKIH Site Builder Kit** from the Plugins screen.
3. Open **WEBKIH Kit** from wp-admin.
4. Add module content (slides, team members, stories, packages), then use the shortcodes where needed.

== Frequently Asked Questions ==
= Where do I manage module content? =
Use wp-admin > **WEBKIH Kit**. Each module has its own management screen and/or settings panel.

= Where can I use the shortcodes? =
You can use them in posts, pages, widgets, and most page builders that support shortcode rendering.

= How do I filter success stories by category? =
Use the `cat` attribute with category slug, for example:
`[wbk_success1_3 cat="student-visa"]`

= Does the package module include a single template? =
Yes. The plugin provides a dedicated single template for `wbk_package1` posts.

== Shortcodes ==
* `[wbk_slider1]`
* `[wbk_slider2]`
* `[wbk_team1]`
* `[wbk_map1]`
* `[wbk_success1_3]`
* `[wbk_success1_all]`
* `[wbk_package1]`

== Changelog ==
= 1.0.0 =
* Initial release.
