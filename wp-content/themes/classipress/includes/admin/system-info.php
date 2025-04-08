<?php
/**
 * System Information.
 *
 * @package ClassiPress\Admin\SystemInfo
 * @author  AppThemes
 * @since   ClassiPress 3.3
 */


class CP_Theme_System_Info extends APP_System_Info {


	function __construct( $args = array(), $options = null ) {

		parent::__construct( $args, $options );

		add_action( 'admin_notices', array( $this, 'admin_tools' ) );
	}


	public function admin_tools() {

		if ( ! empty( $_POST['cp_tools']['flush_cache'] ) ) {
			$message = cp_flush_all_cache();
			echo scb_admin_notice( $message );
		}

		if ( ! empty( $_POST['cp_tools']['delete_tables'] ) ) {
			cp_delete_db_tables();
		}

		if ( ! empty( $_POST['cp_tools']['delete_options'] ) ) {
			cp_delete_all_options();
		}

	}


	function form_handler() {
		if ( empty( $_POST['action'] ) || ! $this->tabs->contains( $_POST['action'] ) ) {
			return;
		}

		check_admin_referer( $this->nonce );

		if ( ! empty( $_POST['cp_tools'] ) ) {
			return;
		} else {
			parent::form_handler();
		}
	}


	protected function init_tabs() {
		global $wp_registered_sidebars, $wp_registered_widgets;
		parent::init_tabs();

		$this->tabs->add( 'cp_tools', __( 'Advanced', APP_TD ) );

		$this->tab_sections['cp_tools']['cache'] = array(
			'title' => '',
			'fields' => array(
				array(
					'title' => __( 'Theme Cache', APP_TD ),
					'type' => 'submit',
					'name' => array( 'cp_tools', 'flush_cache' ),
					'extra' => array(
						'class' => 'button-secondary'
					),
					'value' => __( 'Flush ClassiPress Cache', APP_TD ),
					'desc' => __( 'Empty anything that ClassiPress has stored in cache (i.e. category drop-down menu, home page directory structure, etc).', APP_TD ),
				),
			),
		);


		$this->tab_sections['cp_tools']['uninstall'] = array(
			'title' => __( 'Uninstall Theme', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Database Tables', APP_TD ),
					'type' => 'submit',
					'name' => array( 'cp_tools', 'delete_tables' ),
					'extra' => array(
						'class' => 'button-secondary',
						'onclick' => 'return cp_confirmBeforeDeleteTables();',
					),
					'value' => __( 'Delete ClassiPress Tables', APP_TD ),
					'desc' => __( 'You will lose any custom fields, forms, and ad packs that you have created.', APP_TD ),
				),
				array(
					'title' => __( 'Config Options', APP_TD ),
					'type' => 'submit',
					'name' => array( 'cp_tools', 'delete_options' ),
					'extra' => array(
						'class' => 'button-secondary',
						'onclick' => 'return cp_confirmBeforeDeleteOptions();',
					),
					'value' => __( 'Delete ClassiPress Options', APP_TD ),
					'desc' => __( 'All values saved on the settings, pricing, gateways, etc admin pages will be erased from the wp_options table.', APP_TD ),
				),
			),
		);

		$default_widgets = array();
		$registered      = array();
		$to_restore      = array();
		$sections_list   = new APP_List();

		array_map( array( $sections_list, 'add' ), array_keys( $this->tab_sections['info'] ), $this->tab_sections['info'] );

		$sidebars_widgets_default = _cp_get_default_sidebars_widgets_list();
		$sidebars_widgets_actual  = get_option( 'sidebars_widgets' );

		$registered_id      = array_keys( $wp_registered_widgets );
		$registered_id_base = array_map( '_get_widget_id_base', $registered_id );

		foreach ( $registered_id_base as $key => $id_base ) {
			$registered[ $id_base ][] = $registered_id[ $key ];
		}

		foreach ( $sidebars_widgets_default as $sidebar_id => $widgets ) {

			if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
				$sidebar_title = $wp_registered_sidebars[ $sidebar_id ]['name'];
				$sidebar_desc  = '&#10004;';
			} else {
				$sidebar_title = $sidebar_id;
				$sidebar_desc  = html( 'span class="text-error"', '&#10007; ' . __( 'Could not find such sidebar among registered!', APP_TD ) );
			}

			$default_widgets[] = array(
				'title' => html( 'strong', $sidebar_title ),
				'type'  => 'text',
				'name'  => array( 'system_info', 'default_widgets' ),
				'tip'   => __( 'Check whether sidebar has been registered', APP_TD ),
				'desc'  => $sidebar_desc,
				'extra' => array(
					'style' => 'display: none;',
				),
			);

			$actual_widgets = $sidebars_widgets_actual[ $sidebar_id ];

			foreach ( $widgets as $widget_id => $args ) {

				if ( ! isset( $registered[ $widget_id ] ) ) {
					$widget_title = $widget_id;
					$widget_desc  = html( 'span class="text-error"', '&#10007; ' . __( 'Could not find such widget among registered!', APP_TD ) );
				} else {
					$intersect = array_intersect( $actual_widgets, $registered[ $widget_id ] );
					if ( empty( $intersect ) ) {
						$widget_title = $widget_id;
						$widget_desc  = html( 'span class="text-error"', '&#10007; ' . __( 'Could not find such widget on sidebar!', APP_TD ) );
						$to_restore[ $sidebar_id ][ $widget_id ] = $args;
					} else {
						$intersect = array_shift( $intersect );
						$widget_title = $wp_registered_widgets[ $intersect ]['name'];
						$widget_desc  = '&#10004;';
					}
				}

				$default_widgets[] = array(
					'title' => '&nbsp;&nbsp;&nbsp;&nbsp;' . $widget_title,
					'type'  => 'text',
					'name'  => array( 'system_info', 'default_widgets' ),
					'tip'   => __( 'Check whether default widget has been added to sidebar', APP_TD ),
					'desc'  => $widget_desc,
					'extra' => array(
						'style' => 'display: none;',
					),
				);

			}
		}

		$restore_widgets_link = html( 'a', array(
			'class' => 'button secondary',
			'href'  => wp_nonce_url( add_query_arg( null, null ), 'restore_widgets', '_restore_widgets_nonce' ),
		), __( 'Restore', APP_TD ) );

		$default_widgets[] = array(
			'title' => __( 'Restore removed widgets', APP_TD ),
			'type'  => 'text',
			'name'  => array( 'system_info', 'default_widgets' ),
			'tip'   => __( 'Restore theme default widgets if they accidentally deleted', APP_TD ),
			'desc'  => $restore_widgets_link,
			'extra' => array(
				'style' => 'display: none;',
			),
		);

		$sections_list->add_after( 'theme_pages', 'default_widgets', array(
			'title' => __( 'Default Widgets', APP_TD ),
			'fields' => $default_widgets,
		) );

		if ( ! empty( $to_restore ) && ! empty( $_GET['_restore_widgets_nonce'] ) && wp_verify_nonce( $_GET['_restore_widgets_nonce'], 'restore_widgets' ) ) {
			appthemes_install_widgets( $to_restore );
		}

		$this->tab_sections['info'] = $sections_list->get_all();
	}


	function page_footer() {
		parent::page_footer();
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	if ( $("form input[name^='cp_tools']").length ) {
		$('form p.submit').html('');
	}
});
</script>
<?php
	}


}

