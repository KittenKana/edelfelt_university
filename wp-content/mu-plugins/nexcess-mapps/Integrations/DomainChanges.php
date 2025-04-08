<?php

/**
 * Custom handling during domain changes.
 */

namespace Nexcess\MAPPS\Integrations;

use Nexcess\MAPPS\Concerns\HasAdminPages;
use Nexcess\MAPPS\Concerns\HasAssets;
use Nexcess\MAPPS\Concerns\HasCronEvents;
use Nexcess\MAPPS\Concerns\HasWordPressDependencies;
use Nexcess\MAPPS\Concerns\InvokesCli;
use Nexcess\MAPPS\Services\Domain;
use Nexcess\MAPPS\Services\Installer;
use Nexcess\MAPPS\Services\Logger;
use Nexcess\MAPPS\Settings;
use Nexcess\MAPPS\Support\Helpers;
use StellarWP\PluginFramework\Exceptions\InstallationException;
use StellarWP\PluginFramework\Exceptions\LicensingException;
use StellarWP\PluginFramework\Support\Branding;
use WP_Error;

class DomainChanges extends Integration {
	use HasAdminPages;
	use HasAssets;
	use HasCronEvents;
	use HasWordPressDependencies;
	use InvokesCli;

	/**
	 * @var Settings $settings
	 */
	protected $settings;

	/**
	 * @var Domain $domain_service
	 */
	protected $domain_service;

	/**
	 * @var Installer $installer
	 */
	protected $installer;

	/**
	 * @var Logger $logger
	 */
	protected $logger;

	/**
	 * The hook used for domain change cron events.
	 */
	const DOMAIN_CHANGE_CRON_EVENT = 'nexcess_mapps_domain_changed';

	/**
	 * The hook used for domain-change licensing cron events.
	 */
	const DOMAIN_CHANGE_LICENSE_UPDATES_CRON_EVENT = 'nexcess_mapps_domain_changed_license_updates';

	/**
	 * Solid Security Nocworx ID.
	 */
	const SOLID_SECURITY_NOCWORX_ID = 38;

	/**
	 * Construct the integration.
	 *
	 * @param Settings  $settings
	 * @param Domain    $domain_service
	 * @param Installer $installer
	 * @param Logger    $logger
	 */
	public function __construct( Settings $settings, Domain $domain_service, Installer $installer, Logger $logger ) {
		$this->settings       = $settings;
		$this->domain_service = $domain_service;
		$this->installer      = $installer;
		$this->logger         = $logger;
	}

	/**
	 * Retrieve all actions for the integration.
	 *
	 * @return array[]
	 */
	protected function getActions() {
		// phpcs:disable WordPress.Arrays
		return [
			[ 'load-options.php', [ $this, 'handleSearchReplaceRequests' ] ],
			[ 'load-index.php', [ $this, 'enqueueScripts' ] ],
			[ 'load-options-general.php', [ $this, 'enqueueScripts' ] ],
			[ 'load-site-health.php', [ $this, 'enqueueScripts' ] ],

			[ 'wp_ajax_mapps-change-domain', [ $this, 'handleDomainChangeRequests' ] ],

			// Callback for the cron event.
			[ self::DOMAIN_CHANGE_CRON_EVENT, [ $this, 'searchReplace' ], 10, 2 ],

			// Go Live invalidates Solid Security license, so re-license when needed.
			[ 'update_option_home', [ $this, 'domainSettingChanged' ], 10, 2 ],
			[ 'update_option_siteurl', [ $this, 'domainSettingChanged' ], 10, 2 ],
			[ self::DOMAIN_CHANGE_LICENSE_UPDATES_CRON_EVENT, [ $this, 'updateLicenses' ] ],
		];
		// phpcs:enable WordPress.Arrays
	}

	/**
	 * Enqueue JavaScript for the opt-in UI.
	 */
	public function enqueueScripts() {
		add_action( 'admin_enqueue_scripts', function () {
			$this->enqueueScript( 'nexcess-mapps-domain-changes', 'domain-changes.js', [ 'nexcess-mapps-admin', 'wp-element' ] );

			$this->injectScriptData( 'nexcess-mapps-domain-changes', 'domainChange', [
				'currentDomain' => wp_parse_url( site_url(), PHP_URL_HOST ),
				'dnsHelpUrl'    => Branding::getDNSHelpUrl(),
				'nonce'         => wp_create_nonce( 'mapps-change-domain' ),
				'portalUrl'     => Helpers::getPortalUrl( $this->settings->plan_id, $this->settings->account_id, 'domain-options' ),
			] );

			$this->injectScriptData( 'nexcess-mapps-domain-changes', 'httpsUpdateUrl', [
				'default' => esc_url( add_query_arg( 'action', 'update_https', wp_nonce_url( admin_url( 'site-health.php' ), 'wp_update_https' ) ) ),
				'updated' => esc_url( admin_url( 'options-general.php' ) . '#siteurl' ),
			] );
		} );
	}

