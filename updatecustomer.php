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
$firstname = $surname = $phoneno = $address = $postcode = $facebook = $twitter = "";
$firstname_err = $surname_err = $phoneno_err = $address_err = $postcode_err = $facebook_err = $twitter_err = "";
 
 
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate firstname
    $input_firstname = trim($_POST["firstname"]);
    if(empty($input_firstname)){
        $firstname_err = "Please enter first name.";
    } elseif(!filter_var($input_firstname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $firstname_err = "Please enter a valid first name.";
    } else{
        $firstname = $input_firstname;
    }
      
    // Validate surname
    $input_surname = trim($_POST["surname"]);
    if(empty($input_surname)){
        $surname_err = "Please enter surname.";
    } elseif(!filter_var($input_surname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $surname_err = "Please enter a valid surname.";
    } else{
        $surname = $input_surname;
    }
	
	// Validate phonenumber
    $input_phoneno = trim($_POST["phoneno"]);
    if(empty($input_phoneno)){
        $phoneno_err = "Please enter a phone number.";     
    } elseif(!is_numeric($input_phoneno)){
        $phoneno_err = "Please enter a valid phone number";
    } else{
        $phoneno = $input_phoneno;
    }
	
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
	// Validate postcode
    $input_postcode = trim($_POST["postcode"]);
    if(empty($input_postcode)){
        $postcode_err = "Please enter an postcode.";     
    } else{
        $postcode = $input_postcode;
    }
    
		// Validate facebook
    $input_facebook = trim($_POST["facebook"]);
    if(empty($input_facebook)){
        $facebook_err = "Please enter a facebook link.";     
    } else{
        $facebook = $input_facebook;
    }
	
		// Validate twitter
    $input_twitter = trim($_POST["twitter"]);
    if(empty($input_twitter)){
        $twitter_err = "Please enter a twitter link.";     
    } else{
        $twitter = $input_twitter;
    }
	
    // Check input errors before inserting in database
    if(empty($firstname_err) && empty($surname_err) && empty($phoneno_err) && empty($address_err) && empty($postcode_err) && empty($facebook_err) && empty($postcode_err)){
        // Prepare an update statement
        $sql = "UPDATE customers SET firstname=?, surname=?, phoneno=?, address=?, postcode=?, facebook=?, twitter=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
			
			// Set parameters
            $param_firstname = $firstname;
            $param_surname = $surname;
            $param_phoneno = $phoneno;
			$param_address = $address;
			$param_postcode = $postcode;
			$param_facebook = $facebook;
			$param_twitter = $twitter;
            $param_id = $id;
			
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssi", $param_firstname, $param_surname, $param_phoneno, $param_address, $param_postcode, $param_facebook, $param_twitter, $param_id);
            

            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: customers.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM customers WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
					$firstname = $row["firstname"];
					$surname = $row["surname"];
					$phoneno = $row["phoneno"];
					$address = $row["address"];
					$postcode = $row["postcode"];
					$facebook = $row["facebook"];
					$twitter = $row["twitter"];
					$id = $row["id"];
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
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
					<div class="form-group">
                        <label>Unique ID</label>
                        <p class="form-control-static"><?php echo $row["id"]; ?></p>
                    </div>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($firstname_err)) ? 'has-error' : ''; ?>">
                            <label>First Name</label>
                            <input type="text" name="firstname" class="form-control" value="<?php echo $firstname; ?>">
                            <span class="help-block"><?php echo $firstname_err;?></span>
                        </div>
						
						<div class="form-group <?php echo (!empty($surname_err)) ? 'has-error' : ''; ?>">
                            <label>Surname</label>
                            <input type="text" name="surname" class="form-control" value="<?php echo $surname; ?>">
                            <span class="help-block"><?php echo $surname_err;?></span>
                        </div>
						
						<div class="form-group <?php echo (!empty($phoneno_err)) ? 'has-error' : ''; ?>">
                            <label>phoneno</label>
                            <input type="text" name="phoneno" class="form-control" value="<?php echo $phoneno; ?>">
                            <span class="help-block"><?php echo $phoneno_err;?></span>
                        </div>
						
                        <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                            <label>Address</label>
                            <textarea name="address" class="form-control"><?php echo $address; ?></textarea>
                            <span class="help-block"><?php echo $address_err;?></span>
                        </div>
						
                        <div class="form-group <?php echo (!empty($postcode_err)) ? 'has-error' : ''; ?>">
                            <label>Postcode</label>
                            <input type="text" name="postcode" class="form-control" value="<?php echo $postcode; ?>">
                            <span class="help-block"><?php echo $postcode_err;?></span>
                        </div>
						
						<div class="form-group <?php echo (!empty($facebook_err)) ? 'has-error' : ''; ?>">
                            <label>facebook</label>
                            <input type="text" name="facebook" class="form-control" value="<?php echo $facebook; ?>">
                            <span class="help-block"><?php echo $facebook_err;?></span>
                        </div>
						
						<div class="form-group <?php echo (!empty($twitter_err)) ? 'has-error' : ''; ?>">
                            <label>twitter</label>
                            <input type="text" name="twitter" class="form-control" value="<?php echo $twitter; ?>">
                            <span class="help-block"><?php echo $twitter_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="customers.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>