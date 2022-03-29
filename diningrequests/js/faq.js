$(document).ready(function(){

	if (detectmob()){
		$('.faq-scroll-up').css('bottom','10px');
	}

	// On scroll
	$(window).scroll(function(){
		// Show scroll up btn depends on position of page
        if ($('body').scrollTop() >= 100){
        	$('.faq-scroll-up').show();
		}else {
			$('.faq-scroll-up').hide();
		}
    });

    $('#faq_search_input').focus(function(){
    	$('#accordion .in').collapse('hide');
    });	

});

function faqSearchOnKeyUp(){
	// Hide opened answer first
	$('#accordion .in').collapse('hide');

	var value = $('#faq_search_input').val();

	var arr_value = value.toLowerCase().split(/ +/);

	if (value != "")
	{
		if (value.charAt(0) == ' '){
			value = value.substr(2);
			$('#faq_search_input').val(value);
		}
		value = value.toLowerCase();
		var print = "";
		var question = "";
		var answer = "";
		for (key in faqs_list){
			question = faqs_list[key]['Question'];
			question_lower =question.toLowerCase();
			answer = faqs_list[key]['Answer'];
			answer_lower = answer.toLowerCase();
			index = parseInt(key) + 1;

			// Remove html tags from question if question type is button
			if (faqs_list[key]['Question Type'] == 'button'){
				answer = answer.replace(/(<([^>]+)>)/ig, "");
			}

			if (faqs_list[key]['Question Type'] == 'list'){
				answer = answer.replace(/(<([^>]+)>)/ig, '<br>');
				// answer = answer.replace("(space)", "<br>");
			}

			var qIndex = question_lower.indexOf(value);
			var aIndex = answer.toLowerCase().indexOf(value);

			// Hightlist text 
			if (qIndex > -1) // keyword in question
			{
				question = trimText(question,qIndex);
				question = highlightKeyword(question,value);
				if (aIndex > -1) // keyword in answer
				{
					answer = trimText(answer,aIndex);
					answer = highlightKeyword(answer,value);
				}
				print += printSearchResult(key,index,question,answer);

			}
			else if (aIndex > -1) // keyword in answer
			{
				answer = trimText(answer,aIndex);
				answer = highlightKeyword(answer,value);
				if (qIndex > -1) // keyword in question
				{
					question = trimText(question,qIndex);
					question = highlightKeyword(question,value);
				}
				print += printSearchResult(key,index,question,answer);
			}

		}// end of for-faqs_list
		if (print == "")
		{
			print = '<div class="search-cantfind"><a>Can\'t find..</a></div>';
		}
		$('.search-result').html(print);
	}
	else
	{
		$('.search-result').html("");
	}

	// just incase list is not shown
	if (document.getElementsByClassName('dropdown open').length == 0){
		$('#faq_search_input').click();
	}
}

function trimText(text,index){
	var leng_text = text.length;
	var indexStart = 3;
	var indexEnd = leng_text;
	var maxLeng = 199;

	if (index <= 50){
		indexStart = 0;
		if (leng_text < maxLeng){
			indexEnd = leng_text;
		}else{
			indexEnd = maxLeng;
		}
		text = text.substr(indexStart,indexEnd);
	}else {
		indexStart = index-30;
		indexEnd = indexStart+100;
		text = '...'+text.substr(indexStart,indexEnd)+'...';
	}
	// console.log(indexEnd);
	// text = text.substr(indexStart,indexEnd);
	return text;
}

function highlightKeyword(original,value){
	original = original.replace(RegExp('('+value+')', "gi"),function(value){
		return '<mark>'+value+'</mark>';
	}); // Hightlist text 
	return original;
}

function printSearchResult(key,index,question,answer){
	return '<div class="wrap-search-list"><div class="search-list" onclick="faqOpenAnswer('+key+')"><b>'+index+'. '+question+'</b><hr>'+answer+'</div></div>';
}

function faqOpenAnswer(key){
	key = key + 1;
	// $('#accordion .panel-collapse').collapse('hide');
	$('body').scrollTop($('#collapse'+key).offset().top-200);
	$('#faq_search_input').val(' ');
	$('.search-result').html(' ');
	$('#collapse'+key).collapse('show');
}

function faq_scrollTop(){
	// $('body').scrollTop('20');
	$('html, body').animate({ scrollTop: $('body').scrollTop()-600}, 500);
}

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