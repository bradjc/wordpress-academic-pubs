<?php


	//////////////////////////////////////////////
	//
	// ADMIN Section Functions
	//
	//////////////////////////////////////////////

	// Create basic outline for publications type
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
	function wpap_show_publication_column ($columns) {
		$columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => "Title",
			"publication-category" => "Publication Categories");
		return $columns;
	}

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
			'extra' => 'A .bib file containing the BibTex information.'),
		array(
			'title' => 'Website',
			'name'  => 'wpap_publication-option-website',
			'type'  => 'inputtext',
			'extra' => 'A URL for the project/paper website.')

	);

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


	//////////////////////////////////////////////
	//
	// DISPLAY Section Functions
	//
	//////////////////////////////////////////////


	$wpap_options = array (
		'category'     => '',
		'numbered'     => 'false',
		'limit'        => -1,
		'reverse'      => 'false',
		'show_links'   => 'true',
		'page_num'     => '',
		'num_per_page' => '',
	);

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

		$order = (strtolower($options['reverse']) == 'true') ? 'ASC' : 'DESC';

		// query for the publications
		$pubs_q = new WP_Query(array('post_type'            => 'publication',
		                             'publication-category' => $options['category'],
		                             'order'                => $order,
		                             'paged'                => $options['page_num'],
		                             'posts_per_page'       => $options['num_per_page'],
		                            )
		                      );

		$count = 0;
		while ($pubs_q->have_posts()) {
			if ($count == $options['limit']) {
				// only display that many
				break;
			}

			$pub = array();

			$pubs_q->the_post();

			$pub['id']         = $pubs_q->post->ID;
			$pub['title']      = get_the_title();
			$pub['authors']    = get_post_meta($pub['id'], 'wpap_publication-option-authors', true);
			$pub['conference'] = get_post_meta($pub['id'], 'wpap_publication-option-conference', true);
			$pdf               = get_post_meta($pub['id'], 'wpap_publication-option-paperpdf', true);
			$bibtex            = get_post_meta($pub['id'], 'wpap_publication-option-bibtex', true);
			$website           = get_post_meta($pub['id'], 'wpap_publication-option-website', true);

			if (!empty($pdf)) {
				$pub['pdf_url'] = $pdf;
			}

			if (!empty($bibtex)) {
				$pub['bibtex_url'] = $bibtex;
			}

			if (!empty($website)) {
				$pub['website_url'] = $website;
			}

			$pubs[] = $pub;
			$count++;
		}

		return $pubs;
	}

	/* The second base function that takes the raw publication data and puts it
	 * into nice html tags.
	 *
	 * Also needs the $options because some options are formatting related
	 */
	function wpap_get_pubs_formatted ($options) {

		// get the publication data
		$pubs = wpap_get_pubs_array($options);

		$output = '';

		foreach ($pubs as $pub) {
			// Create the links string
			$links = array();
			if (strtolower($options['show_links']) == 'true') {
				if (!empty($pub['pdf_url'])) {
					$link = '<a href="' . wp_get_attachment_url($pub['pdf_url']) . '">paper</a>';
					array_push($links, $link);
				}
				if (!empty($pub['bibtex_url'])) {
					$link = '<a href="' . wp_get_attachment_url($pub['bibtex_url']) . '">BibTex</a>';
					array_push($links, $link);
				}
				if (!empty($pub['website_url'])) {
					$link = '<a href="' . $pub['website_url'] . '">website</a>';
					array_push($links, $link);
				}
				$links_str = '<p class="publication-links">' . implode(' | ', $links) . '</p>';
			}

			$header = '<span class="publication-title publication'.$pub['id'].'">' . $pub['title'] . '</span>';
			$body   = '<p class="publication-authors">' . $pub['authors'] . '</p>';
			$body  .= '<p class="publication-conference">' . $pub['conference'] . '</p>';

			$pubout = $header . $body . ((count($links) > 0) ? $links_str : '');

			$output .= '<li>' . $pubout . '</li>';

		}

		// Wrap output in list tags and a div for good measure
		if (strtolower($options['numbered']) == 'true') {
			$output = '<ol>' . $output . '</ol>';
		} else {
			$output = '<ul>' . $output . '</ul>';
		}

		$output = '<div class="wpap">' . $output . '</div>';

		return $output;

	}

	function wpap_shortcode($atts) {
		global $wpap_options;

		$options = shortcode_atts($wpap_options, $atts);

		// call the function that does all the work
		return wpap_get_pubs_formatted($options);
	}

	/* Function to call in template to get array of publications.
	 */
	function wpap_get_publications ($options = array()) {
		global $wpap_options;

		$all_options = wpap_array_merge($wpap_options, $options);

		return wpap_get_pubs_array($all_options);
	}

	/* Function to call in template to get formatted publications.
	 */
	function wpap_get_publications_formatted ($options = array()) {
		global $wpap_options;

		$all_options = wpap_array_merge($wpap_options, $options);

		return wpap_get_pubs_formatted($all_options);
	}

?>