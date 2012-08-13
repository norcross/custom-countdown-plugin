jQuery(document).ready(function($) {

//********************************************************
// jquery datepicker(s)
//********************************************************


	$( 'input#ccd_launch' ).datepicker({
		dateFormat:		'mm/dd/yy',
		defaultDate:	null,
		changeMonth:	true,
		changeYear:		true,
		onClose: function() {
			$('input#ccd_launch').trigger('change');
		}

	}); // end datepicker for start date

// **************************************************************
//  expand a text field for length of stuff in it
// **************************************************************

	var expand_calc	= 8; // pixels to expand by
	var start_width	= 5; // fallback


	$('input#ccd_banner').keyup(function () {
		var len = $(this).val().length;
		if (len > start_width) {
			// increase width
			$(this).width(len * expand_calc);
		} else {
			// restore minimal width;
			$(this).width(50);
		}
	});


//********************************************************
// You're still here? It's over. Go home.
//********************************************************


});	// end schema form init
