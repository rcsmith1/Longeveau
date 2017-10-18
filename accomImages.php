<!--
This PHP file deals with the uploading of images for an individual holiday cottage page
The user will upload an image, and a thumbnail image will automatically be created. The image and it's thumbnail will then be stored on the server at the respective locations for English, French and Dutch versions of the site
This file also deals with the deletion of images if the user requests the removal of an image
-->

<?php include "header.php" ?>


<?php

  $mode = "" ;
  $headerTitle = "" ;
  $folderName = "" ;
  $folderFR = "" ;
  $folderND = "" ;
  $foundFiles = "" ; 

  if(!empty($_POST["mode"])) {
    $mode = $_POST["mode"] ;
    $headerTitle = $_POST["headerTitle"] ;
    $folderName = $_POST["folderName"] ;   
    $folderFR = $_POST["folderFR"] ;
    $folderND = $_POST["folderND"] ;
  } 

  function openAFolder($whichFolder) {
    $folder = opendir($whichFolder) ;
    $files = array() ;
    while (false !== ($item = readdir($folder))) {
      $files[] = $item ; 
    }
    closedir($folder) ;
    return $files ;
  }

?>

<h1>Accomodation images for <?php echo $headerTitle ?></h1>
<div id="headerImages">

<?php

  if($mode == "list") {
    $fileTypes = array('jpg') ;  
    $contents = openAFolder($folderName) ;
    if($contents) {
      foreach($contents as $item) {
        $fileInfo = pathinfo($item) ; 
        if(array_key_exists('extension', $fileInfo) && in_array($fileInfo['extension'], $fileTypes )) {
          $foundFiles[] = $item ;          
        }       
      }
      if($foundFiles) {
        sort($foundFiles) ;
      }
    }
?>

  <div id="top">
    <h2>Add new image</h2>
    <p>Any new <b>landscape</b> images must be sized at <b>358 pixels</b> x <b>270 pixels</b> (width x height)</p>
    <p>Any new <b>portrait</b> images must be sized at <b>266 pixels</b> x <b>340 pixels</b> (width x height)</p>
    <p>The accompanying thumbnail image will be created automatically</p>
    
    <form action="accomImages.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="mode" value="upload">
      <input type="hidden" name="headerTitle" value="<?php echo $headerTitle ?>">
      <input type="hidden" name="folderName" value="<?php echo $folderName ?>">
      <input type="hidden" name="folderFR" value="<?php echo $folderFR ?>">
      <input type="hidden" name="folderND" value="<?php echo $folderND ?>">
      <p>Upload image <input type="file" name="image" id="image"></p> 
      <p class="noBottomMargin"><input type="submit" value="Upload" id="upload" name="upload" class="submit"></p>
    </form> 
  </div>


  <h2>Existing images (<?php echo count($foundFiles) ?> currently uploaded)</h2>

  <?php
    for($i=0 ; $i < count($foundFiles) ; $i++) {
  ?>

    <div class="headerImageThumbnail">
      <img src="<?php echo $folderName . '/' . $foundFiles[$i]?>">&nbsp;&nbsp;
      <img src="<?php echo $folderName . '/thumbs/thumb_' . $foundFiles[$i]?>">
      <p><b><?php echo $foundFiles[$i] ?></b> / <b>thumb_<?php echo $foundFiles[$i] ?></p>
      <form action="accomImages.php" method="POST">
        <input type="hidden" name="mode" value="delete">
        <input type="hidden" name="headerTitle" value="<?php echo $headerTitle ?>">
        <input type="hidden" name="imageToRemove" value="<?php echo $foundFiles[$i] ?>"> 
        <input type="hidden" name="folderName" value="<?php echo $folderName ?>">
        <input type="hidden" name="folderFR" value="<?php echo $folderFR ?>">
        <input type="hidden" name="folderND" value="<?php echo $folderND ?>">
        <input type="submit" value="Delete" class="submit">
      </form>
    </div>

  <?php } ?>

<?php } ?>



