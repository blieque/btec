<?php

# variables

$unit_names	= array (
	1  => "Communication and Employability Skills",
	2  => "Computer Systems",
	3  => "Information Systems",
	6  => "Software Design & Development",
	8  => "e-Commerce",
	9  => "Computer Networks",
	11 => "Systems Analysis",
	12 => "IT Technical Support",
	14 => "Event Driven Programming",
	19 => "Computer Systems Architecture",
	20 => "Client-Side Customisation of Web Pages",
	22 => "Developing Computer Games",
	27 => "Web Server Scripting",
	28 => "Website Production",
	31 => "Computer Animation",
	42 => "Spreadsheet Modelling"
);

$is_given	= isset($_GET['a']);									// has an assignment or doc name been given?
$output		= "<!DOCTYPE html><html><head><title>";


# script

if ($is_given) {
	$title		= null;													// probably "Spreadsheet Modelling - Assignment 3" or "Readme" or suchlike
	$markdown	= null;													// path to markdown file's directory
	
	// these make conditions easier later
	if (isset($_GET['s'])) {
		
		$ext	= $_GET['s'] == "ext";
		$doc	= $_GET['s'] == "doc";
		$rol	= $_GET['s'] == "rol";

		global $ext, $doc, $rol;

	} else {

		$ext = $doc = $rol = false;
		global $ext, $doc, $rol;

	}

	$split	= explode(".", $_GET["a"]);

	if ($ext) {															// extra assignment page (see docs for detail)

		$markdown = "markdown/ext/" . strtolower($_GET['a']) . ".md";
		$word_split = explode("-", $split[1]);

		$title = $unit_names[$split[0]] . " &ndash; Assignment " . $word_split[0] . " &ndash; Excerpt";

	} else if ($doc) {													// documentation page

		$word_split = str_replace("-", " ", $split[2]);
		$word_split = ucwords(strtolower($word_split));

		$markdown = "doc/" . strtolower($_GET['a']) . ".md";
		$title = "Documentation &ndash; " . $word_split;

	} else if ($rol) {													// readme or license (tacky id, yes)

		$markdown = "" . strtoupper($_GET['a']) . ".md";
		$title = ucfirst(strtolower($split[0]));

	} else {															// standard assignment page

		$markdown = "markdown/" . $_GET['a'] . ".md";
		$title = $unit_names[$split[0]] . " &ndash; Assignment " . $split[1];

	}

	$output .= $title;													// add title text to title element in head

} else {															// index page, when no markdown file name has been given

	$output .= 'IT BTEC Assignments';

}

$output .= '</title><link rel="stylesheet" href="/btec/css/m.css"></head><body><section>';

