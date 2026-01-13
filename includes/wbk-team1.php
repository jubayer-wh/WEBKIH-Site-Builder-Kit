<?php
if ( ! defined('ABSPATH') ) exit;

add_shortcode('wbk_team1', function(){

    wp_enqueue_style('wbk-team-css', WBK_URL . 'assets/css/team.css', [], WBK_VER);

    // Default sample data (later you can load from CPT or settings)
    $members = [
        ['name' => 'Jubayer Hossain', 'role' => 'Founder, WEBKIH'],
        ['name' => 'Team Member 2', 'role' => 'Designer'],
        ['name' => 'Team Member 3', 'role' => 'Developer'],
        ['name' => 'Team Member 4', 'role' => 'Support'],
    ];

    ob_start(); ?>
    <section class="wbk-team1">
        <div class="wbk-team-grid">
            <?php foreach($members as $m): ?>
                <div class="wbk-member">
                    <p class="wbk-name"><?php echo esc_html($m['name']); ?></p>
                    <p class="wbk-role"><?php echo esc_html($m['role']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
});
