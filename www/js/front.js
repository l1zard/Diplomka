/**
 * Created by Lizardor on 30. 3. 2016.
 */


$(function(){
	$('.logged').hover(function(){
		$(this).css('background', '#454F61');
		$('.dropdown').slideToggle(400);
		setTimeout(function(){
			$('.logged').css('background', 'inherit')
		}, 400);
	});
	$.nette.init();
});