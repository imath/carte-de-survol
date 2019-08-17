<?php
/**
 * Globals.
 *
 * @package   carte-de-survol
 * @subpackage \inc\globales
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Register plugin globals.
 *
 * @since 1.0.0
 */
function carte_de_survol_register_globales() {
	$cds = carte_de_survol();

	$cds->version  = '1.0.0-alpha';
	$cds->inc_path = plugin_dir_path( __FILE__ );
	$cds->js_url   = plugins_url( 'assets/js/', dirname( __FILE__ ) );
	$cds->css_url  = plugins_url( 'assets/css/', dirname( __FILE__ ) );
	$cds->tpl_dir  = trailingslashit(  plugin_dir_path( dirname( __FILE__ ) ) ) . 'templates/buddypress';
}
add_action( 'bp_loaded', 'carte_de_survol_register_globales' );
