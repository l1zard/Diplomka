/**
 * Created by Lizardor on 30. 3. 2016.
 */


$(function() {
	$.nette.init();
	$('.logged').hover(function() {
		$(this).css('background', '#454F61');
		$('.dropdown').slideToggle(400);
		setTimeout(function() {
			$('.logged').css('background', 'inherit')
		}, 400);
	});

	$('#frm-betTicket-vklad').on('input', function() {
		var money = parseFloat($(this).val());
		var winMoney = parseFloat(money * $('#celkovyKurz').html());
		$('#vsazena-castka').html(money.toFixed(2) + ' Kč');
		$('#potenc-vyhra').html(winMoney.toFixed(2) + ' Kč');
	});
	$.nette.ext("spinner", {
		before: function(paylod) {
			$('body').append('<div class="spinner"></div>');
			$('#snippet--ticketSnippet ul li').show(1000);
		}
		,
		complete: function(msg, event, request){
			$('.spinner').remove();
		}
	});

});

$(document).ready(function($) {
	$('.tiket-row').click(function() {
		window.open('http://' + window.location.host + $(this).data('href'), 'window name', 'width=550, height=640');
		return false;
	});

});

$(document).on('input', '#frm-betTicket-vklad', function() {
	var money = parseFloat($(this).val());
	var winMoney = parseFloat(money * $('#celkovyKurz').html());
	$('#vsazena-castka').html(money.toFixed(2) + ' Kč');
	$('#potenc-vyhra').html(winMoney.toFixed(2) + ' Kč');
})