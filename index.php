<?php

# includes

require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/class.headings-handler.php';
require __DIR__ . '/vendor/autoload.php';

# variables

$unit_names = array(
	# "mandatory units"
	1 => 'Communication and Employability Skills',
	2 => 'Computer Systems',
	# "optional units"
	3 => 'Information Systems',
	4 => 'Impact of the Use of IT on Business Systems',
	5 => 'Managing Networks',
	6 => 'Software Design and Development',
	7 => 'Organisational Systems Security',
	8 => 'e-Commerce',
	9 => 'Computer Networks',
	10 => 'Communication Technologies',
	11 => 'Systems Analysis and Design',
	12 => 'IT Technical Support',
	13 => 'IT Systems Troubleshooting and Repair',
	14 => 'Event Driven Programming',
	15 => 'Object Oriented Programming',
	16 => 'Procedural Programming',
	17 => 'Project Planning with IT',
	18 => 'Database Design',
	19 => 'Computer Systems Architecture',
	20 => 'Client Side Customisation of Web Pages',
	21 => 'Data Analysis and Design',
	22 => 'Developing Computer Games',
	23 => 'Human Computer Interaction',
	24 => 'Controlling Systems Using IT',
	25 => 'Maintaining Computer Systems',
	26 => 'Mathematics for IT Practitioners',
	27 => 'Web Server Scripting',
	28 => 'Website Production',
	29 => 'Installing and Upgrading Software',
	30 => 'Digital Graphics',
	31 => 'Computer Animation',
	32 => 'Networked Systems Security',
	33 => 'Supporting Business Activity',
	34 => 'Business Resources',
	# "specialist optional units"
	35 => 'Digital Graphics for Interactive Media',
	36 => 'Computer Game Platforms and Technologies',
	37 => '2D Animation Production',
	38 => 'Interactive Media Authoring',
	39 => 'Web Animation for Interactive Media',
	40 => 'Computer Game Design',
	41 => '3D Modelling',
	42 => 'Spreadsheet Modelling',
	43 => 'Multimedia Design'
);

$is_given = isset($_GET['a']); // has an assignment or doc name been given?

// detect http or https
$protocol = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
// trim "/btec/" and anything after it from address
$address  = explode('/btec/', $_SERVER['REQUEST_URI'])[0];
// put it all together
$address  = $protocol . $_SERVER['SERVER_NAME'] . $address . '/btec/';

// final output variable
$output = '<!DOCTYPE html><html><head><title>';

# script

if ($is_given) {

	// probably 'Spreadsheet Modelling - Assignment 3' or 'Readme' or suchlike
	$title = null;
	// path to markdown file's directory
	$markdown_file = null;
	
	# these make conditions easier later

	if (isset($_GET['s'])) {
		
		$ext = $_GET['s'] == 'ext';
		$doc = $_GET['s'] == 'doc';
		$rol = $_GET['s'] == 'rol';

		global $ext, $doc, $rol;

	} else {

		$ext = $doc = $rol = false;

		global $ext, $doc, $rol;

	}

	$split = explode('.', $_GET['a']);
	$_GET['a'] = strcmp(substr($_GET['a'], 1, 1), '.')
				 == 0 ? '0' . $_GET['a'] : $_GET['a'];

	if ($ext) { // extra assignment page (see docs for detail)

		if (strlen($_GET['a']) > 0) {

			$markdown_file = 'markdown/ext/' . strtolower($_GET['a']) . '.md';
			$word_split = explode('-', $split[1]);

			$title = $unit_names[intval($split[0])] .
					 ' &ndash; Assignment ' .
					 $word_split[0] .
					 ' &ndash; Excerpt';

		} else {

			header('Location: ' . $address_full . '#excerpts');
			exit;

		}

	} else if ($doc) { // documentation page

		$word_split = str_replace('-', ' ', $split[0]);
		$word_split = ucwords(strtolower($word_split));

		$markdown_file = 'doc/' . strtolower($_GET['a']) . '.md';
		$title = 'Documentation &ndash; ' . $word_split;

	} else if ($rol) { // readme or license (tacky id, yes)

		$markdown_file = '' . strtoupper($_GET['a']) . '.md';
		$title = ucfirst(strtolower($split[0]));

	} else { // standard assignment page

		$markdown_file = 'markdown/' . $_GET['a'] . '.md';
		$title = $unit_names[intval($split[0])] .
				 ' &ndash; Assignment ' .
				 $split[1];

	}

	$output .= $title; // add title text to title element in head

} else { // index page, when no markdown file name has been given

	$output .= 'IT BTEC Assignments';

}

$output .= '</title><meta name="viewport" content="width=device-width,initial' .
		   '-scale=1"><link rel="stylesheet" href="/btec/css/main.css"><link ' .
		   'rel="stylesheet" href="/btec/css/mobile.css" media="max-device-wi' .
		   'dth:700px"></head><body><section>';

