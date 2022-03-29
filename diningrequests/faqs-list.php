<?php
$regHelp_list = array();
$foodPro_list = array();

// $regHelp_list[] = [
//		"Question Type"      => "",
// 		"Question"  => "",
// 		"Answer"    => ""
// ];

// $foodPro_list[] = [
// 		"Question"  => "",
//      "Question Type"  =>  "",
// 		"Answer"    => ""
// ];
$regHelp_list[] = [
	"Question" => "How can I use item coupons?",
	"Question Type"  => "button",
	"Answer"   => "<a target='_blank' href='faq_files/How_to_Use_Item_Coupons.pdf' class='btn btn-primary'>How to use item coupons</a>"
	];
$regHelp_list[] = [
"Question" => "How can I reprint a receipt?",
	"Question Type"  => "button",
	"Answer"   => "<a target='_blank' href='faq_files/ReprintAReceipt.pdf' class='btn btn-primary'>How to reprint a receipt</a>"
];

// $regHelp_list[] = [
// 		"Question"  => "",
//      "Question Type"  =>  "",
// 		"Answer"    => ""
// ];

// $foodPro_list[] = [
// 		"Question"  => "",
//      "Question Type"  =>  "",
// 		"Answer"    => ""
// ];

$foodPro_list[] = [
		"Question"  => "How can I configure a new FP Session?",
		"Question Type"  => "button",
		"Answer"    => "<a target='_blank' href='faq_files/How_to_configure_a_new_FP_Session.pdf' class='btn btn-primary'>How to configure a new FP session</a>"
];
$foodPro_list[] = [
		"Question"  => "How to Print Recipes from a Location",
		"Question Type"  => 'list',
		"Answer"    => "<ol>
							<li>Go to the location that needs the recipe list</li>
							<li>Go to Reports</li>
							<li>Go to List Data Files (Location)</li>
							<li>Go to List Recipes (Location)</li>
							<li>Select the sort option
								<ul>
								  <li>1 = Alpha</li>
								  <li>2 = Numeric</li>
								  <li>0 = End (Go back to main FoodPro screen)</li>
								</ul>  
							</li>
							<li>Press Enter and the list should Print</li>
						</ol>"
];
$foodPro_list[] = [
		"Question"  => "How can I schedule a recipe?",
		"Question Type"  => "button",
		"Answer"    => "<a target='_blank' href='faq_files/Scheduling_a_Recipe.pdf' class='btn btn-primary'>How to scheudle a recipe</a>"
];
$foodPro_list[] = [
		"Question"  => "How can I get to vendor quick orders?",
		"Question Type"  => "button",
		"Answer"    => "<a target='_blank' href='faq_files/Vendor_Quick_Orders.pdf' class='btn btn-primary'>How to get to vendor quick orders</a>"
];
$foodPro_list[] = [
		"Question"  => "How can I batch recipes?",
		"Question Type"  => "button",
		"Answer"    => "<a target='_blank' href='faq_files/Batching_Recipes.pdf' class='btn btn-primary'>How to batch recipes</a>"
];
$foodPro_list[] = [
		"Question"  => "How can I reprint Prepared Product Transfers (PPTs) and inventory transfers?",
		"Question Type"  => "button",
		"Answer"    => "<a target='_blank' href='faq_files/Preprint_PPTs.pdf' class='btn btn-primary'>How to reprint PPTs and inventory transfers</a>"
];
?>

<?php $faqs_list = array_merge($regHelp_list,$foodPro_list); ?>

<script type="text/javascript">
	var faqs_list = <?php echo json_encode($faqs_list); ?>;
</script>

<?php $count = 1; ?>

<h2 class="faq_list_heading">Register Help</h2>

<?php $listNum = 1; ?>
<?php foreach ($regHelp_list as $list) { ?>
	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="faq_list_heading_<?php echo $count; ?>">
			<h4 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $count; ?>" aria-expanded="true" aria-controls="collapse<?php echo $count; ?>">
                  <?php echo $listNum.'. '.$list['Question']; ?>
                </a>
            </h4>
        </div>
        <div id="collapse<?php echo $count; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="faq_list_heading_<?php echo $count; ?>">
			<div class="panel-body">
				<?php echo $list['Answer']; ?>
			</div>
		</div>
	</div>
	<?php $count++; ?>
	<?php $listNum++; ?>
<?php } ?>

<h2 class="faq_list_heading foodproHead">FoodPro Help</h2>

<?php $listNum = 1; ?>
<?php foreach ($foodPro_list as $list) { ?>
	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="faq_list_heading_<?php echo $count; ?>">
			<h4 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $count; ?>" aria-expanded="true" aria-controls="collapse<?php echo $count; ?>">
                  <?php echo $listNum.'. '.$list['Question']; ?>
                </a>
            </h4>
        </div>
        <div id="collapse<?php echo $count; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="faq_list_heading_<?php echo $count; ?>">
			<div class="panel-body">
				<?php echo $list['Answer']; ?>
			</div>
		</div>
	</div>
	<?php $count++; ?>
	<?php $listNum++; ?>
<?php } ?>
