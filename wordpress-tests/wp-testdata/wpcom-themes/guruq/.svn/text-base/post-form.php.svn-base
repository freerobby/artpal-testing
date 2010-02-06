<div id="postbox">
<h2>No Luck?</h2>
	<form id="new_post" name="new_post" method="post" action="<?php echo site_url(); ?>/">
		<input type="hidden" name="action" id="action" value="post" />
		<?php wp_nonce_field( 'new-post' ); ?>
		<div class="inputarea">
			<div id="guruq-ask">
				<div id="ask-message">Your question has been submitted:</div>
				<label class="hidden" style="display:none;">Question</label> 
					<input class="required1" type="text" name="question" id="question" value="<?php echo Q_DEFAULT; ?>" tabindex="3" onfocus="this.value=(this.value=='<?php echo Q_DEFAULT; ?>') ? '' : this.value;" onblur="this.value=(this.value=='') ? '<?php echo Q_DEFAULT; ?>' : this.value;" />
				<label class="hidden" style="display:none;">Details</label> 
					<textarea class="required1" name="details" id="details" tabindex="4" rows="3" cols="20" onfocus="this.value=(this.value=='<?php echo D_DEFAULT; ?>') ? '' : this.value;" onblur="this.value=(this.value=='') ? '<?php echo D_DEFAULT; ?>' : this.value;"><?php echo D_DEFAULT; ?></textarea>
				<input class="button" name="ask-submit" id="ask-submit" type="submit" tabindex="5" value="Ask" />
			</div>
			<div id="guruq-email">
				<p>Would you like to be notified when the Guru answers your question?</p>
				<label>Name</label> <input class="required2" type="text" name="notify-name" id="notify-name" value="" tabindex="6" />
				<label>E-mail</label> <input class="required2" type="text" name="notify-email" id="notify-email" value="" tabindex="7" />
				<label>Website</label> <input type="text" name="notify-website" id="notify-website" value="" tabindex="8" />
				<input type="hidden" name="guruq_key" id="guruq_key" value="" /> <br />
				<input id="email-submit" name="email-submit" type="submit" value="Notify me" tabindex="9" />
			</div>
		</div>
		<div class="clear"></div>
	</form>
</div> <!-- // postbox -->
