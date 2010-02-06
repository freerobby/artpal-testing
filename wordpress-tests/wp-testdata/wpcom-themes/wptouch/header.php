<?php 
include( dirname(__FILE__) . '/core/core-header.php' ); 
// End WPtouch Core Header
?>
<body class="<?php wptouch_core_body_background(); ?>">
<!-- New noscript check, we need js on now folks -->
<noscript>
<div id="noscript-wrap">
	<div id="noscript">
		<h2><?php _e("Notice", "wptouch"); ?></h2>
		<p><?php _e("JavaScript is currently turned off.", "wptouch"); ?></p>
	</div>
</div>
</noscript>

<!--#start The Login Overlay -->
	<div id="wptouch-login">
		<div id="wptouch-login-inner">
			<form name="loginform" id="loginform" action="<?php bloginfo('wpurl'); ?>/wp-login.php" method="post">
				<label><input type="text" name="log" id="log" onfocus="if (this.value == 'username') {this.value = ''}" value="username" /></label>
				<label><input type="password" name="pwd"  onfocus="if (this.value == 'password') {this.value = ''}" id="pwd" value="password" /></label>
				<input type="hidden" name="rememberme" value="forever" />
				<input type="hidden" id="logsub" name="submit" value="<?php _e('Login'); ?>" tabindex="9" />
				<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>"/>
			</form>
		</div>
	</div>
	
<div id="headerbar">
	<div id="headerbar-title">
		<!-- This fetches the admin selection logo icon for the header, which is also the bookmark icon -->
		<?php if ('HEADER_IMAGE' == get_header_image()) : ?>
		<img src="<?php echo apply_filters( 'wpcom_wptouch_header_image', bnc_get_title_image() ); ?>" alt="<?php $str = bnc_get_header_title(); echo stripslashes($str); ?>" />
		<?php endif; ?>
		<a href="<?php bloginfo('home'); ?>"><?php wptouch_core_body_sitetitle(); ?></a>
	</div>
	<div id="headerbar-menu">
		    <a href="#" onclick="bnc_jquery_menu_drop(); return false;"></a>
	</div>
</div>

<!-- #start The Search / Menu Drop-Down -->
	<div id="wptouch-menu" class="dropper"> 
 		<div id="wptouch-search-inner">
			<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
			<input type="text" value="<?php esc_attr_e( 'Search&hellip;' ); ?>" onfocus="if (this.value == '<?php echo js_escape( __('Search&hellip;') ); ?>') {this.value = ''}" name="s" id="s" /> 
				<input name="submit" type="hidden" tabindex="5" value="<?php esc_attr_e( 'Search' ); ?>"  />
			</form>
		</div>
        <div id="wptouch-menu-inner">
			<ul>
				<li><a href="<?php bloginfo('home'); ?>"><img class="pageicon" src="<?php echo apply_filters( 'wpcom_wptouch_home_image', bnc_get_title_image() ); ?>" alt="pageicon" /><?php _e('Home'); ?></a></li>
			<?php
				$num_pages = wp_count_posts('page');
				switch ( $num_pages->publish ) {
					case 0:
						break;
					case 1:
					case 2:
					case 3:
						$pages = get_posts('post_type=page');
						foreach ( $pages as $page )
							echo '<li><a href="' . get_permalink($page->ID) . '"><img class="pageicon" src="' . get_stylesheet_directory_uri() . '/images/icon-pool/Default.png" alt="pageicon" />' . get_the_title($page->ID) . '</a></li>';
						break;
					default:
						echo '<li><a href="' . get_bloginfo('home') . '?pages-list"><img class="pageicon" src="' . get_stylesheet_directory_uri() . '/images/icon-pool/Default.png" alt="pageicon" />' . __('Pages') . '</a></li>';
						break;
					}

				echo '<li><a href="' . get_bloginfo('home') . '?archives-list=1"><img class="pageicon" src="' . get_stylesheet_directory_uri() . '/images/icon-pool/Archives.png" alt="pageicon" />' . __('Archives') . '</a></li>';
			?>
			</ul>
        </div>
	</div>

<!-- #start the wptouch plugin use check -->
<?php wptouch_core_header_check_use(); ?>
