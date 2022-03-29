$(document).ready(function() {
	$('#select_class').show();

	$('#payment_option').change(function() {
		var option = $(this);

		if(option.val() === "One Class") {
			$("#select_class").show();
		}
		else {
			$("#select_class").hide();
		}
	});
});