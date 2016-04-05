<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
		<title>Spinattic - Login</title>
	    <!--google font-->

		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
		<!-- css login -->        
	    <link rel="stylesheet" type="text/css" media="screen" href="css/login.css" />
	    <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	    <!-- script src="js/custom.js"></script-->
	    <script>
	    jQuery(document).ready(function(){
			jQuery('.loginaction').click(function(){
					
					user = document.getElementById('name').value;
					pass = document.getElementById('pass').value;
					remember = document.getElementById('remember').checked;
					
					if (remember){r = 1;}else{r=0;};
					
					if(user == '' || pass == ''){
						$(".error").html("Please complete all fields");
						$(".error").show();
					}else{
						//SEND LOGIN DATA BY AJAX
						var xmlhttp;
						if (window.XMLHttpRequest)
						  {// code for IE7+, Firefox, Chrome, Opera, Safari
						  xmlhttp=new XMLHttpRequest();
						  }
						else
						  {// code for IE6, IE5
						  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
						  }
						xmlhttp.onreadystatechange=function()
						  {
						  if (xmlhttp.readyState==4 && xmlhttp.status==200)
							{
								respuesta = JSON.parse(xmlhttp.responseText);
								if (respuesta.success == 'ok'){
									if($('#s_h').val() != respuesta.id){
										window.location.href="home";
										mixpanel.identify(respuesta.id);
										
										mixpanel.people.set({
											"$email": respuesta.email,
											
											"$name": respuesta.username
										});								
										mixpanel.track("User log in");
									}else{
										hide_popup();
									}
								}else{
									$(".error").html("Wrong username or password, please try again");
									$(".error").show();
								}
							}
						  }
						xmlhttp.open("GET","http://"+window.location.hostname+"/ajax_login.php?r="+r+"&u="+user+"&p="+pass,true);
						xmlhttp.send();			
					}
					return false;

			});
	
		jQuery(document).keypress(function(event){
	
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if(keycode == '13'){
				jQuery('.loginaction').trigger("click");	
			}
			
		});
	
	    });

	    </script>
	
	</head>
	<body>

		<div class="formwrap">
			<img src="images/logo_login.png" alt="spinattic logo">
			<div class="error">Error message</div>
			<form action="">
				<fieldset>
					<input type="text" id="name" name="name" placeholder="USERNAME OR EMAIL">
				</fieldset>
				<fieldset>
					<input type="password" id="pass" name="pass" placeholder="PASSWORD">
				</fieldset>
			<input type="checkbox" name="remember" id="remember" checked> Remember me
			<a href="#" class="loginaction blue-button">Login</a>
			</form>	
		</div>

	</body>
</html>