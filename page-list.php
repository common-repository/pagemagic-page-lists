<?php
/*
Plugin Name: PageMagic - Page Lists
Plugin URI: https://pagemagic.dev/pagelistsplugin
Description: Shortcodes to create lists and trees of site pages
Version: 1.0
Author: PageMagic
Author URI: https://pagemagic.dev/
License: GPLv3
*/

define('PAGE_MAGIC_PLUGIN_VERSION', '1.0');

if ( !function_exists('pagemagic_unqprfx_add_stylesheet') ) {
	function pagemagic_unqprfx_add_stylesheet() {
		wp_enqueue_style( 'page-list-style', plugins_url( '/css/page-list.css', __FILE__ ), false, PAGE_MAGIC_PLUGIN_VERSION, 'all' );
	}
	add_action('wp_enqueue_scripts', 'pagemagic_unqprfx_add_stylesheet');
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function pagemagic_add_post_meta_boxes() {
	add_meta_box(
		'pagemagic-excerpt',      // Unique ID
		'Page Magic Excerpt',    // Title
		'pagemagic_excerpt_meta_box',   // Callback function
		'page'  // Admin page (or post type)
	);
}
add_action('add_meta_boxes', 'pagemagic_add_post_meta_boxes');
  
/* Display the post meta box. */
function pagemagic_excerpt_meta_box( $post ) { ?>
	<?php wp_nonce_field( basename( __FILE__ ), 'pagemagic_excerpt_nonce' ); ?>
  
	<p class="pagemagic-excerpt-wrapper">
		<input class="widefat" type="text" name="pagemagic-excerpt" id="pagemagic-excerpt" value="<?php echo esc_attr( get_post_meta( $post->ID, 'pagemagic_excerpt', true ) ); ?>" size="30" />
		<sub>Limit 250 characters</sub>
	</p>
<?php }
  
/* Save the meta box’s post metadata. */
function pagemagic_save_excerpt_meta( $post_id, $post ) {
	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['pagemagic_excerpt_nonce'] ) || !wp_verify_nonce( $_POST['pagemagic_excerpt_nonce'], basename( __FILE__ ) ) )
	  	return $post_id;
  
	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );
  
	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
	  	return $post_id;
  
	/* Get the posted data and sanitize it for use as an HTML class. */
	// $new_meta_value = ( isset( $_POST['pagemagic-excerpt'] ) ? sanitize_html_class( $_POST['pagemagic-excerpt'] ) : ’ );
	// $new_meta_value = $_POST['pagemagic-excerpt'];
	$new_meta_value = sanitize_text_field( $_POST['pagemagic-excerpt'] );
  
	/* Get the meta key. */
	$meta_key = 'pagemagic_excerpt';
  
	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );
  
	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && ’ == $meta_value )
	  	add_post_meta( $post_id, $meta_key, $new_meta_value, true );
  
	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
	  	update_post_meta( $post_id, $meta_key, $new_meta_value );
  
	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( ’ == $new_meta_value && $meta_value )
	  	delete_post_meta( $post_id, $meta_key, $meta_value );
}
add_action( 'save_post', 'pagemagic_save_excerpt_meta', 10, 2 );



$pagemagic_unq_settings = array(
	'version' => PAGE_MAGIC_PLUGIN_VERSION,
	'powered_by' => '<!-- PAGE MAGIC PLUGIN -->',
	'pagemagic_defaults' => array(
		'depth' => '0',
		'child_of' => '0',
		'exclude' => '0',
		'exclude_tree' => '',
		'include' => '0',
		'title_li' => '',
		'number' => '',
		'offset' => '',
		'meta_key' => '',
		'meta_value' => '',
		'show_date' => '',
		'date_format' => get_option('date_format'),
		'authors' => '',
		'sort_column' => 'menu_order, post_title',
		'sort_order' => 'ASC',
		'link_before' => '',
		'link_after' => '',
		'post_type' => 'page',
		'post_status' => 'publish',
		'class' => ''
	)
);

