<?php

	/*	
	*	Goodlayers Portfolio Option File
	*	---------------------------------------------------------------------
	* 	@version	1.0
	* 	@author		Goodlayers
	* 	@link		http://goodlayers.com
	* 	@copyright	Copyright (c) Goodlayers
	*	---------------------------------------------------------------------
	*	This file create and contains the portfolio post_type meta elements
	*	---------------------------------------------------------------------
	*/
	
	// Create basic outline for publications type
	add_action('init', 'wpap_create_publication');
	function wpap_create_publication() {
	
		$labels = array(
			'name'               => _x('Publications', 'Publication General Name', 'gdl_back_office'),
			'singular_name'      => _x('Publication Item', 'Publication Singular Name', 'gdl_back_office'),
			'add_new'            => _x('Add New', 'Add New Publication Name', 'gdl_back_office'),
			'all_items'          => __('All Publications'),
			'add_new_item'       => __('Add New Publication', 'gdl_back_office'),
			'edit_item'          => __('Edit Publication', 'gdl_back_office'),
			'new_item'           => __('New Publication', 'gdl_back_office'),
			'view_item'          => __('View Publication'),
			'search_items'       => __('Search Publications', 'gdl_back_office'),
			'not_found'          => __('Nothing found', 'gdl_back_office'),
			'not_found_in_trash' => __('Nothing found in Trash', 'gdl_back_office'),
			'parent_item_colon'  => ''
		);
		
		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'show_ui'             => true,
			"show_in_nav_menus"        => false,
			'menu_position'       => 5,
		//	'menu_icon'           => GOODLAYERS_PATH . '/include/images/portfolio-icon.png',
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array('title'),
			'rewrite'             => true,
			'query_var'           => true
		); 
		  
		register_post_type('publication' , $args);
		
		register_taxonomy(
			"publication-category", array("publication"), array(
				"hierarchical"      => true, 
				"label"             => "Publication Categories", 
				"singular_label"    => "Publication Categories", 
				"show_in_nav_menus" => false,
				"rewrite"           => true));
		register_taxonomy_for_object_type('publication-category', 'publication');
		
		flush_rewrite_rules();
		
	}
	
	// add table column in edit page
	add_filter("manage_edit-publication_columns", "wpap_show_publication_column");	
	function wpap_show_publication_column ($columns) {
		$columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => "Title",
			"publication-category" => "Publication Categories");
		return $columns;
	}
	
	add_action("manage_posts_custom_column", "wpap_publication_custom_columns");
	function wpap_publication_custom_columns ($column) {
		global $post;

		switch ($column) {
			case "publication-category":
				echo get_the_term_list($post->ID, 'publication-category', '', ', ','');
				break;
		}
	}
	
	// Setup the Publications edit page
	$publication_meta_boxes = array(	
		array(
			'title' => 'Authors',
			'name'  => 'wpap_publication-option-authors',
			'type'  => 'inputtext',
			'extra' => 'List of authors on the paper.'),
		array(
			'title' => 'Conference',
			'name'  => 'wpap_publication-option-conference',
			'type'  => 'inputtext',
			'extra' => 'Conference, year, and description.'),
		array(
			'title' => 'Paper PDF',
			'name'  => 'wpap_publication-option-paperpdf',
			'type'  => 'upload',
			'extra' => 'The PDF of the paper.'),
		array(
			'title' => 'BibTex',
			'name'  => 'wpap_publication-option-bibtex',
			'type'  => 'upload',
			'extra' => 'A .bib file containing the BibTex information.')
		
	);
	
	add_action('add_meta_boxes', 'wpap_add_publication_options');
	function wpap_add_publication_options () {	
	
		global $publication_meta_boxes;

		foreach ($publication_meta_boxes as $opt) {
			add_meta_box('wpap_metabox-' . $opt['title'],
			             __($opt['title']),
						 'wpap_add_publication_option_content',
						 'publication',
						 'normal',
						 'high',
						 $opt);
		}
			
	}
	
	function wpap_add_publication_option_content ($post, $option) {
		$option = $option['args'];
	
		wpap_set_nonce();

		$option['value'] = get_post_meta($post->ID, $option['name'], true);
		wpap_print_option($option);

	}
	
	function wpap_save_publication_option_meta ($post_id) {
	
		global $publication_meta_boxes;
		
				// save
		foreach ($publication_meta_boxes as $opt){
		
			if (isset($_POST[$opt['name']])) {	
				$new_data = stripslashes($_POST[$opt['name']]);		
			} else {
				$new_data = '';
			}
			
			$old_data = get_post_meta($post_id, $opt['name'], true);
			wpap_save_meta_data($post_id, $new_data, $old_data, $opt['name']);
			
		}
		
	}

	add_shortcode('academicpubs', 'wpap_shortcode');
	function wpap_shortcode($atts) {
		extract(shortcode_atts(array(
			'category' => '',
			'numbered' => false,
		), $atts));

		$ret = '<div class="wpapshort">' . $category . '</div>'

		return $ret;
	}
	
?>