<?php


	//////////////////////////////////////////////
	//
	// ADMIN Section Functions
	//
	//////////////////////////////////////////////

	// Create basic outline for publications type
	function wpap_create_publication() {

		$labels = array(
			'name'               => _x('Publications', 'Publication General Name', 'wpap'),
			'singular_name'      => _x('Publication Item', 'Publication Singular Name', 'wpap'),
			'add_new'            => _x('Add New', 'Add New Publication Name', 'wpap'),
			'all_items'          => __('All Publications', 'wpap'),
			'add_new_item'       => __('Add New Publication', 'wpap'),
			'edit_item'          => __('Edit Publication', 'wpap'),
			'new_item'           => __('New Publication', 'wpap'),
			'view_item'          => __('View Publication', 'wpap'),
			'search_items'       => __('Search Publications', 'wpap'),
			'not_found'          => __('Nothing found', 'wpap'),
			'not_found_in_trash' => __('Nothing found in Trash', 'wpap'),
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
				'label'             => __('Publication Categories', 'wpap'),
				'show_in_nav_menus' => false,
				'rewrite'           => true));
		register_taxonomy_for_object_type('publication-category', 'publication');

		flush_rewrite_rules();

	}

	// add table column in edit page
	function wpap_show_publication_column ($columns) {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => 'Title',
			'publication-category' => __('Publication Categories', 'wpap'));
		return $columns;
	}

	function wpap_publication_custom_columns ($column) {
		global $post;

		switch ($column) {
			case 'publication-category':
				echo get_the_term_list($post->ID, 'publication-category', '', ', ','');
				break;
		}
	}

	// Setup the Publications edit page
	$publication_meta_boxes = array(
		array(
			'title' => __('Authors', 'wpap'),
			'name'  => 'wpap_publication-option-authors',
			'type'  => 'inputtext',
			'extra' => __('List of authors on the paper.', 'wpap')),
		array(
			'title' => __('Conference', 'wpap'),
			'name'  => 'wpap_publication-option-conference',
			'type'  => 'inputtext',
			'extra' => __('Conference, year, and description.', 'wpap')),
		array(
			'title' => __('Paper PDF', 'wpap'),
			'name'  => 'wpap_publication-option-paperpdf',
			'type'  => 'upload',
			'extra' => __('The PDF of the paper.', 'wpap')),
		array(
			'title' => __('BibTex', 'wpap'),
			'name'  => 'wpap_publication-option-bibtex',
			'type'  => 'upload',
			'extra' => __('A .bib file containing the BibTex information.', 'wpap')),
		array(
			'title' => __('Slides (Powerpoint)', 'wpap'),
			'name'  => 'wpap_publication-option-slidesppt',
			'type'  => 'upload',
			'extra' => __('The powerpoint version of the slides.', 'wpap')),
		array(
			'title' => __('Website', 'wpap'),
			'name'  => 'wpap_publication-option-website',
			'type'  => 'inputtext',
			'extra' => __('A URL for the project/paper website.', 'wpap'))

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
		'num_per_page' => -1,
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

			$pub['id']          = $pubs_q->post->ID;
			$pub['title']       = get_the_title();
			$pub['authors']     = get_post_meta($pub['id'], 'wpap_publication-option-authors', true);
			$pub['conference']  = get_post_meta($pub['id'], 'wpap_publication-option-conference', true);
			$pub['pdf_url']     = get_post_meta($pub['id'], 'wpap_publication-option-paperpdf', true);
			$pub['bibtex_url']  = get_post_meta($pub['id'], 'wpap_publication-option-bibtex', true);
			$pub['slides_ppt']  = get_post_meta($pub['id'], 'wpap_publication-option-slidesppt', true);
			$pub['website_url'] = get_post_meta($pub['id'], 'wpap_publication-option-website', true);

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
					$link = '<a href="' . wp_get_attachment_url($pub['pdf_url']) . '">' . __('paper', 'wpap') . '</a>';
					array_push($links, $link);
				}
				if (!empty($pub['bibtex_url'])) {
					$link = '<a href="' . wp_get_attachment_url($pub['bibtex_url']) . '">' . __('BibTex', 'wpap') . '</a>';
					array_push($links, $link);
				}
				if (!empty($pub['slides_ppt'])) {
					$link = '<a href="' . wp_get_attachment_url($pub['slides_ppt']) . '">' . __('slides (ppt)', 'wpap') . '</a>';
					array_push($links, $link);
				}
				if (!empty($pub['website_url'])) {
					$link = '<a href="' . $pub['website_url'] . '">' . __('website', 'wpap') . '</a>';
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