<?php
/**
 * Plugin Name: Square Candy Cleanup Nags
 * Plugin URI: https://github.com/squarecandy/cleanup-wordpress-nags
 * GitHub Plugin URI: https://github.com/squarecandy/cleanup-wordpress-nags
 * Description: WordPress Plugin to cleanup annoying nag or marketing messages from various plugins we use.
 * Author: Square Candy Design
 * Author URI: https://squarecandydesign.com/
 * Version: 1.0.0
 */


//////////
// The Events Calendar / Modern Tribe

// eliminate sales pitch notices from The Events Calendar / Modern Tribe
add_filter( 'tribe_bf_2018_end_time', '__return_zero' );
add_filter( 'tribe_bf_2019_end_time', '__return_zero' );
add_filter( 'tribe_bf_2020_end_time', '__return_zero' );

// hide the non-dismissable notice to update geocoding on venues
add_action( 'admin_init', function() {
	if ( class_exists('Tribe__Events__Pro__Geo_Loc') ) :
		remove_action( 'admin_notices', array(
			Tribe__Events__Pro__Geo_Loc::instance(),
			'show_offer_to_fix_notice'
		) );
	endif;
}, 100 );

//////////
// Post Types Order / NSP Code

// hide nag box for custom post order plugin
function squarecandy_cpt_info_box_override_css() {
	if (class_exists('CPTO')) {
		echo '<style>#cpt_info_box{display:none;}</style>';
	}
}
add_action('admin_head', 'squarecandy_cpt_info_box_override_css');
