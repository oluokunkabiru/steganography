<?php
include('includes/functions.php');

$mError = $fileError =$success = "";
$target_dir = "original/";
$encrypt = "encrypt/";
if(isset($_POST['generate'])){
    if(empty($_POST['message'])){
        $mError = "Please provide message to encryt";
    }else{
        $message = $_POST['message'];
        // echo convertMeToBinary($message);
    }
// echo convertMeToBinary($message);
    if($_FILES['image']['size']!=0){
       
        if(!is_dir($target_dir)){
            mkdir($target_dir);

        }
        if(!is_dir($encrypt)){
            mkdir($encrypt);
        }

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== true) {
            $fileError= "Image file is expected";
        }
        $filename = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        // $target_file = "vboy".time();
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
        if ($_FILES["image"]["size"] > 5000000) {
            $fileError = "Sorry, your file is too large, upload file with less than 5MB";
            
        }
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
        $fileError = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
        $image =$target_dir."vboy".time().".png";//.$imageFileType;
    
        if(empty($mError)){
            move_uploaded_file($_FILES["image"]["tmp_name"], $image);
            $split = explode('/', $image);
            $images = $split[1];
            // echo "Uploaded";
           $msg = $message; 

            $src = $image; 
            //Start image
            
            $msg .='|'; 
            //EOF sign, decided to use the pipe symbol to show our decrypter the end of the message
            $msgBin = convertMeToBinary($msg); 
            //Convert our message to binary
            $msgLength = strlen($msgBin); 
            //Get message length
            if($imageFileType=='jpg' || $imageFileType=='jpeg'){
            $img = imagecreatefromjpeg($src); 
            }else{
              $img = imagecreatefrompng($src);
            }
            //returns an image identifier
            list($width, $height, $type, $attr) = getimagesize($src); 
            //get image size
            
            if($msgLength>($width*$height)){ 
                //The image has more bits than there are pixels in our image
             $mError = 'Message too long. This is not supported as of now.';
              die();
            }
            
            $pixelX=0;
             //Coordinates X of our pixel that we want to edit
            $pixelY=0; 
            //Coordinates Y of our pixel that we want to edit
            
            for($x=0;$x<$msgLength;$x++){ 
                //Encrypt message bit by bit (literally)
            
              if($pixelX === $width+1){ 
                  //If this is true, we've reached the end of the row of pixels, start on next row
                $pixelY++;
                $pixelX=0;
              }
            
              if($pixelY===$height && $pixelX===$width){ 
                  //Check if we reached the end of our file
                 $mError= 'Max Reached';
                die();
              }
            
              $rgb = imagecolorat($img,$pixelX,$pixelY); 
              //Color of the pixel at the x and y positions
              $r = ($rgb >>16) & 0xFF; 
              //returns red value for example int(119)
              $g = ($rgb >>8) & 0xFF; 
              //^^ but green
              $b = $rgb & 0xFF;
              //^^ but blue
            
              $newR = $r; 
              //we dont change the red or green color, only the lsb of blue
              $newG = $g; 
              //^
              $newB = convertMeToBinary($b); 
              //Convert our blue to binary
              $newB[strlen($newB)-1] = $msgBin[$x]; 
              //Change least significant bit with the bit from out message
              $newB = toString($newB); 
              //Convert our blue back to an integer value (even though its called tostring its actually toHex)
            
              $new_color = imagecolorallocate($img,$newR,$newG,$newB); 
              //swap pixel with new pixel that has its blue lsb changed (looks the same)
              imagesetpixel($img,$pixelX,$pixelY,$new_color); 
              //Set the color at the x and y positions
              $pixelX++; 
              //next pixel (horizontally)
            
            }
            //Random digit for our filename
            imagepng($img, $encrypt.$images); 
            //Create image
           $success =  $filename.' have successfully encrypted to ' .$encrypt.$images .'.'; 
           header('location:'. $_SERVER['PHP_SELF'].'?success='.$success);
            //Echo our image file name
            
            imagedestroy($img); //get rid of it

        }else{
            $fileError = "Please fill require are before choosing image";
        }
    
    
    }else{
        $fileError = "Please setect image for data encryption";
    }


}
//To encrypt


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steganograph - Home</title>
    <?php include('includes/head.php') ?>
</head>
<body>
<?php include('includes/header.php') ?>

<div class="jumbotron jumbotron-fluid">
  <div class="container bg-white">
    <h1 class="text-center font-weight-bold">Encode Message</h1>
    <?php if(isset($_GET['success'])){ ?>

      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
       <h4><strong>Success!</strong> <?= $_GET['success'] ?></h4>
      </div>
      <?php } ?>
    <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="email">Encode Message:</label>
            <input type="text" class="form-control" id="pwd" name="message">
            <span class="text-danger"><?= $mError ?></span>

        <div class="form-group">
            <label for="pwd">Image to encrypt:</label>
            <input type="file" class="form-control" id="pwd" name="image">
            <span class="text-danger"><?= $fileError ?></span>

        </div>
        <button type="submit" name="generate" class="btn btn-primary">Generate</button>
    </form> 
    </div>
    <div class="col-md-3"></div>
    </div>
  </div>

  <div class="container my-4">
      <h1 class="text-center font-weight-bold">Encoded Result</h1>
    <div class="row">
    <?php 
     if(is_dir($encrypt)){
      $files = scandir($encrypt);
      foreach($files as $file){
        if(strlen($file)>2){
    ?>
    <div class="col-md-4">
      <div class="card">
        <div class="row">
        <div class="col">
          <div class="card card-body" >
          <a href="#view_encrypt"  data-toggle="modal" myencrypt="<?= $target_dir.$file ?>">
          <img src="<?= $target_dir.$file ?>" class="card-img"  style="height: 150px;" alt="">
          <div class="card-footer bg-dark">
            <h5 class="text-white">Original</h5>
          </div>
        </a>
      </div>
        </div>
        <div class="col">
          <div class="card card-body">
        <a href="#view_encrypt"  data-toggle="modal" myencrypt="<?= $encrypt.$file ?>">
          <img src="<?= $encrypt.$file ?>" class="card-img" style="height: 150px;" alt="">
          <div class="card-footer bg-dark ">
            <h6 class="text-white">Stegano image</h6>
          </div>
        </a>
      </div>
        </div>
        </div>
      </div>
    </div>

    <?php
        }
      }
  } ?>

    
    </div>
  </div>

  <div class="modal" id="view_encrypt">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="encrypt_content">
      
      </div>
    </div>
  </div>
  </div>

</div>

<?php include('includes/footer.php') ?>
<script>
  $(document).ready(function(){
  $('#view_encrypt').on('show.bs.modal', function(e){
    var id = $(e.relatedTarget).attr('myencrypt');
    // alert(id);
    $.ajax({
      type:'post',
      url:'decode.php',
      data:'encryptme='+id,
      success:function(data){
        $('.encrypt_content').html(data);
      }
    })
  })
})
</script>
</body>
</html>