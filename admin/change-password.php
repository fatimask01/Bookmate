<?php
session_start();
include('includes/config.php');
error_reporting(0);
if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 
    if(isset($_POST['change'])) {
        $password=md5($_POST['password']);
        $newpassword=md5($_POST['newpassword']);
        $username=$_SESSION['alogin'];
        $sql ="SELECT Password FROM admin where UserName=:username and Password=:password";
        $query= $dbh -> prepare($sql);
        $query-> bindParam(':username', $username, PDO::PARAM_STR);
        $query-> bindParam(':password', $password, PDO::PARAM_STR);
        $query-> execute();
        if($query -> rowCount() > 0) {
            $con="update admin set Password=:newpassword where UserName=:username";
            $chngpwd1 = $dbh->prepare($con);
            $chngpwd1-> bindParam(':username', $username, PDO::PARAM_STR);
            $chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $chngpwd1->execute();
            $msg="Your Password successfully changed";
        } else {
            $error="Your current password is wrong";  
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>OLMS | Change Password</title>
    
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap' rel='stylesheet' />
    
    <style>
        /* Keeping your original CSS exactly as requested */
        html, body { height: 100%; margin: 0; background-color: #ffffff !important; }
        body { display: flex; flex-direction: column; font-family: 'Poppins', sans-serif; }

        .navbar-inverse {
            background-color: #ffffff !important;
            border: none !important;
            margin-bottom: 0 !important;
            min-height: auto !important;
        }

        .menu-section {
            background-color: #ffffff !important;
            border-bottom: 5px solid rgb(225, 161, 25) !important;
            width: 100%;
            z-index: 99;
        }

        #menu-top li a {
            color: rgb(225, 161, 25) !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 13px;
            padding: 12px 20px !important;
            margin: 10px 5px !important;
            border-radius: 10px !important;
            transition: all 0.3s ease;
        }

        #menu-top li a:hover, #menu-top li a.menu-top-active {
            background-color: #8c6411 !important; 
            color: #ffffff !important; 
            transform: translateY(-2px);
        }

        .content-wrapper { 
            flex: 1 0 auto;
            background: linear-gradient(135deg, rgb(225, 161, 25) 0%, #8c6411 100%) !important; 
            padding: 80px 0;
            display: flex;
            align-items: center;
        }

        .panel { 
            max-width: 450px; 
            margin: auto; 
            border-radius: 25px !important; 
            border: none !important; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.3) !important; 
            background: #fff; 
            overflow: hidden; 
            padding: 25px;
        }

        .form-group { position: relative; margin-bottom: 20px; }
        label { font-weight: 700; color: #8c6411; margin-bottom: 5px; text-transform: uppercase; font-size: 12px; }
        
        .form-control {
            height: 50px !important; 
            border-radius: 12px !important;
            padding-right: 45px !important; 
            border: 2px solid #eee !important;
        }

        .form-control:focus {
            border-color: rgb(225, 161, 25) !important;
            box-shadow: 0 0 8px rgba(225, 161, 25, 0.2) !important;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            bottom: 14px;
            cursor: pointer;
            color: #8c6411 !important; 
            font-size: 16px;
            z-index: 100;
        }

        .btn-update { 
            width: 100%; 
            height: 55px; 
            border-radius: 27px !important; 
            background: linear-gradient(135deg, rgb(225, 161, 25) 0%, #8c6411 100%) !important; 
            border: none; 
            font-weight: 800; 
            color: #fff !important; 
            margin-top: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        
        .btn-update:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 8px 20px rgba(0,0,0,0.3); 
        }

        input::-ms-reveal, input::-ms-clear { display: none !important; }
    </style>
</head>

<body>
    <?php include('includes/header.php');?>

    <div class="content-wrapper">
        <div class="container">
            <div class="panel">
                <div class="panel-heading" style="color: #8c6411; text-align: center; font-weight: 800; font-size: 20px; padding-bottom: 15px; text-transform: uppercase;">
                    Update Credentials
                </div>
                <div class="panel-body">
                    <?php if($error){ ?><div class="alert alert-danger"><?php echo htmlentities($error); ?></div><?php } ?>
                    <?php if($msg){ ?><div class="alert alert-success"><?php echo htmlentities($msg); ?></div><?php } ?>

                    <form role="form" method="post" name="chngpwd" onSubmit="return valid();">
                        <div class="form-group">
                            <label>Current Password</label>
                            <input class="form-control" type="password" name="password" id="password" required autocomplete="off" />
                            <i class="fa fa-eye-slash toggle-password" onclick="toggleVisibility('password', this)"></i>
                        </div>

                        <div class="form-group">
                            <label>New Password</label>
                            <input class="form-control" type="password" name="newpassword" id="new_pwd" required />
                            <i class="fa fa-eye-slash toggle-password" onclick="toggleVisibility('new_pwd', this)"></i>
                        </div>

                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input class="form-control" type="password" name="confirmpassword" id="confirmpassword" required autocomplete="off" />
                            <i class="fa fa-eye-slash toggle-password" onclick="toggleVisibility('confirmpassword', this)"></i>
                        </div>

                        <button type="submit" name="change" class="btn btn-update">UPDATE PASSWORD</button> 
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php');?>

    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    
    <script>
    function valid() {
        if(document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
            alert("Passwords do not match!");
            document.chngpwd.confirmpassword.focus();
            return false;
        }
        return true;
    }

    function toggleVisibility(inputId, iconElement) {
        const inputField = document.getElementById(inputId);
        if (inputField.type === "password") {
            inputField.type = "text"; 
            iconElement.classList.replace("fa-eye-slash", "fa-eye");
        } else {
            inputField.type = "password"; 
            iconElement.classList.replace("fa-eye", "fa-eye-slash");
        }
    }
    </script>
</body>
</html>
<?php } ?>