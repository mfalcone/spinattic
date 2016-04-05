<?
	require("../inc/conex.inc");

$lastID = $_GET["lastID"];
$action = $_GET["action"];

$order = $_GET["o"];				
	
if($order == ''){$order = 'id';};


if ($action != 'getLastPosts') {
 


	$ip = $_SERVER['REMOTE_ADDR'];
	$id = $_GET["id"];

	if ($id !=''){
		$ssqlp = "SELECT *,DATE_FORMAT(date,'%d/%m/%Y') as fecha FROM tours where id = ".$id;
		$result = mysql_query($ssqlp);
				
		$row = mysql_fetch_array($result);

		$views = $row["views"];
		$likes = $row["likes"];
		$url = 'http://'.$row["friendly_url"];
		$title = $row["title"];
		$description = $row["description"];
		$lat = $row["lat"];
		$lon = $row["lon"];
		$location = $row["location"];
		$loc_country = $row["loc_country"];
		$date = $row["fecha"];

//User Data

		$ssqlu = "SELECT * FROM users where id = ".$row["iduser"];
		$resultu = mysql_query($ssqlu);
		$rowu = mysql_fetch_array($resultu);
	
		$user = $rowu["username"];
		$iduser = $rowu["id"];
		$website = $rowu["website"];
		$facebook = $rowu["facebook"];
		$twitter  = $rowu["twitter"];	
	
		$ssqlp = "SELECT * FROM views where idtour = ".$id." and ip like '%".$ip."%' and date > DATE_SUB(now(),INTERVAL 72 HOUR)";
	
		$result = mysql_query($ssqlp);
			
		if(!($row = mysql_fetch_array($result))){
			mysql_query("update tours set views = views + 1 where id = ".$id);
			mysql_query("insert into views values (".$id.",' ".$ip."', now())");
			$views++;
		}
		
					
		
	}else{
		header("Location: index.php");
	}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
		<meta name="viewport" content="initial-scale = 1.0,maximum-scale = 1.0" />       

		<link href='favicon.png' rel='shortcut icon' type='image/x-icon'/>
		<link href='favicon.png' rel='icon' type='image/x-icon'/>
		<title>Spinattic</title>

	    <!--google font-->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>

		<!-- css main -->        
	    <link rel="stylesheet" type="text/css" media="screen" href="../css/mobile.css" />
	    <!-- jquery -->    
	    <script type="text/javascript" src="../js/jquery-1.9.1.min.js"></script>
	    <script type="text/javascript" src="../js/jquery.easing.1.3.js"></script>
	    <!-- jquery ui -->        
	    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
		<!-- google map -->
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
       <script type="text/javascript" src="../js/infobox.js"></script>
		<script type="text/javascript" src="../js/mapa_mobile.js"></script>
		<!-- custom -->		                
	    <script src="../js/custom_mobile.js"></script>
		<script src="../js/ajaxlike.js"></script>


	<script type="text/javascript">

	$(document).ready(function(){
		
		$('form#mainForm').bind('submit', function(e){
			e.preventDefault();
			checkForm();
		});
		
		$('input#hostName').focus();
	
		
		function lastPostFunc() 
		{ 
		
			$.post("tour.php?o=<?echo $order;?>&action=getLastPosts&lastID="+$(".post:last").attr("id"),
	
			function(data){
				if (data != "") {
				$(".post:last").after(data);			
				document.getElementById("loading").style.display="none";
				setupBlocks();						   				
				}
			});
 		};  
		
		$(window).scroll(function(){
			if  ($(window).scrollTop() == $(document).height() - $(window).height()){
				document.getElementById("loading").style.display="block";
			   lastPostFunc();
			}
		}); 
		
	});
	


	</script>

	    <script>
        	$(document).ready(function(){
				initialize(17,'<?echo $lat;?>','<?echo $lon;?>');

<?		$ssqlpmap = "SELECT * FROM tours ORDER BY ".$order." desc";
		$resultmap = mysql_query($ssqlpmap);	
		
		while ($rowmap = mysql_fetch_array($resultmap)){
		$open = 'false';
		if($rowmap["id"] == $id)$open='true';
		?>

				addMarker('<?echo $rowmap["id"];?>', '<?echo $rowmap["lat"];?>', '<?echo $rowmap["lon"];?>', '<?echo $rowmap["user"];?>', '../images/users/<?echo $rowmap["iduser"];?>.jpg', '../images/tours/<?echo $rowmap["id"];?>.jpg', '<?echo $rowmap["title"];?>', <?echo $rowmap["views"];?>, <?echo $rowmap["likes"];?>, '', <?echo $open;?>);

<?}?>
			});
        </script>
	    
	    <script type="text/javascript">
		function analiza(evt)
		      {
		         var charCode = (evt.which) ? evt.which : event.keyCode
		         if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46)
		            return false;
		         
				return true;
		      }

		function embed(evt){
	        document.getElementById('inp_embed').value = '<iframe width="' +  document.getElementById('w_embed').value + '" height="' + document.getElementById('h_embed').value + '" src="<?echo $url;?>" frameborder="0" allowfullscreen></iframe>';
		}


		function copyToClipboard (text) {

			document.getElementById('inp_embed').select();
			window.clipboardData.setData(text,str);
//			window.prompt ("Copy to clipboard: Ctrl+C, Enter", text);
		}
		

	    </script>	    
	</head>
	<body>
		<div id="loading" class="loading" style="display: none;">
	        <div class="loading_img"></div>
        </div>
	


        <!--popup login-->
        <div class="overlay_mobile forgotpassword">
            <div class="pop_mobile">
                <a href="#" class="closed closed_register_mobile"></a>
                <h2>Forgot Password</h2>
                <p class="message">Don't worry, we'll send an email with instructions to access your account. Unless you <a href="#">remember</a>?</p>
                <form>
                    <input type="text" value="Username or E-mail" class="name">
                    <a href="#" class="login">Send</a>
                </form>

            </div>
        </div> 

		<!--COMENTADO-->
        <!--popup login-->
        <!--div class="overlay_mobile login_mobile">
            <div class="pop_mobile">
                <a href="#" class="closed closed_register_mobile"></a>
                <h2>Log in</h2>
                <a href="#" class="facebook"></a>
                <a href="#" class="twitter"></a>
                <form>
                    <input type="text" value="Username" class="name">
                    <input type="text" value="Password" class="pass">
                    <a href="#" class="login">Log in</a>
                    
                    <label for="remember" class="terminos">
                        <input type="checkbox" id="remember">
                            <a href="#">Remember me - <font>Forgot password?</font></a>
                        <br clear="all">
                    </label>
                </form>

            </div>
        </div-->    

		<!--COMENTADO-->
        <!--popup register-->
        <!--div class="overlay_mobile register_mobile">
            <div class="pop_mobile">
                <a href="#" class="closed closed_register_mobile"></a>
                <h2>Register</h2>
                <a href="#" class="facebook"></a>
                <a href="#" class="twitter"></a>
                <form>
                    <input type="text" value="Full Name" class="name">
                    <input type="text" value="E-mail" class="mail">
                    <input type="text" value="Password" class="pass">
                    <a href="#" class="register">Register</a>
                    
                    <label for="terminos" class="terminos">
                        <input type="checkbox" id="terminos">
                    
                        <p>I have read and agree to the</p>
                        <a href="#">Terms of Service Spinattic</a>

                        <br clear="all">
                    </label>
                </form>
            </div>
        </div-->


        <header class="header">
            <a href="#" class="spinattic"><img src="../images/spinattic.png"></a>
            <a href="#" class="spinattic spinattic_mobile"><img src="../images/spinattic_mobile.png"></a>
            <a href="#" class="nav_btn_mobile"></a>
            <a href="#" class="login_btn_mobile"></a>

            <br clear="all">
        </header>
        <div class="nav nav_panel">
            <div class="search_mobile">
				<?php require 'inc/head.inc';?>
            </div>
            <!--div class="login-register">
                <div class="or"></div>
                <a href="#" class="login">Log in</a>
                <a href="#" class="register">Register</a>
                <br clear="all">
            </div-->
            
            <div class="user_login">
                <a href="#" class="user_image"><img src="../images/ejemplo/user.jpg"></a>
                <div class="user_name">
                    <a href="#"><p>Derrick Clark</p></a>
                    <div class="action_user">
                        <a href="#">View profile </a>/
                        <a href="#"><strong> Logout</strong></a>
                        <br clear="all">
                    </div>
                </div>
                <br clear="all">
            </div>
            
            <div class="content_nav">                        
				<?php require 'inc/nav.inc';?>
	            <div class="privacy">
	                <div class="social_media">
	                <a href="#" class="facebook"></a>
	                <a href="#" class="twitter"></a>
	                <a href="#" class="googleplus"></a>
	                <br clear="all">
	                </div>
	                <p>
	                    <a href="#">Terms of Use</a> | <a href="#">Privacy Policy</a>
	                    <br>
	                    © All panoramas are property 
	                    <br>
	                    of their authors
	                </p>
	            </div>
            </div>
        </div>        
        
        
        
		        <div class="tour">
                	<div class="nav">
                    	<a href="#" class="btn_map"></a>
                        <a href="#" class="btn_embed"></a>
                        <a href="#" class="btn_share"></a>
                        <br clear="all">
                    </div>
					<!--embed-->
	                <div class="embed">
	                    <p class="embed_title"><strong>Embed</strong></p>

                        <p class="info-embed">
                        	Copy and Paste this code in your page to embed this virtual tour.
						</p>


                      <input class="input_url" value='<iframe width="420" height="315" src="<?echo $url;?>" frameborder="0" allowfullscreen></iframe>'>
                    
                      <div class="size_embed">
	                      <label>width:<input type="text" value="640"></label>
	                      <label>height:<input type="text" value="480"></label>
                          <input value="copy" type="button" class="btn_copy">
                      </div>
                    </div>
					<!--share-->
	                <div class="share">
                    	<a href="#" class="twitter"></a>
                        <a href="#" class="facebook"></a>
                        <a href="#" class="plus"></a>
                        <a href="#" class="pinterest"></a>
                        <a href="#" class="mail"></a>
                        <br clear="all">
                    </div>
        
                    <div class="content_map" id="map_canvas"></div>
	                <div class="content_tour">
	                	<iframe src="<?echo $url;?>"></iframe>
                    	<!--  <div class="play"></div>
                         <img src="../images/ejemplo/higthlight.jpg">
                         -->
					</div>
                </div>
                <div class="social-media">
	                <a href="javascript:void(0)" id="like<?echo $id;?>" class="likes" onclick="like(<?echo $id;?>);"><?echo $likes;?></a>
	                <div class="views"><?echo $views;?></div>
                    <br clear="all">
                </div>

                <div class="modulo_user">
                	<div class="user_tour">
                        <a href="#" class="photo_user">
                            <img src="../images/ejemplo/user_example.jpg">
                        </a>
                        <div class="name_user">                    
                            <a href="#">
                                <p>
                                    Chaluntorn <br>
                                    Preeyasombat
                                </p>
                            </a>
                            <div class="pro">Pro</div>
                        </div>
                        <br clear="all">
                    </div>
                    <?if($website != ''){?><a href="<?echo 'http://'.$website?>" class="url"><?echo $website?></a><?}?>
                    <?if($twitter != ''){?><a href="<?echo 'http://'.$twitter?>" class="twitter"><?echo $twitter?></a><?}?>
                    <?if($facebook != ''){?><a href="<?echo 'http://'.$facebook?>" class="facebook"><?echo $facebook?></a><?}?>
                    
                    <a href="#" class="follow">Follow</a>
                    

