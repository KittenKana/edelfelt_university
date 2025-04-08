<?php
/**
 * Images processing.
 *
 * @package ClassiPress\Images
 * @author  AppThemes
 * @since   ClassiPress 3.4
 */


/**
 * Set default post thumbnail size.
 *
 * Use a max width of 1200 and unlimited height for better responsive support.
 *
 * @since 4.0.0
 */
set_post_thumbnail_size( 1200, 9999 );
// Image sizes
//add_image_size( 'blog-thumbnail', 150, 150 ); // blog post thumbnail size
add_image_size( 'sidebar-thumbnail', 50, 50, true ); // sidebar blog thumbnail size
//add_image_size( 'ad-thumb', 75, 75, true );
//add_image_size( 'ad-small', 100, 100, true );
add_image_size( 'ad-medium', 250, 250, true );
//add_image_size( 'ad-large', 500, 500 );


/**
 * Checks images for valid file size and type on ad submission form.
 *
 * @return array
 */
function cp_validate_image() {
	global $cp_options;

	$error_msg = array();
	$max_size = ( $cp_options->max_image_size * 1024 ); // 1024 K = 1 MB. convert into bytes so we can compare file size to max size. 1048576 bytes = 1MB.

	if ( empty( $_FILES['image']['name'] ) ) {
		$error_msg;
	}

	foreach ( (array) $_FILES['image']['name'] as $key => $value ) {

		// added for 3.0.1 to force image names to lowercase. some systems throw an error otherwise
		$value = strtolower( $value );

		if ( empty( $value ) ) {
			continue;
		}

		if ( $max_size < $_FILES['image']['size'][ $key ] ) {
			$size_diff = number_format( ( $_FILES['image']['size'][ $key ] - $max_size )/1024 );
			$max_size_fmt = number_format( $cp_options->max_image_size );
			$error_msg[] = '<strong>' . $_FILES['image']['name'][ $key ] . '</strong> ' . sprintf( __( 'exceeds the %1$s KB limit by %2$s KB. Please go back and upload a smaller image.', APP_TD ), $max_size_fmt, $size_diff );
		} elseif ( ! cp_file_is_image( $_FILES['image']['tmp_name'][ $key ] ) ) {
			$error_msg[] = '<strong>' . $_FILES['image']['name'][ $key ] . '</strong> ' . __( 'is not a valid image type (.gif, .jpg, .png). Please go back and upload a different image.', APP_TD );
		}
	}

	return $error_msg;
}


/**
 * Process each image that's being uploaded.
 *
 * @return array
 */
function cp_process_new_image() {
	$postvals = array();

	for ( $i = 0; $i < count( $_FILES['image']['tmp_name'] ); $i++ ) {
		if ( empty( $_FILES['image']['tmp_name'][ $i ] ) ) {
			continue;
		}

		// rename the image to a random number to prevent junk image names from coming in
		$renamed = mt_rand( 1000, 1000000 ) . "." . appthemes_find_ext( $_FILES['image']['name'][ $i ] );

		// Hack since WP can't handle multiple uploads as of 2.8.5
		$upload = array( 'name' => $renamed, 'type' => $_FILES['image']['type'][ $i ], 'tmp_name' => $_FILES['image']['tmp_name'][ $i ], 'error' => $_FILES['image']['error'][ $i ], 'size' => $_FILES['image']['size'][ $i ] );

		// need to set this in order to send to WP media
		$overrides = array( 'test_form' => false );

		// check and make sure the image has a valid extension and then upload it
		$file = cp_image_upload( $upload );

		// put all these keys into an array and session so we can associate the image to the post after generating the post id
		if ( $file ) {
			$postvals['attachment'][ $i ] = array( 'post_title' => $renamed, 'post_content' => '', 'post_excerpt' => '', 'post_mime_type' => $file['type'], 'guid' => $file['url'], 'file' => $file['file'] );
		}
	}

	return $postvals;
}


/**
 * Ties the uploaded files to the correct ad post and creates the multiple image sizes.
 *
 * @param int $post_id
 * @param array $files
 * @param bool $print (optional)
 *
 * @return void
 */
