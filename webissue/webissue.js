
var fileIndex = 0;
$(document).ready(function(){

	// On page load focus first name input
	$('#firstName').focus();

	$('#add_more_file').click(function(){
		fileIndex++;
		$('.wrap-files').append(
			'<div id="file'+fileIndex+'">'+
				'<input type="file" id="file-'+fileIndex+'" name="files[]" multiple>'+
				'<a class="delete-file btn btn-warning" onclick="deleteFile('+fileIndex+')">Delete</a>'+
			'</div>'
		);
		$('#file-'+fileIndex).click();
	});

});

function deleteFile(index){
	var length = $('.wrap-files div').length;
	$('#file'+index).remove();
}