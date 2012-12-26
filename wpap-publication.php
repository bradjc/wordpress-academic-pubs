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

	$wpap_options = array (
		'category' => '',
		'numbered' => false,
		'limit'    => -1,
	);
	
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
			"show_in_nav_menus"   => false,
			'menu_position'       => 5,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array('title'),
			'rewrite'             => true,
			'query_var'           => true
		); 
		  
		register_post_type('publication' , $args);
		
		register_taxonomy(
			'publication-category', array('publication'), array(
				'hierarchical'      => true, 
				'label'             => 'Publication Categories', 
				'show_in_nav_menus' => false,
				'rewrite'           => true));
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

	/* Base function that returns a nice array of all the requested publications.
	 * 
	 * Each item in the array contains (if the values are stored):
	 *  id
	 *  title
	 *  authors
	 *  conference
	 *  pdf_url
	 *  bibtex_url
	 *
	 */
	function wpap_get_pubs_array ($options) {

		$pubs = array();

		// query for the publications
		$pubs_q = new WP_Query(array('post_type'            => 'publication',
		                             'publication-category' => $options['category'],
		                             'posts_per_page'       => $options['limit'])
		                      );

		while ($pubs_q->have_posts()) {
			$pub = array();

			$pubs_q->the_post();

			$pub['id']         = $pubs_q->post->ID;
			$pub['title']      = get_the_title();
			$pub['authors']    = get_post_meta($pub['id'], 'wpap_publication-option-authors', true);
			$pub['conference'] = get_post_meta($pub['id'], 'wpap_publication-option-conference', true);
			$pdf               = get_post_meta($pub['id'], 'wpap_publication-option-paperpdf', true);
			$bibtex            = get_post_meta($pub['id'], 'wpap_publication-option-bibtex', true);

			if (!empty($pdf)) {
				$pub[pdf_url] = $pdf;
			}

			if (!empty($bibtex)) {
				$pub[bibtex_url] = $bibtex;
			}

			$pubs[] = $pub;
		}

		return $pubs;
	}

	add_shortcode('academicpubs', 'wpap_shortcode');
	function wpap_shortcode($atts) {
		global $wpap_options;

		// makes all the options nice variables
		// not sure if I like that, however...
		$options = shortcode_atts($wpap_options, $atts);

		$pubs = wpap_get_pubs_array($options);

		$output = '<div class="wpap">';

		if ($options['numbered']) {
			$output .= '<ol>';
		}

		foreach ($pubs as $pub) {
			// Create the links string
			$links = array();
			if (!empty($pub['pdf_url'])) {
				$link = '<a href="' . wp_get_attachment_url($pub['pdf_url']) . '">paper</a>';
				array_push($links, $link);
			}
			if (!empty($pub['bibtex_url'])) {
				$link = '<a href="' . wp_get_attachment_url($pub['bibtex_url']) . '">BibTex</a>';
				array_push($links, $link);
			}
			$links_str = implode(' | ', $links);

			$header = '<h2 class="publication-thumbnail-title post-title-color gdl-title publication'.$pub['id'].'">' . $pub['title'] . '</h2>';
			$body   = '<p>' . $pub['authors'] . '</p><p>' . $pub['conference'] . '</p>';

			$pubout = $header . $body . ((count($links) > 0) ? $links_str : '');

			if ($options['numbered']) {
				$output .= '<li>' . $pubout . '</li>';
			} else {
				$output .= $pubout;
			}

		}

		if ($options['numbered']) {
			$output .= '</ol>';
		}

		$output .= "</div>";

		return $output;
	}

	function wpap_get_publications ($options) {
		return '<div class="wpap"></div>';
	}


	
?>