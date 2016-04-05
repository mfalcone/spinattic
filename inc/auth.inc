<?
require(realpath($_SERVER["DOCUMENT_ROOT"])."/dev.spinattic.com/inc/login.inc");
require(realpath($_SERVER["DOCUMENT_ROOT"])."/dev.spinattic.com/inc/conex.inc");

session_start();

//if($_SESSION['usr'] != ""){
	$logged = 1;
/*}else{
	//Si no esta seteada la session, verifico si tiene la cookie de hash por el recordarme y lo logeo
	if($_COOKIE['hashregistro']!=''){
		login($_COOKIE['hashregistro']);
		$logged = 1;
	}
}

*/
//Chequeo contra la BD por si se borró el usuario

$ssqlp = "SELECT id FROM users where id = '".$_SESSION["usr"]."'";
$result = mysql_query($ssqlp);
if(!($row = mysql_fetch_array($result))){
	//User doesn't exist, Logout
	$_SESSION = array();
	session_destroy();
	setcookie('hashregistro','',1);
	$logged = 0;
}


//Redireccion a form de login general que reemplazó al htaccess password
if($environment == 'dev' && $logged != 1){
	header('Location: http://'.$_SERVER[HTTP_HOST].'/login.php');
}



if($restrict == 1 && $logged != 1){
	header('Location: index.php?login');
}
if($restrict == 2 && $_SESSION['admin'] != 1){
	header('Location: index.php?login');
}

?>