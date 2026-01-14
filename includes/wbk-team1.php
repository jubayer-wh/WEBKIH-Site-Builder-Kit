<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Team Members – CPT + Shortcode
 * File: includes/wbk-team1.php
 * CPT: wbk_team
 * Parent Menu: WEBKIH Kit (wbk-dashboard)
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

    register_post_type('wbk_team', [
        'labels'             => $labels,
        'public'             => true,
        'show_in_menu'       => 'wbk-dashboard', // 👈 KEY LINE
        'has_archive'        => false,
        'rewrite'            => false,
        'supports'           => ['title', 'thumbnail'],
        'capability_type'    => 'post',
    ]);
});


/*--------------------------------------------------------------
2) TEAM ROLE META BOX
--------------------------------------------------------------*/
add_action('add_meta_boxes', function () {
    add_meta_box(
        'wbk_team_role_box',
        'Team Member Details',
        'wbk_team_role_box_cb',
        'wbk_team',
        'normal',
        'high'
    );
});

function wbk_team_role_box_cb($post) {
    $role = get_post_meta($post->ID, '_wbk_team_role', true);
    wp_nonce_field('wbk_team_role_save', 'wbk_team_role_nonce');
    ?>
    <p>
        <label for="wbk_team_role"><strong>Role / Designation</strong></label><br>
        <input
            type="text"
            id="wbk_team_role"
            name="wbk_team_role"
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
add_action('save_post_wbk_team', function ($post_id) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;

    if (
        ! isset($_POST['wbk_team_role_nonce']) ||
        ! wp_verify_nonce($_POST['wbk_team_role_nonce'], 'wbk_team_role_save')
    ) {
        return;
    }

    update_post_meta(
        $post_id,
        '_wbk_team_role',
        sanitize_text_field( wp_unslash($_POST['wbk_team_role'] ?? '') )
    );
});


/*--------------------------------------------------------------
4) SHORTCODE OUTPUT
--------------------------------------------------------------*/
add_shortcode('wbk_team1', function ($atts) {

    wp_enqueue_style(
        'wbk-team1-css',
        WBK_URL . 'assets/css/team1.css',
        [],
        WBK_VER
    );

    $atts = shortcode_atts([
        'title' => 'Meet Our Expert Consultants',
        'limit' => 12,
        'order' => 'ASC',
    ], $atts);

    $q = new WP_Query([
        'post_type'      => 'wbk_team',
        'posts_per_page' => (int) $atts['limit'],
        'order'          => $atts['order'],
    ]);

    ob_start(); ?>

    <section class="wbk-team-section">
        <h2 class="wbk-team-title"><?php echo esc_html($atts['title']); ?></h2>

        <div class="wbk-team-grid">
            <?php if ( $q->have_posts() ) : ?>
                <?php while ( $q->have_posts() ) : $q->the_post(); ?>
                    <?php
                        $role = get_post_meta(get_the_ID(), '_wbk_team_role', true);
                        $img  = has_post_thumbnail()
                            ? get_the_post_thumbnail_url(get_the_ID(), 'large')
                            : WBK_URL . 'assets/images/team-placeholder.svg';
                    ?>

                    <div class="wbk-team-card">
                        <div class="wbk-team-img" style="background-image:url('<?php echo esc_url($img); ?>')"></div>
                        <h4 class="wbk-team-name"><?php the_title(); ?></h4>
                        <?php if ($role): ?>
                            <p class="wbk-team-role"><?php echo esc_html($role); ?></p>
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
