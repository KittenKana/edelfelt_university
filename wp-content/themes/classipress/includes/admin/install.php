<?php
/**
 * Installation functions.
 *
 * @package ClassiPress\Admin\Install
 * @author  AppThemes
 * @since   ClassiPress 3.0
 */


function cp_install_theme() {

	// run the table install script
	cp_tables_install();

	// populate the database tables
	cp_populate_tables();

	// insert the default values
	cp_default_values();

	// create pages and assign templates
	cp_create_pages();

	// create a default ad and category
	cp_default_ad();

	// create the default menus
	cp_default_menus();

	// assign default widgets to sidebars
	cp_default_widgets();

	// create default logo.
	cp_default_logo();

	// create default logo.
	cp_install_favicon();

	// flush the rewrite rules
	flush_rewrite_rules();

	// if fresh install, setup current database version, and do not process update
	if ( get_option( 'cp_db_version' ) == false ) {

		// set blog and ads pages
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', CP_Ads_Home::get_id() );
		update_option( 'page_for_posts', CP_Blog_Archive::get_id() );

		update_option( 'cp_db_version', CP_DB_VERSION );
	}

}
add_action( 'appthemes_first_run', 'cp_install_theme' );


// Create the theme database tables
function cp_tables_install() {
	global $wpdb;

	// create the ad forms table - store form data

		$sql = "
					id int(10) NOT NULL AUTO_INCREMENT,
					form_name varchar(255) NOT NULL,
					form_label varchar(255) NOT NULL,
					form_desc longtext DEFAULT NULL,
					form_cats longtext NOT NULL,
					form_status varchar(255) DEFAULT NULL,
					form_owner varchar(255) NOT NULL DEFAULT 'admin',
					form_created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					form_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					PRIMARY KEY  (id)";

	scb_install_table( 'cp_ad_forms', $sql );


	// create the ad meta table - store form fields meta data

		$sql = "
					meta_id int(10) NOT NULL AUTO_INCREMENT,
					form_id int(10) NOT NULL,
					field_id int(10) NOT NULL,
					field_req varchar(255) NOT NULL DEFAULT '0',
					field_pos int(10) NOT NULL DEFAULT '0',
					field_search int(10) NOT NULL DEFAULT '0',
					PRIMARY KEY  (meta_id)";

	scb_install_table( 'cp_ad_meta', $sql );


	// create the ad fields table - store form fields data

		$sql = "
					field_id int(10) NOT NULL AUTO_INCREMENT,
					field_name varchar(255) NOT NULL,
					field_label varchar(255) NOT NULL,
					field_desc longtext DEFAULT NULL,
					field_type varchar(255) NOT NULL,
					field_values longtext DEFAULT NULL,
					field_tooltip longtext DEFAULT NULL,
					field_search varchar(255) NOT NULL DEFAULT '0',
					field_perm int(11) NOT NULL DEFAULT '0',
					field_core int(11) NOT NULL DEFAULT '0',
					field_req int(11) NOT NULL DEFAULT '0',
					field_owner varchar(255) NOT NULL DEFAULT 'admin',
					field_created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					field_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					field_min_length int(11) NOT NULL DEFAULT '0',
					field_validation longtext DEFAULT NULL,
					PRIMARY KEY  (field_id)";

	scb_install_table( 'cp_ad_fields', $sql );


	// create the geocodes table - store geo location data

		$sql = "
					post_id bigint(20) unsigned NOT NULL,
					lat float( 10, 6 ) NOT NULL,
					lng float( 10, 6 ) NOT NULL,
					PRIMARY KEY  (post_id)";

	scb_install_table( 'cp_ad_geocodes', $sql );

}


