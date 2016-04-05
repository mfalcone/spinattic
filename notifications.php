<?

$lastID = $_GET["lastID"];
$action = $_GET["action"];

if ($action != 'getLastPosts') {

$page_title = "Spinattic | Notifications";

$restrict = 1;
require("inc/header.php");
 
?>
		<script src="js/ajaxscrollhome.js"></script>
        
        <div>
		    <h1 class="notifications-title">NOTIFICATIONS</h1>
		</div>
		
		<div class="wrapper wrapper-user">
		<input type="hidden" value="notifications" id="action">
            <hr class="space180px">
            
			<?
			session_start();
			$ssqlp = "SELECT notifications.id as elid, notifications.*, users.*, DATE_FORMAT(date, '%d %b %Y') as fecha FROM notifications, users where notifications.source_id = users.id and target_id = ".$_SESSION["usr"]." order by date desc";
			$result = mysql_query($ssqlp);
			$i=0;
			$cant_reg=10;
			while($row = mysql_fetch_array($result)){
				$i++;
				
				if ($i > $cant_reg){
					break;
				}
								
				if($row["leido"] == 1){
					$ban_sep = 'read';
				}else{
					$ban_sep = '';
				}
				echo '
		            <div class="item notification '.$ban_sep.'" id="'.$i.'">
		                <div class="data">
		                    <p>
		                    <a href="profile.php?uid='.$row["id"].'">'.$row["username"].'</a> 
		                    '.$row["text"].'
		                    </p>
		                    <p class="date">'.$row["fecha"].'</p>
		                    <div class="avatars">
		                        <a class="" href="javascript:;"><img src="images/users/'.$row["avatar"].'" width="32" height="32" alt=""></a>
		                    </div>
		                    <a href="'.$i.'" class="delete-item tour-remove" rel="'.$row["elid"].'" original-title="Delete item"></a>
		                </div>
		                <div class="clear"></div>
		            </div>				
				';
				if($ban_sep == '' && $row["leido"] == 1){
					echo '<div class="divisor"></div>';
					$ban_sep = 'read';
				}else{
					mysql_query("update notifications set leido = 1 where id = ".$row["elid"]);
				}
			};

			if ($i <$cant_reg){
				echo '<input type="hidden" class="nomore">';
			}		

	
			?>            
                	
        </div>
			
		<script type="text/javascript">
		jQuery(document).ready(function(){
			$('.campana').hide();
		});
		</script>
			
	<?php require_once("inc/footer.php");?>
</html>

<?
}else{
	require_once("inc/conex.inc");
	require_once("inc/functions.inc");
	require_once("inc/auth.inc");
		
	$cant_reg = 5;
	$getPostsText = "";
	session_start();
	$ssqlp = "SELECT notifications.id as elid, notifications.*, users.*, DATE_FORMAT(date, '%d %b %Y') as fecha FROM notifications, users where notifications.source_id = users.id and target_id = ".$_SESSION["usr"]." order by date desc";
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
		echo '
		<div class="item notification '.$ban_sep.'" id="'.$i.'">
		<div class="data">
		<p>
		<a href="profile.php?uid='.$row["id"].'">'.$row["username"].'</a>
		'.$row["text"].'
		</p>
		<p class="date">'.$row["fecha"].'</p>
		<div class="avatars">
		<a class="" href="javascript:;"><img src="images/users/'.$row["avatar"].'" width="32" height="32" alt=""></a>
		</div>
		<a href="'.$i.'" class="delete-item tour-remove" rel="'.$row["elid"].'" original-title="Delete item"></a>
		</div>
		<div class="clear"></div>
		</div>
		';
		if($ban_sep == '' && $row["leido"] == 1){
			echo '<div class="divisor"></div>';
			$ban_sep = 'read';
		}else{
			mysql_query("update notifications set leido = 1 where id = ".$row["elid"]);
		}
	};	
	if ($j <$cant_reg){
		echo '<input type="hidden" class="nomore">';
	}
}
?>