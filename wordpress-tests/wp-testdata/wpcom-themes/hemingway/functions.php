<?php

load_theme_textdomain( 'hemingway' );

$themecolors = array(
	'bg' => '000000',
	'text' => 'bfbfbf',
	'link' => 'ffffff',
	'border' => '000000'
	);

// this varies but the single page content width seems to be 607px max
$content_width = 600;

register_sidebar( array(
	'name'          => __('Bottom 1', 'hemingway'),
	'id'            => 'bottom-1',
	'before_widget' => '<span class="widget">',
	'after_widget'  => '</span>',
	'before_title'  => '<h2>',
	'after_title'   => '</h2>' ) );

register_sidebar( array(
	'name'          => __('Bottom 2', 'hemingway'),
	'id'            => 'bottom-2',
	'before_widget' => '<span class="widget">',
	'after_widget'  => '</span>',
	'before_title'  => '<h2>',
	'after_title'   => '</h2>' ) );

register_sidebar( array(
	'name'          => __('Bottom 3', 'hemingway'),
	'id'            => 'bottom-3',
	'before_widget' => '<span class="widget">',
	'after_widget'  => '</span>',
	'before_title'  => '<h2>',
	'after_title'   => '</h2>' ) );

class Hemingway
	{
		
		var $raw_blocks;
		var $available_blocks;
		var $style;
		var $version;
		
		function add_available_block($block_name, $block_ref)
			{

				$blocks = $this->available_blocks;
				
				if (!$blocks[$block_ref]){
					$blocks[$block_ref] = $block_name;
					update_option('hem_available_blocks', $blocks);
					wp_cache_flush();
				}
				
			}
		
		function get_available_blocks()
			// This function returns an array of available blocks
			// in the format of $arr[block_ref] = block_name
			{				
				$this->available_blocks = get_option('hem_available_blocks');
				return $this->available_blocks;
			}
		
		function get_block_contents($block_place)
			// Returns an array of block_refs in specififed block
			{
				if (!$this->raw_blocks){
					$this->raw_blocks = get_option('hem_blocks');
				}
				return $this->raw_blocks[$block_place];
			}
		
		function add_block_to_place($block_place, $block_ref)
			{
				$block_contents = $this->get_block_contents($block_place);
				if (in_array($block_ref, $block_contents))
					return true;
				
				$block_contents[] = $block_ref;	
				$this->raw_blocks[$block_place] = $block_contents;
				update_option('hem_blocks', $this->raw_blocks);
				wp_cache_flush(); // I was having caching issues
				return true;
			}
			
		function remove_block_in_place($block_place, $block_ref)
			{
				$block_contents = $this->get_block_contents($block_place);
				if (!in_array($block_ref, $block_contents))
					return true;
				$key = array_search($block_ref, $block_contents);
				unset($block_contents[$key]);
				$this->raw_blocks[$block_place] = $block_contents;
				update_option('hem_blocks', $this->raw_blocks);
				wp_cache_flush(); // I was having caching issues
				return true;
			}
			
			// Templating functions
			
			function get_block_output($block_place)
				{
					$blocks = $this->get_block_contents($block_place);
					foreach($blocks as $key => $block ){
						include (TEMPLATEPATH . '/blocks/' . $block . '.php');
					}
				}
				
			function get_style(){
				$this->style = get_option('hem_style');
			}
	}
	
$hemingway = new Hemingway();
$hemingway->get_available_blocks();
$hemingway->get_style();

$hemingway->version = "0.13";
// Options

$default_blocks = Array(
	'recent_entries' => __('Recent Entries', 'hemingway'),
	'about_page' => __('About Page', 'hemingway'),
	'category_listing' => __('Category Listing', 'hemingway'),
	'blogroll' => __('Blogroll', 'hemingway'),
	'pages' => __('Pages', 'hemingway'),
	'monthly_archives' => __('Monthly Archives', 'hemingway')
);

$default_block_locations = Array(
	'block_1' => Array('about_page'),
	'block_2' => Array('recent_entries'),
	'block_3' => Array('category_listing'),
	'block_4' => Array(),
	'block_5' => Array(),
	'block_6' => Array()
);

