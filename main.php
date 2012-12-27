<?php
/*
Plugin Name: WP Academic Publications
Plugin URI: https://github.com/bradjc/wordpress-academic-pubs
Description: Adds a Publications tab to wordpress. Allows authors to add a list of academic publications to the blog.
Version: 1.0
Author: Brad Campbell
Author URI: http://bradcampbell.com/
*/


function wpap_scripts () {
	wp_enqueue_media();
	wp_enqueue_script('thickbox');
	wp_register_script('wpap-js', plugins_url('/js/wpap.js', __FILE__), array('jquery','media-upload','thickbox'));
	wp_enqueue_script('wpap-js');
}

function wpap_styles () {
	wp_enqueue_style('thickbox');
}

add_action('admin_print_scripts', 'wpap_scripts');
add_action('admin_print_styles', 'wpap_styles');

require_once('wpap-functions.php');
require_once('wpap-publication.php');

/*
global $wpapl_prefix;
$wpapl_prefix = "wpapl";
global $wpapl_plugin_version;
$wpapl_plugin_version = "0.1.3";


// Include the CSS file to the plugin
function admin_register_head() {
	$siteurl = get_option('siteurl');
	$url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/style.css';
	echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}
add_action('admin_head', 'admin_register_head');

// Includes functions file
	

// Load CSS
wp_enqueue_style( 'wpapl-style', get_option('siteurl') . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/style.css', false, false, 'all' );




// Register the function that will be called when the plugin is activated
register_activation_hook(__FILE__,'wpapl_install');


// Includes the administrative menu file
require_once('admin-panel.php');


// Add shortcode
require_once('shortcode.php');
add_shortcode("academic-people-list", 'wpapl_shortcode_academic_people_list');
add_shortcode("academic-research-areas", 'wpapl_shortcode_academic_reasearch_areas');
add_shortcode("academic-projects", 'wpapl_shortcode_academic_projects');
add_shortcode("academic-publications", 'wpapl_shortcode_academic_publications');




*/
?>