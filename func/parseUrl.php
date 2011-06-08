<?php
/**
 * parseUrl — Parse a URL and return its components
 * Note: This function is intended specifically for the purpose of parsing URLs
 *
 * Components:
 * tld
 * domain
 * subdomain
 * scheme - e.g. http
 * host
 * port
 * user
 * pass
 * path
 * query    - after the question mark ?
 * fragment - after the hashmark #
 *
 * @author = Dennis T Kaplan
 * @param  = (string) $url
 * @return = (array) Components
 * @todo Break down tld
 * @todo query as key=> value array
 **/
function parseUrl($url) {
	$p = parse_url($url);
	$host = array_reverse(explode('.', $p['host']));
	$p['tld']       = $host[0];
	$p['domain']    = $host[1];
	$p['subdomain'] = $host[2];
	return $p;
}
?>