function cp_associate_images( $post_id, $files, $print = false ) {
	$i = 1;
	$image_count = count( $files );
	if ( $image_count > 0 && $print ) {
		echo html( 'p', __( 'Your ad images are now being processed...', APP_TD ) );
	}

	foreach ( $files as $key => $file ) {
		$post_title = esc_attr( get_the_title( $post_id ) );
		$attachment = array( 'post_title' => $post_title, 'post_content' => $file['post_content'], 'post_excerpt' => $file['post_excerpt'], 'post_mime_type' => $file['post_mime_type'], 'guid' => $file['guid'], 'menu_order' => $key );
		$attach_id = wp_insert_attachment( $attachment, $file['file'], $post_id );

		// create multiple sizes of the uploaded image via WP controls
		wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $file['file'] ) );

		if ( $print ) {
			echo html( 'p', sprintf( __( 'Image number %1$d of %2$s has been processed.', APP_TD ), $i, $image_count ) );
		}
		$i++;
	}
}


/**
 * Uploads an image file.
 *
 * @return array|bool Boolean false on failure
 */
function cp_image_upload( $upload ) {
	if ( cp_file_is_image( $upload['tmp_name'] ) ) {
		$overrides = array( 'test_form' => false );
		// move image to the WP defined upload directory and set correct permissions
		$file = wp_handle_upload( $upload, $overrides );
		return $file;
	}

	return false;
}


/**
 * Deletes the image from WordPress.
 *
 * @return void
 */
function cp_delete_image() {
	if ( ! isset( $_POST['image'] ) ) {
		return;
	}

	foreach ( (array) $_POST['image'] as $img_id_del ) {
		if ( ! $img_id_del = absint( $img_id_del ) ) {
			continue;
		}

		// get image object
		$img_del = get_post( $img_id_del );

		if ( ! $img_del || $img_del->post_type != 'attachment' ) {
			continue;
		}

		if ( ! wp_delete_attachment( $img_id_del, true ) ) {
			wp_die( __( 'Error in deleting the image.', APP_TD ) );
		}
	}
}


/**
 * Updates the image alt and title text on edit ad page.
 * @since 3.0.5
 *
 * @return void
 */
function cp_update_alt_text() {
	if ( ! isset( $_POST['attachments'] ) || ! is_array( $_POST['attachments'] ) ) {
		return;
	}

	foreach ( $_POST['attachments'] as $attachment_id => $attachment ) {
		if ( ! isset( $attachment['image_alt'] ) ) {
			continue;
		}

		$image_alt = esc_html( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) );

		// update not needed
		if ( $image_alt == esc_html( $attachment['image_alt'] ) ) {
			continue;
		}

		$image_alt = wp_strip_all_tags( esc_html( $attachment['image_alt'] ), true );

		$image_data = & get_post( $attachment_id );
		if ( $image_data ) {
			// update the image alt text for based on the id
			update_post_meta( $attachment_id, '_wp_attachment_image_alt', addslashes( $image_alt ) );

			// update the image title text. it's stored as a post title so it's different to update
			$post = array();
			$post['ID'] = $attachment_id;
			$post['post_title'] = $image_alt;
			wp_update_post( $post );
		}
	}
}


/**
 * Checks if passed file is an image.
 *
 * @param string $path
 *
 * @return bool
 */
function cp_file_is_image( $path ) {
	$info = @getimagesize( $path );
	if ( empty( $info ) ) {
		$result = false;
	} elseif ( ! in_array( $info[2], array( IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG ) ) ) {
		$result = false;
	} else {
		$result = true;
	}

	return apply_filters( 'cp_file_is_image', $result, $path );
}


/**
 * Counts images associated to an ad.
 *
 * @param int $ad_id
 *
 * @return int
 */
function cp_count_ad_images( $ad_id ) {
	$args = array( 'post_type' => 'attachment', 'post_mime_type' => 'image', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $ad_id, 'order' => 'ASC', 'orderby' => 'ID', 'no_found_rows' => true );

	// get all the images associated to this ad
	$images = get_posts( $args );
	$imagecount = count( $images );

	return $imagecount;
}


/**
 * Returns the featured image id for a post.
 *
 * @param int $post_id
 *
 * @return int
 */
