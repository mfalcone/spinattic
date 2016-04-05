<?
  
	$restrict = 1;
	$page_title = "Spinattic | Manage Account";
	require("inc/header.php");

?>		<style type="text/css"></style>
		<div>
			<h1 class="manage_acount_header">Make Payment</h1>
		</div>
		<hr class="space180px">
	   
		<div class="wrapper wrapper-user manage_account">
			<header>
				<h2>User ID: #999 - "Ariel Micheletti"</h2>
				<a href="manager_account.php" class="back"><i class="fa fa-chevron-left"></i> back</a>
				<p class="pay"><strong><i class="fa fa-calendar-check-o"></i> we will automatically rebill your <i class="fa fa-cc-visa"></i> (***776 ex 08/16)</strong> <br>
			<a href="#" class="acountlink">modify</a> <span>-</span> <a href="#" class="acountlink">forget this card</a></p>
			</header>
		   <table style="margin-top:-20px">
			   <tr>
					<td class="label">Account type:</td>
					<td>
						<select name="account_type" id="account_type">
							<option value="">Advanced</option>
							<option value="">Pro</option>
						</select>
						
					</td>
				</tr>
				<tr>
					<td class="label">Billing cycle:</td>
					<td>
						<select name="billing_cicle" id="billing_cicle">
							<option value="">Yealy</option>
							<option value="">montly</option>
						</select>
					</td>
			   </tr>
		   </table>
		   <div class="table-wrapper">
			  <table class="table_description">
					<tr>
						<th class="td_main">Description</th>
						<th>Billing</th>
						<th>Start</th>
						<th>End</th>
					</tr>
					<tr>
						<td class="td_main">Advanced account</td>
						<td>$96 yearly ($8 per month)</td>
						<td>2015-10-21</td>
						<td>2016-10-21</td>
					</tr>
					<tr>
						<td class="td_main">Credit of unused period</td>
						<td><span class="money">$96</span></td>
						<td>2015-10-21</td>
						<td>2015-10-21</td>
					</tr>
			</table> 
		</div>
		   <footer>
		   
			<div class="owe owe-large">
				Total: $0.-
			</div>
			<a href="#" class="makepay makepay-large">check out</a>
		   </footer>
		   
			</div>
	<?php require_once("inc/footer.php");?>
</html>