// LIST ALL SITE PAGES
if ( !function_exists('pagemagic_unqprfx_shortcode') ) {
	function pagemagic_unqprfx_shortcode( $atts ) {
		global $post, $pagemagic_unq_settings;
		$return = '';
		extract( shortcode_atts( $pagemagic_unq_settings['pagemagic_defaults'], $atts ) );

		$pagemagic_args = array(
			'depth'        => $depth,
			'child_of'     => pagemagic_unqprfx_norm_params($child_of),
			'exclude'      => pagemagic_unqprfx_norm_params($exclude),
			'exclude_tree' => pagemagic_unqprfx_norm_params($exclude_tree),
			'include'      => pagemagic_unqprfx_norm_params($include),
			'title_li'     => $title_li,
			'number'       => $number,
			'offset'       => $offset,
			'meta_key'     => $meta_key,
			'meta_value'   => $meta_value,
			'show_date'    => $show_date,
			'date_format'  => $date_format,
			'echo'         => 0,
			'authors'      => $authors,
			'sort_column'  => $sort_column,
			'sort_order'   => $sort_order,
			'link_before'  => $link_before,
			'link_after'   => $link_after,
			'post_type'    => $post_type,
			'post_status'  => $post_status
		);
		$list_pages = wp_list_pages( $pagemagic_args );

		$return .= $pagemagic_unq_settings['powered_by'];
		if ($list_pages) {
			$return .= '<ul class="pagemagic '.$class.'">'."\n".$list_pages."\n".'</ul>';
		} else {
			$return .= '<p class="no-results">No results.</p>';
		}
		return $return;
	}
	add_shortcode( 'allpages', 'pagemagic_unqprfx_shortcode' );
	add_shortcode( 'all_pages', 'pagemagic_unqprfx_shortcode' );
	add_shortcode( 'all-pages', 'pagemagic_unqprfx_shortcode' ); // not good (Shortcode names should be all lowercase and use all letters, but numbers and underscores (not dashes!) should work fine too.)
	add_shortcode( 'pagemagic_list_all', 'pagemagic_unqprfx_shortcode' );
	add_shortcode( 'pagemagic_listall', 'pagemagic_unqprfx_shortcode' );
	add_shortcode( 'pagemagic_sitemap', 'pagemagic_unqprfx_shortcode' );
	add_shortcode( 'pagemagic_all', 'pagemagic_unqprfx_shortcode' );
	add_shortcode( 'pagemagic_allpages', 'pagemagic_unqprfx_shortcode' );
	add_shortcode( 'pagemagic_all_pages', 'pagemagic_unqprfx_shortcode' );
	add_shortcode( 'sitemap', 'pagemagic_unqprfx_shortcode' );
}


// LIST *THIS* PAGE'S SUBPAGES
if ( !function_exists('subpages_pagemagic_pl_shortcode') ) {
	function subpages_pagemagic_pl_shortcode( $atts ) {
		global $post, $pagemagic_unq_settings;
		$return = '';
		extract( shortcode_atts( $pagemagic_unq_settings['pagemagic_defaults'], $atts ) );

		$pagemagic_args = array(
			'depth'        => $depth,
			'child_of'     => $post->ID,
			'exclude'      => pagemagic_unqprfx_norm_params($exclude),
			'exclude_tree' => pagemagic_unqprfx_norm_params($exclude_tree),
			'include'      => pagemagic_unqprfx_norm_params($include),
			'title_li'     => $title_li,
			'number'       => $number,
			'offset'       => $offset,
			'meta_key'     => $meta_key,
			'meta_value'   => $meta_value,
			'show_date'    => $show_date,
			'date_format'  => $date_format,
			'echo'         => 0,
			'authors'      => $authors,
			'sort_column'  => $sort_column,
			'sort_order'   => $sort_order,
			'link_before'  => $link_before,
			'link_after'   => $link_after,
			'post_type'    => $post_type,
			'post_status'  => $post_status
		);
		$list_pages = wp_list_pages( $pagemagic_args );

		$return .= $pagemagic_unq_settings['powered_by'];
		if ($list_pages) {
			$return .= '<ul class="pagemagic subpages-page-list '.$class.'">'."\n".$list_pages."\n".'</ul>';
		} else {
			$return .= '<p class="no-results">No results.</p>';
		}
		return $return;
	}
	add_shortcode( 'this_subpages', 'subpages_pagemagic_pl_shortcode' );
	add_shortcode( 'this_sub_pages', 'subpages_pagemagic_pl_shortcode' );
	add_shortcode( 'thissubpages', 'subpages_pagemagic_pl_shortcode' );
	add_shortcode( 'pagemagic_subpages', 'subpages_pagemagic_pl_shortcode' );
	add_shortcode( 'pagemagic_sub_pages', 'subpages_pagemagic_pl_shortcode' );
	add_shortcode( 'pagemagic_thissubpages', 'subpages_pagemagic_pl_shortcode' );
}