<?
		$ssqlot = "SELECT *, DATE_FORMAT(date,'%d/%m/%Y') as fecha FROM tours where id <> ".$id." and iduser = ".$iduser." ORDER BY id desc LIMIT 3";
		$resultot = mysql_query($ssqlot);	
		$cantregot = mysql_num_rows($resultot);
		
		if($cantregot > 0){?>		
                    
                    <h3>Others Tours</h3>
<?
		while ($rowot = mysql_fetch_array($resultot)){
?>

                    <div class="other">
	                    <a href="tour.php?id=<?echo $rowot["id"];?>"><p><?echo $rowot["description"];?></p></a>
	                    <a href="tour.php?id=<?echo $rowot["id"];?>" class="thumb"><img src="../images/tours/<?echo $rowot["id"];?>.jpg"></a>
	                    <div class="date"><?echo $rowot["fecha"];?></div>
	                    <p class="other-location">
                        	<a href="search.php?search=<?echo $rowot["location"];?>"><?echo $rowot["location"];?></a>
                            <br clear="all">
                        </p>
                        <br clear="all">
                    </div>

                    
<?}?>
                    <h3><a href="#">View all</a></h3>

<?}?>

                </div>
                
               <div class="titulo_tour">
                	<h2><?echo $title;?></h2>
                	<p style="margin-bottom:10px;"><?echo $description;?></p>

                    <div class="date"><?echo $date;?></div>
                    <p class="location">
							                    
							<?
							$pieces = explode(",", $location);
							foreach($pieces as $piece => $value){?>
                        	<a href="search.php?search=<?echo trim($value);?>">
                        		<?echo $value;?>
                        	</a><font>,</font>
							 <?}?>                    
                    
                    </p>
                    <br clear="all">
                </div>

                <div class="coments_facebook">
                	<!--img src="../images/ejemplo/facebook.png"-->
                </div>
                <br clear="all">


                
                
                <div class="filter">
		            <ul>
		                <li><a href="tour.php?id=<?echo $id;?>&o=date#filter" <?if ($order=="date"){echo 'class="active"';}?>>New</a></li>
		                <li><a href="tour.php?id=<?echo $id;?>&o=likes#filter" <?if ($order=="likes"){echo 'class="active"';}?>>Top rated</a></li>
		                <li><a href="tour.php?id=<?echo $id;?>&o=views#filter" <?if ($order=="views"){echo 'class="active"';}?>>Popular</a></li>
		            </ul>
                </div>
		</div>
        <div class="wrapper">
               
                
