<?php
error_reporting(1);
include 'image.php';




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

function ignoreFile($filename, $pattern){
    return !(preg_match('/logo|ajax/i', $filename));
}

if(isset($_POST['path_to_images']) && !empty($_POST['path_to_images'])){

    $main_dir = 'placeholders';
    // unlink($main_dir);
    // die;
    mkdir($main_dir);

    xcopy($_POST['path_to_images'], 'placeholders');
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('placeholders'));
    $files = array(); 
    foreach ($rii as $file) {
        if ($file->isDir()){ 
            continue;
        }

        if(is_image( $file->getPathname()) && ignoreFile($file->getFilename()) ){
            $files[] = $file->getPathname(); 
            $size = getimagesize($file->getPathname()); 
             generatePlaceholder($file->getPathname(), $size[0], $size[1] );
        }

    }
    xcopy('placeholders', $_POST['path_to_images'].'/placeholders');
    
}

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <title></title>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119393106-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-119393106-1');
</script>

</head>

<body>

<div class="container">
<div class="col-md-12">
<div class="well">
    
    <form method="post">
        <h3>Generate placeholders</h3>
        <fieldset>
            <div class="form-group">
                <label>Path to images:</label>
                <input type="text" class="form-control" name="path_to_images" value="/Users/abuzer/html/4wheels-html/images2">
            </div>
            <div class="form-group">
                <label>Ignore Files:</label>
                <input type="text" class="form-control" name="ignore_files" value="logo|ajax">
            </div>
            <div class="form-group">
                <label>Ignore Extensions:</label>
                <input type="text" class="form-control" name="ignore_extensions" value="">
            </div>
           
            <input type="submit" name="Generate Placeholders">
            <p></p>
            <?php
                if( isset($_POST['path_to_images']) && empty($_POST['path_to_images'])){
                    echo '<div class="alert alert-danger">Enter path to images</div>';
                }
             ?>
        </fieldset>
    </form>
</div>
</div>
</div>

</body>
</html>
