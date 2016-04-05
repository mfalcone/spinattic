<?

$lastID = $_GET["lastID"];
$action = $_GET["action"];

if ($action != 'getLastPosts') {

$restrict = 1;
require("inc/conex.inc");

 
			session_start();
			$ssqlp = "SELECT notifications.id as elid, notifications.*, users.*, DATE_FORMAT(date,'%b %d %Y %h:%i %p') as fecha FROM notifications, users where notifications.source_id = users.id and target_id = ".$_SESSION["usr"]." order by date desc";
			$result = mysql_query($ssqlp);
			$cant = mysql_num_rows($result);
			if($cant > 0){
			$i=0;
			$cant_reg=10;
			while($row = mysql_fetch_array($result)){
				$i++;
				
				if ($i > $cant_reg){
					break;
				}
								
				if($row["checked"] == 1){
					$ban_sep = 'read';
				}else{
					$ban_sep = '';
				}
				echo '<li class="item notifications notif_item '.$ban_sep.'" data-notif_id = "'.$row["elid"].'" id="'.$i.'">
            			<a href="http://'.$_SERVER[HTTP_HOST].'/profile.php?uid='.$row["id"].'" class="avatar">
            				<img src="http://'.$_SERVER[HTTP_HOST].'/images/users/'.$row["avatar"].'" alt="user">
            			</a>
            			<p>
            			<a href="http://'.$_SERVER[HTTP_HOST].'/profile.php?uid='.$row["id"].'">'.$row["username"].'</a>
            			
            				'.$row["text"].'
            			</p>
            			<span class="date">'.$row["fecha"].'</span>
            			<a href="'.$i.'" class="delete-item tour-remove" rel="'.$row["elid"].'" original-title="Delete item"></a>
            		</li>';
				
				if($ban_sep == '' && $row["leido"] == 1){
					//echo '<div class="divisor"></div>';
					$ban_sep = 'read';
				}
			};
			}else{
				echo '<li class="item notifications notif_item read" ><p>There are no notifications to show... yet.</p></li>';
			}

			if ($i <$cant_reg){
				echo '<input type="hidden" class="nomore_notif">';
			}		

	
			?>            
                	
        </div>
			
		<script type="text/javascript">
		jQuery(document).ready(function(){
			$('.campana').hide();
		});
		</script>
			

<?
}else{
	require_once("inc/conex.inc");
	require_once("inc/functions.inc");
	require_once("inc/auth.inc");
		
	$cant_reg = 5;
	$getPostsText = "";
	session_start();
	$ssqlp = "SELECT notifications.id as elid, notifications.*, users.*, DATE_FORMAT(date,'%b %d %Y %h:%i %p') as fecha FROM notifications, users where notifications.source_id = users.id and target_id = ".$_SESSION["usr"]." order by date desc";
	$result = mysql_query($ssqlp);

	
	while ($row = mysql_fetch_array($result)){
		$i++;
		if ($i >= $lastID){
			break;
		};
	}
	
	while($row = mysql_fetch_array($result)){
		if ($j >= $cant_reg){
			break;
		};
				
		$i++;
		$j++;
	
		if($row["leido"] == 1){
			$ban_sep = 'read';
		}else{
			$ban_sep = '';
		}
				echo '<li class="item notifications notif_item '.$ban_sep.'" id="'.$i.'">
            			<a href="http://'.$_SERVER[HTTP_HOST].'/profile.php?uid='.$row["id"].'" class="avatar">
            				<img src="http://'.$_SERVER[HTTP_HOST].'/images/users/'.$row["avatar"].'" alt="user">
            			</a>
            			<p>
            			<a href="http://'.$_SERVER[HTTP_HOST].'/profile.php?uid='.$row["id"].'">'.$row["username"].'</a>
            			
            				'.$row["text"].'
            			</p>
            			<span class="date">'.$row["fecha"].'</span>
            			<a href="'.$i.'" class="delete-item tour-remove" rel="'.$row["elid"].'" original-title="Delete item"></a>
            		</li>				
				';
		if($ban_sep == '' && $row["leido"] == 1){
			//echo '<div class="divisor"></div>';
			$ban_sep = 'read';
		}
	};	
	if ($j <$cant_reg){
		echo '<input type="hidden" class="nomore_notif">';
	}
}

mysql_query("update notifications set leido = 1 where target_id = ".$_SESSION["usr"]);
?>