// LIST *THIS* PAGE'S SIBLINGS
if ( !function_exists('siblings_pagemagic_pl_shortcode') ) {
	function siblings_pagemagic_pl_shortcode( $atts ) {
		global $post, $pagemagic_unq_settings;
		$return = '';
		extract( shortcode_atts( $pagemagic_unq_settings['pagemagic_defaults'], $atts ) );

		if ( $exclude == 'current' || $exclude == 'this' ) {
			$exclude = $post->ID;
		}

		$pagemagic_args = array(
			'depth'        => $depth,
			'child_of'     => $post->post_parent,
			'exclude'      => pagemagic_unqprfx_norm_params($exclude),
			'exclude_tree' => pagemagic_unqprfx_norm_params($exclude_tree),
			'include'      => pagemagic_unqprfx_norm_params($include),
			'title_li'     => $title_li,
			'number'       => $number,
			'offset'       => $offset,
			'meta_key'     => $meta_key,
			'meta_value'   => $meta_value,
			'show_date'    => $show_date,
			'date_format'  => $date_format,
			'echo'         => 0,
			'authors'      => $authors,
			'sort_column'  => $sort_column,
			'sort_order'   => $sort_order,
			'link_before'  => $link_before,
			'link_after'   => $link_after,
			'post_type'    => $post_type,
			'post_status'  => $post_status
		);
		$list_pages = wp_list_pages( $pagemagic_args );

		$return .= $pagemagic_unq_settings['powered_by'];
		if ($list_pages) {
			$return .= '<ul class="pagemagic siblings-page-list '.$class.'">'."\n".$list_pages."\n".'</ul>';
		} else {
			$return .= '<p class="no-results">No results.</p>';
		}
		return $return;
	}
	add_shortcode( 'this_siblings', 'siblings_pagemagic_pl_shortcode' );
	add_shortcode( 'thissiblings', 'siblings_pagemagic_pl_shortcode' );
	add_shortcode( 'pagemagic_this_siblings', 'siblings_pagemagic_pl_shortcode' );
	add_shortcode( 'pagemagic_thissiblings', 'siblings_pagemagic_pl_shortcode' );
	add_shortcode( 'pagemagic_siblings', 'siblings_pagemagic_pl_shortcode' );
}


