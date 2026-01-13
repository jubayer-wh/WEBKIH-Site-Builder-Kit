<?php
if ( ! defined('ABSPATH') ) exit;

$addons = [
    [
        'title' => 'Hero Section (Style 1)',
        'desc'  => 'Clean hero with title + subtitle + button.',
        'shortcode' => '[wbk_hero1 title="Your Title" subtitle="Your subtitle" button="Contact" url="/contact"]',
    ],
    [
        'title' => 'Slider (Style 1)',
        'desc'  => 'Simple rotating slider with 3 images.',
        'shortcode' => '[wbk_slider1 img1="URL" img2="URL" img3="URL" height="360"]',
    ],
    [
        'title' => 'Team (Style 1)',
        'desc'  => 'Team members grid with name + role.',
        'shortcode' => '[wbk_team1]',
    ],
    [
        'title' => 'Packages (Style 1)',
        'desc'  => 'Package cards. Later you can connect CPT + single page.',
        'shortcode' => '[wbk_package1]',
    ],
    [
        'title' => 'Contact (Style 1)',
        'desc'  => 'Contact section with info + shortcode form area.',
        'shortcode' => '[wbk_contact1 email="jubayer@webkih.com" phone="+880..." address="Dhaka"]',
    ],
    [
        'title' => 'Loader (Style 1)',
        'desc'  => 'A simple preloader animation.',
        'shortcode' => '[wbk_loader1 text="Loading..."]',
    ],
];

?>
<div class="wrap">
    <h1>WEBKIH Site Builder Kit</h1>
    <p class="description">Use these shortcodes anywhere (page/post/Elementor shortcode widget).</p>

    <div class="wbk-grid">
        <?php foreach($addons as $a): ?>
            <div class="wbk-card">
                <h3><?php echo esc_html($a['title']); ?></h3>
                <p><?php echo esc_html($a['desc']); ?></p>
                <textarea class="wbk-shortcode" readonly><?php echo esc_html($a['shortcode']); ?></textarea>
                <button class="button wbk-copy-btn" type="button" data-copy="<?php echo esc_attr($a['shortcode']); ?>">
                    Copy
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <p style="margin-top:18px;opacity:.8;">
        Tip: Keep each addon as its own file in <code>/includes</code>. Add more styles later: <code>wbk-hero2.php</code>, <code>wbk-slider2.php</code> etc.
    </p>
</div>
