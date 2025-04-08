<?php
/**
 * Theme functions file
 *
 * DO NOT MODIFY THIS FILE. Make a child theme instead: https://developer.wordpress.org/themes/advanced-topics/child-themes/
 *
 * @package ClassiPress
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */

// Constants
define( 'CP_VERSION', '4.2.8' );
define( 'CP_DB_VERSION', '3244' );

// Should reflect the WordPress version in the .testenv file.
define( 'CP_WP_COMPATIBLE_VERSION', '4.8' );

define( 'APP_POST_TYPE', 'ad_listing' );
define( 'APP_TAX_CAT', 'ad_cat' );
define( 'APP_TAX_TAG', 'ad_tag' );

define( 'CP_ITEM_LISTING', 'ad-listing' );
define( 'CP_ITEM_MEMBERSHIP', 'membership-pack' );

define( 'CP_PACKAGE_LISTING_PTYPE', 'package-listing' );
define( 'CP_PACKAGE_MEMBERSHIP_PTYPE', 'package-membership' );

define( 'CP_POST_STATUS_EXPIRED', 'expired' );


define( 'APP_TD', 'classipress' );

if ( version_compare( $wp_version, CP_WP_COMPATIBLE_VERSION, '<' ) ) {
	add_action( 'admin_notices', 'cp_display_version_warning' );
}

global $cp_options;

// Legacy variables - some plugins rely on them
$app_theme = 'ClassiPress';
$app_abbr = 'cp';
$app_version = CP_VERSION;
$app_db_version = CP_DB_VERSION;
$app_edition = 'Ultimate Edition';


// Framework
require_once( dirname( __FILE__ ) . '/framework/load.php' );
require_once( dirname( __FILE__ ) . '/theme-framework/load.php' );
require_once( APP_FRAMEWORK_DIR . '/admin/class-meta-box.php' );
require_once( APP_FRAMEWORK_DIR . '/includes/tables.php' );

APP_Mail_From::init();

// define the transients we use
$app_transients = array( 'cp_cat_menu' );

// define the db tables we use
$app_db_tables = array( 'cp_ad_fields', 'cp_ad_forms', 'cp_ad_geocodes', 'cp_ad_meta', 'cp_ad_pop_daily', 'cp_ad_pop_total' );

// Only register deprecated tables on older CP versions.
if ( get_option( 'cp_db_version' ) < 2221 ) {
	array_merge( $app_db_tables, array( 'cp_ad_packs', 'cp_coupons', 'cp_order_info' ) );
}

// register the db tables
foreach ( $app_db_tables as $app_db_table ) {
	scb_register_table( $app_db_table );
}
scb_register_table( 'app_pop_daily', 'cp_ad_pop_daily' );
scb_register_table( 'app_pop_total', 'cp_ad_pop_total' );

// Register app_geodata table with custom name in a backward compatibility
// purposes.
scb_register_table( 'app_geodata', 'cp_ad_geocodes' );

$load_files = array(
	'class-autoload.php',
	'checkout/load.php',
	'payments/load.php',
	'reports/load.php',
	'widgets/load.php',
	'stats/load.php',
	'recaptcha/load.php',
	'open-graph/load.php',
	'search-index/load.php',
	'admin/addons-mp/load.php',
	'plupload/app-plupload.php',
	'structured-data/class-structured-data.php',
	'geo/load.php',
	'options.php',
	'appthemes-functions.php',
	'actions.php',
	'categories.php',
	'comments.php',
	'menus.php',
	'core.php',
	'cron.php',
	'custom-forms.php',
	'deprecated.php',
	'enqueue.php',
	'emails.php',
	'functions.php',
	'hooks.php',
	'images.php',
	'packages.php',
	'payments.php',
	'profile.php',
	'search.php',
	'security.php',
	'stats.php',
	'views.php',
	'views-checkout.php',
	'widgets.php',
	'theme-support.php',
	'social.php',
	'utils.php',
	// Form Progress
	'checkout/form-progress/load.php',
);
appthemes_load_files( dirname( __FILE__ ) . '/includes/', $load_files );

