<?php
/**
 *  Marketplace add-ons main class.
 *
 * @package Components\Add-ons
 */

/**
 * The class responsible for displaying the Add-ons browser.
 */
class APP_Addons_List_Table extends WP_List_Table {

	/**
	 * Additional arguments for the list table.
	 * @var string
	 */
	protected $args;

	/**
	 * The list table page slug.
	 * @var string
	 */
	protected $menu_parent;

	/**
	 * The list table menu parent.
	 * @var string
	 */
	protected $page_slug;

	/**
	 * The errors returned during the items request.
	 * @var object
	 */
	protected $error;

	/**
	 * The MarketPlace REST API Request object.
	 *
	 * @var APP_Addons_MP_Request
	 */
	protected $request;

	/**
	 * Constructor.
	 *
	 * Overrides the list class to display AppThemes add-ons.
	 *
	 * @param string $page_slug   The page slug name.
	 * @param [type] $menu_parent The menu parent name.
	 * @param array  $args        Additional args for the list.
	 */
	public function __construct( $page_slug, $menu_parent, $args = array() ) {

		$defaults = array(
			'tab'             => 'new',
			'page'            => 1,
			'addons_per_page' => 30,
		);
		$this->args = wp_parse_args( $args, $defaults );

		$this->page_slug   = $page_slug;
		$this->menu_parent = $menu_parent;
		$this->request     = new APP_Addons_MP_Request( $this->args );

		parent::__construct( $this->args );

		$this->prepare_items();
	}

	/**
	 * Prepares the items before they are displayed.
	 *
	 * @uses apply_filters() Calls 'appthemes_addons_mp_tabs_<screen_id>'
	 */
	public function prepare_items() {
		$args  = array_map( 'esc_attr', $_GET ); // Input var okay.
		// Get items from cache (if not expired) or from the REST API directly.
		$response    = $this->request->fetch_mp_items( $args );
		$this->items = $response['items'];

		$this->set_pagination_args( array(
			'total_items' => $response['total'],
			'per_page'    => $this->args['addons_per_page'],
		) );

		$this->request->update_counters();
	}

	/**
	 * Retrieves available tabs array
	 *
	 * @return array An array of tab names keyed with a tab action.
	 */
	protected function get_tabs() {
		$raw_filters = $this->request->fetch_mp_filters();
		$tabs_filter = wp_list_filter( $raw_filters, array( 'name' => 'view' ) );
		$tabs        = $tabs_filter[0]['values'];

		foreach ( $tabs as &$tab ) {
			$tab = translate_with_gettext_context( $tab, 'MarketPlace Addons page translation', APP_TD );
		}

		/**
		 * Filters the list of the Add-ons browser tabs.
		 *
		 * The dynamic portion of the hook name refers to the current screen id.
		 *
		 * @param array $tabs An array of tabs names keyed with their slugs.
		 */
		$tabs = apply_filters( "appthemes_addons_mp_tabs_{$this->screen->id}", $tabs );

		// If a non-valid menu tab has been selected, And it's not a non-menu action.
		if ( empty( $this->args['tab'] ) || ( ! isset( $tabs[ $this->args['tab'] ] ) ) ) {
			$tab = key( $tabs );
		}

		return $tabs;
	}

	/**
	 * Outputs the available Add-ons tabs.
	 */
	protected function get_views() {
		$display_tabs = array();

		$admin_url = strpos( $this->menu_parent, '.php' ) === false ? self_admin_url( 'admin.php' ) : self_admin_url( $this->menu_parent );

		$tabs = $this->get_tabs();

		foreach ( (array) $tabs as $action => $text ) {

			$counter = get_option( "appthemes_addons_mp_{$action}_items_counter" );

			if ( ! empty( $counter ) ) {
				$counter = count( $counter );
				$text .= ' <span class="update-plugins count-' . $counter . ' app-mp-tab-count-' . esc_attr( $action ) . '"><span class="plugin-count" aria-hidden="true">' . $counter . '</span></span>';
			}

			$class = ( $action === $this->args['tab'] ) ? ' current' : '';
			$href = add_query_arg( array( 'page' => $this->page_slug, 'tab' => $action ), $admin_url );
			$display_tabs[ $action ] = "<a href='" . esc_url( $href ) . "' class='" . esc_attr( $class ) . "'>" . $text . '</a>';
		}

		return $display_tabs;
	}

