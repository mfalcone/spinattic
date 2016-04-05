<?
  
	$restrict = 1;
	$page_title = "Spinattic | Manage Account";
	require("inc/header.php");

?>		<style type="text/css"></style>
		<div>
			<h1 class="manage_acount_header">Invoice History</h1>
		</div>
		<hr class="space180px">
	   
		<div class="wrapper wrapper-user manage_account">
			<header>
				<h2>User ID: #999 - "Ariel Micheletti"</h2>
				<a href="manager_account.php" class="back"><i class="fa fa-chevron-left"></i> back</a>
			</header>
			<table class="invoice">
			  <tr>
				<th colspan="2"><i class="fa fa-history"></i>&nbsp;invoice history</th>
				<th colspan="3" class="filter">
					<h4>Filter by:</h4>
					<select name="year" id="year">
						<option value="">2015</option>
						<option value="">2014</option>
					</select>
					<select name="month" id="month">
						<option value="">January</option>
						<option value="">Febrary</option>
						<option value="">March</option>
					</select>

				</th>
			  </tr>
			  <tr>
				<td>#001-2015-10-21</td>
				<td>Advanced user yearly</td>
				<td>2015-10-21 | 2016-10-20</td>
				<td>$100</td>
				<td class="action">
					<a href="#">edit</a>-
					<a href="#">download</a>-
					<a href="#" class="pay">pay</a>
				</td>
			  </tr>
			  <tr>
				<td>#001-2015-10-21</td>
				<td>Advanced user yearly</td>
				<td>2015-10-21 | 2016-10-20</td>
				<td>$100</td>
				<td class="action">
					<a href="#">edit</a>-
					<a href="#">download</a>-
					<a href="#" class="pay">pay</a>
				</td>
			  </tr>
			  <tr>
				<td>#001-2015-10-21</td>
				<td>Advanced user yearly</td>
				<td>2015-10-21 | 2016-10-20</td>
				<td>$100</td>
				<td class="action">
					<a href="#">edit</a>-
					<a href="#">download</a>-
					<a href="#" class="pay">pay</a>
				</td>
			  </tr>
			  <tr>
				<td>#001-2015-10-21</td>
				<td>Advanced user yearly</td>
				<td>2015-10-21 | 2016-10-20</td>
				<td>$100</td>
				<td class="action">
					<a href="#">edit</a>-
					<a href="#">download</a>-
					<a href="#" class="pay">pay</a>
				</td>
			  </tr>
			</table>
		</div>
	<?php require_once("inc/footer.php");?>
</html>