function cp_get_featured_image_id( $post_id ) {
	global $images_data;

	// Return the post thumbnail if exists.
	$image_id = get_post_thumbnail_id( $post_id );

	if ( $image_id ) {
		return $image_id;
	}

	$attachment_query = array(
		'post_parent'    => $post_id,
		'post_status'    => 'inherit',
		'numberposts'    => 1,
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'ID',
	);

	$attachment_ids = get_post_meta( $post_id, '_app_media', true );

	if ( ! empty( $attachment_ids ) ) {
		$attachment_query = array_merge( $attachment_query, array(
			'post__in' => $attachment_ids,
			'orderby'  => 'post__in'
		) );
		$images = get_children( $attachment_query );
		if ( $images ) {
			$image = array_shift( $images );
			$image_id = $image->ID;
		}
	} elseif ( isset( $images_data[ $post_id ] ) ) {
		$image_id = $images_data[ $post_id ];
	} else {
		$images = get_children( $attachment_query );
		if ( $images ) {
			$image = array_shift( $images );
			$image_id = $image->ID;
		}
	}

	if ( ! $image_id && get_theme_mod( 'category_thumbnail_placeholder' ) ) {

		$terms   = get_the_terms( $post_id, APP_TAX_CAT );
		$term_id = 0;
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$term_id = $terms[0]->term_id;
		}
		$thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );
		$thumbnail_id = array_filter( (array) $thumbnail_id );
		if ( ! empty( $thumbnail_id ) ) {
			$image_id = $thumbnail_id[0];
		}
	}

	if ( ! isset( $image_id ) || ! is_numeric( $image_id ) ) {
		$image_id = 0;
	}

	return $image_id;
}


/**
 * Displays ad thumbnail. Use in the loop.
 *
 * @return void
 */
if ( ! function_exists( 'cp_ad_loop_thumbnail' ) ) :
	function cp_ad_loop_thumbnail( $size = 'ad-medium' ) {
		global $post, $cp_options;

		// go see if any images are associated with the ad
		$image_id = cp_get_featured_image_id( $post->ID );

		// set the class based on if the hover preview option is set to "yes"
		$prevclass = ( $cp_options->ad_image_preview ) ? 'preview' : 'nopreview';

		if ( $image_id > 0 ) {

			// get 75x75 v3.0.5+ image size
			$adthumbarray = wp_get_attachment_image( $image_id, $size );

			// grab the large image for onhover preview
			$adlargearray = wp_get_attachment_image_src( $image_id, 'large' );
			$img_large_url_raw = $adlargearray[0];

			// must be a v3.0.5+ created ad
			if ( $adthumbarray ) {
				echo '<a href="'. get_permalink() .'" title="'. the_title_attribute( 'echo=0' ) .'" class="'. $prevclass .'" data-rel="'. $img_large_url_raw .'">'. $adthumbarray .'</a>';

			// maybe a v3.0 legacy ad
			} else {
				$adthumblegarray = wp_get_attachment_image_src($image_id, 'thumbnail');
				$img_thumbleg_url_raw = $adthumblegarray[0];
				echo '<a href="'. get_permalink() .'" title="'. the_title_attribute( 'echo=0' ) .'" class="'. $prevclass .'" data-rel="'. $img_large_url_raw .'">'. $adthumblegarray .'</a>';
			}

		// no image so return the placeholder thumbnail
		} else {
			$image_src = appthemes_locate_template_uri( 'assets/images/placeholder.png' );
			if ( get_theme_mod( 'listing_thumbnail_image_placeholder' ) ) {
				$image_src = wp_get_attachment_image_src( get_theme_mod( 'listing_thumbnail_image_placeholder' ), $size );
				if ( ! $image_src ) {
					$image_src = get_theme_mod( 'listing_thumbnail_image_placeholder' );
				}
			}

			$image_hwstring = '';
			$sizes = wp_get_additional_image_sizes();

			if ( is_array( $size ) && 2 === count( $size ) ) {
				$image_hwstring = image_hwstring( $size[0], $size[1] );
			} elseif ( is_string( $size ) ) {
				if ( isset( $sizes[ $size ] ) ) {
					$image_hwstring = image_hwstring( $sizes[ $size ]['width'], $sizes[ $size ]['height'] );
				}
			}

			echo '<a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '"><img class="attachment-ad-medium" alt="" title="" src="' . esc_url( $image_src ) . ' " ' . $image_hwstring . '/></a>';
		}

	}
endif;

/**
 * Displays the main image associated to a blog post used on the single page.
 *
 * @since 3.5.2
 */
function cp_get_blog_image_url() {
	global $post;

	$image_medium = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' );
	$image_large = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
	$alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );

	echo '<a href="' . esc_url( $image_large[0] ) . '" class="img-main" data-rel="colorbox" title="' . esc_attr( the_title_attribute( 'echo=0' ) ) . '">' .
		 '<img class="attachment-blog-thumbnail img-responsive" src="' . esc_url( $image_medium[0] ) . '" title="' . esc_attr( $alt ) . '" alt="'. esc_attr( $alt ) .'" /></a>';
}


/**
 * Returns the image link. Used in the edit-ad page template.
 *
 * @param int $id (optional)
 * @param string $size (optional)
 * @param bool $permalink (optional)
 * @param bool $icon (optional)
 * @param bool $text (optional)
 *
 * @return string
 */
