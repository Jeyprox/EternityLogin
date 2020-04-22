const inputPasswordIcon = $(".input-password-icon");

inputPasswordIcon.click(() => {
    inputPasswordIcon.toggleClass("fa-eye").toggleClass("fa-eye-slash");
    if ($(".password").is("input:password")) {
        $(".password").prop("type", "text");
    } else {
        $(".password").prop("type", "password");
    }
});