// Populate the database tables
function cp_populate_tables() {
	global $wpdb;

	/**
	* Insert default data into tables
	*
	* Flag values for the cp_ad_fields table
	* =======================================
	* Field permissions (field name - field_perm) are 0,1,2 and are as follows:
	* 0 = rename label, remove from form layout, reorder, change values, delete
	* 1 = rename label, reorder
	* 2 = rename label, remove from form layout, reorder, change values
	*
	* please don't ask about the logic of the order. :-)
	*
	* field_core can be 1 or 0. 1 means it's a core field and will be included
	* in the default form if no custom form has been created
	*
	* field_req in this table is only used for the default form meaning if no
	* custom form has been created, use these fields with 1 meaning mandatory field
	*
	*
	*/

	$field_sql = "SELECT field_id FROM $wpdb->cp_ad_fields WHERE field_name = %s LIMIT 1";

	// DO NOT CHANGE THE ORDER OF THE FIRST 9 RECORDS!
	// admin-options.php cp_add_core_fields() depends on these fields
	// add more records after the post_content row insert statement


	// Title field
	$wpdb->get_results( $wpdb->prepare($field_sql, 'post_title') );
	if ( $wpdb->num_rows == 0 ) {

		$wpdb->insert( $wpdb->cp_ad_fields, array(
			'field_name' => 'post_title',
			'field_label' => 'Title',
			'field_desc' => 'This is the name of the ad and is mandatory on all forms. It is a core ClassiPress field and cannot be deleted.',
			'field_type' => 'text box',
			'field_values' => '',
			'field_search' => '0',
			'field_perm' => '1',
			'field_core' => '1',
			'field_req' => '1',
			'field_owner' => 'ClassiPress',
			'field_created' => current_time('mysql'),
			'field_modified' => current_time('mysql'),
			'field_min_length' => '0'
		) );

	}

	// Price field
	$wpdb->get_results( $wpdb->prepare( $field_sql, 'cp_price' ) );
	if ( $wpdb->num_rows == 0 ) {

		$wpdb->insert( $wpdb->cp_ad_fields, array(
			'field_name' => 'cp_price',
			'field_label' => 'Price',
			'field_desc' => 'This is the price field for the ad. It is a core ClassiPress field and cannot be deleted.',
			'field_type' => 'text box',
			'field_values' => '',
			'field_search' => '0',
			'field_perm' => '2',
			'field_core' => '1',
			'field_req' => '1',
			'field_owner' => 'ClassiPress',
			'field_created' => current_time('mysql'),
			'field_modified' => current_time('mysql'),
			'field_min_length' => '0'
		) );

	}

	// Street field
	$wpdb->get_results( $wpdb->prepare( $field_sql, 'cp_street' ) );
	if ( $wpdb->num_rows == 0 ) {

		$wpdb->insert( $wpdb->cp_ad_fields, array(
			'field_name' => 'cp_street',
			'field_label' => 'Street',
			'field_desc' => 'This is the street address text field. It is a core ClassiPress field and cannot be deleted. (Needed on your forms for Google maps to work best.)',
			'field_type' => 'text box',
			'field_values' => '',
			'field_search' => '0',
			'field_perm' => '2',
			'field_core' => '1',
			'field_req' => '0',
			'field_owner' => 'ClassiPress',
			'field_created' => current_time('mysql'),
			'field_modified' => current_time('mysql'),
			'field_min_length' => '0'
		) );

	}

	// City field
	$wpdb->get_results( $wpdb->prepare( $field_sql, 'cp_city' ) );
	if ( $wpdb->num_rows == 0 ) {

		$wpdb->insert( $wpdb->cp_ad_fields, array(
			'field_name' => 'cp_city',
			'field_label' => 'City',
			'field_desc' => 'This is the city field for the ad listing. It is a core ClassiPress field and cannot be deleted. (Needed on your forms for Google maps to work best.)',
			'field_type' => 'text box',
			'field_values' => '',
			'field_search' => '1',
			'field_perm' => '2',
			'field_core' => '1',
			'field_req' => '0',
			'field_owner' => 'ClassiPress',
			'field_created' => current_time('mysql'),
			'field_modified' => current_time('mysql'),
			'field_min_length' => '0'
		) );

	}

	// State field
	$wpdb->get_results( $wpdb->prepare( $field_sql, 'cp_state' ) );
	if ( $wpdb->num_rows == 0 ) {

		$wpdb->insert( $wpdb->cp_ad_fields, array(
			'field_name' => 'cp_state',
			'field_label' => 'State',
			'field_desc' => 'This is the state/province drop-down select box for the ad. It is a core ClassiPress field and cannot be deleted. (Needed on your forms for Google maps to work best.)',
			'field_type' => 'drop-down',
			'field_values' => 'Alabama,Alaska,Arizona,Arkansas,California,Colorado,Connecticut,Delaware,District of Columbia,Florida,Georgia,Hawaii,Idaho,Illinois,Indiana,Iowa,Kansas,Kentucky,Louisiana,Maine,Maryland,Massachusetts,Michigan,Minnesota,Mississippi,Missouri,Montana,Nebraska,Nevada,New Hampshire,New Jersey,New Mexico,New York,North Carolina,North Dakota,Ohio,Oklahoma,Oregon,Pennsylvania,Rhode Island,South Carolina,South Dakota,Tennessee,Texas,Utah,Vermont,Virginia,Washington,West Virginia,Wisconsin,Wyoming',
			'field_search' => '1',
			'field_perm' => '2',
			'field_core' => '1',
			'field_req' => '1',
			'field_owner' => 'ClassiPress',
			'field_created' => current_time('mysql'),
			'field_modified' => current_time('mysql'),
			'field_min_length' => '0'
		) );

	}

	// Country field
	$wpdb->get_results( $wpdb->prepare( $field_sql, 'cp_country' ) );
	if ( $wpdb->num_rows == 0 ) {

		$wpdb->insert( $wpdb->cp_ad_fields, array(
			'field_name' => 'cp_country',
			'field_label' => 'Country',
			'field_desc' => 'This is the country drop-down select box for the ad. It is a core ClassiPress field and cannot be deleted.',
			'field_type' => 'drop-down',
			'field_values' => 'United States,United Kingdom,Afghanistan,Albania,Algeria,American Samoa,Angola,Anguilla,Antarctica,Antigua and Barbuda,Argentina,Armenia,Aruba,Ashmore and Cartier Island,Australia,Austria,Azerbaijan,Bahamas,Bahrain,Bangladesh,Barbados,Belarus,Belgium,Belize,Benin,Bermuda,Bhutan,Bolivia,Bosnia and Herzegovina,Botswana,Brazil,British Virgin Islands,Brunei,Bulgaria,Burkina Faso,Burma,Burundi,Cambodia,Cameroon,Canada,Cape Verde,Cayman Islands,Central African Republic,Chad,Chile,China,Christmas Island,Colombia,Comoros,Congo,Cook Islands,Costa Rica,Cote dIvoire,Croatia,Cuba,Cyprus,Czeck Republic,Denmark,Djibouti,Dominica,Dominican Republic,Ecuador,Egypt,El Salvador,Equatorial Guinea,Eritrea,Estonia,Ethiopia,Europa Island,Falkland Islands,Faroe Islands,Fiji,Finland,France,French Guiana,French Polynesia,French Southern and Antarctic Lands,Gabon,Gambia,Gaza Strip,Georgia,Germany,Ghana,Gibraltar,Glorioso Islands,Greece,Greenland,Grenada,Guadeloupe,Guam,Guatemala,Guernsey,Guinea,Guinea-Bissau,Guyana,Haiti,Heard Island and McDonald Islands,Honduras,Hong Kong,Howland Island,Hungary,Iceland,India,Indonesia,Iran,Iraq,Ireland,Ireland Northern,Isle of Man,Israel,Italy,Jamaica,Jan Mayen,Japan,Jarvis Island,Jersey,Johnston Atoll,Jordan,Juan de Nova Island,Kazakhstan,Kenya,Kiribati,Korea North,Korea South,Kuwait,Kyrgyzstan,Laos,Latvia,Lebanon,Lesotho,Liberia,Libya,Liechtenstein,Lithuania,Luxembourg,Macau,Macedonia,Madagascar,Malawi,Malaysia,Maldives,Mali,Malta,Marshall Islands,Martinique,Mauritania,Mauritius,Mayotte,Mexico,Micronesia,Midway Islands,Moldova,Monaco,Mongolia,Montserrat,Morocco,Mozambique,Namibia,Nauru,Nepal,Netherlands,Netherlands Antilles,New Caledonia,New Zealand,Nicaragua,Niger,Nigeria,Niue,Norfolk Island,Northern Mariana Islands,Norway,Oman,Pakistan,Palau,Panama,Papua New Guinea,Paraguay,Peru,Philippines,Pitcaim Islands,Poland,Portugal,Puerto Rico,Qatar,Reunion,Romania,Russia,Rwanda,Saint Helena,Saint Kitts and Nevis,Saint Lucia,Saint Pierre and Miquelon,Saint Vincent and the Grenadines,Samoa,San Marino,Sao Tome and Principe,Saudi Arabia,Scotland,Senegal,Seychelles,Sierra Leone,Singapore,Slovakia,Slovenia,Solomon Islands,Somalia,South Africa,South Georgia,Spain,Spratly Islands,Sri Lanka,Sudan,Suriname,Svalbard,Swaziland,Sweden,Switzerland,Syria,Taiwan,Tajikistan,Tanzania,Thailand,Tobago,Toga,Tokelau,Tonga,Trinidad,Tunisia,Turkey,Turkmenistan,Tuvalu,Uganda,Ukraine,United Arab Emirates,Uruguay,Uzbekistan,Vanuatu,Vatican City,Venezuela,Vietnam,Virgin Islands,Wales,Wallis and Futuna,West Bank,Western Sahara,Yemen,Yugoslavia,Zambia,Zimbabwe',
			'field_search' => '1',
			'field_perm' => '2',
			'field_core' => '1',
			'field_req' => '1',
			'field_owner' => 'ClassiPress',
			'field_created' => current_time('mysql'),
			'field_modified' => current_time('mysql'),
			'field_min_length' => '0'
		) );

	}

	// Zip/Postal Code field
	$wpdb->get_results( $wpdb->prepare( $field_sql, 'cp_zipcode' ) );
	if ( $wpdb->num_rows == 0 ) {

		$wpdb->insert( $wpdb->cp_ad_fields, array(
			'field_name' => 'cp_zipcode',
			'field_label' => 'Zip/Postal Code',
			'field_desc' => 'This is the zip/postal code text field. It is a core ClassiPress field and cannot be deleted. (Needed on your forms for Google maps to work best.)',
			'field_type' => 'text box',
			'field_values' => '',
			'field_search' => '0',
			'field_perm' => '2',
			'field_core' => '1',
			'field_req' => '0',
			'field_owner' => 'ClassiPress',
			'field_created' => current_time('mysql'),
			'field_modified' => current_time('mysql'),
			'field_min_length' => '0'
		) );

	}

	// Tags field
	$wpdb->get_results( $wpdb->prepare($field_sql, 'tags_input') );
	if ( $wpdb->num_rows == 0 ) {

		$wpdb->insert( $wpdb->cp_ad_fields, array(
			'field_name' => 'tags_input',
			'field_label' => 'Tags',
			'field_desc' => 'This is for inputting tags for the ad. It is a core ClassiPress field and cannot be deleted.',
			'field_type' => 'text box',
			'field_values' => '',
			'field_search' => '0',
			'field_perm' => '2',
			'field_core' => '1',
			'field_req' => '0',
			'field_owner' => 'ClassiPress',
			'field_created' => current_time('mysql'),
			'field_modified' => current_time('mysql'),
			'field_min_length' => '0'
		) );

	}

	// Description field
	$wpdb->get_results( $wpdb->prepare($field_sql, 'post_content') );
	if ( $wpdb->num_rows == 0 ) {

		$wpdb->insert( $wpdb->cp_ad_fields, array(
			'field_name' => 'post_content',
			'field_label' => 'Description',
			'field_desc' => 'This is the main description box for the ad. It is a core ClassiPress field and cannot be deleted.',
			'field_type' => 'text area',
			'field_values' => '',
			'field_search' => '0',
			'field_perm' => '1',
			'field_core' => '1',
			'field_req' => '1',
			'field_owner' => 'ClassiPress',
			'field_created' => current_time('mysql'),
			'field_modified' => current_time('mysql'),
			'field_min_length' => '0'
		) );

	}

	// Region field
	$wpdb->get_results( $wpdb->prepare( $field_sql, 'cp_region' ) );
	if ( $wpdb->num_rows == 0 ) {

		$wpdb->insert( $wpdb->cp_ad_fields, array(
			'field_name' => 'cp_region',
			'field_label' => 'Region',
			'field_desc' => 'This is the region drop-down select box for the ad.',
			'field_type' => 'drop-down',
			'field_values' => 'San Francisco Bay Area,Orange County,Central Valley,Northern CA,Southern CA',
			'field_search' => '1',
			'field_perm' => '2',
			'field_core' => '0',
			'field_req' => '0',
			'field_owner' => 'ClassiPress',
			'field_created' => current_time('mysql'),
			'field_modified' => current_time('mysql'),
			'field_min_length' => '0'
		) );

	}

	// Phone field
	$wpdb->get_results( $wpdb->prepare( $field_sql, 'cp_phone' ) );
	if ( $wpdb->num_rows == 0 ) {

		$wpdb->insert( $wpdb->cp_ad_fields, array(
			'field_name' => 'cp_phone',
			'field_label' => 'Phone',
			'field_desc' => 'This is the phone field for the ad.',
			'field_type' => 'text box',
			'field_values' => '',
			'field_search' => '0',
			'field_perm' => '0',
			'field_core' => '0',
			'field_req' => '0',
			'field_owner' => 'ClassiPress',
			'field_created' => current_time('mysql'),
			'field_modified' => current_time('mysql'),
			'field_min_length' => '0'
		) );

	}

	// Size field
	$wpdb->get_results( $wpdb->prepare( $field_sql, 'cp_size' ) );
	if ( $wpdb->num_rows == 0 ) {

		$wpdb->insert( $wpdb->cp_ad_fields, array(
			'field_name' => 'cp_size',
			'field_label' => 'Size',
			'field_desc' => 'This is an example of a custom drop-down field.',
			'field_type' => 'drop-down',
			'field_values' => 'XS,S,M,L,XL,XXL',
			'field_search' => '0',
			'field_perm' => '0',
			'field_core' => '0',
			'field_req' => '0',
			'field_owner' => 'ClassiPress',
			'field_created' => current_time('mysql'),
			'field_modified' => current_time('mysql'),
			'field_min_length' => '0'
		) );

	}

	// Feedback field
	$wpdb->get_results( $wpdb->prepare( $field_sql, 'cp_feedback' ) );
	if ( $wpdb->num_rows == 0 ) {

		$wpdb->insert( $wpdb->cp_ad_fields, array(
			'field_name' => 'cp_feedback',
			'field_label' => 'Feedback',
			'field_desc' => 'This is an example of a custom text area field.',
			'field_type' => 'text area',
			'field_values' => '',
			'field_search' => '0',
			'field_perm' => '0',
			'field_core' => '0',
			'field_req' => '0',
			'field_owner' => 'ClassiPress',
			'field_created' => current_time('mysql'),
			'field_modified' => current_time('mysql'),
			'field_min_length' => '0'
		) );

	}

	// Currency field
	$wpdb->get_results( $wpdb->prepare( $field_sql, 'cp_currency' ) );
	if ( $wpdb->num_rows == 0 ) {

		$wpdb->insert( $wpdb->cp_ad_fields, array(
			'field_name' => 'cp_currency',
			'field_label' => 'Currency',
			'field_desc' => 'This is the currency drop-down select box for the ad. Add it to the form below the price to allow users to choose the currency for the ad price.',
			'field_type' => 'drop-down',
			'field_values' => '$,€,£,¥',
			'field_search' => '0',
			'field_perm' => '0',
			'field_core' => '0',
			'field_req' => '0',
			'field_owner' => 'ClassiPress',
			'field_created' => current_time('mysql'),
			'field_modified' => current_time('mysql'),
			'field_min_length' => '0'
		) );

	}


	// Example Ad Pack
	$listing_packages = cp_get_listing_packages( array( 'post_status' => 'any' ) );
	if ( ! $listing_packages ) {
		$package_meta = array(
			'pack_name' => '30 days for only $5',
			'description' => 'This is the default price per ad package created by ClassiPress.',
			'price' => '5.00',
			'duration' => '30',
		);

		$package_id = wp_insert_post( array(
			'post_status' => 'publish',
			'post_type' => CP_PACKAGE_LISTING_PTYPE,
			'post_author' => 1,
			'post_name' => sanitize_title_with_dashes( $package_meta['pack_name'] ),
			'post_title' => $package_meta['pack_name'],
		) );

		foreach ( $package_meta as $meta_key => $meta_value ) {
			add_post_meta( $package_id, $meta_key, $meta_value, true );
		}
	}

	// Example Membership Pack
	$membership_packages = cp_get_membership_packages( array( 'post_status' => 'any' ) );
	if ( ! $membership_packages ) {
		$package_meta = array(
			'pack_name' => '30 days publishing for only $2',
			'description' => 'This is the default membership package created by ClassiPress.',
			'price' => '15.00',
			'price_modifier' => '2.00',
			'duration' => '30',
			'pack_type' => 'static',
			'pack_satisfies_required' => '1',
		);

		$package_id = wp_insert_post( array(
			'post_status' => 'publish',
			'post_type' => CP_PACKAGE_MEMBERSHIP_PTYPE,
			'post_author' => 1,
			'post_name' => sanitize_title_with_dashes( $package_meta['pack_name'] ),
			'post_title' => $package_meta['pack_name'],
		) );

		foreach ( $package_meta as $meta_key => $meta_value ) {
			add_post_meta( $package_id, $meta_key, $meta_value, true );
		}
	}

}


