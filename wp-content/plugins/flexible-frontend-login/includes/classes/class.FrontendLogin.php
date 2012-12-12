<?php
/**
* Class for Frontend Login instances
* since Version 0.95
* added 2012-09-24 10:01:00
**/




class FrontendLogin
{
	public $popup_link_text = 'Login';
	public $vertical_position = 'bottom';
	public $horizontal_position = 'left';
	public $modal = true;
	public $html_block = '
										<table>
										   <tbody>
											  <tr>
												 <td>%label_for_username%</td>
												 <td>%input_username%</td>
											  </tr>
											  <tr>
												 <td>%label_for_password%</td>
												 <td>%input_password%</td>
											  </tr>
											  <tr>
												 <td colspan="2">%send_button%</td>
											  </tr>
											 <tr>
												 <td colspan="2">%lost_password%</td>
											  </tr>
										   </tbody>
										</table>';
	public $show_username = true;
	public $show_logout_link = true;
	public $logout_link_text = 'Log Out';
	public $show_admin_link = false;
	public $admin_link_text =  'Admin';
	
	public function ffl_wrap_form_before()
	{
		$unique = rand(100,999);
		$html = "<div class='flexible-frontend-login'>";
		$html .= "<a name='ffl-popup' href='#ffl-popup-content-$unique' class='ffl-popup-link'>";
		$html .= $this->popup_link_text;
		$html .= "</a>";
		$html .= "<div id='ffl-popup-content-$unique' class='ffl-popup-content ffl-{$this->horizontal_position} ffl-{$this->vertical_position}'>";
		$html .= "<a class='ffl-close-popup-link'>";
		$html .= __( 'Close', 'flexible-frontend-login'  );
		$html .= "</a>";
		
		return $html;
	}
	
	public function ffl_wrap_form_after()
	{
		$html = "</div><!-- / .flexible-frontend-login-popup -->";
		$html .= "</div><!-- / .flexible-frontend-login -->";
		
		return $html;
	}
	
	public function ffl_wrap_form_modal_before()
	{
		$html = "<div class='flexible-frontend-login'>";
		$html .= "<a href='#ffl-dialog' name='ffl-modal'>";
		$html .= $this->popup_link_text;
		$html .= "</a>";
		$html .= "<div id='ffl-boxes'>";
		$html .= "<div id='ffl-dialog' class='ffl-window'>";
		$html .= "<a class='ffl-close'>";
		$html .= __( 'Close', 'flexible-frontend-login' );
		$html .= "</a>";
		
		return $html;
	}
	
	public function ffl_wrap_form_modal_after()
	{
		$html = "</div><!-- / #ffl-dialog -->";
		$html .= "</div><!-- / #ffl-boxes -->";
		$html .= "</div><!-- / .flexible-frontend-login -->";
		
		return $html;
	}
	
	public function ffl_form_content()
	{
		$html = "<form action='";
		$html .= get_bloginfo( 'url' );
		$html .= "/wp-login.php' method='post' autocomplete>";
		$html .= "<input type='hidden' name='rememberme' id='rememberme' checked value='forever' />";
		$html .= "<input type='hidden' name='redirect_to' value='";
		$html .= $_SERVER['REQUEST_URI'];
		$html .= "'>";
		$html .= $this->html_block;	
		$html .= "</form>";	
	
		/* replace spaceholders */
		$label_for_username = "<label for='ffl-input-username' id='ffl-label-username'>";
		$label_for_username .= __( 'Username', 'flexible-frontend-login' );
		$label_for_username .= "</label>";

		$html = str_replace( '%label_for_username%', $label_for_username, $html );
		
		$input_username  = "<input type='text' name='log' id='ffl-input-username' size='20' placeholder='";
		$input_username .= __( 'Username', 'flexible-frontend-login' );
		$input_username .= "' pattern='^[@_öäüöÄÜß\w\s\.]{3,30}$'>";
		
		$html = str_replace( '%input_username%', $input_username, $html );
		
		$label_for_password  = "<label for='ffl-input-password'  id='ffl-label-password'>";
		$label_for_password .= __( 'Password', 'flexible-frontend-login' );
		$label_for_password .= "</label>";
		
		$html = str_replace( '%label_for_password%', $label_for_password, $html );	
		
		$input_password  = "<input type='password' name='pwd' size='20' id='ffl-input-password' placeholder='";
		$input_password .= __( 'Password', 'flexible-frontend-login' );
		$input_password .= "' autocomplete=false>";
		
		$html = str_replace( '%input_password%', $input_password, $html );	
		
		$lost_password  = "<a href='";
		$lost_password .= get_bloginfo( 'url' );
		$lost_password .= "/wp-login.php?action=lostpassword' class='lostpassword' id='ffl-lostpassword'>";
		$lost_password .= __( 'Lost Password', 'flexible-frontend-login' );
		$lost_password .= "</a>";	

		$html = str_replace( '%lost_password%', $lost_password, $html );	
		
		$send_button = "<button type='submit' name='submit' id='ffl-submit'>";
		$send_button .= __( 'Login', 'flexible-frontend-login' );
		$send_button .= "</button>";

		$html = str_replace( '%send_button%', $send_button, $html );	

		$html = str_replace( '&lt;', '<', $html );	
		$html = str_replace( '&gt;', '>', $html );	
		$html = str_replace( '\"', '"', $html );			
		return $html;
	}

	
	/* Display something for logged in users */
	public function ffl_show_user_info() 
	{
		$html = '';
		if ( $this->show_admin_link )
		{
			$html .= "<a id='ffl-admin-link' href='";
			$html .= get_bloginfo( 'url' );
			$html .= "/wp-admin'>";
			$html .= $this->admin_link_text;
			$html .= "</a>";
		}
		if ( $this->show_username )
		{
			global $current_user; 
			get_currentuserinfo(); 
			$user_is = $current_user->first_name . " " . $current_user->last_name;
			$html .= "<span id='ffl-logged-in-user'>$user_is</span>";
		}
		if ( $this->show_logout_link )
		{
			$redirect = $_SERVER[ 'REQUEST_URI' ];
			$logout_url = wp_logout_url( $redirect );
			$html .= "<a id='ffl-logout-link' href='$logout_url'>";
			$html .= $this->logout_link_text;
			$html .= "</a>";
		}
		return $html;
	}

}

?>
