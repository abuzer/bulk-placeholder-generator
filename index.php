<?php



//error_reporting(1);
//ini_set('error_reporting', -1);
//ini_set('display_errors', 1);
//ini_set('html_errors', 1); // I use this because I use xdebug.
$link = '';
include 'image.php';
function url(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

?>
<!DOCTYPE html>
<html>
<head>
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <title>Placeholder generator | create image placeholders in few click</title>
<meta name="description" content="PlaceHolder Generator - tool to generator placeholders for images in sub folders- dummy image generator - placeholder image person"/>
        <link rel="stylesheet" href="css/colorpicker.css" type="text/css" />
        <style type="text/css">
            .placeholder-preview{
                background: #ccc;
                color: #000;
                margin: 0 auto;
                font-size: 24px;
                height: 300px;
                width: 300px;
                text-align: center;
                vertical-align: middle;
                display: table-cell;
            }
            .well{
                min-height: 520px;
            }
            .footer {
              position: absolute;
              bottom: 0;
              width: 100%;
              /* Set the fixed height of the footer here */
              height: 60px;
              background-color: #f5f5f5;
            }
            .container .text-muted {
  margin: 20px 0;
}


        </style>
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

<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="col-md-12"> 
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Placeholders</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="/">Home <span class="sr-only">(current)</span></a></li>
<!--        <li><a href="/gallery.php">Gallery</a></li> -->
        <li><a href="https://github.com/xaraartech/placeholder-generator/issues" target="_blank">Contact us</a></li>
      </ul>
  
    </div><!-- /.navbar-collapse -->
    </div>
  </div><!-- /.container-fluid -->
</nav>

<div class="container" style="margin-top:80px;">

<div class="col-md-8">

<div class="well">
    
    <form method="post" enctype="multipart/form-data" onsubmit="return confirm('I agree the terms of use of placeholder generate?');">
        <h3>Generate placeholders</h3>
        <p>This tools helps to generate placeholder of nested images folders of your theme to be published.
        </p>
        <fieldset>
            
            <div class="form-group">
                <label>Zip of images:</label>
                <input type="file" name="file" id="images-zip" />
                <p class="help-block">Zip of images folder, containing all the files and folders to be converted to placeholders.</p>
                <span class="alert-danger" style="display: none;" id="zip-image-error"> Select zip file only</span>
            </div>
            <div class="form-group">
                <label>Ignore Files:</label>
                <input type="text" class="form-control" name="ignore_files" value="logo|ajax">
                <p class="help-block">Images containing words to ignore in placeholder. Separated by "|" sign</p>
            </div>
             <!-- <div class="form-group">
                <label>Watermark:</label>
                <input type="text" class="form-control" name="watermark" value="" placeholder="Watermark text">
                <p class="help-block"></p>
            </div> -->
             <div class="form-group">
                <label>Text color:</label>
                <input type="color" class="colorSelector" name="text_color" value="#000000">
                <p class="help-block"></p>
            </div>
            <div class="form-group">
                <label>Background color:</label>
                <input type="color" class="colorSelector1" name="bg_color" value="#cacaca">
                <p class="help-block"></p>
            </div>
            <!-- <div class="form-group">
                <label>Ignore Extensions:</label>
                <input type="text" placeholder="jpg|png|gif" class="form-control" name="ignore_extensions" value="">
                <p class="help-block">Extensions to ignore in placeholders. Leave blank to convert all.</p>
            </div> -->
           
            <input type="submit" class="btn btn-default" disabled="" title="Select zip first" id="generate-btn" name="Generate Placeholders">
            <p></p>
            <?php
                if( isset($_POST['path_to_images']) && empty($_POST['path_to_images'])){
                    echo '<div class="alert alert-danger">Enter path to images</div>';
                }
             ?>
        </fieldset>
    </form>
    <?php if(!empty($link)): ?>
            <p class="download-link"><a href="<?php echo  $link ; ?>"  class="btn btn-success" target="_blank">Download placeholders</a></p>

        Copy link: <input type="text" name="" value="<?php echo url().$link ?>" size="60" />
    <?php endif; ?>
</div>  
</div>

<div class="col-md-4">

<div class="well">
<h3>Preview</h3>
        <p>This is how your placeholder will look</p>
        <div class="placeholder-preview">
            300x300
        </div>

</div></div>



</div>
<footer class="footer">
      <div class="container">
        <p class="text-muted">All rights reserved 2016.</p>
      </div>
    </footer>

</body>
<script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript" src="js/colorpicker.js"></script>

    <script type="text/javascript">
            $(function(){
                $('#images-zip').change(function () {
                    $('.download-link').html('');
                    var ext = this.value.match(/\.(.+)$/)[1];
                    switch (ext) {
                        case 'zip':
                            $('#generate-btn').attr('disabled', false);
                            $('#zip-image-error').hide();

                            break;
                        default:
                            $('#generate-btn').attr('disabled', true);
                            $('#zip-image-error').show();


                            this.value = '';
                    }
                });  

                $('.colorSelector').ColorPicker({
                    color: '#000000',
                    onShow: function (colpkr) {
                        $(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        $(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        //$('.colorSelector').val('#' + hex);
                        $('.colorSelector').attr('value','#' + hex);
                        $('.placeholder-preview').css('color', '#' + hex);
                    }
                });   $('.colorSelector1').ColorPicker({
                    color: '#cacaca',
                    onShow: function (colpkr) {
                        $(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        $(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        $('.colorSelector1').attr('value','#' + hex);
                        $('.placeholder-preview').css('background-color', '#' + hex);

                    }
                });
            });


    </script>
</html>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/583972697295ad7394d8d572/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-79244337-3', 'auto');
  ga('send', 'pageview');

</script>
