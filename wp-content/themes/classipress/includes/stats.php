<?php
/**
 * Tracking page views. Stats module implementation.
 *
 * @package ClassiPress\Stats
 * @author  AppThemes
 * @since   ClassiPress 3.0
 */

add_filter( 'cp_dashboard_listings_columns', '_cp_add_dashboard_views_column' );
add_action( 'cp_dashboard_listings_column_views', '_cp_render_dashboard_views_column' );
add_action( 'cp_listing_item_footer', 'cp_do_loop_stats' );
add_action( 'cp_post_single_hero_meta', 'cp_add_single_hero_meta' );
add_action( 'cp_listing_single_hero_meta', 'cp_add_single_hero_meta' );
add_action( 'cp_listing_single_status_meta', 'cp_add_single_hero_meta' );
add_action( 'wp', 'cp_cache_stats' );
add_action( 'cp_single_template_footer', 'cp_add_reset_stats_link' );

add_action( 'appthemes_before_post', '_cp_stats_update' );
add_action( 'appthemes_before_ad_listing', '_cp_stats_update' );

function _cp_stats_update() {
	if ( ! is_singular( array( 'post', APP_POST_TYPE ) ) || ! current_theme_supports( 'app-stats' ) ) {
		return;
	}

	appthemes_stats_update( get_the_ID() );
}

/**
 * Adds Listing Dashboard column for Views count.
 *
 * Applies to `cp_dashboard_listings_columns` filter.
 *
 * @since 4.0.0
 *
 * @param array $columns Columns array ($id => $title).
 *
 * @return array
 */
function _cp_add_dashboard_views_column( $columns ) {

	if ( ! current_theme_supports( 'app-stats' ) ) {
		return $columns;
	}

	$list = new APP_List();

	foreach ( $columns as $id => $column ) {
		$list->add( $id, $column );
	}

	if ( $list->contains( 'actions' ) ) {
		$list->add_before( 'actions', 'views', '<i class="fa fa-facebook" aria-hidden="true" title="' . __( 'Views', APP_TD ) . '"></i>' );
	} else {
		$list->add( 'views', '<i class="fa fa-eye" aria-hidden="true" title="' . __( 'Views', APP_TD ) . '"></i>'  );
	}

	return $list->get_all();
}

/**
 * Adds Views count for given listing on the Dashboard.
 *
 * Triggered on `cp_dashboard_listings_column_views` action.
 *
 * @since 4.0.0
 *
 * @param WP_Post $listing
 *
 * @return void
 */
function _cp_render_dashboard_views_column( $listing ) {

	if ( ! current_theme_supports( 'app-stats' ) ) {
		return false;
	}

	echo appthemes_get_stats_by( $listing->ID, 'total' );
}


/**
 * Adds the stats after the ad listing and blog post content.
 *
 * @since 3.1
 *
 * @return void
 */
function cp_do_loop_stats() {
	global $post, $cp_options;

	if ( ! is_archive( APP_POST_TYPE ) && ! is_search() ) {
		return;
	}

	if ( APP_POST_TYPE !== $post->post_type ) {
		return;
	}

	if ( ! $cp_options->ad_stats_all || ! current_theme_supports( 'app-stats' ) ) {
		return;
	}
?>
	<span class="stats"><?php appthemes_stats_counter( $post->ID ); ?></span>
<?php
}


/**
 * Adds the post meta to the content cover meta section.
 * @since 4.0.0
 *
 * @return void
 */
function cp_add_single_hero_meta() {
	global $post, $cp_options;

	if ( ! is_singular( array( 'post', APP_POST_TYPE ) ) ) {
		return;
	}

	if ( $cp_options->ad_stats_all && current_theme_supports( 'app-stats' ) ) {
		$class = 'cp_listing_single_status_meta' === current_action() ? 'label m-b-1' : 'sep-l';
		?>
		<span class="post-stats <?php echo esc_attr( $class ); ?>">
			<i class="fa fa-bar-chart" aria-hidden="true"></i>  <?php appthemes_stats_counter( $post->ID ); ?>
		</span>
	<?php }
}

/**
 * Collects stats if are enabled, limits db queries.
 * @since 3.1.8
 *
 * @return void
 */
function cp_cache_stats() {
	global $cp_options;

	if ( is_singular( array( APP_POST_TYPE, 'post' ) ) ) {
		return;
	}

	if ( ! $cp_options->ad_stats_all || ! current_theme_supports( 'app-stats' ) ) {
		return;
	}

	add_action( 'appthemes_before_ad_listing_loop', 'appthemes_collect_stats' );
	//add_action( 'appthemes_before_search_loop', 'appthemes_collect_stats' );
	add_action( 'appthemes_before_post_loop', 'appthemes_collect_stats' );
}