if ($is_given) {

	# given a valid markdown filename, process said markdown

	if (is_readable($markdown_file)) {

		// add same title as before, but in an h1 in the body
		$output	 .= '<h1>' . $title . '</h1>';
		// load the file into a variable for wikkid modificashunz
		$markdown = file_get_contents($markdown_file);
		global $markdown;

		# process includes

		$markdown = process_specials($markdown_file, $markdown);
		$markdown = process_includes($markdown);

		# downscale headings by one level

		$markdown = "\n" . $markdown; // makes everything easier
		$markdown = str_replace("\n#", "\n##", $markdown);

		# find markdown heading lines

		preg_match_all('/\n[#]{2,6} .*/', $markdown, $heading_lines);

		# id headings and contents list

		$replacements =
			HeadingsHandler::replacement_array($heading_lines[0], $_GET['a']);

		/* 
		 * Below, I had to use a for loop to replace the markdown headings with
		 * their processed, HTML equivalents. I would prefer to use a single
		 * str_replace() and hand it arrays, but this causes issues if there are
		 * multiple identical headings. As str_replace() doesn't have a limit
		 * parameter, and preg_replace() is clunky and would require stuff to be
		 * escaped with preg_quote(), I did this. It's imperfect, but I don't
		 * think there's a better way.
		 * Thanks to http://stackoverflow.com/questions/1252693
		 *
		 */

		for ($i = 0; $i < count($heading_lines[0]); $i++) { 

			$position = strpos($markdown,$heading_lines[0][$i]);
			if ($position !== false) {
				$markdown = substr_replace($markdown,
										   $replacements[$i],
										   $position,
										   strlen($heading_lines[0][$i]));
			}

		}

		$contents = HeadingsHandler::contents($heading_lines[0]);
		if ($contents != 1) {
			$output = preg_replace('/(?<=<\/head><body>)(?=<section>)/',
								   '<aside>' . $contents . '</aside>',
								   $output);
		}

		# parse what markdown is left that I haven't mutilated

		$ciconia = new_gfm();
		$output .= $ciconia->render($markdown);


	} else { // no markdown file of the name given found

		header('HTTP/1.0 404 Not Found'); // send http 404 code
		$output .= '<h1>404</h1>The assignment was not found or is not readab' .
				   'le. You can view a list at the <a class="ref" href="/btec' .
				   '/">index</a>.';

	}

} else {

	/*
	 * No markdown name given ($_GET['a'] unset)
	 *
	 * The following builds a list of all of the assignments that the script can
	 * find. At the moment, this list is in the form of an ugly HTML <ul>, but I
	 * may make it a better formatted table at some point.
	 *
	 * Links without zero-padding (e.g., "/btec/6.1") are preferred to those
	 * with (e.g., "/btec/06.1"), although both will work fine. I may make the
	 * latter redirect to the former one day.
	 *
	 */
	
	$output .= '<h1>BTEC IT</h1>An assignment was not specified. All of the a' .
			   'vailable assignments are listed below.<ul>';

	foreach (glob('markdown/*.md') as $filename) {

		// e.g., ['markdown', '06.1.md']
		$path_split = explode('/', $filename);
		// e.g., ['06', '1', 'md']
		$doc_split = explode('.', end($path_split));
		// e.g., 'Software Design and Development'
		$doc_unit = $unit_names[intval($doc_split[0])];
		$doc_id	 = strcmp(substr($doc_split[0], 0, 1), '0')
				   == 0 ? substr($doc_split[0], 1, 1) : $doc_split[0];
		$doc_id .= '.' . $doc_split[1];

		$output .= '<li><a href="/btec/' .
				   $doc_id . '">' .
				   $doc_unit .
				   ' &ndash; Assignment ' .
				   $doc_split[1] .
				   '</a></li>';

	}

	$output .= '</ul>Excerpts and notes documents are also available.<ul>';

	foreach (glob('markdown/ext/*.md') as $filename) {

		$path_split = explode('/', $filename);
		preg_match('/[0-9]+/', $path_split[2], $doc_split);
		$doc_unit = $doc_split[0];
		$doc_unit = $unit_names[intval($doc_unit)];

		preg_match('/(?<=-).*?(?=.md)/', $path_split[2], $doc_name);
		$doc_name = ucwords(strtolower(str_replace('-', ' ', $doc_name[0])));

		$output .= '<li><a href="/btec/ext/' .
				   substr($path_split[2], 0, -3) .
				   '">' .
				   $doc_unit .
				   ' &ndash; ' .
				   $doc_name .
				   '</a></li>';

	}

	$output .= '</ul>You can also view the <a href="/btec/readme" class="ref"' .
			   '>readme</a> and <a href="/btec/license" class="ref">license</' .
			   'a> documents, or the <a href="/btec/docs/index">documentation' .
			   '</a> for this website. Everything can also be viewed as sourc' .
			   'e code on <a href="https://github.com/blieque/btec">GitHub</a' .
			   '>, the home of its public repository..';

}

$output .= '</section></body></html>';

# change semi-absolute links into absolute ones, by adding protocol and domain
# before link addresses

$output = str_replace(

	'"/btec/',
	'"' . $address,
	$output

);

# add ext class to external anchors

$prefix_full = preg_quote($address, "/");
preg_match_all("/<a href=\"(?=$prefix_full)/", $output, $anchors);
$anchors = $anchors[0];

foreach ($anchors as $anchor) {

	if (strcmp(substr($anchor, 9, strlen($prefix_full)), $prefix_full) == 0) {

		$anchor_new = str_replace('href', 'class="ext" href', $anchor);
		$output = str_replace($anchor, $anchor_new, $output);

	}

}

# un-escape escaped characters (hello /r/shittyprogramming)

// un-escape escaped asterisks, episode 2
$output = str_replace('ESCAPED-ASTERISK', '*', $output);

# semi-minify output

// function from functions.php
$output = minify($output);

# the big reveal

echo $output;

?>
