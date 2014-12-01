<?php

/* contents:
 *
 * Class for processing markdown headings. The two public functions do the
 * following:
 *  - replacement_array(): accepts an array of markdown-formatted headings and
 *      the assignment id, and returns another array of id'd, HTML headings.
 *  - contents(): accepts an array of markdown-formatted headings and returns a
 *      string holding an HTML contents list, wiki-style.
 * 
 */

class HeadingsHandler {

	private function index_headings($headings) {

		/*
		 * This function accepts a list of headings in markdown formatting.
		 *
		 * The function returns an array of strings that act as identifiers for
		 * headings. The output is the numbers for blocks of text in a document,
		 * which is used to create a numbered contents list very much like a
		 * Wikipedia article has.
		 *
		 * The output of this function is used by the other two functions in
		 * this class, replacement_array() and contents().
		 *
		 */
		
		$index				= array(0, null, null, null, null, null);		// keeps track of position in headings
		$heading_level_prev	= 2;											// headings will never be bigger than h2

		$output				= array();

		foreach ($headings as &$heading) {
			
			preg_match("/[#]+/", $heading, $hashes);							// isolate hashes at the beginning
			$heading_level	= strlen($hashes[0]);								// number of hashes -> size of heading

			if ($heading_level > $heading_level_prev) {							// if heading is smaller than the previous

				$heading_level_prev			= $heading_level;
				$index[$heading_level - 2]	= 1;

			} else if ($heading_level < $heading_level_prev) {					// if heading is bigger than the previous

				// for ($i = $heading_level; $i < 7; $i++) { 

				// 	$index[$i - 1]				= null;
					
				// }

				$heading_level_prev			= $heading_level;
				$index[$heading_level - 2]++;

			} else {															// if heading is the same size as the previous

				$index[$heading_level - 2]++;

			}

			$heading_id		= array();

			foreach ($index as &$i) {											// create an id from index variable
				if ($i > 0) {
					array_push($heading_id, $i);
				}
			}

			$heading_id		= implode(".", $heading_id);
			array_push($output, $heading_id);

		}

		return $output;

	}

	public function replacement_array($headings, $assignment_id) {

		/*
		 * This function formats markdown headings as HTML, IDing them in the
		 * process, and adds the permalink anchor to them as well.
		 * 
		 * The function accepts an array of markdown headings, and the ID of the
		 * page the headings are on. This will most-likely be something like
		 * "2.3" or "readme". The latter is required for good permalinks.
		 * 
		 * The function returns an array of HTML headings that correlates with
		 * the headings it was originally given. These are placed on the page
		 * using a simple find and replace.
		 * 
		 */


		$output	= array();
		$index_of_headings	= $this->index_headings($headings);

		for ($i = 0; $i < count($index_of_headings); $i++) {

			$heading_level	= count(explode(".", $index_of_headings[$i])) + 1;
			$heading_id		= $index_of_headings[$i];

			$heading_new	= preg_replace("/[#]+ (.*)/", "<h$heading_level id=\"$heading_id\"><a href=\"/btec/$assignment_id#$heading_id\" title=\"permalink\">permalink</a>$1</h$heading_level>", $headings[$i]);
			array_push($output, $heading_new);

		}

		return $output;

	}

	public function contents($headings) {

		/*
		 * This function generates an HTML-formatted, un-ordered (although in
		 * fact ordered) list of anchors. This can be embedded directly in a
		 * page as a contents list.
		 * 
		 * The function accepts an array of markdown-formatted headings 
		 * 
		 */

		if (count($headings) < 1) {

			return 1;															// abandon ship if no headings have been supplied.

		}

		$index_of_headings	= $this->index_headings($headings);
		$output				= "<h6>Contents</h6><ul><li>";

		$heading_level_prev	= 1;

		for ($i = 0; $i < count($headings); $i++) { 

			$heading_level	= count(explode(".", $index_of_headings[$i]));
			$heading_text	= preg_replace("/[#]+ /", "", $headings[$i], 1);

			if ($i > 0) {														// we don't want to add a divider on the first round

				if ($heading_level > $heading_level_prev) {							// if heading is smaller than the previous

					$output			   .= "<ul><li>";
					$heading_level_prev	= $heading_level;

				} else if ($heading_level < $heading_level_prev) {					// if heading is bigger than the previous

					$output			   .= "</li></ul></li><li>";
					$heading_level_prev	= $heading_level;

				} else {															// if heading is the same size as the previous

					$output    .= "</li><li>";

				}

			}

			$bold	= "";

			if (preg_match("/\[[P|M|D][0-9]\]/", $headings[$i])) {
				
				$bold	= " class=\"b\"";

			}

			$index		= $index_of_headings[$i];
			$output    .= "<a href=\"#$index\"$bold>$index: $heading_text</a>";

		}

		$output	    .= str_repeat("</li></ul>", $heading_level_prev);

		return $output;

	}

}