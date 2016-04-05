<?php

function make_tour_files($idtour)
{

    $ssqlp = "SELECT * FROM tours where id = ".$idtour;
    $result = mysql_query($ssqlp);	
    $row = mysql_fetch_array($result);
	
	$version_xml = $row["version_xml"];
	$title = $row["title"];	
	$description = $row["description"];				

	$ssqlp = "SELECT * FROM panosxtour where idtour = ".$idtour." order by ord";
	$result = mysql_query($ssqlp);	
	$row = mysql_fetch_array($result);
	$preview = '../../panos/'.$row["idpano"].'/index.tiles/preview.jpg';

/* FOLDER */
	
	if (!is_dir('tours/'.$idtour)) mkdir ('tours/'.$idtour);

/* HTML */

	/* Genero Data */
	
	$final_data = '
		<!DOCTYPE html>
		<html>
		<head>
			<title>Spinattic.com</title>
			<meta name="viewport" content="target-densitydpi=device-dpi, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
			<meta name="apple-mobile-web-app-capable" content="yes" />
			<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

		<meta name="title" content="'.$title.'">
		<meta name="description" content="'.$description.'">
		<link rel="image_src" href="'.$preview.'">

			<style>
				html { height:100%; }
				body { height:100%; overflow: hidden; margin:0; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#FFFFFF; background-color:#000000; }
				a{ color:#AAAAAA; text-decoration:underline; }
				a:hover{ color:#FFFFFF; text-decoration:underline; }
			</style>
		</head>
		<body>

		<script src="../../player/tour.js"></script>

		<div id="pano" style="width:100%; height:100%;">
			<noscript><table style="width:100%;height:100%;"><tr style="valign:middle;"><td><div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div></td></tr></table></noscript>
			<script>
				embedpano({swf:"../../player/tour.swf", xml:"tour'.$version_xml.'.xml", target:"pano"});
			</script>
		</div>

		</body>
		</html>
	';


	/* Elimino todos los html que haya */

	foreach (glob('tours/'.$idtour.'/*.html') as $filename) {
		unlink($filename);
	} 

	
	/* Escribo */
	
	$fp = fopen('tours/'.$idtour.'/index.html',"a");
			
	if($fp){
				
		fwrite($fp,$final_data);
		fclose($fp);

	}




/* XML */


	/* Genero Data */
	
	$final_data = '
	
		<krpano version="1.0.8.15" onstart="startup();">

			<include url="../../interfaces/INTERFACE_A_1.0/interface.xml" devices="desktop" />
			<include url="../../interfaces/INTERFACE_A_1.0/interface_m.xml" devices="iphone|ipad|android|mobile|tablet|touchdevice|gesturedevice" />
			

			<action name="startup">
				abreNav();
				loadscene(get(scene[0].name),null,MERGE,BLEND(1));
			</action>
			
			<!-- disable the default progress bar -->
			<progress showload="none" showwait="none" />';
			

		$ssqlp = "SELECT * FROM panosxtour where idtour = ".$idtour." order by ord";
		$result = mysql_query($ssqlp);	
		while($row = mysql_fetch_array($result)){
	
			$final_data.= '

			<!-- SCENES -->
			<scene name="scene_'.$row["id"].'" title="'.$row["name"].'" onstart=""  thumburl="%CURRENTXML%/../../panos/'.$row["idpano"].'/index.tiles/thumb200x100.jpg" lat="" lng="" heading=""  >
					<view hlookat="'.$row["hlookat"].'" vlookat="'.$row["vlookat"].'" fovtype="MFOV" fov="95.000" maxpixelzoom="1.5" fovmin="60" fovmax="120" limitview="auto"  />

					<preview url="%CURRENTXML%/../../panos/'.$row["idpano"].'/index.tiles/preview.jpg" />

					<image>
						<cube url="%CURRENTXML%/../../panos/'.$row["idpano"].'/index.tiles/pano_%s.jpg" />
						<mobile>
							<cube url="%CURRENTXML%/../../panos/'.$row["idpano"].'/index.tiles/mobile_%s.jpg" />
						</mobile>
					</image>';

			//HOTSPOTS

			$ssqlp_htsp = "SELECT * FROM hotspots where scene_id = ".$row["id"];
			$result_htsp = mysql_query($ssqlp_htsp);	
			while($row_htsp = mysql_fetch_array($result_htsp)){
			
				$final_data.= '
					<hotspot style="'.$row_htsp["style"].'" name="'.$row_htsp["name"].'" ath="'.$row_htsp["ath"].'" atv="'.$row_htsp["atv"].'"';

				switch ($row_htsp["type"]) {
				    case "info":
				        $final_data.= ' infotitle="'.$row_htsp["extra_infotitle"].'" infotext="'.$row_htsp["extra_infotext"].'" />';
				        break;
				    case "media":
				        $final_data.= ' pic="'.$row_htsp["extra_photourl"].'" tooltip="'.$row_htsp["extra_tooltip"].'" />';
				        break;
				    case "link":
				        $final_data.= ' linkedscene="'.$row_htsp["extra_linkedscene"].'" />';
				        break;
				}					

			}



			$final_data .= 	'
			</scene>';
		}

		$final_data.= '</krpano>';


	/* Elimino todos los xml que haya */

	foreach (glob('tours/'.$idtour.'/*.xml') as $filename) {
		unlink($filename);
	} 

	
	/* Escribo */
	
	$fp = fopen('tours/'.$idtour.'/tour'.$version_xml.'.xml',"a");
			
	if($fp){
				
		fwrite($fp,$final_data);
		fclose($fp);

	}

}


?>