// Insert the default values
function cp_default_values() {

	// uncheck the crop thumbnail image checkbox
	delete_option( 'thumbnail_crop' );
	// set the WP image sizes
	update_option( 'medium_size_w', 200 );
	update_option( 'medium_size_h', 150 );
	update_option( 'large_size_w', 1200 );
	update_option( 'large_size_h', 9999 );
	if ( get_option( 'embed_size_w' ) == false ) {
		update_option( 'embed_size_w', 500 );
	}

	// set the default new WP user role only if it's currently subscriber
	if ( get_option( 'default_role' ) == 'subscriber' ) {
		update_option( 'default_role', 'contributor' );
	}

	// check the "membership" box to enable wordpress registration
	if ( get_option( 'users_can_register' ) == 0 ) {
		update_option( 'users_can_register', 1 );
	}

}


// Create the ClassiPress pages and assign the templates to them
function cp_create_pages() {

	// NOTE:
	// Creation of page templates currently handled by Framework class 'APP_View_Page',
	// 'install' method hooked into 'appthemes_first_run'

	if ( get_option( 'cp_db_version' ) ) {
		return;
	}

	$content =
<<<EOF
[classipress_typed_elements text="cars in Los Angeles, apartments in Tokyo, antiques in London, bikes in San Francisco, puppies in Paris, anything with ClassiPress!"]
People use ClassiPress to create amazing classified listing sites
to help visitors find what they are searching for.

&nbsp;

[classipress_searchbar]
EOF;

	wp_update_post( array(
		'ID'           => CP_Ads_Home::get_id(),
		'post_content' => $content,
	) );

	$url = appthemes_locate_template_uri( '/assets/images/classipress-featured-image.jpg' );

	// Set the front page image.

	// WordPress API for image uploads.
	require_once( ABSPATH . 'wp-admin/includes/admin.php' );

	// Get the actual file name (xyz.jpg).
	$filename = basename( $url );

	// Download the file to a temp location.
	$url = download_url( $url );

	// Check for download errors.
	if ( is_wp_error( $url ) ) {
		error_log( 'Error downloading Ads Home image: ' . $url->get_error_message() );
		return;
	}

	$file_array = array(
		'name'     => $filename,
		'tmp_name' => $url,
	);

	// Do the validation and put it in the media library.
	$image_id = media_handle_sideload( $file_array, 0, __( 'Hero Cover', APP_TD ) );

	// If error storing permanently, unlink.
	if ( is_wp_error( $image_id ) ) {
		@unlink( $file_array['tmp_name'] );
		return;
	}

	update_post_meta( CP_Ads_Home::get_id(), '_thumbnail_id', $image_id );
}