	/**
	 * When processing the settings API, look for user opt-in to search/replace operations.
	 *
	 * If the "mapps-domain-search-replace" key is found in the $_POST body, apply the appropriate
	 * callback to the update_option_home hook.
	 */
	public function handleSearchReplaceRequests() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ! empty( $_POST['mapps-domain-search-replace'] ) ) {
			add_action( 'update_option_home', [ $this, 'scheduleSearchReplace' ], 10, 2 );
		}
	}

	/**
	 * Form handler for changing the current site's domain.
	 */
	public function handleDomainChangeRequests() {
		if ( empty( $_POST['_mapps_nonce'] ) || ! wp_verify_nonce( $_POST['_mapps_nonce'], 'mapps-change-domain' ) ) {
			return wp_send_json_error( new WP_Error(
				'mapps-nonce-failure',
				__( 'The security nonce has expired or is invalid. Please refresh the page and try again.', 'nexcess-mapps' )
			), 400 );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return wp_send_json_error( new WP_Error(
				'mapps-capabilities-failure',
				__( 'You do not have permission to perform this action. Please contact a site administrator or log into the Nexcess portal to change the site domain.', 'nexcess-mapps' )
			), 403 );
		}

		// Verify the domain structure.
		$domain = ! empty( $_POST['domain'] )
			? $this->domain_service->parseDomain( $_POST['domain'] )
			: null;

		$domain = $this->domain_service->formatDomain( $domain );

		if ( empty( $domain ) ) {
			return wp_send_json_error( new WP_Error(
				'mapps-invalid-domain',
				sprintf(
				/* Translators: %1$s is the provided domain name. */
					__( '"%s" is not a valid domain name. Please check your spelling and try again.', 'nexcess-mapps' ),
					sanitize_text_field( $_POST['domain'] )
				)
			), 422 );
		}

		$response = $this->domain_service->renameDomain( $domain );

		if ( is_wp_error( $response ) ) {
			return wp_send_json_error( new WP_Error(
				'mapps-change-domain-failure',
				$response->get_error_message()
			) );
		}

		return wp_send_json_success( null, 202 );
	}

	/**
	 * Schedule a cron event to run immediately that will perform a search-replace via WP-CLI.
	 *
	 * @param string $previous The old domain.
	 * @param string $current  The new domain.
	 */
	public function scheduleSearchReplace( $previous, $current ) {
		$this->registerCronEvent( self::DOMAIN_CHANGE_CRON_EVENT, null, current_datetime(), [
			$previous,
			$current,
		] )->scheduleEvents();

		// Spawn a cron process immediately.
		spawn_cron();
	}

	/**
	 * Update the domain in the database using WP-CLI's search-replace command.
	 *
	 * @param string $previous The old domain.
	 * @param string $current  The new domain.
	 */
	public function searchReplace( $previous, $current ) {
		$response = $this->makeCommand( 'wp search-replace', [
			$previous,
			$current,
			'--recurse-objects',
			'--skip-columns=user_email,guid',
			'--all-tables',
		] )->execute();

		if ( $response->wasSuccessful() ) {
			delete_option( 'https_migration_required' );
			wp_cache_flush();
		}

		return $response;
	}

	/**
	 * Look for when domain-related settings change in the WP Settings.
	 * This hook only runs when the setting actually changes (saving a new value).
	 *
	 * @param mixed $old_value Previous setting value.
	 * @param mixed $new_value New setting value.
	 */
	public function domainSettingChanged( $old_value, $new_value ) {
		$this->registerCronEvent( self::DOMAIN_CHANGE_LICENSE_UPDATES_CRON_EVENT, null, current_datetime(), [] )->scheduleEvents();

		// Spawn a cron process immediately.
		spawn_cron();
	}

	/**
	 * Run license checks when domain-related settings change in the WP Settings.
	 */
	public function updateLicenses() {
		if ( $this->isPluginActive( 'ithemes-security-pro' ) && empty( get_option( 'nexcess_relicensed_solid_security', false ) ) ) {
			try {
				$this->installer->install( SOLID_SECURITY_NOCWORX_ID );
			} catch ( InstallationException $e ) {
				$this->logger->info( sprintf(
					/* Translators: %1$s is the previous exception message. */
					__( 'Unable to install Solid Security: %1$s', 'nexcess-mapps' ),
					$e->getMessage()
				) );
				return;
			}

			try {
				$this->installer->license( SOLID_SECURITY_NOCWORX_ID );
			} catch ( LicensingException $e ) {
				$this->logger->info( sprintf(
					/* Translators: %1$s is the previous exception message. */
					__( 'Unable to license Solid Security: %1$s', 'nexcess-mapps' ),
					$e->getMessage()
				) );
				return;
			}

			update_option( 'nexcess_relicensed_solid_security', true );
		}
	}
}
