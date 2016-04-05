		<script src="js/fb.js"></script>
		<script src="js/gp.js"></script>
		        
        <!--popup login-->
        <div class="overlay forgotpassword">
            <div class="pop">
                <a href="#" class="closed closed_register_mobile"></a>
                <h2>Forgot Password</h2>
                <div class="content_pop">
                    <p class="message">Don't worry, we'll send an email with instructions to access your account.</p>
                    <form>
    				<span id="reset_pop"></span>  
                    <input type="text" placeholder="E-mail" class="mail" id="reset_email">
                    <a href="#" class="login reset_pwd">Send</a>
                    </form>
                </div>
            </div>
        </div> 


        <div class="overlay overlay_login">
            <div class="pop">
                <a href="#" class="closed closed_register_mobile"></a>
                <h2>Log in</h2>
                <div class="content_pop">
                    <p class="message">I don't have an account yet and <a href="#" class="loginToRegister" >I want to register</a></p>
                    <a href="#" class="fb-bt"></a>
                   	<a href="#" class="gplus-bt"></a>
    				<span id="login_pop"></span>  
                        <input type="text" placeholder="E-Mail" class="mail" id="email">
                        <input type="password" placeholder="Password" class="pass" id="pass">
                        <a href="#" class="login loginaction">Log in</a>
                        <label for="remember" class="terminos">
                            <input type="checkbox" id="remember">
                                <p>Remember me - <a href="#" class="forgot_pwd">Forgot password?</a></p>
                            <br clear="all">
                        </label>
                </div>
            </div>
        </div>         
        

        <!--popup register-->
		<div class="overlay overlay_register">
        	<div class="pop">
                <a href="#" class="closed closed_register_mobile"></a>
                <h2>Register</h2>
				<div class="content_pop">
                
			<span id="data_pop">    
			<p class="message">Register using your preferred social network:</p>
                    
                    <a href="#" class="fb-bt"></a>
                    <a href="#" class="gplus-bt"></a>
                        <p class="message">Or register with your email address:</p>
    					<span id="register_pop"></span>
                        <input type="text" placeholder="Full Name" class="name" id="r_name">
                        <input type="text" placeholder="E-mail" class="mail" id="r_email">
                        <input type="text" placeholder="Repeat E-mail" class="mail" id="r_email1">
                        <input type="password" placeholder="Password" class="pass" id="r_pass">
                        <input type="password" placeholder="Repeat Password" class="pass" id="r_pass1">
                        
                        <a href="#" class="register registeraction">Register</a>
                        
                        <label for="terminos" class="terminos">
    	                    <input type="checkbox" id="terminos">
    		                <p>
                                I have read and agree to Spinattic's <a href="terms.php">Terms of Service</a>.
                            </p>
    				        <br clear="all">
                        </label>
                    </span>
                </div>
			</div>
		</div>



	    <div class="login-register" <?if ($logged == 1){echo 'style="display:none"';}?>>
	        <div class="or"></div>
    	    <a href="#" class="login">Log in</a>
	        <a href="#" class="register">Register</a>
            <br clear="all">
        </div>
            


	        <div class="user_login" <?if ($logged == 1){echo 'style="display:block"';}?>>
	        <a href="profile.php?uid=<?echo $_SESSION["usr"];?>" class="user_image"><img id="nav_avatar" width="47" height="47" src="images/users/<?echo $_SESSION["avatar"];?>"></a>
            	<div class="user_name">
                	<a href="profile.php?uid=<?echo $_SESSION["usr"];?>"><p><?echo $_SESSION["username"];?></p></a>
					<div class="action_user">
                		<a href="user_profile.php">Edit profile </a>/
                    	<a href="lo.php"><strong> Logout</strong></a>
	                	<br clear="all">
					</div>
                </div>
                <br clear="all">
            </div>
            
	        <div class="content_nav">

            <ul class="menu">

<?if ($logged == 1){?>
                <li>
                         <span class="btn-social">Social</span>
                  <ul class="submenu">
                    <li>
                          <a href="home.php">Home 
                          <!-- <span class="contador">+99</span>-->
                          </a>
                          
                          <a href="notifications.php">Notifications 
							<?
							session_start();
							$cuenta = 0;
							$ssqlp_nav = "SELECT count(*) as cuenta FROM notifications where leido = 0 and target_id = ".$_SESSION["usr"];
							$result_nav = mysql_query($ssqlp_nav);	
							$row_nav = mysql_fetch_array($result_nav);
							if($row_nav["cuenta"] > 0){
								if($row_nav["cuenta"] > 99){
									$cuenta = '+99';
								}else{
									$cuenta = $row_nav["cuenta"];
								}
							?>
                          
							<i class="campana"><span class="burbuja"><?echo $cuenta;?></span></i>
							
							<?}?>
                          
                          </a>
                          
                          <a href="followers.php">followers 

							<?
							session_start();
							$ssqlp_nav = "SELECT count(*) as cuenta FROM follows where id_following = ".$_SESSION["usr"];
							$result_nav = mysql_query($ssqlp_nav);	
							$row_nav = mysql_fetch_array($result_nav);
							$cuenta = $row_nav["cuenta"];
							if($cuenta > 99){
								$cuenta = '+99';
							}	
				
							?>
                          
                          <span class="contador" id="nav_followers"><?echo $cuenta;?></span>

                          </a>
                          <a href="following.php">following 
							<?
							session_start();
							$ssqlp_nav = "SELECT count(*) as cuenta FROM follows where id_follower = ".$_SESSION["usr"];
							$result_nav = mysql_query($ssqlp_nav);	
							$row_nav = mysql_fetch_array($result_nav);
							$cuenta = $row_nav["cuenta"];
							if($cuenta > 99){
								$cuenta = '+99';
							}	
					
							?>                          
                          		<span class="contador" id="nav_following"><?echo $cuenta;?></span>
                          
                          </a>
                    </li>
                  </ul>
                </li>
                <li>
                         <span class="btn-production">production</span>
                  <ul class="submenu">
                    <li>
                          <a href="edit_virtual_tour.php">create new tour</a>
                          
                          <a href="manager_tour.php">Manage tours</a>
                          
                          <a href="manager_panoramas.php">Manage panos files</a>
                    </li>
                  </ul>
                </li>
<?}?>

                <li>
                    <a href="index.php" class="home-btn">Explore</a>
                    <a href="map.php" class="world-map">World Map</a>
                    <!--a href="#" class="leader-board">Leader board</a-->
                        <li  class="acor">
                            <a href="#" class="browse" >Browse <span class="acor"></span></a>
                            <ul class="submenu menucategories">
                                <li>
                                    <?      $ssqlpcat = "SELECT * FROM categories ORDER BY category";
                                            $resultcat = mysql_query($ssqlpcat);    
                                            while($rowcat = mysql_fetch_array($resultcat)){?>
                                    <a href="search.php?c=1&search=<?echo $rowcat["category"];?>"><?echo $rowcat["category"];?></a>
                                    <?}?>
                                </li>
                            </ul>
                        </li>

                </li>
            </ul>


            

            <div class="privacy">
                <div class="social_media">
                <a href="#" class="facebook"></a>
                <a href="#" class="twitter"></a>
                <a href="#" class="googleplus"></a>
                <br clear="all">
                </div>
                <p>
                    <a href="terms.php">Terms of service</a> <br> <a href="privacy.php">Privacy Policy</a>
                    <br>
                    © All panoramas are property 
                    <br>
                    of their authors
                </p>
            </div>

            </div>