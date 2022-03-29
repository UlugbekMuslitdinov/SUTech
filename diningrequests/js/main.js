$(document).ready(function(){

	if (detectmob()){
		
		// Minimize height for header
		$(window).scroll(function(){
			// Show scroll up btn depends on position of page
	        if ($('body').scrollTop() >= 200){
	        	$('.site-title').css({
	        		'padding': '0px',
	        		'display': 'inline-block',
	        		'font-size': '25px'
	        	});
	        	$('.mobile-nav-expandBtn').css({
	        		'display':'inline-block',
	        		'margin-left':'100px'
	        	});
	        	$('.mobile-nav-expandBtn span').css({
	        		'top':'5px'
	        	});
			}else {
				$('.site-title').css({
	        		'padding-top': '40px',
	        		'padding-bottom': '30px',
	        		'display': 'block',
	        		'font-size': '35px'
	        	});
	        	$('.mobile-nav-expandBtn').css({
	        		'position': 'relative',
	        		'display':'block'
	        	});
			}
	    });
	}

	$('ul.nav > li').on({
		mouseenter: function () {
			$('ul.nav > li').removeClass('active');
    },
    mouseleave: function () {
      curr_nav.className += " active";
    }
	});

});

function showMobileNav(){
	if ($('.wrap-masthead nav li').css('display') == 'none'){
		$('.wrap-masthead nav li').show();
	}else{
		$('.wrap-masthead nav li').hide();
	}
}


// function emailMsg(){
// 	$.ajax(
// 		{
// 			type: 'POST',
// 			url: "email.php", 
// 			data: {
// 				'email': $('#your-email').val(),
// 				'message': $('#message-text').val()
// 			},
// 			success: function(result){
// 						$(".testing").html(result);
// 						result = JSON.parse(result);
//         		if (result['emailConfirm'].success == false){
//         			if (result['dr_visitor'].error_exist == true){
//         				$('.email-alert').html(result['dr_visitor'].error);
//         			}else{
//         				$('.email-alert').html('');
//         			}
//         			if ('message' in result) {
//         				if (result['message'].success == false){
//         					$('.message-alert').html(result['message'].error);
//         				}else{
//         					$('.message-alert').html('');
//         				}
//         			}
//         		}else{// Sent successfully
//         			$('#emailModal_close_btn').click();
//         			$('#your-email').val('');
//         			$('#message-text').val('');
//         			$('.email-alert').html('');
//         			$('.message-alert').html('');
//         		}
//     		}
//     	}
//     );
// }

function detectmob() { 
 if( navigator.userAgent.match(/Android/i)
 || navigator.userAgent.match(/webOS/i)
 || navigator.userAgent.match(/iPhone/i)
 || navigator.userAgent.match(/iPad/i)
 || navigator.userAgent.match(/iPod/i)
 || navigator.userAgent.match(/BlackBerry/i)
 || navigator.userAgent.match(/Windows Phone/i)
 ){
    return true;
  }
 else {
    return false;
  }
}