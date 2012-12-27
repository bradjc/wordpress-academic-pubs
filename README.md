wordpress-academic-pubs
=======================

Plugin for wordpress that adds support for creating a list of academic publications.


Admin
-----

WPAP adds a section to the wordpress admin panel named "Publications". Here you
can add publications and publication categories. Publications are ordered by the
wordpress "published date" so the order you create the publications doesn't
matter.



Display
-------

### Shortcode

WPAP supports shortcodes. To use, just add:

    [academicpubs]

to your posts.


### PHP Function

To display publications in a template, you can use two functions:

	/* Returns a list of publications. Each publication contains key,val pairs.
	 *
	 * $options: array('option' => 'value')
	 *
	 * Returns: [['id':1, 'title':'Paper Name', 'pdf_url':'http://a.com'], 
	 *           [another pub...]]
	 *          fields are: id, title, authors, conference, pdf_url, bibtex_url
	 */
    wpap_display_publications($options);

    /* Returns html around each publication.
     */
    wpap_display_publications_formatted($options);


Options
-------

WPAP supports some options:

~~~~~~~
category   => '',
numbered   => 'false',
limit      => -1,
reverse    => 'false',
show_links => 'true',
~~~~~~~


### With shortcodes

    [academicpubs category=selected,science numbered=true limit=5 reverse=true show_links=false]

### With PHP functions
    
	<?php
	$opts = array('category'   => 'selected,science',
	              'reverse'    => 'true',
	              'show_links' => 'false');
	$pubs = wpap_display_publications($opts);
	foreach ($pubs as $pub) {
		echo '<p>' . $pub['title'] . '</p>';
	}
	?>

Or the easy way:

	<?php
	$opts = array('numbered'   => 'true',
	              'limit'      => 10);
	echo wpap_display_publications_formatted($opts);
	?>


Example Output
--------------

	<div class="wpap">
		<ul>
			<li>
				<span class="publication-title publication1">Pub1 Title</span>
				<p class="publication-authors">Tom Smith and Eva Newn</p>
				<p class="publication-conference">Conference 1</p>
				<p class="publication-links"><a href="paper.pdf">paper</a> | <a href="paper.bib">BibTex</a></p>
			</li>
			<li>
				<span class="publication-title publication531">Pub2 Title</span>
				<p class="publication-authors">Tom Cruise</p>
				<p class="publication-conference">COOKIE '13</p>
				<p class="publication-links"><a href="paper.pdf">paper</a> | <a href="paper.bib">BibTex</a></p>
			</li>
		</ul>
	</div>