<?php
  if($mode == "delete") {
    $imageToRemove = $_POST["imageToRemove"] ;
?>

  <div id="top">
    <h2>Confirm delete</h2>
    <p>Are you sure you want to delete <b><?php echo $imageToRemove ?></b> from <b><?php echo $headerTitle ?></b>?</p>
    <form action="accomImages.php" method="POST">
      <input type="hidden" name="mode" value="deleteNotification">
      <input type="hidden" name="headerTitle" value="<?php echo $headerTitle ?>">
      <input type="hidden" name="imageToRemove" value="<?php echo $imageToRemove ?>"> 
      <input type="hidden" name="folderName" value="<?php echo $folderName ?>">
      <input type="hidden" name="folderFR" value="<?php echo $folderFR ?>">
      <input type="hidden" name="folderND" value="<?php echo $folderND ?>">
      <input type="submit" value="Yes" class="submit">
    </form>
    <form action="accomImages.php" method="POST">
      <input type="hidden" name="mode" value="list">
      <input type="hidden" name="headerTitle" value="<?php echo $headerTitle ?>">
      <input type="hidden" name="folderName" value="<?php echo $folderName ?>">
      <input type="hidden" name="folderFR" value="<?php echo $folderFR ?>">
      <input type="hidden" name="folderND" value="<?php echo $folderND ?>">
      <input type="submit" value="No" class="submit">
    </form>
  </div>

<?php } ?>


<?php
  if($mode == "deleteNotification") {

    $imageToRemove = $_POST["imageToRemove"] ;

    $condemnedFileEN = $folderName . '/' . $imageToRemove ; 
    $condemnedThumbEN = $folderName . '/thumbs/thumb_' . $imageToRemove ; 
    $condemnedFileFR = $folderFR . '/' . $imageToRemove ; 
    $condemnedThumbFR = $folderFR . '/thumbs/thumb_' . $imageToRemove ; 
    $condemnedFileND = $folderND . '/' . $imageToRemove ; 
    $condemnedThumbND = $folderND . '/thumbs/thumb_' . $imageToRemove ; 

    unlink ($condemnedFileEN) ;
    unlink ($condemnedThumbEN) ;
    unlink ($condemnedFileFR) ;
    unlink ($condemnedThumbFR) ;
    unlink ($condemnedFileND) ;
    unlink ($condemnedThumbND) ;

    //Logfile update section
    $timeDel = date("l, F d, Y h:i" ,time()) ;  
    $userIDDel = $screenName . " (" . $userID . ")" ;
    $operationDel = "Accomodation image deleted" ; 

    $logDataDel = "\r\n****************\r\n" . 
               "Date/Time: " . $timeDel . 
               "\r\nUser: " . $userIDDel .
               "\r\nOperation: " . $operationDel . 
               "\r\nProperty: ". $headerTitle .
               "\r\nDeleted files: \r\n" . 
               $condemnedFileEN . ", " . $condemnedThumbEN . "\r\n" . 
               $condemnedFileFR . ", " . $condemnedThumbFR . "\r\n" . 
               $condemnedFileND . ", " . $condemnedThumbND . "\r\n\r\n" ;
    $logFileDel = fopen('../lv_se/d1/logFile.log', 'a') ;
    fwrite ($logFileDel, $logDataDel) ;
    fclose ($logFileDel) ;

?>

  <div id="top">
    <h2>Image deleted</h2>
    <p><b><?php echo $imageToRemove ?></b> has now been deleted and will no longer appear on the <?php echo $headerTitle ?> page</p>
    <form action="accomImages.php" method="POST">
      <input type="submit" value="Return to <?php echo $headerTitle ?> images" class="submit">
      <input type="hidden" name="mode" value="list">
      <input type="hidden" name="headerTitle" value="<?php echo $headerTitle ?>">
      <input type="hidden" name="folderName" value="<?php echo $folderName ?>">
      <input type="hidden" name="folderFR" value="<?php echo $folderFR ?>">
      <input type="hidden" name="folderND" value="<?php echo $folderND ?>">
    </form>
    <form action="accomImagesList.php" method="POST">
      <input type="submit" value="Return to Accommodation image admin" class="submit">
    </form>
  </div>

<?php } ?>


