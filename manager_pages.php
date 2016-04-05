<?

$restrict = 2;

require("inc/header.php");

$elementosxpag  = (isset($_POST["epp"]) && $_POST["epp"] != "") ? $_POST["epp"] : 10;
$pag            = isset($_POST["p"])? $_POST["p"] : 1;
$registro       = isset($_REQUEST["registro"])? $_REQUEST["registro"] : 0;
$registrosenpag = isset($_REQUEST["registrosenpag"])? $_REQUEST["registrosenpag"] : 0;
$paginador      = '';


if ($elementosxpag == 'all'){
	$elementosxpag = 10000000;
}

$cantpagmuestra = 10; //cantidad de pag que se muestran en el paginador del footer

$indpag = 0;
if ($pag > $cantpagmuestra){
	$indpag = $pag - $cantpagmuestra;
}


?>
<script src="<?echo "http://".$_SERVER[HTTP_HOST]."/";?>js/core.manager-panoramas.js"></script>
        <div class="wrapper wrapper-manager_posts">
        	<h1 class="blog">Static pages</h1>
		    <hr class="space180px" />
			
			<header class="manager_tour">
	
				<form id="form1" name="form1" method="post" action="">
				<input type="hidden" value="<?echo $pag;?>" name="p">
							
				                <select class="form-user actions border-radius-4" name="epp" id="epp" onchange="cambiarpag(1)">
				                        <option value="">Elements per page</option>
				                        <option value="25" <?if ($_POST["epp"] == '1')echo 'selected';?>>1</option>
				                        <option value="50" <?if ($_POST["epp"] == '50')echo 'selected';?>>50</option>
				                        <option value="100" <?if ($_POST["epp"] == '100')echo 'selected';?>>100</option>
				                        <option value="all" <?if ($_POST["epp"] == 'all')echo 'selected';?>>Show All</option>
				                </select>                
				</form>    


			</header>
			<ul>
<?
		$ssqlp = "SELECT *, DATE_FORMAT(date,'%b %d %Y %h:%i %p') as fecha FROM static_pages ORDER BY date desc";
		$result = mysql_query($ssqlp);	
		
		$cantidad = mysql_num_rows($result);
		if($cantidad >0){
			while($registro < ($pag-1)*$elementosxpag){
				$row = mysql_fetch_array($result);
				$registro++;
			} 		
				while($row = mysql_fetch_array($result)){
				  	
?>		
			

					<li class="pano-item pano-item-manager">

						<div class="loader-item">
	                    	<h3><?php echo $row["title"];?></h3>
		          			<p><?php echo $row["fecha"];?></p>
		          			<ul class="actions">
		          				<li><a href="edit_static_page.php?id=<?php echo $row["id"];?>">Edit</a></li>
		          				<li><a href="#" data-id="<?php echo $row["id"];?>" class="remove-static-page">Delete</a></li>
		          				<li><a href="static-page.php?id=<?php echo $row["id"];?>" >View</a></li>
		          			</ul>
		                </div>
	                    <br clear="all">
	   	                <a href="#" data-id="<?php echo $row["id"];?>" title="Delete page" class="delete-item remove-static-page"></a>
	                </li>
	                
<?}}?>	                
	                
	                
</ul>

<div class="paginator">
                
	<?if($pag > 1){?>
	
	
	<a href="#" target="_self" data-page="<?echo $pag-1;?>" class="Link2">&lt; Prev</a> <span class="separadorLink2">|</span>

	<?}?>
	
	<?while( $indpag < $cantidad / $elementosxpag && $cantpagmuestra >0){
			$indpag++;
			$cantpagmuestra--;
				if($indpag == $pag){
					$paginador .= '<font class="active">'.$indpag.'</font>';
				}else{
					$paginador .= '<a href="#"  data-page="'.$indpag.'"><font>'.$indpag.'</font></a>';
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
	</body>
</html>
