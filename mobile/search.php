<?

	require("../inc/conex.inc");
	
	if($_POST){
		$search = $_POST["search"];
		$order = $_POST["order"];	
		$c = $_POST["c"];	
	}else{
		$search = $_GET["search"];
		$order = $_GET["order"];				
		$c = $_GET["c"];	
	}
	
	if($order == ''){$order = 'id';};
	
	$pieces = explode(" ", $search);

		$ssqlp = "SELECT * FROM tours where (";
		
		foreach ($pieces as $clave => $valor){
			$ssqlp .= "category like '%".$valor."%' or location like '%".$valor."%' or title like '%".$valor."%' or tags like '%".$valor."%' or description like '%".$valor."%' or ";
		}
		
		$ssqlp = substr($ssqlp, 0, -4);
		
		$ssqlp .= ")";
		
		$ssqlpfinal = $ssqlp.' ORDER BY '.$order.' DESC';
		
		$result = mysql_query($ssqlpfinal);	
		
		$cantreg = mysql_num_rows($result);

$lastID = $_GET["lastID"];
$action = $_GET["action"];

if ($action != 'getLastPosts') {
 

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
		
			$.post("search.php?c=<?echo $c;?>&order=<?echo $order;?>&search=<?echo $search;?>&action=getLastPosts&lastID="+$(".post:last").attr("id"),
	
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
	


	function order(o){
		document.formsearch.search.value="<?echo $search;?>";
		document.formsearch.c.value="<?echo $c;?>";
		document.formsearch.order.value=o;
		document.formsearch.submit();
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
        <div>
        	<h1 class="result">
        	<?if ($c==1){?>
        		Browsing: 
        	<?}else{?>
        		Search Results for: 
        	<?}?>        	
        	<font>"<?echo $search;?>"</font></h1>        	

            <div class="result_details">
            	<div class="result_number"><?echo $cantreg;?> Tours found</div>
                <div class="sort_results">
                	<font>Sort results by:</font>
                    <a href="#" onclick="order('date');">New</a>
                    <a href="#" onclick="order('likes');">Top rated</a>
                    <a href="#" onclick="order('views');">Popular</a>     
                    <br clear="all">
                </div>
                <br clear="all">
            </div>

		</div>
        <div class="wrapper">
<?
		$i=0;
		
		while ($row = mysql_fetch_array($result)){
		$i++;
		if($i > 20){break;}
?>        
			<div class="post" id="<?echo $i;?>">
			    <div class="thumb"><a href="tour.php?id=<?echo $row["id"];?>"><img src="../images/tours/<?echo $row["id"];?>.jpg"></a></div>                    
				<a href="#" class="user"><img src="../images/users/<?echo $row["iduser"];?>.jpg"  width="43" height="43"></a>
			    <div class="by"><a href="#">by <?echo $row["user"];?></a></div>
			    <a href="tour.php?id=<?echo $row["id"];?>" class="text">
			        <p>
			            <?echo $row["title"];?>
			        </p>
			    </a>
			    <div class="count">
			        <div class="views"><?echo $row["views"];?></div>
			        <!-- 
			        <a href="#"  class="comments"><?echo $row["comments"];?></a>
			        -->
					<a href="javascript:void(0)" id="like<?echo $row["id"];?>" class="likes" onclick="like(<?echo $row["id"];?>);"><?echo $row["likes"];?></a>			        
			        <br clear="all">
			    </div>
			</div>
			
<?}?>

            
        </div>
	</body>
</html>



<?
}else{
	$getPostsText = "";

	$ssqlp = $ssqlp.' ORDER BY '.$order.' DESC';
	
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
                	<a href="#" class="user"><img src="../images/users/'.$row["iduser"].'.jpg" width="43" height="43"></a>
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
                        </div>';
                                
			$getPostsText .= '
                </div>';
	}
	echo $getPostsText; //Writes The Result Of The Query
	//When User Scrolls This Query Is Run End
}
?>        	