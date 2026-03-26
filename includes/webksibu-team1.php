<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Team Members – CPT + Shortcode
 * File: includes/webksibu-team1.php
 * CPT: webksibu_team
 * Parent Menu: WEBKIH Kit (webksibu-dashboard)
 */

/*--------------------------------------------------------------
1) REGISTER TEAM CPT (SUBMENU OF WEBKIH KIT)
--------------------------------------------------------------*/
add_action('init', function () {

    $labels = [
        'name'               => 'Team Members',
        'singular_name'      => 'Team Member',
        'menu_name'          => 'Team Members',
        'name_admin_bar'     => 'Team Member',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Team Member',
        'edit_item'          => 'Edit Team Member',
        'new_item'           => 'New Team Member',
        'view_item'          => 'View Team Member',
        'all_items'          => 'Team Members 1',
        'search_items'       => 'Search Team Members',
        'not_found'          => 'No team members found.',
        'not_found_in_trash' => 'No team members found in Trash.',
    ];

    register_post_type('webksibu_team', [
        'labels'             => $labels,
        'public'             => true,
        'show_in_menu'       => 'webksibu-dashboard', // 👈 KEY LINE
        'has_archive'        => false,
        'rewrite'            => false,

        // ✅ Added page-attributes to enable menu_order ("Order" field)
        'supports'           => ['title', 'thumbnail', 'page-attributes'],

        'capability_type'    => 'post',
    ]);
});


/*--------------------------------------------------------------
2) TEAM ROLE META BOX
--------------------------------------------------------------*/
add_action('add_meta_boxes', function () {
    add_meta_box(
        'webksibu_team_role_box',
        'Team Member Details',
        'webksibu_team_role_box_cb',
        'webksibu_team',
        'normal',
        'high'
    );
});

function webksibu_team_role_box_cb($post) {
    $role = get_post_meta($post->ID, '_webksibu_team_role', true);
    wp_nonce_field('webksibu_team_role_save', 'webksibu_team_role_nonce');
    ?>
    <p>
        <label for="webksibu_team_role"><strong>Role / Designation</strong></label><br>
        <input
            type="text"
            id="webksibu_team_role"
            name="webksibu_team_role"
            value="<?php echo esc_attr($role); ?>"
            class="regular-text"
            placeholder="e.g. Chief Operating Officer"
        >
    </p>
    <p><em>Use Featured Image for photo.</em></p>
    <?php
}


/*--------------------------------------------------------------
3) SAVE META
--------------------------------------------------------------*/
add_action('save_post_webksibu_team', function ($post_id) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;

    if (
        ! isset($_POST['webksibu_team_role_nonce']) ||
        ! wp_verify_nonce( sanitize_text_field( wp_unslash($_POST['webksibu_team_role_nonce']) ), 'webksibu_team_role_save')
    ) {
        return;
    }

    update_post_meta(
        $post_id,
        '_webksibu_team_role',
        sanitize_text_field( wp_unslash($_POST['webksibu_team_role'] ?? '') )
    );
});


/*--------------------------------------------------------------
3.5) ADMIN LIST: SHOW "ORDER" COLUMN + SORTABLE
--------------------------------------------------------------*/
add_filter('manage_webksibu_team_posts_columns', function ($cols) {
    $new = [];
    foreach ($cols as $key => $label) {
        if ($key === 'title') {
            $new['title'] = $label;
            $new['webksibu_order'] = 'Order';
            continue;
        }
        $new[$key] = $label;
    }
    return $new;
});

add_action('manage_webksibu_team_posts_custom_column', function ($col, $post_id) {
    if ($col === 'webksibu_order') {
        echo (int) get_post_field('menu_order', $post_id);
    }
}, 10, 2);

add_filter('manage_edit-webksibu_team_sortable_columns', function ($cols) {
    $cols['webksibu_order'] = 'menu_order';
    return $cols;
});

add_action('pre_get_posts', function ($q) {
    if ( ! is_admin() || ! $q->is_main_query() ) return;

    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if ( ! $screen || $screen->post_type !== 'webksibu_team' ) return;

    // Default admin ordering: menu_order ASC then date DESC
    if ( ! $q->get('orderby') ) {
        $q->set('orderby', ['menu_order' => 'ASC', 'date' => 'DESC']);
    }

    // If user clicks the Order column to sort
    if ( $q->get('orderby') === 'menu_order' ) {
        $q->set('orderby', 'menu_order');
    }
});


/*--------------------------------------------------------------
4) SHORTCODE OUTPUT
--------------------------------------------------------------*/
add_shortcode('webksibu_team1', function ($atts) {

    wp_enqueue_style(
        'webksibu-team1-css',
        WEBKSIBU_URL . 'assets/css/team1.css',
        [],
        WEBKSIBU_VER
    );

    $atts = shortcode_atts([
        'title' => 'Meet Our Expert Consultants',
        'limit' => 12,
        'order' => 'ASC',
    ], $atts, 'webksibu_team1');

    $allowed_order = ['ASC', 'DESC'];
    $order = strtoupper( sanitize_key( (string) $atts['order'] ) );
    if ( ! in_array($order, $allowed_order, true) ) {
        $order = 'ASC';
    }

    $limit = max(1, min(100, absint($atts['limit'])));

    $q = new WP_Query([
        'post_type'      => 'webksibu_team',
        'posts_per_page' => $limit,

        // ✅ Custom order: first by menu_order (manual), then title
        'orderby'        => ['menu_order' => 'ASC', 'title' => $order],
        'order'          => $order,
    ]);

    ob_start(); ?>

    <section class="webksibu-team-section">
        <h2 class="webksibu-team-title"><?php echo esc_html($atts['title']); ?></h2>

        <div class="webksibu-team-grid">
            <?php if ( $q->have_posts() ) : ?>
                <?php while ( $q->have_posts() ) : $q->the_post(); ?>
                    <?php
                        $role = get_post_meta(get_the_ID(), '_webksibu_team_role', true);
                        $img  = has_post_thumbnail()
                            ? get_the_post_thumbnail_url(get_the_ID(), 'large')
                            : WEBKSIBU_URL . 'assets/images/team-placeholder.svg';
                    ?>

                    <div class="webksibu-team-card">
                        <div class="webksibu-team-img" style="background-image:url('<?php echo esc_url($img); ?>')"></div>
                        <h4 class="webksibu-team-name"><?php echo esc_html( get_the_title() ); ?></h4>
                        <?php if ($role): ?>
                            <p class="webksibu-team-role"><?php echo esc_html($role); ?></p>
                        <?php endif; ?>
                    </div>

                <?php endwhile; wp_reset_postdata(); ?>
            <?php else : ?>
                <p>No team members added yet.</p>
            <?php endif; ?>
        </div>
    </section>

    <?php
    return ob_get_clean();
});
