<?session_start();
$_SESSION = array();
session_destroy();
setcookie('hashregistro','',1);
header('Location: index.php?explore');
?>