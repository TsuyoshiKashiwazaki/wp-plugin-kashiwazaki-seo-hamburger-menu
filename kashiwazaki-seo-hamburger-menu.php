<?php
/**
 * Plugin Name: Kashiwazaki SEO Hamburger Menu
 * Plugin URI: https://www.tsuyoshikashiwazaki.jp
 * Description: 指定した幅になったらハンバーガーメニューを表示するWordPressプラグイン
 * Version: 1.0.1
 * Author: 柏崎剛 (Tsuyoshi Kashiwazaki)
 * Author URI: https://www.tsuyoshikashiwazaki.jp/profile/
 * License: GPL v2 or later
 * Text Domain: kashiwazaki-seo-hamburger-menu
 */

if (!defined('ABSPATH')) {
    exit;
}

define('KSHM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('KSHM_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('KSHM_PLUGIN_VERSION', '1.0.1');

require_once KSHM_PLUGIN_PATH . 'includes/plugin-links.php';

class KashiwazakiSeoHamburgerMenu {

    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_footer', array($this, 'render_menu'));
        add_action('admin_menu', array($this, 'admin_menu'), 81);
        add_action('admin_init', array($this, 'admin_init'));
        add_action('wp_head', array($this, 'add_custom_css'));
        add_action('wp_head', array($this, 'add_structured_data'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }

    public function init() {
        load_plugin_textdomain('kashiwazaki-seo-hamburger-menu', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    public function enqueue_scripts() {
        wp_enqueue_script('kshm-script', KSHM_PLUGIN_URL . 'assets/js/script.js', array('jquery'), KSHM_PLUGIN_VERSION, true);
        wp_enqueue_style('kshm-style', KSHM_PLUGIN_URL . 'assets/css/style.css', array(), KSHM_PLUGIN_VERSION);

        $options = get_option('kshm_options', array());
        wp_localize_script('kshm-script', 'kshm_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('kshm_nonce')
        ));
    }

    public function admin_enqueue_scripts($hook) {
        if ($hook != 'toplevel_page_kshm-settings') {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('kshm-admin', KSHM_PLUGIN_URL . 'assets/js/admin.js', array('wp-color-picker'), KSHM_PLUGIN_VERSION, true);
    }

    public function render_menu() {
        $options = get_option('kshm_options', array());
        $breakpoint = isset($options['breakpoint']) ? $options['breakpoint'] : 768;
        $menu_position = isset($options['menu_position']) ? $options['menu_position'] : 'right';
        $menu_type = isset($options['menu_type']) ? $options['menu_type'] : 'default';
        
        // カラー設定を取得
        $menu_color = isset($options['menu_color']) ? $options['menu_color'] : '#333333';
        $submenu_color = isset($options['submenu_color']) ? $options['submenu_color'] : '#666666';
        $background_color = isset($options['background_color']) ? $options['background_color'] : '#ffffff';
        $hamburger_color = isset($options['hamburger_color']) ? $options['hamburger_color'] : '#000000';
        $hamburger_line_color = isset($options['hamburger_line_color']) ? $options['hamburger_line_color'] : '#ffffff';
        $hover_color = isset($options['hover_color']) ? $options['hover_color'] : '#007cba';
        
        // CSSカスタムプロパティを出力
        ?>
        <style>
            :root {
                --kshm-menu-color: <?php echo esc_attr($menu_color); ?>;
                --kshm-submenu-color: <?php echo esc_attr($submenu_color); ?>;
                --kshm-menu-bg-color: <?php echo esc_attr($background_color); ?>;
                --kshm-hamburger-bg-color: <?php echo esc_attr($hamburger_color); ?>;
                --kshm-hamburger-line-color: <?php echo esc_attr($hamburger_line_color); ?>;
                --kshm-hover-color: <?php echo esc_attr($hover_color); ?>;
            }
        </style>
        <?php

        include KSHM_PLUGIN_PATH . 'templates/menu-template.php';
    }

    public function admin_menu() {
        add_menu_page(
            'Kashiwazaki SEO Hamburger Menu',
            'Kashiwazaki SEO Hamburger Menu',
            'manage_options',
            'kshm-settings',
            array($this, 'admin_page'),
            'dashicons-menu',
            81
        );
    }

    public function admin_init() {
        register_setting('kshm_options', 'kshm_options', array($this, 'sanitize_options'));

        add_settings_section(
            'kshm_general_section',
            '基本設定',
            array($this, 'general_section_callback'),
            'kshm-settings'
        );

        add_settings_field(
            'breakpoint',
            'ブレークポイント（px）',
            array($this, 'breakpoint_callback'),
            'kshm-settings',
            'kshm_general_section'
        );

        add_settings_field(
            'menu_position',
            'メニュー位置',
            array($this, 'menu_position_callback'),
            'kshm-settings',
            'kshm_general_section'
        );

        add_settings_field(
            'menu_type',
            'メニュータイプ',
            array($this, 'menu_type_callback'),
            'kshm-settings',
            'kshm_general_section'
        );

        add_settings_field(
            'existing_menu',
            '既存メニュー選択',
            array($this, 'existing_menu_callback'),
            'kshm-settings',
            'kshm_general_section'
        );

        add_settings_field(
            'custom_menu_items',
            'カスタムメニュー項目',
            array($this, 'custom_menu_items_callback'),
            'kshm-settings',
            'kshm_general_section'
        );
        
        add_settings_field(
            'show_home_link',
            'ホームリンクを表示',
            array($this, 'show_home_link_callback'),
            'kshm-settings',
            'kshm_general_section'
        );

                add_settings_field(
            'menu_color',
            'メニュー文字色',
            array($this, 'menu_color_callback'),
            'kshm-settings',
            'kshm_general_section'
        );

        add_settings_field(
            'submenu_color',
            'サブメニュー文字色',
            array($this, 'submenu_color_callback'),
            'kshm-settings',
            'kshm_general_section'
        );

        add_settings_field(
            'hover_color',
            'ホバー時の色',
            array($this, 'hover_color_callback'),
            'kshm-settings',
            'kshm_general_section'
        );

        add_settings_field(
            'background_color',
            'メニュー背景色',
            array($this, 'background_color_callback'),
            'kshm-settings',
            'kshm_general_section'
        );

        add_settings_field(
            'hamburger_color',
            'ハンバーガーボタン色',
            array($this, 'hamburger_color_callback'),
            'kshm-settings',
            'kshm_general_section'
        );

        add_settings_field(
            'hamburger_line_color',
            'ハンバーガー3本線色',
            array($this, 'hamburger_line_color_callback'),
            'kshm-settings',
            'kshm_general_section'
        );

        add_settings_field(
            'structured_data',
            '構造化マークアップ',
            array($this, 'structured_data_callback'),
            'kshm-settings',
            'kshm_general_section'
        );
    }

    public function sanitize_options($input) {
        $sanitized_input = array();

        // 数値フィールド
        $sanitized_input['breakpoint'] = isset($input['breakpoint']) ? absint($input['breakpoint']) : 768;

        // テキストフィールド
        $sanitized_input['menu_position'] = isset($input['menu_position']) ? sanitize_text_field($input['menu_position']) : 'right';
        $sanitized_input['custom_position_top'] = isset($input['custom_position_top']) ? absint($input['custom_position_top']) : 15;
        $sanitized_input['custom_position_side'] = isset($input['custom_position_side']) ? sanitize_text_field($input['custom_position_side']) : 'right';
        $sanitized_input['custom_position_side_value'] = isset($input['custom_position_side_value']) ? absint($input['custom_position_side_value']) : 35;
        $sanitized_input['menu_type'] = isset($input['menu_type']) ? sanitize_text_field($input['menu_type']) : 'default';
        $sanitized_input['existing_menu'] = isset($input['existing_menu']) ? sanitize_text_field($input['existing_menu']) : '';
        $sanitized_input['custom_menu_items'] = isset($input['custom_menu_items']) ? sanitize_textarea_field($input['custom_menu_items']) : '';

        // チェックボックスフィールド - 明示的に0を設定
        $sanitized_input['show_home_link'] = isset($input['show_home_link']) && $input['show_home_link'] == '1' ? '1' : '0';
        $sanitized_input['structured_data'] = isset($input['structured_data']) && $input['structured_data'] == '1' ? '1' : '0';

        // カラーフィールド（#付きの16進数カラーコードを検証）
        $sanitized_input['menu_color'] = isset($input['menu_color']) && preg_match('/^#[a-fA-F0-9]{6}$/', $input['menu_color']) ? $input['menu_color'] : '#333333';
        $sanitized_input['submenu_color'] = isset($input['submenu_color']) && preg_match('/^#[a-fA-F0-9]{6}$/', $input['submenu_color']) ? $input['submenu_color'] : '#666666';
        $sanitized_input['background_color'] = isset($input['background_color']) && preg_match('/^#[a-fA-F0-9]{6}$/', $input['background_color']) ? $input['background_color'] : '#ffffff';
        $sanitized_input['hamburger_color'] = isset($input['hamburger_color']) && preg_match('/^#[a-fA-F0-9]{6}$/', $input['hamburger_color']) ? $input['hamburger_color'] : '#000000';
        $sanitized_input['hamburger_line_color'] = isset($input['hamburger_line_color']) && preg_match('/^#[a-fA-F0-9]{6}$/', $input['hamburger_line_color']) ? $input['hamburger_line_color'] : '#ffffff';
        $sanitized_input['hover_color'] = isset($input['hover_color']) && preg_match('/^#[a-fA-F0-9]{6}$/', $input['hover_color']) ? $input['hover_color'] : '#007cba';

        return $sanitized_input;
    }

    public function admin_page() {
        if (isset($_POST['kshm_reset_defaults'])) {
            // nonceの検証
            if (isset($_POST['kshm_reset_nonce']) && wp_verify_nonce($_POST['kshm_reset_nonce'], 'kshm_reset_defaults')) {
                $this->reset_to_defaults();
            } else {
                add_action('admin_notices', function() {
                    echo '<div class="notice notice-error is-dismissible"><p>セキュリティチェックに失敗しました。</p></div>';
                });
            }
        }
        include KSHM_PLUGIN_PATH . 'templates/admin-template.php';
    }

                private function reset_to_defaults() {
        $default_options = array(
            'breakpoint' => 768,
            'menu_position' => 'right',
            'custom_position_top' => 15,
            'custom_position_side' => 'right',
            'custom_position_side_value' => 35,
            'menu_type' => 'default',
            'existing_menu' => '',
            'custom_menu_items' => '',
            'show_home_link' => '1',
            'menu_color' => '#333333',
            'submenu_color' => '#666666',
            'background_color' => '#ffffff',
            'hamburger_color' => '#000000',
            'hamburger_line_color' => '#ffffff',
            'hover_color' => '#007cba',
            'structured_data' => '0'
        );

        update_option('kshm_options', $default_options);
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>設定をデフォルトに戻しました。</p></div>';
        });
    }

    public function general_section_callback() {
        echo '<p>ハンバーガーメニューの基本設定を行ってください。</p>';
    }

    public function breakpoint_callback() {
        $options = get_option('kshm_options', array());
        $value = isset($options['breakpoint']) ? $options['breakpoint'] : 768;
        echo '<input type="number" name="kshm_options[breakpoint]" value="' . esc_attr($value) . '" min="320" max="1920" step="1" /> px';
    }

        public function menu_position_callback() {
        $options = get_option('kshm_options', array());
        $value = isset($options['menu_position']) ? $options['menu_position'] : 'right';
        $positions = array(
            'left' => '左上',
            'right' => '右上',
            'custom' => 'カスタム'
        );

        foreach ($positions as $key => $label) {
            $checked = ($value === $key) ? 'checked' : '';
            echo '<label><input type="radio" name="kshm_options[menu_position]" value="' . $key . '" ' . $checked . ' /> ' . $label . '</label><br>';
        }
        
        // カスタム位置の入力フィールド
        $custom_top = isset($options['custom_position_top']) ? $options['custom_position_top'] : '15';
        $custom_side = isset($options['custom_position_side']) ? $options['custom_position_side'] : 'right';
        $custom_side_value = isset($options['custom_position_side_value']) ? $options['custom_position_side_value'] : '35';
        
        echo '<div id="kshm-custom-position" style="margin-top: 10px; padding-left: 20px;">';
        echo '<p style="margin: 5px 0;">カスタム位置設定（「カスタム」選択時に有効）:</p>';
        echo '<label>上からの距離: <input type="number" name="kshm_options[custom_position_top]" value="' . esc_attr($custom_top) . '" min="0" max="200" style="width: 60px;"> px</label><br>';
        echo '<label>配置: ';
        echo '<select name="kshm_options[custom_position_side]" style="margin: 5px;">';
        echo '<option value="left"' . selected($custom_side, 'left', false) . '>左</option>';
        echo '<option value="right"' . selected($custom_side, 'right', false) . '>右</option>';
        echo '</select></label>';
        echo '<label>端からの距離: <input type="number" name="kshm_options[custom_position_side_value]" value="' . esc_attr($custom_side_value) . '" min="0" max="200" style="width: 60px;"> px</label>';
        echo '</div>';
    }

        public function menu_type_callback() {
        $options = get_option('kshm_options', array());
        $value = isset($options['menu_type']) ? $options['menu_type'] : 'default';
        $types = array(
            'default' => '既存のメニューを反映',
            'custom' => 'カスタムメニュー'
        );

        foreach ($types as $key => $label) {
            $checked = ($value === $key) ? 'checked' : '';
            echo '<label><input type="radio" name="kshm_options[menu_type]" value="' . $key . '" ' . $checked . ' /> ' . $label . '</label><br>';
        }
    }

    public function existing_menu_callback() {
        $options = get_option('kshm_options', array());
        $value = isset($options['existing_menu']) ? $options['existing_menu'] : '';

        $menus = wp_get_nav_menus();
        if (!empty($menus)) {
            echo '<select name="kshm_options[existing_menu]">';
            echo '<option value="">メニューを選択してください</option>';
            foreach ($menus as $menu) {
                $selected = ($value == $menu->term_id) ? 'selected' : '';
                echo '<option value="' . esc_attr($menu->term_id) . '" ' . $selected . '>' . esc_html($menu->name) . '</option>';
            }
            echo '</select>';
        } else {
            echo '<p>利用可能なメニューがありません。</p>';
        }
    }

    public function custom_menu_items_callback() {
        $options = get_option('kshm_options', array());
        $value = isset($options['custom_menu_items']) ? $options['custom_menu_items'] : '';
        echo '<textarea name="kshm_options[custom_menu_items]" rows="10" cols="50" placeholder="メニュー項目を1行に1つずつ記述してください。&#10;例：&#10;ホーム|/|&#10;会社概要|/about/|&#10;サービス|/services/|サービス1:/service1/,サービス2:/service2/&#10;お問い合わせ|/contact/">' . esc_textarea($value) . '</textarea>';
        echo '<p><small>形式：メニュー名|URL|（サブメニューがある場合：サブメニュー名:URL,サブメニュー名:URL）</small></p>';
    }
    
    public function show_home_link_callback() {
        $options = get_option('kshm_options', array());
        $value = isset($options['show_home_link']) ? $options['show_home_link'] : '1';
        echo '<label><input type="checkbox" name="kshm_options[show_home_link]" value="1" ' . checked(1, $value, false) . ' /> メニューの最初にホームリンクを追加する</label>';
        echo '<p><small>チェックを外すと、メニューからホームリンクが非表示になります。</small></p>';
    }

    public function menu_color_callback() {
        $options = get_option('kshm_options', array());
        $value = isset($options['menu_color']) ? $options['menu_color'] : '#333333';
        echo '<input type="text" class="kshm-color-picker" name="kshm_options[menu_color]" value="' . esc_attr($value) . '" data-default-color="#333333" />';
    }

    public function background_color_callback() {
        $options = get_option('kshm_options', array());
        $value = isset($options['background_color']) ? $options['background_color'] : '#ffffff';
        echo '<input type="text" class="kshm-color-picker" name="kshm_options[background_color]" value="' . esc_attr($value) . '" data-default-color="#ffffff" />';
    }

    public function submenu_color_callback() {
        $options = get_option('kshm_options', array());
        $value = isset($options['submenu_color']) ? $options['submenu_color'] : '#666666';
        echo '<input type="text" class="kshm-color-picker" name="kshm_options[submenu_color]" value="' . esc_attr($value) . '" data-default-color="#666666" />';
    }

    public function hover_color_callback() {
        $options = get_option('kshm_options', array());
        $value = isset($options['hover_color']) ? $options['hover_color'] : '#007cba';
        echo '<input type="text" class="kshm-color-picker" name="kshm_options[hover_color]" value="' . esc_attr($value) . '" data-default-color="#007cba" />';
    }

    public function hamburger_color_callback() {
        $options = get_option('kshm_options', array());
        $value = isset($options['hamburger_color']) ? $options['hamburger_color'] : '#000000';
        echo '<input type="text" class="kshm-color-picker" name="kshm_options[hamburger_color]" value="' . esc_attr($value) . '" data-default-color="#000000" />';
    }

    public function hamburger_line_color_callback() {
        $options = get_option('kshm_options', array());
        $value = isset($options['hamburger_line_color']) ? $options['hamburger_line_color'] : '#ffffff';
        echo '<input type="text" class="kshm-color-picker" name="kshm_options[hamburger_line_color]" value="' . esc_attr($value) . '" data-default-color="#ffffff" />';
    }


    public function structured_data_callback() {
        $options = get_option('kshm_options', array());
        $value = isset($options['structured_data']) ? $options['structured_data'] : '0';
        echo '<label><input type="checkbox" name="kshm_options[structured_data]" value="1" ' . checked(1, $value, false) . ' /> SiteNavigationElementの構造化マークアップを出力する</label>';
        echo '<p><small>チェックを入れると、ハンバーガーメニューにSiteNavigationElementの構造化マークアップが出力されます。SEO対策として効果的です。</small></p>';
    }

                        public function add_custom_css() {
        $options = get_option('kshm_options', array());
        $menu_color = isset($options['menu_color']) ? $options['menu_color'] : '#333333';
        $submenu_color = isset($options['submenu_color']) ? $options['submenu_color'] : '#666666';
        $background_color = isset($options['background_color']) ? $options['background_color'] : '#ffffff';
        $hamburger_color = isset($options['hamburger_color']) ? $options['hamburger_color'] : '#000000';
        $hamburger_line_color = isset($options['hamburger_line_color']) ? $options['hamburger_line_color'] : '#ffffff';
        $hover_color = isset($options['hover_color']) ? $options['hover_color'] : '#007cba';
        
        // ハンバーガーボタンのホバー色を少し明るくする
        $hamburger_hover_color = $this->adjust_brightness($hamburger_color, 20);

        echo '<style>
        #kshm-container {
            --kshm-menu-color: ' . esc_attr($menu_color) . ';
            --kshm-submenu-color: ' . esc_attr($submenu_color) . ';
            --kshm-hover-color: ' . esc_attr($hover_color) . ';
            --kshm-hamburger-line-color: ' . esc_attr($hamburger_line_color) . ';
            --kshm-hamburger-bg-color: ' . esc_attr($hamburger_color) . ';
            --kshm-hamburger-bg-hover-color: ' . esc_attr($hamburger_hover_color) . ';
            --kshm-menu-bg-color: ' . esc_attr($background_color) . ';
        }
        </style>';
    }

    /**
     * 色の明度を調整する関数
     */
    private function adjust_brightness($hex, $steps) {
        $hex = str_replace('#', '', $hex);

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, min(255, $r + $steps));
        $g = max(0, min(255, $g + $steps));
        $b = max(0, min(255, $b + $steps));

        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }
    

    public function activate() {
        $default_options = array(
            'breakpoint' => 768,
            'menu_position' => 'right',
            'custom_position_top' => 15,
            'custom_position_side' => 'right',
            'custom_position_side_value' => 35,
            'menu_type' => 'default',
            'existing_menu' => '',
            'custom_menu_items' => '',
            'show_home_link' => '1',
            'menu_color' => '#333333',
            'submenu_color' => '#666666',
            'background_color' => '#ffffff',
            'hamburger_color' => '#000000',
            'hamburger_line_color' => '#ffffff',
            'hover_color' => '#007cba',
            'structured_data' => '0'
        );

        if (!get_option('kshm_options')) {
            add_option('kshm_options', $default_options);
        }
    }

    public function deactivate() {
    }

    public function add_structured_data() {
        $options = get_option('kshm_options', array());
        $structured_data = isset($options['structured_data']) ? $options['structured_data'] : '0';

        if ($structured_data === '1') {
            $menu_items = $this->get_menu_items_for_structured_data();

            if (!empty($menu_items)) {
                $structured_data_json = array(
                    '@context' => 'https://schema.org',
                    '@type' => 'SiteNavigationElement',
                    'name' => 'ハンバーガーメニュー',
                    'hasPart' => $menu_items
                );

                echo '<script type="application/ld+json">' . wp_json_encode($structured_data_json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
            }
        }
    }

        private function get_menu_items_for_structured_data() {
        $options = get_option('kshm_options', array());
        $menu_type = isset($options['menu_type']) ? $options['menu_type'] : 'default';
        $existing_menu = isset($options['existing_menu']) ? $options['existing_menu'] : '';
        $custom_menu_items = isset($options['custom_menu_items']) ? $options['custom_menu_items'] : '';
        $menu_items = array();

        if ($menu_type === 'default') {
            if (!empty($existing_menu)) {
                $nav_items = wp_get_nav_menu_items($existing_menu);
                if (!empty($nav_items)) {
                    foreach ($nav_items as $item) {
                        $menu_items[] = array(
                            '@type' => 'WebPage',
                            'name' => $item->title,
                            'url' => $item->url
                        );
                    }
                }
            } else {
                $locations = get_nav_menu_locations();

                if (!empty($locations)) {
                    foreach ($locations as $location => $menu_id) {
                        if ($menu_id && $menu = wp_get_nav_menu_object($menu_id)) {
                            $nav_items = wp_get_nav_menu_items($menu_id);
                            if (!empty($nav_items)) {
                                foreach ($nav_items as $item) {
                                    $menu_items[] = array(
                                        '@type' => 'WebPage',
                                        'name' => $item->title,
                                        'url' => $item->url
                                    );
                                }
                                break;
                            }
                        }
                    }
                }

                if (empty($menu_items)) {
                    $pages = get_pages(array('sort_column' => 'menu_order', 'sort_order' => 'asc'));
                    foreach ($pages as $page) {
                        $menu_items[] = array(
                            '@type' => 'WebPage',
                            'name' => $page->post_title,
                            'url' => get_permalink($page->ID)
                        );
                    }
                }
            }
        } else {
            if (!empty($custom_menu_items)) {
                $lines = explode("\n", $custom_menu_items);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (empty($line)) continue;

                    $parts = explode('|', $line);
                    if (count($parts) >= 2) {
                        $title = trim($parts[0]);
                        $url = trim($parts[1]);
                        if (!empty($title) && !empty($url)) {
                            $menu_items[] = array(
                                '@type' => 'WebPage',
                                'name' => $title,
                                'url' => $url
                            );
                        }
                    }
                }
            }
        }

        return $menu_items;
    }

    public function get_menu_items_with_children($menu_id) {
        $menu_items = wp_get_nav_menu_items($menu_id);
        $menu_tree = array();
        $menu_lookup = array();

        if (!empty($menu_items)) {
            foreach ($menu_items as $item) {
                $menu_lookup[$item->ID] = $item;
                $item->children = array();
            }

            foreach ($menu_items as $item) {
                if ($item->menu_item_parent == 0) {
                    $menu_tree[] = $item;
                } else {
                    if (isset($menu_lookup[$item->menu_item_parent])) {
                        $menu_lookup[$item->menu_item_parent]->children[] = $item;
                    }
                }
            }
        }

        return $menu_tree;
    }

    public function is_current_page($url) {
        // メニューアイテムのURLを正規化
        $check_url = untrailingslashit($url);

        // 現在のページURLを取得
        $current_url = untrailingslashit(home_url(add_query_arg(array(), $GLOBALS['wp']->request)));

        // ホームページの特別な処理
        if (is_front_page() || is_home()) {
            $home_url = untrailingslashit(home_url());
            // ホームURLと完全一致するか、または'/'のみの場合
            if ($check_url === $home_url || $check_url === '/' || $check_url === '' ||
                rtrim($check_url, '/') === rtrim($home_url, '/')) {
                return true;
            }
        }

        // 通常のページの場合
        // 完全一致をチェック（末尾スラッシュを無視）
        if (rtrim($current_url, '/') === rtrim($check_url, '/')) {
            return true;
        }

        // パスのみで比較（プロトコルやドメインを除外）
        $current_path = parse_url($current_url, PHP_URL_PATH);
        $check_path = parse_url($check_url, PHP_URL_PATH);

        if ($current_path !== null && $check_path !== null) {
            // パスを正規化（スラッシュを統一、空の場合は'/'に）
            $current_path = '/' . ltrim($current_path ?: '/', '/');
            $check_path = '/' . ltrim($check_path ?: '/', '/');

            // 末尾スラッシュを無視して比較
            if (rtrim($current_path, '/') === rtrim($check_path, '/')) {
                return true;
            }
        }

        return false;
    }
}

new KashiwazakiSeoHamburgerMenu();
