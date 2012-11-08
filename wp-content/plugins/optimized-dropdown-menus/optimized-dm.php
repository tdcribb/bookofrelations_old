<?php
/*  Copyright 2012 Maor Chasen  <maorhaz@gmail.com>

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
/*
Plugin Name: Optimized Dropdown Menus
Plugin URI: http://maorchasen.com/plugins/optimized-dropdown-menus/
Description: Use this widget to create dropdown menus that are searchable or "spiderable" by search engine bots.
Author: <a href="http://twitter.com/maorh">Maor Chasen</a>
Author URI: http://maorchasen.com
Version: 1.0.0
Tags: widget-only, widget, seo
License: GPL2
*/

add_action('plugins_loaded', 'odm_widget_container', 0);

function odm_widget_container() {

	if (!class_exists('WP_Widget')) return;
		class Optimized_Dropdown_Widget extends WP_Widget {
		
			var $plugin_path;
			var $plugin_URL;
			var $localization_domain = 'optimized_dd';
			
			function Optimized_Dropdown_Widget()
			{
				$widget_options = array(
					'classname' => 'optimzed_dd',
					'description' => 'Create a search engine optimized dropdown menu.'
				);
				
				
				parent::WP_Widget('optimzed_dd_widget', __('Optimized Dropdown Menu', $this->localization_domain), $widget_options, $control_options);
				
				// Set Plugin Path
				$this->plugin_path = dirname(plugin_basename(__FILE__));
			
				// Set Plugin URL
				$this->plugin_URL = WP_PLUGIN_URL . '/' . $this->plugin_path;
			}
			

			function form($instance) {
				
				$title 	  = isset( $instance['title'] ) ? $instance['title'] : '';
				$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
				$html5 	  = isset( $instance['html5'] ) ? $instance['html5'] : '';

				// Get menus
				$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

				// If no menus exists, direct the user to go and create some.
				if ( !$menus ) {
					echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
					return;
				}
				?>
				<p>
					<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
					<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:'); ?></label>
					<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
				<?php
					foreach ( $menus as $menu ) {
						$selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
						echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
					}
				?>
					</select>
				</p>
				<p>
					<input class="checkbox" type="checkbox" <?php echo ($html5 == 'on') ? 'checked="checked"' : ''; ?> id="<?php echo $this->get_field_id( 'html5' ); ?>" name="<?php echo $this->get_field_name( 'html5' ); ?>" />
					<label for="<?php echo $this->get_field_id( 'html5' ); ?>">&nbsp;Use HTML5 <code>&lt;nav&gt;</code> element?</label>
				</p>
				<?php
			}

			function update($new_instance, $old_instance) {
				
				$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
				$instance['nav_menu'] = (int) $new_instance['nav_menu'];
				$instance['html5'] = $new_instance['html5'];
				return $instance;
			}

			function widget($args, $instance) {
				// Get menu
				$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

				if ( !$nav_menu )
					return;

				$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

				echo $args['before_widget'];

				if ( !empty($instance['title']) )
					echo $args['before_title'] . $instance['title'] . $args['after_title'];

				$menu_args = array(
					'fallback_cb' => '',
					'menu' => $nav_menu,
					'container' => (($instance['html5'] == 'on') ? 'div' : 'nav'),
					'container_class' => "osm_{$nav_menu->slug}_widget osm_widget"
				);

				// action for developers that might want to hook into this function.
				// the hook passes the menu arguments.
				do_action('optimized_dd_menu', $menu_args);

				wp_nav_menu( $menu_args );

				echo $args['after_widget'];
			}
		}

	function setup_backend_js() {
		// make sure jQuery is already included, if not, include it.
		if ( !wp_script_is('jquery') ) wp_enqueue_script('jquery');
		print_odm_javascript();
	}

	add_action('wp_head', 'setup_backend_js');

	/**
	 * Add function to widgets_init that will later load the widget.
	 * @since 0.1
	 */
	add_action( 'widgets_init', 'osm_load_widgets' );

	/**
	 * Register our widget.
	 * @since 0.1
	 */
	function osm_load_widgets() {
		register_widget( 'Optimized_Dropdown_Widget' );
	}
}

// indicates whether the JS was printed already
$osm_js_flag = false; 

function print_odm_javascript() {
	global $osm_js_flag;

	if ( $osm_js_flag == false ) :
		?>
		<script type="text/javascript">
			// https://gist.github.com/1918689
			jQuery(document).ready(function($) {
				$('.osm_widget ul').each(function(){
				  var list = $(this);
				  var select = $(document.createElement('select'))
				          .attr('id',$(this).attr('id'))
				          .insertBefore($(this).hide());
				  $('>li a', this).each( function(){
				    option = $( document.createElement('option') )
				      .appendTo(select)
				      .val(this.href)
				      .html( $(this).html() );
				  });
				  list.remove();
				  $(document.createElement('button'))
				    .attr('onclick','window.location.href=jQuery(\'#'+$(this).attr('id')+'\').val();')
				    .html('Go')
				    .insertAfter(select);
				});
			});
	    </script>
		<?php
		$osm_js_flag = true;
	endif;
}