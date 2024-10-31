=== PageMagic - Page Lists ===
Contributors: meaghanbass
Donate link: https://pagemagic.dev/donate
Tags: list pages, listpages, page tree, page nav, page navigation, pagemagic, page magic, page, sitemap, subpages, siblings
Requires at least: 3.0
Tested up to: 5.4
Stable tag: 1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Create visual hierarchies of site pages. Options to list all pages, subpages, current page siblings. Also able to show page featured image and custom excerpts.

== Description ==

Create visual hierarchies of site pages. Options to list all pages, subpages, current page siblings. Also able to show page featured image and custom excerpts.

= Quick Shortcodes =

* **[pagemagic_listall]** - hierarchical tree of all pages on site (useful to show sitemap of the site)
* **[pagemagic_subpages]** - hierarchical tree of subpages to the current page
* **[pagemagic_siblings]** - hierarchical tree of sibling pages to the current page
* **[pagemagic_image_excerpt]** - list of pages with featured image and with excerpt

= Example Parameters =

* `[pagemagic_listall child_of="4" depth="2" exclude="6,7,8"]`
* `[pagemagic_image_excerpt child_of="4" exclude="6,7,8" image_width="50" image_height="50"]`


== Other Notes ==

= Page List Parameters =

* **[pagemagic_listall]** - list of all pages as the hierarchical list
* **[pagemagic_subpages]** - list of subpages to the current page as the hierarchical list; Same as: `[pagemagic_listall child_of="current"]`
* **[pagemagic_siblings]** - list of sibling pages to the current page as the hierarchical list; Same as: `[pagemagic_listall child_of="parent"]`
* **depth** - how many levels in the hierarchy of pages are to be included in the list: `[pagemagic_listall depth="3"]`; by default depth is unlimited (depth="0"); Displays pages at any depth and arranges them in a flat list: `[pagemagic_listall depth="-1"]`
* **child_of** - displays the sub-pages of a single Page by ID: `[pagemagic_listall child_of="4"]`
* **exclude** - define a comma-separated list of Page IDs to be excluded from the list: `[pagemagic_listall exclude="6,7,8"]`; You may exclude current page: `[pagemagic_listall exclude="current"]`
* **exclude_tree** - define a comma-separated list of parent Page IDs and all its subpages to be excluded: `[pagemagic_listall exclude_tree="7,10"]`
* **include** - include a comma-separated list of Page IDs into the list: `[pagemagic_listall include="6,7,8"]`
* **title_li** - set the text and style of the Page list's heading: `[pagemagic_listall title_li="<h2>List of pages</h2>"]`; by default there is no title (title_li="")
* **authors** - only include pages authored by the authors in this comma-separated list of author IDs: `[pagemagic_listall authors="2,5"]`; by default all authors are included (authors="")
* **number** - sets the number of pages to display: `[pagemagic_listall number="10"]`; by default the number is unlimited (number="")
* **offset** - the number of pages to pass over (or displace) before collecting the set of pages: `[pagemagic_listall offset="5"]`; by default there is no offset (offset="")
* **post_type** - list associated with a certain hierarchical Post Type `[pagemagic_listall post_type="page"]`; by default: (post_type="page"); possible values: page, revision, Hierarchical Custom Post Types ('post' is not a Hierarchical Post Type)
* **post_status** - a comma-separated list of all post status types: `[pagemagic_listall post_status="private"]`; by default: (post_status="publish"); possible values: publish, private, draft
* **meta_key** and **meta_value** - only include the pages that have this Custom Field Key and this Custom Field Value: `[pagemagic_listall meta_key="metakey" meta_value="metaval"]`
* **show_date** - display creation or last modified date next to each Page: `[pagemagic_listall show_date="created"]`; possible values: created, modified, updated
* **date_format** - the format of the Page date set by the show_date parameter: `[pagemagic_listall date_format="l, F j, Y"]`; by default use the date format configured in your WordPress options
* **sort_column** - sort the list of pages by column: `[pagemagic_listall sort_column="menu_order"]`; by default: (sort_column="menu_order, post_title"); possible values: post_title, menu_order, post_date (sort by creation time), post_modified, ID, post_author, post_name (sort by page slug)
* **sort_order** - the sort order of the list of pages (either ascending or descending): `[pagemagic_listall sort_order="desc"]`; by default: (sort_order="asc"); possible values: asc, desc
* **link_before** - sets the text or html that precedes the link text inside link tag: `[pagemagic_listall link_before="<span>"]`; you may specify html tags only in the `HTML` tab in your Rich-text editor
* **link_after** - sets the text or html that follows the link text inside link tag: `[pagemagic_listall link_after="</span>"]`; you may specify html tags only in the `HTML` tab in your Rich-text editor
* **class** - the CSS class for list of pages: `[pagemagic_listall class="listclass"]`; by default the class is empty (class="")
* **columns** - for splitting list of pages into columns: `[pagemagic_listall class="pagemagic-cols-2"]`; available classes: pagemagic-cols-2, pagemagic-cols-3, pagemagic-cols-4, pagemagic-cols-5; works in all modern browsers and IE10+; columns are responsive and become 1 column at less than 768px

