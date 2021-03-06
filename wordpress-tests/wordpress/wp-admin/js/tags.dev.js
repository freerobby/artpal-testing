jQuery(document).ready(function($) {

	$('.delete-tag').live('click', function(e){
		var t = $(this), tr = t.parents('tr'), r = true, data;
		if ( 'undefined' != showNotice )
			r = showNotice.warn();
		if ( r ) {
			data = t.attr('href').replace(/[^?]*\?/, '').replace(/action=delete/, 'action=delete-tag');
			$.post(ajaxurl, data, function(r){
				if ( '1' == r ) {
					$('#ajax-response').empty();
					tr.fadeOut('normal', function(){ tr.remove(); });
				} else if ( '-1' == r ) {
					$('#ajax-response').empty().append('<div class="error"><p>' + tagsl10n.noPerm + '</p></div>');
					tr.children().css('backgroundColor', '');
				} else {
					$('#ajax-response').empty().append('<div class="error"><p>' + tagsl10n.broken + '</p></div>');
					tr.children().css('backgroundColor', '');
				}
			});
			tr.children().css('backgroundColor', '#f33');
		}
		return false;
	});

	$('#submit').click(function(){
		var form = $(this).parents('form');

		if ( !validateForm( form ) )
			return false;

		$.post(ajaxurl, $('#addtag').serialize(), function(r){
			if ( r.indexOf('<div class="error"') === 0 ) {
				$('#ajax-response').append(r);
			} else {
				$('#ajax-response').empty();
				var parent = form.find('select#parent').val();
				if ( parent > 0 && $('#tag-' + parent ).length > 0 ) // If the parent exists on this page, insert it below. Else insert it at the top of the list.
					$('#the-list #tag-' + parent).after(r);
				else
					$('#the-list').prepend(r);
				$('input[type="text"]:visible, textarea:visible', form).val('');
			}
		});

		return false;
	});

});