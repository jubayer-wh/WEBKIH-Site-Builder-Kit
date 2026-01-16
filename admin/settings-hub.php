<?php
if ( ! defined('ABSPATH') ) exit;
if ( ! current_user_can('manage_options') ) return;

/**
 * WEBKIH Kit – Settings Hub
 * Secure module switching with nonce protection
 */

/*--------------------------------------------------------------
1) NONCE + SAFE MODULE RESOLUTION
--------------------------------------------------------------*/
$wbk_settings_nonce = wp_create_nonce('wbk_settings_module');

$module = 'success1';

if (
    isset($_GET['module'], $_GET['_wpnonce']) &&
    wp_verify_nonce(
        sanitize_text_field( wp_unslash($_GET['_wpnonce']) ),
        'wbk_settings_module'
    )
) {
    $module = sanitize_key( wp_unslash($_GET['module']) );
}


/*--------------------------------------------------------------
2) MODULE REGISTRY
--------------------------------------------------------------*/
$modules = [
    'dashboard' => [
        'title' => 'Primary Dashboard',
        'desc'  => 'Brand, colors',
        'file'  => WBK_DIR . 'admin/modules/settings-dashboard.php',
    ],
    'success1' => [
        'title' => 'Success Stories (Style 1)',
        'desc'  => 'Title, caption, hide, colors, columns',
        'file'  => WBK_DIR . 'admin/modules/success1-settings-panel.php',
    ],
    'map1' => [
        'title' => 'Map (Style 1)',
        'desc'  => 'Title, address, button, iframe',
        'file'  => WBK_DIR . 'admin/modules/map1-settings-panel.php',
    ],
];

// Fallback if invalid module requested
if ( ! isset($modules[$module]) ) {
    $module = 'success1';
}
?>

<div class="wrap">
    <h1><?php echo esc_html__('WEBKIH Kit Settings', 'webkih-site-builder-kit'); ?></h1>

    <style>
        .wbk-hub {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 18px;
            margin-top: 16px;
        }
        .wbk-hub-left {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 14px;
        }
        .wbk-mod-card {
            display: block;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #eef2f7;
            text-decoration: none;
            margin-bottom: 10px;
            color: #111827;
            transition: 0.2s ease;
        }
        .wbk-mod-card:hover {
            border-color:#cbd5e1;
            transform: translateY(-1px);
        }
        .wbk-mod-card.is-active {
            border-color:#60a5fa;
            box-shadow: 0 8px 20px rgba(96,165,250,0.18);
        }
        .wbk-mod-title {
            font-weight: 800;
            margin: 0 0 4px;
        }
        .wbk-mod-desc {
            margin: 0;
            color:#64748b;
            font-size: 13px;
        }
        .wbk-hub-right {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 18px;
        }
        @media (max-width: 960px) {
            .wbk-hub { grid-template-columns: 1fr; }
        }
    </style>

    <div class="wbk-hub">

        <!-- LEFT: MODULE LIST -->
        <div class="wbk-hub-left">
            <h2 style="margin-top:0;"><?php echo esc_html__('Modules', 'webkih-site-builder-kit'); ?></h2>

            <?php foreach ( $modules as $key => $m ) : ?>
                <?php
                    $url = wp_nonce_url(
                        add_query_arg(
                            [
                                'page'   => 'wbk-settings',
                                'module' => $key,
                            ],
                            admin_url('admin.php')
                        ),
                        'wbk_settings_module'
                    );

                    $active = ($key === $module) ? 'is-active' : '';
                ?>
                <a class="wbk-mod-card <?php echo esc_attr($active); ?>" href="<?php echo esc_url($url); ?>">
                    <p class="wbk-mod-title"><?php echo esc_html($m['title']); ?></p>
                    <p class="wbk-mod-desc"><?php echo esc_html($m['desc']); ?></p>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- RIGHT: MODULE PANEL -->
        <div class="wbk-hub-right">
            <?php
                $panel_file = $modules[$module]['file'];

                if ( file_exists($panel_file) ) {
                    require $panel_file;
                } else {
                    echo '<p>' . esc_html__(
                        'Settings panel file not found.',
                        'webkih-site-builder-kit'
                    ) . '</p>';
                }
            ?>
        </div>

    </div>
</div>
