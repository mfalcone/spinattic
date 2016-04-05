<?php
require("inc/conex.inc");

$ssqlp = "SELECT * FROM users where hashregistro = '".$_GET["h"]."' and reset_sol = 1";
$result = mysql_query($ssqlp);
if($row = mysql_fetch_array($result)){

	session_start();
	$_SESSION['h'] = $row["hashregistro"];?>
	
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
	    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
		<script src="js/custom.js"></script>
		
		
	</head>
	<body>

		<div class="page_centered">
			<div class="logo2"></div>


			<div class="content_box">

<!-- RESET -->
			<span id="titulo">
				<h3>Reset your password</span></h3>
				<h4>Type your new password. Must be at least six characters.</h4>
			</span>
					
					<div class="form_container">

<!-- 					<div class="message_box warning_m" >
							<p>Your password must be at least 6 characters.</p>
						</div>
						<div class="message_box error_m" >
							<p>There was an error. Please try again.</p>
						</div>
-->						
        <div class="overlay overlay_login">
            <div class="pop">
                <a href="#" class="closed closed_register_mobile"></a>
                <h2>Log in</h2>
                <div class="content_pop">
	                <a href="#" class="facebook"></a>
	                <a href="#" class="googleplus"></a>
					<span id="login_pop"></span>  
	                    <input type="text" placeholder="E-Mail" class="mail" id="email">
	                    <input type="password" placeholder="Password" class="pass" id="pass">
	                    <a href="#" class="login loginaction">Log in</a>
	                    <label for="remember" class="terminos">
	                        <input type="checkbox" id="remember">
	                            <a href="#" class="forgot_pwd">Remember me - <font>Forgot password?</font></a>
	                        <br clear="all">
	                    </label>
	            </div>            				
            </div>
        </div> 					
						<span id="mensajes"></span>
						<span id="inputs">
			                <label>
			                    <p>New Password</p>
			                    <input class="border-radius-4" value=""  type="password" name="ud_password" id="ud_password" >
	                            <br clear="all"/>
			                </label>
			                <label>
			                    <p>Repeat New Password</p>
			                    <input class="border-radius-4" value="" type="password" name="ud_repeat_password" id="ud_repeat_password" >
								<input type="hidden" value="<?echo $_SESSION['h'];?>" id="h">
	                            <br clear="all"/>
			                </label>
                        </span>
                        <hr class="separator" />
                        
						<a href="#" class="uduser green-button border-radius-4" id="change_pwd_btn">CHANGE PASSWORD</a>
						<a href="index.php?login" class="green-button border-radius-4" id="login_pwd_btn" style="display:none;">LOGIN</a>
						<br><br clear="all">

					</div>
<!-- end RESET -->


			</div>


		</div>

	</body>
</html>
	
<?php }else{
	header('Location: index.php');
}