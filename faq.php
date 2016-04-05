<?
$page_title = "Spinattic - FAQ";
require("inc/header.php");
?>

		
	
<?
		$ssqlp = "SELECT * FROM tours where state = 'publish' and privacy = '_public' ORDER BY RAND() LIMIT 1";
		$result = mysql_query($ssqlp);	
		$row = mysql_fetch_array($result);

		$ssqlp_usr = "SELECT * FROM users where id = '".$row["iduser"]."'";
		$result_usr = mysql_query($ssqlp_usr);
		$row_usr = mysql_fetch_array($result_usr);		
?>
        
        <div class="wrapper wrapper-faq wrapper-home">
        	<h1 class="faq">FAQ</h1>
        	<h2>What is Spinattic</h2>
        	<p>Spinattic enables everyone to create interactive virtual tours, saving time and money.. Our free community enables members to share their tours and engage with other tour creators.</p>
	        	<dl>
	        		<dt>Is Spinattic free? </dt>
	        		<dd>Yes, uploading panoramas, creating, publishing, hosting, and sharing your virtual tours is free for our members. Spinattic has some exciting features coming soon that may have fees associated with them. </dd>
	        		<dt>Can I use Spinattic to make and host virtual tours for my clients?</dt>
	        		<dd>Yes of course. That’s what we are all about.</dd>
	        		<dt>I'm a photographer. What are the benefits of joining Spinattic? </dt>
	        		<dd>
	        			<ul>
	        				<li>Free membership.</li>
	        				<li>Easy, quick way create and integrate your virtual tours on your own website. </li>
	        				<li>Great exposure for your photography and virtual tours.</li>
	        				<li>Great professional networking.</li>
	        				<li>Versatile, all inclusive options to add to your virtual tours. (Coming Soon!)</li>
	        				<li>Flexible solution to customize the interface of your tour. (Coming Soon!) </li>
	        			</ul>
	        		</dd>
	        	</dl>
	        <h2>Creating an account</h2>
	        
	        <dl>
	        		<dt>How do I create an account with Spinattic?</dt>
	        		<dd>Go to www.spinattic.com and click on register. You can  register using your preferred social network, or add your name, email and password and you will receive an activation notice from Spinattic in your email. Once you click on that link you will be ready to build your tours. </dd>
	        	</dl>
            
            <h2>How to use Spinattic</h2>
            <dl>
            	<dt>Does Spinattic have a mobile app?</dt>
            	<dd>We will soon! Hopefully September 2015. Go here to sign up to be alerted. The app will make it easier to get panoramas to Spinattic, to browse, follow and view other Spinattic member's imagery.</dd>
            </dl>
            <h2>Uploading Images</h2>
            <dl>
            	<dt>Which types of panos does Spinattic support? </dt>
            	<dd>Currently, Spinattic allows members to upload 2:1 panos. This means that the pano must be twice as long as it is high in pixel dimensions.</dd>
            	<dt>Does Spinattic allow tiff files to be uploaded?</dt>
            	<dd>Yes, you can upload tiffs or jpegs… keep in mind that the bigger the image the longer the time it will take to upload and process. We recommend you use 10mb or less.</dd>
            	<dt>Why won’t my pano upload?</dt>
            	<dd>Uploading images takes time and varies based on the size of the image and the type of internet connection you have.  If the upload is taking more time than it should try uploading one panorama at a time, check the size of your image.  Additionally check your internet connection. </dd>
            </dl>
            <p>When viewing media on the web, the trick is to use the smallest file possible without losing quality. We feel the optimum size pano should be 6000px wide by 3000px high and 72dpi. This is easily done in photo editing programs such as Adobe's Photoshop. 
