<!--
This PHP file deals with the uploading of images the various site section fading header images
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

<h1>Header images for <?php echo $headerTitle ?></h1>
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
        natcasesort($foundFiles) ;
      }
    }
?>

  <div id="top">
    <h2>Add new image</h2>
    <p>Any new header images MUST be sized at <b>700 pixels</b> x <b>200 pixels</b> (width x height)</p>
    <form action="headerImages.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="mode" value="upload">
      <input type="hidden" name="headerTitle" value="<?php echo $headerTitle ?>">
      <input type="hidden" name="folderName" value="<?php echo $folderName ?>">
      <input type="hidden" name="folderFR" value="<?php echo $folderFR ?>">
      <input type="hidden" name="folderND" value="<?php echo $folderND ?>">
      <p>Upload image <input type="file" name="image" id="image"></p> 
      <p class="noBottomMargin"><input type="submit" class="submit" value="Upload" id="upload" name="upload"></p>
    </form> 
  </div>


  <h2>Existing images (<?php echo count($foundFiles) ?> currently uploaded)</h2>

  <?php
    for($i=0 ; $i < count($foundFiles) ; $i++) {
  ?>

    <div class="headerImageThumbnail">
      <img src="<?php echo $folderName . '/' . $foundFiles[$i]?>" width="700" height="200">
      <p><b><?php echo $foundFiles[$i] ?></b></p>
      <form action="headerImages.php" method="POST">
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
    <p>Are you sure you want to delete <b><?php echo $imageToRemove ?></b> from <b><?php echo $headerTitle ?>?</b></p>
    <form action="headerImages.php" method="POST">
      <input type="hidden" name="mode" value="deleteNotification">
      <input type="hidden" name="headerTitle" value="<?php echo $headerTitle ?>">
      <input type="hidden" name="imageToRemove" value="<?php echo $imageToRemove ?>"> 
      <input type="hidden" name="folderName" value="<?php echo $folderName ?>">
      <input type="hidden" name="folderFR" value="<?php echo $folderFR ?>">
      <input type="hidden" name="folderND" value="<?php echo $folderND ?>">
      <input type="submit" value="Yes"  class="submit">
    </form>
    <form action="headerImages.php" method="POST">
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
    $condemnedFileFR = $folderFR . '/' . $imageToRemove ; 
    $condemnedFileND = $folderND . '/' . $imageToRemove ; 
    unlink ($condemnedFileEN) ;
    unlink ($condemnedFileFR) ;
    unlink ($condemnedFileND) ;

    //Logfile update section
    $timeDel = date("l, F d, Y h:i" ,time()) ;  
    $userIDDel = $screenName . " (" . $userID . ")" ;
    $operationDel = "Header image deleted" ; 

    $logDataDel = "\r\n****************\r\n" . 
               "Date/Time: " . $timeDel . 
               "\r\nUser: " . $userIDDel .
               "\r\nOperation: " . $operationDel . 
               "\r\nHeader: ". $headerTitle .
               "\r\nDeleted files: \r\n" . $condemnedFileEN . ", " . $condemnedFileFR . ", " . $condemnedFileND . "\r\n\r\n" ;
    $logFileDel = fopen('../lv_se/d1/logFile.log', 'a') ;
    fwrite ($logFileDel, $logDataDel) ;
    fclose ($logFileDel) ;
?>

  <div id="top">
    <h2>Image deleted</h2>
    <p><b><?php echo $imageToRemove ?></b> has now been deleted and will no longer appear in the <b><?php echo $headerTitle ?></b> header</p>
    <form action="headerImages.php" method="POST">
      <input type="submit" value="Return to <?php echo $headerTitle ?> images" class="submit">
      <input type="hidden" name="mode" value="list">
      <input type="hidden" name="headerTitle" value="<?php echo $headerTitle ?>">
      <input type="hidden" name="folderName" value="<?php echo $folderName ?>">
      <input type="hidden" name="folderFR" value="<?php echo $folderFR ?>">
      <input type="hidden" name="folderND" value="<?php echo $folderND ?>">
    </form>
    <form action="imagesList.php" method="POST">
      <input type="submit" value="Return to Header image admin" class="submit">
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
    $uploadedFile = $_FILES['image']['tmp_name'] ; 
    list($width, $height, $type) = getImageSize($uploadedFile) ;

    if($width == 700 && $height == 200) {

      $fileName = time() . '_' . str_replace(' ', '_', $_FILES['image']['name']) ;
      $filePath = $folderName . '/' . $fileName ; 
      $filePathFR = $folderFR . '/' . $fileName ;
      $filePathND = $folderND . '/' . $fileName ;

      copy($_FILES['image']['tmp_name'], $filePath) ;
      copy($_FILES['image']['tmp_name'], $filePathFR) ;
      copy($_FILES['image']['tmp_name'], $filePathND) ;
      unlink($_FILES['image']['tmp_name']) ;

    } else {
      $isError = true ;
      $errorMessage = "The file is not the correct size, it must be 700 x 200 pixels in size" ; 
    } 
  }
  ?>  

  <div id="top">
    <h2>Image upload</h2>


    <?php if($isError) { ?>
      <p class="error"><b>There was a problem with your file upload</b><br>
        - <?php echo $errorMessage ?></p>
  
    <?php } else { ?>
  
      <p>Your file has been uploaded successfully and saved as <b><?php echo $fileName ?></b></p>
      <p><img src="<?php echo $filePath ?>" width="700" height="200"></p>

      <?php
        //Logfile update section
        $timeUp = date("l, F d, Y h:i" ,time()) ;  
        $userIDUp = $screenName . " (" . $userID . ")" ;
        $operationUp = "Header image uploaded" ; 

        $logDataUp = "\r\n****************\r\n" . 
                   "Date/Time: " . $timeUp . 
                   "\r\nUser: " . $userIDUp .
                   "\r\nOperation: " . $operationUp . 
                   "\r\nProperty: ". $headerTitle .
                   "\r\nFiles added: \r\n" . 
                   $filePath . ", " . $filePathFR . ", " . $filePathND . "\r\n\r\n" ;
        $logFileUp = fopen('../lv_se/d1/logFile.log', 'a') ;
        fwrite ($logFileUp, $logDataUp) ;
        fclose ($logFileUp) ;
      ?>

    <?php } ?>

    <form action="headerImages.php" method="POST">
      <input type="submit" value="Return to <?php echo $headerTitle ?> images" class="submit">
      <input type="hidden" name="mode" value="list">
      <input type="hidden" name="headerTitle" value="<?php echo $headerTitle ?>">
      <input type="hidden" name="folderName" value="<?php echo $folderName ?>">
      <input type="hidden" name="folderFR" value="<?php echo $folderFR ?>">
      <input type="hidden" name="folderND" value="<?php echo $folderND ?>">
    </form>
  
    <form action="imagesList.php" method="POST">
      <input type="submit" value="Return to Header image admin" class="submit">
    </form>

  </div>

<?php } ?>




</div>


<?php include "footer.php" ?>