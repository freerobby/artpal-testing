<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">

	<div style="text-align: center;">
	
		<input type="text" value="<?php _e('Type and Press Enter to Search...', 'daydream'); ?>" onfocus="this.value=''; this.style.color='#000';" onblur="this.value='<?php _e('Type and Press Enter to Search...', 'daydream'); ?>'; this.style.color='#ccc';"  name="s" id="s" />
	
	</div>

</form>