CP_Autoload::add_class_map( array(
	'CP_Structured_Data'                  => dirname( __FILE__ ) . '/includes/theme-structured-data/class-structured-data.php',
	'CP_Schema_Type_Offer_Ad'             => dirname( __FILE__ ) . '/includes/theme-structured-data/class-schema-type-offer-ad.php',
	'CP_Schema_Type_Offer_Product'        => dirname( __FILE__ ) . '/includes/theme-structured-data/class-schema-type-offer-product.php',
	'CP_Schema_Type_Product_Ad'           => dirname( __FILE__ ) . '/includes/theme-structured-data/class-schema-type-product-ad.php',
	'CP_Schema_Type_PostalAddress_Ad'     => dirname( __FILE__ ) . '/includes/theme-structured-data/class-schema-type-postaladdress-ad.php',
	'CP_Schema_Type_GeoCoordinates_Ad'    => dirname( __FILE__ ) . '/includes/theme-structured-data/class-schema-type-geocoordinates-ad.php',
	'CP_Schema_Type_Organization_Ad'      => dirname( __FILE__ ) . '/includes/theme-structured-data/class-schema-type-organization-ad.php',
	'CP_Schema_Type_LocalBusiness_Ad'     => dirname( __FILE__ ) . '/includes/theme-structured-data/class-schema-type-localbusiness-ad.php',

	// Views.
	'CP_Ads_Search'                       => dirname( __FILE__ ) . '/includes/views/class-ads-search.php',

	// Widgets.
	'CP_Widget_125_Ads'                   => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-125-ads.php',
	'CP_Widget_Ad_Categories'             => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-ad-categories.php',
	'CP_Widget_Ad_Sub_Categories'         => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-ad-sub-categories.php',
	'CP_Widget_Ads_Tag_Cloud'             => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-ads-tag-cloud.php',
	'CP_Widget_Blog_Posts'                => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-blog-posts.php',
	'CP_Widget_Facebook'                  => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-facebook.php',
	'CP_Widget_Featured_Ads'              => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-featured-ads.php',
	'CP_Widget_Search'                    => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-search.php',
	'CP_Widget_Sold_Ads'                  => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-sold-ads.php',
	'CP_Widget_Top_Ads_Today'             => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-top-ads-today.php',
	'CP_Widget_Top_Ads_Overall'           => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-top-ads-overall.php',
	'CP_Widget_Listing_Latest'            => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-listing-latest.php',
	'CP_Widget_Listing_Featured_Slider'   => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-listing-featured-slider.php',
	'CP_Widget_Post_Latest'               => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-post-latest.php',
	'CP_Widget_Listing_Author_Stats'      => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-listing-author-stats.php',
	'CP_Widget_Account_Info'              => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-account-info.php',
	'CP_Widget_Listing_Reveal_Gallery'    => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-listing-reveal-gallery.php',
	'CP_Widget_Listing_Comments'          => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-listing-comments.php',
	'CP_Widget_Listing_Content'           => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-listing-content.php',
	'CP_Widget_Listing_Custom_Fields'     => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-listing-custom-fields.php',
	'CP_Widget_Listing_Author'            => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-listing-author.php',
	'CP_Widget_Listing_Map'               => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-listing-map.php',
	'CP_Widget_Author_Bio'                => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-author-bio.php',
	'CP_Widget_Author_Listings'           => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-author-listings.php',
	'CP_Widget_Author_Posts'              => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-author-posts.php',
	'CP_Widget_Author_Featured_Slider'    => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-author-featured-slider.php',
	'CP_Widget_Callout_Box'               => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-callout-box.php',
	'CP_Widget_468_Ads'                   => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-468-ads.php',
	'CP_Widget_Recent_Ads'                => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-recent-ads.php',
	'CP_Widget_Listing_Image_Slider'      => dirname( __FILE__ ) . '/includes/theme-widgets/class-widget-listing-image-slider.php',
	// Integrations.
	'CP_Amp'                              => dirname( __FILE__ ) . '/includes/integrations/amp/class-amp.php',
	'CP_WordPress_SEO'                    => dirname( __FILE__ ) . '/includes/integrations/wordpress-seo/class-wordpress-seo.php',
	'CP_Critic'                           => dirname( __FILE__ ) . '/includes/integrations/critic/class-critic.php',
	'CP_Foundation_Framework'             => dirname( __FILE__ ) . '/includes/integrations/foundation-framework/class-foundation-framework.php',
	'CP_WooCommerce'                      => dirname( __FILE__ ) . '/includes/integrations/woocommerce/class-woocommerce.php',
	// Misc.
	'CP_Shortcodes'                       => dirname( __FILE__ ) . '/includes/class-shortcodes.php',
	'CP_Tabbed_Sidebar'                   => dirname( __FILE__ ) . '/includes/class-tabbed-sidebar.php',
	'CP_Tiled_Sidebar'                    => dirname( __FILE__ ) . '/includes/class-tiled-sidebar.php',
	'CP_Customizer'                       => dirname( __FILE__ ) . '/includes/customizer/class-customizer.php',
	'APP_PHPColors'                       => dirname( __FILE__ ) . '/includes/customizer/class-phpcolors.php',
	'CP_Walker_Refine_Category_Checklist' => dirname( __FILE__ ) . '/includes/class-walker-refine-categories-checklist.php',
	// Admin.
	'APP_Taxonomy_Meta_Box'               => APP_FRAMEWORK_DIR . '/admin/class-taxonomy-meta-box.php',
	'CP_Ad_Listing_Category_Meta_Box'     => dirname( __FILE__ ) . '/includes/admin/class-ad-listing-category-meta-box.php',
) );

