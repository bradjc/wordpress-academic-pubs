<?php
/*
Plugin Name: WP Academic Publications
Plugin URI: https://github.com/bradjc/wordpress-academic-pubs
Description: Adds a Publications tab to wordpress. Allows authors to add a list of academic publications to the blog.
Version: 1.2
Author: Brad Campbell
Author URI: http://bradcampbell.com/
*/


function wpap_scripts () {
	wp_enqueue_media();
	wp_register_script('wpap-js', plugins_url('/js/wpap.js', __FILE__), array('jquery'));
	wp_enqueue_script('wpap-js');
}

function wpap_styles () {
	wp_enqueue_style('thickbox');
}

function wpap_loadtextdomain() {
	load_plugin_textdomain('wpap', false, basename(dirname(__FILE__)) . '/languages/' );
}

add_action('admin_print_scripts', 'wpap_scripts');
add_action('admin_print_styles', 'wpap_styles');

require_once('wpap-functions.php');
require_once('wpap-publication.php');


add_filter('upload_mimes', 'wpap_add_bib_to_mimes');
add_filter('manage_edit-publication_columns', 'wpap_show_publication_column');

add_action('save_post', 'wpap_save_option_meta');
add_action('init', 'wpap_create_publication');
add_action('manage_posts_custom_column', 'wpap_publication_custom_columns');
add_action('add_meta_boxes', 'wpap_add_publication_options');

add_shortcode('academicpubs', 'wpap_shortcode');

add_action('plugins_loaded', 'wpap_loadtextdomain');

?>