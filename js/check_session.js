var check_session;
function CheckForSession() {
	if(!($('.chk_session_overlay').is(':visible')) && !($('.overlay_login').is(':visible'))) {
		var str="chklogout=true";
		if($('.chk_session_overlay').length > 0){
			$('.chk_session_overlay').remove();
		}
		jQuery.ajax({
				type: "POST",
				url: "ajax_chk_session.php",
				data: str,
				cache: false,
				success: function(res){
					if(res == "1") {
					    var html = '<div class="overlay show-response chk_session_overlay">'
				              +'      <div class="pop">'
				              +'          <a href="#" class="closed"  onclick="hide_popup();"></a>'
				              +'          <h2>Ooops !</h2>'
				              +'          <div class="content_pop">'
				              +'              <form class="pop-up">'
				              +'                  <label>           '    
				              +'                      <p>Your session expired</p>'
				              +'                  </label>'
				              +'                    <div class="content-btn-pop">'
				              +'                        <a href="" class="red-button border-radius-4 go_login">Login</a>'
				              +'                    </div>'
				              +'             </form>'
				              +'          </div>'
				              +'      </div>'
				              +'  </div>';
				      
				    var el = jQuery(html).appendTo('body');
				    var $pop = jQuery(el).children('.pop');
				    jQuery(el).fadeIn(200);
					 $(".go_login").click(function(event){
						 event.preventDefault();
						 jQuery(el).fadeOut(200);
						 $('.login-register .login').trigger("click");
					 });
					 
					}
				}
		});
		}
}
//check_session = setInterval(CheckForSession, 2000);