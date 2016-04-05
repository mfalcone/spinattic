<?
$page_title = "oops! | Private virtual Tour";
require("inc/header.php");
?>
<div class="error wrapper ptour">
	<h3>oops!</h3>
	<h1>Private Virtual Tour</h1>
	<p>The virtual tour you’re trying to load has the privacy configuration set to “private”.<br>
Only the user who created this virtual tour can see this page.</p>	
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