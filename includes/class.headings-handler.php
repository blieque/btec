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
		 * This function accepst a list of headings in markdown formatting.
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
		
		$index			= array(0, null, null, null, null, null);			// keeps track of position in headings
		$current_level	= 2;												// headings will never be bigger than h2

		$output			= array();

		foreach ($headings as &$heading) {
			
			preg_match("/[#]+/", $heading, $hashes);							// isolate hashes at the beginning
			$heading_level	= strlen($hashes[0]);								// number of hashes -> size of heading

			if ($heading_level > $current_level) {								// if heading is smaller than the previous

				$index[$heading_level - 2]	= 1;
				$current_level++;

			} else if ($heading_level < $current_level) {						// if heading is bigger than the previous

				$index[$current_level - 2]	= null;
				$index[$current_level - 3]++;
				$current_level--;

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

		$output	= array();
		$index	= $this->index_headings($headings);

		for ($i = 0; $i < count($index); $i++) {

			$heading_level	= count(explode(".", $index[$i])) + 1;
			$heading_id		= $index[$i];

			$heading_new	= preg_replace("/[#]+ (.*)/", "<h$heading_level id=\"$heading_id\"><a href=\"/btec/$assignment_id#$heading_id\" title=\"permalink\">permalink</a>$1</h$heading_level>", $headings[$i]);
			array_push($output, $heading_new);

		}

		return $output;

	}

	public function contents($markdown) {

	}

}