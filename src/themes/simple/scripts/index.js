var $navbar = $(".navbar"), $footer = $(".footer"), $side = $(".side");
$.fn.extend({
    panel: function () {
        var $panel = $(this), $toggle = $panel.find(".panel-toggle"), $body = $panel.find(".panel-body");
        $toggle.on("click", function () {
            $body.slideToggle();
            $panel.hasClass("panel-fold") ?
                $panel.removeClass("panel-fold") :
                $panel.addClass("panel-fold");
        });
    }
});
$(".panel").panel();
