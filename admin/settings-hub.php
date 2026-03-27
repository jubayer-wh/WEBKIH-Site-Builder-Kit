<?php
if ( ! defined('ABSPATH') ) exit;
if ( ! current_user_can('manage_options') ) return;

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only admin routing parameter.
$webksibu_module = isset($_GET['module']) ? sanitize_key( wp_unslash($_GET['module']) ) : 'success1';

$webksibu_modules = [
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

if ( ! isset($webksibu_modules[$webksibu_module]) ) {
    $webksibu_module = 'success1';
}
?>

<div class="wrap">
    <h1>WEBKIH Kit Settings</h1>

    <div class="webksibu-hub">
        <div class="webksibu-hub-left">
            <h2 class="webksibu-modules-heading">Modules</h2>

            <?php foreach ($webksibu_modules as $webksibu_key => $webksibu_module_config): ?>
                <?php
                    $webksibu_url = add_query_arg(['page' => 'webksibu-settings', 'module' => $webksibu_key], admin_url('admin.php'));
                    $webksibu_active = ($webksibu_key === $webksibu_module) ? 'is-active' : '';
                ?>
                <a class="webksibu-mod-card <?php echo esc_attr($webksibu_active); ?>" href="<?php echo esc_url($webksibu_url); ?>">
                    <p class="webksibu-mod-title"><?php echo esc_html($webksibu_module_config['title']); ?></p>
                    <p class="webksibu-mod-desc"><?php echo esc_html($webksibu_module_config['desc']); ?></p>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="webksibu-hub-right">
            <?php
                $webksibu_panel_file = $webksibu_modules[$webksibu_module]['file'];
                if ( file_exists($webksibu_panel_file) ) {
                    require $webksibu_panel_file;
                } else {
                    echo '<p>Settings panel file not found.</p>';
                }
            ?>
        </div>
    </div>
</div>
