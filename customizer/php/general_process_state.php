<?php
$restrict = 1;

ini_set("display_errors", 0);

//require("../inc/auth.inc");
//require("../inc/conex.inc");

require_once("functions.php");

$upload_dir = '../panos/';

session_write_close();

// Helper functions

function exit_status($return)
{
    echo json_encode($return);
    exit;
}


$ssqlp = "select * from general_process_log where proc_id = '".$_POST["proc_id"]."' and user_id = '".$_SESSION['usr']."' and step_checked = 0 order by id";
$result = mysql_query($ssqlp);

if($row = mysql_fetch_array($result)){
	mysql_query("update general_process_log set step_checked = 1 where id = '".$row["id"]."'");
	exit_status(array(
			'pano_id' => $row["pano_id"],
			'scene_id' => $row["scene_id"],
			'tour_id' => $row["tour_id"],
			'filename' => $row["filename"],
			'state' => $row["step"],
			'state_desc' => $row["step_desc"],
			'thumb_path' => $cdn. str_replace('..', '', $upload_dir) . $row["pano_id"] . '/pano.tiles/thumb200x100.jpg'
	));
}else{
	exit_status(array(
			'state' => "w",
			'state_desc' => "Waiting ..."
	));	
}
	


?>
