<?
require("conex.inc");
function login($h){
	
	global $hash_mobile_api;

	$ssql_login = "SELECT * FROM users where hashregistro = '".$h."' and status = 1";
	$result_login = mysql_query($ssql_login);	
	if($row_login = mysql_fetch_array($result_login)){
		session_start();
		
		//Si el hash para la API de mobile está vacía, genero una (se borra en el deslogueo a traves de la api post.php)
		$hash_mobile_api = $row_login["hash_mobile_api"];
		
		if($hash_mobile_api == ''){
			$hash_mobile_api = md5(strtolower($_GET["n"]).$_GET["e"].$_GET["p"].time());
		}
			
		
		$ssqlp = "update users set reset_sol = 0, hash_mobile_api = '".$hash_mobile_api."' where hashregistro = '".$h."' and status = 1";
		mysql_query($ssqlp);
		
		
		$_SESSION['usr'] = $row_login["id"];
		$_SESSION['username'] = $row_login["username"];
		$_SESSION['email'] = $row_login["email"];
		$_SESSION['h'] = $row_login["hashregistro"];
		$_SESSION['avatar'] = $row_login["avatar"];
		$_SESSION['cover'] = $row_login["cover"];
		$_SESSION['admin'] = $row_login["admin"];
		$_SESSION['nickname'] = $row_login["nickname"];
		$_SESSION['friendly_url'] = $row_login["friendly_url"];
		$_SESSION['hash_mobile_api'] = $row_login["hash_mobile_api"];
	
		
	}
}

?>