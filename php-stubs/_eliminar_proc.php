<?
if($_GET["pass"] == 'vlad8523'){
	$salida = shell_exec('kill -9 '.$_GET["pid"]);
	echo nl2br($salida);
}
?>