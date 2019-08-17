<?php
/**
 * Affichage d'informations détaillées d'un membre de votre communauté BuddyPress au survol du lien pointant vers son profil.
 *
 * @package   carte-de-survol
 * @author    imath
 * @license   GPL-2.0+
 * @link      https://imathi.eu
 *
 * @buddypress-plugin
 * Plugin Name:       Carte de Survol
 * Plugin URI:        https://github.com/imath/carte-de-survol
 * Description:       Affichage d'informations détaillées d'un membre de votre communauté BuddyPress au survol du lien pointant vers son profil.
 * Version:           1.0.0-alpha
 * Author:            imath
 * Author URI:        https://github.com/imath
 * Text Domain:       carte-de-survol
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages/
 * GitHub Plugin URI: https://github.com/imath/carte-de-survol
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Carte_De_Survol' ) ) :
/**
 * Main Class
 *
 * @since 1.0.0
 */
class Carte_De_Survol {
	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin
	 */
	private function __construct() {
		$this->inc();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 */
	public static function start() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load needed files.
	 *
	 * @since 1.0.0
	 */
	private function inc() {
		$inc_path = plugin_dir_path( __FILE__ ) . 'inc/';

		require $inc_path . 'globals.php';
		require $inc_path . 'functions.php';
	}
}

endif;

/**
 * Start plugin.
 *
 * @since 1.0.0
 *
 * @return Carte_De_Survol The main instance of the plugin.
 */
function carte_de_survol() {
	return Carte_De_Survol::start();
}
add_action( 'bp_include', 'carte_de_survol', 9 );
