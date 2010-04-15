<?php

/*
Plugin Name: CF ShareThis
Description: Easy to customize ShareThis sharing links
Version: 1.0
Author: Crowd Favorite
Author URI: http://crowdfavorite.com
*/


/**
 * Get ShareThis API url
 * Generated for the v0.8 API
 * @param $url string - optional. URL to share
 * @param $title string - optional. Title of bookmark
 * @param $service string - optional. One of the service keywords (see below)
 *
 * Service keywords are undocumented. Usually what you would expect - lowercase service name.
 * Look at a ShareThis widget and try the class that is on the <a> tag for the service you want.
 */
function cfst_get_url($url, $title = '', $service) {
	// Build Param Query array
	$q = array(
		'url' => $url
	);
	if ($title) {
		$q['title'] = $title;
	}
	if ($service) {
		$q['destination'] = $service;
	}
	
	// Turn it into HTTP query get string
	$query = http_build_query($q, null, '&amp;');
	
	$api_url = apply_filters('cfst_api_url', 'http://wd.sharethis.com/api/sharer.php');
	
	$result = $api_url . '?' . $query;

	return $result;
}

/**
 * Get an ShareThis API <a> tags
 *
 * Add classes, IDs, etc through attributes array
 */
function cfst_get_share($args = '') {
	$default_args = array(
		// URL to share
		'url' => get_permalink(),
		// Optional.
		'title' => get_the_title(),
		// Link text
		'text' => __('Share', 'cf-sharethis'),
		// Service keyword (see cfst_get_url above)
		'service' => '',
		'attributes' => array(
			'onclick' => 'window.open(this.href, \'sharethis\', \'height=500,width=490,menubar=no,toolbar=no,location=no\'); return false;',
			'rel' => 'nofollow'
		)
	);

	$args = wp_parse_args($args, $default_args);
	extract($args);
	
	$add_url = cfst_get_url($url, $title, $service);
	
	$attr = '';
	if (is_array($attributes) && !empty($attributes)) {
		// Build attributes for link
		foreach ($attributes as $key => $value) {
			$attr[] = $key . '="'.$value.'"';
		}
		$attr = implode(' ', $attr);
	}
	
	$return = '<a href="'.$add_url.'" '.$attr.'>'.$text.'</a>';
	
	return $return;
}
function cfst_share($args = '') {
	echo cfst_get_share($args);
}

?>