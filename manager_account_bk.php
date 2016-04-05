<?
  
	$restrict = 1;
	$page_title = "Spinattic | Manage Account";
	require("inc/header.php");

?>		<style type="text/css"></style>
		<div>
			<h1 class="manage_acount_header">Manage Account</h1>
		</div>
		<hr class="space180px">
	   
		<div class="wrapper wrapper-user manage_account">
			<header>
				<h2>User ID: #999 - "Ariel Micheletti"</h2>
				<ul>
					<li><a href="#" class="acountlink">edit profile</a></li>
					<li><a href="#" class="acountlink">Close Acount</a></li>
				</ul>
			</header>
		   <table>
			   <tr>
					<td class="label">Account type:</td>
					<td><span class="blue-link">Advanced</span><a href="#" class="changenow">Upgrade now</a></td>               
			   </tr>
				<tr>
					<td class="label">Account started on:</td>
					<td>2014-10-21</td>               
			   </tr>
			   <tr>
					<td class="label">Billing cycle:</td>
					<td>
						<select name="billing_cicle" id="billing_cicle">
							<option value="">Yealy</option>
							<option value="">montly</option>
						</select>
						<span class="updated"><i class="fa fa-check-circle-o"></i> Updated. Next cycle will be monthly</span>
					</td>
		 	   </tr>
		 	   	<tr>
					<td class="label">Current cycle start:</td>
					<td>
						2015-10-21
					</td>
					<tr>
					<td class="label">Current cycle end:</td>
					<td>
						2016-20-21 <a href="#" class="changenow">Renew now</a>
					</td>
		 	   		</tr>
		 	   </tr>
		   </table>
		   
		   	<strong><i class="fa fa-calendar-check-o"></i> we will automatically rebill your <i class="fa fa-cc-visa"></i> (***776 ex 08/16) </strong><br>
		   	<a href="#" class="acountlink">modify</a> <span>-</span> <a href="#" class="acountlink">forget this card</a>
			<div style="clear-both"></div>
		   	<footer style="max-width:450px">
		   	<div class="owe">
		   		You currently owe: $0.-
		   	</div>
		   	<a href="#" class="makepay">Make payment now</a>
		   	<p><a href="#">View invoice history</a></p>
		   </footer>
		   
			</div>
	<?php require_once("inc/footer.php");?>
</html>



