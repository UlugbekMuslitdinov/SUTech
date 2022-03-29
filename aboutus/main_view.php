<?php
//	require_once("header.php");
//	require_once("sidebar.php");
//	require_once("./template/header.php");
//	require_once("./template/sidebar.php");
	define('__ROOT__', dirname(dirname(__FILE__)));
	require_once(__ROOT__.'/template/header.php');
	require_once(__ROOT__.'/template/sidebar.php');
?>
<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="aboutus.css">


<div class="about">

	<h1 class="aboutus-title">About Us</h1>
	<div class="wrap-director">
		<h2 class="h2-title"></h2>
		<?php printStaffList($IT_Director); ?>
	</div>
	<div class="wrap-contact">
		<div class="cont-left">
			<h2 class="h2-title">IT Team</h2>
			<div class="wrap-dept-contact block-background-color">
				<div class="cont-row main-font">
					<span class="glyphicon glyphicon-envelope" aria-hidden="true" title="email"></span>
					<a href="mailto:su-tech@email.arizona.edu" class="main-font">su-tech@email.arizona.edu</a>
				</div>
				<div class="cont-row main-font">
					<span class="glyphicon glyphicon-earphone" aria-hidden="true" title="phone"></span>
					520-626-3252
				</div>
				<div class="cont-row main-font">
					<span class="glyphicon glyphicon-print" aria-hidden="true" title="fax"></span>
					520-621-9242
				</div>
			</div>
		</div>

		<div class="cont-right">
			<h2 class="h2-title">Web Team</h2>
			<div class="wrap-dept-contact block-background-color">
				<div class="cont-row main-font">
					<span class="glyphicon glyphicon-envelope" aria-hidden="true" title="email"></span>
					<a href="/webissue/index.php" class="main-font">su-web@email.arizona.edu</a>
				</div>
				<div class="cont-row main-font">
					<span class="glyphicon glyphicon-earphone" aria-hidden="true" title="phone"></span>
					520-621-9892
				</div>
			</div>
		</div>

	</div>

	<div class="wrap-staff-list">
		
		<div class="staff-list-it">
			<h2 class="h2-title">Staff - IT</h2>
			<?php printStaffList($IT_staff_list); ?>
			<h2 class="h2-title" style="margin-top: 40px;">Student Employees - IT</h2>
			<?php printStaffList($IT_student_list); ?>
		</div>

		<div class="staff-list-web">
			<h2 class="h2-title">Staff - Web</h2>
			<?php printStaffList($Web_staff_list); ?>
			<h2 class="h2-title" style="margin-top: 40px;">Student Employees - Web</h2>
			<?php printStaffList($Web_student_list); ?>
			<h2 class="h2-title" style="margin-top: 100px;">Location</h2>
			<div class="wrap-staff-info location main-font block-background-color">
				<h2 class="main-font">Student Unions Tech</h2>
				The University of Arizona<br>
				Student Union Memorial Center<br>
				Room #156<br>
				1303 E. University Blvd.<br>
				Mountain Ave & North Campus Dr<br>
				Tucson, AZ 85719-0521
			</div>
			<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1687.4645400736765!2d-110.95328957834622!3d32.23307600213726!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzLCsDEzJzU2LjgiTiAxMTDCsDU3JzA1LjYiVw!5e0!3m2!1sen!2sus!4v1481930752887" width="329" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
			<a href="http://union.arizona.edu/infodesk/maps/sumc_maps.php?level=level1" class="staff-list-web" target="_blank"><img style="width:329px;" src="/images/unionmap.png"></a>
		</div>
	</div>
</div>



<?php


function printStaffList($array){
	foreach ($array as $i) {
		$print = '<div class="wrap-staff-info block-background-color">';

		if ($i['img']==''){
			$imgsrc = '/images/people/noface.jpg';
		}else {
			$imgsrc = $i['img'];
		}


		$print .= '<div class="staff-img">'.
			      	'<img title="'.$i['name'].'" src="'.$imgsrc.'" />'.
			      '</div>'.
			      '<div class="staff-info">'.
				  	'<div class="staff-name main-font">'.
						'<b>'.$i['name'].'</b>'.
					'</div>'.
					'<div class="staff-position main-font">'.
						'<div class="emp_title">'.$i['position'].'</div>'.
					'</div>';
		if ($i['email'] != ''){
			$print .= '<div class="staff-email">'.
					  	'<span class="glyphicon glyphicon-envelope" aria-hidden="true" title="email"></span>'.
					  	'<a href="mailto:'.$i['email'].'" class="main-font">'.$i['email'].'</a>'.
					  '</div>';
		}
		if ($i['phone'] != ''){
			$print .= '<div class="staff-phone main-font">'.
					  	'<span class="glyphicon glyphicon-earphone" aria-hidden="true" title="phone"></span>'.
					  	$i['phone'].
					  '</div>';
		}
		if ($i['website'] != ''){
			$print .= '<div class="staff-website main-font">'.
					  	'<span class="glyphicon glyphicon-home" aria-hidden="true" title="website"></span>'.
					  	'<a href="'.$i['website'].'">'.
					  	$i['website'].
					  	'</a>'.
					  '</div>';
		}
		$print .= '</div>';
		$print .= '</div>';
		echo $print;
	}
}
//require_once("footer.php");
//require_once("./template/footer.php");
require_once(__ROOT__.'/template/footer.php');
?>
