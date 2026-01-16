<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Success Stories (Style 1)
 * CPT: wbk_success
 * Taxonomy: wbk_success_category
 *
 * Shortcodes:
 *  - [wbk_success1_3 cat="slug"]   => shows 3
 *  - [wbk_success1_all cat="slug"] => shows all
 *
 * Settings option:
 *  - wbk_success1_settings (title, caption, hide heading, colors, columns)
 *
 * CSS:
 *  - assets/css/success1.css
 */

/*--------------------------------------------------------------
1) REGISTER CPT + TAXONOMY (UNDER WBK DASHBOARD MENU)
--------------------------------------------------------------*/
add_action('init', function () {

    $labels = [
        'name'               => 'Success Stories',
        'singular_name'      => 'Success Story',
        'menu_name'          => 'Success Stories',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Story',
        'edit_item'          => 'Edit Story',
        'new_item'           => 'New Story',
        'view_item'          => 'View Story',
        'all_items'          => 'Stories 1',
        'search_items'       => 'Search Stories',
        'not_found'          => 'No stories found.',
        'not_found_in_trash' => 'No stories found in Trash.',
    ];

    register_post_type('wbk_success', [
        'labels'          => $labels,
        'public'          => true,
        'show_in_menu'    => 'wbk-dashboard', // submenu under WEBKIH Kit
        'has_archive'     => false,
        'rewrite'         => false,
        'supports'        => ['title', 'thumbnail'],
        'capability_type' => 'post',
        'show_in_rest'    => true,
    ]);

    $tax_labels = [
        'name'          => 'Story Categories',
        'singular_name' => 'Story Category',
        'search_items'  => 'Search Categories',
        'all_items'     => 'All Categories',
        'edit_item'     => 'Edit Category',
        'update_item'   => 'Update Category',
        'add_new_item'  => 'Add New Category',
        'new_item_name' => 'New Category Name',
        'menu_name'     => 'Categories',
    ];

    register_taxonomy('wbk_success_category', ['wbk_success'], [
        'labels'            => $tax_labels,
        'public'            => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'success-category'],
    ]);
});


/*--------------------------------------------------------------
2) META BOXES
--------------------------------------------------------------*/
add_action('add_meta_boxes', function () {
    add_meta_box(
        'wbk_success_details',
        'Success Story Details',
        'wbk_success_details_metabox_cb',
        'wbk_success',
        'normal',
        'high'
    );
});

function wbk_success_details_metabox_cb($post) {

    $badge       = get_post_meta($post->ID, '_wbk_success_badge', true);
    $destination = get_post_meta($post->ID, '_wbk_success_destination', true);
    $quote       = get_post_meta($post->ID, '_wbk_success_quote', true);
    $issued      = get_post_meta($post->ID, '_wbk_success_issued', true);
    $rating      = get_post_meta($post->ID, '_wbk_success_rating', true);

    wp_nonce_field('wbk_success_save_metabox', 'wbk_success_nonce');
    ?>
    <table class="form-table">
        <tr>
            <th><label for="wbk_success_badge">Badge (Visa Type)</label></th>
            <td>
                <input type="text" class="regular-text" id="wbk_success_badge" name="wbk_success_badge"
                       value="<?php echo esc_attr($badge); ?>" placeholder="e.g. Student Visa">
            </td>
        </tr>

        <tr>
            <th><label for="wbk_success_destination">Destination (text only)</label></th>
            <td>
                <input type="text" class="large-text" id="wbk_success_destination" name="wbk_success_destination"
                       value="<?php echo esc_attr($destination); ?>" placeholder="e.g. University of Greenwich, UK">
                <p class="description">Frontend automatically adds 📍 icon.</p>
            </td>
        </tr>

        <tr>
            <th><label for="wbk_success_quote">Quote</label></th>
            <td>
                <textarea class="large-text" rows="4" id="wbk_success_quote" name="wbk_success_quote"
                          placeholder="Write the client quote..."><?php echo esc_textarea($quote); ?></textarea>
            </td>
        </tr>

        <tr>
            <th><label for="wbk_success_issued">Visa Issued (text)</label></th>
            <td>
                <input type="text" class="regular-text" id="wbk_success_issued" name="wbk_success_issued"
                       value="<?php echo esc_attr($issued); ?>" placeholder="e.g. Dec 2025">
            </td>
        </tr>

        <tr>
            <th><label for="wbk_success_rating">Rating</label></th>
            <td>
                <input type="text" class="regular-text" id="wbk_success_rating" name="wbk_success_rating"
                       value="<?php echo esc_attr($rating); ?>" placeholder="e.g. ⭐⭐⭐⭐⭐ (blank = default)">
                <p class="description">Leave blank to show default: ⭐⭐⭐⭐⭐</p>
            </td>
        </tr>
    </table>

    <p><strong>Image:</strong> Use <em>Featured Image</em> for the story photo.</p>
    <?php
}


