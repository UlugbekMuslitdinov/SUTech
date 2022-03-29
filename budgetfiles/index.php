<?php
session_start();
include("webauth/include.php");
	
	if (isset($_POST["submit"]) && $_POST["submit"]=="Download") {
		 // check for IE only headers
		if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) {
		  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		  header('Pragma: public');
		}
		else {
		  header('Pragma: no-cache');
		}
		$selection = filter_var($_POST["selection"], FILTER_SANITIZE_STRING);
		$selection = str_replace("/", "", $selection);
		$selection = str_replace("\\", "", $selection);
		$selection = str_replace(">", "", $selection);
		$selection = str_replace("<", "", $selection);
		header("Content-Disposition: attachment; filename=".$selection);
		$types = array(".txt" => 'Content-Type: text/plain',
					   ".pdf" => 'Content-Type: application/pdf',
					   ".jpg" => 'Content-Type: image/jpeg',
					   ".docx" => 'Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document',
					   ".doc" => 'Content-type: application/vnd.ms-word',
					   ".xls" => 'application/vnd.ms-excel',
					   ".xlsx" => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header($types[substr($selection, -4)]);
		readfile('../protected_downloads/budfiles/'.$selection);
		exit;
	}
	
require_once("header.php");
require_once("sidebar.php");

	
$fileTypes = Array(".pdf",".doc","docx",".xls","xlsx",".txt",".jpg");
function getFileList($dir) {

	if ($dh = opendir($dir)) {

		$files = Array();
		$inner_files = Array();
		$fileTypes = Array(".pdf",".doc","docx",".xls","xlsx",".txt",".jpg");
		$dirBlacklist = Array();

		while ($file = readdir($dh)) {
			if ($file != "." && $file != ".." && $file[0] != '.') {
				if (is_dir($dir . "/" . $file)) {
					if (!in_array($file, $dirBlacklist) && substr($file, 0, 1)) {
						array_push($files, $dir . "/" . $file);
						$inner_files = getFileList($dir . "/" . $file);
						if (is_array($inner_files))
							$files = array_merge($files, $inner_files);
					}
				} else {
					if (in_array(substr($file, -4), $fileTypes)) {
						array_push($files, $dir . "/" . $file);
					}
				}
			}
		}
		closedir($dh);
		sort($files);
		return $files;
	}
}
?>
<h1>Budget Files</h1>
<br /><br />
<div>Select file from dropdown and click the download button:</div><br />
		<form action="#" method="post" name="form">
			
			<?php
			$dir = '/srv/www/htdocs/Dropbox/WebBudgets';
			echo '<select id="selection" name="selection" style="font-size: 12px;" ><option></option>';
			foreach (getFileList($dir) as $key => $file) {
				$file = stripslashes($file);
				$location = substr($file, strlen($dir));
				$name = substr($location, strripos($location, "/") + 1);
				var_dump($name);
				if (in_array(substr($name, -4),$fileTypes)) {
					$location = ''.$location;
					//$name = substr($name, 0, -4);
					echo '<option value="' . $location . '">' . $name . '</option>';
				} else {
					$location = '';
					echo '<option value="">--' . $name . '--</option>';
				}
			}
			echo '</select>';
			?>
			<input type="submit" name="submit" style="font-size: 10px;" value="Download">
		</form>

<?php
    require_once("footer.php");
?>
