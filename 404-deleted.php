<?
$page_title = "oops! | 404";
require("inc/header.php");
?>
<div class="error wrapper">
	<h3>oops!</h3>
	<h1>Tour deleted</h1>
	<p>This tour has been deleted from Spinattic</p>	
</div>

                <div class="filter"  id="filter">
                	<ul>
                    	<li><a href="map.php?id=<?echo $id;?>&o=date" <?if ($order=="date"){echo 'class="active"';}?>>New</a></li>
                    	<li><a href="map.php?id=<?echo $id;?>&o=likes" <?if ($order=="likes"){echo 'class="active"';}?>>Top Rated</a></li>
                    	<li><a href="map.php?id=<?echo $id;?>&o=views" <?if ($order=="views"){echo 'class="active"';}?>>Popular</a></li>
                    </ul>
                </div>
		</div>
        <div class="wrapper">
			
			<?php 
			$first_show = 21;
			require 'php-stubs/get_first_tours.php';
			echo $postText;?>
			        
	        	
        </div>