	/**
	 * Outputs the Add-ons filters.
	 */
	protected function get_filters() {
		$filters = '';

		// Get all available filters.
		if ( ! ( $filter_list = $this->request->get_filter_list() ) ) {
			return $filters;
		}

		foreach ( $filter_list as $name => $values ) {
			$filter = array(
				'name'    => $name,
				'title'   => '',
				'type'    => 'select',
				'values'  => $values,
				'default' => ! empty( $this->args['defaults'][ $name ] ) ? $this->args['defaults'][ $name ] : '',
				'extra'   => array( 'class' => 'app-mp-addons-filter' ),
			);

			$filters .= html( 'li', scbForms::input( $filter, $_GET ) );
		}

		return $filters;
	}

	/**
	 * Override parent views so we can use the filter bar display.
	 *
	 * @uses do_action() Calls 'appthemes_addons_mp_before_table'
	 */
	public function views() {
		$views = $this->get_views();

		/** This filter is documented in wp-admin/inclues/class-wp-list-table.php */
		$views = apply_filters( "views_{$this->screen->id}", $views );
?>
		<div class="wp-filter app-mp-addons">
			<ul class="filter-links">
				<?php
				if ( ! empty( $views ) ) {
					foreach ( $views as $class => $view ) {
						$class = esc_attr( $class );
						$views[ $class ] = "\t<li class='addons-install-" . esc_attr( $class ) ."'>$view";
					}

					echo implode( " </li>\n", $views ) . "</li>\n";
				}
				?>
			</ul>

			<?php $this->search_form(); ?>
		</div>
		<?php
		/**
		 * Fires before the add-ons mp table is displayed.
		 *
		 * Recommended for marketplace marketing campaigns.
		 */
		do_action( 'appthemes_addons_mp_before_table' );
		?>
<?php
	}

	/**
	 * Outputs all the Add-ons page content.
	 */
	public function display() {
		$singular = $this->_args['singular'];

		$data_attr = '';

		if ( $singular ) {
			$data_attr = " data-wp-lists='list:$singular'";
		}

		$this->display_tablenav( 'top' );
?>
		<div class="wp-list-table app-mp-addons <?php echo esc_attr( implode( ' ', $this->get_table_classes() ) ); ?>">

			<div id="the-list" <?php echo esc_attr( $data_attr ); ?> >
				<?php $this->display_rows_or_placeholder(); ?>
			</div>
		</div>
<?php
		$this->display_tablenav( 'bottom' );

		$this->request->update_counters();
	}

	/**
	 * Outputs the pagination bar.
	 *
	 * @param string $which The position for the pagination bar: 'top' or 'bottom'.
	 */
	protected function display_tablenav( $which ) {

		if ( 'top' === $which ) :
			wp_referer_field();
		?>

			<div class="tablenav top">
				<div class="alignleft actions">
					<?php
					/**
					 * Fires before the add-ons mp table header pagination is displayed.
					 */
					do_action( 'appthemes_addons_mp_table_header' ); ?>
				</div>
				<?php $this->pagination( $which ); ?>
				<br class="clear" />
			</div>

		<?php else : ?>

			<div class="tablenav bottom">
				<?php $this->pagination( $which ); ?>
				<br class="clear" />
			</div>

		<?php
		endif;
	}

	/**
	 * Retrieve a list of CSS classes to be used on the table listing.
	 *
	 * @return array The list of CSS classes.
	 */
	protected function get_table_classes() {
		return array( 'widefat', $this->_args['plural'] );
	}

