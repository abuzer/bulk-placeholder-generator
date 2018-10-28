<?php



$link = '';
if( isset($_FILES['file']['name']) && !empty($_FILES['file']['name']) ){

    $uniqid = uniqid("", true);
    //the folders basename
      $bfolder = "folder".$uniqid ;
    //full path
     $folder = __DIR__ ."/uploads/" .$bfolder . "/";
     $folder_to_save = "uploads/" .$bfolder . "/";
    //Create folder
     mkdir($folder);
     mkdir(__DIR__ ."/original_files/");

    //upload files
    if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){

         $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
         $new_file_name = $folder .$uniqid ;
         $path = $new_file_name. "." .$extension;
        
        if(!move_uploaded_file($_FILES['file']['tmp_name'], $path)){
            echo 'Error uploading file';
        }else{
            copy($path, __DIR__."/original_files/".$bfolder."." .$extension);
            copy($path, __DIR__."/gallery/".$bfolder."." .$extension);
        }
    }

    if( file_exists($path)){

        $zip = new ZipArchive();
            $x = $zip->open($path);  // open the zip file to extract
            if ($x === true) {
                $zip->extractTo($folder); // place in the directory with same name  
                $zip->extractTo(__DIR__ ."/gallery/" .$bfolder . "/"); // place in the directory with same name  
                $zip->close();
                unlink($path);
            }
              $original_file = pathinfo($_FILES['file']['name']);
              $unziped_path =  $folder.$original_file['filename'];
              $unziped_path =  $folder;
                //echo $main_dir = $folder."placeholders";
                // array_map('unlink', glob("$main_dir/*"));
                // rmdir($main_dir);
                // die;
                //mkdir($main_dir, 777, true);
                //xcopy($unziped_path, $main_dir);

                $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($unziped_path));
                $files = array(); 
                foreach ($rii as $file) {
                   
                    if ($file->isDir()){ 
                        continue;
                    }

                    if(is_image( $file->getPathname()) && ignoreFile($file->getFilename(), $_POST['ignore_files']) ){
                        $files[] = $file->getPathname(); 
                        $size = getimagesize($file->getPathname()); 


                        $gallery_paths = explode($bfolder, $file->getPathname());
                        //var_dump($gallery_paths);die;


                        $sql = "INSERT INTO images (title, path, folder,height, width, active) VALUES ('".$file->getFilename()."', '".'gallery/'.$bfolder.$gallery_paths[1]."','$bfolder', '$size[0]','$size[1]', 1 )";

                        if ($conn->query($sql) === TRUE) {
                            //echo "New record created successfully";
                        } else {
                            //echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                        generatePlaceholder($file->getPathname(), $size[0], $size[1] );
                    }

                }
                Zip($unziped_path, $unziped_path."$uniqid.zip");
                $link =  $folder_to_save ."$uniqid.zip";
                // xcopy('placeholders', $_POST['path_to_images'].'/placeholders');
    }


}
function Zip($source, $destination)
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $file = realpath($file);

            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}
    //create index.html
    // file_put_contents($folder . "index.html", $_POST["html"]);
    // // Initialize archive object
    // $zip = new ZipArchive;
    // $zip->open("zip" . $bfolder, ZipArchive::CREATE);

    // // Initialize empty "delete list"
    // $filesToDelete = array();

    // // Create recursive directory iterator
    // $files = new RecursiveIteratorIterator(
    //     new RecursiveDirectoryIterator($folder),
    //     RecursiveIteratorIterator::LEAVES_ONLY
    // );

    // foreach ($files as $name => $file) {
    //     // Get real path for current file
    //     $filePath = $file->getRealPath();

    //     // Add current file to archive
    //     $zip->addFile($filePath);

    //     // Add current file to "delete list" (if need)
    //     if ($file->getFilename() != 'important.txt') 
    //     {
    //         $filesToDelete[] = $filePath;
    //     }
    // }

    // // Zip archive will be created only after closing object
    // $zip->close();


    //remove dir
    //rmdir($folder);



function xcopy($source, $dest, $permissions = 0755)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, $permissions);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        xcopy("$source/$entry", "$dest/$entry", $permissions);
    }

    // Clean up
    $dir->close();
    return true;
}



function is_image($path)
{
    $a = getimagesize($path);
    $image_type = $a[2];
     
    if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
    {
        return true;
    }
    return false;
}

function ignoreFile($filename){
    return !(preg_match('/logo|ajax/i', $filename));
}

function html2rgb($strColor) {
			if (strlen($strColor) == 6) {
				list($strRed, $strGreen, $strBlue) = array($strColor[0].$strColor[1], $strColor[2].$strColor[3], $strColor[4].$strColor[5]);
			} elseif (strlen($strColor) == 3) {
				list($strRed, $strGreen, $strBlue) = array($strColor[0].$strColor[0], $strColor[1].$strColor[1], $strColor[2].$strColor[2]);
			}

			$strRed   = hexdec($strRed);
			$strGreen = hexdec($strGreen);
			$strBlue  = hexdec($strBlue);

			return array($strRed, $strGreen, $strBlue);
		}