/*--------------------------------------------------------------
3) SAVE META (SECURE)
--------------------------------------------------------------*/
add_action('save_post_wbk_success', function ($post_id) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;

    if (
        ! isset($_POST['wbk_success_nonce']) ||
        ! wp_verify_nonce($_POST['wbk_success_nonce'], 'wbk_success_save_metabox')
    ) {
        return;
    }

    $badge       = isset($_POST['wbk_success_badge']) ? sanitize_text_field( wp_unslash($_POST['wbk_success_badge']) ) : '';
    $destination = isset($_POST['wbk_success_destination']) ? sanitize_text_field( wp_unslash($_POST['wbk_success_destination']) ) : '';
    $quote       = isset($_POST['wbk_success_quote']) ? sanitize_textarea_field( wp_unslash($_POST['wbk_success_quote']) ) : '';
    $issued      = isset($_POST['wbk_success_issued']) ? sanitize_text_field( wp_unslash($_POST['wbk_success_issued']) ) : '';
    $rating      = isset($_POST['wbk_success_rating']) ? sanitize_text_field( wp_unslash($_POST['wbk_success_rating']) ) : '';

    update_post_meta($post_id, '_wbk_success_badge', $badge);
    update_post_meta($post_id, '_wbk_success_destination', $destination);
    update_post_meta($post_id, '_wbk_success_quote', $quote);
    update_post_meta($post_id, '_wbk_success_issued', $issued);
    update_post_meta($post_id, '_wbk_success_rating', $rating);
});


/*--------------------------------------------------------------
4) SETTINGS (TITLE/CAPTION/HIDE/COLORS/COLUMNS)
--------------------------------------------------------------*/
function wbk_success1_get_settings() {

    $defaults = [
        'title'        => 'Our Recent Success Stories',
        'caption'      => 'Real people, real visas, and realized dreams.',
        'hide_heading' => 0,

        // Colors (CSS variables used by your CSS)
        'deep_blue'     => '#002e63',
        'accent_blue'   => '#0056b3',
        'success_green' => '#27ae60',
        'light_gray'    => '#f4f7f9',

        // Layout controls
        'max_columns'    => 3,    // 1..6
        'min_card_width' => 350,  // 220..600 (px)
    ];

    $opt = get_option('wbk_success1_settings', []);
    $opt = is_array($opt) ? $opt : [];

    $s = array_merge($defaults, $opt);

    // Sanity clamp
    $s['max_columns'] = max(1, min(6, (int) $s['max_columns']));
    $s['min_card_width'] = max(220, min(600, (int) $s['min_card_width']));

    // Validate hex colors (fallback to defaults)
    foreach ( ['deep_blue','accent_blue','success_green','light_gray'] as $k ) {
        if ( ! is_string($s[$k]) || ! preg_match('/^#[0-9a-fA-F]{6}$/', $s[$k]) ) {
            $s[$k] = $defaults[$k];
        }
    }

    $s['hide_heading'] = (int) $s['hide_heading'] === 1 ? 1 : 0;

    $s['title'] = is_string($s['title']) && $s['title'] !== '' ? $s['title'] : $defaults['title'];
    $s['caption'] = is_string($s['caption']) && $s['caption'] !== '' ? $s['caption'] : $defaults['caption'];

    return $s;
}


/*--------------------------------------------------------------
5) ICON + DEFAULTS
--------------------------------------------------------------*/
function wbk_success1_default_stars() {
    return '⭐⭐⭐⭐⭐';
}

function wbk_success1_location_icon() {
    return '📍';
}


