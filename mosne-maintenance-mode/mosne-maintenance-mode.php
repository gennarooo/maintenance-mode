<?php
/**
 * Plugin Name:       Mosne Maintenace Mode
 * Description:       Maintenance mode for development use.
 * Version:           1.0
 * Requires at least: 2.8
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
	echo '<fieldset><legend class="screen-reader-text"><span>'.__('Mosne maintenance mode', 'mosne').'</span></legend>
	<label for="m_maintenance_mode"><input name="m_maintenance_mode" type="checkbox" id="m_maintenance_mode" value="1" '.checked(get_option('m_maintenance_mode'), 1, false).'/>'.__('Turn on the maintenace mode', 'mosne').'</label>
	<p class="description">'.__('If checked add the "m-maintenance-mode" class to body.<br>The function m_is_maintenance_mode() check if true/false', 'mosne').'</p>
	</fieldset>';
}

?>