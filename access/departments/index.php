<?php
	$webauth_script_override = "/access/index.php";
	$require_authorization = true;
	include_once('header2.php');
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	require_once("mysql/include.php");

	$db_link = select_db("access");
?>

<ol class="breadcrumb">
  <li><a href="/strap">Home</a></li>
  <li><a href="/access">Access</a></li>
  <li class="active">Departments</li>
</ol>
<h1>Departments</h1>

<style type="text/css">
.footable-row-detail-name {
	text-align: right;
}
.footable-row-detail-value > input {
	margin-bottom: 5px;
}
@icon-font-path: "/boostrap/fonts/";
@font-face {
  font-family: 'Glyphicons Halflings';
  src: url('/bootstrap/fonts/glyphicons-halflings-regular.eot');
  src: url('/bootstrap/fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded- opentype'), url('/bootstrap/fonts/glyphicons-halflings-regular.woff') format('woff'), url('/bootstrap/fonts/glyphicons-halflings-regular.ttf') format('truetype'), url('/bootstrap/fonts/glyphicons-halflings-regular.svg#glyphicons-halflingsregular') format('svg');
}
</style>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<br /><br />
<div class="row">
    <div class="col-xs-8 col-sm-5 col-md-5">
      <input id="filter1" class="form-control" placeholder="Filter" type="text"/>
    </div>
    <div class="col-xs-4 col-sm-7 col-md-7">
		<button type="submit" class="btn btn-danger clear-filter clear-filter" onClick="$('table').trigger('footable_clear_filter');">Reset Filters</button>
    </div>
</div>
<br />
<table class="table footable toggle-square" data-filter="#filter1" data-page-size="10">
  <thead>
    <tr>
      <th data-toggle="true">Display Name</th>
      <th data-hide="phone">Short Name</th>
	  <th data-hide="phone">Abbreviation</th>
	  <th data-hide="phone,tablet">Phone</th>
	  <th data-hide="phone">Acct#</th>
	  <th data-hide="phone">FoodPro#</th>
	  <th data-hide="phone,tablet" data-sort-ignore="true"></th>
    </tr>
  </thead>
  <tbody>
  <?php
    $query = 'SELECT * FROM  department';
	$result = $db_link->query($query);
	while($row = $result->fetch_array()) {
		echo'<a name="dep_'.$row["id"].'"></a>
		<tr>
          <td>'.$row["display_name"].'</td>
		  <td>'.$row["short_name"].'</td>
		  <td>'.$row["abbreviation"].'</td>
		  <td>'.$row["phone"].'</td>
		  <td>'.$row["acct_num"].'</td>
		  <td>'.$row["foodpro_num"].'</td>
		  <td><br /><a href="edit.php?dep_id='.$row["id"].'" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</a><br /></td>
		</tr>';
	}
?>
          </tbody>
          <tfoot class="hide-if-no-paging">
          <tr>
          <td colspan="5">
            <ul class="pagination pagination-centered"></ul>
          </td>
        </tr>
        </tfoot>
      </table>
<?php
    include_once('footer2.php');
?>
<script src="/FooTable-2/js/footable.js?v=2-0-1" type="text/javascript"></script>
<script src="/FooTable-2/js/footable.sort.js?v=2-0-1" type="text/javascript"></script>
<script src="/FooTable-2/js/footable.filter.js?v=2-0-1" type="text/javascript"></script>
<script type="text/javascript">
  $(function () {
    $('table').footable();
    //$('table').trigger('footable_expand_all');
  });
</script>