/*--------------------------------------------------------------
6) SHORTCODE RENDER FUNCTION
--------------------------------------------------------------*/
function wbk_render_success1_cards( $limit = 3, $cat_slug = '' ) {
    $inline_css = '';

    wp_enqueue_style('wbk-success1-css', WBK_URL . 'assets/css/success1.css', [], WBK_VER);
    wp_add_inline_style('wbk-success1-css', $inline_css);

    $set = wbk_success1_get_settings();

// Inline CSS variables + grid tuning (safe + scoped)
$inline_css = sprintf(
    '.wbk-success-section{--deep-blue:%1$s;--accent-blue:%2$s;--success-green:%3$s;--light-gray:%4$s;}
     .wbk-success-section .wbk-success-grid{grid-template-columns:repeat(%5$d, minmax(%6$dpx, 1fr));}',
    esc_attr($set['deep_blue']),
    esc_attr($set['accent_blue']),
    esc_attr($set['success_green']),
    esc_attr($set['light_gray']),
    (int) $set['max_columns'],
    (int) $set['min_card_width']
);


    $args = [
        'post_type'      => 'wbk_success',
        'post_status'    => 'publish',
        'posts_per_page' => (int) $limit,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ];

    $cat_slug = is_string($cat_slug) ? sanitize_title($cat_slug) : '';

    if ( $cat_slug !== '' ) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'wbk_success_category',
                'field'    => 'slug',
                'terms'    => $cat_slug,
            ]
        ];
    }

    $q = new WP_Query($args);

    if ( ! $q->have_posts() ) {
        return '';
    }

    $stars_default = wbk_success1_default_stars();
    $loc_icon = wbk_success1_location_icon();

    ob_start();

    ?>
    <section class="wbk-success-section">

        <?php if ( ! $set['hide_heading'] ) : ?>
            <div class="wbk-section-title">
                <h2><?php echo esc_html($set['title']); ?></h2>
                <p><?php echo esc_html($set['caption']); ?></p>
            </div>
        <?php endif; ?>

        <div class="wbk-success-grid">
            <?php while ( $q->have_posts() ) : $q->the_post(); ?>
                <?php
                    $badge       = (string) get_post_meta(get_the_ID(), '_wbk_success_badge', true);
                    $destination = (string) get_post_meta(get_the_ID(), '_wbk_success_destination', true);
                    $quote       = (string) get_post_meta(get_the_ID(), '_wbk_success_quote', true);
                    $issued      = (string) get_post_meta(get_the_ID(), '_wbk_success_issued', true);
                    $rating      = (string) get_post_meta(get_the_ID(), '_wbk_success_rating', true);

                    $badge = trim($badge);
                    $destination = trim($destination);
                    $quote = trim($quote);
                    $issued = trim($issued);
                    $rating = trim($rating);

                    if ( $rating === '' ) {
                        $rating = $stars_default;
                    }

                    $img = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : '';
                    $alt = get_the_title();
                ?>

                <div class="wbk-success-card">
                    <div class="wbk-success-image-wrap">
                        <?php if ( $img ) : ?>
                            <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($alt); ?>">
                        <?php endif; ?>

                        <?php if ( $badge !== '' ) : ?>
                            <span class="wbk-visa-badge"><?php echo esc_html($badge); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="wbk-success-content">
                        <h3><?php echo esc_html( get_the_title() ); ?></h3>

                        <?php if ( $destination !== '' ) : ?>
                            <span class="wbk-destination-tag">
                                <?php echo esc_html( $loc_icon . ' ' . $destination ); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( $quote !== '' ) : ?>
                            <p class="wbk-success-quote"><?php echo esc_html($quote); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="wbk-success-footer">
                        <span>
                            <?php
                                if ( $issued !== '' ) {
                                    echo esc_html( 'Visa Issued: ' . $issued );
                                } else {
                                    echo '&nbsp;'; // keep layout stable
                                }
                            ?>
                        </span>
                        <span><?php echo esc_html($rating); ?></span>
                    </div>
                </div>

            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}


/*--------------------------------------------------------------
7) SHORTCODES (3 items + all)
--------------------------------------------------------------*/
add_shortcode('wbk_success1_3', function($atts){
    $a = shortcode_atts(['cat' => ''], $atts, 'wbk_success1_3');
    $cat = isset($a['cat']) ? (string) $a['cat'] : '';
    return wbk_render_success1_cards(3, $cat);
});

add_shortcode('wbk_success1_all', function($atts){
    $a = shortcode_atts(['cat' => ''], $atts, 'wbk_success1_all');
    $cat = isset($a['cat']) ? (string) $a['cat'] : '';
    return wbk_render_success1_cards(-1, $cat);
});
