<?
require("inc/conex.inc");
require("inc/login.inc");

//Check user

if(isset($_GET["h"]) && $_GET["h"] != ''){
		
	$ssqlp = "SELECT *, now() as ahora, TIMEDIFF(now(),sol_date) as diferencia FROM users where hashregistro = '".$_GET["h"]."' and confirm_sol = 1";

	//echo $ssqlp;
	
	$result = mysql_query($ssqlp);	

	if($row = mysql_fetch_array($result)){
		
		//CHEQUEAR SI PASARON 48 HS
		$dif = current(explode(':', $row["diferencia"]));
		if($dif < 48){
			//TODO BIEN, DOY DE ALTA Y LOGEO AL USUARIO
			mysql_query("update users set status = 1, confirm_sol = 0 where hashregistro = '".$_GET["h"]."'");
			
			login($row["hashregistro"]);
			
			?>

				<!DOCTYPE HTML>
				<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						        
				        <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
						        
						        
						<link href='favicon.png' rel='shortcut icon' type='image/x-icon'/>
						<link href='favicon.png' rel='icon' type='image/x-icon'/>
						<title>Spinattic</title>

					    <!--google font-->

						<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
				        <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>

						<!-- css main -->        
					    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
					    <!-- jquery -->  
					    
						<!-- start Mixpanel -->
						<script type="text/javascript">
						<?php if($environment=='dev'){?>
						(function(f,b){if(!b.__SV){var a,e,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");
						for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=f.createElement("script");a.type="text/javascript";a.async=!0;a.src="//cdn.mxpnl.com/libs/mixpanel-2.2.min.js";e=f.getElementsByTagName("script")[0];e.parentNode.insertBefore(a,e)}})(document,window.mixpanel||[]);
						mixpanel.init("455e2a7a2ec17d504d8b7dd0acbb6eb2");
						<?php }
						if($environment=='prod'){?>
							(function(f,b){if(!b.__SV){var a,e,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");
							for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=f.createElement("script");a.type="text/javascript";a.async=!0;a.src="//cdn.mxpnl.com/libs/mixpanel-2.2.min.js";e=f.getElementsByTagName("script")[0];e.parentNode.insertBefore(a,e)}})(document,window.mixpanel||[]);
							mixpanel.init("634919fa9e66f0ceb9df46b4070cc0dc");
						<?php }?>

							mixpanel.identify("<?php echo $_SESSION["usr"];?>");

							mixpanel.people.set({
							    "$email": "<?php echo $_SESSION["email"];?>",
							    
							    "$name": "<?php echo $_SESSION["username"];?>"
							});
														
						</script><!-- end Mixpanel -->
											    
					    <script  type="text/javascript">
						 mixpanel.track("New user from Register Form");
					    </script>  
					    
						
						<?php if($environment=='prod'){
							//require_once("inc/fb_pixels.inc");
						}?>							    

					</head>
					<body>

						<div class="page_centered">
							<div class="logo2"></div>
							<div class="content_box">
								<h3><span>Welcome, <?echo $_SESSION['username'];?></span><br>your account is now activated! </h1>
								<?php if($row["fb_name"] != ''){echo '<h4><U>ATTENTION</U>: Looks like you used Facebook credentials to login in the past. You can now also login using your email and the password you just created.</h2>';}?>
								<?php if($row["fb_name"] == '' && $row["gp_name"] != ''){echo '<h4><U>ATTENTION</U>: Looks like you used Google+ credentials to login in the past. You can now also login using your email and the password you just created.</h2>';}?>
								<h4>Let's start spinning</h2>
									<div class="buttons_container">
										<a href="user_profile.php" class="welcome_button purple_btn">
											<img src="images/icons/icon_user.png" /><br>Edit your profile info
										</a>
										<a href="edit_virtual_tour.php" class="welcome_button green_btn">
											<img src="images/icons/icon_upload.png" /><br>Upload your panoramas and create your first virtual tour
										</a>
										<a href="home.php" class="welcome_button blue_btn">
											<img src="images/icons/icon_home.png" /><br>Great! but just take me to my home page
										</a>
									</div>
									<div class="list_container">
										<h5>Remember! In Spinattic you can:</h5>
										<ul>
											<li>Create and customize your virtual tours</li>
											<li>Store your panoramas and access them from other apps (coming soon)</li>
											<li>Get an embed code of your virtual tour to use in your personal website</li>
											<li>Follow and be followed by other Spinattic users</li>
											<li>Get updates on the users you follow</li>
											<li>Share your tours with Spinattic and other social networks, or keep them for yourself with Spinattic's privacy settings</li>
											<li>Give and get feedback on your virtual tours</li>

													
										</ul>
									</div>
											
							</div>

						</div>

					</body>
				</html>

		<?}else{
		//PASARON 48 HS?>
		
			<!DOCTYPE HTML>
			<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			        
			        <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
			        
			        
					<link href='favicon.png' rel='shortcut icon' type='image/x-icon'/>
					<link href='favicon.png' rel='icon' type='image/x-icon'/>
					<title>Spinattic</title>

				    <!--google font-->

					<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
			        <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>

					<!-- css main -->        
				    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
					<!-- jquery -->    
		
  
					<script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>	
						    
					<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
					<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
					<!-- jquery ui -->        
					<!-- script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script-->
					<script src="js/jquery-ui.js"></script>
        

					<script src="js/custom.js"></script>
				</head>
				<body>
					<input type="hidden" id="h" value="<?echo $_GET["h"]?>">
					<div class="page_centered">
						<div class="logo2"></div>
						<div class="content_box">
							<h3>The activation link expired! </h1>
							<h4>More than 48hs passed since you signed in.</h2>
							<span id="respta"></span>
							<div style="margin-top:50px;"><button id="resend">Re-send link and try again</button></div>
						</div>

					</div>

				</body>
			</html>
		
		<?}
		
	}else{
		
		header('Location: index.php');
	
	}
}else{
	header('Location: index.php');
}

?>