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
if(isset($_GET["jobid"]) && !empty(trim($_GET["jobid"]))){
    // Include config file
    require_once "config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM jobs WHERE jobid = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["jobid"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
				$jobid = $row["jobid"];
                $customerid = $row["customerid"];
                $jobtype = $row["jobtype"];
                $jobtime = $row["jobtime"];
				$jobestimate = $row["jobestimate"];
                $jobstatus = $row["jobstatus"];
                $jobstart = $row["jobstart"];
				$jobend = $row["jobend"];
				
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
    //header("location: error.php");
	echo("invalid ID parameter");
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
                        <h1>View Job</h1>
                    </div>
					
					<div class="form-group">
                        <label>Unique ID</label>
                        <p class="form-control-static"><?php echo $row["jobid"]; ?></p>
                    </div>
					
                    <div class="form-group">
                        <label>Customer ID</label>
                        <p class="form-control-static"><?php echo $row["customerid"]; ?></p>
                    </div>
					
                    <div class="form-group">
                        <label>Job Type</label>
                        <p class="form-control-static"><?php echo $row["jobtype"]; ?></p>
                    </div>
					
                    <div class="form-group">
                        <label>Job Time</label>
                        <p class="form-control-static"><?php echo $row["jobtime"]; ?></p>
                    </div>
					
					<div class="form-group">
                        <label>Job Estimate</label>
                        <p class="form-control-static"><?php echo $row["jobestimate"]; ?></p>
                    </div>
					
					<div class="form-group">
                        <label>Job Status</label>
                        <p class="form-control-static"><?php echo $row["jobstatus"]; ?></p>
                    </div>
					
					<div class="form-group">
                        <label>Job Start</label>
                        <p class="form-control-static"><?php echo $row["jobstart"]; ?></p>
                    </div>
					
					<div class="form-group">
                        <label>Job End</label>
                        <p class="form-control-static"><?php echo $row["jobend"]; ?></p>
                    </div>
                    <p><a href="jobs.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>