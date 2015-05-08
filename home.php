<?php 

    // connect to the DB and start session  
    require("config.php"); 
     
    // if user is not logged in
    if(empty($_SESSION['user'])) 
    { 
        // redirect to login 
        header("Location: login.php"); 
         
        die("Redirecting to login.php"); 
    } 
     

?> 
<!doctype html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
		<title>HOME</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	</head>
	<body>
	<div class="container">
		<div class="row">
			<div class="alert alert-success" role="alert"><h4>Hello <?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?>!</h4></div>
		</div>
		<div class="row">
			<ul class="nav nav-pills nav-stacked col-sm-3">
				<li class="list-group-item"><a href="edit.php">Edit Profile</a></li>
				<li class="list-group-item"><a href="logout.php">Logout</a></li>
			</ul>
		</div>
	</div>
	</body>
</html>			