<?
		$i = 0;
		$ssqlp = "SELECT * FROM tours ORDER BY ".$order." desc";
		$result = mysql_query($ssqlp);	
		
		while ($row = mysql_fetch_array($result)){
		$i++;
		if ($i >=17){break;}
?>			
        
	        		<div class="post" id="<?echo $i;?>">
						<div class="thumb"><a href="tour.php?id=<?echo $row["id"];?>"><img src="../images/tours/<?echo $row["id"];?>.jpg"></a></div>                    
							<a href="tour.php?id=<?echo $row["id"];?>" class="user"><img src="../images/users/<?echo $row["iduser"];?>.jpg" width="43" height="43"></a>
						<div class="by"><a href="#">by <?echo $row["user"];?></a></div>
					    <a href="tour.php?id=<?echo $row["id"];?>" class="text">
					    	<p>
								<?echo $row["title"];?>
					        </p>
					    </a>
						<div class="count">
					    	<div class="views"><?echo $row["views"];?></div>
					    	<!-- 
					    	<a href="#" class="comments"><?echo $row["comments"];?></a>
					    	-->   
				    		<a href="javascript:void(0)" id="like<?echo $row["id"];?>" class="likes" onclick="like(<?echo $row["id"];?>);"><?echo $row["likes"];?></a>
					        <br clear="all">
					    </div>
			    
					</div>
			
          
		<?}?>
		        
				<br clear="all">
        </div>
	</body>