// Create the default ad
function cp_default_ad() {
	global $wpdb;

	$posts = get_posts( array( 'posts_per_page' => 1, 'post_type' => APP_POST_TYPE, 'no_found_rows' => true ) );

	if ( ! empty( $posts ) ) {
		return;
	}

	$cat = appthemes_maybe_insert_term( 'Misc', APP_TAX_CAT );

	$description = '<p>This is your first ClassiPress ad listing. It is a placeholder ad just so you can see how it works. Delete this before launching your new classified ads site.</p>Duis arcu turpis, varius nec sagittis id, ultricies ac arcu. Etiam sagittis rutrum nunc nec viverra. Etiam egestas congue mi vel sollicitudin.</p><p>Vivamus ac libero massa. Cras pellentesque volutpat dictum. Ut blandit dapibus augue, lobortis cursus mi blandit sed. Fusce vulputate hendrerit sapien id aliquet.</p>';

	$default_ad = array(
		'post_title' => 'My First Classified Ad',
		'post_name' => 'my-first-classified-ad',
		'post_content' => $description,
		'post_status' => 'publish',
		'post_type' => APP_POST_TYPE,
		'post_author' => 1,
	);

	// insert the default ad
	$post_id = wp_insert_post( $default_ad );

	//set the custom post type categories
	wp_set_post_terms( $post_id, $cat['term_id'], APP_TAX_CAT, false );

	//set the custom post type tags
	$new_tags = array( 'ad tag1', 'ad tag2', 'ad tag3' );
	wp_set_post_terms( $post_id, $new_tags, APP_TAX_TAG, false );


	// set some default meta values
	$ad_expire_date = appthemes_mysql_date( current_time( 'mysql' ), 30 );
	$advals['cp_sys_expire_date'] = $ad_expire_date;
	$advals['cp_sys_ad_duration'] = '30';
	$advals['cp_sys_ad_conf_id'] = '3624e0d2963459d2';
	$advals['cp_sys_userIP'] = '153.247.194.375';
	$advals['cp_daily_count'] = '0';
	$advals['cp_total_count'] = '0';
	$advals['cp_price'] = '250';
	$advals['cp_street'] = '153 Townsend St';
	$advals['cp_city'] = 'San Francisco';
	$advals['cp_state'] = 'California';
	$advals['cp_country'] = 'United States';
	$advals['cp_zipcode'] = '94107';
	$advals['cp_sys_total_ad_cost'] = '5.00';

	// now add the custom fields into WP post meta fields
	foreach ( $advals as $meta_key => $meta_value ) {
		add_post_meta( $post_id, $meta_key, $meta_value, true );
	}

	// set coordinates of new ad
	cp_update_geocode( $post_id, '', '37.779633', '-122.391762' );

	$url = appthemes_locate_template_uri( '/assets/images/classipress-first-ad-image.jpg' );

	// WordPress API for image uploads.
	require_once( ABSPATH . 'wp-admin/includes/admin.php' );

	// Get the actual file name (xyz.jpg).
	$filename = basename( $url );

	// Download the file to a temp location.
	$url = download_url( $url );

	// Check for download errors.
	if ( is_wp_error( $url ) ) {
		error_log( 'Error downloading Default Ad image: ' . $url->get_error_message() );
		return;
	}

	$file_array = array(
		'name'     => $filename,
		'tmp_name' => $url,
	);

	// Do the validation and put it in the media library.
	$image_id = media_handle_sideload( $file_array, $post_id, __( 'First Ad Cover', APP_TD ) );

	// If error storing permanently, unlink.
	if ( is_wp_error( $image_id ) ) {
		@unlink( $file_array['tmp_name'] );
		return;
	}

	update_post_meta( $post_id, '_app_media', array( $image_id ) );
}


