$(function() {
	$.nette.init();
});

$("a.ajax").on("click", function (event) {
    event.preventDefault();
    $.get(this.href);
});

/* AJAXové odeslání formulářů */
$("form.ajax").on("submit", function () {
    $(this).ajaxSubmit();
    return false;
});

$("form.ajax :submit").on("click", function () {
    $(this).ajaxSubmit();
    return false;
});