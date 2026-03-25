<?php
if ( ! defined('ABSPATH') ) exit;
?>


<div class="wbk-doc-wrap">

    <!-- Header -->
    <div class="wbk-doc-header">
        <h1>WEBKIH Kit Documentation</h1>
        <p>Click a shortcode → auto copy → live preview</p>
    </div>

    <div class="wbk-doc-layout">

        <!-- LEFT: Shortcodes -->
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

        <!-- RIGHT: Live Preview -->
        <div class="wbk-doc-right">
            <h2>Live Preview</h2>
            <div class="wbk-preview-note">
                Preview loads using a sandbox page on your site.
            </div>

            <iframe
                id="wbkPreviewFrame"
                class="wbk-preview-frame"
                data-preview-base="<?php echo esc_url( home_url( '?wbk_preview=' ) ); ?>"
                src="<?php echo esc_url( home_url( '?wbk_preview=[wbk_slider1]' ) ); ?>">
            </iframe>
        </div>

    </div>
</div>

<div class="wbk-toast" id="wbkToast">Shortcode copied</div>

