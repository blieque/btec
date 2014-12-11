<?php

/* functions:
 *
 * Holds any functions I need in index.php. This is just to keep things tidy.
 * 
 * functions include:
 *  - minify: strips the rubbish out of HTML
 * 
 */

function minify($input) {

	$output	= $input;
	
	$output	= preg_replace("/[\n\r\t]*/", "", $output);					// newlines and tab indentation
	$output	= preg_replace("/<!\-\-.*?\-\->/", "", $output);			// HTML comments

	return $output;

}