CP_Autoload::register();

$load_classes = array(
	'CP_Blog_Archive',
	'CP_Posts_Tag_Archive',
	'CP_Post_Single',
	'CP_Author_Archive',
	'CP_Ads_Tag_Archive',
	'CP_Ads_Search',
	'CP_Ads_Home',
	'CP_Ads_Categories',
	'CP_Add_New',
	'CP_Renew_Listing',
	'CP_Ad_Single',
	'CP_Edit_Item',
	'CP_Membership',
	'CP_User_Dashboard',
	'CP_User_Dashboard_Orders',
	'CP_User_Profile',
	'CP_Customizer',
	'CP_Structured_Data',
	'CP_Shortcodes',
	'CP_404_Page',
	'CP_Tiled_Sidebar',
	// Checkout
	'CP_Order',
	'CP_Membership_Form_Select',
	'CP_Membership_Form_Preview',
	'CP_Listing_Form_Select_Category',
	'CP_Listing_Form_Edit',
	'CP_Listing_Form_Details',
	'CP_Listing_Form_Preview',
	'CP_Listing_Form_Submit_Free',
	'CP_Gateway_Select',
	'CP_Gateway_Process',
	'CP_Order_Summary',
	// Widgets
	'CP_Widget_125_Ads',
	'CP_Widget_Ad_Categories',
	'CP_Widget_Ad_Sub_Categories',
	'CP_Widget_Ads_Tag_Cloud',
	'CP_Widget_Blog_Posts',
	'CP_Widget_Facebook',
	'CP_Widget_Featured_Ads',
	'CP_Widget_Search',
	'CP_Widget_Sold_Ads',
	'CP_Widget_Top_Ads_Today',
	'CP_Widget_Top_Ads_Overall',
	// Since 4.0.0
	'CP_Widget_Listing_Latest',
	'CP_Widget_Listing_Featured_Slider',
	'CP_Widget_Post_Latest',
	'CP_Widget_Listing_Author_Stats',
	'CP_Widget_Account_Info',
	'CP_Widget_Listing_Reveal_Gallery',
	'CP_Widget_Listing_Comments',
	'CP_Widget_Listing_Content',
	'CP_Widget_Listing_Custom_Fields',
	'CP_Widget_Listing_Author',
	'CP_Widget_Listing_Map',
	'CP_Widget_Author_Bio',
	'CP_Widget_Author_Listings',
	'CP_Widget_Author_Posts',
	'CP_Widget_Author_Featured_Slider',
	'CP_Widget_Callout_Box',
	'CP_Widget_468_Ads',
	'CP_Widget_Recent_Ads',
	'CP_Widget_Listing_Image_Slider',
	// Integrations
	'CP_Amp',
	'CP_Critic',
	'CP_WordPress_SEO',
	'CP_Foundation_Framework',
	'CP_WooCommerce',
);
appthemes_add_instance( $load_classes );