	/**
	 * Outputs the Add-ons search form.
	 */
	private function search_form() {

		if ( isset( $_REQUEST['s'] ) ) { // Input var okay.
			$term = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ); // Input var okay.
		} else {
			$term = '';
		}
?>
		<form id="app-addons-search" class="search-form search-plugins" method="get" action="">
			<ul class="filter-links addons-filter">
				<?php echo $this->get_filters(); ?>
				<li>
					<div class="app-mp-addons-filter">
						<span class="screen-reader-text"><?php echo __( 'Search Add-ons', APP_TD ); ?></span>
						<input type="search" name="s" value="<?php echo esc_attr( $term ) ?>" class="wp-filter-search" placeholder="<?php echo esc_attr__( 'Search Add-ons', APP_TD ); ?>">
					</div>
					<input type="submit" name="" id="search-submit" class="button screen-reader-text" value="<?php echo esc_attr__( 'Search Add-ons', APP_TD ); ?>">
				</li>
			</ul>
			<?php appthemes_pass_request_var( 'page' ); ?>
			<?php appthemes_pass_request_var( 'tab' ); ?>
			<?php appthemes_pass_request_var( 'post_type' ); ?>
		</form>
<?php
	}

	/**
	 * Outputs a given add-on using custom markup.
	 *
	 * @uses apply_filters() Calls 'appthemes_addons_mp_markup_<screen_id>'.
	 * @uses do_action()	 Calls 'appthemes_addons_mp_addon_after'.
	 *
	 * @param object $addon The add-on object to output.
	 */
	public function single_row( $addon ) {
		$badge = '';

		if ( 'new' === $this->args['tab'] ) {
			$newest_published_item = get_option( 'appthemes_addons_mp_newest_published_item' );
			$counter               = get_option( "appthemes_addons_mp_{$this->args['tab']}_items_counter" );

			if ( ! empty( $counter ) && in_array( $addon->id, $counter ) ) {
				$badge = '<span class="app-mp-badge-new">' . __( 'New', APP_TD ) . '</span>';
			}

			if ( $addon->id > $newest_published_item ) {
				update_option( 'appthemes_addons_mp_newest_published_item', $addon->id );
			}

		} elseif ( 'updated' === $this->args['tab'] ) {
			$newest_updated_item = get_option( 'appthemes_addons_mp_newest_updated_item' );
			$counter             = get_option( "appthemes_addons_mp_{$this->args['tab']}_items_counter" );

			if ( ! empty( $counter ) && in_array( $addon->id, $counter ) ) {
				$badge = '<span class="app-mp-badge-updated">' . __( 'Updated', APP_TD ) . '</span>';
			}

			if ( $addon->last_updated_t > $newest_updated_item ) {
				update_option( 'appthemes_addons_mp_newest_updated_item', $addon->last_updated_t );
			}
		}

		ob_start();
?>
		<div class="plugin-card">
			<div class="plugin-card-top">
				<a href="<?php echo esc_url( $addon->link ); ?>" target="_new" class="thickbox plugin-icon"><?php echo $addon->image; ?></a>
				<div class="name-top">
					<div class="name column-name">
						<?php echo $badge; ?>
						<h4><?php echo $addon->title; ?></h4>
					</div>
					<div class="action-links price-meta">
						<ul class="plugin-action-buttons price">
							<li><?php echo $addon->price; ?></li>
						</ul>
					</div>
				</div>
				<div class="desc column-description">
					<p><?php echo $addon->description; ?></p>
					<p class="authors">
						<cite><?php echo sprintf( __( 'By %1$s', APP_TD ), $addon->author_link ); ?> </cite>
					</p>
				</div>
			</div>
			<div class="plugin-card-bottom">
				<div class="vers column-rating">
					<?php wp_star_rating( array( 'rating' => (double) $addon->rating, 'number' => $addon->votes ) ); ?>
					<span class="num-ratings">(<?php echo number_format_i18n( $addon->votes ); ?>)</span>
				</div>
				<div class="column-updated">
					<strong><?php echo __( 'Last Updated:', APP_TD ); ?></strong>
					<span title="<?php echo esc_attr( $addon->last_updated ); ?>"><?php echo sprintf( __( '%1$s ago', APP_TD ), $addon->last_updated_h ); ?></span>
				</div>
				<div class="column-category">
					<strong><?php echo __( 'Category:', APP_TD ); ?></strong> <span title="<?php echo esc_attr( $addon->category_desc ); ?>"><?php echo $addon->category_desc; ?></span>
				</div>
				<div class="column-requirements">
					<strong><?php echo __( 'Compatibilities:', APP_TD ); ?></strong> <span title="<?php echo esc_attr( $addon->compats ); ?>"><?php echo $addon->compats; ?></span>
				</div>
			</div>
			<?php
			/**
			 * Fires after the all the content for each plugin is displayed.
			 *
			 * Recommended for add-ons marketing campaigns: discounts codes, etc.
			 */
			do_action( 'appthemes_addons_mp_addon_after', $addon ); ?>
		</div>
<?php
		$output = ob_get_clean();

		/**
		 * Filters the generated HTML for each single Add-on.
		 *
		 * The dynamic portion of the hook name refers to the current screen id.
		 *
		 * @param string $output A generated HTML.
		 * @param object $addon  An Add-on instance.
		 */
		echo apply_filters( "appthemes_addons_mp_markup_{$this->screen->id}", $output, $addon );
	}

	/**
	 * Outputs the no items message.
	 */
	public function no_items() {

		if ( isset( $this->error ) ) {
			$message = $this->error->get_error_message() . '<p class="hide-if-no-js"><a href="#" class="button" onclick="document.location.reload(); return false;">' . __( 'Try again', APP_TD ) . '</a></p>';
		} else {
			$message = __( 'No add-ons match your request.', APP_TD );
		}
		echo '<div class="no-plugin-results">' . $message . '</div>';
	}

}
