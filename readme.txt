=== WP Academic Publications ===
Contributors: bman12
Donate link:
Tags: publications, academic
Requires at least: 3.5
Tested up to: 3.7.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Let's you add academic publications to your Wordpress admin page which can then be displayed on your site.

== Description ==

Let's you add academic publications, such as those listed in journals or conferences, to your Wordpress admin page. These can then be displayed on your site using a shortcode or php function call.

== Installation ==

1. Upload `wpacademicpubs` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use `[academicpubs]` as a shortcode or `wpap_get_publications()` or `wpap_get_publications_formated()` in a template.

== Frequently asked questions ==

= How do I use the shortcode? =

Add [academicpubs] in a post. This can have options:

`
category: comma separated list of publication category slugs to display.
numbered: if true, publications will be displayed with an ordered list.
limit: total number of publications to display.
reverse: display in chronological order (default is reverse chronological).
show_links: if true, show links to the paper pdf and bibtex file.
page_num: useful for paging, tell wordpress to return the publications that would be on this page.
num_per_page: number of pubs to show on a 'page', needed for the paging functionality.
`

For example:

`[academicpubs category=selected,science numbered=true limit=5 reverse=true show_links=false]`

= What if I want the publications somewhere else? =

You can use one of the two php functions anywhere in a template.

`
/* Returns a list of publications. Each publication contains key,val pairs.
 *
 * $options: array('option' => 'value')
 *
 * Returns: [['id':1, 'title':'Paper Name', 'pdf_url':'http://a.com'],
 *           [another pub...]]
 *          fields are: id, title, authors, conference, pdf_url, bibtex_url,
 *                      slides_ppt, website_url
 */
wpap_display_publications($options);

/* Returns html around each publication.
 */
wpap_display_publications_formatted($options);
`

For example:
`
<?php
$opts = array('category'   => 'selected,science',
              'reverse'    => 'true',
              'show_links' => 'false');
$pubs = wpap_display_publications($opts);
foreach ($pubs as $pub) {
    echo '<p>' . $pub['title'] . '</p>';
}
?>
`

or

`
<?php
$opts = array('numbered'   => 'true',
              'limit'      => 10);
echo wpap_display_publications_formatted($opts);
?>
`

= What does the output look like? =

`
<div class="wpap">
    <ul>
        <li>
            <span class="publication-title publication1">Pub1 Title</span>
            <p class="publication-authors">Tom Smith and Eva Newn</p>
            <p class="publication-conference">Conference 1</p>
            <p class="publication-links"><a href="paper.pdf">paper</a> | <a href="paper.bib">BibTex</a> | <a href="slides.pptx">slides (ppt)</a> | <a href="http://website.com">website</a></p>
        </li>
        <li>
            <span class="publication-title publication531">Pub2 Title</span>
            <p class="publication-authors">Tom Cruise</p>
            <p class="publication-conference">COOKIE '13</p>
            <p class="publication-links"><a href="paper.pdf">paper</a> | <a href="paper.bib">BibTex</a></p>
        </li>
    </ul>
</div>
`

= What is a starter for some CSS styling? =

`
.wpap .publication-title {
    font-size: 110%;
    font-weight: bold;
}
.wpap p {
    margin: 0;
    padding: 0;
}
.wpap ul {
    list-style: none;
    margin: 0;
}
.wpap li {
    margin-bottom: 15px;
}
`

== Screenshots ==



== Changelog ==

1.1: Added slides and website.
     Better support for translation.

1.0: Initial release

== Upgrade notice ==