// LIST ALL PAGES WITH THEIR DATA
if ( !function_exists('pagemagic_unqprfx_data_shortcode') ) {
	function pagemagic_unqprfx_data_shortcode( $atts ) {
		global $post, $pagemagic_unq_settings;
		$return = '';
		extract( shortcode_atts( array(
			'show_image' => 1,
			'show_first_image' => 0,
			'show_title' => 1,
			'show_content' => 0,
			'show_pagemagic_excerpt' => 1,
			'more_tag' => 1,
			'limit_content' => 250,
			'image_width' => '150',
			'image_height' => '150',
			'child_of' => '',
			'sort_order' => 'ASC',
			'sort_column' => 'menu_order, post_title',
			'hierarchical' => 1,
			'exclude' => '0',
			'include' => '0',
			'meta_key' => '',
			'meta_value' => '',
			'authors' => '',
			'parent' => -1,
			'exclude_tree' => '',
			'number' => '',
			'offset' => 0,
			'post_type' => 'page',
			'post_status' => 'publish',
			'class' => '',
			'strip_tags' => 1,
			'strip_shortcodes' => 1,
			'show_child_count' => 0,
			'child_count_template' => 'Subpages: %child_count%',
			'show_meta_key' => '',
			'meta_template' => '%meta%'
		), $atts ) );

		if ( $child_of == '' ) { // show subpages if child_of is empty
			$child_of = $post->ID;
		}

		$pagemagic_data_args = array(
			'show_image' => $show_image,
			'show_first_image' => $show_first_image,
			'show_title' => $show_title,
			'show_content' => $show_content,
			'show_pagemagic_excerpt' => $show_pagemagic_excerpt,
			'more_tag' => $more_tag,
			'limit_content' => $limit_content,
			'image_width' => $image_width,
			'image_height' => $image_height,
			'sort_order' => $sort_order,
			'sort_column' => $sort_column,
			'hierarchical' => $hierarchical,
			'exclude' => pagemagic_unqprfx_norm_params($exclude),
			'include' => pagemagic_unqprfx_norm_params($include),
			'meta_key' => $meta_key,
			'meta_value' => $meta_value,
			'authors' => $authors,
			'child_of' => pagemagic_unqprfx_norm_params($child_of),
			'parent' => pagemagic_unqprfx_norm_params($parent),
			'exclude_tree' => pagemagic_unqprfx_norm_params($exclude_tree),
			'number' => '', // $number - own counter
			'offset' => 0, // $offset - own offset
			'post_type' => $post_type,
			'post_status' => $post_status,
			'class' => $class,
			'strip_tags' => $strip_tags,
			'strip_shortcodes' => $strip_shortcodes,
			'show_child_count' => $show_child_count,
			'child_count_template' => $child_count_template,
			'show_meta_key' => $show_meta_key,
			'meta_template' => $meta_template
		);
		$pagemagic_data_args_all = array(
			'show_image' => $show_image,
			'show_first_image' => $show_first_image,
			'show_title' => $show_title,
			'show_content' => $show_content,
			'show_pagemagic_excerpt' => $show_pagemagic_excerpt,
			'more_tag' => $more_tag,
			'limit_content' => $limit_content,
			'image_width' => $image_width,
			'image_height' => $image_height,
			'sort_order' => $sort_order,
			'sort_column' => $sort_column,
			'hierarchical' => $hierarchical,
			'exclude' => pagemagic_unqprfx_norm_params($exclude),
			'include' => pagemagic_unqprfx_norm_params($include),
			'meta_key' => $meta_key,
			'meta_value' => $meta_value,
			'authors' => $authors,
			'child_of' => 0, // for showing all pages
			'parent' => pagemagic_unqprfx_norm_params($parent),
			'exclude_tree' => pagemagic_unqprfx_norm_params($exclude_tree),
			'number' => '', // $number - own counter
			'offset' => 0, // $offset - own offset
			'post_type' => $post_type,
			'post_status' => $post_status,
			'class' => $class,
			'strip_tags' => $strip_tags,
			'strip_shortcodes' => $strip_shortcodes,
			'show_child_count' => $show_child_count,
			'child_count_template' => $child_count_template,
			'show_meta_key' => $show_meta_key,
			'meta_template' => $meta_template
		);
		$list_pages = get_pages( $pagemagic_data_args );
		if ( count( $list_pages ) == 0 ) { // if there are no subpages
			$list_pages = get_pages( $pagemagic_data_args_all ); // we are showing all pages
		}
		$list_pages_html = '';
		$count = 0;
		$offset_count = 0;
		if ( $list_pages !== false && count( $list_pages ) > 0 ) {
			foreach($list_pages as $page){
				$count++;
				$offset_count++;
				if ( !empty( $offset ) && is_numeric( $offset ) && $offset_count <= $offset ) {
					$count = 0; // number counter to zero if offset is not finished
				}
				if ( ( !empty( $offset ) && is_numeric( $offset ) && $offset_count > $offset ) || ( empty( $offset ) ) || ( !empty( $offset ) && !is_numeric( $offset ) ) ) {
					if ( ( !empty( $number ) && is_numeric( $number ) && $count <= $number ) || ( empty( $number ) ) || ( !empty( $number ) && !is_numeric( $number ) ) ) {
						$link = get_permalink( $page->ID );
						$list_pages_html .= '<div class="pagemagic-data-item">';

						if ( $show_image == 1 ) {
							if ( get_the_post_thumbnail( $page->ID ) ) { // if there is a featured image
								$list_pages_html .= '<div class="pagemagic-data-image"><a href="'.$link.'" title="'.esc_attr($page->post_title).'">';
								//$list_pages_html .= get_the_post_thumbnail($page->ID, array($image_width,$image_height)); // doesn't work good with image size

								$image = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ), array($image_width,$image_height) ); // get featured img; 'large'
								$img_url = $image[0]; // get the src of the featured image
								$list_pages_html .= '<img src="'.$img_url.'" width="'.$image_width.'" alt="'.esc_attr($page->post_title).'" />'; // not using height="'.$image_height.'" because images could be not square shaped and they will be stretched

								$list_pages_html .= '</a></div> ';
							} else {
								if ( $show_first_image == 1 ) {
									$img_scr = pagemagic_unqprfx_get_first_image( $page->post_content );
									if ( !empty( $img_scr ) ) {
										$list_pages_html .= '<div class="pagemagic-data-image"><a href="'.$link.'" title="'.esc_attr($page->post_title).'">';
										$list_pages_html .= '<img src="'.$img_scr.'" width="'.$image_width.'" alt="'.esc_attr($page->post_title).'" />'; // not using height="'.$image_height.'" because images could be not square shaped and they will be stretched
										$list_pages_html .= '</a></div> ';
									}
								}
							}
						}


						if ( $show_title == 1 ) {
							$list_pages_html .= '<h3 class="pagemagic-data-title"><a href="'.$link.'" title="'.esc_attr($page->post_title).'">'.$page->post_title.'</a></h3>';
						}


						if ( $show_pagemagic_excerpt == 1 ) {
							if ( get_post_meta( $page->ID, 'pagemagic_excerpt', true ) ) {
								$pagemagic_meta = get_post_meta( $page->ID, 'pagemagic_excerpt', true);

								$pagemagic_snippet = pagemagic_unqprfx_parse_content( $pagemagic_meta, $limit_content );
								$pagemagic_snippet = do_shortcode( $pagemagic_snippet );

								$list_pages_html .= '<div class="pagemagic-excerpt pagemagic-data-item-content">'.$pagemagic_snippet.'</div>';
							}
						}


						if ( $show_content == 1 ) {
								// $content = apply_filters('the_content', $page->post_content);
								// $content = str_replace(']]>', ']]&gt;', $content); // both used in default the_content() function

								if ( !empty( $page->post_excerpt ) ) {
									$text_content = $page->post_excerpt;
								} else {
									$text_content = $page->post_content;
								}

								if ( post_password_required($page) ) {
									$content = '<!-- password protected -->';
								} else {
									$content = pagemagic_unqprfx_parse_content( $text_content, $limit_content, $strip_tags, $strip_shortcodes, $more_tag );
									$content = do_shortcode( $content );

									if ( $show_title == 0 ) { // make content as a link if there is no title
										$content = '<a href="'.$link.'">'.$content.'</a>';
									}
								}

								$list_pages_html .= '<div class="pagemagic-data-item-content">'.$content.'</div>';

							}

						
						if ( $show_child_count == 1 ) {
							$count_subpages = count(get_pages("child_of=".$page->ID));
							if ( $count_subpages > 0 ) { // hide empty
								$child_count_pos = strpos($child_count_template, '%child_count%'); // check if we have %child_count% marker in template
								if ($child_count_pos === false) { // %child_count% not found in template
									$child_count_template_html = $child_count_template.' '.$count_subpages;
									$list_pages_html .= '<div class="pagemagic-data-child-count">'.$child_count_template_html.'</div>';
								} else { // %child_count% found in template
									$child_count_template_html = str_replace('%child_count%', $count_subpages, $child_count_template);
									$list_pages_html .= '<div class="pagemagic-data-child-count">'.$child_count_template_html.'</div>';
								}
							}
						}
						if ( $show_meta_key != '' ) {
							$post_meta = do_shortcode(get_post_meta($page->ID, $show_meta_key, true));
							if ( !empty($post_meta) ) { // hide empty
								$meta_pos = strpos($meta_template, '%meta%'); // check if we have %meta% marker in template
								if ($meta_pos === false) { // %meta% not found in template
									$meta_template_html = $meta_template.' '.$post_meta;
									$list_pages_html .= '<div class="pagemagic-data-meta">'.$meta_template_html.'</div>';
								} else { // %meta% found in template
									$meta_template_html = str_replace('%meta%', $post_meta, $meta_template);
									$list_pages_html .= '<div class="pagemagic-data-meta">'.$meta_template_html.'</div>';
								}
							}
						}
						$list_pages_html .= '</div>'."\n";
					}
				}
			}
		}
		$return .= $pagemagic_unq_settings['powered_by'];
		if ($list_pages_html) {
			$return .= '<div class="pagemagic pagemagic-data '.$class.'">'."\n".$list_pages_html."\n".'</div>';
		} else {
			$return .= '<p class="no-results">No results.</p>'; // this line will not work, because we show all pages if there are no pages to show
		}
		return $return;
	}
	add_shortcode( 'pagelist_image_excerpt', 'pagemagic_unqprfx_data_shortcode' );
	add_shortcode( 'pagelistimageexcerpt', 'pagemagic_unqprfx_data_shortcode' );
	add_shortcode( 'pagemagic_image_excerpt', 'pagemagic_unqprfx_data_shortcode' );
	add_shortcode( 'pagemagicimageexcerpt', 'pagemagic_unqprfx_data_shortcode' );
}


