<?php
if ( ! defined('ABSPATH') ) exit;

// Default shortcode on first load
$default_sc = '[wbk_slider1]';

// Nonce for preview security (same action name you verify in template_redirect)
$nonce = wp_create_nonce('wbk_preview_shortcode');

// Build preview URL safely
$preview_url = add_query_arg(
    [
        'wbk_preview' => $default_sc,
        '_wbk_nonce'  => $nonce,
    ],
    home_url('/')
);
?>

<div class="wbk-doc-wrap">

    <div class="wbk-doc-header">
        <h1>WEBKIH Kit Documentation</h1>
        <p>Click a shortcode → auto copy → live preview</p>
    </div>

    <div class="wbk-doc-layout">

        <div class="wbk-doc-left">
            <h2>Shortcodes</h2>

            <div class="wbk-sc-grid">

                <div class="wbk-sc-card active" data-sc="[wbk_slider1]">
                    <span class="wbk-sc-code">[wbk_slider1]</span>
                    <div class="wbk-sc-desc">Slider 1</div>
                </div>

                <div class="wbk-sc-card" data-sc="[wbk_slider2]">
                    <span class="wbk-sc-code">[wbk_slider2]</span>
                    <div class="wbk-sc-desc">Slider 2</div>
                </div>

                <div class="wbk-sc-card" data-sc="[wbk_team1]">
                    <span class="wbk-sc-code">[wbk_team1]</span>
                    <div class="wbk-sc-desc">Team members 1</div>
                </div>

                <div class="wbk-sc-card" data-sc="[wbk_map1]">
                    <span class="wbk-sc-code">[wbk_map1]</span>
                    <div class="wbk-sc-desc">Map Block 1</div>
                </div>

                <div class="wbk-sc-card" data-sc="[wbk_success1_3]">
                    <span class="wbk-sc-code">[wbk_success1_3]</span>
                    <div class="wbk-sc-desc">Success stories 1</div>
                </div>

                <div class="wbk-sc-card" data-sc="[wbk_package1]">
                    <span class="wbk-sc-code">[wbk_package1]</span>
                    <div class="wbk-sc-desc">Packages 1</div>
                </div>

            </div>
        </div>

        <div class="wbk-doc-right">
            <h2>Live Preview</h2>
            <div class="wbk-preview-note">
                Preview loads using a sandbox page on your site.
            </div>

            <iframe
                id="wbkPreviewFrame"
                class="wbk-preview-frame"
                src="<?php echo esc_url($preview_url); ?>">
            </iframe>
        </div>

    </div>
</div>

<div class="wbk-toast" id="wbkToast">Shortcode copied</div>
