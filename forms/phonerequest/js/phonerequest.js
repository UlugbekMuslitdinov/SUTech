(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();

function jack_id() {
  var x = document.getElementById("jack_id");
  x.style.display = "block";
  $('#jack').attr('required');
}

function disable_jack_id() {
  var x = document.getElementById("jack_id");
  x.style.display = "none";
  $('#jack').removeAttr('required');
}

function enable_call_appearance() {
  var x = document.getElementById("call_appearance");
  x.style.display = "block";
  $('#call_appearance1').attr('required');
}

function disable_call_appearance() {
  var x = document.getElementById("call_appearance");
  x.style.display = "none";
  $('#call_appearance1').removeAttr('required');
}