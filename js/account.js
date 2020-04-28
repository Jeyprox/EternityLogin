$(".change-input input")
    .focus(function () {
        $(this).parents(".settings-item").addClass("settings-item-focus");
        $(".settings-item").removeClass("settings-item-hover");
    })
    .blur(function () {
        $(this).parents(".settings-item").removeClass("settings-item-focus");
    });

$(".settings-item").hover(
    function () {
        if (!$(this).hasClass("settings-item-focus")) {
            $(this).addClass("settings-item-hover");
        }
    },
    function () {
        $(this).removeClass("settings-item-hover");
    }
);
