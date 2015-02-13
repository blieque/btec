<?php

/* functions:
 *
 * Holds any functions I need in index.php. This is just to keep things tidy.
 * 
 * Functions include:
 *
 *  - minify: Strips the rubbish out of HTML.
 *  - process_specials: Process certain filetypes before Ciconia does.
 *  - process_includes: Handles custom includes in markdown documents.
 *  - new_gfm: Returns a new GitHub-flavoured Ciconia-class object.
 * 
 */

function minify($input) {

	$output	= $input;

	$output	= preg_replace("/[\t]*/", "", $output);						// tab indentation
	$output	= preg_replace("/<!\-\-.*?\-\->/", "", $output);			// HTML comments

	return $output;

}

function process_specials($include, $file_contents) {

	$include_directory	= explode("/", $include)[0];	// e.g., "markdown"
	$include_dotsplit	= explode(".", $include);		// e.g., ["markdown/ext/06", "1-sdlc", "md"]
	$include_extension	= end($include_dotsplit);		// e.g., "md"

	if ($include_directory == "file") {

		preg_match("/(?<=file\/)(.*?)(?=\/)/", $include, $include_category);
		$include_category	= $include_category[0];

		if ($include_category == "code") {

			$file_contents	= str_replace("\\", "\\\\", $file_contents);
			preg_match_all("/.*?\t/", $file_contents, $tab_indents);

			foreach ($tab_indents[0] as &$tab_indent) {

				$tab_width		= 4;
				$position		= strlen($tab_indent) - 1;
				$space_count	= $tab_width - $position % $tab_width;
				$space_count	= $space_count < 4 ? $space_count + substr_count($tab_indent, "\\n") : $space_count;
				$file_contents	= preg_replace("/\t/", str_repeat(" ", $space_count), $file_contents, 1);

			}

			$file_contents		= preg_replace("/(.*\n)/", "\t$1", $file_contents);							// indent each line with a tab for markdown

		} else if ($include_extension == "html") {

			preg_match("/<body[A-z0-9='.,:; \"]*>((?s).*?)<\/body>/", $file_contents, $file_contents);
			$file_contents	= $file_contents[1];
			$file_contents	= preg_replace("/[\n\r\t]*/", "", $file_contents);	// prevents markdown from converting markup to HTML entities

		}

	}



	if ($include_extension == "md") {

		preg_match("/(?<=<!--\[TEMPLATE\] )(.*?)(?= -->)/", $file_contents, $include_template);

		if (isset($include_template[0])) {

			$include_template	= $include_template[0];
			
			$ciconia		= new_gfm();
			$file_contents	= $ciconia->render($file_contents);

			if ($include_template == "article") {

				$file_contents	= str_replace("<!--[COL-1]-->", "<div class=\"col-1\">", $file_contents);
				$file_contents	= str_replace("<!--[COL-2]-->", "</div><div class=\"col-2\">", $file_contents);
				$file_contents	= $file_contents . "</div>";
				$file_contents	= "<hr><article>" . $file_contents . "</article><hr>";

			} elseif ($include_template == "timeline") {
				
				$file_contents	= "<div class=\"tl\">" . $file_contents . "<div>";

			}

			$second_to_last	= $include_dotsplit[count($include_dotsplit) - 2];
			if (strlen($second_to_last) > 12 &&
				substr_compare($second_to_last, "-corrections", -12) == 0) {
				$file_contents = "<div class=\"n\"><span class=\"hb\">Blue highlighting</span> indicates new passages of text.</div>" . $file_contents;
			}

		}

	}

	return $file_contents;

}

function process_includes($markdown) {

	preg_match_all("/(?<=<!--\[INCLUDE\] )[A-z0-9\-_\/.]*.[md|txt|html|vba|conf|py|c](?= -->)/", $markdown, $includes);

	foreach ($includes[0] as $include) {

		if (file_exists($include)) {

			# read the requested file
			$file_contents	= file_get_contents($include);
			$file_contents	= process_specials($include, $file_contents);

			# process includes in includes
			$file_contents	= process_includes($file_contents);

			# replace include line with processed file contents
			$include_quote	= preg_quote($include,"/");
			$markdown		= preg_replace("/<!--\[INCLUDE\] $include_quote -->/", $file_contents, $markdown);

		}

	}

	return $markdown;

}

function new_gfm() {

	$ciconia = new \Ciconia\Ciconia();
	$ciconia->addExtension(new \Ciconia\Extension\Gfm\FencedCodeBlockExtension());
	$ciconia->addExtension(new \Ciconia\Extension\Gfm\TaskListExtension());
	$ciconia->addExtension(new \Ciconia\Extension\Gfm\InlineStyleExtension());
	$ciconia->addExtension(new \Ciconia\Extension\Gfm\WhiteSpaceExtension());
	$ciconia->addExtension(new \Ciconia\Extension\Gfm\TableExtension());
	$ciconia->addExtension(new \Ciconia\Extension\Gfm\UrlAutoLinkExtension());

	return $ciconia;

}
