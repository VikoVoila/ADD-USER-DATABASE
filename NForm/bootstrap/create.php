<?php
// Include config file
require_once 'config.php';
 
// Define variables and initialize with empty values
$name = $email = $mobile = "";
$name_err = $email_err = $mobile_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var(trim($_POST["name"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $name_err = 'Please enter a valid name.';
    } else{
        $name = $input_name;
    }
    
    // Validate email address
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = 'Please enter an address.';     
    } else{
        $email = $input_email;
    }
    
    // Validate mobile
    $input_mobile = trim($_POST["mobile"]);
    if(empty($input_mobile)){
        $mobile_err = "Please enter the mobile amount.";     
    } elseif(!ctype_digit($input_mobile)){
        $mobile_err = 'Please enter a positive integer value.';
    } else{
        $mobile = $input_mobile;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($email_err) && empty($mobile_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO customers (name, email, mobile) VALUES (?, ?, ?)";
 
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_name, $param_email, $param_mobile);
            
            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_mobile = $mobile;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add new user record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <label>Email</label>
                                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">

                              
                            <span class="help-block"><?php echo $email_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($mobile_err)) ? 'has-error' : ''; ?>">
                            <label>Mobile</label>
                            <input type="text" name="mobile" class="form-control" value="<?php echo $mobile; ?>">
                            <span class="help-block"><?php echo $mobile_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
