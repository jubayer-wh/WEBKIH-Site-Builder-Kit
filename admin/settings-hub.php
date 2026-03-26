<?php
if ( ! defined('ABSPATH') ) exit;
if ( ! current_user_can('manage_options') ) return;

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only admin routing parameter.
$module = isset($_GET['module']) ? sanitize_key( wp_unslash($_GET['module']) ) : 'success1';

$modules = [
    'dashboard' => [
        'title' => 'Primary Dashboard',
        'desc'  => 'brand, colors',
        'file'  => WEBKSIBU_DIR . 'admin/modules/settings-dashboard.php',
    ],    
    'success1' => [
        'title' => 'Success Stories (Style 1)',
        'desc'  => 'Title, caption, hide, colors, columns',
        'file'  => WEBKSIBU_DIR . 'admin/modules/success1-settings-panel.php',
    ],
    'map1' => [
        'title' => 'Map (Style 1)',
        'desc'  => 'Title, address, button, iframe',
        'file'  => WEBKSIBU_DIR . 'admin/modules/map1-settings-panel.php',
    ],

    // Add more modules later:
    // 'hero1' => ...
    // 'reviews1' => ...
];

if ( ! isset($modules[$module]) ) {
    $module = 'success1';
}
?>

<div class="wrap">
    <h1>WEBKIH Kit Settings</h1>

    <div class="webksibu-hub">
        <div class="webksibu-hub-left">
            <h2 class="webksibu-modules-heading">Modules</h2>

            <?php foreach ($modules as $key => $m): ?>
                <?php
                    $url = add_query_arg(['page' => 'webksibu-settings', 'module' => $key], admin_url('admin.php'));
                    $active = ($key === $module) ? 'is-active' : '';
                ?>
                <a class="webksibu-mod-card <?php echo esc_attr($active); ?>" href="<?php echo esc_url($url); ?>">
                    <p class="webksibu-mod-title"><?php echo esc_html($m['title']); ?></p>
                    <p class="webksibu-mod-desc"><?php echo esc_html($m['desc']); ?></p>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="webksibu-hub-right">
            <?php
                $panel_file = $modules[$module]['file'];
                if ( file_exists($panel_file) ) {
                    require $panel_file;
                } else {
                    echo '<p>Settings panel file not found.</p>';
                }
            ?>
        </div>
    </div>
</div>
