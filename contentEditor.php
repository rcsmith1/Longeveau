<!--
This file controls the text editor for updating content. As there are three language versions of the site, the content editor has 3 panes, one for each language - the visibility of the relevant pane is controlled via JavaScript
When the user clicks on the 'Preview before saving' button, the content is sent to a temporary preview page (housed in the respective language's area on the server)
-->

<?php include "header.php" ?>

<div id="lms">

<?php

if(!empty($_POST["mode"])){

  $id = $_POST["id"] ; 
  $listOfPages = file('includes/listOfPages.inc') ;
  for($i=0; $i < count($listOfPages) ; $i++) {
    $tmp = explode(', ', $listOfPages[$i]) ;
    $listOfPages[$i] = array('pageTitle' => $tmp[0], 'includeFile' => rtrim($tmp[1])) ;
  }
  $pageTitle = $listOfPages[$id]["pageTitle"] ; 
  $includeFile = $listOfPages[$id]["includeFile"] ;

} 


$filenameEN = '../en/content/'.$includeFile.'.inc' ;
$fileEN = fopen($filenameEN, 'r') ;
$contentsEN = fread($fileEN, filesize($filenameEN));
fclose($fileEN) ;

$filenameFR = '../fr/content/'.$includeFile.'.inc' ;
$fileFR = fopen($filenameFR, 'r') ;
$contentsFR = fread($fileFR, filesize($filenameFR));
fclose($fileFR) ;

$filenameND = '../nd/content/'.$includeFile.'.inc' ;
$fileND = fopen($filenameND, 'r') ;
$contentsND = fread($fileND, filesize($filenameND));
fclose($fileND) ;

?>


<div id="editor">

<h1>Editing > <?php echo $pageTitle ?></h1>

<div id="togglePanes">
  <ul>
    <li id="englishLink"><a href="#" class="highlight">English</a></li>
    <li id="frenchLink"><a href="#">French</a></li>
    <li id="dutchLink"><a href="#">Dutch</a></li>
  </ul>
</div>


<div class="writingPane" id="english">
  <form action="../en/preview.php" method="post">
    <input type="hidden" name="mode" value="preview">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <input type="hidden" name="pageTitle" value="<?php echo $pageTitle ?>">
    <h2>English</h2>
    <textarea rows="25" cols="80" name="dataToWrite"><?php echo $contentsEN ; ?></textarea><br>
    <input type="submit" name="previewButton" value="Preview before saving" class="submit"><br>
  </form>
</div>

<div class="writingPane" id="french">
  <form action="../fr/preview.php" method="post">
    <input type="hidden" name="mode" value="preview">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <input type="hidden" name="pageTitle" value="<?php echo $pageTitle ?>">
    <h2>French</h2>
    <textarea rows="25" cols="80" name="dataToWrite"><?php echo $contentsFR ; ?></textarea><br>
    <input type="submit" name="previewButton" value="Preview before saving" class="submit"><br>
  </form>
</div>


<div class="writingPane" id="dutch">
  <form action="../nd/preview.php" method="post">
    <input type="hidden" name="mode" value="preview">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <input type="hidden" name="pageTitle" value="<?php echo $pageTitle ?>">
    <h2>Dutch</h2>
    <textarea rows="25" cols="80" name="dataToWrite"><?php echo $contentsND ; ?></textarea><br>
    <input type="submit" name="previewButton" value="Preview before saving" class="submit"><br>
  </form>
</div>


</div>
<?php include "footer.php" ?>