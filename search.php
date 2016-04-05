<?

$page_title = "Spinattic - Search Results";

	

$lastID = $_GET["lastID"];
$action = $_GET["action"];

if($_POST){
	$order = $_POST["order"];
	$c = $_POST["c"];
}else{
	$order = $_GET["order"];
	$c = $_GET["c"];
}

if($_GET["search"] != ''){
	$search = $_GET["search"];
}else{
	if($_POST["search"] != ''){
		$search = $_POST["search"];
	}	
}


if($order == ''){
	$order = 'id';
};

$pieces = explode(" ", $search);

$conditions = " (";


foreach ($pieces as $clave => $valor){
	$conditions .= "category like '%".$valor."%' or location like '%".$valor."%' or title like '%".$valor."%' or tags like '%".$valor."%' or description like '%".$valor."%' or ";
}

$conditions = substr($conditions, 0, -4);

$conditions .= ")";



if ($action != 'getLastPosts') {
 
	require("inc/header.php");


?>
	<script src="js/ajaxscroll.js"></script>
    <div>
        	<h1 class="result">
        	<?if ($c==1){?>
        		Browsing: 
        	<?}else{?>
        		Search Results for: 
        	<?}
        	$first_show = 21;
			require 'php-stubs/get_first_tours.php';
			$cantreg = mysql_num_rows($result);
			?>        	
        	<font>"<?echo htmlspecialchars($search);?>"</font></h1>

            <div class="result_details">
            	<div class="result_number"><?echo $cantreg;?> Tours found</div>
                <div class="sort_results">
                	<font>Sort results by:</font>
                    <a href="#" data-order="date">New</a>
                    <a href="#" data-order="likes">Top rated</a>
                    <a href="#" data-order="views">Popular</a>     
                    <br clear="all">
                </div>
                <br clear="all">
            </div>

		</div>
        <div class="wrapper">
			<div class="wrappper-posts">
			<?php 
			echo $postText;
			?>
			</div>
        </div>
    <?php require_once("inc/footer.php");?>
<script type="text/javascript">

	function order(o){
		document.formsearch.search.value="<?echo htmlspecialchars($search);?>";
		document.formsearch.c.value="<?echo $c;?>";
		document.formsearch.order.value=o;
		document.formsearch.submit();
	}
	
	$(document).ready(function(){

		$(".sort_results").on('click','a',function(e){
			e.preventDefault();
			var ord = $(e.target).data("order");
			console.log($(e.target).data("order"));
			order(ord);
		})

	})

	</script>


</html>


<?php }else{
	require_once("inc/conex.inc");
	$last_show = 6;
	require 'php-stubs/get_last_tours.php';
}
?>
