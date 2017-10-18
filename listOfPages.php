<!-- 
This PHP file generates a list of pages that can be edited based on a input from an external CSV file 
The user can then pick which file they'd like to edit from the generated list of files
-->


<?php include "header.php" ?>

<div id="lms">

<?php

$listOfPages = file('includes/listOfPages.inc') ;

for($i=0; $i < count($listOfPages) ; $i++) {
  $tmp = explode(', ', $listOfPages[$i]) ;
  $listOfPages[$i] = array('pageTitle' => $tmp[0], 'includeFile' => rtrim($tmp[1])) ;
}

?>


<h1>Editable pages</h1>


<table cellspacing="0" cellpadding="8" width="630" border="0" id="listOfPages">
  <tr>
    <th width="58%" class="page">Page title</th>
    <th width="30%">View current</th>
    <th width="12%">Actions</th>
  </tr>

  <?php

    for ($i = 0 ; $i < count($listOfPages) ; $i++) {

      $pageTitle = $listOfPages[$i]['pageTitle'] ; 
      $filename = $listOfPages[$i]['includeFile'] ; 

      echo '<tr>' ;
      echo '<td class="page">' . $pageTitle . '</td>' ;  
      echo '<td><a href="http://www.longeveau.com/en/' . $filename . '.php" target="_preview">English</a> |' ;
      echo '    <a href="http://www.longeveau.com/fr/' . $filename . '.php" target="_preview">French</a> |' ;
      echo '    <a href="http://www.longeveau.com/nd/' . $filename . '.php" target="_preview">Dutch</a></td>' ;
      echo '<td><form action="contentEditor.php" method="post"><input type="hidden" name="id" value="' . $i . '"><input type="hidden" name="mode" value="initialLoad"><input type="submit" value="Edit" class="submit"></form></td>' ;
      echo '</tr>' ;

    }
  ?> 



</table>

<?php include "footer.php" ?>