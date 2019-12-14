const $navbar = $(".navbar")
	, $footer = $(".footer")
	, $side = $(".side");


interface JQuery {
	panel(): JQuery
}

$.fn.extend({
	panel() {
		const $panel = $(this)
			, $toggle = $panel.find(".panel-toggle")
			, $body = $panel.find(".panel-body");
		$toggle.on("click", function () {
			$body.slideToggle();
			$panel.hasClass("panel-fold") ?
				$panel.removeClass("panel-fold") :
				$panel.addClass("panel-fold")
		})
	}
});


$(".panel").panel();