function cp_get_attachment_link( $id = 0, $size = 'thumbnail', $permalink = false, $icon = false, $text = false ) {
	$id = intval( $id );
	$_post = get_post( $id );

	if ( ( 'attachment' != $_post->post_type ) || ! $url = wp_get_attachment_url( $_post->ID ) ) {
		return __( 'Missing Attachment', APP_TD );
	}

	if ( $permalink ) {
		$url = get_attachment_link( $_post->ID );
	}

	$post_title = esc_attr( $_post->post_title );

	if ( $text ) {
		$link_text = esc_attr( $text );
	} elseif ( ( is_int( $size ) && $size != 0 ) || ( is_string( $size ) && $size != 'none' ) || $size != false ) {
		$link_text = wp_get_attachment_image( $id, $size, $icon );
	} else {
		$link_text = '';
	}

	if ( trim( $link_text ) == '' ) {
		$link_text = $_post->post_title;
	}

	return apply_filters( 'cp_get_attachment_link', "<a target='_blank' href='$url' alt='' class='post-gallery' data-rel='colorbox' title='$post_title'>$link_text</a>", $id, $size, $permalink, $icon, $text );
}


/**
 * Displays manage images form. Used on the ad edit page.
 *
 * @param int $ad_id
 *
 * @return int
 */
if ( ! function_exists( 'cp_get_ad_images' ) ) {
	function cp_get_ad_images( $ad_id ) {
		$args = array( 'post_type' => 'attachment', 'post_mime_type' => 'image', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $ad_id, 'order' => 'ASC', 'orderby' => 'ID', 'no_found_rows' => true );

		// get all the images associated to this ad
		$images = get_posts( $args );
		$imagecount = count( $images );

		// make sure we have images associated to the ad
		if ( ! $images ) {
			return $imagecount;
		}

		$i = 1;
		$media_dims = '';
		foreach ( $images as $image ) {

			// go get the width and height fields since they are stored in meta data
			$meta = wp_get_attachment_metadata( $image->ID );
			if ( is_array( $meta ) && array_key_exists( 'width', $meta ) && array_key_exists( 'height', $meta ) ) {
				$media_dims = "<span id='media-dims-". $image->ID ."'>{$meta['width']}&nbsp;&times;&nbsp;{$meta['height']}</span> ";
			}
		?>
			<li class="images">
				<div class="labelwrapper">
					<label><?php _e( 'Image', APP_TD ); ?> <?php echo $i; ?>:</label>
				</div>

				<div class="thumb-wrap-edit">
					<?php echo cp_get_attachment_link( $image->ID ); ?>
				</div>

				<div class="image-meta">
					<p class="image-delete"><input class="checkbox" type="checkbox" name="image[]" value="<?php echo $image->ID; ?>">&nbsp;<?php _e( 'Delete Image', APP_TD ); ?></p>
					<p class="image-meta"><strong><?php _e( 'Upload Date:', APP_TD ); ?></strong> <?php echo appthemes_display_date( $image->post_date, 'date' ); ?></p>
					<p class="image-meta"><strong><?php _e( 'File Info:', APP_TD ); ?></strong> <?php echo $media_dims; ?> <?php echo $image->post_mime_type; ?></p>
				</div>

				<div class="clr"></div>

			<?php // get the alt text and print out the field
				$alt = get_post_meta( $image->ID, '_wp_attachment_image_alt', true ); ?>
				<p class="alt-text">
					<div class="labelwrapper">
						<label><?php _e( 'Alt Text:', APP_TD ); ?></label>
					</div>
					<input type="text" class="text" name="attachments[<?php echo $image->ID; ?>][image_alt]" id="image_alt" value="<?php echo esc_attr( stripslashes( $alt ) ); ?>" />
				</p>

				<div class="clr"></div>
			</li>
		<?php
			$i++;
		}

		return $imagecount;
	}
}


/**
 * Displays form fields for adding new images.
 *
 * @param int $imagecount
 *
 * @return void
 */
