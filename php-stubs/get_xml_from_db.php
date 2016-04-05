<?php
$restrict = 1;
require("../inc/auth.inc");
require("../inc/conex.inc");


$idtour = $_POST['tour-id'];
$draft_subscript = $_POST["d"]; 


$ssqlp = "SELECT * FROM tours".$draft_subscript." where id = ".$idtour;
$result = mysql_query($ssqlp);	
$row = mysql_fetch_array($result);

$ssqlp = "SELECT * FROM panosxtour".$draft_subscript." where idtour = ".$idtour." order by ord";
$result = mysql_query($ssqlp);	
$row = mysql_fetch_array($result);



$output = '
<krpano onstart="startup();">


        <action name="startup">
                loadscene(get(scene[0].name),null,MERGE,BLEND(1));
        </action>

        <!-- disable the default progress bar -->
        <progress showload="none" showwait="none" />';


$ssqlp = "SELECT * FROM panosxtour".$draft_subscript." where idtour = ".$idtour." order by ord";

$result = mysql_query($ssqlp);	
while($row = mysql_fetch_array($result)){

        $output.= '

        <!-- SCENES -->
        <scene name="scene_'.$row["id"].'" title="'.htmlspecialchars($row["name"]).'" onstart=""  thumburl="'.$cdn.'/panos/'.$row["idpano"].'/pano.tiles/thumb200x100.jpg" lat="" lng="" heading=""  >
                        <view hlookat="'.$row["hlookat"].'" vlookat="'.$row["vlookat"].'" fovtype="MFOV" fov="105" maxpixelzoom="1.5" fovmin="60" fovmax="120" limitview="auto"  />

                        <preview url="'.$cdn.'/panos/'.$row["idpano"].'/pano.tiles/preview.jpg" />

                        <image>
                                <cube url="'.$cdn.'/panos/'.$row["idpano"].'/pano.tiles/pano_%s.jpg" />
                                <mobile>
                                        <cube url="'.$cdn.'/panos/'.$row["idpano"].'/pano.tiles/mobile_%s.jpg" />
                                </mobile>
                        </image>';

        //HOTSPOTS

        $ssqlp_htsp = "SELECT * FROM hotspots".$draft_subscript." where scene_id = ".$row["id"];
        $result_htsp = mysql_query($ssqlp_htsp);	
        while($row_htsp = mysql_fetch_array($result_htsp)){

                $output.= '
                        <hotspot style="'.$row_htsp["style"].'" name="'.htmlspecialchars($row_htsp["name"]).'" ath="'.$row_htsp["ath"].'" atv="'.$row_htsp["atv"].'"';

                switch ($row_htsp["type"]) {
                    case "info":
                        $output.= ' infotitle="'.htmlspecialchars($row_htsp["extra_infotitle"]).'" infotext="'.htmlspecialchars($row_htsp["extra_infotext"]).'" />';
                        break;
                    case "media":
                        $output.= ' pic="'.htmlspecialchars($row_htsp["extra_photourl"]).'" tooltip="'.htmlspecialchars($row_htsp["extra_tooltip"]).'" />';
                        break;
                    case "link":
                        $output.= ' linkedscene="'.$row_htsp["extra_linkedscene"].'" />';
                        break;
                }					

        }



        $output .= 	'
        </scene>';
}

$output.= '</krpano>';


echo $output;

?>
