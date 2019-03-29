<?php
// Initialize the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Business Manager</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Business Manager</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="home.php">Home</a></li>
      <li><a href="customers.php">Customers</a></li>
	  <li><a href="jobs.php">Jobs</a></li>
	  <li><a href="calendar.php">Calendar</a></li>
	  <li><a href="users.php">Users</a></li>
    </ul>

  <ul class="nav navbar-nav navbar-right">
  <?php
// Check if the user is logged in, if not shows the login/register buttons
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
?>
    

   
      <li><a href="register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
      <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
<?php;	  
	 // exit;
	  }
?>

<?php
// Check if the user is logged in, if so the logout button is shown
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
?>
<?php
}else{
?>
	  <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout as <?php echo htmlspecialchars($_SESSION["username"]); ?> </a></li>
<?php;	  
	 // exit;
	  }
?>
    </ul>
  </div>
</nav>
