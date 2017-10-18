<!-- 
This file contains the necessary coding for logging the user out of the site editor. 
Please note that for secuirty reasons, I have not included the login code in this repository
-->

<?php

  session_start() ;

  $_SESSION = array() ;
  if(isset($_COOKIE[session_name()])){
    setcookie(session_name(), '', time()-86400, '/') ;
  }
  session_destroy() ;

  header("Location: login.php") ;

?>