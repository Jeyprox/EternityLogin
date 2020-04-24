$(".change-input input")
    .focus(function () {
        $(this)
            .parents(".personal-info-item")
            .addClass("personal-info-item-focus");
        $(".personal-info-item").removeClass("personal-info-item-hover");
    })
    .blur(function () {
        $(this)
            .parents(".personal-info-item")
            .removeClass("personal-info-item-focus");
    });

$(".personal-info-item").hover(
    function () {
        if (!$(this).hasClass("personal-info-item-focus")) {
            $(this).addClass("personal-info-item-hover");
        }
    },
    function () {
        $(this).removeClass("personal-info-item-hover");
    }
);
