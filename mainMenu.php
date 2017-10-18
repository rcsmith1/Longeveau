<!-- 
Site editor main menu screen, no PHP coding on this page, but included for reference 
-->

<?php include "header.php" ?>

<h1>Welcome to the Longeveau site editor</h1>

<div class="mainMenuItem">
  <a href="listOfPages.php">
    <span>Text editor</span><br>
    Use the text editor to modify the text that appears on each page of the site. All three versions of the pages (English, French and Dutch) are accessible from here. Changes need to be made in HTML, please see the document below for a quick reference on some basic HTML
  </a>
</div>

<div class="mainMenuItem">
  <a href="imagesList.php">
    <span>Header images</span><br>
    File management for the fading header images. You can customise which images appear in the header and in which section.
  </a>
</div>

<div class="mainMenuItem">
  <a href="accomImagesList.php">
    <span>Accomodation images</span><br>
    File management for accomodation images. Thumbnail images are automatically created and uploaded to the site for you.
  </a>
</div>

<div class="mainMenuItem">
  <a href="htmlGuide.php" target="_blank">
    <span>HTML basic guidelines</span><br>
    A very rough intro to HTML with some examples of how it would be used within the content editor.
  </a>
</div>


<?php include "footer.php" ?>