function cp_ad_edit_image_input_fields( $imagecount ) {
	global $cp_options, $wp_query;

	$disabled = '';

	// get the max number of images allowed option
	$maximages = $cp_options->num_images;

	// figure out how many image upload fields we need
	$imageboxes = ( $maximages - $imagecount );

	// now loop through and print out the upload fields
	for ( $i = 0; $i < $imageboxes; $i++ ) {
		$next = $i + 1;
		if ( $i > 0 ) $disabled = 'disabled="disabled"';

		$field = array(
			'field_id'         => '0',
			'field_name'       => array( 'image' ),
			'field_label'      => __( 'Add Image', APP_TD ),
			'field_desc'       => '',
			'field_type'       => 'image-input',
			'field_values'     => '',
			'field_tooltip'    => '',
			'field_req'        => '1',
			'field_min_length' => '0',
		);

		$wp_query->set( 'app_field_array', (object) $field );
		$wp_query->set( 'app_field_token', "<input type=\"file\" name=\"image[]\" id=\"upload$i\" class=\"fileupload\" onchange=\"enableNextImage(this,$next)\" $disabled" . ' />' );

		appthemes_get_template_part( 'parts/form-field', 'image-input' );
	}
?>

	<p class="help-text"><?php printf( __( 'You are allowed %s image(s) per ad.', APP_TD ), $maximages ); ?> <?php echo $cp_options->max_image_size; ?><?php _e( 'KB max file size per image.', APP_TD ); ?> <?php _e( 'Check the box next to each image you wish to delete.', APP_TD ); ?></p>

<?php
}


/**
 * Collects and cache featured images for displayed posts.
 *
 * @return void
 */
function cp_collect_featured_images() {
	global $wpdb, $posts, $pageposts, $wp_query, $images_data;

	if ( isset( $posts ) && is_array( $posts ) ) {
		foreach ( $posts as $post ) {
			$post_ids[] = $post->ID;
		}
	}

	if ( isset( $pageposts ) && is_array( $pageposts ) ) {
		foreach ( $pageposts as $post ) {
			$post_ids[] = $post->ID;
		}
	}

	if ( isset( $wp_query->posts ) && is_array( $wp_query->posts ) ) {
		foreach ( $wp_query->posts as $post ) {
			$post_ids[] = $post->ID;
		}
	}

	if ( isset( $post_ids ) && is_array( $post_ids ) ) {
		$post_ids = array_unique( $post_ids );
		$post_list = implode( ",", $post_ids );
		$images = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_parent IN ($post_list) AND (post_mime_type LIKE 'image/%') AND post_type = 'attachment' AND (post_status = 'inherit') ORDER BY ID ASC" );
  }

	if ( isset( $images ) && is_array( $images ) ) {
		foreach( $images as $image ) {
			if ( ! isset( $images_data[ $image->post_parent ] ) ) {
				$images_data[ $image->post_parent ] = $image->ID;
			}
		}
		// create cache for images
		update_post_caches( $images, 'post', false, true );
	}

	if ( isset( $post_ids ) && is_array( $post_ids ) ) {
		foreach ( $post_ids as $post_id ) {
			if ( ! isset( $images_data[ $post_id ] ) ) {
				$images_data[ $post_id ] = 0;
			}
		}
	}

}


/**
 * Outputs the media manager.
 */
function cp_media_manager( $post_id, $atts, $params = array() ) {

	if ( ! current_theme_supports('app-media-manager') ) {
		return;
	}

	appthemes_media_manager( $post_id, $atts, $params );
}

/**
 * Get a cover image from the same taxonomy (category/post_tag) if one doesn't already exist.
 *
 * @since 4.0.0
 *
 * @param array|object $args
 * @return array $image
 */
function cp_tax_cover_image( $args ) {
	global $wp_query;

	$image = false;

	if ( empty( $args['object_ids'] ) && empty( $args['images'] ) ) {

		if ( empty( $wp_query->posts ) ) {
			return $image;
		}

		$args['object_ids'] = wp_list_pluck( $wp_query->posts, 'ID' );
	}

	if ( empty( $args['images'] ) && ! empty( $args['object_ids'] ) ) {

		// No image so query for a new one in the same category/tax.
		if ( ! $image ) {
			$attachments = new WP_Query( array(
				'post_parent__in'        => $args['object_ids'],
				'post_type'              => 'attachment',
				'post_status'            => 'inherit',
				'posts_per_page'         => 1,
				'orderby'                => 'rand',
				'update_post_term_cache' => false,
				'no_found_rows'          => true,
			) );

			// Get the first image it finds.
			if ( $attachments->have_posts() ) {
				$image = wp_get_attachment_image_src( $attachments->posts[0]->ID, $args['size'] );
			}
		}

	} elseif ( ! empty( $args['images'] ) ) {
		shuffle( $args['images'] );
		$image = wp_get_attachment_image_src( current( $args['images'] ), $args['size'] );
	}

	/**
	 * Filters the cover image from the same taxonomy.
	 *
	 * @since 4.0.0
	 *
	 * @param string $image The url to the image.
	 * @param array  $args  The arguments for the image.
	 */
	return apply_filters( 'cp_tax_cover_image', $image, $args );
}
