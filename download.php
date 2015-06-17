<?php

# get sanitised

$base_path = realpath('file/') . '/';

$path = str_replace('..', '', $_GET['path']); // remove cheeky relative shenanigans
$path = trim($path, '/'); // remove preceding and trailing slashes
$path = preg_replace("/[\/]+/", '/', $path);
$path = $base_path . $path;

# exit if there's nothing to download

if (!file_exists($path)) {
	header('HTTP/1.0 404 Not Found');
	exit;
}

# handle files

$served_file;
$served_file_mime;
$delete_served_file = false;

if (is_dir($path)) {

	// get name for zip file
	$path_explode = explode('/', $path);
	$dir_name = $path_explode[count($path_explode) - 1];
	$zip_name = $dir_name . '.zip'; // sanitising is taken care of at rewite stage

	// initialise archive object
	$zip = new ZipArchive();
	$zip->open('/tmp/' . $zip_name, ZipArchive::CREATE | ZipArchive::OVERWRITE);

	// create recursive directory iterator
	$files = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($path),
		RecursiveIteratorIterator::LEAVES_ONLY
	);

	foreach ($files as $name => $file) {

		// skip directories (added automatically)
		if (!$file->isDir()) {

			// get real and relative paths for current file
			$file_path = $file->getRealPath();
			$relative_path = substr($file_path, strlen($path) + 1);

			// add current file to archive
			$zip->addFile($file_path, $relative_path);

		}

	}

	// compress files
	$zip->close();

	$served_file = '/tmp/' . $zip_name;
	$served_file_mime = 'application/zip';
	$delete_served_file = true;

} else if (is_file($path)) {

	$served_file = $path;

	# get mime of file
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$served_file_mime = finfo_file($finfo, $served_file);
	finfo_close($finfo);

}

# set up download headers

header('Content-Description: File Transfer');
header('Content-Type: ' . $served_file_mime);
header('Content-Disposition: attachment; filename=' . basename($served_file));
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Content-Length: ' . filesize($served_file));

# imperfect, but it will do
readfile($served_file);

# this should be cached, but time is of the essence and i'm lazy
if ($delete_served_file) {
	unlink($served_file);
}

exit;
