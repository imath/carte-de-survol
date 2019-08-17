<?php
/**
 * General functions.
 *
 * @package   carte-de-survol
 * @subpackage \inc\functions
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Load translations.
 *
 * @since 1.0.0
 */
function carte_de_survol_load_textdomain() {
	load_plugin_textdomain(
		'carte-de-survol',
		false,
		trailingslashit( basename( carte_de_survol()->dir ) ) . 'languages'
	);
}
add_action( 'bp_loaded', 'carte_de_survol_load_textdomain', 11 );

/**
 * Register assets for the hovercard.
 *
 * @since 1.0.0
 */
function carte_de_survol_register_assets() {
	$cds = carte_de_survol();
	$min = bp_core_get_minified_asset_suffix();

	wp_register_script(
		'carte-de-survol',
		$cds->js_url . "carte-de-survol{$min}.js",
		array(
			'wp-pointer',
			'hoverIntent',
			'wp-util',
			'bp-api-request',
		),
		$cds->version,
		true
	);

	$style = $cds->css_url . "style{$min}.css";

	/**
	 * If you want to override the styles:
	 *
	 * Just put a style.css file into your theme's directory at this location:
	 * `buddypress/assets/hovercard/style.css`
	 */
	$custom = bp_locate_template_asset( 'assets/hovercard/style.css' );
	if ( isset( $custom['uri'] ) ) {
		$style = $custom['uri'];
	}

	wp_register_style(
		'carte-de-survol',
		$style,
		array(
			'wp-pointer',
		),
		$cds->version
	);
}
add_action( 'bp_init', 'carte_de_survol_register_assets' );

/**
 * Enqueue assets for the hovercard.
 *
 * @since 1.0.0
 */
function carte_de_survol_enqueue_assets() {
	wp_enqueue_script( 'carte-de-survol' );
	wp_localize_script(
		'carte-de-survol',
		'carteDeSurvol',
		array(
			'pattern' => trailingslashit( bp_get_root_domain() . '/' . bp_get_members_root_slug() ),
			'loader'  => admin_url( 'images/spinner-2x.gif' ),
		)
	);
	wp_enqueue_style( 'carte-de-survol' );
}
add_action( 'bp_enqueue_scripts', 'carte_de_survol_enqueue_assets' );

/**
 * Extend the BP REST Members endpoint collection params.
 *
 * @since 1.0.0
 *
 * @param  array $params The collection params.
 * @return array         The collection params.
 */
function carte_de_survol_rest_members_params( $params = array() ) {
	$params['slug'] = array(
		'description'       => __( 'Limite les résultats aux membres ayant pour slug celui qui est requêté.', 'carte-de-survol' ),
		'default'           => '',
		'type'              => 'string',
		'validate_callback' => 'rest_validate_request_arg',
	);

	return $params;
}
add_filter( 'bp_rest_members_collection_params', 'carte_de_survol_rest_members_params', 10, 1 );

/**
 * Define the requested user's slug into the BP REST API.
 *
 * @since 1.0.0
 *
 * @param  array           $args    The arguments for BP_User_Query.
 * @param  WP_REST_Request $request The REST request being executed.
 * @return array                    The arguments for BP_User_Query.
 */
function carte_de_survol_rest_members_get_items_query_args( $args = array(), WP_REST_Request $request ) {
	$args['slug'] = $request['slug'];

	return $args;
}
add_filter( 'bp_rest_members_get_items_query_args', 'carte_de_survol_rest_members_get_items_query_args', 10, 2 );

/**
 * Set the user_ids' parameter of BP User Query if needed.
 *
 * @since 1.0.0
 *
 * @param BP_User_Query $user_query The BuddyPress User Query object.
 */
function carte_de_survol_user_query_construct( BP_User_Query $user_query ) {
	if ( ! isset( $user_query->query_vars_raw['slug'] ) || ! $user_query->query_vars_raw['slug'] ) {
		return;
	}

	$user_id = (int) bp_core_get_userid_from_nicename( $user_query->query_vars_raw['slug'] );

	$user_query->query_vars_raw['user_ids'] = array( $user_id );
	$user_query->query_vars['user_ids']     = array( $user_id );

	unset( $user_query->query_vars_raw['slug'], $user_query->query_vars['slug'] );

}
add_action( 'bp_pre_user_query_construct', 'carte_de_survol_user_query_construct', 10, 1 );

/**
 * Add our templates directory to the BuddyPress templates stack.
 *
 * @since  1.0.0
 *
 * @param  array $stack The BuddyPress templates stack.
 * @return array $stack The BuddyPress templates stack.
 */
function carte_de_survol_get_template_stack( $stack = array() ) {
	$templates_dir = trailingslashit( carte_de_survol()->tpl_dir );

	return array_merge( $stack, array( $templates_dir ) );
}
add_filter( 'bp_get_template_stack', 'carte_de_survol_get_template_stack' );

/**
 * Load the JS template.
 *
 * @since 1.0.0
 */
function carte_de_survol_get_hovercard_template() {
	/**
	 * If you want to override the markup:
	 *
	 * Just put an index.php file into your theme's directory at this location:
	 * `buddypress/assets/hovercard/index.php`
	 */
	bp_get_template_part( 'assets/hovercard/index' );
}
add_action( 'wp_footer', 'carte_de_survol_get_hovercard_template', 100 );
