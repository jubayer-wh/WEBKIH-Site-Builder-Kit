<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Map Style 1
 * - Shortcode: [webksibu_map1]
 * - Admin submenu page renderer: webksibu_render_map1_admin_page()
 *
 * Options key: webksibu_map1_settings
 */

/*--------------------------------------------------------------
1) FRONTEND SHORTCODE
--------------------------------------------------------------*/
function webksibu_map1_get_defaults() {
    return [
        'title'       => 'Visit WEBKIH',
        'address'     => 'Brudger Bazaar Rd, Atpara 2470',
        'email'       => 'jubayer@webkih.com',
        'hours'       => 'Sat - Thu | 9 AM - 8 PM',
        'button_text' => 'Get Directions',
        'button_url'  => 'https://www.google.com/maps/place/WEBKIH/@24.7944884,90.8598375,17z',
        'iframe_src'  => '',
    ];
}

function webksibu_map1_get_settings() {
    $defaults = webksibu_map1_get_defaults();
    $settings = get_option('webksibu_map1_settings', []);
    return array_merge($defaults, is_array($settings) ? $settings : []);
}

function webksibu_map1_render_markup($s) {
    ob_start();
    ?>
    <div class="webksibu-map-section">
        <div class="webksibu-map-container">
            <div class="webksibu-map-card">

                <div class="webksibu-map-info">
                    <h2><?php echo esc_html($s['title']); ?></h2>

                    <div class="webksibu-details">
                        <div class="webksibu-row">
                            <span>📍</span>
                            <span><?php echo esc_html($s['address']); ?></span>
                        </div>
                        <div class="webksibu-row">
                            <span>📧</span>
                            <span><?php echo esc_html($s['email']); ?></span>
                        </div>
                        <div class="webksibu-row">
                            <span>🕒</span>
                            <span><?php echo esc_html($s['hours']); ?></span>
                        </div>
                    </div>

                    <a href="<?php echo esc_url($s['button_url']); ?>"
                       target="_blank"
                       class="webksibu-map-btn" rel="noopener">
                        <?php echo esc_html($s['button_text']); ?>
                    </a>
                </div>

                <div class="webksibu-map-iframe">
                    <?php if ( ! empty($s['iframe_src']) ) : ?>
                        <iframe
                            src="<?php echo esc_url($s['iframe_src']); ?>"
                            allowfullscreen
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <?php else : ?>
                        <p style="padding:20px;color:#fff;">Map iframe src is not set yet.</p>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('webksibu_map1', function () {
    $s = webksibu_map1_get_settings();
    return webksibu_map1_render_markup($s);
});


/*--------------------------------------------------------------
2) ADMIN SUBMENU PAGE OUTPUT (renovated UI, no preview)
--------------------------------------------------------------*/
function webksibu_render_map1_admin_page() {

    if ( ! current_user_can('manage_options') ) {
        wp_die( esc_html__('You do not have permission to access this page.', 'webkih-site-builder-kit') );
    }

    $defaults = webksibu_map1_get_defaults();
    $opt_key  = 'webksibu_map1_settings';

    // Save
    if ( isset($_POST['webksibu_save_map1']) ) {

        $nonce = isset($_POST['webksibu_map1_nonce']) ? sanitize_text_field( wp_unslash($_POST['webksibu_map1_nonce']) ) : '';
        if ( ! wp_verify_nonce($nonce, 'webksibu_save_map1_action') ) {
            wp_die( esc_html__('Security check failed.', 'webkih-site-builder-kit') );
        }

        $data = [
            'title'       => sanitize_text_field( wp_unslash($_POST['title'] ?? '') ),
            'address'     => sanitize_text_field( wp_unslash($_POST['address'] ?? '') ),
            'email'       => sanitize_email( wp_unslash($_POST['email'] ?? '') ),
            'hours'       => sanitize_text_field( wp_unslash($_POST['hours'] ?? '') ),
            'button_text' => sanitize_text_field( wp_unslash($_POST['button_text'] ?? '') ),
            'button_url'  => esc_url_raw( wp_unslash($_POST['button_url'] ?? '') ),
            'iframe_src'  => esc_url_raw( wp_unslash($_POST['iframe_src'] ?? '') ),
        ];

        update_option($opt_key, array_merge($defaults, $data));

        echo '<div class="notice notice-success is-dismissible"><p>' .
            esc_html__('Map settings saved.', 'webkih-site-builder-kit') .
        '</p></div>';
    }

    $s = webksibu_map1_get_settings();

    // Inline admin UI styling (compact + neon accent)
    $css = '
    .webksibu-map-admin-wrap{max-width:1100px;margin-top:14px;font-family:Inter,system-ui,-apple-system,sans-serif;}
    .webksibu-map-admin-hero{
        background:linear-gradient(135deg,#011c39,#022b55);
        border-radius:14px;padding:18px 18px;margin-bottom:14px;color:#fff;
        display:flex;gap:12px;align-items:center;justify-content:space-between;
    }
    .webksibu-map-admin-hero h1{margin:0;font-size:1.35rem;font-weight:900;color:#fff;}
    .webksibu-map-admin-hero p{margin:4px 0 0;opacity:.85;font-size:.9rem;}
    .webksibu-map-admin-pill{
        background:rgba(56,189,248,.15);
        border:1px solid rgba(56,189,248,.35);
        color:#eaf7ff;
        padding:8px 10px;border-radius:999px;
        font-family:ui-monospace,monospace;font-size:.85rem;font-weight:800;
        white-space:nowrap;
    }

    .webksibu-map-admin-grid{display:grid;grid-template-columns:1fr 320px;gap:14px;align-items:start;}
    .webksibu-card{
        background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px;
        box-shadow:0 8px 22px rgba(1,28,57,.06);
    }
    .webksibu-card h2{margin:0 0 10px;font-size:1.05rem;font-weight:900;color:#011c39;}
    .webksibu-help{
        background:rgba(56,189,248,.08);
        border:1px solid rgba(56,189,248,.22);
        border-radius:12px;padding:12px;color:#0f172a;
    }
    .webksibu-help strong{color:#011c39;}
    .webksibu-help code{font-family:ui-monospace,monospace;font-weight:800;}
    .webksibu-map-admin-actions{display:flex;gap:10px;align-items:center;margin-top:10px;}
    .webksibu-map-admin-actions .button-primary{background:#011c39;border-color:#011c39;}
    .webksibu-map-admin-actions .button-primary:hover{background:#022b55;border-color:#022b55;}
    .webksibu-muted{color:#64748b;font-size:.85rem;margin:8px 0 0;}
    .webksibu-field-note{color:#64748b;font-size:.82rem;margin:6px 0 0;}
    .form-table th{width:160px;}
    .form-table input.regular-text{max-width:420px;}
    .form-table input.large-text, .form-table textarea.large-text{max-width:520px;}
    @media(max-width:980px){
        .webksibu-map-admin-grid{grid-template-columns:1fr;}
        .form-table th{width:auto;}
    }
    ';
    wp_register_style('webksibu-map1-admin-inline', false, [], '1.0');
    wp_enqueue_style('webksibu-map1-admin-inline');
    wp_add_inline_style('webksibu-map1-admin-inline', $css);

    ?>
    <div class="wrap webksibu-map-admin-wrap">

        <div class="webksibu-map-admin-hero">
            <div>
                <h1>Map (Style 1)</h1>
                <p>Edit your contact map block content. Clean, fast, and shortcode-ready.</p>
            </div>
            <div class="webksibu-map-admin-pill">[webksibu_map1]</div>
        </div>

        <div class="webksibu-map-admin-grid">

            <div class="webksibu-card">
                <h2>Map Content</h2>

                <form method="post">
                    <?php wp_nonce_field('webksibu_save_map1_action', 'webksibu_map1_nonce'); ?>

                    <table class="form-table" role="presentation">
                        <tr>
                            <th><label for="title">Title</label></th>
                            <td>
                                <input class="regular-text" id="title" name="title" value="<?php echo esc_attr($s['title']); ?>">
                                <p class="webksibu-field-note">Example: Visit WEBKIH, Our Office Location, etc.</p>
                            </td>
                        </tr>

                        <tr>
                            <th><label for="address">Address</label></th>
                            <td>
                                <input class="regular-text" id="address" name="address" value="<?php echo esc_attr($s['address']); ?>">
                            </td>
                        </tr>

                        <tr>
                            <th><label for="email">Email</label></th>
                            <td>
                                <input class="regular-text" id="email" name="email" value="<?php echo esc_attr($s['email']); ?>">
                            </td>
                        </tr>

                        <tr>
                            <th><label for="hours">Hours</label></th>
                            <td>
                                <input class="regular-text" id="hours" name="hours" value="<?php echo esc_attr($s['hours']); ?>">
                            </td>
                        </tr>

                        <tr>
                            <th><label for="button_text">Button Text</label></th>
                            <td>
                                <input class="regular-text" id="button_text" name="button_text" value="<?php echo esc_attr($s['button_text']); ?>">
                            </td>
                        </tr>

                        <tr>
                            <th><label for="button_url">Button URL</label></th>
                            <td>
                                <input class="large-text" id="button_url" name="button_url" value="<?php echo esc_attr($s['button_url']); ?>">
                                <p class="webksibu-field-note">This opens when users click the button.</p>
                            </td>
                        </tr>

                        <tr>
                            <th><label for="iframe_src">Google Map iframe src</label></th>
                            <td>
                                <textarea class="large-text" rows="4" id="iframe_src" name="iframe_src"><?php echo esc_textarea($s['iframe_src']); ?></textarea>
                                <p class="webksibu-field-note">Paste only the <strong>src</strong> URL from the embed code, not the whole iframe tag.</p>
                            </td>
                        </tr>
                    </table>

                    <div class="webksibu-map-admin-actions">
                        <button class="button button-primary" type="submit" name="webksibu_save_map1">Save</button>
                        <span class="webksibu-muted">Changes will reflect wherever you use <code>[webksibu_map1]</code>.</span>
                    </div>
                </form>
            </div>

            <div class="webksibu-card">
                <h2>Quick Help</h2>

                <div class="webksibu-help">
                    <p style="margin:0 0 10px;">
                        <strong>Where to use?</strong><br>
                        Add <code>[webksibu_map1]</code> into any page, post, or Elementor shortcode widget.
                    </p>
                    <p style="margin:0 0 10px;">
                        <strong>Iframe src not working?</strong><br>
                        Use Google Maps → Share → Embed a map → copy only the <code>src</code> link.
                    </p>
                    <p style="margin:0;">
                        <strong>Tip:</strong> Keep your title short, your address clear, and your CTA button strong.
                    </p>
                </div>
            </div>

        </div>
    </div>
    <?php
}
