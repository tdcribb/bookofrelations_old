$(document).ready(function() {
	centerOverlay();
	isTermsChecked();
	addReportAbuseTitle();

	$('.policy-link').click(function() {
		$('.overlay').hide();
		$('#page-fade').show();
		$('#policy-overlay').fadeIn();
	});

	$('.faq-link').click(function() {
		$('.overlay').hide();
		$('#page-fade').show();
		$('#faq-overlay').fadeIn();
	});

	$('.member-link').click(function() {
		$('.overlay').hide();
		$('#page-fade').show();
		$('#member-overlay').fadeIn();
	});

	$('.report-abuse').click(function() {
		$('.overlay').hide();
		$('#page-fade').show();
		$('#report-abuse-overlay').fadeIn();
	});

	$('.x-close').click(function() {
		$('.overlay').fadeOut();
		$('#page-fade').fadeOut();
	});

	$('.agree-checkbox').click(function() {
		var c = this.checked ? '#f00' : '#09f';
		if (c == '#f00') {
			$('#cover-submit').css('display', 'none');
		} else {
			$('#cover-submit').css('display', 'block');
		}
	});

	$('#blog-search .product').each(function() {
		$('#blog-search .product .continue-reading-post').html('Visit Bookstore...');
		$('#blog-search .product .continue-reading-post').css('bottom', 23+'px');
	});

	
});

$(window).resize(function() {
	centerOverlay();
});

function addReportAbuseTitle() {
	var title = 'Report Post: '+$('.posttitle').html();
	$('#report-abuse-overlay input#report-title').val(title);
}

function centerOverlay() {
	var wH = $(window).height(),
		wW = $(window).width(),
		newTop = (wH - 400) / 2,
		newLeft = (wW - 800) /2;
	if($(window).height() > 420) {
		$('.overlay').css({'left':newLeft, 'top':newTop});
	}
}

function isTermsChecked() {
	var c = this.checked ? '#f00' : '#09f';
	if (c == '#f00') {
		$('#cover-submit').css('display', 'none');
	} else {
		$('#cover-submit').css('display', 'block');
	}
}