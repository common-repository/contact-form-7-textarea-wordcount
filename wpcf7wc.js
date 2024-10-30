// wpcf7wc v1.1

function wpcf7wc_count( field, counter ) {
	var htm = field.val();
	if ( htm == '' ) {
		counter.val('0');
		return;
	} // else
	var words = htm.split(' ');
	var num = words.length;
	var mx = field.data('maxwc');
	if(num > mx) {
		words = words.slice(0,mx);
		htm = '';
		for(w in words) {
			htm += words[w] + ' ';
		}
		field.val(htm);
		counter.val(mx);
	} else {
    counter.val(htm.split(' ').length);
  }
}

jQuery(function($) {
	$('form.wpcf7-form textarea[data-maxwc]').each(function() {
		var nam = $(this).attr('name');
		$(this).addClass('found').bind('input',function() {
			wpcf7wc_count($(this),$('#wcount_'+nam));
		}).trigger('input');
	});
});