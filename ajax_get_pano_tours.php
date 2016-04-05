	<?php require("inc/conex.inc");
		$idpano = $_POST["id"];
		$filename = $_POST["fn"];
		$ssql = "SELECT tours.title, tours.id as tourid , panosxtour.id as sceneid, panosxtour.idpano as panoid FROM `panosxtour`, tours WHERE tours.id = panosxtour.idtour and panosxtour.idpano = ".$idpano;
		$result = mysql_query($ssql);
	?>
           
           <div class="pop">
                <a href="#" class="closed"></a>
                <h2><?php echo $filename;?></h2>
                    <div class="message_box">
                        <p>This pano file is included as a scene in the following Virtual Tours:</p>
                    </div>
                    <div class="content_pop">
                
					<?php while($row = mysql_fetch_array($result)){
						//echo "SELECT * FROM panosxtour where idtour = ".$row["id"]." ORDER BY ord";
						$ssqlthumb = "SELECT * FROM panosxtour where idtour = ".$row["tourid"]." and idpano = ".$row["panoid"]."  ORDER BY ord";
						$resultthumb = mysql_query($ssqlthumb);
						$rowthumb = mysql_fetch_array($resultthumb);?>                            
                            <!-- pano -->
                            <div class="pano-item pano-item-manager">
                                <label class="check_action">
                                    <input class="form-user border-radius-4 select_checkbox" type="checkbox" name="sel[]" data-tour="<?php echo $row["tourid"];?>" data-scene="<?php echo $row["sceneid"];?>">
                                </label>
                                <div class="thumb-pano">
                                	<img src="<?php echo $cdn;?>/panos/<?echo $rowthumb["idpano"];?>/pano.tiles/thumb100x50.jpg" />
                                </div>
                                <div class="loader-item">
                                    <h3><?php echo $row["title"];?></h3>
                                </div>
                                <br clear="all">
                            </div>

					<?php }?>                            
                
                </div><!-- end content_pop -->
                
                <div class="btnBkg">
                    <a href="#" class="red-button border-radius-4 remove_selected_panos">REMOVE SCENE FROM SELECTED TOURS</a>
                </div>

            </div>