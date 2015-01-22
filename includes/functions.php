<?php

/* functions:
 *
 * Holds any functions I need in index.php. This is just to keep things tidy.
 * 
 * functions include:
 *  - minify: strips the rubbish out of HTML
 * 
 */

# requires class.parsedown.php

function minify($input) {

	$output	= $input;

	$output	= preg_replace("/[\t]*/", "", $output);						// tab indentation
	$output	= preg_replace("/<!\-\-.*?\-\->/", "", $output);			// HTML comments

	return $output;

}

function process_includes($markdown) {

	preg_match_all("/(?<=<!--\[INCLUDE\] )[A-z0-9\-_\/.]*.[md|txt|html|vba|conf|py|c](?= -->)/", $markdown, $includes);

	foreach ($includes[0] as &$include) {

		if (file_exists($include)) {

			# read the requested file

			$file_contents	= file_get_contents($include);

			# process the contents, for some filetypes

			$include_split		= explode(".", $include);
			$include_directory	= explode("/", $include)[0];

			if ($include_directory == "file") {

				$include_extension	= end($include_split);
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

				} else if ($include_category == "article") {

					$parsedown_article	= new Parsedown();
					$file_contents		= $parsedown_article->text($file_contents);
					$file_contents		= str_replace("<!--[COL-1]-->", "<div class=\"col-1\">", $file_contents);
					$file_contents		= str_replace("<!--[COL-2]-->", "</div><div>", $file_contents);
					$file_contents		= $file_contents . "</div>";
					$file_contents		= "<hr><article>" . $file_contents . "</article><hr>";

				}

				if ($include_extension == "html") {

					preg_match("/<body[A-z0-9='.,:; \"]*>((?s).*?)<\/body>/", $file_contents, $file_contents);
					$file_contents	= $file_contents[1];
					$file_contents	= preg_replace("/[\n\r\t]*/", "", $file_contents);	// prevents markdown from converting markup to HTML entities

				}

			}

			$file_contents	= process_includes($file_contents);

			# replace include line with processed file contents

			$include		= preg_quote($include,"/");							// escape regex syntax
			$markdown		= preg_replace("/<!--\[INCLUDE\] $include -->/", $file_contents, $markdown);

		}

	}

	return $markdown;

}
