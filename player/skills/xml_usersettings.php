<?
	require("../../inc/conex.inc");

	$id = $_GET["id"];

	if ($id !=''){
		$ssqlp = "SELECT * FROM users where id = ".$id;
		$result = mysql_query($ssqlp);
		if($row = mysql_fetch_array($result)){
			//echo htmlspecialchars ('<krpano onstart="trace(php loaded)"><settings name="usersettings" username="'.$row["username"].'" useravatar="http://'.$_SERVER[HTTP_HOST].'/images/users/'.$row["avatar"].'" userlink="[a href=http://'.$_SERVER[HTTP_HOST].'/profile.php?uid='.$id.' target=_blank]" userprofile="http://'.$_SERVER[HTTP_HOST].'/profile.php?uid='.$id.'"/><plugin name="ttt" keep="true" align="center" url="http://dev.spinattic.com/images/users/cn8kniipb1k7a0y.jpg" /></krpano>');
			echo '<krpano><settings name="usersettings" username="'.$row["username"].'" useravatar="http://'.$_SERVER[HTTP_HOST].'/images/users/'.$row["avatar"].'" userprofile="http://'.$_SERVER[HTTP_HOST].'/profile.php?uid='.$id.'"/></krpano>';
		}
	}

?>