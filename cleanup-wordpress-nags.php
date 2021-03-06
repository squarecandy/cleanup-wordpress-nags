<?php
/**
 * Plugin Name: Square Candy Cleanup Nags
 * Plugin URI: https://github.com/squarecandy/cleanup-wordpress-nags
 * GitHub Plugin URI: https://github.com/squarecandy/cleanup-wordpress-nags
 * Description: WordPress Plugin to cleanup annoying nag or marketing messages from various plugins we use.
 * Author: Square Candy Design
 * Author URI: https://squarecandydesign.com/
 * Version: 1.1.0
 */


//////////
// The Events Calendar / Modern Tribe

// eliminate sales pitch notices from The Events Calendar / Modern Tribe
add_filter( 'tribe_bf_2018_end_time', '__return_zero' );
add_filter( 'tribe_bf_2019_end_time', '__return_zero' );
add_filter( 'tribe_bf_2020_end_time', '__return_zero' );

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
