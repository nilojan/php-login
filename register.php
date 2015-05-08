<?php 

    // connect to the DB and start session  
    require("config.php"); 
     
    // statement, if posted
    if(!empty($_POST)) 
    { 
        // make sure posted form fields are not empty
        if(empty($_POST['username'])) 
        { 
            // Note that die() is generally a terrible way of handling user errors 
            // like this.  It is much better to display the error with the form 
            // and allow the user to correct their mistake.  However, that is an 
            // exercise for you to implement yourself. 
            die("Please enter a username."); 
        } 
         
        // make sure posted form fields are not empty
        if(empty($_POST['password'])) 
        { 
            die("Please enter a password."); 
        } 
         
        // Make sure its a valid email address 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { 
            die("Invalid E-Mail Address"); 
        } 
         
        // query to chk whether the username is already there
        $query = " 
            SELECT 
                1 
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
            // run the query to chk table. 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            // chk if any error occurs  
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        // fetch() to returns a row in an array
        $row = $stmt->fetch(); 
         
        // if row found , username is already in use 
        if($row) 
        { 
            die("This username is already in use"); 
        } 
         
        // query to chk whether the email is already there
        $query = " 
            SELECT 
                1 
            FROM users 
            WHERE 
                email = :email 
        "; 
         
        $query_params = array( 
            ':email' => $_POST['email'] 
        ); 
         
        try 
        { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        $row = $stmt->fetch(); 
         
        if($row) 
        { 
            die("This email address is already registered"); 
        } 
         
        // if no errors on above steps. insert the user in to table
        $query = " 
            INSERT INTO users ( 
                username, 
                password, 
                salt, 
                email 
            ) VALUES ( 
                :username, 
                :password, 
                :salt, 
                :email 
            ) 
        "; 
         
        // salt to protect again brute force attacks 
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
         
        $password = hash('sha256', $_POST['password'] . $salt); 
         
        // hash value 10 more times.  the more is better 
        for($round = 0; $round < 10; $round++) 
        { 
            $password = hash('sha256', $password . $salt); 
        } 
        
		// store the user record created date and time
		$created = date('Y-m-d H:i:s');
        // parameter in array 
        $query_params = array( 
            ':username' => $_POST['username'], 
            ':password' => $password, 
            ':salt' => $salt, 
            ':email' => $_POST['email'],
			':created' => $created
        ); 
         
        try 
        { 
            //query to create the user 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            // chk if there any error  
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        // redirect to login if all fine 
        header("Location: login.php"); 
         
        die("Redirecting to login.php"); 
    } 
     
?>
<!doctype html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Register</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	</head>
	<body>
	<div class="container">
		<div class="row">
			<h1>Register</h1> 
		</div>
		<div class="row">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="RegisterForm" class="form-horizontal"> 

				<div class="form-group">
					<label for="Username" class="col-sm-2 control-label">Username</label>
					<div class="col-sm-3">
						<input type="text" name="username" class="form-control" id="Username" value="" pattern=".{4,}" title="Min four or more characters" placeholder="Username" autofocus required /> 
					</div>
				</div>


				<div class="form-group">
					<label for="email" class="col-sm-2 control-label">Email</label>
					<div class="col-sm-3">
						<input type="email" name="email" class="form-control" id="email" value="" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" placeholder="E-Mail" required />
					</div>
				</div>
				
				
				<div class="form-group">
					<label for="Password" class="col-sm-2 control-label">Password</label>
					<div class="col-sm-3">
						<input type="password" name="password" class="form-control" id="Password" value="" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"  placeholder="Password" required /> 
					</div>
				</div> 

		
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-info">Sign-up</button>
					</div>
				</div>
			</form>
		
		</div>

		<div class="row">
			<a href="login.php">Login</a>
		</div>		
	</div>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
	<script src="validation.js"></script>

	</body>
</html>		