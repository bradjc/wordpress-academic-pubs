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

    wpap_display_publications($options)
    wpap_display_publications_formatted($options)


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

	Pass in as array.

