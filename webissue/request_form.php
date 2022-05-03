

<form name="issueForm" onsubmit="validateEmail()" id="issueForm" method="POST" class="wrap-all-form" enctype="multipart/form-data">
		<!-- Error report -->
		<?php
		if( isset($_SESSION['web_issue_errors']) ){
			// Make sure session carries data
			if ( count($_SESSION['web_issue_errors']) != 0 ){
				$echo_err = '<div class="bg-danger"> <b>Error</b> : <ul>';
				foreach ($_SESSION['web_issue_errors'] as $err) {
					$echo_err .= '<li>'.$err.'</li>';
				}
				echo $echo_err.'</ul></div>';
				// Just in case session is not deleted, empty array
				$_SESSION['web_issue_errors'] = array();
				// Session will be unset at the end of this file
			}
		}

		// When page is returned with the error, inputs that user typed will be store in the session and function wi_oldInputs() will write value of inputs back.
		$wi_oldInputs = array();
		if (isset($_SESSION['WI_old_inputs'])){
			$wi_oldInputs = $_SESSION['WI_old_inputs'];
		}
		function wi_oldInputs($arr,$type){
			// Check if session has value for each input
			$return = isset($arr[$type]) ? $arr[$type] : '';
			echo $return;
		}
		?>

    	<h2 class="page-title">Having a Website related Issue?</h2>
    	<p class="webissue-description">If you find any problem with the Student Union’s websites, including errors,<br> wrong information or outdated pages, please submit your comments.</p>
    	<hr class="form-hr">    	
	    <div class="form-group">	    	
	    	<label for="firstName" class="name-label firstname required-input">First Name: </label>
	    	<input type="text" class="name-input" name="first_name" id="firstName" value="<?php wi_oldInputs($wi_oldInputs,'firstname'); ?>" required>
	    	<label for="lastName" class="name-label lastname required-input">Last Name: </label>
	    	<input type="text" class="name-input" name="last_name" id="lastName" value="<?php wi_oldInputs($wi_oldInputs,'lastname'); ?>" required>
	    </div>
	    <hr class="form-hr">
	    <div class="form-group">    	
	    	<label for="email" class="default-label required-input">E-mail: </label>
	    	<!-- <input type="email" class="email-input wide-input" name="email" id="email" value="<?php wi_oldInputs($wi_oldInputs,'email'); ?>" required> -->
		
		<!-- new input is same as old except type is changed to text and placeholder is added to prompt user to input a netid -->
		<input type="text" class="email-input short-input" name="email" id="email" placeholder="NetID" size="16" value="<?php wi_oldInputs($wi_oldInputs,'email'); ?>" style="min-width:145px" required>
<!--		<label for="email" class="">@email.arizona.edu</label>	-->
	    </div>
	    <div class="form-group">
	    	<label for="phone" class="default-label">Phone: </label>
	    	<input type="tel" class="phone-input" name="phone" id="phone" value="<?php wi_oldInputs($wi_oldInputs,'phone'); ?>">
	    </div>
	    <div class="form-group">
	    	<label for="url" class="url-label">URL of the page with issue: </label>
	    	<input type="text" class="url-input wide-input" name="url" id="url" value="<?php wi_oldInputs($wi_oldInputs,'url'); ?>">
	    </div>
	    <hr class="form-hr">
	    <div class="form-group">
	    	<label for="web_support_title" class="default-label">Title: </label>
	    	<input type="text" class="wst-input wide-input" name="web_support_title" id="web_support_title" value="<?php wi_oldInputs($wi_oldInputs,'title'); ?>">
	    </div>
	    <div class="form-group">
	    	<label for="supportRequest" class="supportRequest-label required-input">Issue details: </label>
	    	<textarea class="supportRequest-textarea" name="supportRequestText" id="supportRequest" rows="12" value="" wrap="hard" required><?php wi_oldInputs($wi_oldInputs,'text'); ?></textarea>
	    </div>
	    <hr class="form-hr">
	    <div class="form-group wrap-urgent">	    
	    	<label for="urgent" class="default-label">Is it Urgent? </label>
	    	<label class="urgent-label">
	    		<input type="radio" class="form-check-input" name="optionsUrgent" id="optionsUrgent" value="urgent">
        		Urgent
	    	</label>	    	
        	<label class="urgent-label">
        		<input type="radio" class="form-check-input" name="optionsUrgent" id="optionsUrgent" value="normal" checked>
        		Normal
        	</label>
        	<label class="urgent-label">
        		<input type="radio" class="form-check-input" name="optionsUrgent" id="optionsUrgent" value="anytime">
        		Anytime
        	</label>        	
	    </div>

	    <hr class="form-hr">

	    <div class="form-group ww-file-attach">
	    	<label>Attach File</label>
	    	<a class="btn btn-default btn-sm add-attach" id="add_more_file">Attach files</a>
	    	<button id="btn-submit" onclick="validateEmail()" type="submit" name="submit" class="btn btn-primary btn-lg submit-btn">Submit</button> (Click for multiple attachment.)
	    	<div class="wrap-files">
	    	</div>
	    </div>
    </form>


<script>
    function validateEmail()
    {
        let catmail = document.issueForm.email.value
        let parts = catmail.split("@")
        let possible_domains = [
            "email.arizona.edu",
            "arizona.edu",
            "catworks.arizona.edu",
            "catmail.arizona.edu"
        ]
        if isInList(parts[1], possible_domains) != true{
            alert("Wrong email domain")
        }
    }

    function isInList(str, list){
        for (i in list) {
            if (str == list[i]) {return true}
        }
        return false
    }
</script>

    <?php
    	// Destroy old input and error session
    	unset($_SESSION['web_issue_errors']);
    	unset($_SESSION['WI_old_inputs']);
    ?>