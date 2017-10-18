<!-- 
This PHP file generates a list of accommodation pages that can have images uploaded to them based on a input from an external CSV file 
The user can then pick which holiday cottage they'd like to add images to from the generated list of files
-->

<?php include "header.php" ?>

<?php

$headerImageFoldersFile = file('includes/accomImageFolders.inc') ;
$headerImageFolders = array() ;

for($i=0; $i < count($headerImageFoldersFile) ; $i++) {
  $tmp = explode(', ', $headerImageFoldersFile[$i]) ;
  $folderToCheck = rtrim($tmp[1]) ;
  $numberOfImages = getNumberOfImages($folderToCheck) ;
  $headerImageFolders[$i] = array('name' => $tmp[0], 'folderName' => $folderToCheck, 'numberOfImages' => $numberOfImages, 'folderFR' => $tmp[2], 'folderND' => rtrim($tmp[3]) ) ;
}

function getNumberOfImages($theFolder) {
  $found = 0 ;
  $fileTypes = array('jpg') ;
  $contents = openAFolder($theFolder) ;
  if($contents) {
    foreach($contents as $item) {
      $fileInfo = pathinfo($item) ; 
      if(array_key_exists('extension', $fileInfo) && in_array($fileInfo['extension'], $fileTypes )) {
        $found++ ;
      }       
    }
  }
  return $found ;
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

<h1>Accomodation images</h1>

<table cellspacing="0" cellpadding="8" width="630" border="0" id="listOfPages">

  <tr>
    <th width="55%" class="page">Header</th>
    <th width="30%">Number of images</th>
    <th width="15%">Actions</th>
  </tr>

  <?php

    for ($i = 0 ; $i < count($headerImageFolders) ; $i++) {

      $headerTitle = $headerImageFolders[$i]['name'] ; 
      $folderName = $headerImageFolders[$i]['folderName'] ; 
      $numberOfImages = $headerImageFolders[$i]['numberOfImages'] ; 
      $folderFR = $headerImageFolders[$i]['folderFR'] ; 
      $folderND = $headerImageFolders[$i]['folderND'] ; 

      echo '<tr>' ;
      echo '<td class="page">' . $headerTitle . '</td>' ;  
      echo '<td>' . $numberOfImages . ' images</td>' ;
      echo '<td><form action="accomImages.php" method="post"><input type="hidden" name="folderName" value="' . $folderName . '"><input type="hidden" name="folderFR" value="' . $folderFR . '"><input type="hidden" name="folderND" value="' . $folderND . '"><input type="hidden" name="headerTitle" value="' . $headerTitle . '"><input type="hidden" name="mode" value="list"><input type="submit" value="Edit" class="submit"></form></td>' ;
      echo '</tr>' ;

    }
  ?> 


</table>

<?php include "footer.php" ?>
