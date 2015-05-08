<?php 

    // connect to the DB and start session  
    require("config.php"); 
     
    // clear session 
    unset($_SESSION['user']); 
     
    // redirect them to the index page 
    header("Location: index.php"); 
    die("Redirecting to: index.php");