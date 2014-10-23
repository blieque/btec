<?php

$output		= "<!DOCTYPE html><html><head><title>";
$name		= array(explode(".",$_GET["a"]),						// e.g., ["2","3"] for C.S. As. 2
			array(
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
			),
			NULL // probably "Assignment X", basically a title
		);
$specified	= isset($_GET['a']);									// has an assignment or doc been specified?
$isassign	= count($name[0]) > 1;									// if false, we're looking at a README, LICENSE or /ext/ document

if ($specified) {
	if ($isassign) {
		$name[2] = "Assignment " . $name[0][1];
		$output .= $name[1][$name[0][0]] . " &ndash; " . $name[2];			// append title to output variable
	} else {
		if ($name[0][1]) {
			
		}
		$name[2] = ucfirst(strtolower($name[0][0]));						// "Readme" or "License" probably
		$output .= $name[2];
	}
} else {
	$output .= 'IT BTEC Assignments';
}

$output .= '</title><link rel="stylesheet" href="/btec/m.css"></head><body><section>';

if ($specified) {
	// given an id of exisiting file, process markdown
	if (file_exists($markdown = $_GET['a'] . ".md")) {
		$markdown = file_get_contents($markdown);

		// find task levels (PX, MX or DX)
		$matches = NULL;
		preg_match_all("/\[[P|M|D][0-9]+\]/",$markdown,$matches);
		$matches = $matches[0];												// we don't want no array-in-an-array bull
		foreach ($matches as &$match) {										// iterate through the array
			$match = preg_replace("/\[([A-Z0-9]+)\]/","$1",$match);
		}
		$matchesstr = implode(", ",$matches);

		include "Parsedown.php";
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
	foreach (glob('*.*.md') as $filename) {
		$file_split = explode(".",$filename);
		$file_module = $name[1][$file_split[0]];
		$output .= '<li><a href="/btec/' . str_replace('.md','',$filename) . '" class="ref">' . $file_module . ' &ndash; Assignment ' . $file_split[1] . '</a></li>';
	}
	$output .= '</ul>You can also view the <a href="/btec/README" class="ref">README</a> and <a href="/btec/LICENSE" class="ref">LICENSE</a> documents.';
}

$prefix = explode("/btec/",$_SERVER['REQUEST_URI'])[0];
$protocol = (empty($_SERVER['HTTPS']) ? "http://" : "https://");
// change semi-absolute links with real ones
$output = str_replace(
	"/btec/",
	$protocol . $_SERVER['SERVER_NAME'] . $prefix . "/btec/",
	$output . "</section></body></html>"
);

echo $output;

?>