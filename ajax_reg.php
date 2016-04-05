<?
require("inc/conex.inc");
require_once("inc/functions.inc");

$action = $_GET["reg_action"];

switch ($action){
	
	case "check_email":
		$email = $_GET["email"];
		if ($email !=''){
			$ssqlp = "SELECT email FROM users where email = '".$email."'";
			$result = mysql_query($ssqlp);
			if($row = mysql_fetch_array($result)){
				echo json_encode(array(
							'result' => "exist"));
			}else{
				echo json_encode(array(
						'result' => "ok"));
			}
		}
		break;
	
	case "check_user":
		$username = $_GET["username"];

		if ($username !=''){
			$ssqlp = "SELECT username FROM users where username = '".$username."'";
			$result = mysql_query($ssqlp);
			if($row = mysql_fetch_array($result)){
				echo json_encode(array(
							'result' => "exist"));
			}else{
				echo json_encode(array(
						'result' => "ok"));
			}
		}
		break;
		
	default:
		
		$new_user = 0;
		//Check if exists by FB or GP
		$ssqlp = "SELECT * FROM users where email = '".$_GET["e"]."'";
		$result = mysql_query($ssqlp);
		if($row = mysql_fetch_array($result)){
			$hashregistro = $row["hashregistro"];
			$ssqlp = "update users set password = '".md5($_GET["p"])."', sol_date = now(), confirm_sol = 1, subscribe = '".$_GET["s"]."' where hashregistro = '".$hashregistro."'";
		}else{
			$hashregistro = md5(strtolower($_GET["n"]).$_GET["e"].$_GET["p"]);
			$ssqlp = "insert into users (username, nickname, email, password, sol_date, hashregistro, confirm_sol, friendly_url, subscribe) values ('".mysql_real_escape_string($_GET["n"])."', '".mysql_real_escape_string($_GET["nn"])."','".mysql_real_escape_string($_GET["e"])."','".md5($_GET["p"])."',now(), '".$hashregistro."', 1, '', '".$_GET["s"]."')";
			$new_user = 1;
		}
		
		mysql_query($ssqlp);
		
		//Si es nuevo, pongo el id generado en el friendly
		if($new_user == 1){
			mysql_query("update users set friendly_url = id where email = '".$_GET["e"]."'");
		}
		
		
		//send mail
		
		$to =$_GET["e"];
		$toName = $_GET["n"];
		$subject = "Activate your account in Spinattic.com";
		$body = 'Welcome to Spinattic!<br><br>Thank you for signing up. <br><br>Please activate your account by clicking on the following link.<br><br> Best regards, the Spinattic support team.<br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/confirm_reg.php?h='.$hashregistro.'" style="color:#51ace5; text-decoration:none;">http://'.$_SERVER['HTTP_HOST'].'/confirm_reg.php?h='.$hashregistro.'</a>';
		
		$result_mail = send_mail($to, $toName, $subject, '', '', 'Welcome to Spinattic !', '', $body, '', '');
		
		if($result_mail == 'SUCCESS'){
			echo json_encode(array(
					'result' => "ok"));			
			
		}
		
	break;
}


?>