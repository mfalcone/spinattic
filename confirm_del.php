<?
require_once("inc/conex.inc");
require_once("inc/login.inc");

function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir")
					rrmdir($dir."/".$object);
				else 
					unlink   ($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
}

function del_panos_user($user_id){//Elimina los panos que pertenecen a un user

	global $cdn_string;
	global $cdn;
	global $bucket_config_file;
	
	$ssqlp = "select * from panos where user = ".$user_id;
	$result = mysql_query($ssqlp);
	
	while ($row = mysql_fetch_array($result)){
		$id_pano = $row["id"];
		
		$ssqlp1 = "delete from panos where id = ".$id_pano;
		mysql_query($ssqlp1);
				
		//Borro cloud
		$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' del s3://'.$cdn_string.'/panos/'.$id_pano.' --recursive 2>&1');

	}
}

function del_tour($id_tour){

	global $cdn_string;
	global $cdn;
	global $bucket_config_file;

	mysql_query("delete from tours where id = '".$id_tour."'");
	mysql_query("delete from tours_draft where id = '".$id_tour."'");
	mysql_query("delete from comments where idtour = '".$id_tour."'");
	mysql_query("delete from likes where idtour = '".$id_tour."'");

	mysql_query("delete from hotspots where scene_id in (select id from panosxtour where idtour = '".$id_tour."')");
	mysql_query("delete from hotspots_draft where scene_id in (select id from panosxtour_draft where idtour = '".$id_tour."')");

	//echo "delete from hotspots where scene_id in (select id from panosxtour where idtour = '".$id_tour."')";

	mysql_query("delete from panosxtour where idtour = '".$id_tour."'");
	mysql_query("delete from panosxtour_draft where idtour = '".$id_tour."'");

	//vacio la carpeta y la elimino
	rrmdir('tours/'.$id_tour);
	
}


//Check user
if (isset($_GET["f"]) && $_GET["f"] == 1){
	if(isset($_GET["h"]) && $_GET["h"] != ''){
		
		$ssqlp = "SELECT * FROM users where hashdelete = '".$_GET["h"]."'";
	
		$result = mysql_query($ssqlp);
	
		if($row = mysql_fetch_array($result)){
			$user_id = $row["id"];
	
			$ssqlp_tour = "SELECT * FROM tours where iduser = ".$user_id;
	
			$result_tour = mysql_query($ssqlp_tour);
	
			while ($row_tour = mysql_fetch_array($result_tour)){
	
				$id_tour = $row_tour["id"];
	
				del_tour($id_tour);
	
			}
	
			del_panos_user($user_id);

			$ssqlp = "delete FROM follows WHERE id_follower = ".$user_id." or id_following = ".$user_id;
			mysql_query($ssqlp);
			
			
			$ssqlp = "delete from users where hashdelete = '".$_GET["h"]."'";
			mysql_query($ssqlp);

			
			
	
			?>
			<!DOCTYPE HTML>
			<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			
			<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
			
			<link href='favicon.png' rel='shortcut icon' type='image/x-icon'/>
			<link href='favicon.png' rel='icon' type='image/x-icon'/>
			<title>Spinattic</title>
			
			<!-- css main -->
			<link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
			</head>
			<body>
			
			<div class="page_centered">
				<div class="logo2"></div>
				<div class="content_box">
					<h3>Your account has been deleted!!!<br><br>We're sorry that you're gone! =(<br>All your personal information, panoramas and tours are no longer available.<br><br>Have a nice day!</h3>
					<br><br>
					<a href="index.php" class="blue-buttom">OK</a>
				</div>
			</div>
			
			</body>
			</html>
			<?php
			session_start();
			$_SESSION = array();
			session_destroy();
			setcookie('hashregistro','',1);
			}else{
				header('Location: index.php');
			}
		}else{
			header('Location: index.php');
		}
}else{
?>	
	<!DOCTYPE HTML>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
	
	<link href='favicon.png' rel='shortcut icon' type='image/x-icon'/>
	<link href='favicon.png' rel='icon' type='image/x-icon'/>
	<title>Spinattic</title>
	
	<!-- css main -->
	<link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
	</head>
	<body>
	
	<div class="page_centered">
		<div class="logo2"></div>
		<div class="content-btn-pop">
			<h3>You are about to delete your account from Spinattic.<br>This is your last chance to regret and cancel. If you proceed all your personal information, panoramas and tours stored in Spinattic will be removed and never be accesible again.<br><br>I understand what I am doing and I want to delete my account:</h3>
			<br><br>
			<a href="index.php" class="grey-button">CANCEL</a>
			<a href="confirm_del.php?f=1&h=<?php echo $_GET["h"]?>" class="red-button">YES</a>
		</div>
	</div>
	
	</body>
	</html>
<?php
}
?>