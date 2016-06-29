<?
  
    $restrict = 1;
    $page_title = "Spinattic | Manage Virtual Tours";
    require("inc/header.php");
	
	$elementosxpag  = (isset($_POST["epp"]) && $_POST["epp"] != "") ? $_POST["epp"] : 10;
    $pag            = isset($_POST["p"])? $_POST["p"] : 1;
    $registro       = isset($_REQUEST["registro"])? $_REQUEST["registro"] : 0;
	$registrosenpag = isset($_REQUEST["registrosenpag"])? $_REQUEST["registrosenpag"] : 0;
    $paginador      = '';
    $sby = (isset($_POST["sby"]) && $_POST["sby"] != "") ? $_POST["sby"] : "id";
    $order = (isset($_POST["val_orderBy"]) && $_POST["val_orderBy"] != "") ? $_POST["val_orderBy"] : "desc";
    
    if ($elementosxpag == 'all'){$elementosxpag = 10000000;}
	
	$cantpagmuestra = 10; //cantidad de pag que se muestran en el paginador del footer

	$indpag = 0;
	if ($pag > $cantpagmuestra){
	    $indpag = $pag - $cantpagmuestra;
	}


?>

            
        <link rel="stylesheet" type="text/css" href="css/tipsy.css" />
        <meta name="robots" content="noindex">	
        <script type="text/javascript" src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>js/jquery.tipsy.js"></script>
        <script src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>js/core-actions.js"></script>
        <script src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>js/core.manager-tours.inc.js"></script>   


<div class="overlay confirm-action" style="display:none;">
    <div class="pop">
        <a href="#" class="closed"></a>
        <h2>Virtual Tours Manager</h2>
        <div class="content_pop">
            <form class="pop-up">
                <label>
                    <p>Are you sure you want to delete this tour?</p>                
                </label>                        
                <div class="content-btn-pop">
                    <a href="#" class="grey-button border-radius-4">NO</a>
                    <a href="#" class="red-button border-radius-4 save-button">YES</a>
                </div>
            </form>
        </div>
    </div>
</div>    
<!--popup select privacy-->
<div class="overlay select-privacy" style="display:none;">
    <div class="pop">
        <a href="#" class="closed"></a>
        <h2>Virtual Tours Manager</h2>
        <div class="content_pop">
            <form class="pop-up">
                <label>               
                    <p>Choose privacy level </p>
                    <select class="info-tour border-radius-4 form-user" name="privacy-level" id="privacy-level">
                        <option value="public">Public</option>
                        <option value="private">Private</option>
                        <option value="notlisted">Not Listed</option>                    
                    </select>                
                </label>            
                <div class="content-btn-pop">
                    <a href="#" class="grey-button border-radius-4">CANCEL</a>
                    <a href="#" class="red-button border-radius-4 save-button">SAVE</a>
                </div>
            </form>
        </div>
    </div>
</div>   


        <div>
        	<h1 class="manage_tour">Manage Virtual Tours</h1>
		</div>
        
       
        <div class="wrapper wrapper-user manage_tours">
            
            <hr class="space180px" />
			<div class="manager_tour manager_tour_drag_top">

<form id="form1" name="form1" method="post" action="">
<input type="hidden" value="<?echo $pag;?>" name="p" id="p">
			
                <label class="check_action">
                    <input class="border-radius-4" type="checkbox" id="tours-check-all">
                </label>
				
                <select class="form-user actions border-radius-4" name="tours-batch-actions" id="tours-batch-actions">
                        <option value="none">Choose an action</option>
                        <option value="remove">Delete Tours</option>
                        <optgroup label="Privacy">
                                <option value="public">Public</option>
                                <option value="notlisted">Not Listed</option>
                                <option value="private">Private</option>
                        </optgroup>                        
                </select>


				
                <select class="form-user actions border-radius-4" name="epp" id="epp" data-page="1">
                        <option value="">Elements per page</option>
                        <option value="25" <?if ($_POST["epp"] == '25')echo 'selected';?>>25</option>
                        <option value="50" <?if ($_POST["epp"] == '50')echo 'selected';?>>50</option>
                        <option value="100" <?if ($_POST["epp"] == '100')echo 'selected';?>>100</option>
                        <option value="all" <?if ($_POST["epp"] == 'all')echo 'selected';?>>Show All</option>
                </select>      
                
               <select class="form-user actions border-radius-4" name="sby" id="sby" data-page="1">
                        <option value="">Sort by</option>
                        <option value="title" <?if ($_POST["sby"] == 'title')echo 'selected';?>>A-Z</option>
                        <option value="publish" <?if ($_POST["sby"] == 'publish')echo 'selected';?>>by publish date</option>
                        <option value="updated" <?if ($_POST["sby"] == 'updated')echo 'selected';?>>by last update</option>
                        <option value="amount_panos" <?if ($_POST["sby"] == 'amount_panos')echo 'selected';?>>by amount of panoramas</option>
                        <option value="likes" <?if ($_POST["sby"] == 'likes')echo 'selected';?>>by likes</option>
                        <option value="views" <?if ($_POST["sby"] == 'views')echo 'selected';?>>by views</option>
                </select>

                <a href="" id="orderBy" class="<?php echo $order;?>">Order By <?php echo $order;?></a>
                <input type="hidden" name="val_orderBy" id="val_orderBy" value="<?php echo $order;?>">