<?php
  if($mode == "upload") {

    $isError = true ; 
    $errorMessage = "" ; 
    $fileName = "" ;
    $filePath = "" ;  
  
    switch($_FILES['image']['error']) {
      case 0 :
      $isError = false ; 
      break;

      case 1 :
      $errorMessage = "File exceeds maximum filesize limit" ;
      break;

      case 2 :
      $errorMessage = "File exceeds maximum filesize limit" ;
      break;

      case 3 :
      $errorMessage = "File not fully uploaded" ;
      break;

      case 4 :
      $errorMessage = "No file specified" ;
      break;        

      case 6 :
      $errorMessage = "No temporary folder defined" ;
      break;

      case 7 :
      $errorMessage = "Unable to write to file to disk" ;
      break;
    }
?>


  <?php if(!$isError) {

    if (is_uploaded_file($_FILES['image']['tmp_name'])) {

      $uploadedFile = $_FILES['image']['tmp_name'] ; 
      list($width, $height, $type) = getImageSize($uploadedFile) ;
      $source = imagecreatefromjpeg($_FILES['image']['tmp_name']) ;

      if(($width == 358 && $height == 270) || ($width == 266 && $height == 340)) {

        $path_info = pathinfo($_FILES['image']['name']);
        $fileNameExtension = $path_info['extension'] ; 
        $fileNameBeginning = $path_info['filename'] ;
        $fileName = str_replace(' ', '_', $fileNameBeginning) . '_' . time() . '.' . $fileNameExtension ;

        $filePath = $folderName . '/' . $fileName ; 
        $filePathFR = $folderFR . '/' . $fileName ;
        $filePathND = $folderND . '/' . $fileName ;

        $thumbFilePath = $folderName . '/thumbs/thumb_' . $fileName ; 
        $thumbFilePathFR = $folderFR . '/thumbs/thumb_' . $fileName ; 
        $thumbFilePathND = $folderND . '/thumbs/thumb_' . $fileName ; 

        copy($_FILES['image']['tmp_name'], $filePath) ;
        copy($_FILES['image']['tmp_name'], $filePathFR) ;
        copy($_FILES['image']['tmp_name'], $filePathND) ;
        unlink($_FILES['image']['tmp_name']) ;


        //Resize image stuff here
        $thumb = imagecreatetruecolor(82, 60) ;

        if($width == 358 && $height == 270){ 
          imagecopyresampled($thumb, $source, 0, 0, 0, 0, 82, 60, $width, $height) ;
        } else if ($width == 266 && $height == 340) {
          imagecopyresampled($thumb, $source, 0, 0, 0, 65, 82, 60, 266, 195) ;
        }


        $successEN = imagejpeg($thumb, $thumbFilePath) ;
        if (!$successEN) {
          $isError = true ; 
          $errorMessage = "Problem creating thumbnail image" ;
        }

        $successFR = imagejpeg($thumb, $thumbFilePathFR) ;
        if (!$successFR) {
          $isError = true ; 
          $errorMessage = "Problem creating thumbnail image" ;
        }

        $successND = imagejpeg($thumb, $thumbFilePathND) ;
        if (!$successND) {
          $isError = true ; 
          $errorMessage = "Problem creating thumbnail image" ;
        }

      } else {
        $isError = true ;
        $errorMessage = "The file is not the correct size.<br><br>Landscape images must be 358 x 270 pixels in size<br>portrait images must be 266 x 340 pixels in size" ; 
      } 
    }
  }
  ?>  

  <div id="top">
    <h2>Image upload</h2>

    <?php if($isError) { ?>
      <p class="error"><b>There was a problem with your file upload</b><br>
         <?php echo $errorMessage ?></p>

    <?php } else { ?>
  
      <p>Your file has been uploaded successfully and saved as <b><?php echo $fileName ?></b></p>
      <p><img src="<?php echo $filePath ?>" alt="Uploaded image">&nbsp;&nbsp;
         <img src="<?php echo $thumbFilePath ?>" alt="Uploaded image thumbnail">
      </p>


      <?php
        //Logfile update section
        $timeUp = date("l, F d, Y h:i" ,time()) ;  
        $userIDUp = $screenName . " (" . $userID . ")" ;
        $operationUp = "Accomodation image uploaded" ; 

        $logDataUp = "\r\n****************\r\n" . 
                   "Date/Time: " . $timeUp . 
                   "\r\nUser: " . $userIDUp .
                   "\r\nOperation: " . $operationUp . 
                   "\r\nProperty: ". $headerTitle .
                   "\r\nFiles added: \r\n" . 
                   $filePath . ", " . $thumbFilePath . "\r\n" . 
                   $filePathFR . ", " . $thumbFilePathFR . "\r\n" . 
                   $filePathND . ", " . $thumbFilePathND . "\r\n\r\n" ;
        $logFileUp = fopen('../lv_se/d1/logFile.log', 'a') ;
        fwrite ($logFileUp, $logDataUp) ;
        fclose ($logFileUp) ;
      ?>

    <?php } ?>

    <form action="accomImages.php" method="POST">
      <input type="submit" value="Return to <?php echo $headerTitle ?> images" class="submit">
      <input type="hidden" name="mode" value="list">
      <input type="hidden" name="headerTitle" value="<?php echo $headerTitle ?>">
      <input type="hidden" name="folderName" value="<?php echo $folderName ?>">
      <input type="hidden" name="folderFR" value="<?php echo $folderFR ?>">
      <input type="hidden" name="folderND" value="<?php echo $folderND ?>">
    </form>

    <form action="accomImagesList.php" method="POST">
      <input type="submit" value="Return to Accommodation image admin" class="submit">
    </form>

  </div>
 

<?php } ?>


</div>


<?php include "footer.php" ?>