</html>



<?
}else{
	$getPostsText = "";

	$ssqlp = 'SELECT * FROM tours ORDER BY '.$order.' DESC';
	$result = mysql_query($ssqlp);	
	
	$i = 1;
	
	while ($row = mysql_fetch_array($result)){
		if ($i >= $lastID){break;};
		$i++;
	}

	$j=1;
		
	while ($row = mysql_fetch_array($result)){
		if ($j >= 5){break;};
		$j++;
		$i++;


		$getPostsText .= '<div class="post" id="'.$i.'">
                	<div class="thumb"><a href="tour.php?id='.$row["id"].'"><img src="../images/tours/'.$row["id"].'.jpg"></a></div>                    
                	<a href="tour.php?id='.$row["id"].'" class="user"><img src="../images/users/'.$row["iduser"].'.jpg" width="43" height="43"></a>
					<div class="by"><a href="#">by '.$row["user"].'</a></div>
                    <a href="tour.php?id='.$row["id"].'" class="text">
                    	<p>
							'.$row["title"].'
                        </p>
                    </a>
					<div class="count">
                    	<div class="views">'.$row["views"].'</div>
                    		<!-- 
                    	<a href="#"  class="comments">'.$row["comments"].'</a>
                    		-->        
						<a href="javascript:void(0)" id="like'.$row["id"].'" class="likes" onclick="like('.$row["id"].');">'.$row["likes"].'</a>                    		
                        <br clear="all">
                        </div>
                   </div>';
	}
	echo $getPostsText; //Writes The Result Of The Query
	//When User Scrolls This Query Is Run End
}
?>
