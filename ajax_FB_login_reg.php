<?
require("inc/conex.inc");
require("inc/login.inc");
require('php-stubs/Uploader.php');

//Check if get email address
$email = $_GET["mail"];

$link = $_GET["link"];

if(substr($link , -1) == '/'){
	$link = substr($link,0,strlen($link)-1);
}

$hash_mobile_api = '';

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

	//Check for existing users, if not, register it, if yes, update data
	$ssqlp = "SELECT * FROM users where email = '".$email."'";
	$result = mysql_query($ssqlp);
	
	if(!($row = mysql_fetch_array($result))){
	
		$hashregistro = $_GET["h"];
		
		//Make username------------------------------------------------------------------
		$i=0;
		$username_array = explode(" ",strtolower($_GET["n"]));
		$first = array_shift($username_array);
		$second = implode($username_array);
		$username = $first;
		$username .= substr($second,$i,1);
		
		$ssqlp_un = "SELECT * FROM users where nickname = '".$username."' and email <> '".$email."'";
		$result_un = mysql_query($ssqlp_un);
		while($row_un = mysql_fetch_array($result_un) && $i < strlen($second)-1){
			$i++;
			$username .= substr($second,$i,1);
			$ssqlp_un = "SELECT * FROM users where nickname = '".$username."' and email <> '".$email."'";
			$result_un = mysql_query($ssqlp_un);	
		}		
		
		if($i >= strlen($second)-1){
			$ind = 1;
			$username .= $ind;
			$ssqlp_un = "SELECT * FROM users where nickname = '".$username."' and email <> '".$email."'";
			$result_un = mysql_query($ssqlp_un);
			while($row_un = mysql_fetch_array($result_un)){
				$ind++;
				$username .= $ind;
				$ssqlp_un = "SELECT * FROM users where nickname = '".$username."' and email <> '".$email."'";
				$result_un = mysql_query($ssqlp_un);
			}			
		}
		//End Making Username------------------------------------------------------------------
		
		$ssqlp = "insert into users (avatar, nickname, username, fb_name, hashregistro, status, email, facebook, friendly_url, subscribe) values ('".mysql_real_escape_string($_GET["h"]).".jpg', '".$username."', '".mysql_real_escape_string($_GET["n"])."', '".mysql_real_escape_string($_GET["n"])."', '".$hashregistro."',1, '".mysql_real_escape_string($email)."', '".end(explode('/',$link))."', '', '".$_GET["s"]."')";
		mysql_query($ssqlp);

		//Update de friendly URL para poner ID
		mysql_query("update users set friendly_url = id where email = '".mysql_real_escape_string($email)."'");
		
		//get avatar
		$img = file_get_contents('https://graph.facebook.com/'.$_GET["h"].'/picture?type=large');
		$file = dirname(__file__).'/images/users/'.$_GET["h"].'.jpg';
		file_put_contents($file, $img);
		//croppeo
		define('THUMBNAIL_IMAGE_MAX_WIDTH', 107);
		define('THUMBNAIL_IMAGE_MAX_HEIGHT', 107);		
		crop_image('images/users/'.$_GET["h"].'.jpg', 'images/users/'.$_GET["h"].'.jpg', THUMBNAIL_IMAGE_MAX_WIDTH, THUMBNAIL_IMAGE_MAX_HEIGHT);
		
	}else{
		
		$hashregistro = $row["hashregistro"];

		$ssqlp = "update users set fb_name = '".mysql_real_escape_string($_GET["n"])."'";
		
		//Si no tiene avatar, lo bajo y actualizo
		if($row["avatar"] == 'avatar.jpg'){
			$img = file_get_contents('https://graph.facebook.com/'.$_GET["h"].'/picture?type=large');
			$file = dirname(__file__).'/images/users/'.$_GET["h"].'.jpg';
			file_put_contents($file, $img);
			//croppeo
			define('THUMBNAIL_IMAGE_MAX_WIDTH', 107);
			define('THUMBNAIL_IMAGE_MAX_HEIGHT', 107);
			crop_image('images/users/'.$_GET["h"].'.jpg', 'images/users/'.$_GET["h"].'.jpg', THUMBNAIL_IMAGE_MAX_WIDTH, THUMBNAIL_IMAGE_MAX_HEIGHT);			
			$ssqlp .= ", avatar = '".$_GET["h"].".jpg'";
		}
		$ssqlp.= "where hashregistro = '".$hashregistro."'";
		mysql_query($ssqlp);				
	}	
	

	//seteo la cookie para que lo recuerde hasta que haga un logout
	setcookie('hashregistro',$hashregistro,time()+60*60*24*29.5*12*24);
	
	login($hashregistro);
	
	echo json_encode(array(
		
		'success' => 'ok',
		
		'id' => $row["id"],
			
		'email' => $row["email"],
		
		'name' => $row["username"],
			
		'hma' => $hash_mobile_api			
			
	));	

}else{
	
	echo json_encode(array(
	
			'success' => 'errormail'
	
	));	
	
	
}
?>