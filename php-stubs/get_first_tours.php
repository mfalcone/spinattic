<?

session_start();

if(isset($_POST["order"]) && $_POST["order"] != ''){ //Estoy recibiendo la llamada por ajax
	require_once(realpath($_SERVER["DOCUMENT_ROOT"])."/inc/conex.inc");
	$order = $_POST["order"];
}

if(isset($_POST["first_show"]) && $_POST["first_show"] != ''){//Estoy recibiendo la llamada por ajax
	$first_show = $_POST["first_show"];
}

if ($order != ''){
	$order = " ORDER BY ".$order." desc";
}else{
	$order = " ORDER BY priority desc";
}
if ($conditions != ''){
	$conditions = " and ".$conditions." ";
}
$i = 0;

$ssqlp = "SELECT tours.id as id, tours.comments as comments, users.id as iduser, users.friendly_url as userfriendly, users.nickname as nickname, users.username as user, users.avatar as avatar, tours.title as title, tours.views as views, tours.allow_votes as allow_votes, tours.allow_comments as allow_comments, tours.likes as likes, tours.priority, tours.tour_thumb_path, tours.friendly_url FROM tours, users where tours.iduser = users.id and tours.state = 'publish' and tours.privacy = '_public' ".$conditions.$order;
$result = mysql_query($ssqlp);
//echo $ssqlp;

while ($row = mysql_fetch_array($result)){

	
	
	$class_like = "";
	
	/*
	$ssqlthumb = "SELECT * FROM panosxtour where idtour = ".$row["id"]." ORDER BY ord LIMIT 1";
	$resultthumb = mysql_query($ssqlthumb);
	$rowthumb = mysql_fetch_array($resultthumb);
	*/


	$ssqllike = "SELECT * FROM likes where idtour = ".$row["id"]." and iduser = ".$_SESSION["usr"];
	$resultlike = mysql_query($ssqllike);
	if($rowlike = mysql_fetch_array($resultlike)){
		$class_like = 'liked';
	}	
	
	//echo $ssqlthumb;
	
	$i++;
	if ($i > $first_show){
		break;
	}

	$postText.='
	
	<div class="post" id="'.$i.'">
	<div class="thumb">
	<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'/'.$row["friendly_url"].'">
	</a>
	<img src="'.$row["tour_thumb_path"].'thumb500x250.jpg">
	</div>
	<div class="home_thumb_wrapper">
	<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'" class="user"><img src="http://'.$_SERVER[HTTP_HOST].'/images/users/'.$row["avatar"].'" width="43" height="43" alt="'.$row["user"].'">
	</a>
	<div class="by">
	<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'">by '.$row["user"].'
	</a>
	</div>
	<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'/'.$row["friendly_url"].'" class="text">
	<p>
	'.$row["title"].'
	</p>
	</a>
	</div>
	<div class="count">
	<div class="views">
	'.$row["views"].'
	</div>';
		
	if($row["allow_votes"] == 'on'){
		$postText.='<a href="javascript:void(0)" id="like'.$row["id"].'" class="like'.$row["id"].' likes '.$class_like.'" onclick="like('.$row["id"].');">'.$row["likes"].'</a>';
	};

	if($row["allow_comments"] == 'on'){
		$postText.='<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'/'.$row["friendly_url"].'#comments"  class="comments">'.$row["comments"].'</a>';
	}	
	
	$postText.='<br clear="all"></div>';

	if($row["allow_comments"] == 'on'){
		
		$breaks = array("&lt;br /&gt;", "<br />","<br>","<br/>");
		
		$ssqlc = "SELECT comments.comments, comments.id, comments.iduser, DATE_FORMAT(comments.date,'%d/%m/%Y') as fecha, users.avatar, users.friendly_url as userfriendly, users.nickname, users.username FROM comments, users where comments.idtour = ".$row["id"]." and comments.iduser = users.id order by comments.date LIMIT 3";
		//$ssqlc = "SELECT * FROM comments where idtour = ".$row["id"]." ORDER BY date DESC LIMIT 3";
		$resultc = mysql_query($ssqlc);
			
		while ($rowc = mysql_fetch_array($resultc)){
			
			$thecomment = str_ireplace($breaks,  "<br>", $rowc["comments"]);
			
		$postText.='
		<div class="comments-text">
		<a href="http://'.$_SERVER[HTTP_HOST].'/'.$rowc["userfriendly"].'" class="user">
			<img src="http://'.$_SERVER[HTTP_HOST].'/images/users/'.$rowc["avatar"].'" width="43" height="43" alt="'.$rowc["username"].'"> 
		</a>
		<p>
			<a href="http://'.$_SERVER[HTTP_HOST].'/'.$rowc["userfriendly"].'"><strong>'.$rowc["username"].': </strong> </a><br>
			'.substr($thecomment,0,110).' ...
		</p>
		<br clear="all">
		</div>';
		}
	}	
	$postText.='</div>';
		
}
if ($i <$first_show){
	$postText .= '<input type="hidden" class="nomore">';
}

if($_POST["print_result"] == 1){ //Estoy recibiendo la llamada por ajax
	echo $postText;
}


?>