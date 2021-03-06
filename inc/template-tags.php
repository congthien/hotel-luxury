<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Hotel_Luxury
 */

if ( ! function_exists( 'hotel_luxury_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function hotel_luxury_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'hotel-luxury' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'hotel-luxury' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="blog-date"><i class="icon-time"></i>' . $posted_on . '</span><span class="blog-author"><i class="icon-user"></i> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'hotel_luxury_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function hotel_luxury_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'hotel-luxury' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'hotel-luxury' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'hotel-luxury' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'hotel-luxury' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'hotel-luxury' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'hotel-luxury' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;


function  hotel_luxury_get_taxonomy( $taxonomy) {
	$tags = array();
	$gallery_tags = get_terms( $taxonomy, array(
		'hide_empty' => false
	) );
	if ( ! empty( $gallery_tags ) && ! is_wp_error( $gallery_tags ) ){
		foreach ( $gallery_tags as $tag ) {
			$tags[$tag->term_id] = $tag->name;
		}
	}
	return $tags;
}


if ( ! function_exists( 'hotel_luxury_get_gallery_data' ) ) {
	function hotel_luxury_get_gallery_data( $page_id ) {
		$post_id = $page_id;
		$data = array();

		if ( $post_id ) {
			$gallery = get_post_gallery( $post_id , false );
			if ( $gallery ) {
				$images = $gallery['ids'];
			}
		}

		$size = 'hotel_luxury_medium';
		$image_thumb_size = apply_filters( 'onepress_gallery_page_img_size', $size );
		if ( ! empty( $images ) ) {
			$images = explode( ',', $images );
			foreach ( $images as $post_id ) {
				$post = get_post( $post_id );
				if ( $post ) {
					$img_thumb = wp_get_attachment_image_src($post_id, $image_thumb_size );
					if ($img_thumb) {
						$img_thumb = $img_thumb[0];
					}
					$img_full = wp_get_attachment_image_src( $post_id, 'full' );
					if ($img_full) {
						$img_full = $img_full[0];
					}
					if ( $img_thumb && $img_full ) {
						$data[] = array(
							'id'        => $post_id,
							'thumbnail' => $img_thumb,
							'full'      => $img_full,
							'title'     => $post->post_title,
							'content'   => $post->post_content,
						);
					}
				}
			}
		}

		return $data;
	}
}


/**
 * Custom styling
 *
 * @return string
 */
function hotel_luxury_custom_style(){
	$css = '';
	$primary_color = esc_attr( get_theme_mod( 'primary_color', 'bca474' ) );
	$footer_bg_color = esc_attr( get_theme_mod( 'footer_bg_color', '202020' ) );
	$footer_text_color = esc_attr( get_theme_mod( 'footer_text_color', '666' ) );

	$copyright_bg_color = esc_attr( get_theme_mod( 'footer_copyright_color', '222222' ) );
	$copyright_text_color = esc_attr( get_theme_mod( 'copyright_text_color', '666' ) );

	$css .= ".site-footer { background: #{$footer_bg_color}; }
			.site-footer, .site-footer a, .widget-container .footer-widgettitle { color: #{$footer_text_color}; }
			.footer-copyright-wrapper { background: #{$copyright_bg_color}; }
			.footer-copyright-wrapper, .footer-copyright-wrapper a { color: #{$copyright_text_color}; }
			a,
			.primary-nav ul li.current-menu-item a,
			.primary-nav ul li a:hover {
				color: #{$primary_color};
			}
			
			#tribe-bar-form .tribe-bar-submit input[type=submit],
			#main_slider  .owl-nav [class*=owl-],
			input[type=\"reset\"], input[type=\"submit\"], input[type=\"button\"], button {
				background: #{$primary_color};
			}
	";

	return $css;
}


function hotel_luxury_is_event(){

	$condition = false;

	if ( function_exists('tribe_is_month') || function_exists('tribe_is_list_view')
	     || function_exists('tribe_is_day') ) {
		if ( tribe_is_month() || tribe_is_list_view() || tribe_is_day() ) {
			$condition = true;
		}
	}

	return $condition;
}


function hotel_luxury_is_single_event(){
	global  $post;
	$is_event = false;
	if ( function_exists('tribe_is_event') ) {
		if ( tribe_is_event( $post->ID ) ){
			$is_event = true;
		}
	}
	return $is_event;
}