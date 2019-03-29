<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  include('header.php')
  ?>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Customer Job Search</h2>
                       
                    </div>

<?php

// Check existence of id parameter before processing further
if(isset($_GET["customerid"]) && !empty(trim($_GET["customerid"]))){
    // Include config file
    require_once "config.php";
    // Prepare a select statement
    $sql = "SELECT * FROM jobs WHERE customerid = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
		// Set parameters
        $param_id = trim($_GET["customerid"]);
		
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
              
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
			
            if(mysqli_num_rows($result) > 0){
                
                echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Job#</th>";
                                        echo "<th>Customer</th>";
										echo "<th>Job Type</th>";
                                        echo "<th>Job Time</th>";
                                        echo "<th>Job Estimate</th>";
                                        echo "<th>Job Status</th>";
										echo "<th>Job Start Date</th>";
										echo "<th>Job End Date</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['jobid'] . "</td>";
                                        echo "<td><a href='readcustomer.php?id=". $row['customerid'] ."' title='View Customer Details' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a></td>";
										echo "<td>" . $row['jobtype'] . "</td>";
										echo "<td>" . $row['jobtime'] . "</td>";
										echo "<td>" . $row['jobestimate'] . "</td>";
										echo "<td>" . $row['jobstatus'] . "</td>";
                                        echo "<td>" . $row['jobstart'] . "</td>";
                                        echo "<td>" . $row['jobend'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='readjob.php?jobid=". $row['jobid'] ."' title='View Record' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                            echo "<a href='updatejob.php?jobid=". $row['jobid'] ."' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='deletejob.php?jobid=". $row['jobid'] ."' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
				
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