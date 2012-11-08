<?php
/*
Plugin Name: Cyclone Slider
Plugin URI: http://www.codefleet.net/cyclone-slider/
Description: Create amazing slideshows with ease. Built for both developers and non-developers, it features: 25+ transitions, easy-to-use interface with drag and drop functionality, multiple slideshows, individual slideshow settings, shortcode, and customizable templates.
Version: 1.2.1
Author: Nico Amarilla
Author URI: http://www.codefleet.net/
License:

  Copyright 2012 (kosinix@codefleet.net)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/
if(!class_exists('CycloneSlider')):
class CycloneSlider {
	var $plugin_path;
	var $template_slide_box;
	var $template_slide_box_js;
	var $template_slider_dir;
	var $slider_count;
	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/
	
	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {
		// Templates
		$ds = DIRECTORY_SEPARATOR;
		$this->plugin_path = realpath(plugin_dir_path(__FILE__)) . $ds;
		$this->template_slide_box = $this->plugin_path . 'inc'.$ds.'admin'.$ds.'box.php';
		$this->template_slide_box_js = $this->plugin_path . 'inc'.$ds.'admin'.$ds.'box-js.php';
		$this->template_slider_dir = $this->plugin_path . 'templates'.$ds;
		$this->slider_count = 0;

		load_plugin_textdomain( 'cycloneslider', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
		
		// Register admin styles and scripts
		add_action( 'admin_enqueue_scripts', array( &$this, 'register_admin_scripts' ), 10 );
	
		// Register frontend styles and scripts
		add_action( 'wp_enqueue_scripts', array( &$this, 'register_plugin_scripts' ) );
		
		register_activation_hook( __FILE__, array( &$this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );
		
		// Add admin menus
		add_action( 'init', array( &$this, 'create_post_types' ) );
		
		// Update the messages for our custom post make it appropriate for slideshow
		add_filter('post_updated_messages', array( &$this, 'post_updated_messages' ) );
		
		// Add slider metaboxes
		add_action( 'add_meta_boxes', array( &$this, 'add_meta_boxes' ) );
		
		// Save slides
		add_action( 'save_post', array( &$this, 'save_post' ) );
		
		// Hacky way to change text in thickbox
		add_filter( 'gettext', array( $this, 'replace_text_in_thickbox' ), 10, 3 );
		
		// Modify html of image
		add_filter( 'image_send_to_editor', array( $this, 'image_send_to_editor'), 1, 8 );
		
		// Custom columns
		add_action( 'manage_cycloneslider_posts_custom_column', array( $this, 'custom_column' ), 10, 2);
		add_filter( 'manage_edit-cycloneslider_columns', array( $this, 'slideshow_columns') );
		
		// Add hook for admin footer
		add_action('admin_footer', array( $this, 'admin_footer') );
		
		// Our shortcode
		add_shortcode('cycloneslider', array( $this, 'cycloneslider_shortcode') );
		
		// Add query var for so we can access our css via www.mysite.com/?cyclone_templates_css=1
		add_filter('query_vars', array( $this, 'modify_query_vars'));

		// The magic that shows our css
		add_action('template_redirect', array( $this, 'cyclone_css_hook'));
	} // end constructor
	
	/**
	 * Fired when the plugin is activated.
	 *
	 * @params	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	function activate( $network_wide ) {

	} // end activate
	
	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @params	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	function deactivate( $network_wide ) {

	} // end deactivate

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */	
	public function register_admin_scripts() {
		if(get_post_type()=='cycloneslider'){//limit loading of styles and scripts to prevent conflicts
			//styles
			wp_register_style('jquery-ui', plugins_url( 'cyclone-slider/css/jqueryui/smoothness/jquery-ui-1.8.18.custom.css' ) );
			wp_enqueue_style('jquery-ui');
			
			wp_enqueue_style('thickbox');
			
			wp_register_style( 'cyclone-slider-admin-styles', plugins_url( 'cyclone-slider/css/admin.css' ) );
			wp_enqueue_style( 'cyclone-slider-admin-styles' );
			
			//scripts
			wp_dequeue_script( 'autosave' );//disable autosave
			
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('jquery-ui-accordion');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			
			wp_register_script( 'jquery-cookie', plugins_url( 'cyclone-slider/js/jquery.cookie.js' ) );
			wp_enqueue_script( 'jquery-cookie' );
			wp_register_script( 'cyclone-slider-admin-script', plugins_url( 'cyclone-slider/js/admin.js' ) );
			wp_enqueue_script( 'cyclone-slider-admin-script' );
			
		}
	
	} // end register_admin_scripts
	
	/**
	 * Registers and enqueues frontend-specific scripts.
	 */
	public function register_plugin_scripts() {
		//style
		wp_register_style( 'cyclone-slider-plugin-styles', home_url( '/' ).'?cyclone_templates_css=1' );//contains our combined css from ALL templates
		wp_enqueue_style( 'cyclone-slider-plugin-styles' );
		
		//scripts
		wp_register_script( 'cycle-all', plugins_url( 'cyclone-slider/js/jquery.cycle.all.min.js' ), array('jquery') );
		wp_enqueue_script( 'cycle-all' );
		
		wp_register_script( 'jcarousellite', plugins_url( 'cyclone-slider/js/jcarousellite.min.js' ), array('jquery') );
		wp_enqueue_script( 'jcarousellite' );
		
	} // end register_plugin_scripts
	
	/*--------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/
	// Create custom post for slideshows
	function create_post_types() {
		register_post_type( 'cycloneslider',
			array(
				'labels' => array(
					'name' => __('Cyclone Slider', 'cycloneslider'),
					'singular_name' => __('Slideshow', 'cycloneslider'),
					'add_new' => __('Add Slideshow', 'cycloneslider'),
					'add_new_item' => __('Add New Slideshow', 'cycloneslider'),
					'edit_item' => __('Edit Slideshow', 'cycloneslider'),
					'new_item' => __('New Slideshow', 'cycloneslider'),
					'view_item' => __('View Slideshow', 'cycloneslider'),
					'search_items' => __('Search Slideshows', 'cycloneslider'),
					'not_found' => __('No slideshows found', 'cycloneslider'),
					'not_found_in_trash' => __('No slideshows found in Trash', 'cycloneslider')
				),
				'supports' => array('title'),
				'public' => false,
				'exclude_from_search' => true,
				'show_ui' => true,
				'menu_position' => 100
			)
		);
	}
	
	// Messages
	function post_updated_messages($messages){
		global $post, $post_ID;
		$messages['cycloneslider'] = array(
			0  => '',
			1  => sprintf( __( 'Slideshow updated. Shortcode is [cycloneslider id="%s"]', 'cycloneslider' ), $post->post_name),
			2  => __( 'Custom field updated.', 'cycloneslider' ),
			3  => __( 'Custom field deleted.', 'cycloneslider' ),
			4  => __( 'Slideshow updated.', 'cycloneslider' ),
			5  => __( 'Slideshow updated.', 'cycloneslider' ),
			6  => sprintf( __( 'Slideshow published. Shortcode is [cycloneslider id="%s"]', 'cycloneslider' ), $post->post_name),
			7  => __( 'Slideshow saved.', 'cycloneslider' ),
			8  => __( 'Slideshow updated.', 'cycloneslider' ),
			9  => __( 'Slideshow updated.', 'cycloneslider' ),
			10 => __( 'Slideshow updated.', 'cycloneslider' )
		);
		return $messages;
	}
	
	// Slides metabox init
	function add_meta_boxes(){
		add_meta_box(
			'cyclone-slides-metabox',
			__('Slides', 'cycloneslider'),
			array( &$this, 'render_slides_meta_box' ),
			'cycloneslider' ,
			'normal',
			'high'
		);
		add_meta_box(
			'cyclone-slider-properties-metabox',
			__('Slider Settings', 'cycloneslider'),
			array( &$this, 'render_slider_properties_meta_box' ),
			'cycloneslider' ,
			'side',
			'low'
		);
		add_meta_box(
			'cyclone-slider-templates-metabox',
			__('Slider Templates', 'cycloneslider'),
			array( &$this, 'render_slider_templates_meta_box' ),
			'cycloneslider' ,
			'normal',
			'low'
		);
	}
	
	// Get Image mime type. @param $image - full path to image
	function get_mime_type( $image ){
		if($properties = getimagesize( $image )){
			return $properties['mime'];
		}
		return false;
	}
	
	// Slides metabox render
	function render_slides_meta_box($post){
		
		// Use nonce for verification
		echo '<input type="hidden" name="cycloneslider_metabox_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		$meta = get_post_custom($post->ID);

		$slider_metas = $this->get_slider_metas($meta);
		$slider_settings = $this->get_slider_admin_settings($meta);
		
		$debug = 0;
		if($debug){
		echo '<pre>';
		print_r($slider_settings);
		print_r($slider_metas);
		echo '</pre>';
		}
		?>
		
		<input type="button" value="<?php _e('Add Slide', 'cycloneslider'); ?>" class="button-secondary" name="cycloneslider_add_slide">
	
		<div class="cycloneslider-sortable">
			<?php
			if(is_array($slider_metas) and count($slider_metas)>0):
			foreach($slider_metas as $i=>$slider_meta):
				$image_url = wp_get_attachment_url($slider_meta['id']);
				$image_url = ($image_url===false) ? '' : $image_url;
				include($this->template_slide_box);
			endforeach;
			endif;
			?>
		</div><!-- end .cycloneslider-sortable -->
		<?php
	}
	
	function render_slider_properties_meta_box($post){
		// Use nonce for verification
		echo '<input type="hidden" name="cycloneslider_metabox_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

		$meta = get_post_custom($post->ID);
		$slider_settings = $this->get_slider_admin_settings($meta);
		//default values
		$slider_settings['timeout'] = ($slider_settings['timeout']===null) ? 4000 : $slider_settings['timeout'];
		$slider_settings['speed'] = ($slider_settings['speed']===null) ? 1000 : $slider_settings['speed'];
		$slider_settings['width'] = ($slider_settings['width']===null) ? 960 : $slider_settings['width'];
		$slider_settings['height'] = ($slider_settings['height']===null) ? 300 : $slider_settings['height'];
		$slider_settings['show_prev_next'] = ($slider_settings['show_prev_next']===null) ? 1 : $slider_settings['show_prev_next'];
		$slider_settings['show_nav'] = ($slider_settings['show_nav']===null) ? 1 : $slider_settings['show_nav'];
		
		$effects = array(
			'fade'=>'Fade',
			'blindX'=>'Blind X',
			'blindY'=>'Blind Y',
			'blindZ'=>'Blind Z',
			'cover'=>'Cover',
			'curtainX'=>'Curtain X',
			'curtainY'=>'Curtain Y',
			'fadeZoom'=>'Fade Zoom',
			'growX'=>'Grow X',
			'growY'=>'Grow Y',
			'none'=>'None',
			'scrollUp'=>'Scroll Up',
			'scrollDown'=>'Scroll Down',
			'scrollLeft'=>'Scroll Left',
			'scrollRight'=>'Scroll Right',
			'scrollHorz'=>'Scroll Horizontally',
			'scrollVert'=>'Scroll Vertically',
			'shuffle'=>'Shuffle',
			'slideX'=>'Slide X',
			'slideY'=>'Slide Y',
			'toss'=>'Toss',
			'turnUp'=>'Turn Up',
			'turnDown'=>'Turn Down',
			'turnLeft'=>'Turn Left',
			'turnRight'=>'Turn Right',
			'uncover'=>'Uncover',
			'wipe'=>'Wipe',
			'zoom'=>'Zoom'
		);
		?>
		<div class="cycloneslider-field">
			<label for="cycloneslider_settings_fx"><?php _e('Transition Effects to Use:', 'cycloneslider'); ?></label>
			<select id="cycloneslider_settings_fx" name="cycloneslider_settings[fx]">
			<?php foreach($effects as $key=>$fx): ?>
			<option <?php echo ($key==$slider_settings['fx']) ? 'selected="selected"' : ''; ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($fx); ?></option>
			<?php endforeach; ?>
			</select>
			<div class="clear"></div>
		</div>
		<?php
		?>
		<div class="cycloneslider-field">
			<label for="cycloneslider_settings_timeout"><?php _e('Next Slide Delay:', 'cycloneslider'); ?> </label>
			<input id="cycloneslider_settings_timeout" type="text" name="cycloneslider_settings[timeout]" value="<?php echo esc_attr($slider_settings['timeout']); ?>" />
			<span class="note"><?php _e('Milliseconds. 0 to disable auto advance.', 'cycloneslider'); ?></span>
			<div class="clear"></div>
		</div>
		<div class="cycloneslider-field">
			<label for="cycloneslider_settings_speed"><?php _e('Transition Effects Speed:', 'cycloneslider'); ?></label>
			<input id="cycloneslider_settings_speed" type="text" name="cycloneslider_settings[speed]" value="<?php echo esc_attr($slider_settings['speed']); ?>" />
			<span class="note"><?php _e('Milliseconds', 'cycloneslider'); ?></span>
			<div class="clear"></div>
		</div>
		<div class="cycloneslider-field">
			<label for="cycloneslider_settings_width"><?php _e('Width:', 'cycloneslider'); ?> </label>
			<input id="cycloneslider_settings_width" type="text" name="cycloneslider_settings[width]" value="<?php echo esc_attr($slider_settings['width']); ?>" />
			<span class="note"><?php _e('pixels.', 'cycloneslider'); ?></span>
			<div class="clear"></div>
		</div>
		<div class="cycloneslider-field">
			<label for="cycloneslider_settings_height"><?php _e('Height:', 'cycloneslider'); ?> </label>
			<input id="cycloneslider_settings_height" type="text" name="cycloneslider_settings[height]" value="<?php echo esc_attr($slider_settings['height']); ?>" />
			<span class="note"><?php _e('pixels.', 'cycloneslider'); ?></span>
			<div class="clear"></div>
		</div>
		<div class="cycloneslider-field">
			<label for="cycloneslider_settings_show_prev_next"><?php _e('Show Prev/Next Buttons?', 'cycloneslider'); ?></label>
			<select id="cycloneslider_settings_show_prev_next" name="cycloneslider_settings[show_prev_next]">
				<option <?php echo (1==$slider_settings['show_prev_next']) ? 'selected="selected"' : ''; ?> value="1"><?php _e('Yes', 'cycloneslider'); ?></option>
				<option <?php echo (0==$slider_settings['show_prev_next']) ? 'selected="selected"' : ''; ?> value="0"><?php _e('No', 'cycloneslider'); ?></option>
			</select>
			<div class="clear"></div>
		</div>
		<div class="cycloneslider-field last">
			<label for="cycloneslider_settings_show_nav"><?php _e('Show Navigation?', 'cycloneslider'); ?></label>
			<select id="cycloneslider_settings_show_nav" name="cycloneslider_settings[show_nav]">
				<option <?php echo (1==$slider_settings['show_nav']) ? 'selected="selected"' : ''; ?> value="1"><?php _e('Yes', 'cycloneslider'); ?></option>
				<option <?php echo (0==$slider_settings['show_nav']) ? 'selected="selected"' : ''; ?> value="0"><?php _e('No', 'cycloneslider'); ?></option>
			</select>
			<div class="clear"></div>
		</div>
		<?php
	}
	
	function render_slider_templates_meta_box($post){
		// Use nonce for verification
		echo '<input type="hidden" name="cycloneslider_metabox_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

		$meta = get_post_custom($post->ID);
		$slider_settings = $this->get_slider_admin_settings($meta);
		//default values
		$slider_settings['template'] = ($slider_settings['template']===null) ? 'default' : $slider_settings['template'];
		
		$templates = $this->get_all_templates();
		$template_options = array();
		$template_options['default'] = 'Default';
		foreach($templates as $name=>$template){
			if($name!='default'){
				$template_options[$name]= ucwords(str_replace('-',' ',$name));
			}
		}
		?>
		<div class="cycloneslider-field last">
			<label for="cycloneslider_settings_template"><?php _e('Select Template to Use:', 'cycloneslider'); ?></label>
			<select id="cycloneslider_settings_template" name="cycloneslider_settings[template]">
			<?php foreach($template_options as $key=>$name): ?>
			<option <?php echo ($key==$slider_settings['template']) ? 'selected="selected"' : ''; ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($name); ?></option>
			<?php endforeach; ?>
			</select>
			<div class="clear"></div>
		</div>
		<?php
	}
	
	function save_post($post_id){

		// verify nonce
		if (!empty($_POST['cycloneslider_metabox_nonce'])) {
			if (!wp_verify_nonce($_POST['cycloneslider_metabox_nonce'], basename(__FILE__))) {
				return $post_id;
			}
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		    return $post_id;
		}

		//slide metas
		$this->save_metas($post_id);
		
		//settings
		$this->save_settings($post_id);
		
	}
	
	//sanitize and save
	function save_metas($post_id){

		if(isset($_POST['cycloneslider_metas'])){
			$slides = array();
			$i=0;//always start from 0
			foreach($_POST['cycloneslider_metas'] as $slide){
				$slides[$i]['id'] = (int) $slide['id'];
				$slides[$i]['link'] = esc_url_raw($slide['link']);
				$slides[$i]['title'] = sanitize_text_field($slide['title']);
				$slides[$i]['description'] = wp_kses_post($slide['description']);
				$i++;
			}
			delete_post_meta($post_id, 'cycloneslider_metas');//remove duplicates. deprecated key. will remove in the future
			delete_post_meta($post_id, '_cycloneslider_metas');//as of 1.0.2 we are prefixing _ to hide it from wp custom fields metabox
			update_post_meta($post_id, '_cycloneslider_metas', $slides);
		}
	}
	
	//sanitize and save 
	function save_settings($post_id){
		if(isset($_POST['cycloneslider_settings'])){
			$settings = array();
			$settings['fx'] = wp_filter_nohtml_kses($_POST['cycloneslider_settings']['fx']);
			$settings['timeout'] = (int) wp_filter_nohtml_kses($_POST['cycloneslider_settings']['timeout']);
			$settings['speed'] = (int) wp_filter_nohtml_kses($_POST['cycloneslider_settings']['speed']);
			$settings['width'] = (int) wp_filter_nohtml_kses($_POST['cycloneslider_settings']['width']);
			$settings['height'] = (int) wp_filter_nohtml_kses($_POST['cycloneslider_settings']['height']);
			$settings['show_prev_next'] = (int) wp_filter_nohtml_kses($_POST['cycloneslider_settings']['show_prev_next']);
			$settings['show_nav'] = (int) wp_filter_nohtml_kses($_POST['cycloneslider_settings']['show_nav']);
			$settings['template'] = wp_filter_nohtml_kses($_POST['cycloneslider_settings']['template']);
			
			delete_post_meta($post_id, 'cycloneslider_settings');//remove duplicates
			delete_post_meta($post_id, '_cycloneslider_settings');
			update_post_meta($post_id, '_cycloneslider_settings', $settings);
		}
	}
	
	function replace_text_in_thickbox($translation, $text, $domain ) {
		$http_referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		$req_referrer = isset($_REQUEST['referer']) ? $_REQUEST['referer'] : '';
		if(strpos($http_referrer, 'cycloneslider')!==false or $req_referrer=='cycloneslider') {
			if ( 'default' == $domain and 'Insert into Post' == $text )
			{
				return 'Use this image';
			}
		}
		return $translation;
	}
	
	// Add attachment ID as html5 data attr in thickbox
	function image_send_to_editor( $html, $id, $caption, $title, $align, $url, $size, $alt = '' ){
		return str_replace('/>', ' data-id="'.$id.'" />', $html);
	}
	
	// Modify columns
	function slideshow_columns($columns) {
		$columns = array();
		$columns['title']= __('Slideshow Name', 'cycloneslider');
		$columns['id']= __('Slideshow ID', 'cycloneslider');
		$columns['shortcode']= __('Shortcode', 'cycloneslider');
		return $columns;
	}
	
	// Add content to custom columns
	function custom_column( $column_name, $post_id ){
		if ($column_name == 'id') {
			$post = get_post($post_id);
			echo $post->post_name;
		}
		if ($column_name == 'shortcode') {  
			$post = get_post($post_id);
			echo '[cycloneslider id="'.$post->post_name.'"]';
		}  
	}
	
	// For js adding of box
	function admin_footer() {
		if(get_post_type()=='cycloneslider'){
			?>
			<div class="cycloneslider-box-template">
				<?php
				include_once($this->template_slide_box_js);
				?>
			</div><!-- end .cycloneslider-box-template -->
		<?php
		}
	}
	
	// Compare the value from admin and shortcode. If shortcode value is present and not empty, use it, otherwise return admin value
	function get_comp_slider_setting($admin_val, $shortcode_val){
		if($shortcode_val!==null){//make sure its really null and not just int zero 0
			return $shortcode_val;
		}
		return $admin_val;
	}
	
	/* Our shortcode function.
	  Slider settings comes from both admin settings and shortcode attributes.
	  Shortcode attributes, if present, will override the admin settings.
	*/
	function cycloneslider_shortcode($shortcode_settings) {
		// Process shortcode settings and return only allowed vars
		$shortcode_settings = shortcode_atts(
			array(
				'id' => 0,
				'template' => null,
				'fx' => null,
				'speed' => null,
				'timeout' => null,
				'width' => null,
				'height' => null,
				'show_prev_next' => null,
				'show_nav' => null
			),
			$shortcode_settings
		);
		$slider_id = esc_attr($shortcode_settings['id']);
		//$template = esc_attr($shortcode_settings['template']);
		$cycle_options = array();
		$this->slider_count++;
		// Get slideshow by id
		$my_query = new WP_Query(
			array(
				'post_type' => 'cycloneslider',
				'order'=>'ASC',
				'posts_per_page' => 1,
				'name'=> $slider_id
			)
		);
		if($my_query->have_posts()):
			while ( $my_query->have_posts() ) : $my_query->the_post();
				
				$meta = get_post_custom();
				
				$admin_settings = $this->get_slider_admin_settings($meta);
				$slider_metas = $this->get_slider_metas($meta);
				$slides = $this->get_slides_from_meta($slider_metas);
				
				$template = $this->get_comp_slider_setting($admin_settings['template'], $shortcode_settings['template']);
				$template = esc_attr($template===null ? 'default' : $template);//fallback to default
				$slider_settings['fx'] = esc_attr($this->get_comp_slider_setting($admin_settings['fx'], $shortcode_settings['fx']));
				$slider_settings['speed'] = (int) $this->get_comp_slider_setting($admin_settings['speed'], $shortcode_settings['speed']);
				$slider_settings['timeout'] = (int) $this->get_comp_slider_setting($admin_settings['timeout'], $shortcode_settings['timeout']);
				$slider_settings['width'] = (int) $this->get_comp_slider_setting($admin_settings['width'], $shortcode_settings['width']);
				$slider_settings['height'] = (int) $this->get_comp_slider_setting($admin_settings['height'], $shortcode_settings['height']);
				$slider_settings['show_prev_next'] = (int) $this->get_comp_slider_setting($admin_settings['show_prev_next'], $shortcode_settings['show_prev_next']);
				$slider_settings['show_nav'] = (int) $this->get_comp_slider_setting($admin_settings['show_nav'], $shortcode_settings['show_nav']);
				
				$slider = $this->get_slider_template($slider_id, $template, $slides, $slider_metas, $slider_settings, $this->slider_count);
				
			endwhile;
			
			wp_reset_postdata();

		else:
			$slider = __('[Slideshow not found]', 'cycloneslider');
		endif;
		
		return $slider;
	}
	
	// Process the post meta and return the settings
	function get_slider_admin_settings($meta){
		if(isset($meta['cycloneslider_settings'][0]) and !empty($meta['cycloneslider_settings'][0])){//from version 1.0.0. set for deletion in future releases
			return maybe_unserialize($meta['cycloneslider_settings'][0]);
		}
		if(isset($meta['_cycloneslider_settings'][0]) and !empty($meta['_cycloneslider_settings'][0])){//we have added prefix _ since 1.0.2
			return maybe_unserialize($meta['_cycloneslider_settings'][0]);
		}
		return false;
	}
	
	// Process the post meta and return the settings
	function get_slider_metas($meta){
		if(isset($meta['cycloneslider_metas'][0]) and !empty($meta['cycloneslider_metas'][0])){//from version 1.0.0. set for deletion in future releases
			return maybe_unserialize($meta['cycloneslider_metas'][0]);
		}
		if(isset($meta['_cycloneslider_metas'][0]) and !empty($meta['_cycloneslider_metas'][0])){//we have added prefix _ since 1.0.2
			return maybe_unserialize($meta['_cycloneslider_metas'][0]);
		}
		return false;
	}
	
	// Get slideshow template
	function get_slider_template($slider_id, $template_name, $slides, $slider_metas, $slider_settings, $slider_count){

		$template = get_stylesheet_directory()."/cycloneslider/{$template_name}/slider.php";
		if(@is_file($template)){
			ob_start();
			include($template);
			$html = ob_get_clean();
			return $html = $this->trim_white_spaces($html);
		}
		
		$template = $this->template_slider_dir."{$template_name}/slider.php";
		if(@is_file($template)) {
			ob_start();
			include($template);
			$html = ob_get_clean();
			return $html = $this->trim_white_spaces($html);
		}
		
		return sprintf(__('[Template "%s" not found]', 'cycloneslider'), $template_name);
	}
	function trim_white_spaces($buffer){
		$search = array(
			'/\>[^\S ]+/s', //strip whitespaces after tags, except space
			'/[^\S ]+\</s', //strip whitespaces before tags, except space
			'/(\s)+/s'  // shorten multiple whitespace sequences
        );
		$replace = array(
			'>',
			'<',
			'\\1'
		);
		return preg_replace($search, $replace, $buffer);
	}

	// Return array of slide urls from meta
	function get_slides_from_meta($slider_metas){
		$slides = array();
		if(is_array($slider_metas)){
			foreach($slider_metas as $slider_meta){
				$attachment_id = (int) $slider_meta['id'];
				$image_url = wp_get_attachment_url($attachment_id);
				$image_url = ($image_url===false) ? '' : $image_url;
				$slides[] = $image_url;
			}
		}
		return $slides;
	}
	
	// Add custom query var
	function modify_query_vars($vars) {
		$vars[] = 'cyclone_templates_css';//add our own
		return $vars;
	}
	
	// Hook to template redirect
	function cyclone_css_hook() {
		if(intval(get_query_var('cyclone_templates_css')) == 1) {
			header("Content-type: text/css");
?>
.cycloneslider{
	position:relative;
}
.cycloneslider-slides{
	position:relative;
}
.cycloneslider-slide{
	position:absolute;
	left:0;
	top:0;
	width:100%;
	z-index:100;
}
.cycloneslider-slide img{
	border:0;
	padding:0;
	margin:0;
}
.cycloneslider-prev,.cycloneslider-next{
	cursor:pointer;
}
.cycloneslider-slide:first-child{
	z-index:101;
}<?php
			$template_folders = $this->get_all_templates();
			$ds = DIRECTORY_SEPARATOR;
			foreach($template_folders as $name=>$folder){
				$style = $folder['path']."{$ds}style.css";
				if(file_exists($style)){
					echo "\n".str_replace('$tpl', $folder['url'], file_get_contents($style));//apply url and print css
				}
			}
			die();
		}
	}
	
	// Get all template locations. Returns array of locations containing path and url 
	function get_all_locations(){
		$ds = DIRECTORY_SEPARATOR;
		$template_locations = array();
		$template_locations[0] = array(
			'path'=>$this->template_slider_dir, //this resides in the plugin
			'url'=>plugin_dir_url(__FILE__).'templates/'
		);
		$template_locations[1] = array(
			'path'=> realpath(get_stylesheet_directory())."{$ds}cycloneslider{$ds}",//this resides in the current theme or child theme
			'url'=> get_stylesheet_directory_uri()."/cycloneslider/"
		);
		return $template_locations;
	}
	
	// Get all templates from all locations. Returns array of templates with keys as name containing array of path and url
	function get_all_templates(){
		$template_locations = $this->get_all_locations();
		$template_folders = array();
		foreach($template_locations as $location){
			if($files = @scandir($location['path'])){
				$c = 0;
				foreach($files as $name){
					if($name!='.' and $name!='..' and is_dir($location['path'].$name)){
						$name = sanitize_title($name);//change space to dash and all lowercase
						$template_folders[$name] = array( //here we override template of the same names. templates inside themes take precedence
							'path'=>$location['path'].$name,
							'url'=>$location['url'].$name,
						);
					}
				}
			}
		}
		return $template_folders;
	}
	
} // end class
new CycloneSlider();

