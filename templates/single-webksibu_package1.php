<?php
if ( ! defined('ABSPATH') ) exit;

global $post;
if ( ! ($post instanceof WP_Post) ) {
    return;
}

$webksibu_id = $post->ID;

$webksibu_img = has_post_thumbnail($webksibu_id) ? get_the_post_thumbnail_url($webksibu_id, 'full') : '';
$webksibu_dur = (string) get_post_meta($webksibu_id, '_webksibu_pkg1_duration', true);
$webksibu_price = (string) get_post_meta($webksibu_id, '_webksibu_pkg1_price', true);
$webksibu_destinations = (string) get_post_meta($webksibu_id, '_webksibu_pkg1_destinations', true);
$webksibu_book_text = (string) get_post_meta($webksibu_id, '_webksibu_pkg1_book_text', true);
$webksibu_book_url  = (string) get_post_meta($webksibu_id, '_webksibu_pkg1_book_url', true);

// Content area: use editor content for Description
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core WordPress hook name.
$webksibu_description = apply_filters('the_content', $post->post_content);

get_header();
?>
<div class="webksibu-package1-single">

    <div class="webksibu-package1-hero" style="<?php echo $webksibu_img ? 'background-image:url(' . esc_url($webksibu_img) . ');' : ''; ?>">
        <div class="webksibu-package1-hero-overlay">
            <h1><?php echo esc_html( get_the_title($webksibu_id) ); ?></h1>
        </div>
    </div>

    <div class="webksibu-package1-container">
        <div class="webksibu-package1-main">
            <h3 style="color:#002e63; margin-top:0;">Description</h3>
            <?php echo $webksibu_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

            <!-- Optional: If you later want itinerary as a field, we can add it as a textarea meta -->
        </div>

        <div class="webksibu-package1-sidebar">
            <div class="webksibu-package1-widget">
                <h4>Trip Summary</h4>

                <?php if ( $webksibu_dur !== '' ) : ?>
                    <p><strong>Duration:</strong> <?php echo esc_html($webksibu_dur); ?></p>
                <?php endif; ?>

                <?php if ( $webksibu_price !== '' ) : ?>
                    <p><strong>Price:</strong> <span class="webksibu-p-amt"><?php echo esc_html($webksibu_price); ?></span></p>
                <?php endif; ?>

                <?php if ( $webksibu_destinations !== '' ) : ?>
                    <p><strong>Destinations:</strong> <?php echo esc_html($webksibu_destinations); ?></p>
                <?php endif; ?>

                <?php
                $webksibu_final_book_text = $webksibu_book_text !== '' ? $webksibu_book_text : __('Book This Trip', 'webkih-site-builder-kit');
                $webksibu_final_book_url  = $webksibu_book_url !== '' ? $webksibu_book_url : '#';
                ?>
                <a href="<?php echo esc_url($webksibu_final_book_url); ?>" class="webksibu-p-btn">
                    <?php echo esc_html($webksibu_final_book_text); ?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
