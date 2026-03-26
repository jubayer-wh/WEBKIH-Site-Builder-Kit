<?php
if ( ! defined('ABSPATH') ) exit;
?>


<div class="webksibu-doc-wrap">

    <!-- Header -->
    <div class="webksibu-doc-header">
        <h1>WEBKIH Kit Documentation</h1>
        <p>Click a shortcode → auto copy → live preview</p>
    </div>

    <div class="webksibu-doc-layout">

        <!-- LEFT: Shortcodes -->
        <div class="webksibu-doc-left">
            <h2>Shortcodes</h2>

            <div class="webksibu-sc-grid">

                <div class="webksibu-sc-card active" data-sc="[webksibu_slider1]">
                    <span class="webksibu-sc-code">[webksibu_slider1]</span>
                    <div class="webksibu-sc-desc">Slider 1</div>
                </div>

                <div class="webksibu-sc-card" data-sc="[webksibu_slider2]">
                    <span class="webksibu-sc-code">[webksibu_slider2]</span>
                    <div class="webksibu-sc-desc">Slider 2</div>
                </div>

                <div class="webksibu-sc-card" data-sc="[webksibu_team1]">
                    <span class="webksibu-sc-code">[webksibu_team1]</span>
                    <div class="webksibu-sc-desc">Team members 1</div>
                </div>

                <div class="webksibu-sc-card" data-sc="[webksibu_map1]">
                    <span class="webksibu-sc-code">[webksibu_map1]</span>
                    <div class="webksibu-sc-desc">Map Block 1</div>
                </div>

                <div class="webksibu-sc-card" data-sc="[webksibu_success1_3]">
                    <span class="webksibu-sc-code">[webksibu_success1_3]</span>
                    <div class="webksibu-sc-desc">Success stories 1</div>
                </div>

                <div class="webksibu-sc-card" data-sc="[webksibu_package1]">
                    <span class="webksibu-sc-code">[webksibu_package1]</span>
                    <div class="webksibu-sc-desc">Packages 1</div>
                </div>

            </div>
        </div>

        <!-- RIGHT: Live Preview -->
        <div class="webksibu-doc-right">
            <h2>Live Preview</h2>
            <div class="webksibu-preview-note">
                Preview loads using a sandbox page on your site.
            </div>

            <iframe
                id="wbkPreviewFrame"
                class="webksibu-preview-frame"
                data-preview-base="<?php echo esc_url( home_url( '?webksibu_preview=' ) ); ?>"
                src="<?php echo esc_url( home_url( '?webksibu_preview=[webksibu_slider1]' ) ); ?>">
            </iframe>
        </div>

    </div>
</div>

<div class="webksibu-toast" id="wbkToast">Shortcode copied</div>

