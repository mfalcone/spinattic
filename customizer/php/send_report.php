<?
//require("../inc/conex.inc");
session_start();
$type = $_POST["type"];
$extras = $_POST["extras"];

//$type = '';

if ($type != ''){

	switch ($type){
		case "1": //Error no especificado al subir pano
			$message = '<u>Descripcion</u>: Error no especificado al subir archivo<br><br><u>Usuario</u>: '.$_SESSION["username"].'<br><u>User ID</u>: '.$_SESSION["usr"];
			$message .= '<br><br>'.$extras;
			break;
		case "2": //Error al crear thumbs
			$message = '<u>Descripcion</u>: Error al crear thumbs<br><br><u>Usuario</u>: '.$_SESSION["username"].'<br><u>User ID</u>: '.$_SESSION["usr"];
			$message .= '<br><u>Files in path (panos/'.$extras.'/index.tiles)</u>:';
			$dir = '../panos/'.$extras.'/index.tiles/';
			if (is_dir($dir)) {
				if ($dh = opendir($dir)) {
					while (($file = readdir($dh)) !== false) {
						$message .= $file."<br />";
					}
					closedir($dh);
				}
			}	else {
				echo "error dir ".'../panos/'.$extras.'/index.tiles/';
			}		
			break;
		case "3": //Error al crear pano
			$message = '<u>Descripcion</u>: Error al crear pano<br><br><u>Usuario</u>: '.$_SESSION["username"].'<br><u>User ID</u>: '.$_SESSION["usr"];
			$message .= '<br><u>Files in path (panos/'.$extras.'/index.tiles)</u>:';			
			$dir = '../panos/'.$extras.'/index.tiles/';
			if (is_dir($dir)) {
				if ($dh = opendir($dir)) {
					while (($file = readdir($dh)) !== false) {
						$message .= $file."<br />";
					}
					closedir($dh);
				}
			}	else {
				echo "error dir ".'../panos/'.$extras.'/index.tiles/';
			}		
			break;		
	}
	
	
	
	//send mail
	
	$to = "reports@spinattic.com";
	$subject = "[SPINATTIC] - Error Report";
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

	// Additional headers
	$headers .= "From: Spinattic.com <info@spinattic.com>\r\n";

	// Mail it
	mail($to, $subject, $message, $headers);
	
	echo "ok";
		
}else{
	
	header('Location: index.php');
}
?>