if (!Seasons) {
    var Seasons = {};
}

(function ($) {
	Seasons.webNav = function () {
		$("a[href='https://libraryguides.berea.edu/archives']").attr("target", "_blank");
	}
})(jQuery);
(function ($) {

    Seasons.mobileSelectNav = function () {
        // Create the dropdown base
        $("<select class=\"mobile\" />").appendTo("nav.top");

        // Create default option "Go to..."
        $("<option />", {
           "selected": "selected",
           "value"   : "",
           "text"    : "Go to..."
        }).appendTo("nav select");

        // Populate dropdown with menu items
        $("nav.top a").each(function() {
            var el = $(this);
            if (el.parents('ul ul').length) {
                var parentCount = el.parents("ul").length;
                var dashes = new Array(parentCount).join('- ');
                $("<option />", {
                    "value": el.attr("href"),
                    "text":  dashes + el.text()
                }).appendTo("nav select");
            } else {
		            if (el.attr("href") == "https://libraryguides.berea.edu/archives"){
	                 $("<option />", {
                   	 "value": el.attr("href"),
                     "text": el.text(),
			               "target":"_blank"
                	  }).appendTo("nav.top select");
		            } else {
                    $("<option />", {
                    "value": el.attr("href"),
                    "text": el.text()
                    }).appendTo("nav.top select");
		            }
            }

            $("nav.top select").change(function() {
                window.location = $(this).find("option:selected").val();
            });
        });
    }

})(jQuery);
