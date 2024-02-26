<?php
/**
 * Plugin Name: Square Candy Cleanup Nags
 * Plugin URI: https://github.com/squarecandy/cleanup-wordpress-nags
 * GitHub Plugin URI: https://github.com/squarecandy/cleanup-wordpress-nags
 * Description: WordPress Plugin to cleanup annoying nag or marketing messages from various plugins we use.
 * Author: Square Candy Design
 * Author URI: https://squarecandydesign.com/
 * Version: 1.2.2-dev.0
 */


//////////
// The Events Calendar / Modern Tribe

// eliminate sales pitch notices from The Events Calendar / Modern Tribe
add_filter( 'tribe_bf_2018_end_time', '__return_zero' );
add_filter( 'tribe_bf_2019_end_time', '__return_zero' );
add_filter( 'tribe_bf_2020_end_time', '__return_zero' );
add_filter( 'tribe_bf_2021_end_time', '__return_zero' );
add_filter( 'tribe_bf_2022_end_time', '__return_zero' );
add_filter( 'tribe_bf_2023_end_time', '__return_zero' );
// universal/longterm solutions for 2024+ !!!
define( 'TEC_HIDE_UPSELL', true );
add_filter( 'tec_common_telemetry_show_optin_modal', '__return_false' );

// hide the non-dismissable notice to update geocoding on venues
add_action(
	'admin_init',
	function() {
		if ( class_exists( 'Tribe__Events__Pro__Geo_Loc' ) ) :
			remove_action(
				'admin_notices',
				array(
					Tribe__Events__Pro__Geo_Loc::instance(),
					'show_offer_to_fix_notice',
				)
			);
	endif;
	},
	100
);

//////////
// Hacky CSS Hiding

function squarecandy_cpt_info_box_override_css() {
	echo '<style>';
	$hide_styles = array(
		// hide nag box for custom post order plugin
		'#cpt_info_box',
		// hide event calendar nag to connect to data collection service
		'.updated.success.fs-notice.fs-slug-the-events-calendar[data-id="connect_account"]',
		// hide event calendar virtual events ad
		'.tribe-notice-tribe-virtual-events',
		// hide "do you like plugin WPS Hide Login?"
		'#dnh-wrm_1e278f4992d8bb3f1f0b ',
	);
	echo implode( ',', $hide_styles );
	echo '{display:none;}</style>';
}
add_action( 'admin_head', 'squarecandy_cpt_info_box_override_css' );


// get rid of autoptimize/shortpixel cross promotion notice
add_filter(
	'autoptimize_filter_main_imgopt_plug_notice',
	function() {
		return '';
	}
);

function squarecandy_hide_wordpress_seo_menus() {
	// Hide "SEO Workouts" upsell quasi-spam from Editor level.
	remove_menu_page( 'wpseo_workouts' );
}
add_action( 'admin_menu', 'squarecandy_hide_wordpress_seo_menus', 11 );


// https://www.advancedcustomfields.com/blog/acf-6-2-5-security-release/
// Don't show the ACF "the_field" notice in wp-admin

add_filter( 'acf/admin/prevent_escaped_html_notice', 'sqcdy_cleanup_nags_acf_prevent_escaped_html_notice' );
function sqcdy_cleanup_nags_acf_prevent_escaped_html_notice() {
	// if in debug mode, log useful info
	if ( WP_DEBUG ) {
		// HTML that has already been escaped.
		$escaped = _acf_get_escaped_html_log();
		if ( ! empty( $escaped ) ) {
			error_log( 'ACF: escaped html' );
			error_log( print_r( $escaped, true ) );
		}
		// HTML that will be escaped in future releases.
		$will_escape = _acf_get_will_escape_html_log();
		if ( ! empty( $will_escape ) ) {
			error_log( 'ACF: html that will be escaped in future releases' );
			error_log( print_r( $will_escape, true ) );
		}
	}
	// hide the admin notice
	return true;
}

// Log problematic content when displayed
if ( WP_DEBUG ) {
	add_action( 'acf/will_remove_unsafe_html', 'sqcdy_acf_security_logging_2024', 99, 4 );
	add_action( 'acf/removed_unsafe_html', 'sqcdy_acf_security_logging_2024', 99, 4 );
	if ( ! function_exists( 'sqcdy_acf_security_logging_2024' ) ) {
		function sqcdy_acf_security_logging_2024( $function, $selector, $field, $post_id ) {
			error_log( "ACF unsafe_html: function: $function • selector: $selector • field: $field • post_id: $post_id" );
		}
	}
}
