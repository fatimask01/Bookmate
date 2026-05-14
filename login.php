<?php
session_start();
include('includes/config.php');

/* Prevent browser caching */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

if(isset($_POST['login'])) {
    // Trim inputs to handle accidental spaces from different keyboards/browsers
    $username = trim($_POST['username']); 
    $password = trim($_POST['password']); 
    $type = $_POST['type']; 

    if($type == "admin") {
        $adminPassword = md5($password);
        $sql ="SELECT UserName,Password FROM admin WHERE UserName=:username and Password=:password";
        $query= $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $adminPassword, PDO::PARAM_STR);
        $query->execute();

        if($query->rowCount() > 0) {
            $_SESSION['alogin'] = $username;
            echo "<script type='text/javascript'> document.location ='admin/dashboard.php'; </script>";
            exit;
        } else {
            echo "<script>alert('Invalid Admin Details');</script>";
        }
    } else {
        // Use StudentId for the lookup
        $sql ="SELECT StudentId, Password, Status FROM tblstudents WHERE StudentId=:studentid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentid', $username, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        if($query->rowCount() > 0) {
            // Securely verify the hashed password
            if (password_verify($password, $result->Password)) {
                if($result->Status == 1) {
                    $_SESSION['stdid'] = $result->StudentId;
                    $_SESSION['login'] = $username;
                    echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
                    exit;
                } else {
                    echo "<script>alert('Your Account Has been blocked. Please contact admin');</script>";
                }
            } else {
                echo "<script>alert('Invalid Password');</script>";
            }
        } else {
            echo "<script>alert('Invalid Student ID');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Login</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Poppins:400,600,700' rel='stylesheet' type='text/css' />

<style>
    /* ===== 1. GLOBAL THEME (MATCHES INDEX) ===== */
    .content-wrapper { 
        margin-top: 40px; 
        min-height: 80vh; 
        display: flex; 
        align-items: center; 
    }

    #menu-top li a, .navbar-nav li a {
        color: rgb(225, 161, 25) !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        padding: 15px 25px !important;
        border-radius: 12px !important;
        transition: 0.3s;
    }

    /* ===== 3. LOGIN CARD ===== */
    .panel {
        max-width: 450px; 
        margin: auto;
        border-radius: 20px !important; 
        background: #ffffff;
        box-shadow: 0 25px 60px rgba(0,0,0,0.3);
        border: none !important;
        overflow: hidden;
    }

    .panel-heading {
        background: #ffffff !important;
        padding: 40px 10px 10px 10px !important;
        font-size: 26px;
        font-weight: 800;
        text-align: center;
        color: #8c6411 !important; /* Bronze Title */
        border: none !important;
    }

    .panel-body { padding: 30px 40px 40px 40px !important; }

    /* ===== 4. FORM INPUTS ===== */
    .form-group {
        position: relative;
        margin-bottom: 25px;
    }

    label {
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
    }

    .form-control {
        height: 52px;
        border-radius: 12px !important;
        background: #fdfdfd;
        border: 2px solid #eee;
        padding-right: 45px !important;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: rgb(225, 161, 25);
        box-shadow: 0 0 8px rgba(225, 161, 25, 0.2);
        background: #fff;
    }

    /* Eye Icon & Select Arrow */
    .toggle-password, .select-arrow {
        position: absolute;
        right: 18px;
        bottom: 16px;
        cursor: pointer;
        color: #8c6411;
        font-size: 16px;
        z-index: 10;
    }

    /* ===== 5. LOGIN BUTTON (GOLD) ===== */
    .btn-login {
        width: 100%;
        border-radius: 30px;
        background: linear-gradient(to right, rgb(225, 161, 25), #8c6411) !important;
        color: #fff !important;
        font-weight: 700;
        letter-spacing: 1px;
        padding: 12px;
        margin-top: 15px;
        border: none;
        height: 55px;
        box-shadow: 0 4px 15px rgba(140, 100, 17, 0.3);
        transition: 0.3s;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(140, 100, 17, 0.4);
        filter: brightness(1.1);
    }

    /* Signup Link */
    .signup-link {
        color: #8c6411 !important;
        font-weight: 700;
        text-decoration: none;
    }

    .signup-link:hover {
        text-decoration: underline;
    }
    /* ===== GLOBAL THEME ===== */
    html {
        background: linear-gradient(135deg, rgb(225, 161, 25), #8c6411) fixed !important;
    }
    body {
        background: transparent !important;
        font-family: 'Poppins', sans-serif !important;
        margin: 0;
        min-height: 100vh;
    }

    /* ===== FORCED WHITE NAVBAR ===== */
    .navbar-inverse, .navbar-default, .navbar {
        background-color: #ffffff !important;
        background-image: none !important;
        border-bottom: 4px solid rgb(225, 161, 25) !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        margin-bottom: 0 !important;
        border-radius: 0 !important;
        padding: 5px 0 !important;
    }

    .navbar-brand {
        color: rgb(225, 161, 25) !important;
        font-weight: 700 !important;
        font-size: 22px !important;
    }

    .navbar-nav > li > a {
        color: rgb(225, 161, 25) !important; 
        font-weight: 600 !important;
        text-transform: uppercase !important;
        font-size: 13px !important;
        padding: 20px 20px !important;
        background: transparent !important;
        transition: all 0.3s ease !important;
    }

    .navbar-nav > li > a:hover, 
    .navbar-nav > li.active > a,
    .navbar-nav > li.open > a {
        color: #8c6411 !important; 
        background-color: #f9f9f9 !important;
    }

    .dropdown-menu {
        background-color: #ffffff !important;
        border: none !important;
        border-top: 4px solid rgb(225, 161, 25) !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        margin-top: 0 !important;
        padding: 0 !important;
    }

    .dropdown-menu > li > a {
        color: rgb(225, 161, 25) !important; 
        padding: 12px 20px !important;
        font-weight: 500 !important;
        border-bottom: 1px solid #f1f1f1 !important;
        transition: all 0.2s ease !important;
    }

    .dropdown-menu > li > a:hover {
        background-color: rgb(225, 161, 25) !important;
        color: #ffffff !important;
        padding-left: 25px !important;
    }

    .caret {
        border-top-color: #333 !important;
    }
    .navbar-nav > li > a:hover .caret {
        border-top-color: rgb(225, 161, 25) !important;
    }

    .home-footer {
        background: #111 !important; 
        color: #eee !important; 
        padding: 40px 0 30px !important;
        border-top: 5px solid rgb(225, 161, 25) !important;
        text-align: center !important;
        width: 100% !important;
        margin-top: 50px !important;
    }

    .navbar, .navbar-inverse, .navbar-default, .menu-section {
        background-color: #ffffff !important;
        background-image: none !important;
        border-bottom: none !important;
        box-shadow: 0 -5px 15px rgba(0,0,0,0.1), 0 2px 10px rgba(0,0,0,0.05) !important;
        min-height: 80px !important;
        display: flex !important;
        align-items: center !important;
        margin-bottom: 0 !important;
        border-radius: 0 !important;
    }

    #menu-top li a, .navbar-nav li a, .navbar-brand {
        color: rgb(225, 161, 25) !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        font-size: 14px !important;
        padding: 22px 25px !important; 
        margin: 0px !important; 
        background: transparent !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        border-radius: 12px !important;
        display: inline-block !important;
        text-decoration: none !important;
    }

    #menu-top li a:hover, 
    .navbar-nav li a:hover,
    .navbar-nav li.active > a,
    .navbar-nav li.open > a {
        background-color: #8c6411 !important; 
        color: #ffffff !important; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
        transform: translateY(-1px);
    }

    label {
        color: #444 !important; 
        font-weight: 600 !important;
        font-size: 13px;
        text-transform: uppercase;
        margin-bottom: 8px;
        display: block;
    }

    .toggle-password {
        color: #8c6411 !important; 
        cursor: pointer;
    }
</style>

</head>
<body>
<?php include('includes/header.php');?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-8 col-xs-12 col-md-offset-3 col-sm-offset-2">
                <div class="panel">
                    <div class="panel-heading">LOGIN FORM</div>
                    <div class="panel-body">
                        <form role="form" method="post">

                            <div class="form-group">
                                <label>User ID / Student ID</label>
                                <input type="text" class="form-control" name="username" required autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input id="password" class="form-control" type="password" name="password" required autocomplete="off">
                                <i class="fa fa-eye-slash toggle-password" onclick="toggleVisibility('password', this)"></i>
                            </div>

                            <div class="form-group">
                                <label>Login Type</label>
                                <select class="form-control" name="type" required>
                                    <option value="student">Student</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <button type="submit" name="login" class="btn btn-login">LOGIN NOW</button>
                            
                            <div style="text-align:center; margin-top:20px;">
                                Not registered? <a href="signup.php" style="color:#8c6411; font-weight:700;">Signup Here</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>

<footer class="home-footer">
    <div class="container">
        &copy; <?php echo date("Y"); ?> Online Library Management System | Build with care 
    </div>
</footer>

<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script>
function toggleVisibility(inputId, iconElement) {
    var x = document.getElementById(inputId);
    if (x.type === "password") {
        x.type = "text";
        iconElement.classList.replace("fa-eye-slash", "fa-eye");
    } else {
        x.type = "password";
        iconElement.classList.replace("fa-eye", "fa-eye-slash");
    }
}
</script>
</body>
</html>