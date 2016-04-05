<?
require("inc/conex.inc");
require("inc/login.inc");

//Check for existing users
		
$ssqlp = "SELECT * FROM users where (email = '".$_GET["u"]."' or nickname = '".$_GET["u"]."') and password = '".md5($_GET["p"])."' and status = 1";
$result = mysql_query($ssqlp);	
		
if($row = mysql_fetch_array($result)){

	if($_GET["r"]==1){
		setcookie('hashregistro',$row["hashregistro"],time()+60*60*24*29.5*12*24);
	}else{
		setcookie('hashregistro',$row["hashregistro"],1);
	}

	$hash_mobile_api = '';
	
	login($row["hashregistro"]);	
	
	echo json_encode(array(
		
		'success' => 'ok',
		
		'id' => $row["id"],
			
		'email' => $row["email"],
		
		'name' => $row["username"],
			
		'hma' => $hash_mobile_api
			
	));	

}else{
	echo json_encode(array(
	
			'success' => 'nok',
	
			'msg' => '<div class="message_box error_m in_pop"><p>Wrong username or password, please try again</p></div>'
	
	));	

}

?>