</form>                    

                
                </div>
                <div class="manager_tour manager_tour_drag_top manager_tour_controls">
                    <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/customizer/" id="new-tour" class="green-button border-radius-4" target="_blank">Create New Tour</a>
                
                    <a href="#" id="custom-sign" class="green-button border-radius-4">Custom signature</a>

                </div>


<?		
		$ssqlp = "(SELECT *, DATE_FORMAT(date,'%b %d %Y %h:%i %p') as fecha, 'published' as sourcetable FROM tours where iduser = '".$_SESSION["usr"]."')
		UNION
		(SELECT *, DATE_FORMAT(date,'%b %d %Y %h:%i %p') as fecha, 'draft' as sourcetable FROM tours_draft where iduser = '".$_SESSION["usr"]."' and id not in (select id from tours where iduser = '".$_SESSION["usr"]."'))
		ORDER BY ";
		switch ($_POST["sby"]){
		case "title":
			$ssqlp .= "title";
			break;
		case "publish":
			$ssqlp .= "state desc, date";
			break;
		case "updated":
			$ssqlp .= "date_updated";
			break;
		case "amount_panos":
			$ssqlp = "(SELECT tours.id, friendly_url, title, date, date_updated, DATE_FORMAT(date,'%b %d %Y %h:%i %p') as fecha, views, likes, 'published' as sourcetable, count(idtour) as cant FROM tours left join panosxtour on tours.id = panosxtour.idtour where iduser = '".$_SESSION["usr"]."' group by tours.id, title, date, date_updated, fecha, views, likes, sourcetable)
			UNION
			(SELECT tours_draft.id, friendly_url, title, date, date_updated, DATE_FORMAT(date,'%b %d %Y %h:%i %p') as fecha, views, likes, 'draft' as sourcetable, count(idtour) cant FROM tours_draft left join panosxtour_draft on tours_draft.id = panosxtour_draft.idtour where iduser = '".$_SESSION["usr"]."' and tours_draft.id not in (select id from tours where iduser = '".$_SESSION["usr"]."') group by tours_draft.id, title, date, date_updated, fecha, views, likes, sourcetable)
			ORDER BY cant";
			break;
		case "likes":
			$ssqlp .= "likes";
			break;
		case "views":
			$ssqlp .= "views";
			break;
		default:
			$ssqlp .= "id";
		}
		
		$ssqlp .= " ".$order;
		
		//echo $ssqlp;
		
		$result = mysql_query($ssqlp);
		
		$cantidad = mysql_num_rows($result);
		if($cantidad >0){
			while($registro < ($pag-1)*$elementosxpag){
				$row = mysql_fetch_array($result);
				$registro++;
			} 		
				while($row = mysql_fetch_array($result)){
					
					/*
					if($row["sourcetable"] == 'draft'){
						$ssqlthumb = "SELECT * FROM panosxtour_draft where idtour = ".$row["id"]." ORDER BY ord";
					}else{
						$ssqlthumb = "SELECT * FROM panosxtour where idtour = ".$row["id"]." ORDER BY ord";
					}
					//echo $ssqlthumb;
					$resultthumb = mysql_query($ssqlthumb);
					$cant_panos = mysql_num_rows($resultthumb);
					$rowthumb = mysql_fetch_array($resultthumb);
					*/
					
					if($registrosenpag < $elementosxpag) {
					  	$registrosenpag ++;		

                                
                                
				if($row["privacy"] == '_private')$descprivacy = 'Private';
				elseif($row["privacy"] == '_notlisted')$descprivacy = 'Not Listed';
				elseif($row["privacy"] == '_public')$descprivacy = 'Public';
                else $descprivacy = 'Private'; // default desc
?>

                <!-- pano -->
                <div class="pano-item pano-item-manager">
                    <label class="check_action">
                        <input class="form-user border-radius-4 tour-batch-checkbox" type="checkbox" value="<?echo $row["id"];?>" name="ids[]" >                        
                    </label>
                    <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/customizer/#tour/<?echo $row["id"];?>" target="_blank">
                        <div class="thumb-pano">
                        <?php
						$thumb = $http.$_SERVER[HTTP_HOST].'/images/thumb200x100.jpg';
                        //$thumb = 'panos/thumb200x100.jpg';
                        
                        //$file = $cdn.'/panos/'.$rowthumb["idpano"].'/pano.tiles/thumb200x100.jpg';
                        $file = $row["tour_thumb_path"].'thumb200x100.jpg';
                        $file_headers = @get_headers($file);
                        if(!($file_headers[0] == 'HTTP/1.1 404 Not Found') && $row["tour_thumb_path"] != '') {
                        	//$thumb = $cdn.'/panos/'.$rowthumb["idpano"].'/pano.tiles/thumb200x100.jpg';
                        	$thumb = $row["tour_thumb_path"].'thumb200x100.jpg';
                        }                        
                        
                       /* 
                        if(is_file($cdn.'/panos/'.$rowthumb["idpano"].'/pano.tiles/thumb200x100.jpg')){
                        	$thumb = $cdn.'/panos/'.$rowthumb["idpano"].'/pano.tiles/thumb200x100.jpg';
                        };
                        */?>
                            <img src="<?echo $thumb;?>" width="200" height="100"/>
                            
                            <?php if ($cant_panos > 0){?>
                            <div class="counterbkg">
                                <p><?php echo $cant_panos;?> scene(s) in this tour</p>
                            </div>
                            <?php }?>

                        </div>
                    </a>
                    <div class="loader-item">
                        <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/customizer/#tour/<?echo $row["id"];?>" target="_blank"><h3><?echo $row["title"];?></h3></a>
                        <p><?echo $row["fecha"];?></p>
                        <p>Status:
                        <?if(ucfirst(trim($row["sourcetable"])) == 'Published'){
                        	echo '<font color = "#6bba45">'.ucfirst($row["sourcetable"]).'</font>';                        	
                        }else{
                        	echo '<font color = "#d6b007">'.ucfirst($row["sourcetable"]).'</font>';
                        }?>
                        
                        </p>
                        <p>
	                        <?php if($row["state"] == 'publish'){?><a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST].'/'.$_SESSION["friendly_url"].'/'.$row["friendly_url"];?>" style="color:#457EC1;" target="_blank">Open tour page &raquo;</a><?php }?>
                        </p>
                        
           <!--
                        <div class="info-activity">
                            <div class="tours"><b>In this tour:</b> Pano 1 - Pano 2 - Pano 3</div>
                            <br clear="all">
                        </div>
           -->
                    </div>
                    <br clear="all">
                    <a href="#" title="Delete Tour" class="delete-item tour-remove" rel="id=<?echo $row["id"];?>;"></a>
                    <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/customizer/#tour/<?echo $row["id"];?>" class="edit-buttom" title="Edit tour" target="_blank"></a>

                    
                    <a href="#" class="<?echo $row["privacy"];?> visibility tour-privacy" title="<?echo $descprivacy;?>" rel="id=<?echo $row["id"];?>;"></a>
                </div>
                
<?}}
}else{?>
<div class="message_box centered_m">
    <p>There are no tours in your portfolio yet. <br><a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/customizer" target="_blank">Create your first tour.</a></p>
</div>
<?}?>  

