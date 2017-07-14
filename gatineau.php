<?php
/*
Plugin Name: adCenter Analytics for WordPress
Version: 1.0
Plugin URI: http://www.joostdevalk.nl/wordpress/adcenter-analytics/
Description: Adds Microsoft adCenter Analytics tags to your WordPress without any hassle
Author: Joost de Valk
Author URI: http://www.joostdevalk.nl/
*/

function gatineau_control() {
	$options = get_option('gatineau');
	if ( !is_array($options) ) {
		//This array sets the default options for the plugin when it is first activated.
		$options = array('profileid'=>'', 'position'=>'footer');
	}
	if ( $_POST['gatineau-submit'] ) {
		$options['profileid'] = strip_tags(stripslashes($_POST['gatineau-profileid']));
		$options['position'] = strip_tags(stripslashes($_POST['gatineau-position']));
		update_option('gatineau', $options);
	}

	$profileid = htmlspecialchars($options['profileid'], ENT_QUOTES);

	echo '<p><label for="gatineau-profileid">Profile ID:</label><br /> <input style="width: 200px;" id="gatineau-profileid" name="gatineau-profileid" type="text" value="'.$profileid.'" /></p>';
	echo '<p><label for="gatineau-position">Position the tag in the:</label><br /> <select style="width: 200px;" id="gatineau-position" name="gatineau-position">';
	if ($options['position'] == 'header') {
		echo '<option value="footer">footer</option><option selected="selected" value="header">header</option>';
	} else {
		echo '<option selected="selected" value="footer">footer</option><option value="header">header</option>';
	}
	echo '</select></p>';
	echo '<input type="hidden" id="gatineau-submit" name="gatineau-submit" value="1" />';
}

//This function is called by gatineau_addMenu, and displays the options panel
function gatineau_optionsMenu() {
	echo '<div class="wrap">';
	echo '<h2>adCenter Analytics Configuration</h2>';
	echo '<form method="post">';
	gatineau_control();
	echo '<p class="submit"><input value="Save Changes &raquo;" type="submit"></form></p></div>';
}

function gatineau_tag() {
	$options = get_option('gatineau');
	if ($options['profileid'] != '') {
		echo "\n".'<!-- Microsoft adCenter Analytics tag added by Joost de Valk\'s adCenter Analytics for WordPress plugin. -->'."\n";
		echo '<script language="javascript" type="text/javascript" src="http://analytics.r.msn.com/Analytics/msAnalytics.js"></script>'."\n";
		echo '<script language="javascript" type="text/javascript">'."\n";
		echo "\t".'msAnalytics.ProfileId = \''.$options['profileid'].'\';'."\n";
		echo "\t".'msAnalytics.TrackPage();'."\n";
		echo '</script>';
	}
}

$options = get_option('gatineau');
if ($options['position'] == 'header') {
	add_action('wp_head','gatineau_tag');
} else {
	add_action('wp_footer','gatineau_tag');
}

function add_config_page() {
	global $wpdb;
	if ( function_exists('add_submenu_page') ) {
		add_submenu_page('plugins.php','adCenter Analytics', 'adCenter Analytics', 1, basename(__FILE__), 'gatineau_optionsMenu');
	}
} // end add_config_page()

add_action('admin_menu', 'add_config_page');

?>