</p>
			<p>This size file will quickly upload and look great in your virtual tour. </p>
			<h2>Creating your Tour</h2>
			<dl>
				<dt>Steps for creating your virtual tour on Spinattic:</dt>
				<dd>
					<ul>
						<li>Upload your image(s)… for a greater experience here … try uploading one or two at a time.</li>
						<li>Edit your hotspots… select a starting point, add hotspots/navigation between your images, add scene descriptions and photos.</li>
						<li>Place your images in order… this can be done by simply dragging and dropping.</li>
						<li>Title your Tour.</li>
						<li>Create a description to define your tour.</li>
						<li>Select your privacy settings.</li>
						<li>Choose a category where your tour would most likely fit… i.e. resort, destination… etc.</li>
						<li>Identify the location of the tour for the map. Just click on the exact location on the map that represents your tour as a whole.</li>
						<li>Create tags for your tour that will best describe it. These tags make it easier for other members to find your tour. </li>
						<li>Your tour draft and settings will save automatically. When you are done creating your tour. Click "Publish" to publish your tour to Spinattic's sharing platform. </li>
						<li>After publishing, you'll be able to share your tour on various social networks, retrieve an embed code to include in your own web page.</li>		
					</ul>
				</dd>
				<dt>How many panos can I add to my tour?</dt>
				<dd>As many as you would like. The more the better.</dd>
				<dt>What is a Hotspot</dt>
				<dd>Hotspots make your tour more interactive.  You can add a starting point for each image, put in navigation points, add scene descriptions and still photos to your virtual tour. Other types of hotspots and the customization of those buttons is in development and will be Coming Soon!</dd>
				<dt>How do I add navigation to my virtual tour?</dt>
				<dd>When you click on the “arrow” icon in your edit menu for your panorama, you will then place/drag the icon where you would like it to appear in the scene.  You will then be asked to select the panorama that you would like the navigation to link with.  You can have multiple navigation arrows from one pano image. Don't forget to save click "Update" to save the changes to the published tour. </dd>
			<dt>How do I add a still photo to my virtual tour?</dt>
			<dd>When you click on the photo icon in your edit menu for your panorama, you will then place/drag the icon where you would like it to appear in the scene and then click on it to browse to the file where your image is stored. Remember, to reduce your still photo images to 72dpi so that the upload takes less time and your tour functions better. Don't forget to save click "Update" to save the changes to the published tour.</dd>
			<dt>How do I add a scene description to my virtual tour?</dt>
			<dd>When you click on the info icon in your edit menu for your panorama, you will then place/drag the icon where you would like it to appear in the scene.  There will be a box to add text for your description. Don't forget to save click "Update" to save the changes to the published tour.</dd>
			<dt>How do I change the scene sequence including the opening scene for my virtual tour?</dt>
			<dd>To change the scene or pano sequence for your tour, simply click and drag the scene or pano within the page into the sequence you desire. Don't forget to update your changes. If you forget to update your changes don't worry. There will be a draft version with your changes waiting for you when you get back.</dd>
			</dl>
			<h2>Using your Virtual Tour</h2>
			<dl>
				<dt>Are my tours that I create with Spinattic going to be “mobile ready”</dt>
				<dd>Yes they will. Try it out. Use the embed code URL on a computer and on a mobile device. </dd>
				<dt>How do I integrate my virtual tour into my website or blog?</dt>
				<dd>In the tour page after it has been published, click on the Embed icon <> to get a line of code.  Use this code to embed into your webpage.</dd>
				<dt>Can I use Spinattic for my business? </dt>
				<dd>Yes. You can embed your tours in your web-site or blog.  Spinattic is a terrific way to promote and grow your business.</dd>
			</dl>
			<h2>Using our Social Media</h2>
			<dl>
				<dt>What if I don’t want to share my tour on Spinattic’s sharing platform?</dt>
				<dd>Don’t worry. You can make tours and turn off the sharing feature so that you can keep the tour for yourself and for your clients.</dd>
			</dl>
			<h2>Spinattic Phase II customizer launching soon.</h2>
			<p>Stay tuned for Spinattic's new development. It's going to be exciting! Spinattic is going to be offering virtual tour interface customization solutions. Imagine using an all cloud based, easy to use platform, that allows you to upload your panos, create your virtual tour, create a great interface template and customize the look and feel right in your Spinattic account. Sign up for our news letter to learn more about the new customization platform and maybe you'll get a chance to test it before it's publicly launched. :)</p>
             <p>If your question is not listed here, please don't hesitate to <a href="contact.php">contact us</a>.</p>   
        </div>
	    <?php require_once("inc/footer.php");?>
	<script type="text/javascript">
			$(document).ready(function(){

				$('dt').on('click', function (e) {
        			e.preventDefault();
        			$("dt").next('dd:visible').slideUp('slow')
        			$("dt.active").removeClass('active');
			        
			        $mydt = $(this);
			        if($mydt.next().is(":hidden")){
			        
			        	$mydt.addClass("active")
			        }
			        $mydt.nextUntil('dt').filter(':not(:visible)').slideDown('slow');
					
			    });

			})
		</script>
</html>

