<?php

$subfolder_add = '../../';

/*
if($_GET["ajax_call"]=='on'){
	require("../inc/conex.inc");
	global $subfolder_add;
	$subfolder_add = '../';
	make_tour_files($_GET["idtour"]);

}
*/

function make_tour_files($idtour)
{
	global $subfolder_add;
	global $cdn_string;
	global $cdn;
	global $bucket_config_file;
	
    $ssqlp = "SELECT * FROM tours where id = ".$idtour;
    $result = mysql_query($ssqlp);	
    $row = mysql_fetch_array($result);
    
    if($row["state"]=='publish'){ //si esta en draft no genero el archivo
	
		$version_xml = $row["version_xml"];
		$title = htmlspecialchars ($row["title"]);	
		$description_t = htmlspecialchars ($row["description"]);	
		$location = htmlspecialchars ($row["location"]);
		$userid = $row["iduser"];
		$skin_id = $row["skin_id"];
		
		$thumb_width = $row["thumb_width"];
		$thumb_height = $row["thumb_height"];
		$thumb_margin = $row["thumb_margin"];
		
		if($row["enable_avatar"] == 'on'){
			$enable_avatar = 'true';
		}else{
			$enable_avatar = 'false';
		}

		if($row["enable_title"] == 'on'){
			$enable_title = 'true';
		}else{
			$enable_title = 'false';
		}
		
		
		$ssqlp = "SELECT * FROM users where id = ".$userid;
		$result = mysql_query($ssqlp);
		$row = mysql_fetch_array($result);		
		
		$user = htmlspecialchars ($row["username"]);		
		$avatar = htmlspecialchars ($row["avatar"]);
	
		$ssqlp = "SELECT * FROM panosxtour where idtour = ".$idtour." order by ord";
		$result = mysql_query($ssqlp);	
		$row = mysql_fetch_array($result);
		//$preview = '../../panos/'.$row["idpano"].'/index.tiles/preview.jpg';
		$preview = $cdn.'/panos/'.$row["idpano"].'/pano.tiles/preview.jpg';
		
		//busco la skin
		$ssqlp_skin = "SELECT * FROM skins where id = ".$skin_id;
		$result_skin = mysql_query($ssqlp_skin);
		if($row_skin = mysql_fetch_array($result_skin)){
			$skin = $row_skin["skin"];
		}else{
			$skin = 'SSI_1.0';    //si por algo no existe la skin, pongo esta por defecto
		}
		
				
		
	
	/* FOLDER */

		if (!is_dir($subfolder_add.'tours/'.$idtour)) mkdir ($subfolder_add.'tours/'.$idtour);
		chmod($subfolder_add.'tours/'.$idtour, 0777);
	
	/* HTML */
	
		/* Genero Data */
		
		
		$final_data = '
			<?php 
	
			require_once("../../inc/conex.inc");
		
			$id = "'.$idtour.'";
			
			$ip = $_SERVER[\'REMOTE_ADDR\'];
			
			$ssqlp = "SELECT * FROM tours where id = ".$id." and state = \'publish\' and (privacy = \'_public\' or privacy = \'_notlisted\')";
			$result = mysql_query($ssqlp);
			
			if(!($row = mysql_fetch_array($result))){
				header("Location: http://".$_SERVER[HTTP_HOST]);
			}
			
			
			$user = $row["user"];
			$title = $row["title"];
			$description = $row["description"];
			$meta_type=\'tour\';
			
			$page_title = $title." by ".$user." | Spinattic.com";
			
			
			$ssqlp = "SELECT * FROM views where idtour = ".$id." and ip like \'%".$ip."%\' and date > DATE_SUB(now(),INTERVAL 72 HOUR)";
		
			$result = mysql_query($ssqlp);
				
			if(!($row = mysql_fetch_array($result))){
				mysql_query("update tours_draft set views = views + 1 where id = ".$id);
				mysql_query("insert into views values (".$id.",\'".$ip."\', now())");
				mysql_query("update tours set views = views + 1 where id = ".$id);
			}			
			
			
			?>
			<!DOCTYPE html>
			<html>
			<head>
				<meta name="viewport" content="target-densitydpi=device-dpi, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
				<meta name="apple-mobile-web-app-capable" content="yes" />
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<title><?php echo $page_title;?></title>
				
				<?php require_once("../../inc/fk-meta.inc");?>
	
				<style>
					html { height:100%; }
					body { height:100%; overflow: hidden; margin:0; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#FFFFFF; background-color:#000000; }
					a{ color:#AAAAAA; text-decoration:underline; }
					a:hover{ color:#FFFFFF; text-decoration:underline; }
				</style>
				
			<?if($environment==\'prod\'){
				require_once("../../inc/ganalitycs.inc");
			}?>					
				
			</head>
			<body>
	
			<script src="../../player/tour.js"></script>
	
			<div id="pano" style="width:100%; height:100%;">
				<noscript><table style="width:100%;height:100%;"><tr style="valign:middle;"><td><div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div></td></tr></table></noscript>
				<script>
					embedpano({swf:"../../player/tour.swf", xml:"../../customizer/data/xml.php?id='.$idtour.'", target:"pano", wmode:"opaque", passQueryParameters:true});
				</script>
			</div>
	
			</body>
			</html>
		';
	
	
		/* Elimino todos los html y php que haya */
	
		foreach (glob($subfolder_add.'tours/'.$idtour.'/*.html') as $filename) {
			unlink($filename);
		} 
		foreach (glob($subfolder_add.'tours/'.$idtour.'/*.php') as $filename) {
			unlink($filename);
		}	
		
		/* Escribo */
		
		$fp = fopen($subfolder_add.'tours/'.$idtour.'/index.php',"a");
				
		if($fp){
					
			fwrite($fp,$final_data);
			fclose($fp);
	
		}
	
	
	
	
	/* XML */
	
	
		/* REEMPLAZADO POR XML.PHP 
		
		$final_data = '
		
			<krpano>
				<contextmenu><item name="Spinattic" caption="Spinattic.com" onclick="openurl(http://'.$_SERVER[HTTP_HOST].',_blank); " /></contextmenu>
			
				<settings name="toursettings" tourtitle="'.$title.'" description="'.$description_t.'" location="'.$location.'"  topavatar="'.$enable_avatar.'" toptitles="'.$enable_title.'"/>
				
				<include url="../../php-stubs/xml_usersettings.php?id='.$userid.'" />
				
				<settings name="SSI_interface" color="'.$skin.'" thumbwidth="'.$thumb_width.'" thumbheight="'.$thumb_height.'" margin="'.$thumb_margin.'"/>

				<include url="http://'.$_SERVER[HTTP_HOST].'/interfaces/SSI_1.0/interface.xml" devices="desktop" />
				<include url="http://'.$_SERVER[HTTP_HOST].'/interfaces/SSI_1.0/interface_m.xml" devices="iphone|ipad|android|mobile|tablet|touchdevice|gesturedevice" />
				
	
				<!-- disable the default progress bar -->
				<progress showload="none" showwait="none" />';
				
	
			$ssqlp = "SELECT * FROM panosxtour where idtour = ".$idtour." order by ord";
			$result = mysql_query($ssqlp);	
			while($row = mysql_fetch_array($result)){
		
				$final_data.= '
	
				<!-- SCENES -->
				<scene name="scene_'.$row["id"].'" title="'.htmlspecialchars($row["name"]).'" onstart=""  thumburl="'.$cdn.'/panos/'.$row["idpano"].'/pano.tiles/thumb200x100.jpg" lat="" lng="" heading=""  >
						<view hlookat="'.$row["hlookat"].'" vlookat="'.$row["vlookat"].'" fovtype="MFOV" fov="105" fovmin="60" fovmax="120" limitview="auto"  />
	
						<preview url="'.$cdn.'/panos/'.$row["idpano"].'/pano.tiles/preview.jpg" />
	
						<image>
							<cube url="'.$cdn.'/panos/'.$row["idpano"].'/pano.tiles/pano_%s.jpg" />
							<mobile>
								<cube url="'.$cdn.'/panos/'.$row["idpano"].'/pano.tiles/mobile_%s.jpg" />
							</mobile>
						</image>';
	
				//HOTSPOTS
	
				$ssqlp_htsp = "SELECT * FROM hotspots where scene_id = ".$row["id"];
				$result_htsp = mysql_query($ssqlp_htsp);	
				while($row_htsp = mysql_fetch_array($result_htsp)){
				
					$final_data.= '
						<hotspot style="'.$row_htsp["style"].'" name="'.htmlspecialchars($row_htsp["name"]).'" ath="'.$row_htsp["ath"].'" atv="'.$row_htsp["atv"].'"';
	
					switch ($row_htsp["type"]) {
					    case "info":
					        $final_data.= ' infotitle="'.str_replace("'", "´", htmlspecialchars($row_htsp["extra_infotitle"])).'" infotext="'.str_replace("'", "´", htmlspecialchars($row_htsp["extra_infotext"])).'" />';
					        break;
					    case "media":
					        $final_data.= ' pic="'.htmlspecialchars($row_htsp["extra_photourl"], ENT_QUOTES).'" tooltip="'.htmlspecialchars($row_htsp["extra_tooltip"], ENT_QUOTES).'" />';
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
	
	
	
		foreach (glob($subfolder_add.'tours/'.$idtour.'/*.xml') as $filename) {
			unlink($filename);
		} 
	
		
		
		$fp = fopen($subfolder_add.'tours/'.$idtour.'/tour'.$version_xml.'.xml',"a");
				
		if($fp){
					
			fwrite($fp,$final_data);
			fclose($fp);
	
		}
		
		*/
		
    }
    

}



?>