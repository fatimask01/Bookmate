<?php
session_start();
// 1. Set Error Reporting to see why it hangs (White Screen)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/config.php');

// 2. Clear existing admin sessions safely
if(isset($_SESSION['alogin']) && $_SESSION['alogin'] != ''){
    $_SESSION['alogin'] = '';
}

if(isset($_POST['login']))
{
    // 3. Basic sanitization
    $username = trim($_POST['username']);
    $password = md5($_POST['password']);

    // 4. Using SELECT with specific columns is faster than SELECT *
    $sql = "SELECT UserName, Password FROM admin WHERE UserName=:username AND Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    
    if($query->rowCount() > 0)
    {
        $_SESSION['alogin'] = $username;
        // 5. SUCCESS: Redirect and EXIT immediately to stop server load
        echo "<script type='text/javascript'> document.location ='admin/dashboard.php'; </script>";
        exit; 
    } else {
        echo "<script>alert('Invalid Details');</script>";
        // 6. FAILURE: Redirect back to reset POST data and EXIT
        echo "<script type='text/javascript'> document.location ='adminlogin.php'; </script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Admin Login</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
    <?php include('includes/header.php');?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">ADMIN LOGIN FORM</h4>
                </div>
            </div>
                         
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">LOGIN FORM</div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>Enter Username</label>
                                    <input class="form-control" type="text" name="username" autocomplete="off" required />
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="form-control" type="password" name="password" autocomplete="off" required />
                                </div>
                                <button type="submit" name="login" class="btn btn-info">LOGIN </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>

    <?php include('includes/footer.php');?>

    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>