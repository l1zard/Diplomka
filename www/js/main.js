$(function() {
	$.nette.init();
});

$("a.ajax").on("click", function(event) {
	event.preventDefault();
	$.get(this.href);
});

/* AJAXové odeslání formulářů */
$("form.ajax").on("submit", function() {
	$(this).ajaxSubmit();
	return false;
});

$("form.ajax :submit").on("click", function() {
	$(this).ajaxSubmit();
	return false;
});

$(function() {
	$('#checkBoxShowMatch').change(function() {
		if($(this).is(':checked')) {
			if(!$('#snippet--showMatchInfoSnippet').children().hasClass('alert-success')) {
				$(this).prop('checked', false);
				$('#showMatch').modal('show');
			}
		}
		else {

		}
	});

	$('#deniedShowMatch').click(function() {
		$('#checkBoxShowMatch').trigger('click');

	});

	$('.datepicker').datetimepicker({
		locale: 'cs'
	});

	function readURL(input) {
		if(input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function(e) {
				$('#image').parent().prev().children().next().val(input.files[0].name);
				$('#image').slideDown('slow');
				$('#image').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#imageInput").change(function() {
		readURL(this);
	});

})