if (!get_option('hem_version') || get_option('hem_version') < $hemingway->version){
	// Hemingway isn't installed, so we'll need to add options
	if (!get_option('hem_version') )
		add_option('hem_version', $hemingway->version, __('Hemingway Version installed', 'hemingway'));
	else
		update_option('hem_version', $hemingway->version);
		
	if (!get_option('hem_available_blocks') ) 
		add_option('hem_available_blocks', $default_blocks, __('A list of available blocks for Hemingway', 'hemingway'));
	
	if (!get_option('hem_blocks') ) 
		add_option('hem_blocks', $default_block_locations, __('An array of blocks and their contents', 'hemingway'));
	
	if (!get_option('hem_style') )
		add_option('hem_style', '', __('Location of custom style sheet', 'hemingway'));
}
// Ajax Stuff

if ($_GET['hem_action'] == 'add_block'){
	
	$block_ref = $_GET['block_ref'];
	$block_place = $_GET['block_place'];
	
	$block_name = __($hemingway->available_blocks[$block_ref], 'hemingway');
	
	$hemingway->add_block_to_place($block_place, $block_ref);

	ob_end_clean(); // Kill preceding output
	$output = '<ul>';
	foreach($hemingway->get_block_contents($block_place) as $key => $block_ref){
			$block_name = __($hemingway->available_blocks[$block_ref], 'hemingway');
			$output .= '<li>' . $block_name . ' (<a href="#" onclick="remove_block(\'' . $block_place . '\', \'' . $block_ref . '\');">' . __('remove', 'hemingway') . '</a>)</li>';
	}
	$output .= '</ul>';
	echo $output;
	exit(); // Kill any more output
}

if ($_GET['hem_action'] == 'remove_block'){
	
	$block_ref = $_GET['block_ref'];
	$block_place = $_GET['block_place'];
	
	$hemingway->remove_block_in_place($block_place, $block_ref);

	ob_end_clean(); // Kill preceding output
	$output = '<ul>';
	foreach($hemingway->get_block_contents($block_place) as $key => $block_ref){
			$block_name = __($hemingway->available_blocks[$block_ref], 'hemingway');
			$output .= '<li>' . $block_name . ' (<a href="#" onclick="remove_block(\'' . $block_place . '\', \'' . $block_ref . '\');">' . __('remove', 'hemingway') . '</a>)</li>';
	}
	$output .= '</ul>';
	echo $output;
	exit(); // Kill any more output
}

if ($_POST['custom_styles']){
	update_option('hem_style', $_POST['custom_styles']);
	wp_cache_flush();
	$message  = __('Styles updated!', 'hemingway');
}

if ($_POST['block_ref']){
	$hemingway->add_available_block($_POST['display_name'], $_POST['block_ref']);
	$hemingway->get_available_blocks();
	$message = __('Block added!', 'hemingway');
}

// Stuff

add_action ('admin_menu', 'hemingway_menu');

$hem_loc = '../themes/' . basename(dirname($file)); 

function hemingway_scripts() {
	$dir = get_bloginfo('template_directory');
	wp_enqueue_script('prototype');
	wp_enqueue_script('dragdrop', $dir . '/admin/js/dragdrop.js', false, 1);
	wp_enqueue_script('effects', $dir . '/admin/js/effects.js', false, 1);
}

function hemingway_menu() {
	$page = add_submenu_page('themes.php', __('Hemingway Options', 'hemingway'), __('Hemingway Options', 'hemingway'), 5, $hem_loc . 'functions.php', 'menu');
	add_action('load-' . $page, 'hemingway_scripts');
}

function menu() {
global $hem_loc, $hemingway, $message;
?>
<!--
Okay, so I don't honestly know how legit this is, but I want a more intuitive interface
so I'm going to import scriptaculous. There's a good chance this is going to mess stuff up
for some people :)
-->
<script type="text/javascript">
	function remove_block(block_place, block_ref){
		url = 'themes.php?page=functions.php&hem_action=remove_block&block_place=' + block_place + '&block_ref=' + block_ref;
		new Ajax.Updater(block_place, url, 
				{
					evalScripts:true, asynchronous:true
				}
		)
	}
</script>
<style>
	.block{
		width:200px;
		height:200px;
		border:1px solid #CCC;
		float:left;
		margin:20px 1em 20px 0;
		padding:10px;
		display:inline;
	}
	.block ul{
		padding:0;
		margin:0;
	}
	.block ul li{
		margin:0 0 5px 0;
		list-style-type:none;
	}
	.block-active{
		border:1px solid #333;
		background:#F2F8FF;
	}
	
	#addables li{
		list-style-type:none;
		margin:1em 1em 1em 0;
		background:#EAEAEA;
		border:1px solid #DDD;
		padding:3px;
		width:215px;
		float:left;
		cursor:move;
	}
	ul#addables{
		margin:0;
		padding:0;
		width:720px;
		position:relative;
	}
