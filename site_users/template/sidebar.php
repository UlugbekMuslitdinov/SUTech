<div id="sidebar-left" class="column sidebar">
	<div class="block block-user" id="block-user-1">
		<div class="content">
			<ul class="menu">

			<li class="leaf first"><a href="/index.php">Home - Technology Support</a></li>
			<?php
//				echo get_include_path();
//				error_reporting(E_ALL);
//				ini_set('display_errors', '1');
				require_once(__DIR__."/mysql/include.php");
				$db_link = select_db("sucs");
				$links = array("Helpful Links" => "/links.php",
				"Technology Support Links" => "/techlinks.php",
				"Kronos Remote Access" => "/kronos.php",
			//	"Access Portal <i>(beta)</i>" => "/access/index.php",
			//	"Building Access <i>(beta)</i>" => "/buildingaccess/index.php",
			//	"Credentials" => "/credentials/index.php",
			//	"Workstations" => "/assets/workstations/index.php",
			//	"Printers" => "/assets/printers/index.php",
			//	"Servers" => "/assets/servers/index.php",
			//	"Menuboards" => "/assets/menuboards/index.php",
			//	"Network Assets" => "/assets/network/index.php",
			//	"Tips &amp; Scripts" => "/tips/index.php",
			//	"Workstation Drivers" => "/drivers/workstations.php",
			//	"Printer Drivers" => "/drivers/printers.php",
			//	"Nick B's Page" => "/nickb/index.php",
				"Site Access Control" => "/site_access/index.php",
				"Site Users &amp; Groups" => "/site_users/index.php",
			//	"Wiki - Knowledgebase" => "/wiki",
				"Kronos 7 F.A.Q." => "/faq_kronos",
				"Connecting to FoodPro" => "/faq_foodpro",
				"Accessing New Home Share" => "/faq_homeshare",
				"F.A.Q." => "/faq.php");

				foreach($links as $name => $link) {
					$print = false;

					$result = mysqli_query($db_link,'SELECT resources.name, COUNT(resource_id) AS access_records
									FROM resources
									WHERE resources.script = "'.$link.'"
									GROUP BY resources.name')
					or die(mysqli_error($db_link));
							$result = mysqli_fetch_array($result);
							if (!isset($result) || $result["access_records"]<1) {
									$print = true;
							}
					else {
							$result = mysqli_query($db_link,'SELECT users.netID, users.user_id, COUNT( permission_id ) AS access_records
									FROM users, permissions, resources
									WHERE users.user_id = permissions.user_id
									AND permissions.resource_id = resources.resource_id
									AND resources.script = "'.$link.'"
									AND users.netID = "'.$_SESSION['webauth']['netID'].'"')
								or die(mysqli_error($db_link));
							$result = mysqli_fetch_array($result);
							$records = $result["access_records"];
							$result = mysqli_query($db_link,'SELECT users.netID, users.user_id
											FROM users
											WHERE users.netID = "'.$_SESSION['webauth']['netID'].'"')
									or die(mysqli_error($db_link));
							$result = mysqli_fetch_array($result);
							$user_id = $result["user_id"];
							$result = mysqli_query($db_link,'SELECT COUNT( permission_id ) AS access_records
									FROM memberships, permissions, resources
									WHERE memberships.user_id = '.intval($user_id).'
									AND memberships.group_id = permissions.group_id
									AND permissions.resource_id = resources.resource_id
									AND resources.script = "'.$link.'"')
								or die(mysqli_error($db_link));
							$result = mysqli_fetch_array($result);
							$records += $result["access_records"];
								if (isset($result) && $records>0) {
							$print = true;
								}
					}

					if ($print) {
						echo '<li class="collapsed"><h2><a href="'.$link.'">'.$name.'</a></h2></li>';
					}
				}
			?>
			<form name="webauthform" id="webauthform" method="post" action="#" />
				<input type="hidden" name="action" />
				<li class="collapsed">
				<?php
				if (isset($_SESSION['webauth']['netID'])) {
					echo '<a href="javascript:webauthAction(\'logout\')">Logout</a>';
				}
				else {
					echo '<a href="javascript:webauthAction(\'login\')">Login</a>';
				}
				?>
				</li>
			</form>
			</ul>
		</div>
	</div>
	<div class="block block-user" style="float:left;margin-top: 10px;border:none;">
		<div style="width:100%;float:left;">
			<a href="/aboutus">
				<div style="float:left;width:100%;">
					<div style="float:left;"><img src="/images/phone.png" /></div>
					<div style="margin-top: 14px;margin-left: 6px;float:left;color: #003876;font-weight: bold;">Contact us</div>
				</div>
			</a>
			<br />
			<a href="/aboutus">
				<div style="float: left;width:100%;">
					<div style="float:left;"><img src="/images/Google-Maps-icon.png" /></div>
					<div style="margin-top: 14px;margin-left: 6px;float:left;color: #003876;font-weight: bold;">Find us</div>
				</div>
			</a>
		</div>
	</div>
</div>


<div id="main" class="column">
<div id="squeeze" class="clear-block">
<div id="content">
