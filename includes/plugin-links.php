<?php
if (!defined('ABSPATH')) {
    exit;
}

class KSHM_Plugin_Links {

    public function __construct() {
        add_filter('plugin_action_links_' . plugin_basename(KSHM_PLUGIN_PATH . 'kashiwazaki-seo-hamburger-menu.php'), array($this, 'add_plugin_links'));
    }

    public function add_plugin_links($links) {
        $settings_link = '<a href="' . admin_url('admin.php?page=kshm-settings') . '">詳細設定</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}

new KSHM_Plugin_Links();
