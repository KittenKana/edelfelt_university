<?php
/**
 * Dynamic Tabbed Sidebar.
 *
 * This class allows to turn any sidebar into a tabbed one. Everything it needs
 * it has inside this class.
 *
 * It uses a template part 'parts/tabs' which can be extended with a sidebar
 * name. For example 'parts/tabs-sidebar_author_tabbed_content'.
 *
 * The filter 'cp_tabbed_sidebar_tab_title' can be used to modify a tab title,
 * add icons or counts.
 *
 * NOTE: some widgets with pagination may be added in the sidebar, make sure
 * that the page can process URLs with possible page numbers.
 *
 * @package ClassiPress\Sidebars
 * @author AppThemes
 * @since 4.2.0
 */

/**
 * ClassiPress Dynamic Tabbed Sidebar class.
 *
 * @since 4.2.0
 */
class CP_Tabbed_Sidebar {
	/**
	 * The current sidebar id.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Current widget number.
	 *
	 * Used to set tab anchors in pagination links.
	 *
	 * @var string
	 */
	protected $current_widget = 0;

	/**
	 * An array of all registered widgets and their numbers.
	 *
	 * @var array
	 */
	protected $register = array();

	/**
	 * The Constructor.
	 *
	 * @param string $id The current sidebar id.
	 */
	public function __construct( $id ) {
		$this->id = $id;
	}

	/**
	 * Renders the actual tabbed sidebar.
	 */
	public function render() {
		global $wp_query, $wp_registered_sidebars;

		if ( ! isset( $wp_registered_sidebars[ $this->id ] ) ) {
			return;
		}

		ob_start();
		add_filter( 'dynamic_sidebar_params', array( $this, 'sidebar_params' ), 10 );
		add_filter( 'appthemes_pagenavi_args', array( $this, 'set_tab_anchor' ), 10 );
		dynamic_sidebar( $this->id );
		remove_filter( 'dynamic_sidebar_params', array( $this, 'sidebar_params' ), 10 );
		remove_filter( 'appthemes_pagenavi_args', array( $this, 'set_tab_anchor' ), 10 );
		$output = ob_get_clean();

		preg_match_all( '/<!--BEG_TAB-->(.*?)<!--END_TAB-->/s', $output, $matches );
		$tabs_html = $matches[1];
		$tabs      = array();

		foreach ( $tabs_html as $tab_html ) {
			preg_match( '/<!--\{\{(.*?)\}\}-->/s', $tab_html, $matches );
			$widget_id = $matches[1];

			preg_match( '/<!--BEG_TAB_TITLE-->(.*?)<!--END_TAB_TITLE-->/s', $tab_html, $matches );
			$title = ( isset( $matches[1] ) ) ? wp_filter_nohtml_kses( $matches[1] ) : $widget_id;

			$tab_id = $this->id . '-' . $this->register[ $widget_id ];

			$tabs[ $tab_id ] = array(
				'title'     => apply_filters( 'cp_tabbed_sidebar_tab_title', $title, $widget_id, $tab_id, $this->id ),
				'content'   => $tab_html,
				'widget_id' => $widget_id,
			);
		}

		// Set query var so we can pass into template.
		$wp_query->set( 'app_sidebar_tabs', $tabs );

		appthemes_get_template_part( 'parts/tabs', $this->id );
	}

	/**
	 * Adds tokens into the sidebar params for further replace.
	 *
	 * This filter applies to each widget in sidebar, but not each widget may
	 * use 'before_widget' parameter.
	 *
	 * Widget with not implemented 'before_widget' parameter will no create a
	 * tab and will be missing.
	 *
	 * @param array $params Sidebar parameters.
	 * @return array
	 */
	public function sidebar_params( $params ) {

		if ( $params[0]['id'] !== $this->id ) {
			return $params;
		}

		// Current widget needs to set tab anchor.
		$this->current_widget++;
		// Register needs to find out the tab id by widget id in the render().
		$this->register[ $params[0]['widget_id'] ] = $this->current_widget;

		$params[0]['before_widget'] = '<!--BEG_TAB--><!--{{' . $params[0]['widget_id'] . '}}-->' . $params[0]['before_widget'];
		$params[0]['after_widget'] .= '<!--END_TAB-->';
		$params[0]['before_title']  = '<!--BEG_TAB_TITLE-->' . $params[0]['before_title'];
		$params[0]['after_title']  .= '<!--END_TAB_TITLE-->';

		return $params;
	}

	/**
	 * Adds a tab anchor to the end of pagination link.
	 *
	 * @param array $args Pagination args.
	 * @return string
	 */
	public function set_tab_anchor( $args ) {
		if ( ! empty( $this->current_widget ) ) {
			$args['add_fragment'] = '#' . $this->id . '-' . $this->current_widget;
		}

		return $args;
	}

}
