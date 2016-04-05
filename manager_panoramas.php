<?
    $restrict = 1;
	$page_title = "Spinattic | Manage pano files";

    require("inc/header.php");

	$elementosxpag  = (isset($_POST["epp"]) && $_POST["epp"] != "") ? $_POST["epp"] : 10;
    $pag            = isset($_POST["p"])? $_POST["p"] : 1;
    $registro       = isset($_REQUEST["registro"])? $_REQUEST["registro"] : 0;
	$registrosenpag = isset($_REQUEST["registrosenpag"])? $_REQUEST["registrosenpag"] : 0;
    $paginador      = '';
		
	if ($elementosxpag == 'all'){$elementosxpag = 10000000;}
	
	$cantpagmuestra = 10; //cantidad de pag que se muestran en el paginador del footer
	
	$indpag = 0;
	if ($pag > $cantpagmuestra){
	    $indpag = $pag - $cantpagmuestra;
	}

	$m_display = 'none';
	
?>
        
	    <script src="<?echo "http://".$_SERVER[HTTP_HOST]."/";?>js/core.manager-panoramas.js"></script>
	    
        <!-- loader -->            
		<div id="loading" class="loading" style="display: none;">
		    <div class="loading_img"></div>
		</div>
	
    
        <!--popup login-->
        <div class="overlay overlay_login edit-panorama" style="display:none;">
            <div class="pop">
                <a href="#" class="closed"></a>
                <h2>Edit Panorama</h2>
                <div class="content_pop">
	                <form class="pop-up">
	                    <label>
	                        <p>Title</p>
	                        <input class="info-tour border-radius-4 form-user" value="Panorama title">
	                    </label>
	                    <!--label>               
	                        <p>Privacy Configuration <a href="#" class="help"></a></p>
	                        <select class="info-tour border-radius-4 form-user">
	                            <option value="Country">- Country -</option>
	                            <option value="Country 1">Country 1</option>
	                            <option value="Country 2">Country 2</option>
	                            <option value="Country 3">Country 3</option>
	                        </select>
	                        <p><font>Anyone can search for and view</font></p>
	                    </label-->
	                    <a href="#" class="thumb-edit">
							<p>Edit pano thumbnail</p>
	                        <div class="thumb-pano"><img src="images/ejemplo/thumb-upload.jpg" ></div>
	                        <p class="text-info">Text info</p>
	                        <br clear="all">
				        </a>
	                    <div class="content-btn-pop">
	                    	<a href="#" class="grey-button border-radius-4">CANCEL</a>
			            	<a href="#" class="red-button border-radius-4">SAVE</a>
	                    </div>
	                </form>
	            </div>
            </div>
        </div>    
    
    
    


        <!--USED in tours-->
        <div class="overlay panocollection" style="display:none">
 
        </div> 
        <!--END used in tours -->        
        <div>
        	<h1 class="manage_tour">Manage Pano Files</h1>
		</div>
        <div class="wrapper wrapper-user manage_panoramas">

        	<hr class="space180px" />
			<div class="manager_tour">

<form id="form1" name="form1" method="post" action="">
<input type="hidden" value="<?echo $pag;?>" name="p" id="p">
			
                <label class="check_action">
                    <input class="border-radius-4" type="checkbox" id="tours-check-all">
                </label>
				
                <select class="form-user actions border-radius-4" name="panos-batch-actions" id="panos-batch-actions">
                        <option value="none">Choose an action</option>
                        <option value="remove">Delete Panos</option>
                        <option value="create">Create Tour with selection</option>
                        <!-- optgroup label="Privacy">
                                <option value="public">Public</option>
                                <option value="notlisted">Not Listed</option>
                                <option value="private">Private</option>
                        </optgroup-->                        
                </select>


				
                <select class="form-user actions border-radius-4" name="epp" id="epp" onchange="cambiarpag(1)">
                        <option value="">Elements per page</option>
                        <option value="25" <?if ($_POST["epp"] == '25')echo 'selected';?>>25</option>
                        <option value="50" <?if ($_POST["epp"] == '50')echo 'selected';?>>50</option>
                        <option value="100" <?if ($_POST["epp"] == '100')echo 'selected';?>>100</option>
                        <option value="all" <?if ($_POST["epp"] == 'all')echo 'selected';?>>Show All</option>
                </select>                
</form>    

                <a href="#" class="green-button border-radius-4 create-tour-with-selection">Create tour with selection</a>

                </div>


<?
		$ssqlp = "SELECT *, DATE_FORMAT(date,'%b %d %Y %h:%i %p') as fecha FROM panos where user = '".$_SESSION["usr"]."' and state = 1 ORDER BY date desc";
		$result = mysql_query($ssqlp);	
		
		$cantidad = mysql_num_rows($result);
		if($cantidad >0){
			while($registro < ($pag-1)*$elementosxpag){
				$row = mysql_fetch_array($result);
				$registro++;
			} 		
				while($row = mysql_fetch_array($result)){


					if($registrosenpag < $elementosxpag) {
					  	$registrosenpag ++;		
					  	
						$ssqlthumb = "SELECT * FROM panosxtour where idpano = ".$row["id"]." ORDER BY ord";
						//echo $ssqlthumb;
						$resultthumb = mysql_query($ssqlthumb);	
						$rowthumb = mysql_fetch_array($resultthumb);
					  	
					  	$cantidad_cant_tours = mysql_num_rows($resultthumb);					  	
?>		

                <!-- pano -->
                <div class="pano-item pano-item-manager">
                    <label class="check_action">
                        <input class="form-user border-radius-4 tour-batch-checkbox" type="checkbox" value="<?echo $row["id"];?>" name="ids[]">
                    </label>
					<div class="thumb-pano">
						<img src="<?php echo $cdn?>/panos/<?echo $row["id"];?>/pano.tiles/thumb100x50.jpg" />
					</div>
					<div class="loader-item">
                    	<h3><?echo $row["name"];?></h3>
	          			<p><?echo $row["fecha"];?></p>

                  
	                </div>
                    <br clear="all">
                    
                    <?php if($cantidad_cant_tours > 0){?>
   	                <a href="<?php echo $row["id"];?>" data-name="<?echo $row["name"];?>" data-cantidad= "<?php echo $cantidad_cant_tours; ?>" class="blue-buttom included">Included in <?php echo $cantidad_cant_tours; ?> tour(s)</a>
   	                <?php }else{?>
   	                <a href="#" class="grey-button">Not used</a>
   	                <?php }?>
   	                <a href="#" title="Delete pano file" class="delete-item pano-remove" data-id="<?echo $row["id"];?>"></a>
                </div>
                

<?}}
	
}else{
	$m_display = 'block';
}?>  
<div class="message_box centered_m" style="display:<?php echo $m_display;?>">
    <p>There are no panos uploaded yet. <br><a href="edit_virtual_tour.php" target="_self">Click here to upload your first panos and create a Virtual Tour.</a></p>
</div>
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
</html>



