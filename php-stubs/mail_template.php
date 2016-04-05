<?function get_mail_body($avatar, $notif_info, $text_title, $text_title_link, $text_paragraph1, $text_paragraph2, $add_footer){

$template = '	
<table cellspacing="10" cellpadding="20" border="0" width="100%">
  <tr>
	<td width="100%"style="background:#F2F2F2;width:100%; font-family:\'Open Sans\', sans-serif; font-size:14px;">
		<img src="http://'.$_SERVER['HTTP_HOST'].'/images/logo_mails.jpg" align="center" style="margin-left:auto;margin-right:auto;display:block; margin-bottom:20px;">
		<table cellspacing="10" cellpadding="20" border="0" width="100%" style="background:#FFFFFF;border:1px solid #D4D4D4;color:#666666" border="1">
			<tr>
				<td>';

	if($notif_info != ''){
		$template .= '<table align="left"  style="float:left; font-size:14px;width:100%" cellpadding="5" width="100">
						<tr>
							<td width="39" style="width:39px; vertical-align:top" valign="top">
								<img src="http://'.$_SERVER['HTTP_HOST'].'/images/users/'.$avatar.'" width="39" height="39" alt="user" title="user" align="left" style="float:left" />
							</td>
							<td valign="top" style="vertical-align:top;padding: 0 10px 20px 10px;">'.$notif_info.'</td>
						</tr>
					</table>';
	}
					

	$template .= '<table align="left" width="100%" style="float:left; font-size:14px; margin-top:20px;width:100%; margin-bottom:10px;clear:left;" cellpadding="5">';
					
	if($text_title != ''){
		if($text_title_link != ''){
			$text_title = '<a href="'.$text_title_link.'" style="color:#51ace5; text-decoration:none;">'.$text_title.'</a>';
		}
		$template .= '<tr>
						<td>
							<h2 style="font-size:18px; font-weight:bold; margin:0;padding:0">'.$text_title.'</h2>
						</td>
					  </tr>';
	};
						
	if($text_paragraph1 != ''){			
		$template .= '<tr><td>'.$text_paragraph1.'</td></tr>';
	}
	
	if($text_paragraph2 != ''){			
		$template .= '<tr><td>'.$text_paragraph2.'</td></tr>';
	}	
					
	$template .= '</table>';
					
	
	
	
	$template .= '</td>
			</tr>
		</table>';
	
	if($add_footer == 1){
		$template .= '<table cellspacing="10" cellpadding="5" border="0" width="100%" style="color:#666666;margin-top:10px;">
			<tr>
				<td style="font-size:12px;color:#acacac">
					You received this email because your email notifications are set to receive these types of updates from Spinattic.If you\'d like to control which types of email notifications you receive from Spinattic, go to <a href="http://'.$_SERVER['HTTP_HOST'].'/user_profile.php" style="color:#51ace5; text-decoration:none;">http://'.$_SERVER['HTTP_HOST'].'/user_profile.php</a> login and change the settings under e-mail notifications. 
				</td>
			</tr>
		</table>';
	}
				
	$template .= '</td>
  </tr>
</table>';

return $template;
} 