</style>




<?php if($message) : ?>
<div id="message" class="updated fade"><p><?php echo $message; ?></p></div>
<?php endif; ?>

<div class="wrap" style="position:relative;">
<h2><?php _e('Hemingway Options', 'hemingway'); ?></h2>

<h3><?php _e('Color Options', 'hemingway'); ?></h3>
<p><?php _e('Choose a primary color for your site:', 'hemingway'); ?></p>
<form name="dofollow" action="" method="post">
  <input type="hidden" name="page_options" value="'dofollow_timeout'" />

  <p><label><input name="custom_styles" type="radio" value="none" <?php if ($hemingway->style == 'none') echo 'checked="checked"'; ?> /> 
  <?php _e('Black', 'hemingway'); ?></label></p>
  <p><label><input name="custom_styles" type="radio" value="white.css" <?php if ($hemingway->style == 'white.css') echo 'checked="checked"'; ?> /> <?php _e('White', 'hemingway'); ?></label></p>

	<input type="submit" value="<?php _e('Update Color &raquo;', 'hemingway'); ?>" />
</form>

<h3><?php _e("Hemingway's Bottombar&trade;", 'hemingway'); ?></h3>
<p><?php _e('Drag and drop the different blocks into their place below. After you drag the block to the area, it will update with the new contents automatically.', 'hemingway'); ?></p>
<p><?php _e('*Note: Widgets take preference over these blocks.', 'hemingway'); ?></p>
<ul id="addables">
	<?php foreach($hemingway->available_blocks as $ref => $name) : ?>
	<li id="<?php echo $ref; ?>" class="blocks"><?php _e($name, 'hemingway'); ?></li>
	<script type="text/javascript">new Draggable('<?php echo $ref; ?>', {revert:true})</script>
	<?php endforeach; ?>
</ul>

<div class="clear"></div>

<div class="block" id="block_1">
	<ul>
		<?php 
		foreach($hemingway->get_block_contents('block_1') as $key => $block_ref) :
			$block_name = __($hemingway->available_blocks[$block_ref], 'hemingway');
		?>
			<li><?php echo $block_name; ?> (<a href="#" onclick="remove_block('block_1', '<?php echo $block_ref; ?>');"><?php _e('remove', 'hemingway'); ?></a>)</li>
		<?php endforeach; ?>
	</ul>
</div>
<script type="text/javascript">
Droppables.add(
	'block_1', {
		accept:'blocks', 
		onDrop:function(element){
			new Ajax.Updater('block_1', 'themes.php?page=functions.php&hem_action=add_block&block_place=block_1&block_ref=' + element.id, 
				{
					evalScripts:true, asynchronous:true
				}
			)
		}, 
		hoverclass:'block-active'
	}
)
</script>

<div class="block" id="block_2">
	<ul>
		<?php 
		foreach($hemingway->get_block_contents('block_2') as $key => $block_ref) :
			$block_name = __($hemingway->available_blocks[$block_ref], 'hemingway');
		?>
			<li><?php echo $block_name; ?> (<a href="#" onclick="remove_block('block_2', '<?php echo $block_ref; ?>');"><?php _e('remove', 'hemingway'); ?></a>)</li>
		<?php endforeach; ?>
	</ul>
</div>
<script type="text/javascript">
Droppables.add(
	'block_2', {
		accept:'blocks', 
		onDrop:function(element){
			new Ajax.Updater('block_2', 'themes.php?page=functions.php&hem_action=add_block&block_place=block_2&block_ref=' + element.id, 
				{
					evalScripts:true, asynchronous:true
				}
			)
		}, 
		hoverclass:'block-active'
	}
)
</script>

<div class="block" id="block_3">
	<ul>
		<?php 
		foreach($hemingway->get_block_contents('block_3') as $key => $block_ref) :
			$block_name = __($hemingway->available_blocks[$block_ref], 'hemingway');
		?>
			<li><?php echo $block_name; ?> (<a href="#" onclick="remove_block('block_3', '<?php echo $block_ref; ?>');"><?php _e('remove', 'hemingway'); ?></a>)</li>
		<?php endforeach; ?>
	</ul>
