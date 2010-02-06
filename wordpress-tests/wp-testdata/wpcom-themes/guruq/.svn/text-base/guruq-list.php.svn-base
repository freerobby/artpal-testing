<?php
global $wpdb, $wp_locale;

if ( isset( $_GET['action'] ) && isset( $_GET['guruq'] ) && 'delete' == $_GET['action'] ) {
	delete_option( $_GET['guruq'] );
	wp_redirect( add_query_arg( 'page', GURUQ_SLUG, 'admin.php' ) );
}

$title = __( GURUQ_CAT );
$parent_file = 'edit.php';
?>
<div class="wrap">
<h2><?php echo esc_html( $title ); ?></h2>

<?php 
$total = guruq_count_queue();
$pagenum = 1;
if ( isset( $_GET['pagenum'] ) ) {
	$pagenum = (int) $_GET['pagenum'];
}
$per_page = 10;
$num_pages = (int) ( $total / $per_page );
$post_args = array( 'limit' => $per_page, 'offset' => 0 );
if ( $pagenum > 1 ) {
	$post_args['offset'] = ( ( $pagenum - 1 ) * $per_page );
}

$posts = guruq_get_queue( $post_args );
?>

<?php if ( !empty( $posts ) ) { ?>
<form method="post" action="">
	<input type="hidden" name="action" value="bulk_action"/>

<div class="tablenav">
		<div class="alignleft actions">
			<select name="bulk_action">
				<option value="-1"><?php _e( 'Bulk Actions' ); ?></option>
				<option value="delete"><?php _e( 'Delete' ); ?></option>
			</select>

			<input type="submit" value="<?php _e( 'Apply' ); ?>" id="doaction" class="button-secondary action" />
 		</div><!-- .alignleft .actions -->

<?php
$page_links = paginate_links( array(
	'base'      => add_query_arg( 'pagenum', '%#%' ),
	'format'    => '',
	'prev_text' => __('&laquo;'),
	'next_text' => __('&raquo;'),
	'total'     => $num_pages + 1,
	'current'   => $pagenum
));
?>

<?php if ( $page_links ) { ?>
<div class="tablenav-pages">
<?php 
$page_link_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s', 
	number_format_i18n( ( $pagenum - 1 ) * $per_page + 1 ),
	number_format_i18n( min( $pagenum * $per_page, $total ) ), 
	number_format_i18n( $total ), $page_links 
);
echo $page_link_text; 
?>
</div><!-- #tablenav-pages -->
<?php } ?>

<div class="clear"></div>
</div><!-- #tablenav -->

<div class="clear"></div>
<?php
$theaders = array( 'Title', 'Date', 'Author' );
$thead = '';
$thead .= '<th class="manage-column column-cb check-column"><input type="checkbox" /></th>';

foreach ( $theaders as $label ) {
	$thead .= "<th scope='row'>$label</th>";
}
?>
<table class="widefat post fixed" cellspacing="0">
	<thead>
	<tr><?php echo $thead ?></tr>
	</thead>

	<tfoot>
	<tr><?php echo $thead ?></tr>
	</tfoot>

	<tbody>
<?php
$out = '';
$date_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
foreach ( $posts as $post ) {
	$key = $post->option_name;
	$o = unserialize( $post->option_value );
	$edit_link = add_query_arg( 'guruq', $key, 'post-new.php' );
	$delete_link = add_query_arg( 'action', 'delete', $_SERVER['REQUEST_URI'] );
	$delete_link = add_query_arg( 'guruq', $key, $delete_link );
	$out .= "<tr>";
	$out .= '<th class="check-column"><input type="checkbox" class="bulk" name="bulk[]" value="' . $key . '" /></th>';
	$out .= "<td>$o->post_title<br>
	<a href='$edit_link'>Edit</a> | 
	<a href='$delete_link'>Delete</a>
	</td>";
	$out .= "<td>" . date( $date_format, strtotime( $o->post_date ) ) . "</td>";
	$email = '';
	if ( !empty( $o->author_email ) ) {
		$email = " ($o->author_email)";
	}
	$out .= "<td>$o->author_name $email</td>";
	$out .= "</tr>";
}
echo $out;
?>
	</tbody>
</table>
<div class="tablenav">
<?php
if ( $page_links )
	echo "<div class='tablenav-pages'>$page_link_text</div>";
?>


<?php } else { ?>
<div class="clear"></div>
<p><?php _e('No posts found'); ?></p>
<?php } ?>

<div id="ajax-response"></div>
<br class="clear" />
</div>
</form>
