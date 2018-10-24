<?php
include 'functions.php';
$servername = "localhost";
$username = "peachstu_placeho";
$password = "google.123";
$dbname = "peachstu_placeholders";


if( $_SERVER['SERVER_NAME'] == 'localhost' ){


$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "placeholder";

}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 



 
 $page = (int)(!isset($_GET["page"]) ? 1 : $_GET["page"]);
if ($page <= 0) $page = 1;
 
$per_page = 10; // Set how many records do you want to display per page.
 
$startpoint = ($page * $per_page) - $per_page;
 
$statement = "`images` where width > 250 ORDER BY `id` ASC"; // Change `records` according to your table name.
  
 


?>
<!DOCTYPE html>
<html>
<head>
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/css/custom.css">
    <title>Placeholder generator | create image placeholders in few click</title>
<meta name="description" content="PlaceHolder Generator - tool to generator placeholders for images in sub folders- dummy image generator - placeholder image person"/>
        <link rel="stylesheet" href="/css/colorpicker.css" type="text/css" />
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
           /*   position: absolute;
              bottom: 0;*/
              width: 100%;
              /* Set the fixed height of the footer here */
              height: 60px;
              background-color: #f5f5f5;
            }
            .container .text-muted {
  margin: 20px 0;
}


        </style>
   
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
        <li><a href="/gallery.php">Gallery</a></li>
        <li><a href="https://github.com/xaraartech/placeholder-generator/issues" target="_blank">Contact us</a></li>
      </ul>
  
    </div><!-- /.navbar-collapse -->
    </div>
  </div><!-- /.container-fluid -->
</nav>


<!-- Reference: https://github.com/ashleydw/lightbox/ -->


<div class="container mt40" style="margin-top:80px;">
    <section class="row">

<?php
 $sql = "SELECT * FROM images where width >250 order by id desc LIMIT {$startpoint} , {$per_page}";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        ?>
     <!--    <a href="http://placeholdersgenerator.com/<?php echo $row['path'] ?>" target="_blank">
        <img src="http://placeholdersgenerator.com/<?php echo $row['path'] ?>" class="rounded mx-auto d-block" style="padding: 2px; border: 1px solid #ccc; margin: 5px;" alt="<?php echo $row['title'] ?> | placeholdersgenerator.com"  height="236">
        </a>
 -->
        <article class="col-xs-12 col-sm-6 col-md-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a   target="_blank" style="max-height: 200px; height: 200px;" href="http://placeholdersgenerator.com/<?php echo $row['path'] ?>" title="<?php echo $row['title'] ?> | placeholdersgenerator.com" class="zoom" data-title="<?php echo $row['title'] ?>" >
                        <img src="http://placeholdersgenerator.com/<?php echo $row['path'] ?>" alt="<?php echo $row['title'] ?> | placeholdersgenerator.com"  />
                        <span class="overlay"><i class="glyphicon glyphicon-fullscreen"></i></span>
                    </a>
                </div>
                <div class="panel-footer">
                    <h4><a href="http://placeholdersgenerator.com/<?php echo $row['path'] ?>" title="<?php echo $row['title'] ?> | placeholdersgenerator.com"><?php echo $row['title'] ?></a></h4>
                   <!--  <span class="pull-right">
                        <i id="like1" class="glyphicon glyphicon-thumbs-up"></i> <div id="like1-bs3"></div>
                        <i id="dislike1" class="glyphicon glyphicon-thumbs-down"></i> <div id="dislike1-bs3"></div>
                    </span> -->
                </div>
            </div>
        </article>
        <?php
    }
} else {
    echo "0 results";
}

?>

                                                  

</section>
</div>

<div class="container" style="margin-top:80px;">

<div class="row">


<div class="col-md-12">




<?php
echo pagination($statement,$per_page,$page,$url='?');
 ?>
</div>
    
</div>



</div>
<footer class="footer">
      <div class="container">
        <p class="text-muted">All rights reserved 2016.</p>
      </div>
    </footer>

</body>
<script src="/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/custom.js"></script>

    <script type="text/javascript" src="/js/colorpicker.js"></script>
<!-- <script src="//rawgithub.com/ashleydw/lightbox/master/dist/ekko-lightbox.js"></script> -->
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
