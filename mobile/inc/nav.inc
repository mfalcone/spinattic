		<div id="loading" class="loading" style="display: none;">
	        <div class="loading_img"></div>
        </div>
        	
 		<header class="header">
 			<? if($home){?>
 			<div class="text-message">
				<p>Thanks for finding Spinattic on your mobile device! Spinattic is better experienced using your desktop or laptop which will allow you to create an account, create tours and interact with other members. In the meantime, have fun browsing our member's published virtual tours.</p>
				<a href="#" class="bt_close">close</a>
			</div>
			<? } ?>
			<div class="inner-header">
	        <a href="#" class="spinattic"><img src="../images/spinattic.png"></a>
            <a href="#" class="spinattic spinattic_mobile"><img src="../images/spinattic_mobile.png"></a>
	        <a href="#" class="nav_btn_mobile"></a>
	        <!--<a href="#" class="login_btn_mobile"></a>-->
            </div>
        </header>
        <div class="nav nav_panel">
			<div class="search_mobile">
				<?if ($search != ''){
					$valors = $search;
				}else{
					$valors = 'Search';
				}?>
				<div class="search">
					<div class="bg_search">
						<form action="search.php" name="formsearch" method="POST">
							<input type="text" value="<?echo $valors;?>" id="search-input" onclick="if(this.value == 'Search'){this.value=''};" onfocus="this.select()" onblur="this.value=!this.value?'Search':this.value;" name="search">
							<!--a href="#" class="clear_input"></a--><!-- CRUZ PARA LIMPIAR BUSQUEDA-->
							<a href="#" class="glass_input" onclick="if (document.formsearch.search.value != '' && document.formsearch.search.value != 'Search'){document.formsearch.submit();};"></a><!-- LUPA DE BUSQUEDA-->
							<input type="hidden" value="" name="order">
							<input type="hidden" value="" name="c">
							<!--
		                	<div class="predictive">
		                		<ul>
		                	    	<li><a href="#"><strong>text</strong> 1</a></li>
		                	    	<li><a href="#"><strong>text</strong> 2</a></li>
		                	    	<li><a href="#"><strong>text</strong> 3</a></li>
		                	    	<li><a href="#"><strong>text</strong> 4</a></li>
		                	    	<li><a href="#"><strong>text</strong> 5</a></li>
		                	    	<li><a href="#"><strong>text</strong> 6</a></li>
		                	    </ul>
		                	</div>
							-->
						</form>
	                
                        <a class="nav_btn_mobile"></a>
					</div>                
				</div>                
            </div>
            
            <div class="content_nav">                        
 				<ul class="menu">
					<li>
					<a href="map.php" class="world-map">World map</a>
					</li>
					<li>
						<a href="#" class="accordion browse">Browse</a>
						<ul class="submenu">
							<li>
							<?		$ssqlpcat = "SELECT * FROM categories ORDER BY category";
									$resultcat = mysql_query($ssqlpcat);	
									
									while($rowcat = mysql_fetch_array($resultcat)){?>
							                            <a href="search.php?c=1&search=<?echo $rowcat["category"];?>"><?echo $rowcat["category"];?></a>
							                            
							<?}?>
							</li>
						</ul>
					</li>
					<li><a href="blog.php" class="blog">blog</a></li>
					</ul>
            <div class="privacy">
            	<div class="social_media">
                <a href="#" class="facebook"></a>
                <a href="#" class="twitter"></a>
                <a href="#" class="googleplus"></a>
                <br clear="all">
                </div>
            	<p>
                	<a href="#">Terms of Use</a> | <a href="#">Privacy Policy</a>
                    <br>
					© All panoramas are property 
                    <br>
					of their authors
                </p>
			</div>
		</div>
        </div>