// Create the default menus
function cp_default_menus() {
	$menus = array(
		'primary_top'     => __( 'Top Bar', APP_TD ),
		'primary'         => __( 'Header', APP_TD ),
		'secondary'       => __( 'Footer', APP_TD ),
		'theme_dashboard' => __( 'Dashboard', APP_TD ),
	);

	foreach( $menus as $location => $name ) {

		if ( has_nav_menu( $location ) ) {
			continue;
		}

		$menu_id = wp_create_nav_menu( $name );
		if ( is_wp_error( $menu_id ) ) {
			continue;
		}

		if ( 'primary_top' === $location ) {
			$page_id = CP_Add_New::get_id();
			$page    = get_post( $page_id );

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-type' => 'post_type',
				'menu-item-object' => 'page',
				'menu-item-object-id' => $page_id,
				'menu-item-title' => '<i class="fa fa-plus"></i> ' . __( 'Post an Ad', APP_TD ),
				'menu-item-url' => get_permalink( $page ),
				'menu-item-status' => 'publish'
			) );

			$user_item_id = wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  => '{{user_account}}',
				'menu-item-url'    => '#',
				'menu-item-status' => 'publish'
			) );

			if ( ! $user_item_id instanceof WP_Error ) {
				update_post_meta( $menu_id, 'user_menu_id', $user_item_id );

				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-type'      => 'post_type',
					'menu-item-object'    => 'page',
					'menu-item-object-id' => CP_User_Dashboard::get_id(),
					'menu-item-parent-id' => $user_item_id,
					'menu-item-title'     => '<i class="fa fa-list" aria-hidden="true"></i> ' . __( 'Listings', APP_TD ),
					'menu-item-url'       => CP_DASHBOARD_URL,
					'menu-item-status'    => 'publish',
				) );

				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-type'      => 'post_type',
					'menu-item-object'    => 'page',
					'menu-item-object-id' => CP_User_Profile::get_id(),
					'menu-item-parent-id' => $user_item_id,
					'menu-item-title'     => '<i class="fa fa-user" aria-hidden="true"></i> ' . __( 'Edit Profile', APP_TD ),
					'menu-item-url'       => CP_PROFILE_URL,
					'menu-item-status'    => 'publish',
				) );

				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title'     => '{{divider}}',
					'menu-item-url'       => '#',
					'menu-item-parent-id' => $user_item_id,
					'menu-item-status'    => 'publish',
				) );

				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title'     => '{{logout}}',
					'menu-item-url'       => '#',
					'menu-item-status'    => 'publish',
					'menu-item-parent-id' => $user_item_id,
				) );
			}

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  => '<button class="button hollow"><i class="fa fa-plus"></i>' . __( 'Signup', APP_TD ) . '</button>',
				'menu-item-url'    => '#register_url',
				'menu-item-status' => 'publish',
			) );

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  => '<button class="button">' . __( 'Login', APP_TD ) . '</button>',
				'menu-item-url'    => '#login_url',
				'menu-item-status' => 'publish',
			) );

		} elseif ( 'theme_dashboard' === $location ) {

			$page_ids = array(
				CP_User_Dashboard::get_id(),
				CP_User_Dashboard_Orders::get_id(),
				CP_User_Profile::get_id()
			);

			foreach ( $page_ids as $page_id ) {
				$page = get_post( $page_id );

				if ( ! $page ) {
					continue;
				}

				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-type' => 'post_type',
					'menu-item-object' => 'page',
					'menu-item-object-id' => $page_id,
					'menu-item-title' => $page->post_title,
					'menu-item-url' => get_permalink( $page ),
					'menu-item-status' => 'publish'
				) );
			}

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => __( 'Log Out', APP_TD ),
				'menu-item-url' => cp_logout_url(),
				'menu-item-status' => 'publish'
			) );

		} else {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => __( 'Home', APP_TD ),
				'menu-item-url' => home_url( '/' ),
				'menu-item-status' => 'publish'
			) );

			$page_ids = array(
				CP_Ads_Categories::get_id(),
				CP_Blog_Archive::get_id(),
			);

			foreach ( $page_ids as $page_id ) {
				$page = get_post( $page_id );

				if ( ! $page ) {
					continue;
				}

				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-type' => 'post_type',
					'menu-item-object' => 'page',
					'menu-item-object-id' => $page_id,
					'menu-item-title' => $page->post_title,
					'menu-item-url' => get_permalink( $page ),
					'menu-item-status' => 'publish'
				) );
			}
		}

		$locations = get_theme_mod( 'nav_menu_locations' );
		$locations[ $location ] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}
}

