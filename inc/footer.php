<footer class="main-footer">
	<ul>
		<!--<li><a href="<?echo $http.$_SERVER[HTTP_HOST]."/";?>pricing">Pricing</a>&bull;</li>-->
		<li><a href="<?echo $http.$_SERVER[HTTP_HOST]."/";?>terms">Terms of service</a>&bull;</li>
		<li><a href="<?echo $http.$_SERVER[HTTP_HOST]."/";?>privacy">Privacy Policy</a>&bull;</li>
		<li><a href="<?echo $http.$_SERVER[HTTP_HOST]."/";?>refound">Refound Policy</a>&bull;</li>
		<li><a href="/blog">Blog</a>&bull;</li>
		<li><a href="<?echo $http.$_SERVER[HTTP_HOST]."/";?>faq">FAQs</a></li>
		<li><a href="<?echo $http.$_SERVER[HTTP_HOST]."/";?>about">About Us</a></li>
	</ul>
	<p>Powered by Spinattic LLC.</p>
	<a href="<?echo $http;?>www.spinattic.com"><img src="<?echo $http.$_SERVER[HTTP_HOST]."/";?>images/footer-logo.png" alt="footer logo"></a>
</footer>
<script type="text/javascript" src="//assets.zendesk.com/external/zenbox/v2.6/zenbox.js"></script>
<style type="text/css" media="screen, projection">
 @import url(//assets.zendesk.com/external/zenbox/v2.6/zenbox.css);
</style>

<?if(strpos($_SERVER[REQUEST_URI],'account')){?>
<!-- Start of spinattic Zendesk Widget script -->
	<script>/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(c){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var o=this.createElement("script");n&&(this.domain=n),o.id="js-iframe-async",o.src=e,this.t=+new Date,this.zendeskHost=t,this.zEQueue=a,this.body.appendChild(o)},o.write('<body onload="document._l();">'),o.close()}("https://assets.zendesk.com/embeddable_framework/main.js","spinattic.zendesk.com");
/*]]>*/</script>
<!-- End of spinattic Zendesk Widget script -->

<?}else{?>
	<div id="zendbox-wrapper">
	<script type="text/javascript">
	  if (typeof(Zenbox) !== "undefined") {
		Zenbox.init({
		  dropboxID:   "20171379",
		  url:         "https://spinattic.zendesk.com",
		  tabTooltip:  "Feedback",
		  tabImageURL: "https://www.spinattic.com/images/feedback.jpg",
		  tabColor:    "white",
		  tabPosition: "Right"
		});
	  }
	</script>
	</div>
<?}?>
	

</body>