if ( !function_exists('pagemagic_unqprfx_norm_params') ) {
	function pagemagic_unqprfx_norm_params( $str ) {
		global $post;
		$new_str = $str;
		$new_str = str_replace('this', $post->ID, $new_str); // exclude this page
		$new_str = str_replace('current', $post->ID, $new_str); // exclude current page
		$new_str = str_replace('curent', $post->ID, $new_str); // exclude curent page with mistake
		$new_str = str_replace('parent', $post->post_parent, $new_str); // exclude parent page
		return $new_str;
	}
}


if ( !function_exists('pagemagic_unqprfx_parse_content') ) {
	function pagemagic_unqprfx_parse_content($content, $limit_content = 250, $strip_tags = 1, $strip_shortcodes = 1, $more_tag = 1) {

		$more_tag_found = 0;

		if ( $more_tag ) { // "more_tag" have higher priority than "limit_content"
			if ( preg_match('/<!--more(.*?)?-->/', $content, $matches) ) {
				$more_tag_found = 1;
				$more_tag = $matches[0];
				$content_temp = explode($matches[0], $content);
				$content_temp = $content_temp[0];
				$content_before_more_tag_length = strlen($content_temp);
				$content = substr_replace($content, '###more###', $content_before_more_tag_length, 0);
			}
		}

		// replace php and comments tags so they do not get stripped
		//$content = preg_replace("@<\?@", "#?#", $content);
		//$content = preg_replace("@<!--@", "#!--#", $content); // save html comments
		// strip tags normally
		//$content = strip_tags($content);
		if ( $strip_tags ) {
			$content = str_replace('</', ' </', $content); // <p>line1</p><p>line2</p> - adding space between lines
			$content = strip_tags($content); // ,'<p>'
		}
		// return php and comments tags to their origial form
		//$content = preg_replace("@#\?#@", "<?", $content);
		//$content = preg_replace("@#!--#@", "<!--", $content);

		if ( $strip_shortcodes ) {
			$content = strip_shortcodes( $content );
		}

		if ( $more_tag && $more_tag_found ) { // "more_tag" have higher priority than "limit_content"
			$fake_more_pos = mb_strpos($content, '###more###', 0, 'UTF-8');
			if ( $fake_more_pos === false ) {
				// substring not found in string and this is strange :)
			} else {
				$content = mb_substr($content, 0, $fake_more_pos, 'UTF-8');
			}
		} else {
			if ( strlen($content) > $limit_content ) { // limiting content
				$pos = strpos($content, ' ', $limit_content); // find first space position
				if ($pos !== false) {
					$first_space_pos = $pos;
				} else {
					$first_space_pos = $limit_content;
				}
				$content = mb_substr($content, 0, $first_space_pos, 'UTF-8') . '...';
			}
		}

		$output = force_balance_tags($content);
		return $output;
	}
}


if ( !function_exists('pagemagic_unqprfx_get_first_image') ) {
	function pagemagic_unqprfx_get_first_image( $content='' ) {
		$first_img = '';
		$matchCount = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
		if ( $matchCount !== 0 ) { // if we found first image
			$first_img = $matches[1][0];
		}
		return $first_img;
	}
}

if ( ! function_exists('pagemagic_unqprfx_plugin_meta') ) {
	function pagemagic_unqprfx_plugin_meta( $links, $file ) { // add links to plugin meta row
		if ( $file == plugin_basename( __FILE__ ) ) {
			$row_meta = array(
				'donate' => '<a href="https://pagemagic.dev/donate" target="_blank">' . __( 'Donate', 'pagemagic' ) . '</a>'
			);
			$links = array_merge( $links, $row_meta );
		}
		return (array) $links;
	}
	add_filter( 'plugin_row_meta', 'pagemagic_unqprfx_plugin_meta', 10, 2 );
}