$(document).ready(function() {
	anchor.init()
});

anchor = {
	init : function() {
	$("a.anchorLink").click(function () {
		elementClick = $(this).attr("href")
		destination = $(elementClick).offset().top;
		$("html:not(:animated),body:not(:animated)").animate({ scrollTop: destination}, 1100 );
			return false;
		})
	}
}