/**
 * Thumbnailer for cycloneslider
 *
 * Creates thumbnail of the slide image using the specified attachment ID, width and height
 *
 * @since 1.0.2
 *
 * @param int $original_attachment_id Attachment ID.
 * @param int $width Width of thumbnail in pixels.
 * @param int $height Height of thumbnail in pixels.
 * @param bool $refresh Recreate thumbnail if it already exists if set to true. Default to false, will not recreate thumbnails if it already exist.
 * @return string The url to the thumbnail. False on failure.
 */
// 
function cycloneslider_thumb( $original_attachment_id, $width, $height, $refresh = false ){
	$dir = wp_upload_dir();
	
	// Get full path to the slide image
	$image_path = get_attached_file($original_attachment_id);
	if(empty($image_path)){
		return false;
	}
	
	// Full url to the slide image
	$image_url = wp_get_attachment_url($original_attachment_id);
	if(empty($image_path)){
		return false;
	}
	
	
	$info = pathinfo($image_path);
	$dirname = isset($info['dirname']) ? $info['dirname'] : '';
	$ext = isset($info['extension']) ? $info['extension'] : '';
	$thumb = wp_basename($image_path, ".$ext")."-{$width}x{$height}.{$ext}";
	
	// Check if thumb already exists. If it is, return its url, unless refresh is true
	if(file_exists($dirname.'/'.$thumb ) and !$refresh){
		return dirname($image_url).'/'.$thumb;
	}
	
	// On success path to resized image is returned. On error WP_error object
	$resized_image = image_resize( $image_path, $width, $height, true, null, $dirname); 
	if(is_wp_error($resized_image)){
		return false;
	}

	// Get full url to the resized image 
	return dirname($image_url).'/'.wp_basename($resized_image);//full path to resized image 
}

/**
 * Transparent GIF Generator
 *
 * Creates trasnparent gif for use by the responsive template passing the width and height
 *
 * @since 1.1.0
 *
 * @param int $width Width in pixels.
 * @param int $height Height in pixels.
 * @param bool $refresh Recreate if it already exists if set to true. Default to false, will not recreate if it already exist.
 * @return string The url to the image. False on failure.
 */
// 
function cycloneslider_trans( $width=1, $height=1, $refresh = false ){
	$dir = wp_upload_dir();
	
	// Check if thumb already exists. Return its url
	$thumb = "cycloneslider_trans-{$width}x{$height}.gif";
	if(file_exists($dir['path'].'/'.$thumb ) and !$refresh){
		return $dir['url'].'/'.$thumb;
	}
	
	// Create it
	if(function_exists('imagecreate')){ //check for gd lib
		$image = imagecreate( $width, $height );
		$background = imagecolorallocate( $image,  255, 255, 255);
		
		imagecolortransparent($image, $background);
		
		if(!imagegif($image, $dir['path'].'/'.$thumb)){
			return false; //error
		}
		imagedestroy( $image );
	} else {
		return false;
	}
	// Get full url to the image 
	return $dir['url'].'/'.$thumb;
}
endif;