if ($is_given) {

	// given a valid markdown filename, process said markdown
	if (file_exists($markdown)) {

		$output .= "<h1>" . $title . "</h1>";								// add same title as before, but in an h1 in the body
		$markdown = file_get_contents($markdown);							// load the file into a variable for wikkid modificashunz


		# process markdown includes

		preg_match_all("/(?<=<!--\[INCLUDE\] )[A-z0-9\-_\/.]*.[md|txt|html|vba|conf](?= -->)/", $markdown, $includes);
		$includes = $includes[0];											// preg_match_all() has a weird output

		foreach ($includes as &$include) {
			
			if (file_exists($include)) {

				# read the file requested

				$file_contents	= file_get_contents($include);

				# process the contents, for some languages

				$include_extension	= end(explode(".", $include));					// avoid variables passes in reference strict error

				if ($include_extension == "html") {

					preg_match("/<body[A-z0-9='.,:; \"]*>((?s).*)<\/body>/", $file_contents, $file_contents);
					$file_contents	= $file_contents[1];
					$file_contents	= preg_replace("/[\n\r\t]*/", "", $file_contents);	// prevents markdown from converting markup to HTML entities

				}

				if ($include_extension == "vba") {									// if we're in a dire situation

					$file_contents	= preg_replace("/\n    /", "\n", $file_contents);	// remove once level of space indentation
					$file_contents	= preg_replace("/\n/", "ESCAPED-NEWLINE", $file_contents);	// prevent newlines from being stripped later (line ~250)

					# pull in highlighting class

					include "includes/class.highlight.php";
					$highlight	= new Highlight;

					$file_contents_hl	= $highlight->vba($file_contents);
					$file_contents		= $file_contents_hl[1];

				}

				# replace include line with processed file contents

				$include		= preg_quote($include,"/");							// escape regex syntax
				$markdown		= preg_replace("/<!--\[INCLUDE\] $include -->/", $file_contents, $markdown);

			}

		}


		# type-specific additions

		if ($doc) {

			$output .= "<h2>Contents</h2><p>I have yet to make this a thing.</p>";

		} else if ($rol) {

			$output .= "<h2>Contents</h2><p>I have yet to make this a thing.</p>";

		} else {

			// find markdown header lines
			preg_match_all("/[#]+ [A-z0-9 :;,.&-\/!()]*\n(\r)?/", $markdown, $header_lines);
			$header_lines = $header_lines[0];									// preg_match_all() has a weird output

	
			foreach ($header_lines as &$header) {								// iterate through the array
	
				$header_id	= null;
				$header_new	= $header;
	
				$task_level = null;

				// if (preg_match()) { for contents
				// 	echo "placehold";
				// }

				preg_match("/\[([P|M|D][0-9])\]/", $header_new, $task_level);		// e.g., [M3]
	
				if ($task_level != null) {											// if header has a task level in it
					$task_level	= substr($task_level[0], 1, 2);							// clear out square brackets around task level
					$header_id	= " id=\"" . strtolower($task_level) . "\"";			// id attribute for headers
				}
	
				$header_size = preg_match_all("/[#]/", $header_new);				// h1, h2, h3, etc.
	
				$header_new = preg_replace("/[#]+ /", "<h" . $header_size . $header_id . ">", $header_new);
				$header_new .= "</h" . $header_size . ">";
	
				$markdown = str_replace($header, $header_new, $markdown);

			}

		}

		include "includes/parsedown.php";
		$pd = new Parsedown();

		$output .= $pd->text($markdown);

	// no markdown file of the name given found
	} else {

		header("HTTP/1.0 404 Not Found");									// send actual 404 code
		$output .= '<h1>404</h1>Assignment not found. You can view a list at the <a class="ref" href="/btec/">index</a>.';

	}

} else {

	// no markdown name given ($_GET['a'] not set)
	$output .= 'No assignment specified.<br>Maybe one of these will take your fancy.<ul>';

	foreach (glob('markdown/*.md') as $filename) {

		$module_split	= explode("/", $filename);
		$file_split		= explode(".", $module_split[1]);
		$file_module	= $unit_names[$file_split[0]];

		$output .= '<li><a href="/btec/' . substr($module_split[1], 0, -3) . '" class="ref">' . $file_module . ' &ndash; Assignment ' . $file_split[1] . '</a></li>';

	}

	$output .= '</ul>You can also view the <a href="/btec/readme" class="ref">read-me</a> and <a href="/btec/license" class="ref">license</a> documents.';

}

$protocol = (empty($_SERVER['HTTPS']) ? "http://" : "https://");	// detect http or https
$prefix = explode("/btec/", $_SERVER['REQUEST_URI'])[0];			// trim "/btec/" and anything after it from address


# change semi-absolute links into real ones, by adding protocol and domain before link addresses

$output = str_replace(

	"\"/btec/",
	"\"" . $protocol . $_SERVER['SERVER_NAME'] . $prefix . "/btec/",
	$output . "</section></body></html>"

);


# un-escape escaped characters (hello /r/shittyprogramming)

$output	= str_replace("ESCAPED-ASTERISK", "*", $output);		// un-escape escaped asterisks, episode 2
$output	= str_replace("ESCAPED-NEWLINE", "<br>", $output);

# semi-minify output

$output	= preg_replace("/[\n\r\t]*/", "", $output);					// strip newlines and tab indentation
$output	= preg_replace("/<!\-\-.*?\-\->/", "", $output);				// strip HTML comments


# the big reveal

echo $output;

?>