<div class="paginator">
                
	<?if($pag > 1){?>
	
	<a href="#" target="_self" data-page="<?echo $pag-1;?>" class="Link2">&lt; Prev</a> <span class="separadorLink2">|</span>
	
	<?}?>
	
	<?while( $indpag < $cantidad / $elementosxpag && $cantpagmuestra >0){
			$indpag++;
			$cantpagmuestra--;
				if($indpag == $pag){
					$paginador .= '<span class="active">'.$indpag.'</span>';
				}else{
					$paginador .= '<a href="#"  data-page="'.$indpag.'">'.$indpag.'</a>';
				}
				$paginador .= ' | ';
		}
		if ($indpag > 1){
			echo substr($paginador,0,strlen($paginador)-3);
		}?>
	
	<?if($pag < $cantidad/$elementosxpag ){?>     
		<a href="#" data-page="<?echo $pag+1;?>" target="_self" class="Link2">Next &gt;</a>
	<?}?>  

</div>              
              
            </div>
                
    <?php require_once("inc/footer.php");?>
    <div class="modal-wrapper modal-signature">
        <div class="modal">
            <h2 class="">Custom signature</h2>
            <iframe src="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/customizer/signatureuploader/index.php" frameborder="0"></iframe>
        </div>
    </div>  
</html>



