<?php
/**
 * Plupload API
 *
 * @package ClassiPress\Plupload
 */

add_action( 'wp_ajax_app_plupload_handle_upload', 'appthemes_plupload_handle_upload' );
add_action( 'wp_ajax_app_plupload_handle_delete', 'appthemes_plupload_handle_delete' );


/**
 * Generate html uploader form.
 *
 * @param int   $post_id (optional)
 * @param array $options Custom options
 *
 * @return void
 */
function appthemes_plupload_form( $post_id = 0, $options = array() ) {
	global $wp_query, $cp_options;

	if ( ! current_theme_supports( 'app-plupload' ) ) {
		return;
	}

	list( $defaults ) = get_theme_support( 'app-plupload' );
	$options = wp_parse_args( $options, $defaults );

	$attachments = array();
	if ( $post_id ) {
		$args = array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'numberposts'    => -1,
			'post_status'    => null,
			'post_parent'    => $post_id,
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
		);

		$attachments = get_posts( $args );
	}

	$field = array(
		'field_id'     => '0',
		'field_name'   => 'image-input',
		'field_label'  => __( 'Images', APP_TD ),
		'field_desc'   => '',
		'field_type'   => 'image-input',
		'field_values' => '',
		'field_req'    => $cp_options->require_images,
	);

	$curr_dir = str_replace( get_template_directory() . '/', '', dirname( __FILE__ ) );

	ob_start();
	?>
		<p class="help-text"><?php printf( __( 'You are allowed to upload %s file(s).', APP_TD ), $options['allowed_files'] ); ?> <?php printf( __( 'Maximum file size: %s KB.', APP_TD ), $options['max_file_size'] ); ?></p>
		<?php if ( ! isset( $options['disable_switch'] ) || ! $options['disable_switch'] ) { ?>
			<p class="help-text upload-flash-bypass"><?php _e( 'You are using the flash uploader. Problems? Try the <a href="#">browser uploader</a> instead.', APP_TD ); ?></p>
			<p class="help-text upload-html-bypass"><?php _e( 'You are using the browser uploader. Problems? Try the <a href="#">flash uploader</a> instead.', APP_TD ); ?></p>
		<?php } ?>
	<?php

	$field['field_tooltip'] = ob_get_clean();

	$wp_query->set( 'app_field_array', (object) $field );
	$wp_query->set( 'app_plupload_attachments', $attachments );
	$wp_query->set( 'app_plupload_options', $options );

	ob_start();
	appthemes_locate_template( array(
		'parts/form-field-image-input-content.php',
		$curr_dir . '/form-field-image-input-content.php',
	), true, false );

	$token = ob_get_clean();

	$wp_query->set( 'app_field_token', $token );
	appthemes_get_template_part( 'parts/form-field', 'image-input' );

}


/**
 * Generate html for uploaded attachment.
 *
 * @param int $attach_id
 *
 * @return string
 */
function appthemes_plupload_attach_html( $attach_id ) {
	global $wp_query;

	$attachment = get_post( $attach_id );
	$curr_dir   = str_replace( get_template_directory() . '/', '', dirname( __FILE__ ) );
	$wp_query->set( 'app_plupload_attachment', $attachment );

	ob_start();
	appthemes_locate_template( array(
		'parts/form-field-image-input-attach.php',
		$curr_dir . '/form-field-image-input-attach.php',
	), true, false );

	$html = ob_get_clean();
	return $html;
}


/**
 * Enqueue scripts for plupload.
 *
 * @param int   $post_id (optional)
 * @param array $options Custom options
 *
 * @return void
 */
function appthemes_plupload_enqueue_scripts( $post_id = 0, $options = array() ) {

	if ( ! current_theme_supports( 'app-plupload' ) ) {
		return;
	}

	list( $defaults ) = get_theme_support( 'app-plupload' );
	$options = wp_parse_args( $options, $defaults );

	wp_enqueue_script( 'app-plupload', get_template_directory_uri() . '/includes/plupload/app-plupload.js', array( 'jquery', 'plupload-handlers' ) );

	$app_plupload_config = array(
		'nonce' => wp_create_nonce( 'app_attachment' ),
		'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
		'confirmMsg' => __( 'Are you sure?', APP_TD ),
		'dimErMsg'   => __( 'This is lower than the minimum size. Please try another.', APP_TD ),
		'plupload' => array(
			'runtimes' => 'flash,silverlight,html5,html4',
			'file_data_name' => 'app_attachment_file',
			'max_file_size' => $options['max_file_size'] . 'kb',
			'multi_selection' => false,
			//'resize' => array( 'width' => 1000, 'height' => 1000, 'quality' => 80 ),
			'url' => admin_url( 'admin-ajax.php', 'relative' ),
			'flash_swf_url' => includes_url( 'js/plupload/plupload.flash.swf' ),
			'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
			'filters' => array(
				'mime_types' => array(
					array(
						'title'      => __( 'Allowed Files', APP_TD ),
						'extensions' => 'jpg,jpeg,gif,png',
					),
				),
				'min_file_width'  => $options['min_file_width'],
				'min_file_height' => $options['min_file_height'],
			),
			'multipart' => true,
			'urlstream_upload' => true,
			'multipart_params' => array(
				'post_id' => $post_id,
				'nonce' => wp_create_nonce( 'app_attachment_upload' ),
				'action' => 'app_plupload_handle_upload',
			)
		)
	);
	$app_plupload_config = apply_filters( 'appthemes_plupload_config', $app_plupload_config );

	wp_localize_script( 'app-plupload', 'AppPluploadConfig', $app_plupload_config );

}


