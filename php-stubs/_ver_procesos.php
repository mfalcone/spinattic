<?
//$salida = shell_exec('ps -ef | grep krpano');
$salida = shell_exec('ps -ef');
echo nl2br($salida);
?>