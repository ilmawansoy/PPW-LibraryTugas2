$(document).ready(function() {
	
	var bookindex=1;
	$(".detail").each(function(index, el) {
		if(bookindex % 4 !== 0){
			$(this).addClass('book-detail-right');
		}else if(bookindex % 4 === 0){
			$(this).addClass('book-detail-left');
		}
		++bookindex;
	});

	$("img").click(function(event) {
		if($(this).siblings().is(":hidden")){
			$(".detail").fadeOut(600);
			$(this).siblings().fadeIn(600);	
		}else if($(this).siblings().is(":visible")){
			$(this).siblings().fadeOut(600);
		}
	});

	$(window).scroll(function() {
	  $(".slideanim").each(function(){
	    var pos = $(this).offset().top;

	    var winTop = $(window).scrollTop();
	    if (pos < winTop + 600) {
	      $(this).addClass("slide");
	    }
	  });
	});

	$('.to-top').click(function(){
		$(document.body).animate({scrollTop : 0},500);
		return false;
	});

	 //Set the carousel options
	$('#quote-carousel').carousel({
		pause: true,
		interval: 4000,
	});
});