/**
 * Handle upload of attachment and generates metadata.
 *
 * @return void
 */
function appthemes_plupload_handle_upload() {
	check_ajax_referer( 'app_attachment_upload', 'nonce' );

	$attach_id = false;
	$file_name = basename( $_FILES['app_attachment_file']['name'] );
	$file_type = wp_check_filetype( $file_name );
	$file_renamed = mt_rand( 1000, 1000000 ) . '.' . $file_type['ext'];
	$upload = array(
		'name' => $file_renamed,
		'type' => $file_type['type'],
		'tmp_name' => $_FILES['app_attachment_file']['tmp_name'],
		'error' => $_FILES['app_attachment_file']['error'],
		'size' => $_FILES['app_attachment_file']['size']
	);
	$file = wp_handle_upload( $upload, array( 'test_form' => false ) );

	if ( isset( $file['file'] ) ) {
		$file_loc = $file['file'];

		$attachment = array(
			'post_mime_type' => $file_type['type'],
			'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $file_name ) ),
			'post_content' => '',
			'post_status' => 'inherit'
		);

		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$attach_id = wp_insert_attachment( $attachment, $file_loc, $post_id );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file_loc );
		wp_update_attachment_metadata( $attach_id, $attach_data );
	}

	if ( $attach_id ) {
		do_action( 'appthemes_plupload_uploaded_attachment', $attach_id );

		$html = appthemes_plupload_attach_html( $attach_id );

		$response = array(
			'success' => true,
			'html' => $html,
		);

		die( json_encode( $response ) );
	}


	$response = array( 'success' => false );
	die( json_encode( $response ) );
}


/**
 * Deletes attachment.
 *
 * @return string
 */
function appthemes_plupload_handle_delete() {
	check_ajax_referer( 'app_attachment', 'nonce' );

	$attach_id = isset( $_POST['attach_id'] ) ? intval( $_POST['attach_id'] ) : 0;
	$attachment = get_post( $attach_id );

	if ( get_current_user_id() == $attachment->post_author || current_user_can( 'delete_private_pages' ) ) {
		wp_delete_attachment( $attach_id, true );
		do_action( 'appthemes_plupload_deleted_attachment', $attach_id );
		echo 'success';
	}

	exit;
}


/**
 * Associate previously uploaded attachments.
 *
 * @param int $post_id
 * @param array $attachments
 * @param array $titles (optional)
 * @param bool $print (optional)
 *
 * @return string
 */
function appthemes_plupload_associate_images( $post_id, $attachments, $titles = false, $print = false ) {
	$i = 0;
	$count = count( $attachments );

	if ( $count > 0 && $print ) {
		echo html( 'p', __( 'Your listing images are now being processed...', APP_TD ) );
	}

	foreach ( $attachments as $key => $attach_id ) {
		$update = array(
			'ID' => $attach_id,
			'post_parent' => $post_id,
			'menu_order' => $key,
		);

		if ( isset( $titles[ $key ] ) ) {
			$title = wp_strip_all_tags( $titles[ $key ] );
			$update['post_title'] = $title;
			update_post_meta( $attach_id, '_wp_attachment_image_alt', $title );
		}

		wp_update_post( $update );
		do_action( 'appthemes_plupload_updated_attachment', $attach_id );

		if ( $print ) {
			echo html( 'p', sprintf( __( 'Image number %1$d of %2$s has been processed.', APP_TD ), $i+1, $count ) );
		}

		$i++;
	}

}

/**
 * Check state of app-plupload
 *
 * @return bool
 */
function appthemes_plupload_is_enabled() {
	if ( isset( $_REQUEST['app-plupload'] ) && $_REQUEST['app-plupload'] == 'disable' ) {
		return false;
	}

	if ( ! current_theme_supports( 'app-plupload' ) ) {
		return false;
	}

	return true;
}
