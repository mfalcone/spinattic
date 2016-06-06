<?

//Detect if mobile
require_once realpath($_SERVER["DOCUMENT_ROOT"]).'/php-stubs/Mobile_Detect.php';
$detect = new Mobile_Detect;

if($detect->isMobile()) {
	header('Location:'.$http.$_SERVER[HTTP_HOST].'/mobile/');
	exit;
}


require_once("inc/auth.inc");
require_once("inc/conex.inc");
require_once("inc/functions.inc");


if(!isset($page_title)){
	$page_title = "Spinattic | 360 Virtual Tours. Create Customize and Share ";
}

if(!isset($description_head)){
	$description_head = "Create, customize and share virtual tours with 360 panoramas. Online Software to customize 360 Virtual tours with tour navigation and virtual tour plugins.";
}	
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="<? echo $description_head; ?>">  
		<?require("inc/fk-meta.inc");?>

        
		<link rel="shortcut icon" href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/images/favicon.ico" type="image/x-icon">
		<title><? echo $page_title; ?></title>

	    <!--google font-->

		<link href='<?echo $http;?>fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <link href='<?echo $http;?>fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
		<!-- custom scrollbar -->
		<link rel="stylesheet" type="text/css" href="<?echo $http.$_SERVER[HTTP_HOST]."/";?>css/jquery.mCustomScrollbar.css?r=<?echo $ver;?>" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?echo $http.$_SERVER[HTTP_HOST]."/";?>css/font-awesome.min.css" />
	    
		<!-- css main -->        
	    <link rel="stylesheet" type="text/css" media="screen" href="<?echo $http.$_SERVER[HTTP_HOST]."/";?>css/main.css?r=<?echo $ver;?>" />

	    <!-- jquery -->    
		    
	    <script type="text/javascript" src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>js/jquery-1.9.1.min.js"></script>
	    <script type="text/javascript" src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>js/jquery.easing.1.3.js"></script>
	    <!--custom scroll bar-->
	    <script type="text/javascript" src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>js/jquery.mCustomScrollbar.concat.min.js"></script>
	    <!-- jquery ui -->        
	    <!-- script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script-->
	    <script src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>js/jquery-ui.js"></script>
        
	    <script src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>js/custom.js?r=<?echo $ver;?>"></script>
		<script src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>js/core-utils.js?r=<?echo $ver;?>"></script>
		<script src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>js/ajaxlike.js?r=<?echo $ver;?>"></script>
		<script src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>js/ajaxscrollnotif.js?r=<?echo $ver;?>"></script>
		
		
		<?php if($restrict == 1){?>
		<script src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>js/check_session.js?r=<?echo $ver;?>"></script>
		<?php }?>
	

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
			
			<?php if($environment=='prod'){
				require_once("inc/ganalitycs.inc");
			}?>	
	
	
	</head>
