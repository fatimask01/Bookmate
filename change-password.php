<?php
session_start();
include('includes/config.php');
error_reporting(0);

if(strlen($_SESSION['login'])==0) {   
    header('location:index.php');
} else {
    if(isset($_POST['change'])) {
        $current_password = $_POST['password'];
        $new_password = $_POST['newpassword'];
        $identifier = $_SESSION['login']; 

        $sql = "SELECT Password, EmailId FROM tblstudents WHERE EmailId=:ident OR StudentId=:ident";
        $query = $dbh->prepare($sql);
        $query->bindParam(':ident', $identifier, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        if($result) {
            if (password_verify($current_password, $result->Password)) {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $con = "UPDATE tblstudents SET Password=:newpassword WHERE EmailId=:email";
                $chngpwd1 = $dbh->prepare($con);
                $chngpwd1->bindParam(':email', $result->EmailId, PDO::PARAM_STR);
                $chngpwd1->bindParam(':newpassword', $new_hashed_password, PDO::PARAM_STR);
                $chngpwd1->execute();
                echo "<script>alert('Your Password was successfully changed');</script>";
            } else {
                echo "<script>alert('Your current password is wrong');</script>";
            }
        } else {
            echo "<script>alert('User not found. Please log in again.');</script>";
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Poppins:300,400,600,700' rel='stylesheet' type='text/css' /> 

    <style>
        /* YOUR EXACT CSS - UNCHANGED */
        html { 
            background: linear-gradient(135deg, rgb(225, 161, 25), #8c6411) fixed !important; 
            background-size: cover !important;
        }
        body { 
            background: transparent !important; 
            font-family: 'Poppins', sans-serif !important; 
            margin: 0; 
            min-height: 100vh;
        }

        .logo-section {
            padding: 15px 0;
            background-color: #fff;
            text-align: left;
        }
        .logo-section img { max-height: 60px; }

        .navbar {
            background-color: #ffffff !important;
            border-bottom: 4px solid rgb(225, 161, 25) !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
            margin-bottom: 0 !important;
            border-radius: 0 !important;
            padding: 5px 0 !important;
            border: none !important;
        }

        #menu-top li a {
            color: rgb(225, 161, 25) !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 14px !important;
            padding: 25px 25px !important;
            margin: 5px !important;
            background: transparent !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border-radius: 12px !important; 
        }

        #menu-top li a:hover, .menu-top-active {
            background-color: #8c6411 !important; 
            color: #ffffff !important; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
            transform: translateY(-2px) !important;
        }

        .dropdown-menu {
            background-color: #ffffff !important;
            border-top: 4px solid rgb(225, 161, 25) !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        }

        .content-wrapper { padding: 40px 0; }
        .header-line { 
            color: #fff !important; 
            font-weight: 300; 
            letter-spacing: 2px; 
            text-transform: uppercase; 
            margin-bottom: 40px; 
            text-align: center;
        }
        .header-line strong { font-weight: 800 !important; }

        .panel { 
            border-radius: 24px; 
            border: none; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.3); 
            max-width: 500px; 
            margin: auto; 
            background: rgba(255, 255, 255, 0.98);
        }

        .panel-heading { 
            background: transparent !important; 
            color: #8c6411 !important; 
            font-weight: 700; 
            text-align: center; 
            font-size: 20px; 
            padding: 25px;
            border-bottom: 1px solid #eee !important;
        }

        .form-group label { color: #8c6411; font-weight: 600; font-size: 13px; }
        
        .form-control { 
            border-radius: 12px; 
            height: 50px; 
            border: 2px solid #eee; 
            transition: all 0.3s ease;
        }

        .form-control:focus { 
            border-color: rgb(225, 161, 25) !important; 
            box-shadow: 0 0 12px rgba(225, 161, 25, 0.3) !important;
            outline: none !important;
        }

        .btn-change { 
            background: #8c6411; 
            color: #fff !important; 
            border: none; 
            padding: 15px; 
            border-radius: 12px; 
            font-weight: 700; 
            width: 100%; 
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-change:hover { 
            background: rgb(225, 161, 25); 
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(140, 100, 17, 0.3);
        }

        .home-footer {
            background: #111 !important; color: #eee !important; 
            padding: 30px 0; border-top: 5px solid rgb(225, 161, 25) !important;
            text-align: center;
            margin-top: 50px;
        }

        /* FUNCTIONAL EYE CSS (Necessary for positioning) */
        .form-group { position: relative; }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 38px;
            cursor: pointer;
            color: #8c6411;
            font-size: 18px;
            z-index: 10;
        }
        /* Hides the "Ghost Eye" browser default icon */
        input::-ms-reveal, input::-ms-clear { display: none !important; }
    </style>
</head>
<body>

<div class="logo-section">
    <div class="container">
        <a href="dashboard.php"><img src="assets/img/logo.png" /></a>
    </div>
</div>

<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
                <span class="icon-bar" style="background: rgb(225, 161, 25)"></span>
                <span class="icon-bar" style="background: rgb(225, 161, 25)"></span>
                <span class="icon-bar" style="background: rgb(225, 161, 25)"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="main-nav">
            <ul id="menu-top" class="nav navbar-nav navbar-right">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="listed-books.php">Explore Books</a></li>
                <li><a href="issued-books.php">My History</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account <i class="fa fa-angle-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="my-profile.php">Profile</a></li>
                        <li><a href="change-password.php" class="menu-top-active">Security</a></li>
                        <li class="divider"></li>
                        <li><a href="logout.php" style="color:#e74c3c !important;">Sign Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">🔐<strong>Change Password</strong></h4>
            </div>
        </div>
        
        <div class="panel">
            <div class="panel-heading">Update Credentials</div>
            <div class="panel-body">
                <form name="chngpwd" method="post" onSubmit="return valid();">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input class="form-control" type="password" name="password" id="password" autocomplete="off" required />
                        <i class="fa fa-eye-slash toggle-password" onclick="togglePass('password', this)"></i>
                    </div>

                    <div class="form-group">
                        <label>New Password</label>
                        <input class="form-control" type="password" name="newpassword" id="newpassword" autocomplete="off" required />
                        <i class="fa fa-eye-slash toggle-password" onclick="togglePass('newpassword', this)"></i>
                    </div>

                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input class="form-control" type="password" name="confirmpassword" id="confirmpassword" autocomplete="off" required />
                        <i class="fa fa-eye-slash toggle-password" onclick="togglePass('confirmpassword', this)"></i>
                    </div>

                    <button type="submit" name="change" class="btn btn-change">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<footer class="home-footer">
    <div class="container">
        &copy; <?php echo date("Y"); ?> Online Library Management System | User Dashboard
    </div>
</footer>

<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.js"></script>

<script>
function valid() {
    if(document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
        alert("New Password and Confirm Password Field do not match!!");
        document.chngpwd.confirmpassword.focus();
        return false;
    }
    return true;
}

function togglePass(id, el) {
    const input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
        el.classList.replace("fa-eye-slash", "fa-eye");
    } else {
        input.type = "password";
        el.classList.replace("fa-eye", "fa-eye-slash");
    }
}
</script>

</body>
</html>
<?php } ?>