// Admin only
if ( is_admin() ) {
	require_once( APP_FRAMEWORK_DIR . '/admin/importer.php' );

	$load_files = array(
		'admin.php',
		'dashboard.php',
		'enqueue.php',
		'install.php',
		'importer.php',
		'listing-single.php',
		'listing-list.php',
		'categories-list.php',
		'options.php',
		'package-single.php',
		'package-list.php',
		'settings.php',
		'system-info.php',
		'updates.php',
	);
	appthemes_load_files( dirname( __FILE__ ) . '/includes/admin/', $load_files );

	$load_classes = array(
		'CP_Theme_Dashboard',
		'CP_Theme_Settings_General' => $cp_options,
		'CP_Theme_Settings_Emails' => $cp_options,
		'CP_Theme_Settings_Pricing' => $cp_options,
		'CP_Theme_System_Info',
		'CP_Listing_Package_General_Metabox',
		'CP_Membership_Package_General_Metabox',
		'CP_Listing_Attachments_Metabox',
		'CP_Listing_Media' => array( '_app_media', __( 'Attachments', APP_TD ), APP_POST_TYPE, 'normal', 'low' ),
		'CP_Listing_Author_Metabox',
		'CP_Listing_Info_Metabox',
		'CP_Listing_Custom_Forms_Metabox',
		'CP_Listing_Pricing_Metabox',
		'CP_Ad_Listing_Category_Meta_Box',
	);
	appthemes_add_instance( $load_classes );

	// integrate custom permalinks in WP permalinks page
	$settings = appthemes_get_instance('CP_Theme_Settings_General');
	add_action( 'admin_init', array( $settings, 'init_integrated_options' ), 10 );
} elseif ( defined( 'APP_TESTS_LIB' ) ) {
	require_once( APP_TESTS_LIB . '/../bootstrap.php' );
}


// Frontend only
if ( ! is_admin() ) {
	//cp_load_all_page_templates();
}


// Constants
define( 'CP_DASHBOARD_URL', get_permalink( CP_User_Dashboard::get_id() ) );
define( 'CP_DASHBOARD_ORDERS_URL', get_permalink( CP_User_Dashboard_Orders::get_id() ) );
define( 'CP_PROFILE_URL', get_permalink( CP_User_Profile::get_id() ) );
define( 'CP_EDIT_URL', get_permalink( CP_Edit_Item::get_id() ) );
define( 'CP_ADD_NEW_URL', get_permalink( CP_Add_New::get_id() ) );
define( 'CP_MEMBERSHIP_PURCHASE_URL', get_permalink( CP_Membership::get_id() ) );


// Set the content width based on the theme's design and stylesheet.
// Used to set the width of images and content. Should be equal to the width the theme
// is designed for, generally via the style.css stylesheet.
if ( ! isset( $content_width ) ) {
	$content_width = 500;
}

