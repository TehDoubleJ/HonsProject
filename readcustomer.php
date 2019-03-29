<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<?php
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM customers WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $id = $row["id"];
				$firstname = $row["firstname"];
				$surname = $row["surname"];
				$phoneno = $row["phoneno"];
				$address = $row["address"];
				$postcode = $row["postcode"];
				$facebook = $row["facebook"];
				$twitter = $row["twitter"];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  include('header.php')
  ?>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>View Record</h1>
                    </div>
					
					<div class="form-group">
                        <label>Unique ID</label>
                        <p class="form-control-static"><?php echo $row["id"]; ?></p>
                    </div>
					
                    <div class="form-group">
                        <label>First Name</label>
                        <p class="form-control-static"><?php echo $row["firstname"]; ?></p>
                    </div>
					
                    <div class="form-group">
                        <label>Surname</label>
                        <p class="form-control-static"><?php echo $row["surname"]; ?></p>
                    </div>
					
                    <div class="form-group">
                        <label>Phone Number</label>
                        <p class="form-control-static"><?php echo $row["phoneno"]; ?></p>
                    </div>
					
					<div class="form-group">
                        <label>Address</label>
                        <p class="form-control-static"><?php echo $row["address"]; ?></p>
                    </div>
					
					<div class="form-group">
                        <label>Postcode</label>
                        <p class="form-control-static"><?php echo $row["postcode"]; ?></p>
						<?php echo "<a href='https://maps.google.com/maps?q=". $row['postcode'] ."' title='Open Google Maps'><span class='btn btn-primary'>Open Google Maps</span></a>";?>
                    </div>
					
					<div class="form-group">
                        <label>Facebook Profile</label>
                        <p class="form-control-static"><?php echo $row["facebook"]; ?></p>
                    </div>
					
					<div class="form-group">
                        <label>Twitter Profile</label>
                        <p class="form-control-static"><?php echo $row["twitter"]; ?></p>
                    </div>
                    <p><a href="customers.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>