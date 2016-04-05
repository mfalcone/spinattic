<?php
ini_set("display_errors", 0);

require_once("inc/conex.inc");

require_once realpath($_SERVER["DOCUMENT_ROOT"]).'/php-stubs/Mobile_Detect.php';

$detect = new Mobile_Detect;


$a = $_GET["a"];
$b = $_GET["b"];
$c = $_GET["c"];

//echo $a."<br>".$b."<br>".$c;

$in_friendly = 1; //esta var se comprueba en las paginas que se llaman para hacer la redireccion o no (si el usuario llega a la pagina destino directamente, con esta var defino si hago la redireccion al friendly o no)

//user profile
if($a != '' && $b == '' && $c == ''){
	
	if($detect->isMobile()) {//Si es mobile, mando al index
		header('Location:'."http://".$_SERVER[HTTP_HOST].'/mobile/');
		//echo "este FRIENDLY mobile";
		exit;
	
	}else{	
	
		if(strpos($a, '?')){
			$secparam = explode("?", $a);
			$a = $secparam[0];
			$mod = $secparam[1];
		}
		
		$ssqlp = "SELECT users.id as id from users where (id = '".$a."' or friendly_url = '".$a."') and status = 1";
		$result = mysql_query($ssqlp);
		if($row = mysql_fetch_array($result)){
			
			$uid = $row["id"];
			include_once './profile.php';
			
		}else{
			
			include_once './404.php';
			
		}
	}
}

//tour
if($a != '' && $b != ''){
	
	//scene
	if($c != ''){

		include_once './404.php';

	}else{
	
		$ssqlp = "SELECT tours.id as id from tours, users where tours.iduser = users.id and tours.friendly_url = '".$b."' and (users.friendly_url = '".$a."' or users.id = '".$a."')";
		$result = mysql_query($ssqlp);
		if($row = mysql_fetch_array($result)){
			
			$id = $row["id"];
			$fullscreen = 0;
			
			if(strpos($_SERVER[REQUEST_URI], '?full')){
				//traigo tour en full
				$fullscreen = 1;
			}else{
				if($detect->isMobile()) {//Si no es fullscreen y es mobile, lo mando al index
					header('Location:'."http://".$_SERVER[HTTP_HOST].'/mobile/');
					//echo "FRIENDLY mobile";
					exit;
				}
			}
			
			//echo "ACA:".$fullscreen;
			include_once './tour.php';
			
		}else{
			
			include_once './404.php';
			
		}
	}		
	
}

?>