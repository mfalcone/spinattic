            <?php $search       = isset($_REQUEST["search"])? $_REQUEST["search"] : ''; // inicializar correctamente ?>

	        <a href="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>" class="spinattic"><img src="<?echo $http;?><?php echo $_SERVER[HTTP_HOST];?>/images/spinattic.png"></a>
			<!-- div class="language">
	            <a href="#" class="language_btn">
	            	<div class="icon"></div>
	                <font>ENG</font>
	            </a>
                <ul class="select_language">
                	<li><a href="#">English</a></li>
                    <li><a href="#">Español</a></li>
                    <li><a href="#">Portugués</a></li>
                </ul>
            </div-->
            <div class="search">
            <?if ($search != ''){
            	$valors = $search;
            }else{
            	$valors = 'Search';
            }?>
            	<div class="bg_search">
            	<form action="search.php" name="formsearch" method="GET">
                	<input type="text" value="<?echo $valors;?>" id="search-input" onclick="if(this.value == 'Search'){this.value=''};" onfocus="this.select()" onblur="this.value=!this.value?'Search':this.value;" name="search">
                	<!--a href="#" class="clear_input"></a--><!-- CRUZ PARA LIMPIAR BUSQUEDA-->
                	<a href="#" class="glass_input" onclick="if (document.formsearch.search.value != '' && document.formsearch.search.value != 'Search'){document.formsearch.submit();};"></a><!-- LUPA DE BUSQUEDA-->
			<input type="hidden" value="" name="order">
			<input type="hidden" value="" name="c">
                </form>
                	<br clear="all">
                </div>                

            </div>
            <br clear="all">