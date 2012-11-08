<?php 
/**
 * @package Nice PayPal Button Lite
 */
/*
Plugin Name: Nice PayPal Button Lite
Plugin URI: http://trinitronic.com/index.php/Downloads/downloads.html
Description: The Nice PayPal Button Lite plugin provides you with an easy PayPal payment solution. Simply add a Nice PayPal Button Lite shortcode to your post or page and a PayPal Buy Now button will be published in place of the shortcode. To change the plugin's settings visit the <a href="./options-general.php?page=nicePayPalButtonLite.php" target="_self" >settings page.</a>
Version: 1.01
Author: TriniTronic
Author URI: http://trinitronic.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if (!class_exists("NicePayPalButtonLite")) {

  class NicePayPalButtonLite {
  
    var $adminOptionsName = "NicePayPalButtonLiteAdminOptions";
  
    function NicePayPalButtonLite() { //constructor
       
    }
    
    // Admin Panel page --------------------------------------
    
    // Returns an array of admin options
    function getAdminOptions() {
    
      $nicePayPalButtonLiteAdminOpts = array(            
          'currency_code'    => 'USD',
          'country_code'     => 'US',
          'paypal_testmode'  => 0,
          'button_path'      => 'en_US',
          'default_btnsize'  => '_LG',
          'open_window'      => '_self',
          'paypal_url'       => 'https://www.paypal.com/',
          'paypal_testurl'   => 'https://www.sandbox.paypal.com/',
          'paypal_email'     => '',
          'paypal_testemail' => ''
        );
          
      $niceOptions = get_option( $this->adminOptionsName );
      
      if ( !empty( $niceOptions ) ) {
      
          foreach ( $niceOptions as $k => $v )
          
              $nicePayPalButtonLiteAdminOpts[$k] = $v;
              
      }
      
      update_option($this->adminOptionsName, $nicePayPalButtonLiteAdminOpts);
      
      return $nicePayPalButtonLiteAdminOpts;

    }
    
    function init() {
    
      $this->getAdminOptions();
    
    }
    
    //Prints out the admin page
    function printAdminPage() {
    
      global $wpdb;
      
      $niceOptions = $this->getAdminOptions();
      
      
      if (isset($_POST['update_nicePayPalBUttonLiteSettings'])) {
      
        if (isset($_POST['currency_code'])) {

          $niceOptions['currency_code'] = $wpdb->escape($_POST['currency_code']);
          
        }

        if (isset($_POST['country_code'])) {

          $niceOptions['country_code'] = $wpdb->escape($_POST['country_code']);
          
        }

        if (isset($_POST['paypal_testmode'])) {

          $niceOptions['paypal_testmode'] = $wpdb->escape($_POST['paypal_testmode']);
          
        }

        if (isset($_POST['button_path'])) {

          $niceOptions['button_path'] = $wpdb->escape($_POST['button_path']);
          
        }

        if (isset($_POST['default_btnsize'])) {

          $niceOptions['default_btnsize'] = $wpdb->escape($_POST['default_btnsize']);
          
        }

        if (isset($_POST['open_window'])) {

          $niceOptions['open_window'] = $wpdb->escape($_POST['open_window']);
          
        }

        if (isset($_POST['paypal_url'])) {

          $niceOptions['paypal_url'] = $wpdb->escape($_POST['paypal_url']);
          
        }

        if (isset($_POST['paypal_testurl'])) {

          $niceOptions['paypal_testurl'] = $wpdb->escape($_POST['paypal_testurl']);
          
        }

        if (isset($_POST['paypal_email'])) {

          $niceOptions['paypal_email'] = $wpdb->escape($_POST['paypal_email']);
          
        }

        if (isset($_POST['paypal_testemail'])) {

          $niceOptions['paypal_testemail'] = $wpdb->escape($_POST['paypal_testemail']);
          
        }
          
        update_option($this->adminOptionsName, $niceOptions);
       
        ?>
        
        
            
        <div class="updated"><p><strong><?php _e("Settings Updated.", "nicePayPalButtonLite");?></strong></p></div>
        
      <?php
      
        foreach($niceOptions as $k => $v)
        {
        
          $niceOptions[$k] = esc_html($v);
          
        }
        
      } ?>
 
      <div class=wrap>
        <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
          <h2>Nice PayPal Button Lite</h2>
          <h3>PayPal Account Email / Merchant Account ID</h3>
          <p>Enter your valid PayPal account email address. Or, enter your valid Merchant Account ID, which you can find in your PayPal account profile under My Business Info. Payments will be made to the PayPal account associated with this specified email address or merchant id.</p>
          <input type="text" size="50" name="paypal_email" id="paypal_email" value="<?php _e(apply_filters('format_to_edit',$niceOptions['paypal_email']), 'nicePayPalButtonLite'); ?>" />
          <h3>PayPal Test Mode Email / Test Merchant Account ID</h3>		
          <p>nter your valid PayPal sandbox test seller email address. Or, enter your valid Merchant Account ID, which you can find in your PayPal sandbox account profile under My Business Info. Test payments will be made to the sandbox account associated with this specified email address. To use this feature you must have a PayPal developer account.</p>
          <input type="text" size="50" name="paypal_testemail" id="paypal_testemail" value="<?php _e(apply_filters('format_to_edit',$niceOptions['paypal_testemail']), 'nicePayPalButtonLite'); ?>" />	
          <h3>PayPal Test Mode</h3>
          <p>Select on or off to put all PayPal buttons in and out of test mode. When on is specified all transactions are sent to the PayPal sandbox. To use this feature you must have a PayPal developer account.</p>
          <p><label for="paypal_testmode0">
            <input type="radio" id="paypal_testmode0" name="paypal_testmode" value="0" <?php if ($niceOptions['paypal_testmode'] == 0) { _e('checked="checked"', "nicePayPalButtonLite"); }?> />off</label>&nbsp;&nbsp;&nbsp;&nbsp;
          <label for="jform_params_paypal_testmode1">
            <input type="radio" id="paypal_testmode1" name="paypal_testmode" value="1" <?php if ($niceOptions['paypal_testmode'] == 1) { _e('checked="checked"', "nicePayPalButtonLite"); }?> />on</label>
          </p>
          <h3>Currency Code</h3>		
          <p>Valid PayPal 3-letter currency codes: Australian Dollars: AUD, Canadian Dollars: CAD, Euros: EUR, Pounds Sterling: GBP, Yen: JPY, U.S. Dollars: USD, New Zealand Dollar: NZD, Swiss Franc: CHF, Hong Kong Dollar: HKD, Singapore Dollar: SGD, Swedish Krona: SEK, Danish Krone: DKK, Polish Zloty: PLN, Norwegian Krone: NOK, Hungarian Forint: HUF, Czech Koruna: CZK, Israeli Shekel: ILS, Mexican Peso: MXN.</p>
          <input type="text" size="3" maxlength="3" name="currency_code" id="currency_code" value="<?php _e(apply_filters('format_to_edit',$niceOptions['currency_code']), 'nicePayPalButtonLite'); ?>" />
          <h3>PayPal Country Code</h3>		
          <p>Enter your 2 digit country code to set the language used on the PayPal payment page. PayPal uses a two-character country code (ISO 3166). Some examples are United States: US, Great Britain: GB, France: FR, Spain: ES, Netherlands: DL, Poland: PL, German: DE. If you don't know your country code, or you can Google PayPal Country Codes.</p>
          <input type="text" size="2" maxlength="2" name="country_code" id="country_code" value="<?php _e(apply_filters('format_to_edit',$niceOptions['country_code']), 'nicePayPalButtonLite'); ?>" />
          <h3>Button Language Code</h3>		
          <p>PayPal uses a 5 character code to designate the language of its buttons. For example, United States English is designated with en_US. Enter the 5 character code for the desired button language. Other code examples are Great Britain English: en_GB, French: fr_FR, Spanish: es_ES, Dutch: nl_NL, Polish: pl_PL, German: de_DE. If you don't know the code for your desired language, log into PayPal use the button creater and search the resulting HTML code for this https://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif. Notice the en_US in the URL, it's the language code for the button.</p>
          <input type="text" size="10" maxlength="10" name="button_path" id="button_path" value="<?php _e(apply_filters('format_to_edit',$niceOptions['button_path']), 'nicePayPalButtonLite'); ?>" />
          <h3>Default Button Size</h3>
          <p>Select the default button size. You can choose between Small and large.</p>
          <p><label for="default_btnsize_SM">
            <input type="radio" id="default_btnsize_SM" name="default_btnsize" value="_SM" <?php if ($niceOptions['default_btnsize'] == "_SM") { _e('checked="checked"', "nicePayPalButtonLite"); }?> />Small</label>&nbsp;&nbsp;&nbsp;&nbsp;
          <label for="default_btnsize_LG">
            <input type="radio" id="default_btnsize_LG" name="default_btnsize" value="_LG" <?php if ($niceOptions['default_btnsize'] == "_LG") { _e('checked="checked"', "nicePayPalButtonLite"); }?> />Large</label>
          </p>
          <h3>New Window Target</h3>
          <p>Choose how you want the buyer to be sent to PayPal, either in their current browser window or a new browser window.</p>
          <p><label for="open_window_self">
            <input type="radio" id="open_window_self" name="open_window" value="_self" <?php if ($niceOptions['open_window'] == "_self") { _e('checked="checked"', "nicePayPalButtonLite"); }?> />Current window</label>&nbsp;&nbsp;&nbsp;&nbsp;
          <label for="open_window_blank">
            <input type="radio" id="open_window_blank" name="open_window" value="_blank" <?php if ($niceOptions['open_window'] == "_blank") { _e('checked="checked"', "nicePayPalButtonLite"); }?> />New window</label>
          </p>         
          <div class="submit">
          <input type="submit" name="update_nicePayPalBUttonLiteSettings" value="<?php _e('Update Settings', 'nicePayPalButtonLite') ?>" /></div>
        </form>
      </div>
       
    <?php
    }//End function printAdminPage()
      

    // -------------------------------------- End Admin Panel page
    
    
    // Plugin functionality --------------------------------------

    function getNicePayPalButtonLite ( $atts = '' ){

      extract( shortcode_atts(

      array(

        'amount'	  => '',
        'name'	    => '',
        'sku'	      => '',
        'shipping'	=> '',
        'shipping2'	=> '',
        'tax'	      => '',
        'quantity'	=> '',
        'weight'	  => ''
        
      ), $atts ));
      
      $opts = $this->getAdminOptions();
      
      $atts['currency_code']    = $opts['currency_code'];
      $atts['country_code']     = $opts['country_code'];
      $atts['paypal_testmode']  = $opts['paypal_testmode'];
      $atts['button_path']      = $opts['button_path'];
      $atts['default_btnsize']  = $opts['default_btnsize'];
      $atts['open_window']      = $opts['open_window'];
      $atts['paypal_email']     = $opts['paypal_email'];
      $atts['paypal_testemail'] = $opts['paypal_testemail'];
      $atts['paypal_url']       = 'https://www.paypal.com/';
      $atts['paypal_testurl']   = 'https://www.sandbox.paypal.com/';
      $atts['command']          = '_xclick';
      $atts['url']              = $atts['paypal_testmode'] ? $atts['paypal_testurl']   : $atts['paypal_url'];
      $atts['email']            = $atts['paypal_testmode'] ? $atts['paypal_testemail'] : $atts['paypal_email'];
      $atts['button_image']     = $atts['url'].$atts['button_path'].'/i/btn/btn_buynow'.$atts['default_btnsize'].'.gif';
      
      foreach($atts as $k => $v)
      {
      
        $atts[$k] = esc_html($v);
        
      }
      
      $insert = $this->nicePayPalButtonLiteLiteBuildForm( $atts );
      
      return $insert;
      
    }  

    function nicePayPalButtonLiteLiteBuildForm( $a )
    {
      
      $f = '';
      
      if ( $a['name'] != '' && $a['command'] != '' && $a['email'] != '' && $a['url'] != '' ){
      
        $f .= '<form class="nicepaypalbuttonlite" action="'.$a['url'].'/cgi-bin/webscr" method="post" target="'.$a['open_window'].'">';
        $f .= '<input type="hidden" name="business" value="'.$a['email'].'" />';
        $f .= '<input type="hidden" name="cmd" value="'.$a['command'].'" />';
        $f .= '<input type="hidden" name="item_name" value="'.$a['name'].'" />';
        $f .= '<input type="hidden" name="amount" value="'.$a['amount'].'" />';
        
        //item number
        if ( $a['sku'] != '' ){

          $f .= '<input type="hidden" name="item_number" value="'.$a['sku'].'" />';

        }

        //shipping
        if ( $a['shipping'] != '' ){

          $f .= '<input type="hidden" name="shipping" value="'.$a['shipping'].'" />';

        }

        //shipping2
        if ( $a['shipping2'] != '' ){

          $f .= '<input type="hidden" name="shipping2" value="'.$a['shipping2'].'" />';

        }

        //tax
        if ( $a['tax'] != '' ){
                      
        $f .= '<input type="hidden" name="tax" value="'.$a['tax'].'" />';

        }

        //quantity
        if ( $a['quantity'] != '' ){

        $f .= '<input type="hidden" name="quantity" value="'.$a['quantity'].'" />';

        }

        //weight
        if ( $a['weight'] != '' ){

         $a['weight'] = strtolower( $a['weight'] );
         $a['weight'] = str_replace( ' ', '', $a['weight'] );
         
         if ( preg_match_all('/lbs/', $a['weight'], $op) || preg_match_all('/kgs/', $a['weight'], $op) ) {
         
           $wu  = substr( $a['weight'], -3);
           $w   = substr( $a['weight'], 0, -3);
           
         }else {
         
           $wu  = 'lbs';
           $w   = $a['weight'];
         
         }
         
          $f .= '<input type="hidden" name="weight" value="'.$w.'" />';
          $f .=	'<input type="hidden" name="weight_unit" value="'.$wu.'" />';
         
        }
        
        $f .= '<input type="hidden" name="currency_code" value="'.$a['currency_code'].'" />';
        $f .= '<input type="hidden" name="lc" value="'.$a['country_code'].'" />';
        $f .= '<input type="image" name="submit" style="border: 0;" src="'.$a['button_image'].'" alt="PayPal - The safer, easier way to pay online" />';
        $f .= '</form>';
        
      }else {
        
        $f .= '<div style="color: red;" >ERROR: Incomplete Nice PayPal Button Lite data!</div>';
        
      }
      
      return $f;

    }

  }

} //End Class NicePayPalButtonLite

if (class_exists("NicePayPalButtonLite")) {
    $nice_paypalButtonLite = new NicePayPalButtonLite();
}

//Initialize the admin panel
if ( !function_exists("nicePayPalButtonLite_ap") ) {

    function nicePayPalButtonLite_ap() {
    
        global $nice_paypalButtonLite;
        
        if ( !isset($nice_paypalButtonLite) ) {
        
          return;
          
        }
        
        if ( function_exists('add_options_page') ) {
        
          add_options_page('Nice PayPal Button Lite', 'Nice PayPal Button Lite', 9, basename(__FILE__), array(&$nice_paypalButtonLite, 'printAdminPage'));
       
        }
    }   
}

//Actions and Filters   
if (isset($nice_paypalButtonLite)) {

    // Init admin panel
    add_action('admin_menu', 'nicePayPalButtonLite_ap');
    
    // Retrieve admin options
    add_action('activate_nicePayPalButtonLite/nicePayPalButtonLite.php',  array(&$nice_paypalButtonLite, 'init'));
    
    // Adds shortcode
    add_shortcode('nicepaypallite', array(&$nice_paypalButtonLite, 'getNicePayPalButtonLite'), 1);

}

?>