function cp_display_version_warning(){
	global $wp_version;

	$message = sprintf( __( 'ClassiPress version %1$s is not compatible with WordPress version %2$s. Correct work is not guaranteed. Please upgrade the WordPress at least to version %3$s or downgrade the ClassiPress theme.', APP_TD ), CP_VERSION, $wp_version, CP_WP_COMPATIBLE_VERSION );
	echo '<div class="error fade"><p>' . $message .'</p></div>';
}

appthemes_init();

// Shortcode function
function class_search_shortcode() {
    ob_start(); ?>
    <div class="container">
        <h2>Class Search</h2>
        <form method="GET" action="">
            <div class="search-bar">
		<input type="text" name="subject" placeholder="Subject (e.g., MATH)" value="<?php echo isset($_GET['subject']) ? esc_attr($_GET['subject']) : ''; ?>">
                <input type="text" name="course_number" placeholder="Course Number (e.g., 101)" value="<?php echo isset($_GET['course_number']) ? esc_attr($_GET['course_number']) : ''; ?>">
                <input type="date" name="start_date" value="<?php echo isset($_GET['start_date']) ? esc_attr($_GET['start_date']) : ''; ?>">
                <input type="date" name="end_date" value="<?php echo isset($_GET['end_date']) ? esc_attr($_GET['end_date']) : ''; ?>">
                <button type="submit">Search</button>
                <button type="reset" onclick="window.location.href=window.location.pathname">Reset</button>
            </div>
        </form>
        <div class="course-list">
            <?php
            global $wpdb;
            $query = "SELECT * FROM `cr_course` WHERE 1=1";
            
            $params = [];
            
	$subject = isset($_GET['subject']) ? trim($_GET['subject']) : '';
    $course_number = isset($_GET['course_number']) ? trim($_GET['course_number']) : '';
    $start_date = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
    $end_date = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';

	echo $subject;
	if (!empty($subject)) {
		$query .= " AND department LIKE '%" . $wpdb->esc_like($subject) . "%'";	
	}

    if (!empty($_GET['course_number'])) {
        $query .= " AND course_code LIKE '%" . $wpdb->esc_like($course_number) . "%'";
    }

    if (!empty($_GET['start_date'])) {
        $query .= " AND start_date >= '" . $wpdb->esc_like($start_date) . "'";
    }

    if (!empty($_GET['end_date'])) {
        $query .= " AND end_date <= '" . $wpdb->esc_like($end_date) . "'";
    }


	$query_final = !empty($params) ? $wpdb->prepare($query, ...$params) : $query;

	echo "<pre>SQL Query: " . esc_html($query_final) . "</pre>";

	// Execute query
	$courses = $wpdb->get_results($query_final);
            
            // Ensure proper use of prepare()
	    // $courses = !empty($params) ? $wpdb->get_results($wpdb->prepare($query, ...$params)) : $wpdb->get_results($query);

            if ($courses) {
                foreach ($courses as $course) {
                    echo '<div class="course-item" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">';
                    echo '<div>';
                    echo '<strong>' . esc_html($course->course_name) . '</strong><br>';
                    echo esc_html($course->course_code) . ' | ' . esc_html($course->department) . '<br>';
                    echo esc_html($course->credits) . ' Credits | Starts: ' . esc_html($course->start_date) . ' | Ends: ' . esc_html($course->end_date);
                    echo '<br><em>' . esc_html($course->description) . '</em>';
                    echo '<br>Seats Available: ' . (esc_html($course->capacity) - esc_html($course->seats_filled));
                    echo '</div>';
                    echo '<button class="add-btn" style="background-color: lightgreen; padding: 10px; border: none; cursor: pointer;">Add Course</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>No courses found.</p>';
            }
            ?>
        </div>
    </div>
    <style>
        .container {
            max-width: 800px;
            margin: auto;
            font-family: Arial, sans-serif;
        }
        .search-bar input, .search-bar button {
            margin: 5px;
            padding: 10px;
        }
        .course-list {
            margin-top: 20px;
        }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('class_search', 'class_search_shortcode');

