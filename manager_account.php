<?

/*	$owe = 0;
	$credit_b = 0; //Crédito a favor

	$restrict = 1;
	$page_title = "Spinattic | Manage Account";
	require(realpath($_SERVER["DOCUMENT_ROOT"])."/inc/header.php");
	
	$level = get_level($_SESSION['usr']);

	$level_ind = 1;
	if($level == 'PRO'){
		$level_ind = 2;
	}
	
	if($level != 'FREE'){
		//$allow_renew = allow_renew($_SESSION['usr'], $level); //verificación si deja renovar (1 mes antes del vencimiento)
		//Check if has credit
		$has_credit = 0;
		//$ssqlp = "select payments.*, DATEDIFF(cycle_end, now()) as remaining_days,amount/DATEDIFF(cycle_end, cycle_start) as amount_per_day, amount/DATEDIFF(cycle_end, cycle_start) * DATEDIFF(cycle_end, now()) as remaining_credit from payments where user_id = ".$_SESSION['usr']." and (cycle_start <= now() and cycle_end > now() or cycle_start > now()) and upgraded_to_level = '".$level."' order by cycle_start";
		$ssqlp = "select payments.*, DATE_ADD(cycle_end, INTERVAL 1 YEAR) as cycle_end_renew , DATEDIFF(cycle_end, now()) as remaining_days, amount/DATEDIFF(cycle_end, cycle_start) * DATEDIFF(cycle_end, now()) as remaining_credit from payments where user_id = ".$_SESSION['usr']." and cycle_start <= now() and cycle_end > now() and upgraded_to_level = '".$level."' and upgraded = 0 ";
		$ssqlp .= "UNION ";
		$ssqlp .= "select payments.*, DATE_ADD(cycle_end, INTERVAL 1 YEAR) as cycle_end_renew , DATEDIFF(cycle_end, cycle_start) as remaining_days, amount/DATEDIFF(cycle_end, cycle_start) * DATEDIFF(cycle_end, cycle_start) as remaining_credit from payments where user_id = ".$_SESSION['usr']." and cycle_start > now() and upgraded_to_level = '".$level."' and upgraded = 0 order by id";
		//echo $ssqlp;		
		$result = mysql_query($ssqlp);
		
		if($row = mysql_fetch_array($result)){
			$has_credit = 1;
			$credit = $row["remaining_credit"];
			$credit_cycle_start = $row["cycle_start"];
			$credit_cycle_end = $row["cycle_end"];
			
			//Chequeo si más ciclos pagados
			while($row = mysql_fetch_array($result)){
				$credit = $credit + $row["remaining_credit"]; //Voy acumulando los créditos
				$credit_cycle_end = $row["cycle_end"];  //VOy actualizando la última fecha para el vencimiento
			}			
			
		}else{
			//el nivel está vencido, busco lo que debe
			$ssqlp = "select * from payments_rates where level = '".$level."'";
			$result = mysql_query($ssqlp);
			if($row = mysql_fetch_array($result)){
				$owe = $row["yearly_rate"];
			}
		}
	}	
	
	//Check if has credit balance
	$ssqlp = "select * from payments_credits where user_id = ".$_SESSION['usr']." order by date desc";
	$result = mysql_query($ssqlp);
	
	if($row = mysql_fetch_array($result)){
		$credit_b = $row["credit"];
	}	
	*/
	
?>		<style type="text/css"></style>
		<div>
			<h1 class="manage_acount_header">Manage Account</h1>
		</div>
		<hr class="space180px">
	   
		<div class="wrapper wrapper-user manage_account">
			<header>
				<h2>User ID: #<?php echo $_SESSION['usr'];?> - "<?php echo $_SESSION['username'];?>"</h2>
				<ul>
					<li><a href="editprofile" class="acountlink">Edit Profile</a></li>
					<li><a href="#" class="deluserdata acountlink">Close Account</a></li>
				</ul>
			</header>
		   <table>
			   <tr>
					<td class="label">Account type:</td>
					<td><span class="blue-link"><?php echo $level;?></span>
						
						<?php if($level != 'PRO'){?>
							<a href="<?php echo $http.$_SERVER[HTTP_HOST];?>/account/pricing" class="changenow">Upgrade now</a><?php }?></td>               
			   </tr>
				<!-- tr>
					<td class="label">Account started on:</td>
					<td>2014-10-21</td>               
			   </tr-->
			   
			   
			   
			   <!-- 
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
		 	   -->
		 	   
		 	   <?php if($level != 'FREE' && $has_credit == 1){?>
		 	   	<tr>
					<td class="label">Current cycle start:</td><td><?php echo $credit_cycle_start;?></td>
				</tr>
				<tr>
					<td class="label">Current cycle end:</td><td><?php echo $credit_cycle_end;?> <?php //if ($allow_renew == 1){?><a href="<?php echo $http.$_SERVER[HTTP_HOST];?>/account/checkout?nl=<?php echo $level_ind;?>" class="changenow">Renew now</a><?php //}?></td>
		 	   	</tr>
		 	   	<?php }?>

		 	   	
		   </table>
		   <!--  
		   	<strong><i class="fa fa-calendar-check-o"></i> we will automatically rebill your <i class="fa fa-cc-visa"></i> (***776 ex 08/16) </strong><br>
		   	<a href="#" class="acountlink">modify</a> <span>-</span> <a href="#" class="acountlink">forget this card</a>
		   	
		   	-->			
		   	<!-- Start of spinattic Zendesk Widget script -->
<script>/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(c){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var o=this.createElement("script");n&&(this.domain=n),o.id="js-iframe-async",o.src=e,this.t=+new Date,this.zendeskHost=t,this.zEQueue=a,this.body.appendChild(o)},o.write('<body onload="document._l();">'),o.close()}("https://assets.zendesk.com/embeddable_framework/main.js","spinattic.zendesk.com");
/*]]>*/</script>
<!-- End of spinattic Zendesk Widget script -->

		   	
			<div style="clear-both"></div>
		   	<footer style="max-width:450px">
 		  <?php if($level != 'FREE'){?>
			   	<div class="owe">
			   		You currently owe: $ <?php echo $owe;?>.-
			   	</div>
		   <?php }?>
		   
		   <?php if($level != 'FREE' && $owe > 0){?>
			   	<a href="<?php echo $http.$_SERVER[HTTP_HOST];?>/account/checkout?nl=<?php echo $level_ind;?>" class="makepay manage_account">Make payment now</a>
		   <?php }?>
		   
		   <?php if($credit_b > 0){?>
		   	<div class="credit_balance">
		   		Credit balance: $ <?php echo $credit_b;?>.-
		   	</div>	
		   <?php }?>
		   
		   <?php 
		  	$ssqlp = "select * from payments where user_id = '".$_SESSION['usr']."'";
		  	$result = mysql_query($ssqlp);
		  	if(mysql_num_rows($result) > 0){		
		   ?>
		   
		   	<p><a href="<?php echo $http.$_SERVER[HTTP_HOST];?>/account/invoice">View invoice history</a></p>
		   	
		   <?php }?>
		   </footer>
		   
			</div>
	<?php require_once(realpath($_SERVER["DOCUMENT_ROOT"])."/inc/footer.php");?>
</html>



