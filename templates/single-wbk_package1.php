<?php
if ( ! defined('ABSPATH') ) exit;

global $post;
if ( ! ($post instanceof WP_Post) ) {
    return;
}

$id = $post->ID;

$img = has_post_thumbnail($id) ? get_the_post_thumbnail_url($id, 'full') : '';
$dur = (string) get_post_meta($id, '_wbk_pkg1_duration', true);
$price = (string) get_post_meta($id, '_wbk_pkg1_price', true);
$destinations = (string) get_post_meta($id, '_wbk_pkg1_destinations', true);
$book_text = (string) get_post_meta($id, '_wbk_pkg1_book_text', true);
$book_url  = (string) get_post_meta($id, '_wbk_pkg1_book_url', true);

// Content area: use editor content for Description
$description = apply_filters('the_content', $post->post_content);

get_header();
?>
<div class="wbk-package1-single">

    <div class="wbk-package1-hero" style="<?php echo $img ? 'background-image:url(' . esc_url($img) . ');' : ''; ?>">
        <div class="wbk-package1-hero-overlay">
            <h1><?php echo esc_html( get_the_title($id) ); ?></h1>
        </div>
    </div>

    <div class="wbk-package1-container">
        <div class="wbk-package1-main">
            <h3 style="color:#002e63; margin-top:0;">Description</h3>
            <?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

            <!-- Optional: If you later want itinerary as a field, we can add it as a textarea meta -->
        </div>

        <div class="wbk-package1-sidebar">
            <div class="wbk-package1-widget">
                <h4>Trip Summary</h4>

                <?php if ( $dur !== '' ) : ?>
                    <p><strong>Duration:</strong> <?php echo esc_html($dur); ?></p>
                <?php endif; ?>

                <?php if ( $price !== '' ) : ?>
                    <p><strong>Price:</strong> <span class="wbk-p-amt"><?php echo esc_html($price); ?></span></p>
                <?php endif; ?>

                <?php if ( $destinations !== '' ) : ?>
                    <p><strong>Destinations:</strong> <?php echo esc_html($destinations); ?></p>
                <?php endif; ?>

                <?php
                $final_book_text = $book_text !== '' ? $book_text : __('Book This Trip', 'webkih-site-builder-kit');
                $final_book_url  = $book_url !== '' ? $book_url : '#';
                ?>
                <a href="<?php echo esc_url($final_book_url); ?>" class="wbk-p-btn">
                    <?php echo esc_html($final_book_text); ?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