/**
 * Inserts link for admin to reset stats of an ad or post.
 * @since 3.3
 *
 * @return void
 */
function cp_add_reset_stats_link() {
	global $cp_options;

	if ( ! is_singular( array( APP_POST_TYPE, 'post' ) ) || ! $cp_options->ad_stats_all || ! current_theme_supports( 'app-stats' ) ) {
		return;
	}

	appthemes_reset_stats_link();
}

/**
 * Displays list of overall popular ads/posts.
 *
 * @param string $post_type
 * @param int $limit
 *
 * @return void
 */
function cp_todays_overall_count_widget( $post_type, $limit ) {

	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => $limit,
		'paged'          => 1,
		'no_found_rows'  => true,
		'post_status'    => 'publish',
	);

	$popular = new CP_Popular_Posts_Query( $args );

	echo '<ul class="pop">';

	// must be overall views
	if ( $popular->have_posts() ) {

		while ( $popular->have_posts() ) {
			$popular->the_post();
			echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a> (' . appthemes_get_stats_by( get_the_ID(), 'total' ) . '&nbsp;' . __( 'views', APP_TD ) . ')</li>';
		}

	} else {

		echo '<li>' . __( 'No ads viewed yet.', APP_TD ) . '</li>';

	}

	echo '</ul>';

	wp_reset_postdata();
}


/**
 * Displays list of today's popular ads/posts.
 *
 * @param string $post_type
 * @param int $limit
 *
 * @return void
 */
function cp_todays_count_widget( $post_type, $limit ) {

	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => $limit,
		'paged'          => 1,
		'no_found_rows'  => true,
		'post_status'    => 'publish',
	);

	$popular = new CP_Popular_Posts_Query( $args, 'today' );

	echo '<ul class="pop">';

	// must be views today
	if ( $popular->have_posts() ) {

		while ( $popular->have_posts() ) {
			$popular->the_post();
			echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a> (' . appthemes_get_stats_by( get_the_ID(), 'today' ) . '&nbsp;' . __( 'views', APP_TD ) . ')</li>';
		}

	} else {

		echo '<li>' . __( 'No ads viewed yet.', APP_TD ) . '</li>';
	}

	echo '</ul>';

	wp_reset_postdata();
}

/**
 * Query popular ads & posts.
 */
class CP_Popular_Posts_Query extends WP_Query {

	public $stats;
	public $stats_table;
	public $today_date;

	function __construct( $args = array(), $stats = 'total' ) {
		global $wpdb;

		$this->stats = $stats;
		$this->stats_table = ( $stats == 'today' ) ? $wpdb->cp_ad_pop_daily : $wpdb->cp_ad_pop_total;
		$this->today_date = date( 'Y-m-d', current_time( 'timestamp' ) );

		$defaults = array(
			'post_type' => APP_POST_TYPE,
			'post_status' => 'publish',
			'paged' => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
			'suppress_filters' => false,
		);
		$args = wp_parse_args( $args, $defaults );

		$args = apply_filters( 'cp_popular_posts_args', $args );

		add_filter( 'posts_join', array( $this, 'posts_join' ) );
		add_filter( 'posts_where', array( $this, 'posts_where' ) );
		add_filter( 'posts_orderby', array( $this, 'posts_orderby' ) );

		parent::__construct( $args );

		// remove filters to don't affect any other queries
		remove_filter( 'posts_join', array( $this, 'posts_join' ) );
		remove_filter( 'posts_where', array( $this, 'posts_where' ) );
		remove_filter( 'posts_orderby', array( $this, 'posts_orderby' ) );
	}

	function posts_join( $sql ) {
		global $wpdb;
		return $sql . " INNER JOIN $this->stats_table ON ($wpdb->posts.ID = $this->stats_table.postnum) ";
	}

	function posts_where( $sql ) {
		global $wpdb;
		$sql = $sql . " AND $this->stats_table.postcount > 0 ";

		if ( $this->stats == 'today' ) {
			$sql .= " AND $this->stats_table.time = '$this->today_date' ";
		}

		if ( $this->get( 'date_start' ) ) {
			$sql .= " AND $wpdb->posts.post_date > '" . $this->get( 'date_start' ) . "' ";
		}

		return $sql;
	}

	function posts_orderby( $sql ) {
		return "$this->stats_table.postcount DESC";
	}

}
