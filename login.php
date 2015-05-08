<?php 

    // connect to the DB and start session 
    require("config.php"); 
     
    $submitted_username = ''; 
     
    // statement, if posted
    if(!empty($_POST)) 
    { 
        // query the user information from table 
        $query = " 
            SELECT 
                id, 
                username, 
                password, 
                salt, 
                email 
            FROM users 
            WHERE 
                username = :username 
        "; 
         
        // parameter in array 
        $query_params = array( 
            ':username' => $_POST['username'] 
        ); 
         
        // try/catch for error handling
		try 
        { 
            // query execution
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            // read the error message if there is an error only on dev site : $ex->getMessage(). 
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        // chk successfully logged in or not. 
        $login_ok = false; 
         
        // retrieve the user data from the table.
        $row = $stmt->fetch(); 
        if($row) 
        { 
            // chk the password and salt weather they are valid or not. 
            $check_password = hash('sha256', $_POST['password'] . $row['salt']); 
            for($round = 0; $round < 10; $round++) 
            { 
                $check_password = hash('sha256', $check_password . $row['salt']); 
            } 
             
            if($check_password === $row['password']) 
            { 
                // If they do, then we flip this to true 
                $login_ok = true; 
            } 
        } 
         
        // If logged in successfully re-direct
        if($login_ok) 
        { 
            // unset password and salt (security purpose)
            unset($row['salt']); 
            unset($row['password']); 
             
            // stores the user data into the session at the index 'user'. 
            $_SESSION['user'] = $row; 
             
            // Redirect to secured private page. 
            header("Location: home.php"); 
            die("Redirecting to: home.php"); 
        } 
        else 
        { 
            // If not logged in successfully re-direct
            print("Login Failed.");              
            $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); 
        } 
    } 
     
?>
<!doctype html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
		<title>Log In</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	</head>
	<body>
    <!--<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Project Frankel</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div>
      </div>
    </nav>-->
	
	<div class="container">
		<div class="row">
			<h1>Login</h1> 
		</div>
		<div class="row">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-horizontal"> 
				<div class="form-group">
					<label for="Username" class="col-sm-2 control-label">Username</label>
					<div class="col-sm-3">
						<input type="text" name="username" id="Username" class="form-control" value="<?php echo $submitted_username; ?>" placeholder="Username" autofocus required />
					</div>
				</div>
				
				<div class="form-group">
					<label for="Password" class="col-sm-2 control-label">Password</label>
					<div class="col-sm-3">
						<input type="password" name="password" id="Password" class="form-control" value="" placeholder="Password" required /> 
					</div>
				</div> 
				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-success">Sign in</button>
					</div>
				</div>
			</form> 			
		</div>

		<div class="row">
			<a href="register.php">Register</a>
		</div>		
	</div>
	</body>
</html>