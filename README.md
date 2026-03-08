# WEBKIH Site Builder Kit

WEBKIH Site Builder Kit is a modular WordPress plugin that adds reusable frontend sections via shortcodes and module-specific admin management.

## Plugin Details

- **Plugin URI:** https://github.com/jubayer-wh/WEBKIH-Site-Builder-Kit
- **Author:** Jubayer Hossain
- **Author URI:** https://webkih.com
- **Version:** 1.0.0
- **License:** GPLv2 or later

## Included Modules

- **Slider 1** – Hero-style slider using a dedicated custom post type.
- **Slider 2** – Lightweight image/content slider.
- **Team 1** – Team cards with role/designation fields.
- **Map 1** – Contact/location block with CTA and embedded map support.
- **Success Stories 1** – Testimonial/success cards with taxonomy filtering.
- **Package 1** – Package cards and single package template rendering.

## Available Shortcodes

- `[wbk_slider1]`
- `[wbk_slider2]`
- `[wbk_team1]`
- `[wbk_map1]`
- `[wbk_success1_3]`
- `[wbk_success1_all]`
- `[wbk_package1]`

## Installation

1. Upload the plugin to `/wp-content/plugins/WEBKIH-Site-Builder-Kit`.
2. Activate the plugin from **Plugins** in WordPress admin.
3. Open **WEBKIH Kit** menu and add your module content.
4. Place shortcodes in pages/posts/widgets as needed.

## Notes for WordPress Submission

This plugin follows WordPress-oriented data handling patterns:

- Capability checks before admin actions.
- Nonce verification on settings/meta save flows.
- Sanitization of submitted data.
- Escaping of frontend/admin output.

## Support

- Website: https://webkih.com
- Repository: https://github.com/jubayer-wh/WEBKIH-Site-Builder-Kit
