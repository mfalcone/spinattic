<?
  
	$restrict = 1;
	$page_title = "Spinattic | Pricing";
	require("inc/header.php");
	?>	
	
	<link rel="stylesheet" type="text/css" href="css/pricing.css">
	<?php if (isset($_GET['check'])) { ?>
	    <script type="text/javascript">

	    	

jQuery.fn.extend({
        toggleText: function (a, b){
            var isClicked = false;
            var that = this;
            this.click(function (){
                if (isClicked) { that.text(a); isClicked = false; }
                else { that.text(b); isClicked = true; }
            });
            return this;
        }
    });

	    	$(document).ready(function(){
			    	$(".expand").show();
			    	$(".column ul").hide();
			    	$(".expand").click(function(e){

			    		$(".column ul").slideToggle();
			    		$(".expand").each(function(ind,ele){
			    			if($(ele).find(".text-inf").text()=='Expand details'){
			    				console.log("se da la cond a")
			    				$(ele).find(".text-inf").text('Collapse details')
			    			}else{
			    					console.log("se da la cond b")
			    				$(ele).find(".text-inf").text('Expand details')
			    			}
			    			$(ele).find(".fa").toggleClass("fa-chevron-down fa-chevron-up")
			    		})
			    	});

	    	})
	    </script>
	<?php } ?>

		<div>
			<h1 class="manage_acount_header">Pricing</h1>
		</div>
		<hr class="space180px">
		<div class="wrapper pricing">
			<h3>Everything your business need: <br>Choose your plan and start creating, customizing and sharing amazing Virtual Tours.</h3>
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
								<dd><span class="fa fa-plug"></span>Social buttons</dd>
							</dl>
						</li>
						<li>WebVR Plugin</li>
						<li>Gyroscope plugin</li>
					</ul>
					<footer>
						<p class="prize upper single-line">Free</p>
						<p class="position upper single-line">Current</p>
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
						<li>Set max tile size</li>
						<li>Mouse control settings</li>
						<li>Autorrotation settings</li>
						<li class="tooltipped">
							Advanced plugins pack <i class="fa fa-search-plus"></i>
							<dl>
								<dt>ADVANCED plugins pack:</dt>
								<dd><span class="fa fa-plug"></span>Tour info panel</dd>
								<dd><span class="fa fa-plug"></span>Logo</dd>
								<dd><span class="fa fa-plug"></span>Signature (add your own)</dd>
								<dd><span class="fa fa-plug"></span>Audio</dd>
								<dd><span class="fa fa-plug"></span>Link button</dd>
								<dd><span class="fa fa-plug"></span>Context menu</dd>
								<dd><span class="fa fa-plug"></span>Nadir Patch</dd>
								<dd><span class="fa fa-plug"></span>Custom loader bar</dd>
							</dl>
						</li>
						<li>
							Real Estate pack: <br>
							<span class="small">3 customizable interface layouts dedicated to Real estate business.</span>
						</li>
						<li>Remove Spinattic Signature</li>
					</ul>
					<footer>
						<p class="prize">$120 / year<br><span class="small">($10/mo)</span></p>
						<p class="position upper single-line"><a href="#">Get Advanced</a></p>
					</footer>
				</div>
				<div class="column">
					<h3>Pro</h3>
					<div class="expand"><span class="text-inf">Expand details</span><span class="fa  fa-chevron-down"></span></div>
					<ul>
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
						
					</ul>
					<footer>
						<p class="prize">$240 / year<br><span class="small">($20/mo)</span></p>
						<p class="position coming-soon upper single-line">Coming soon</p>
					</footer>
				</div>
				<div class="column">
					<h3>Company</h3>
					<div class="expand"><span class="text-inf">Expand details</span> <span class="fa  fa-chevron-down"></span></div>
					<ul>
						<li class="upper red">All of the Pro plan <br/> plus:</li>
						<li>Unlimited Customer accounts</li>
						<li>10 Editor accounts</li>
					</ul>
					<footer>
						<p class="prize">$360 / year<br><span class="small">($30/mo)</span></p>
						<p class="position coming-soon upper single-line">Coming soon</p>
					</footer>
				</div>			
			</div>
		</div>

	<?php require_once("inc/footer.php");?>
</html>