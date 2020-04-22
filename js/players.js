$(".team-selection-submit").hover(
    function () {
        const teamColor = $(this).attr("data-team-color");

        $(this).css("background-color", teamColor);
    },
    function () {
        $(this).css("background-color", "transparent");
    }
);

$(".select-button").click(function () {
    $(".team-selection").toggleClass("team-selection-hidden");
    $(".select-button").toggleClass("select-button-active");
});

$(".player-team-button").click(function () {
    $(".player-team-selection").toggleClass("player-team-selection-hidden");
    $(".player-team-button").toggleClass("player-team-button-active");
});

$(".player-team-radio-check").click(function () {
    const checkBox = $(this).siblings(".player-team-selection-radio");
    checkBox.prop("checked", !checkBox.prop("checked"));
    $(".player-team-button").removeClass("player-team-button-active");
    $(this)
        .parents(".player-team-selection")
        .addClass("player-team-selection-hidden");
    $(".player-team-value span").text(
        checkBox.siblings(".player-team-selection-label").text()
    );
});

$(".table-item").click(function () {
    $(this).parent().addClass("table-item-container-target");
});

$(".table-item").dblclick(function (event) {
    if ($(this).hasClass("table-item-target")) {
        $(this).removeClass("table-item-target");
        $(this).parent().removeClass("table-item-container-target");
        $(this).attr("readonly", true);
    } else {
        $(this).addClass("table-item-target");
        $(this).parent().addClass("table-item-container-target");
        $(this).attr("readonly", false);
        $(this).focus();
        const inputString = $(this).val();
        $(this).val("");
        $(this).val(inputString);
    }
    event.preventDefault();
});

$(".table-item").blur(function () {
    $(this).removeClass("table-item-target");
    $(this).parent().removeClass("table-item-container-target");
    $(this).attr("readonly", true);
});

$(".player-create").click(function () {
    $(".overlay").removeClass("overlay-hidden");
    $(".create-player").removeClass("create-player-hidden");
});

$(".cancel-creation span").click(function () {
    $(".create-player-input input").val("");
    $(".overlay").addClass("overlay-hidden");
    $(".create-player").addClass("create-player-hidden");
});

$(function () {
    setTimeout(() => {
        $(".create-player-error-box").addClass("player-error-hidden");
    }, 5000);
});
