<?php

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
$title		= NULL;													// probably "Spreadsheet Modelling - Assignment 3" or "Readme" or suchlike
$md_path	= NULL;													// path to markdown file's directory

$output		= "<!DOCTYPE html><html><head><title>";

if ($is_given) {
	$split	= explode(".",$_GET["a"]);

	if ($_GET['s'] == "ext") {											// extra assignment page (see docs for detail)

		$md_path = "markdown/ext/";
		$title = $unit_names[$split[0]] . " &ndash; Assignment " . $split[1] . " &ndash; Excerpt";

	} else if ($_GET['s'] == "doc") {										// documentation page

		$word_split = str_replace("-"," ",$split[2]);
		$word_split = ucwords(strtolower($word_split));

		$md_path = "doc/";
		$title = "Documentation &ndash; " . $word_split;

	} else if ($_GET['s'] == "rol") {										// readme or license (tacky id, yes)

		$md_path = "";
		$title = ucfirst(strtolower($split[0]));

	} else {															// standard assignment page

		$md_path = "markdown/";
		$title = $unit_names[$split[0]] . " &ndash; Assignment " . $split[1];

	}

	$output .= $title;													// add our title to the output var

} else {															// index page, when no markdown file name has been given

	$output .= 'IT BTEC Assignments';

}


$output .= '</title><link rel="stylesheet" href="/btec/css/m.css"></head><body><section>';

if ($is_given) {
	// given an id of exisiting file, process markdown
	if (file_exists($markdown = "markdown/" . $_GET['a'] . ".md")) {
		$markdown = file_get_contents($markdown);

		// find task levels (PX, MX or DX)
		$matches = NULL;
		preg_match_all("/\[[P|M|D][0-9]+\]/",$markdown,$matches);
		$matches = $matches[0];												// we don't want no array-in-an-array bull
		foreach ($matches as &$match) {										// iterate through the array
			$match = preg_replace("/\[([A-Z0-9]+)\]/","$1",$match);
		}
		$matchesstr = implode(", ",$matches);

		include "include/parsedown.php";
		$pd = new Parsedown();

		if ($isassign) {
			$output .= "<h1>Unit " . $name[0][0] . " &ndash; " . $name[1][$name[0][0]] . "</h1><h3>" . $name[2] . ": " . $matchesstr . "</h3>";
		} else {
			$output .= "<h1>" . $name[2] . "</h1>";
		}
		$output .= $pd->text($markdown);
	} else {
		$output .= '<h1>404</h1>Assignment not found. You can view a list at the <a class="ref" href="/btec/">index</a>.';
	}
} else {
	// no assignment id given
	$output .= 'No assignment specified.<br>Maybe one of these will take your fancy.<ul>';
	foreach (glob('markdown/*.md') as $filename) {
		$file_split = explode(".",$filename);
		$file_module = $name[1][$file_split[0]];
		$output .= '<li><a href="/btec/' . str_replace('.md','',$filename) . '" class="ref">' . $file_module . ' &ndash; Assignment ' . $file_split[1] . '</a></li>';
	}
	$output .= '</ul>You can also view the <a href="/btec/README" class="ref">README</a> and <a href="/btec/LICENSE" class="ref">LICENSE</a> documents.';
}

$protocol = (empty($_SERVER['HTTPS']) ? "http://" : "https://");
$prefix = explode("/btec/",$_SERVER['REQUEST_URI'])[0];
// change semi-absolute links with real ones
$output = str_replace(
	"/btec/",
	$protocol . $_SERVER['SERVER_NAME'] . $prefix . "/btec/",
	$output . "</section></body></html>"
);

echo $output;

?>