[More info] about parameters (https://developer.wordpress.org/reference/functions/get_pages/#parameters) for [pagemagic_listall].

= Parameters for Page List Excerpts =

* **[pagemagic_image_excerpt]** - by default shows list of subpages to current page; but if there is no subpages than all pages will be shown
* **show_image** - show or hide featured image `[pagemagic_image_excerpt show_image="0"]`; "show_image" have higher priority than "show_first_image"; by default: show_image="1"
* **show_first_image** - show or hide first image from content if there is no featured image `[pagemagic_image_excerpt show_first_image="1"]`; by default: show_first_image="0"
* **show_title** - show or hide title `[pagemagic_image_excerpt show_title="0"]`; by default: show_title="1"
* **show_content** - show or hide content `[pagemagic_image_excerpt show_content="0"]`; by default: show_content="1"
* **more_tag** - output all content before and after more tag: `[pagemagic_image_excerpt more_tag="0"]`; this parameter does not add "more-link" to the end of content, it just cut content before more-tag; "more_tag" parameter have higher priority than "limit_content"; by default the more_tag is enabled (more_tag="1") and showing only content before more tag
* **limit_content** - content is limited by "more-tag" if it is exist or by "limit_content" parameter `[pagemagic_image_excerpt limit_content="100"]`; by default: limit_content="250"
* **image_width** - width of the image `[pagemagic_image_excerpt image_width="80"]`; by default: image_width="50"
* **image_height** - height of the image `[pagemagic_image_excerpt image_height="80"]`; by default: image_height="50"
* **child_of** - displays the sub-pages of a single Page by ID: `[pagemagic_image_excerpt child_of="4"]`; by default it shows subpages to the current page
* **parent** - list those pages that have the provided single page only ID as parent: `[pagemagic_image_excerpt parent="4"]`; by default parent="-1" and depth is unlimited
* **sort_column** - sort the list of pages by column: `[pagemagic_image_excerpt sort_column="menu_order"]`; by default: (sort_column="menu_order, post_title"); possible values: post_title, menu_order, post_date (sort by creation time), post_modified, ID, post_author, post_name (sort by page slug)
* **sort_order** - the sort order of the list of pages (either ascending or descending): `[pagemagic_image_excerpt sort_order="desc"]`; by default: (sort_order="asc"); possible values: asc, desc;* **hierarchical** - display subpages below their parent page `[pagemagic_image_excerpt hierarchical="0"]`; by default: hierarchical="1"
* **hierarchical** - display subpages below their parent page `[pagemagic_image_excerpt hierarchical="0"]`; by default: hierarchical="1"
* **exclude** - define a comma-separated list of Page IDs to be excluded from the list: `[pagemagic_image_excerpt exclude="6,7,8"]`
* **exclude_tree** - define a comma-separated list of parent Page IDs and all its subpages to be excluded: `[pagemagic_image_excerpt exclude_tree="7,10"]`
* **include** - include a comma-separated list of Page IDs into the list: `[pagemagic_image_excerpt include="6,7,8"]`
* **meta_key** and **meta_value** - only include the pages that have this Custom Field Key and this Custom Field Value: `[pagemagic_image_excerpt meta_key="metakey" meta_value="metaval"]`
* **authors** - only include the pages written by the given author(s) `[pagemagic_image_excerpt authors="6,7,8"]`
* **number** - sets the number of pages to display: `[pagemagic_image_excerpt number="10"]`; by default the number is unlimited (number="")
* **offset** - the number of pages to pass over (or displace) before collecting the set of pages: `[pagemagic_image_excerpt offset="5"]`; by default there is no offset (offset="")
* **post_type** - list associated with a certain hierarchical Post Type `[pagemagic_image_excerpt post_type="page"]`; by default: (post_type="page"); possible values: page, revision, Hierarchical Custom Post Types ('post' is not a Hierarchical Post Type)
* **post_status** - a comma-separated list of all post status types: `[pagemagic_image_excerpt post_status="private"]`; by default: (post_status="publish"); possible values: publish, private, draft
* **class** - the CSS class for list of pages: `[pagemagic_image_excerpt class="listclass"]`; by default the class is empty (class="")
* **strip_tags** - strip tags or not: `[pagemagic_image_excerpt strip_tags="0"]`; by default the tags are stripped (strip_tags="1")
* **strip_shortcodes** - strip registered shortcodes or not: `[pagemagic_image_excerpt strip_shortcodes="0"]`; by default shortcodes are stripped (strip_shortcodes="1") and all registered shortcodes are removed
* **show_child_count** - show count of subpages: `[pagemagic_image_excerpt show_child_count="1"]`; by default the child_count is disabled (show_child_count="0"); If show_child_count="1", but count of subpages=0, than child count is not shown
* **child_count_template** - the template of child_count: `[pagemagic_image_excerpt show_child_count="1" child_count_template="Subpages: %child_count%"]`; by default child_count_template="Subpages: %child_count%"
* **show_meta_key** - show or hide meta key: `[pagemagic_image_excerpt show_meta_key="your_meta_key"]`; by default the show_meta_key is empty (show_meta_key=""); If show_meta_key is enabled, but meta_value is empty, than meta_key is not shown
* **meta_template** - the template of meta: `[pagemagic_image_excerpt show_meta_key="your_meta_key" meta_template="Meta: %meta%"]`; by default meta_template="%meta%"
* **columns** - for splitting list of pages into columns: `[pagemagic_image_excerpt class="pagemagic-cols-2"]`; available classes: pagemagic-cols-2, pagemagic-cols-3, pagemagic-cols-4, pagemagic-cols-5; works in all modern browsers and IE10+;  columns are responsive and become 1 column at less than 768px

[More info] about parameters (https://developer.wordpress.org/reference/functions/get_pages/#parameters) for [pagemagic_image_excerpt].

== Frequently Asked Questions ==

= What's the difference between [pagemagic_listall], [pagemagic_subpages] and [pagemagic_siblings]? =

Shortcodes [pagemagic_listall], [pagemagic_subpages] and [pagemagic_siblings] accept the same parameters. The only difference is that [pagemagic_subpages] and [pagemagic_siblings] do not accept `child_of` parameter, because [pagemagic_subpages] shows subpages to the current page and [pagemagic_siblings] shows subpages to the parent page.

= How do I create a sitemap.xml file? =
To create a sitemap.xml file you can use [XML-Sitemaps.com](https://www.xml-sitemaps.com/).

= What do I do if I need to change the plugin's code? =
When you changed the plugin's code you should also change the plugin's version to '100' (for example) to avoid updates, which could override and delete your code.

== Screenshots ==

1. Page Magic Custom Excerpt Meta Box
2. Page Magic [pagemagic_listall] Shortcode
3. Page Magic [pagemagic_listall] Preview
4. Page Magic [pagemagic_image_excerpt] Preview

== Changelog ==

= 1.0.0 =
* Initial release

== Installation ==

1. Install and activate the plugin on the Plugins page.
2. Add shortcodes to pages: `[pagemagic_listall]`, `[pagemagic_subpages]`, `[pagemagic_this_siblings]`, `[pagemagic_image_excerpt]`
3. Use the Page Magic Custom Excerpt meta box in the page builder to set a custom excerpt.