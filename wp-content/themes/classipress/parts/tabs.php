<?php
/**
 * Template to display the Tabs layout.
 *
 * @package ClassiPress\Templates
 * @since 4.2.0
 */

if ( empty( $app_sidebar_tabs ) ) {
	return;
}

$first_tab = key( $app_sidebar_tabs );

?>
<div class="tabs primary sidebar-tabs content-wrap" data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge-delay="500" data-tabs id="deeplinked-tabs">

	<?php foreach ( $app_sidebar_tabs as $tab_id => $sidebar_tab ) { ?>

		<div <?php echo $tab_id === $first_tab ? 'class="tabs-title is-active" aria-selected="true"' : 'class="tabs-title"'; ?>>

			<a href="#<?php echo esc_attr( $tab_id ); ?>" role="tab" aria-controls="<?php echo esc_attr( $tab_id ); ?>" id="<?php echo esc_attr( $tab_id ); ?>-label"><?php echo $sidebar_tab['title']; ?></a>

		</div>

	<?php } ?>

</div>

<div class="sidebar-tabs-content" data-tabs-content="deeplinked-tabs">

	<?php foreach ( $app_sidebar_tabs as $tab_id => $sidebar_tab ) { ?>

		<div id="<?php echo esc_attr( $tab_id ); ?>" class="tabs-panel<?php echo $tab_id === $first_tab ? ' is-active' : ''; ?>">

			<?php echo $sidebar_tab['content']; ?>

		</div>

	<?php } ?>

</div>
