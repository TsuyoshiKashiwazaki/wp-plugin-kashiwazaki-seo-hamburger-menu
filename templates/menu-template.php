<?php
if (!defined('ABSPATH')) {
    exit;
}

$options = get_option('kshm_options', array());
$breakpoint = isset($options['breakpoint']) ? $options['breakpoint'] : 768;
$menu_position = isset($options['menu_position']) ? $options['menu_position'] : 'right';
$menu_type = isset($options['menu_type']) ? $options['menu_type'] : 'default';
$existing_menu = isset($options['existing_menu']) ? $options['existing_menu'] : '';
$custom_menu_items = isset($options['custom_menu_items']) ? $options['custom_menu_items'] : '';
$show_home_link = isset($options['show_home_link']) ? $options['show_home_link'] : '1';

$position_class = 'kshm-position-' . $menu_position;

// カスタム位置の設定
$custom_styles = '';
if ($menu_position === 'custom') {
    $custom_top = isset($options['custom_position_top']) ? $options['custom_position_top'] : 15;
    $custom_side = isset($options['custom_position_side']) ? $options['custom_position_side'] : 'right';
    $custom_side_value = isset($options['custom_position_side_value']) ? $options['custom_position_side_value'] : 35;
    
    $position_class .= ' kshm-custom-' . $custom_side;
    $custom_styles = ' style="--kshm-custom-top: ' . esc_attr($custom_top) . 'px; --kshm-custom-side-value: ' . esc_attr($custom_side_value) . 'px;"';
}
?>

<div id="kshm-container" class="kshm-container <?php echo esc_attr($position_class); ?>" data-breakpoint="<?php echo esc_attr($breakpoint); ?>"<?php echo $custom_styles; ?>>
    <div class="kshm-hamburger-button" id="kshm-hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="kshm-menu-overlay" id="kshm-overlay">
        <div class="kshm-menu-content">
            <div class="kshm-menu-header">
                <div class="kshm-close-button" id="kshm-close"></div>
            </div>

            <nav class="kshm-navigation">
                <?php if ($menu_type === 'default'): ?>
                    <?php
                    $menu_items = array();

                    if (!empty($existing_menu)) {
                        $menu_items = $this->get_menu_items_with_children($existing_menu);
                    } else {
                        $locations = get_nav_menu_locations();
                        if (!empty($locations)) {
                            foreach ($locations as $location => $menu_id) {
                                if ($menu_id && $menu = wp_get_nav_menu_object($menu_id)) {
                                    $menu_items = $this->get_menu_items_with_children($menu_id);
                                    break;
                                }
                            }
                        }

                        if (empty($menu_items)) {
                            $all_menus = wp_get_nav_menus();
                            if (!empty($all_menus)) {
                                $menu_items = $this->get_menu_items_with_children($all_menus[0]->term_id);
                            }
                        }
                    }

                    if (!empty($menu_items)): ?>
                        <ul class="kshm-menu-list">
                            <?php if ($show_home_link == '1'): ?>
                                <li class="kshm-menu-item<?php echo ($this->is_current_page(home_url('/'))) ? ' kshm-current-page' : ''; ?>">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" class="kshm-menu-link">ホーム</a>
                                </li>
                            <?php endif; ?>
                            <?php foreach ($menu_items as $item): ?>
                                <li class="kshm-menu-item<?php echo ($this->is_current_page($item->url)) ? ' kshm-current-page' : ''; ?>">
                                    <a href="<?php echo esc_url($item->url); ?>" class="kshm-menu-link">
                                        <?php echo esc_html($item->title); ?>
                                    </a>
                                    <?php if (!empty($item->children)): ?>
                                        <ul class="kshm-submenu">
                                            <?php foreach ($item->children as $child): ?>
                                                <li class="kshm-submenu-item<?php echo ($this->is_current_page($child->url)) ? ' kshm-current-page' : ''; ?>">
                                                    <a href="<?php echo esc_url($child->url); ?>" class="kshm-submenu-link">
                                                        <?php echo esc_html($child->title); ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <ul class="kshm-menu-list">
                            <?php if ($show_home_link == '1'): ?>
                                <li class="kshm-menu-item<?php echo ($this->is_current_page(home_url('/'))) ? ' kshm-current-page' : ''; ?>">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" class="kshm-menu-link">ホーム</a>
                                </li>
                            <?php endif; ?>
                            <?php
                            $pages = get_pages(array('sort_column' => 'menu_order', 'sort_order' => 'asc'));
                            foreach ($pages as $page): ?>
                                <li class="kshm-menu-item">
                                    <a href="<?php echo esc_url(get_permalink($page->ID)); ?>" class="kshm-menu-link">
                                        <?php echo esc_html($page->post_title); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                <?php else: ?>
                    <?php if (!empty($custom_menu_items)): ?>
                        <ul class="kshm-menu-list">
                            <?php if ($show_home_link == '1'): ?>
                                <li class="kshm-menu-item<?php echo ($this->is_current_page(home_url('/'))) ? ' kshm-current-page' : ''; ?>">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" class="kshm-menu-link">ホーム</a>
                                </li>
                            <?php endif; ?>
                            <?php
                            $lines = explode("\n", $custom_menu_items);
                            foreach ($lines as $line):
                                $line = trim($line);
                                if (empty($line)) continue;

                                $parts = explode('|', $line);
                                if (count($parts) >= 2):
                                    $title = trim($parts[0]);
                                    $url = trim($parts[1]);
                                    $submenu = isset($parts[2]) ? trim($parts[2]) : '';
                                    if (!empty($title) && !empty($url)):
                            ?>
                                <li class="kshm-menu-item<?php echo ($this->is_current_page($url)) ? ' kshm-current-page' : ''; ?>">
                                    <a href="<?php echo esc_url($url); ?>" class="kshm-menu-link">
                                        <?php echo esc_html($title); ?>
                                    </a>
                                    <?php if (!empty($submenu)): ?>
                                        <ul class="kshm-submenu">
                                            <?php
                                            $submenu_items = explode(',', $submenu);
                                            foreach ($submenu_items as $submenu_item):
                                                $submenu_parts = explode(':', $submenu_item);
                                                if (count($submenu_parts) >= 2):
                                                    $submenu_title = trim($submenu_parts[0]);
                                                    $submenu_url = trim($submenu_parts[1]);
                                            ?>
                                                <li class="kshm-submenu-item">
                                                    <a href="<?php echo esc_url($submenu_url); ?>" class="kshm-submenu-link">
                                                        <?php echo esc_html($submenu_title); ?>
                                                    </a>
                                                </li>
                                            <?php
                                                endif;
                                            endforeach;
                                            ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php
                                    endif;
                                endif;
                            endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <ul class="kshm-menu-list">
                            <?php if ($show_home_link == '1'): ?>
                                <li class="kshm-menu-item<?php echo ($this->is_current_page(home_url('/'))) ? ' kshm-current-page' : ''; ?>">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" class="kshm-menu-link">ホーム</a>
                                </li>
                            <?php else: ?>
                                <li class="kshm-menu-item">
                                    <span style="padding: 15px 0; display: block; color: #999;">メニュー項目が設定されていません</span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</div>
