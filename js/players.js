// Team selection
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

$(".table-item").click(function () {
    $(this).parent().addClass("table-item-container-target");
});

let inputStrings = new Array();
let currentInput = "";

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
        currentInput = inputString;
    }
    event.preventDefault();
});

$(".table-item").blur(function () {
    $(this).removeClass("table-item-target");
    $(this).parent().removeClass("table-item-container-target");
    $(this).attr("readonly", true);
    if ($(this).val() !== currentInput) {
        const playerName = $(this)
            .parent()
            .siblings()
            .first()
            .children()
            .first()
            .val();
        if (!inputStrings.includes(playerName)) {
            inputStrings.push(playerName);
        }
    }
    console.log(inputStrings);
});
