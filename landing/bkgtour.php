<?

require("../inc/conex.inc");

?>

<!DOCTYPE html>
		<html>
		<head>
			<title>Spinattic.com</title>
			<meta name="viewport" content="target-densitydpi=device-dpi, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
			<meta name="apple-mobile-web-app-capable" content="yes" />
			<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

		<meta name="title" content="">
		<meta name="description" content="">

			<style>
				html { height:100%; }
				body { height:100%; overflow: hidden; margin:0; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#FFFFFF; background-color:#000000; }
				a{ color:#AAAAAA; text-decoration:underline; }
				a:hover{ color:#FFFFFF; text-decoration:underline; }
			</style>
		</head>
		<body>

		<script src="../player/tour.js"></script>

		<div id="pano" style="width:100%; height:100%;">
			<noscript><table style="width:100%;height:100%;"><tr style="valign:middle;"><td><div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div></td></tr></table></noscript>
			<script>
				<?php if($environment == 'dev'){?>
				mypanos = ["63"];
				<?php }else{?>
				mypanos = [564, 568, 569, 729, 535, 34, 16, 813, 143, 147, 174, 544, 549, 177, 96, 99, 81, 93, 74, 73];
				<?php }?>
				randompano = mypanos[Math.floor(Math.random()*mypanos.length)];

				embedpano({swf:"../player/tour.swf", xml:"<?echo $cdn;?>/panos/"+randompano+"/pano.xml", target:"pano", html5:"prefer", wmode:"opaque", passQueryParameters:true});

			</script>
		</div>

		</body>
		</html>
	