<?php

//the folders basename
$bfolder = uniqid("folder", true);

//full path
$folder = __DIR__ . $bfolder . "/";

//Create folder
mkdir($folder);

//upload files
if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){

	$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

	$path =  $folder . uniqid("", true) .  "." . $extension;
	
	if(!move_uploaded_file($_FILES['file']['tmp_name'], $path)){
		echo 'Error uploading file';
	}
}

//create index.html
file_put_contents($folder . "index.html", $_POST["html"]);


// Initialize archive object
$zip = new ZipArchive;
$zip->open("zip" . $bfolder, ZipArchive::CREATE);

// Initialize empty "delete list"
$filesToDelete = array();

// Create recursive directory iterator
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($folder),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file) {
    // Get real path for current file
    $filePath = $file->getRealPath();

    // Add current file to archive
    $zip->addFile($filePath);

    // Add current file to "delete list" (if need)
    if ($file->getFilename() != 'important.txt') 
    {
        $filesToDelete[] = $filePath;
    }
}

// Zip archive will be created only after closing object
$zip->close();


//remove dir
rmdir($folder);

//header("Location: " . $bfolder);