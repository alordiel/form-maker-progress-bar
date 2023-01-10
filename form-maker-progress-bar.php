<?php
/*
Plugin Name: Progress bar for Form Maker
Plugin URI: https://github.com/alordiel/form-maker-progress-bar
Description: With the help of a shortcode you can add a progress bar for your forms, when you use them as petitions.
Version: 1.0.0
Author: Alexander Vasilev
Author URI: https://timelinedev.com
License: GPLv2
text-domain: fmpb
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function fmpb_progress_bar( $attributes ) {
	$attributes = shortcode_atts( array(
		'form-id'        => 0,
		'check-verified' => 'true',
		'bar-color'      => '#f47822',
		'target-votes'   => '5000'
	), $attributes );
	global $wpdb;
	$sql = "SELECT count(ip)
			FROM {$wpdb->prefix}formmaker_submits
			WHERE form_id = %d AND element_label = 'verifyInfo'";
	if ( $attributes['check-verified'] === 'true' ) {
		$sql .= " AND element_value LIKE '%verified%'";
	}
	$count    = $wpdb->get_var( $wpdb->prepare( $sql, $attributes['form-id'] ) );
	$progress = ( $count / $attributes['target-votes']) * 100;
	if ($progress > 100) {
		$progress = 100;
	}
	echo '<div class="fmpb-progress">
  			<div
  			    class="fmpb-progress-bar"
  			    style="width: ' . $progress . '%"
  			    aria-valuenow="' . $progress . '"
  			    aria-valuemax="100"
  			    >
  			    '.sprintf(__('%d out of %d votes','fmpb'),$count,$attributes['target-votes']).'
			</div>
		 </div>
		 <style>
		 	.fmpb-progress {
		 		background-color: #d9d9d9;
		 		border-radius: 5px;
		 	}
		 	.fmpb-progress-bar {
		 		height: 20px;
		 		padding-left: 10px;
		 		border-radius: 5px 0 0 5px;
		 		background-color: ' . $attributes['bar-color'] . ';
		 	}
		 </style>';
}

add_shortcode( 'fmpb-progress', 'fmpb_progress_bar' );
