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
	$output	= preg_replace("/([>.])[ ]{2,}([A-z0-9<])/", "$1$2", $output);		// space indentation

	return $output;

}