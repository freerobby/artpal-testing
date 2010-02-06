<?php
/**
 * This file contains some variable and function definitions take from
 * the wptouch plugin, so that it can work as a standalone theme on WordPress.com
 */

global $wptouch_defaults;
$wptouch_defaults = array(
	'header-title' => get_bloginfo('name'),
	'main_title' => 'Default.png',
	'enable-post-excerpts' => true,
	'enable-page-coms' => false,
	'enable-cats-button' => true,
	'enable-tags-button' => true,
	'enable-login-button' => false,
	'enable-ajax-comments' => true,
	'enable-gravatars' => true,
	'enable-main-home' => true,
	'enable-main-rss' => true,
	'enable-main-name' => true,
	'enable-main-tags' => true,
	'enable-main-categories' => true,
	'enable-main-email' => true,
	'header-background-color' => '000000',
	'header-border-color' => '333333',
	'header-text-color' => 'eeeeee',
	'link-color' => '006bb3',
	'style-text-size' => '',
	'style-text-justify' => 'full-justified',
	'style-background' => 'classic-wptouch-bg',
	'enable-regular-default' => false,
	'excluded-cat-ids' => '',
	'home-page' => 0,
	'enable-exclusive' => false,
	'sort-order' => 'name',
	'adsense-id' => '',
	'statistics' => '',
	'adsense-channel' => ''
);

function wptouch_ajax_url() {
	return get_bloginfo( 'url' ) . '/index.php?wptouch=ajax-comments-handler';
}

function wptouch_dir() {
	return dirname( __FILE__ );
}

function bnc_get_header_title() {
	$v = bnc_wp_touch_get_menu_pages();
	return $v['header-title'];
}

function bnc_get_header_background() {
	$v = bnc_wp_touch_get_menu_pages();
	return $v['header-background-color'];
}
  
function bnc_get_header_border_color() {
	$v = bnc_wp_touch_get_menu_pages();
	return $v['header-border-color'];
}

function bnc_get_header_color() {
	$v = bnc_wp_touch_get_menu_pages();
	return $v['header-text-color'];
}

function bnc_get_link_color() {
	$v = bnc_wp_touch_get_menu_pages();
	return $v['link-color'];
}

function bnc_wp_touch_get_menu_pages() {
	$v = get_option('bnc_iphone_pages');
	if (!$v) {
		$v = array();
	}
	
	if (!is_array($v)) {
		$v = unserialize($v);
	}
	
	bnc_validate_wptouch_settings( $v );

	return $v;
}

function bnc_validate_wptouch_settings( &$settings ) {
	global $wptouch_defaults;
	foreach ( $wptouch_defaults as $key => $value ) {
		if ( !isset( $settings[$key] ) ) {
			$settings[$key] = $value;
		}
	}
}

function bnc_get_title_image() {
	$ids = bnc_wp_touch_get_menu_pages();
	$title_image = $ids['main_title'];

	if ( file_exists( wptouch_dir() . '/images/icon-pool/' . $title_image ) ) {
		$image = get_stylesheet_directory_uri() . '/images/icon-pool/' . $title_image;
	}

	return $image;
}

function bnc_wptouch_is_exclusive() {
	$settings = bnc_wptouch_get_settings();
	return $settings['enable-exclusive'];
}

function bnc_wptouch_get_settings() {
	return bnc_wp_touch_get_menu_pages();
}


function bnc_get_selected_home_page() {
   $v = bnc_wp_touch_get_menu_pages();
   return $v['home-page'];
}

function wptouch_get_stats() {
	$options = bnc_wp_touch_get_menu_pages();
	if (isset($options['statistics'])) {
		echo stripslashes($options['statistics']);
	}
}
  
function wptouch_excluded_cats() {
	$settings = bnc_wptouch_get_settings();
	return stripslashes($settings['excluded-cat-ids']);
}

function bnc_excerpt_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-post-excerpts'];
}	

function bnc_is_page_coms_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-page-coms'];
}		

function bnc_is_cats_button_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-cats-button'];
}	

function bnc_is_tags_button_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-tags-button'];
}	

function bnc_is_login_button_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-login-button'];
}		
	
function bnc_is_gravatars_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-gravatars'];
}	

function bnc_is_ajax_coms_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-ajax-comments'];
}	

function bnc_show_author() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-main-name'];
}

function bnc_show_tags() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-main-tags'];
}

function bnc_show_categories() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-main-categories'];
}

function bnc_is_home_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-main-home'];
}	

function bnc_is_rss_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-main-rss'];
}	

function bnc_is_email_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-main-email'];
}

function bnc_wp_touch_get_pages() {
	global $table_prefix;
	global $wpdb;
	
	$ids = bnc_wp_touch_get_menu_pages();
	$a = array();
	$keys = array();
	foreach ($ids as $k => $v) {
		if ($k == 'main_title' || $k == 'enable-post-excerpts' || $k == 'enable-page-coms' || 
			 $k == 'enable-cats-button'  || $k == 'enable-tags-button'  || $k == 'enable-login-button' || 
			 $k == 'enable-gravatars' || $k == 'enable-ajax-comments' || $k == 'enable-main-home' || 
			 $k == 'enable-main-rss' || $k == 'enable-main-email' || $k == 'enable-main-name' || 
			 $k == 'enable-main-tags' || $k == 'enable-main-categories' || 
			 $k == 'enable-prowl-comments-button' || $k == 'enable-prowl-users-button' || 
			 $k == 'enable-prowl-message-button') {
			} else {
				if (is_numeric($k)) {
					$keys[] = $k;
				}
			}
	}
	 
	$menu_order = array(); 
	$results = false;

	if ( count( $keys ) > 0 ) {
		$query = "select * from {$table_prefix}posts where ID in (" . implode(',', $keys) . ") and post_status = 'publish' order by post_title asc";
		$results = $wpdb->get_results( $query, ARRAY_A );
	}

	if ( $results ) {
		foreach ( $results as $row ) {
			$row['icon'] = $ids[$row['ID']];
			$a[$row['ID']] = $row;
			if (isset($menu_order[$row['menu_order']])) {
				$menu_order[$row['menu_order']*100 + $inc] = $row;
			} else {
				$menu_order[$row['menu_order']*100] = $row;
			}
			$inc = $inc + 1;
		}
	}

	if (isset($ids['sort-order']) && $ids['sort-order'] == 'page') {
		asort($menu_order);
		return $menu_order;
	} else {
		return $a;
	}
}
