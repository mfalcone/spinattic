<?
//Detect if mobile
/*require_once realpath($_SERVER["DOCUMENT_ROOT"]).'/php-stubs/Mobile_Detect.php';
$detect = new Mobile_Detect;

if($detect->isMobile()) {
	header('Location:'."http://".$_SERVER[HTTP_HOST].'/mobile/');
	exit;
}
require(realpath($_SERVER["DOCUMENT_ROOT"])."/inc/auth.inc");
require(realpath($_SERVER["DOCUMENT_ROOT"])."/inc/conex.inc");*/
?>
<!doctype html>

<!--[if lt IE 9]> <html class="no-js lte9 oldie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9 oldie" lang="en"> <![endif]-->
<!--[if gt IE 9]>  <html> <![endif]-->
<!--[if !IE]><!--> <html>             <!--<![endif]-->
	<head>
	    <title>Customizer</title>
	    <link href="images/favicon.ico" rel="icon" type="image/x-icon" />
	    <meta name="robots" content="noindex">
	    <meta charset="UTF-8">
		<script data-main="js/main" src="js/lib/require/require.js?v=<?echo $ver;?>"></script>
		<link rel="stylesheet" type="text/css" href="css/main.css?02262016">
	</head>
	<body>
		<header class="main-header">
			<h1>Spinattic customizer</h1>
			<div class="header-side"></div>
			<div class="header-bottom"></div>
		</header>
		<div class="loading-msg"><span class="loading"></span><span>Firing up the engines...</span></div>
		<section class="main-section">			
			<div class="inner"></div>
		</section>
		<aside>
		</aside>
		<footer class="main-footer">
		</footer>
		<div class="patent">Patent Pending</div>
	</body>
</html>
