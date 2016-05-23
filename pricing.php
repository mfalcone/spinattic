<?
  
	
	require_once("inc/conex.inc");
	require_once ("inc/functions.inc");

	session_start();
	

		
	$page_title = "Spinattic | Pricing";
	require("inc/header.php");

	$level = get_level($_SESSION['usr']);
	
	//Get Actual Rates
	$ssqlp = "select * from payments_rates where level = 'ADVANCED'";
	$result = mysql_query($ssqlp);
	if($row = mysql_fetch_array($result)){
		$advanced_yearly_rate = $row["yearly_rate"];
	}
	
	$ssqlp = "select * from payments_rates where level = 'PRO'";
	$result = mysql_query($ssqlp);
	if($row = mysql_fetch_array($result)){
		$pro_yearly_rate = $row["yearly_rate"];
	}
		
  
	?>	
	
	<link rel="stylesheet" type="text/css" href="css/pricing.css">

		<div>
			<h1 class="manage_acount_header">Pricing</h1>
		</div>
		<hr class="space180px">
		<div class="wrapper pricing">
			<h3>Everything you need to start creating, customizing and sharing amazing virtual tours.</h3>
			<div class="column-wrap">
				<div class="column">
					<h3>Free</h3>
					<div class="expand"><span class="text-inf">Expand details</span><span class="fa  fa-chevron-down"></span></div>
					<ul>
						<li class="upper green">Unlimited Panoramas</li>
						<li class="upper green">Unlimited tours</li>
						<li>Pano formats: JPG</li>
						<li>Max file size: 25 mb</li>
						<li>Max px width 30 000 px</li>
						<li>Geolocation: <span>Scenes and tours</span></li>
						<li>Privacy settings: <br><span class="small">Public, Private, Not listed</span></li>
						<li>Friendly URL: <br> <span class="small">For profile and tours</span></li>
						<li class="tooltipped">
							Free plugins pack <i class="fa fa-search-plus"></i>
							<dl>
								<dt>FREE plugins pack:</dt>
								<dd><span class="fa fa-plug"></span>Simple Menu</dd>
								<dd><span class="fa fa-plug"></span>Avatar and titles</dd>
								<dd><span class="fa fa-plug"></span>Controls</dd>
								<dd><span class="fa fa-plug"></span>Social buttons<br>
									<span class="small">(coming soon)</span>
								</dd>
							</dl>
						</li>
						<li>WebVR Plugin</li>
						<li>Gyroscope plugin<br>
							<span class="small">(coming soon)</span>
						</li>
					</ul>
					<footer>
						<?php
						
						if($logged != 1){
							$current='<a href="">Get Free</a>';
						}else{
							if($level == 'FREE'){
								$current = 'Current';
							}else{
								$current = '<i class="fa fa-check"></i>';
							}
						}
						?>					
						<p class="prize upper single-line">Free</p>
						<p class="position upper single-line <?php if($logged != 1){echo "register";}?>"><?php echo $current;?></p>
					</footer>
				</div>
				<div class="column">
					<h3>Advanced</h3>
					<div class="expand"><span class="text-inf">Expand details</span> <span class="fa  fa-chevron-down"></span></div>
					<ul>
						<li class="upper red">All of the basic plan <br/> plus:</li>
						<li>Pano formats: JPG, TIFF, PNG</li>
						<li>Max file size: 50 mb</li>
						<li>Max px width: 50 000 px</li>
						<li>
							Set max tile size<br>
							<span class="small">(coming soon)</span>
						</li>
						<li>Add your Custom Signature</li>
						<li>Mouse control settings</li>
						<li>Autorrotation settings</li>
						<li class="tooltipped">
							Advanced plugins pack <i class="fa fa-search-plus"></i>
							<dl>
								<dt>ADVANCED plugins pack:</dt>
								<dd><span class="fa fa-plug"></span>Tour info panel <span class="small red">(coming soon)</span></dd>
								<dd><span class="fa fa-plug"></span>Logo</dd>
								<dd><span class="fa fa-plug"></span>Your Custom Signature</dd>
								<dd><span class="fa fa-plug"></span>Audio Plugin <span class="small red">(coming soon)</span></dd>
								<dd><span class="fa fa-plug"></span>Link button <span class="small red">(coming soon)</span></dd>
								<dd><span class="fa fa-plug"></span>Context menu</dd>
								<dd><span class="fa fa-plug"></span>Nadir Patch</dd>
								<dd><span class="fa fa-plug"></span>Custom loader bar</dd>
							</dl>
						</li>
						<li>
							Advanced Layouts pack: <br>
							<span class="small">(coming soon)</span>
						</li>
					</ul>
					<footer>
						<?php
						if($logged != 1){
							$current = '<a href="">Get Advanced</a>';
						}else{
							if($level == 'ADVANCED'){
								$current = 'Current';
							}else{
								if($level == 'PRO'){
									$current = '<i class="fa fa-check"></i>';
								}else{
									$current = '<a href="account/checkout?nl=1">Get Advanced</a>';
								}
							}
						}?>					
						<p class="prize">$<?php echo $advanced_yearly_rate;?> / year<br><span class="small">($<?php echo $advanced_yearly_rate/12;?>/mo)</span></p>
						<p class="position upper single-line <?php if($logged != 1){echo "register";}?>" <?php if($logged != 1){echo 'data-level="1"';}?>><?php echo $current;?></p>
					</footer>
				</div>
				<div class="column">
					<h3>Pro</h3>
					<div class="expand"><span class="text-inf">Expand details</span><span class="fa  fa-chevron-down"></span></div>
					<ul>
						<li class="coming red">
							COMING SOON!	
						</li>
					</ul>
					<?php /*$nxt ='<ul>
						<li class="upper red">All of the advanced plan <br/> plus:</li>
						<li>Max file sieze: 250 mb</li>
						<li>Max px width: 150 000 px</li>
						<li>FTP pano upload</li>
						<li>Multiresolution panos</li>
						<li>Hire a Pro button</li>
						<li>No adds in your tours pages <br><span class="small">Or put your your own adds</span></li>
						<li>Custom hotspots styles</li>
						<li>Custom Maps widget</li>
						<li>Portfolio widget</li>
						<li>Back up your original files <br><span class="small">Download zip</span></li>
						<li class="tooltipped">
							PRO plugins pack <i class="fa fa-search-plus"></i>
							<dl>
								<dt>PRO plugins pack:</dt>
								<dd><span class="fa fa-plug"></span>Scene info panel</dd>
								<dd><span class="fa fa-plug"></span>Menu with categories</dd>
								<dd><span class="fa fa-plug"></span>Video</dd>
								<dd><span class="fa fa-plug"></span>Photo gallery</dd>
								<dd><span class="fa fa-plug"></span>Map</dd>
								<dd><span class="fa fa-plug"></span>Floorplans</dd>
							</dl>
						</li>
						<li>PRO Layouts pack <br><span class="small">5 customizable interface layouts</span></li>
						
					</ul>'*/?>
					
					<footer>
					<?php /* PARA CUANDO SE HABILITE PRO ************************************************************************************************************************************
					
						<?php 
						if($logged != 1){
							$current = '<a href="">Get Pro</a>';
						}else{
							if($level == 'PRO'){
								$current = 'Current';
							}else{
								$current = '<a href="account/checkout?nl=2">Get Pro</a>';
							}
						}?>						
						<p class="prize">$<?php echo $pro_yearly_rate;?> / year<br><span class="small">($<?php echo $pro_yearly_rate/12;?>/mo)</span></p>
						<p class="position coming-soon upper single-line <?php if($logged != 1){echo "register";}?>" <?php if($logged != 1){echo 'data-level="2"';}?>><?php echo $current;?></p>
					
					
					*/?>
					<p class="prize">---</p>
					<p class="position coming-soon upper single-line">Coming soon</p>
					</footer>
				</div>
				<div class="column">
					<h3>Company</h3>
					<div class="expand"><span class="text-inf">Expand details</span> <span class="fa  fa-chevron-down"></span></div>
					<ul>
						<li class="coming red">
							COMING SOON!	
						</li>
					</ul>
					<?php /*$nxt ='<ul>
						<li class="upper red">All of the Pro plan <br/> plus:</li>
						<li>Unlimited Customer accounts</li>
						<li>10 Editor accounts</li>
					</ul>';*/?>
					<footer>
						<p class="prize">---</p>
						<p class="position coming-soon upper single-line">Coming soon</p>
					</footer>
				</div>			
			</div>
		</div>
	<input type="hidden" id="sel-level" value="">
	<?php require_once("inc/footer.php");?>
</html>