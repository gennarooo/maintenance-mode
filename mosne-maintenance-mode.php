<?php
/**
 * Plugin Name:       Mosne Maintenace Mode
 * Description:       Maintenance mode for development use.
 * Version:           1.0.2
 * Update URI:        https://raw.githubusercontent.com/gennarooo/plugins-updates/master/plugins-info.json
 * Requires at least: 5.8
 * Author:            mosne
 * Author URI:        https://mosne.it
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mosne-maintenance-mode
 * Domain Path:       /languages
 */

 /**
* Evaluate the maintenance status
* 
* @return bool
*/

function m_is_maintenance_mode(){
	if(get_option('m_maintenance_mode') == 1) {
		return true;
	}else{
		return false;
	}
}

/**
* Evaluate if the maintenance status is on and the the current visitor is a logged in user 
* 
* @return bool
*/

function m_is_maintenance_mode_loggedin(){
	if(m_is_maintenance_mode() && current_user_can('administrator')) {
		return true;
	}else{
		return false;
	}
}


/**
 * Add body class if maintenace mode is true
 */

if(m_is_maintenance_mode()) {
	add_filter( 'body_class', 'm_maintenance_mode_add_body_class' );
}

/**
 * Add the body class
 */

function m_maintenance_mode_add_body_class( $classes ) {
	$classes[] = 'm-maintenance-mode';
	return $classes;
}

/**
 * Add the admin settings to turn on/off the maintenance mode
 */

add_action('admin_init', function(){
	register_setting( 
		'reading',
		'm_maintenance_mode'
	);

	add_settings_field(
		'm_maintenance_mode',
		__('Mosne maintenace mode', 'mosne-maintenance-mode'),
		function(){
			m_add_maintenance_mode_checkbox();
		},
		'reading',
		'default'
		// array( 'label_for' => $page_id )
	);
});

/**
 * Generate the on/off checkbox
 */

function m_add_maintenance_mode_checkbox(){
	echo '<fieldset><legend class="screen-reader-text"><span>'.__('Mosne maintenance mode', 'mosne-maintenance-mode').'</span></legend>
	<label for="m_maintenance_mode"><input name="m_maintenance_mode" type="checkbox" id="m_maintenance_mode" value="1" '.checked(get_option('m_maintenance_mode'), 1, false).'/>'.__('Turn on the maintenace mode', 'mosne-maintenance-mode').'</label>
	<p class="description">'.__('If checked add the "m-maintenance-mode" class to body.<br>The function m_is_maintenance_mode() check if true/false', 'mosne-maintenance-mode').'</p>
	</fieldset>';
}

/**
 *  Check for Updates
 */
if( ! function_exists( 'mosne_maintenance_mode_check_for_updates' ) ){
    
	function mosne_maintenance_mode_check_for_updates( $update, $plugin_data, $plugin_file ){
		 
		 static $response = false;
		 
		 if( empty( $plugin_data['UpdateURI'] ) || ! empty( $update ) )
			  return $update;
		 
		 if( $response === false )
			  $response = wp_remote_get( $plugin_data['UpdateURI'] );
		 
		 if( empty( $response['body'] ) )
			  return $update;
		 
		 $custom_plugins_data = json_decode( $response['body'], true );
		 
		 if( ! empty( $custom_plugins_data[ $plugin_file ] ) )
			  return $custom_plugins_data[ $plugin_file ];
		 else
			  return $update;
		 
	}
	
	add_filter('update_plugins_github.com', 'mosne_maintenance_mode_check_for_updates', 10, 3);
	
}
?>