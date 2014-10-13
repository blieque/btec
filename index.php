<?php

$output = "<!DOCTYPE html><html><head><title>";
$specified = isset($_GET['a']);
// name = [assignment id from uri, array of module names, array from id split by full stop, relevant assignment no.]
$name = array(explode(".",$_GET["a"]),
		array(
			1  => "Communication and Employability Skills",
			2  => "Computer Systems",
			3  => "Information Systems",
			6  => "Software Design & Development",
			8  => "e-Commerce",
			9  => "Computer Networks",
			11 => "Systems Analysis",
			12 => "IT Technical Support ",
			14 => "Event Driven Programming",
			19 => "Computer Systems Architecture ",
			20 => "Client-Side Customisation of Web Pages ",
			22 => "Developing Computer Games",
			27 => "Web Server Scripting",
			28 => "Website Production",
			31 => "Computer Animation",
			42 => "Spreadsheet Modelling"
		),
		NULL // "Assignment X"
	);

if ($specified) {									// check a module is specified, and that we're doing it
	$name[2] = "Assignment " . $name[0][1];
	$output .= $name[1][$name[0][0]] . " &ndash; " . $name[2];				// append title to output variable
} else {
	$output .= 'IT BTEC Assignments';
}

$output .= '</title><link rel="stylesheet" href="/btec/m.css"></head><body><section>';

if ($specified) {
	// given an id of exisiting file, process markdown
	if (file_exists($markdown = $_GET['a'] . ".md")) {
		$markdown = file_get_contents($markdown);

		// find task levels (PX, MX or DX)
		

		include "Parsedown.php";
		$pd = new Parsedown();
		$output .= "<h1>Unit " . $name[0][0] . " &ndash; " . $name[1][$name[0][0]] . "</h1><h3>" . $name[2] . ": " . $pd->text($markdown);
	} else {
		$output .= '404: Assignment not found.<br><a href="/btec/">Index</a>';
	}
} else {
	// no assignment id given
	$output .= 'No assignment specified.<br>Maybe one of these will take your fancy.<ul>';
	foreach (glob('*.md') as $filename) {
		$file_split = explode(".",$filename);
		$file_module = $name[1][$file_split[0]];
		$output .= '<li><a href="/btec/' . str_replace('.md','',$filename) . '">' . $file_module . ' &ndash; Assignment ' . $file_split[1] . '</a></li>';
	}
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