function _cp_get_default_sidebars_widgets_list() {

	$locations = get_nav_menu_locations();

	return array(
		// Footer.
		'sidebar_header' => array(
			'cp_468_ads' => array(),
		),
		// Homepage.
		'sidebar_main_content' => array(
			'cp_widget_listing_featured' => array(
				'header'      => __( 'Featured Listings', APP_TD ),
				'description' => __( 'Check out our featured listings', APP_TD ),
				'limit'       => 8,
			),
			'widget-ad-categories' => array(
				'header'      => __( 'Ad Categories', APP_TD ),
				'cols'        => '2',
				'extra_class' => 'widget',
			),
			'cp_widget_listing_latest' => array(
				'header'      => __( 'Latest Listings', APP_TD ),
				'description' => __( 'Check out our newest listings', APP_TD ),
				'limit'       => 6,
			),
			'cp_widget_post_latest' => array(
				'header'      => __( 'From the Blog', APP_TD ),
				'description' => __( 'Read our latest articles', APP_TD ),
				'limit'       => 3,
			),
		),
		// Archives
		'sidebar_main' => array(
			'cp_125_ads' => array(
				'title' => __( 'Sponsored Ads', APP_TD ),
				'ads' => CP_Widget_125_Ads::$ads,
			),
			'cp_facebook_like' => array(
				'title' => __( 'Facebook Friends', APP_TD ),
				'fid' => '137589686255438',
				'connections' => 10,
				'width' => 305,
				'height' => 290,
			),
			'ad_tag_cloud' => array(
				'title' => __( 'Tags', APP_TD ),
				'taxonomy' => 'ad_tag',
				'number' => 45,
			),
			'cp_recent_posts' => array(
				'title' => __( 'From the Blog', APP_TD ),
				'count' => 5,
			),
		),
		// Ad
		'sidebar_listing' => array(
			'cp_widget_listing_map' => array(),
			'text' => array(
				'title' => __( 'Share Ad', APP_TD ),
				'text'  => "<ul class='social-icons'>\n"
							. "<li>\n"
							. "[classipress_share_button icon='fa-facebook' share_url='https://www.facebook.com/sharer/sharer.php?u=']\n"
							. "</li>\n"
							. "<li>\n"
							. "[classipress_share_button icon='fa-twitter' share_url='https://twitter.com/intent/tweet?text=&url=']\n"
							. "</li>\n"
							. "<li>\n"
							. "[classipress_share_button icon='fa-pinterest' share_url='https://pinterest.com/pin/create/button/?url=']\n"
							. "</li>\n"
						. "</ul>\n",
			),
			'cp_widget_listing_author' => array(),
			'top_ads_overall' => array(
				'title' => __( 'Popular Ads Overall', APP_TD ),
				'number' => 10,
			),
			'widget-sold-ads' => array(
				'title' => __( 'Sold Ads', APP_TD ),
				'number' => 10,
			),
		),
		// Single ad page widgets.
		'sidebar_listing_content' => array(
			'cp_widget_listing_reveal_gallery' => array(
				'title'  => __( 'Photo Gallery', APP_TD ),
			),
			'cp_widget_listing_custom_fields' => array(
				'title'  => __( 'Additional Info', APP_TD ),
			),
			'cp_widget_listing_content' => array(
				'title'  => '',
			),
			'cp_widget_listing_comments' => array(
				'title'  => __( 'Comments', APP_TD ),
			),
		),
		// Page
		'sidebar_page' => array(
			'top_ads_overall' => array(
				'title' => __( 'Popular Ads Overall', APP_TD ),
				'number' => 10,
			),
		),
		// Blog
		'sidebar_blog' => array(
			'cp_recent_posts' => array(
				'title'   => __( 'Popular', APP_TD ),
				'orderby' => 'popularity',
			),
			'recent-comments' => array(
				'title'   => __( 'Comments', APP_TD ),
			),
			'categories' => array(
				'title' => __( 'Blog Categories', APP_TD ),
				'count' => 1,
			),
			'tag_cloud' => array(
				'title' => __( 'Tags', APP_TD ),
				'taxonomy' => 'post_tag',
			),
		),
		// Author
		'sidebar_author' => array(
			'widget-ad-categories' => array(
				'title' => __( 'Ad Categories', APP_TD ),
				'number' => 0,
			),
		),
		'sidebar_author_content' => array(
			'cp_widget_author_bio'      => array(),
		),
		'sidebar_author_tabbed_content' => array(
			'cp_widget_author_featured' => array(
				'header' => __( 'Featured', APP_TD ),
			),
			'cp_widget_author_listings' => array(
				'header' => __( 'Listings', APP_TD )
			),
			'cp_widget_author_posts'    => array(
				'header' => __( 'Posts', APP_TD ),
			),
		),
		// Dashboard
		'sidebar_user' => array(
			'nav_menu' => array(
				'title'    => __( 'Dashboard Menu', APP_TD ),
				'nav_menu' => ! empty( $locations[ 'theme_dashboard' ] ) ? $locations[ 'theme_dashboard' ] : 0,
			),
			'cp_widget_user_account_info' => array(
				'title'    => __( 'Account Info', APP_TD ),
			),
			'cp_widget_listing_author_stats' => array(
				'title' => __( 'Account Stats', APP_TD ),
			),
		),
		// Footer
		'sidebar_footer' => array(
			'text' => array(
				'title' => __( 'About Us', APP_TD ),
				'text' => 'This is just a text box widget so you can type whatever you want and it will automatically appear here. Pretty cool, huh?',
			),
			'top_ads_overall' => array(
				'title' => __( 'Most Popular', APP_TD ),
				'number' => 10,
			),
			'recent-posts' => array(
				'title' => __( 'Recent Posts', APP_TD ),
				'number' => 10,
			),
			'meta' => array(
				'title' => __( 'Meta', APP_TD ),
			),
		),
	);
}

