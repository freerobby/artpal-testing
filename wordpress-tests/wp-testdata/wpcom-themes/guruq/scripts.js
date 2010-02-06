//<![CDATA[
var $j = jQuery.noConflict();
jQuery(document).ready(function(){
	$j(function() {
		$j("#accordion1").accordion({ 
			header: 'h3', 
			autoHeight: false, 
			collapsible: true, 
			active: false 
		});
		
		$j("#accordion2").accordion({ 
			header: 'h3', 
			autoHeight: false, 
			collapsible: true, 
			active: false 
		});
	});

var q_default = 'Ask your question';
var d_default = 'More details...';

	$j("#ask-submit").click(function() { 
		$j('form#new_post .error').remove();
		var hasError = false;

		$j('.required1').each(function() {
			if(jQuery.trim($j(this).val()) == '') {
				var labelText = $j(this).prev('label').text();
				$j(this).parent().append('<span class="error">You forgot to enter your '+labelText+'.</span>');
				hasError = true;
			} else if($j(this).hasClass('email')) {
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
				if(!emailReg.test(jQuery.trim($j(this).val()))) {
					var labelText = $j(this).prev('label').text();
					$j(this).parent().append('<span class="error">You entered an invalid '+labelText+'.</span>');
					hasError = true;
				}
			}
			if(jQuery.trim($j(this).val()) == q_default) {
				var labelText = $j(this).prev('label').text();
				$j(this).parent().append('<span class="error">You forgot to enter your '+labelText+'.</span>');
				hasError = true;
			}
		});

		if( !hasError ) {
			var dataString = $j("#new_post").serialize();
			//alert (dataString);return false;

			$j('#guruq-ask').fadeOut('fast');
			$j('#postbox h2').fadeOut('fast');

			$j.ajax({
				type: "POST",
				url: "?action=post",
				data: dataString,
				success: function( data, status ) {
					var guruq_key = data;
					$j("#guruq_key").val(data);

					$j('#guruq-email').fadeIn('fast');
				}
			  });

			return false;
		} else {
			return false;
		}
	});


	$j("#email-submit").click(function() { 
		$j('form#new_post .error').remove();
		var hasError = false;

		$j('.required2').each(function() {
			if(jQuery.trim($j(this).val()) == '') {
				var labelText = $j(this).prev('label').text();
				$j(this).parent().append('<span class="error">You forgot to enter your '+labelText+'.</span>');
				hasError = true;
			} else if($j(this).hasClass('email')) {
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
				if(!emailReg.test(jQuery.trim($j(this).val()))) {
					var labelText = $j(this).prev('label').text();
					$j(this).parent().append('<span class="error">You entered an invalid '+labelText+'.</span>');
					hasError = true;
				}
			}
		});

		if( !hasError ) {
			var dataString = $j("#new_post").serialize();
			//alert (dataString);return false;

			$j.ajax({
				type: "POST",
				url: "?action=notify",
				data: dataString,
				success: function() {
					$j('#guruq-email').fadeOut('fast');					

					$j('#ask-message').fadeIn('fast');
					$j('#ask-message').append( 'Your question has been submitted:' + $j('#question').val() );

					$j('#guruq-ask').fadeIn('fast');
					$j('#question').val(q_default);
					$j('#details').val(d_default);
					$j('#postbox h2').fadeIn('fast');

					$j('#ask-message').fadeTo('slow', 1).animate({opacity: 1.0}, 3000).fadeTo('slow', 0);  
					$j('#ask-message').fadeOut('fast');
				}
			  });

			return false;
		} else {
			return false;
		}
	});

});
//]]>