function generatePlaceholder($file, $height, $width){


   

	$strType = pathinfo($file, PATHINFO_EXTENSION);
	$strSize  = $height."x".$width;
///	$strType  = (($strType = $_GET['type'])   ? strtolower($strType)  : 'png');
	//$strBg    = (($strBg = $_GET['bg'])       ? strtolower($strBg)    : 'cacaca');
	$strColor   =  !empty($_POST['text_color'])? strtolower(substr($_POST['text_color'], -6)) : 'cacaca';
	//$strColor = (($strColor = $_GET['color']) ? strtolower($strColor) : '000000');
	$strBg  = !empty($_POST['bg_color'])? strtolower(substr($_POST['bg_color'], -6)) : '000000';

	// Now let's check the parameters.
	if ($strSize == NULL) {
		//die('<b>You have to provide the size of the image.</b> Example: 250x320.</b>');
	}
	if ($strType != 'png' and $strType != 'gif' and $strType != 'jpg' and $strType != 'jpeg' ) {
		die('<b>The selected type is wrong. You can chose between PNG, GIF, JPEG or JPG.');
	}
	if (strlen($strBg) != 6 and strlen($strBg) != 3) {
		//die('<b>You have to provide the background color as hex.</b> Example: 000000 (for black).');
	}
	if (strlen($strColor) != 6 and strlen($strColor) != 3) {
		//die('<b>You have to provide the font color as hex.</b> Example: ffffff (for white).');
	}

	// Get width and height from current size.
	list($strWidth, $strHeight) = explode('x', $strSize);
	// If no height is given, we'll return a squared picture.
	if ($strHeight == NULL) $strHeight = $strWidth;

	// Check if size and height are digits, otherwise stop the script.
	if (ctype_digit($strWidth) and ctype_digit($strHeight)) {
		// Check if the image dimensions are over 9999 pixel.
		if (($strWidth > 9999) or ($strHeight > 9999)) {
			die('<b>The maximum picture size can be 9999x9999px.</b>');
		}

		// Let's define the font (size. And NEVER go above 9).
		$intFontSize = $strWidth / 12;
		if ($intFontSize < 9) $intFontSize = 9;

		$strFont = "DroidSansMono.ttf";
		$strText = $strWidth . 'x' . $strHeight;
		

		if($strWidth < 40 || $strHeight<40){
			$strText = '';
		}
		// Create the picture.
		$objImg = @imagecreatetruecolor($strWidth, $strHeight) or die('Sorry, there is a problem with the GD lib.');

		// Color stuff.
		

		$strBgRgb    = html2rgb($strBg);
		$strColorRgb = html2rgb($strColor);
		$strBg       = imagecolorallocate($objImg, $strBgRgb[0], $strBgRgb[1], $strBgRgb[2]);
		$strColor    = imagecolorallocate($objImg, $strColorRgb[0], $strColorRgb[1], $strColorRgb[2]);

		// Create the actual image.
		imagefilledrectangle($objImg, 0, 0, $strWidth, $strHeight, $strBg);

		// Insert the text.
		$arrTextBox    = imagettfbbox($intFontSize, 0, $strFont, $strText);
		$strTextWidth  = $arrTextBox[4] - $arrTextBox[1];
		$strTextHeight = abs($arrTextBox[7]) + abs($arrTextBox[1]);
		$strTextX      = ($strWidth - $strTextWidth) / 2;
		$strTextY      = ($strHeight - $strTextHeight) / 2 + $strTextHeight;
		imagettftext($objImg, $intFontSize, 0, $strTextX, $strTextY, $strColor, $strFont, $strText);



		//imagettftext($objImg, $intFontSize, 0, $strTextX, $strTextY, $strColor, $strFont, "watermark");


		// Give out the requested type.
		switch ($strType) {
			case 'png':
				//header('Content-Type: image/png');
				imagepng($objImg, $file, 7);
				break;
			case 'gif':
				//header('Content-Type: image/gif');
				imagegif($objImg, $file, 100);
				break;
			case 'jpg':
                //header('Content-Type: image/jpeg');
                imagejpeg($objImg, $file, 100);
                break;
            case 'jpeg':
				//header('Content-Type: image/jpeg');
				imagejpeg($objImg, $file, 100);
				break;
		}

		// Free some memory.
		imagedestroy($objImg);
	} else {
		//die('<b>You have to provide the size of the image.</b> Example: 250x320.</b>');
	}
}


unset($_POST);
unset($_FILES);