function cp_default_widgets() {
	list( $args ) = get_theme_support( 'app-versions' );

	if ( ! get_option( $args['option_key'] ) ) {
		appthemes_install_widgets( _cp_get_default_sidebars_widgets_list() );
	}

}

function cp_default_logo() {
	list( $args ) = get_theme_support( 'app-versions' );

	$needs_update = ! get_option( $args['option_key'] ) || version_compare( get_option( $args['option_key'] ), '4.0.0', '<' );

	if ( get_custom_logo() || ! $needs_update ) {
		return;
	} elseif ( get_header_image() ) {
		$url = get_header_image();
	} else {
		$url = appthemes_locate_template_uri( '/assets/images/admin/cp_logo_black.png' );
	}

	// Set the header logo image.

	// WordPress API for image uploads.
	require_once( ABSPATH . 'wp-admin/includes/admin.php' );

	// Get the actual file name (xyz.jpg).
	$filename = basename( $url );

	// Download the file to a temp location.
	$url = download_url( $url );

	// Check for download errors.
	if ( is_wp_error( $url ) ) {
		error_log( 'Error downloading Default Logo image: ' . $url->get_error_message() );
		return;
	}

	$file_array = array(
		'name'     => $filename,
		'tmp_name' => $url,
	);

	// Do the validation and put it in the media library.
	$custom_logo_id = media_handle_sideload( $file_array, 0, __( 'ClassiPress Logo', APP_TD ) );

	// If error storing permanently, unlink.
	if ( is_wp_error( $custom_logo_id ) ) {
		@unlink( $file_array['tmp_name'] );
		return;
	}

	set_theme_mod( 'custom_logo', $custom_logo_id );
	set_theme_mod( 'header_text_site_title', 1 );
}

function cp_install_favicon( $favicon_url = '' ) {

	if ( get_option( 'site_icon' ) ) {
		return;
	}

	if ( ! $favicon_url ) {
		$favicon_url = appthemes_locate_template_uri( 'assets/images/favicon.png' );
	}

	// WordPress API for image uploads.
	require_once( ABSPATH . 'wp-admin/includes/admin.php' );
	$importer = new APP_Importer( '', array() );
	if ( $favicon_url && $importer ) {
		$attachment_id = $importer->process_attachment( $favicon_url, 0 );
		if ( is_wp_error( $attachment_id ) ) {
			error_log( 'Error downloading Favicon image: ' . $attachment_id->get_error_message() );
		} else if ( wp_get_attachment_image_src( $attachment_id, 'full' ) ) {
			update_option( 'site_icon', $attachment_id );
		}
	}
}