</div>
<script type="text/javascript">
Droppables.add(
	'block_3', {
		accept:'blocks', 
		onDrop:function(element){
			new Ajax.Updater('block_3', 'themes.php?page=functions.php&hem_action=add_block&block_place=block_3&block_ref=' + element.id, 
				{
					evalScripts:true, asynchronous:true
				}
			)
		}, 
		hoverclass:'block-active'
	}
)
</script>

<!-- Maybe later...


<div class="clear"></div>

<div class="block" id="block_4">
	Block 4
	<ul>
		<?php 
		foreach($hemingway->get_block_contents('block_4') as $key => $block_ref) :
			$block_name = $hemingway->available_blocks[$block_ref];
		?>
			<li><?php echo $block_name; ?> (<a href="#" onclick="remove_block('block_4', '<?php echo $block_ref; ?>');">remove</a>)</li>
		<?php endforeach; ?>
	</ul>
</div>
<script type="text/javascript">
Droppables.add(
	'block_4', {
		accept:'blocks', 
		onDrop:function(element){
			new Ajax.Updater('block_4', 'themes.php?page=functions.php&hem_action=add_block&block_place=block_4&block_ref=' + element.id, 
				{
					evalScripts:true, asynchronous:true
				}
			)
		}, 
		hoverclass:'block-active'
	}
)
</script>

<div class="block" id="block_5">
	Block 5
	<ul>
		<?php 
		foreach($hemingway->get_block_contents('block_5') as $key => $block_ref) :
			$block_name = $hemingway->available_blocks[$block_ref];
		?>
			<li><?php echo $block_name; ?> (<a href="#" onclick="remove_block('block_5', '<?php echo $block_ref; ?>');">remove</a>)</li>
		<?php endforeach; ?>
	</ul>
</div>
<script type="text/javascript">
Droppables.add(
	'block_5', {
		accept:'blocks', 
		onDrop:function(element){
			new Ajax.Updater('block_5', 'themes.php?page=functions.php&hem_action=add_block&block_place=block_5&block_ref=' + element.id, 
				{
					evalScripts:true, asynchronous:true
				}
			)
		}, 
		hoverclass:'block-active'
	}
)
</script>

<div class="block" id="block_6">
	Block 6
	<ul>
		<?php 
		foreach($hemingway->get_block_contents('block_6') as $key => $block_ref) :
			$block_name = $hemingway->available_blocks[$block_ref];
		?>
			<li><?php echo $block_name; ?> (<a href="#" onclick="remove_block('block_6', '<?php echo $block_ref; ?>');">remove</a>)</li>
		<?php endforeach; ?>
	</ul>
</div>
<script type="text/javascript">
Droppables.add(
	'block_6', {
		accept:'blocks', 
		onDrop:function(element){
			new Ajax.Updater('block_6', 'themes.php?page=functions.php&hem_action=add_block&block_place=block_6&block_ref=' + element.id, 
				{
					evalScripts:true, asynchronous:true
				}
			)
		}, 
		hoverclass:'block-active'
	}
)
</script>
-->

<div class="clear"></div>

	<?php
		$blocks_dir = @ dir(ABSPATH . '/wp-content/themes/' . get_template() . '/blocks');
	
		if ($blocks_dir) {
			while(($file = $blocks_dir->read()) !== false) {
					if (!preg_match('|^\.+$|', $file) && preg_match('|\.php$|', $file)) 
					$blocks_files[] = $file;
				}
			}
			if ($blocks_dir || $blocks_files) {
				foreach($blocks_files as $blocks_file) {
				$block_ref = preg_replace('/\.php/', '', $blocks_file);
				if (!array_key_exists($block_ref, $hemingway->available_blocks)){
				?>
				<h3><?php _e('You have uninstalled blocks!', 'hemingway');?></h3>
				<p><?php printf(_('Give the block <strong>%s</strong> a display name (such as "About Page")', 'hemingway'), $block_ref); ?>
				<form action="" name="dofollow" method="post">
					<input type="hidden" name="block_ref" value="<?php echo $block_ref; ?>" />
					<?php echo $block_ref; ?> : <input type="text" name="display_name" />
					<input type="submit" value="<?php _e('Save', 'hemingway'); ?>" />
				</form>
				<?
				}
			}
		} 
		?>




</div>

<?php
}
?>