<body>
	<input type="hidden" value="<?php echo $cdn;?>" id="cdn">
    <input type="hidden" value="" id="regban">
	<script src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>player/tour.js"></script>
	<div id="loading" class="loading" style="display: none;">
	        <div class="loading_img"></div>
    </div>  

        
    	<header class="header">
			<?php $search       = isset($_REQUEST["search"])? $_REQUEST["search"] : ''; // inicializar correctamente ?>

            <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>" class="spinattic"><img src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>images/spinattic.png" alt="Spinattic 360 virtual tours" ></a>
            <!-- div class="language">
                <a href="#" class="language_btn">
                    <div class="icon"></div>
                    <font>ENG</font>
                </a>
                <ul class="select_language">
                    <li><a href="#">English</a></li>
                    <li><a href="#">EspaÃ±ol</a></li>
                    <li><a href="#">PortuguÃ©s</a></li>
                </ul>
            </div-->
            <div class="search">
            <?if ($search != ''){
                $valors = $search;
            }else{
                $valors = 'Search';
            }?>
                <div class="bg_search">
                <form action="<?echo $http.$_SERVER[HTTP_HOST]."/search.php";?>" name="formsearch" method="GET">
                    <input type="text" value="<?echo  htmlspecialchars($valors);?>" id="search-input" onclick="if(this.value == 'Search'){this.value=''};" onfocus="this.select()" onblur="this.value=!this.value?'Search':this.value;" name="search">
                    <!--a href="#" class="clear_input"></a--><!-- CRUZ PARA LIMPIAR BUSQUEDA-->
                    <a href="#" class="glass_input" onclick="if (document.formsearch.search.value != '' && document.formsearch.search.value != 'Search'){document.formsearch.submit();};"></a><!-- LUPA DE BUSQUEDA-->
            <input type="hidden" value="" name="order" id="the_order">
            <input type="hidden" value="" name="c" id="the_cat">
                </form>
                </div>                
            </div>
        </header>


        <div class="nav">
        <input type="hidden" value="<?php echo $_SESSION["usr"];?>" id="s_h">
			<script src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>js/fb.js?r=<?echo $ver;?>"></script>
			<script src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>js/gp.js?r=<?echo $ver;?>"></script>
	        <!--popup login-->
	        <div class="overlay forgotpassword">
	            <div class="pop">
	                <a href="#" class="closed closed_register_mobile"></a>
	                <h2>Forgot Password</h2>
	                <div class="content_pop">
	                    <p class="message">Don't worry, we'll send an email with instructions to access your account.</p>
	                    <form>
	    				<span id="reset_pop"></span>  
	                    <input type="text" placeholder="E-mail" class="mail" id="reset_email">
	                    <a href="#" class="login reset_pwd">Send</a>
	                    </form>
	                </div>
	            </div>
	        </div> 
	        <div class="overlay overlay_login">
	            <div class="pop">
	                <a href="#" class="closed closed_register_mobile"></a>
	                <h2>Log in</h2>
	                <div class="content_pop">
	                <div class="data_pop">
	                    <p class="message">I don't have an account yet and <a href="#" class="loginToRegister" >I want to register</a></p>
	                    <a href="#" class="fb-bt"></a>
	                   	<a href="#" class="gplus-bt"></a>
	    				<span id="login_pop"></span>  
	                        <input type="text" placeholder="E-Mail or User Name" class="name" id="user">
	                        <input type="password" placeholder="Password" class="pass" id="pass">
	                        <a href="#" class="login loginaction">Log in</a>
	                        <div class="terms">
	                            <input type="checkbox" id="remember">
	                                <p>Remember me - <a href="#" class="forgot_pwd">Forgot password?</a></p>
	                        </div>
	                </div>
	                </div>
	            </div>
	        </div>         
	        <!--popup register-->
			<div class="overlay overlay_register">
	        	<div class="pop">
	                <a href="#" class="closed closed_register_mobile"></a>
	                <h2>Register</h2>
					<div class="content_pop">
	                
						<div class="data_pop">
						<p class="message">Register using your preferred social network:</p>
			                    
			                    <a href="#" class="fb-bt"></a>
			                    <a href="#" class="gplus-bt"></a>
		                        <p class="message">Or register with your email address:</p>
		    					<span id="register_pop"></span>
		                        <input type="text" placeholder="Full Name" class="name" id="r_name">
		                        <div class="validate hide"></div>
		                        <input type="text" placeholder="User Name" class="name" id="r_nickname">
		                        <div class="validate hide"></div>
		                        <input type="text" placeholder="E-mail" class="mail" id="r_email">
		                        <div class="validate hide"></div>
		                        <input type="text" placeholder="Repeat E-mail" class="mail" id="r_email1">
		                        <div class="validate hide"></div>
		                        <input type="password" placeholder="Password" class="pass" id="r_pass">
		                        <div class="validate hide"></div>
		                        <input type="password" placeholder="Repeat Password" class="pass" id="r_pass1">
		                        <div class="validate hide"></div>

		                        <a href="#" class="register registeraction">Register</a>
		                        
		                        <div class="terms">
		    	                    <input type="checkbox" id="terminos">
		    		                <p>
		                                I have read and agree to Spinattic's <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/terms.php">Terms of Service</a>.
		                            </p>
		    				    </div>
		    				    <div class="terms">
		    	                    <input type="checkbox" id="receive-emails" checked="checked">
		    		                <p>
		                                I want to receive emails from Spinattic about new product releases and updates.
		                            </p>
		    				    </div>
			            </div>
	                </div>
				</div>
			</div>
			<div class="ballon hide"></div>


		    <div class="login-register" <?if ($logged == 1){echo 'style="display:none"';}?>>
		        <div class="or"></div>
	    	    <a href="#" class="login">Log in</a>
		        <a href="/pricing" class="register_style">Register</a>
	        </div>
	            


		        <div class="user_login" <?if ($logged == 1){echo 'style="display:block"';}?>>
		        <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/<?echo $_SESSION["friendly_url"];?>" class="user_image"><img id="nav_avatar" width="47" height="47" src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>images/users/<?echo $_SESSION["avatar"];?>" alt="<?echo $_SESSION["username"];?>"></a>
	            	<div class="user_name">
	                	<a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/<?echo $_SESSION["friendly_url"];?>"><p><?echo $_SESSION["username"];?></p></a>
						<div class="action_user">
	                		<a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/editprofile">Edit profile </a>/
	                    	<a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/lo"><strong> Logout</strong></a>
						</div>
						<?php 
						$my_level = ucfirst(strtolower((get_level($_SESSION["usr"]))));
						$level_class = "green";
						if($my_level == 'Advanced'){
							$level_class = "blue";
						};
						if($my_level == 'Pro'){
							$level_class = "red";
						};
						?>
						<a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/account" class="my_account">My Account: <span class="<?php echo $level_class;?>"><?php echo $my_level;?></span></a>
	                </div>
	            </div>

	            
	            
		        <div class="content_nav">

	            <ul class="menu">

				<?if ($logged == 1){?>
	                <li>
	                         <span class="btn-social">Social</span>
	                  <ul class="submenu">
	                    <li>
	                          <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/home">Home 
	                          <!-- <span class="contador">+99</span>-->
	                          </a>
	                          
	                          <a href="" id="notifications">Notifications
								<?
								session_start();
								$cuenta = 0;
								$ssqlp_nav = "SELECT count(*) as cuenta FROM notifications where leido = 0 and target_id = ".$_SESSION["usr"];
								$result_nav = mysql_query($ssqlp_nav);	
								$row_nav = mysql_fetch_array($result_nav);
								if($row_nav["cuenta"] > 0){
									$cuenta = $row_nav["cuenta"];
								?>
	                          
								<i class="campana"><span class="burbuja"><?echo abbr_number($cuenta);?></span></i>
								
								<?}?>
	                          
	                          </a>
	                          
	                          <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/<?php echo $_SESSION["friendly_url"];?>?followers">followers 

								<?
								session_start();
								$ssqlp_nav = "SELECT count(*) as cuenta FROM follows, users where id_following = ".$_SESSION["usr"]." and id_follower = users.id and users.status = 1";
								$result_nav = mysql_query($ssqlp_nav);	
								$row_nav = mysql_fetch_array($result_nav);
								$cuenta = $row_nav["cuenta"];
					
								?>
	                          
	                          <span class="counter" id="nav_followers"><?echo abbr_number($cuenta);?></span>

	                          </a>
	                          <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/<?php echo $_SESSION["friendly_url"];?>?following">following 
								<?
								session_start();
								$ssqlp_nav = "SELECT count(*) as cuenta FROM follows, users where id_follower = ".$_SESSION["usr"]." and id_following = users.id and users.status = 1";
								$result_nav = mysql_query($ssqlp_nav);	
								$row_nav = mysql_fetch_array($result_nav);
								$cuenta = $row_nav["cuenta"];
						
								?>                          
	                          		<span class="counter" id="nav_following"><?echo abbr_number($cuenta);?></span>
	                          
	                          </a>
	                    </li>
	                  </ul>
	                </li>
	                <li>
	                  <span class="btn-production">production</span>
	                  <ul class="submenu">
	                    <li>
	                          <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/customizer" target="_blank">create new tour <span class="newtourbt"></span></a>
	                          
	                          <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/tours">
	                          	Manage tours
	                          	<?php
	                          	$ssqlp_nav = "SELECT count(*) as cuenta FROM tours_draft where iduser = ".$_SESSION["usr"];
	                          	$result_nav = mysql_query($ssqlp_nav);
	                          	$row_nav = mysql_fetch_array($result_nav);
	                          	$cuenta = $row_nav["cuenta"];
	                          	?>
								<span class="counter" id="nav_followers"><?echo abbr_number($cuenta);?></span>
	                          </a>
	                          
	                          <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/panos">
	                          	Manage pano files
	                          	<?php
	                          	$ssqlp_nav = "SELECT count(*) as cuenta FROM panos where user = ".$_SESSION["usr"];	                          	
	                          	$result_nav = mysql_query($ssqlp_nav);
	                          	$row_nav = mysql_fetch_array($result_nav);
	                          	$cuenta = $row_nav["cuenta"];
	                          	?>
								<span class="counter" id="nav_followers"><?echo abbr_number($cuenta);?></span>
	                          </a>
	                    </li>
	                  </ul>
	                </li>
	                <?php if($_SESSION['admin'] == 1){?>
	                <li>
	                  <span class="btn-production">master admin</span>
	                  <ul class="submenu">
	                    <li>
	                    	  <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/edit_images.php">manage images</a>
	                    		
	                          <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/edit_post.php">create new post</a>
	                          
	                          <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/manager_posts.php">Manage posts</a>
	                          
	                          <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/edit_static_page.php">create new page</a>

	                          <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/manager_pages.php">Manage pages</a>

	                    </li>
	                  </ul>
	                </li>
	                <?php }?>
	<?}?>

	                <li>
	                    <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/index.php?explore" class="home-btn" title="360 virtual tours" >Explore</a>
	                    <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/leaderboard" class="leader-board" title="Panoramic photographers leader board" >Leader board</a>
	                    <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/worldmap" class="world-map" title="360 Virtual Tours World Map" >World Map</a>
	                    
	                        <div  class="acor">
	                            <a href="#" class="browse" >Browse <span class="acor"></span></a>
	                            <ul class="submenu menucategories">
	                                <li>
	                                    <?      $ssqlpcat = "SELECT * FROM categories ORDER BY category";
	                                            $resultcat = mysql_query($ssqlpcat);    
	                                            while($rowcat = mysql_fetch_array($resultcat)){?>
	                                    <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/search.php?c=1&amp;search=<?echo urlencode($rowcat["category"]);?>"><?echo $rowcat["category"];?></a>
	                                    <?}?>
	                                </li>
	                            </ul>
	                        </div>
						
	                </li>
	            </ul>
	            <div class="recommends">
	            	<h3>Spinattic recommends:</h3>
	            	<ul>
	            		<li>
	            			<a href="<?echo $http;?>www.spinattic.com/static-page.php?id=15" target="_blank">
	            				<img src="<?echo $http;?>//<?php echo $_SERVER[HTTP_HOST];?>/images/recommend/nodalninja_discount.jpg" alt="Nodal Ninja">
	            			</a>
	            		</li>
	            		<?php if($logged == 1){?>
	            		<li>
	            			<a href="<?echo $http;?>www.spinattic.com/blog-single-post.php?id=22" target="_blank">
	            				<img src="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/images/recommend/spinattic10.jpg" alt="GeoPress">
	            			</a>
	            		</li>
	            		<?php }?>	            		
	            	</ul>
	            </div>
				<div class="sponsors">
					<h3>Proud members and sponsors of</h3>
					<ul>
						<li><a href="http://ivrpa.org" class="ivrpa" target="_blank">ivrpa</a></li>
						<li><a href="http://www.panoramicassociation.org/" class="iapp" target="_blank">iaap</a></li>
					</ul>
				</div>
	            <div class="privacy">
	                <div class="social_media">
	                <a href="https://www.facebook.com/Spinattic" class="facebook" target="_blank"></a>
	                <a href="https://twitter.com/spinattic" class="twitter" target="_blank"></a>
	                <a href="https://plus.google.com/116755088127889937384" class="googleplus" target="_blank" rel="publisher"></a>
	                </div>
	                <p>
	                	<a href="http://<?php echo $_SERVER[HTTP_HOST];?>/pricing">Pricing</a><br>
	                	<a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/blog" title="Blog virtual tours" >Blog</a><br>
	                	<a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/faq">FAQs</a><br>
	                	<a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/about">About us</a><br>
	                    <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/terms">Terms of service</a> <br>
	                    <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/privacy">Privacy Policy</a><br>
	                    <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/refound">Refound Policy</a>
	                    <br>
	                    © All panoramas are property 
	                    <br>
	                    of their authors
	                </p>
	            </div>
            </div>
            <div id="notifications-wrapper" class="hide">
            	<div class="inner-notifications">
            	<ul id ="notif_list">

            	</ul>
            	<div class="loader" id="loader" style="display:none;"></div>
            	</div>
            </div>
        </div>
   
                