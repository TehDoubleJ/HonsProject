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
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$customerid = $jobtype = $jobtime = $jobestimate = $jobstatus = $jobstart = $jobend = "";
$customerid_err = $jobtype_err = $jobtime_err = $jobestimate_err = $jobstatus_err = $jobstart_err = $jobend_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["jobid"]) && !empty($_POST["jobid"])){
    // Get hidden input value
    $jobid = $_POST["jobid"];
    
    // Validate Customer ID
    $input_customerid = trim($_POST["customerid"]);
    if(empty($input_customerid)){
        $customerid_err = "Please enter expected job end date";     
    } else{
        $customerid = $input_customerid;
	}
    
    // Validate Job Type
    $input_jobtype = trim($_POST["jobtype"]);
    if(empty($input_jobtype)){
        $jobtype_err = "Please enter type of Job.";
    } elseif(!filter_var($input_jobtype, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $jobtype_err = "Please enter valid job type.";
    } else{
        $jobtype = $input_jobtype;
    }
    
    // Validate Job Time
    $input_jobtime = trim($_POST["jobtime"]);
    if(empty($input_jobtime)){
        $jobtime_err = "Please enter job time(In Days)";     
    } else{
        $jobtime = $input_jobtime;
    }
	
	// Validate Job Cost Estimate
    $input_jobestimate = trim($_POST["jobestimate"]);
    if(empty($input_jobestimate)){
        $jobestimate_err = "Please enter Job Cost Estimate.";     
    } elseif(!ctype_digit($input_jobestimate)){
        $jobestimate_err = "Please enter a positive integer value.";
    } else{
        $jobestimate = $input_jobestimate;
    }
	
	// Validate Job Status
    $input_jobstatus = trim($_POST["jobstatus"]);
    if(empty($input_jobstatus)){
        $jobstatus_err = "Please enter job Status";     
    } else{
        $jobstatus = $input_jobstatus;
	}
	
	// Validate Job Start Date
    $input_jobstart = trim($_POST["jobstart"]);
    if(empty($input_jobstart)){
        $jobstart_err = "Please enter expected job start date";     
    } else{
        $jobstart = $input_jobstart;
	}
	
	// Validate Job End Date
    $input_jobend = trim($_POST["jobend"]);
    if(empty($input_jobend)){
        $jobend_err = "Please enter expected job end date";     
    } else{
        $jobend = $input_jobend;
	}
	
    
    // Check input errors before inserting in database
    if(empty($jobtype_err) && empty($jobtime_err) && empty($jobestimate_err) && empty($jobstatus_err) && empty($jobstart_err) && empty($jobend_err)){
        // Prepare an update statement
        $sql = "UPDATE jobs SET customerid=?, jobtype=?, jobtime=?, jobestimate=?, jobstatus=?, jobstart=?, jobend=? WHERE jobid=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
			
			// Set parameters
            $param_customerid = $customerid;
            $param_jobtype = $jobtype;
			$param_jobtime = $jobtime;
			$param_jobestimate = $jobestimate;
			$param_jobstatus = $jobstatus;
			$param_jobstart = $jobstart;
			$param_jobend = $jobend;
			$param_jobid = $jobid;
			
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssi", $param_customerid, $param_jobtype, $param_jobtime, $param_jobestimate, $param_jobstatus, $param_jobstart, $param_jobend, $param_jobid);
            
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: jobs.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
				echo "SQL UPDATE QUERY FAILED";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
	//echo "something wierds going on";
    // Check existence of id parameter before processing further
    if(isset($_GET["jobid"]) && !empty(trim($_GET["jobid"]))){
        // Get URL parameter
        $jobid =  trim($_GET["jobid"]);

        // Prepare a select statement
        $sql = "SELECT * FROM jobs WHERE jobid = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_jobid);
            
            // Set parameters
            $param_jobid = $jobid;
            
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
                    // URL doesn't contain valid id. Redirect to error page
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
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  include('header.php')
  ?>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                        <h2>Update Job</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($customerid_err)) ? 'has-error' : ''; ?>">
                            <label>Customer ID</label>
                            <input type="text" name="customerid" class="form-control" value="<?php echo $customerid; ?>">
                            <span class="help-block"><?php echo $customerid_err;?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($jobtype_err)) ? 'has-error' : ''; ?>">
                            <label>Job Type</label>
                            <input type="text" name="jobtype" class="form-control" value="<?php echo $jobtype; ?>">
                            <span class="help-block"><?php echo $jobtype_err;?></span>
                        </div>
						
						<div class="form-group <?php echo (!empty($jobtime_err)) ? 'has-error' : ''; ?>">
                            <label>Expected Job Time In Days</label>
                            <input type="text" name="jobtime" class="form-control" value="<?php echo $jobtime; ?>">
                            <span class="help-block"><?php echo $jobtime_err;?></span>
                        </div>
						
						<div class="form-group <?php echo (!empty($jobestimate_err)) ? 'has-error' : ''; ?>">
                            <label>Job Cost Estimate</label>
                            <input type="text" name="jobestimate" class="form-control" value="<?php echo $jobestimate; ?>">
                            <span class="help-block"><?php echo $jobestimate_err;?></span>
                        </div>
						
						<div class="form-group <?php echo (!empty($jobstatus_err)) ? 'has-error' : ''; ?>">
                            <label>Jobs Status</label>
                            <input type="text" name="jobstatus" class="form-control" value="<?php echo $jobstatus; ?>">
                            <span class="help-block"><?php echo $jobstatus_err;?></span>
                        </div>
						
						<div class="form-group <?php echo (!empty($jobstart_err)) ? 'has-error' : ''; ?>">
                            <label>Job Start Date</label>
                            <input type="text" name="jobstart" class="form-control" value="<?php echo $jobstart; ?>">
                            <span class="help-block"><?php echo $jobstart_err;?></span>
                        </div>
						
						<div class="form-group <?php echo (!empty($jobend_err)) ? 'has-error' : ''; ?>">
                            <label>Job End Date</label>
                            <input type="text" name="jobend" class="form-control" value="<?php echo $jobend; ?>">
                            <span class="help-block"><?php echo $jobend_err;?></span>
                        </div>
                        <input type="hidden" name="jobid" value="<?php echo $jobid; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="jobs.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>