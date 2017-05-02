$(document).ready(function() {
	var bookid = $("#review-bookid").val();
	var userid = $("#review-userid").val();
	var allreview = "";

	$('#add-review').click(function(event) {
		/* Act on the event */
		var content = document.getElementById("content").value;

		$.when(
			$.ajax({
	            url: 'services/review.php',
				type: 'POST',
				data: {
					perintah: 'tambah',
					content: content,
					bookid: bookid,
					userid: userid
				},
				success: function(data) {
	                $("textarea").val("");
	               	return false;
	            }
        	})
		).then( function(){
	        $.ajax({
				url: 'services/review.php',
				type: 'POST',
				data: {
					perintah: 'tampil',
					bookid: bookid
				},
				success: function(data) {
	                $(".review").html(data);
	               	return false;
	            }
        	})
	    });
	});

	$.ajax({
		url: 'services/review.php',
		type: 'POST',
		data: {
			perintah: 'tampil',
			bookid: bookid
		},
	})
	.done(function(data) {
		$(".review").html(data);
		return false;
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
});	