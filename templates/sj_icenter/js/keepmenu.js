jQuery(window).on("load", function() {
	if(jQuery("#yt_header")){
		offset_top = jQuery(" #yt_header").offset().top
		jQuery(window).scroll(function(){
			processScroll("#yt_header", "top-fixed", offset_top);
		});
	}
});

function processScroll(element, eclass, offset_top, column, offset_end) {
	var scrollTop = jQuery(window).scrollTop();
	if(jQuery(element).height()< jQuery(window).height()){
		if (scrollTop >= offset_top) {
			jQuery(element).addClass(eclass);
		} else if (scrollTop <= offset_top) {
			jQuery(element).removeClass(eclass);
		}
		
	}
}

