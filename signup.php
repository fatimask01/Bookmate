<?php
session_start();
include('includes/config.php');
error_reporting(0);

if(isset($_POST['signup'])) {
    // 1. Collect and trim inputs
    $fname=$_POST['fullname'];
    $mobileno=$_POST['mobileno'];
    $email=$_POST['email']; 
    $password=$_POST['password'];
    
    // 2. Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // 3. SECURE Student ID Generation
    // Using MAX(id) is safer than rowCount() to prevent ID duplication
    $sql_id = "SELECT MAX(id) as lastid FROM tblstudents";
    $query_id = $dbh->prepare($sql_id);
    $query_id->execute();
    $row = $query_id->fetch(PDO::FETCH_OBJ);
    $nextIdNum = ($row->lastid) ? $row->lastid + 1 : 1;
    $studentId = "SID" . str_pad($nextIdNum, 3, '0', STR_PAD_LEFT);
    
    // 4. Insert into database
    $sql="INSERT INTO tblstudents(StudentId,FullName,MobileNumber,EmailId,Password,Status) VALUES(:studentId,:fname,:mobileno,:email,:hashedPassword,1)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':studentId',$studentId,PDO::PARAM_STR);
    $query->bindParam(':fname',$fname,PDO::PARAM_STR);
    $query->bindParam(':mobileno',$mobileno,PDO::PARAM_STR);
    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->bindParam(':hashedPassword',$hashedPassword,PDO::PARAM_STR); 
    
    if($query->execute()) {
        echo '<script>alert("Success! Your Student ID is '.$studentId.'")</script>';
        echo "<script>window.location.href='index.php'</script>";
    } else {
        echo "<script>alert('Error. Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>OLMS | User Signup</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Poppins:400,600,700' rel='stylesheet' type='text/css' />
    
<style>
/* ================= GLOBAL THEME ================= */
html { background: linear-gradient(135deg, rgb(225, 161, 25), #8c6411) fixed !important; }
body { margin: 0; font-family: 'Poppins', sans-serif; background: transparent !important; min-height: 100vh; }

.content-wrapper { 
    margin-top: 40px; 
    padding-bottom: 60px;
}

/* ================= SIGNUP CARD ================= */
.panel {
    max-width: 500px; 
    margin: auto;
    border-radius: 16px !important; 
    background: #fff;
    box-shadow: 0 25px 60px rgba(0,0,0,0.25);
    border: none !important;
    overflow: hidden;
}

.panel-heading {
    background: #ffffff !important;
    padding: 30px 10px 10px 10px !important;
    font-size: 24px;
    font-weight: 700;
    text-align: center;
    color: #8c6411 !important; /* Professional Bronze Heading */
    border-bottom: 1px solid #f1f1f1 !important;
}

.panel-body { padding: 30px !important; }

/* 2. THE LABELS (Professional Dark Charcoal) */
label {
    color: #444 !important; 
    font-weight: 600 !important;
    font-size: 13px;
    text-transform: uppercase;
    margin-bottom: 8px;
    display: block;
}

/* ================= INPUTS & EYE POSITIONING ================= */
.form-group {
    position: relative;
    margin-bottom: 20px;
}

.form-control {
    height: 48px;
    border-radius: 10px !important;
    background: #f8fafc;
    padding-right: 45px !important;
    border: 1px solid #ddd;
}

.form-control::-ms-reveal, .form-control::-ms-clear { display: none !important; }

/* 4. INTERACTIVE ELEMENTS (Eye Icons) */
.toggle-password {
    position: absolute;
    right: 15px;
    bottom: 14px;
    cursor: pointer;
    color: #8c6411 !important; /* Bronze Eye Icon */
    font-size: 16px;
    z-index: 10;
}

.toggle-password:hover { color: rgb(225, 161, 25); }

/* 3. THE REGISTRATION BUTTON (Signature Gold Gradient) */
.btn-signup {
    width: 100%;
    border-radius: 30px;
    background: linear-gradient(135deg, rgb(225, 161, 25), #8c6411) !important;
    color: #fff !important;
    font-weight: 700;
    letter-spacing: 1px;
    padding: 12px;
    margin-top: 10px;
    border: none;
    height: 50px;
    transition: all 0.3s ease;
}

.btn-signup:hover {
    box-shadow: 0 5px 15px rgba(140, 100, 17, 0.3);
    transform: translateY(-1px);
}

/* 4. INTERACTIVE ELEMENTS (Bottom Link) */
.login-link-area {
    text-align: center;
    margin-top: 20px;
    color: #666;
}

.login-link-area a {
    color: #8c6411 !important; /* Signature Deep Bronze link */
    font-weight: 700;
    text-decoration: none;
}

.login-link-area a:hover {
    color: rgb(225, 161, 25) !important;
    text-decoration: underline;
}

/* ===== NAVBAR STYLING ===== */
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
    background: transparent !important;
    border-radius: 12px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

#menu-top li a:hover, .navbar-nav li a:hover {
    background-color: #8c6411 !important; 
    color: #ffffff !important; 
}

/* ===== FOOTER STYLING ===== */
.home-footer {
    background: #111 !important; 
    color: #eee !important; 
    padding: 40px 0 30px !important;
    border-top: 5px solid rgb(225, 161, 25) !important;
    text-align: center !important;
    width: 100% !important;
    margin-top: 50px !important;
}

.home-footer-bottom { 
    color: rgb(225, 161, 25) !important; 
    font-size: 13px !important;
    font-weight: 600 !important;
    text-transform: uppercase;
}

.caret { border-top-color: rgb(225, 161, 25) !important; }

/* ================= INPUTS & GOLDEN FOCUS ================= */
.form-group {
    position: relative;
    margin-bottom: 20px;
}

.form-control {
    height: 48px;
    border-radius: 10px !important;
    background: #f8fafc;
    padding-right: 45px !important;
    border: 1px solid #ddd;
    transition: all 0.3s ease-in-out;
}

.form-control:focus {
    border-color: rgb(225, 161, 25) !important;
    outline: 0 !important;
    box-shadow: 0 0 8px rgba(225, 161, 25, 0.5) !important;
    background-color: #fff;
}

.not-matched {
    border-color: rgb(225, 161, 25) !important;
    box-shadow: 0 0 12px rgba(225, 161, 25, 0.4) !important;
}

.matched {
    border-color: #2ecc71 !important;
    box-shadow: 0 0 12px rgba(46, 204, 113, 0.4) !important;
}

.navbar-brand {
    color: rgb(225, 161, 25) !important;
    font-weight: 700 !important;
    font-size: 22px !important;
}
</style>
</head>
<body>
    <?php include('includes/header.php');?>
    
    <div class="content-wrapper">
        <div class="container">
            <div class="panel">
                <div class="panel-heading">USER SIGNUP</div>
                <div class="panel-body">
                    <form name="signup" method="post" onSubmit="return valid();">
                        
                        <div class="form-group">
                            <label>Full Name</label>
                            <input class="form-control" type="text" name="fullname" required autocomplete="off" />
                        </div>
                        
                        <div class="form-group">
                            <label>Mobile Number</label>
                            <input class="form-control" type="text" name="mobileno" maxlength="10" required autocomplete="off" />
                        </div>
                        
                        <div class="form-group">
                            <label>Email ID</label>
                            <input class="form-control" type="email" name="email" required autocomplete="off" />
                        </div>
                        
                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" type="password" name="password" id="password" required autocomplete="off" />
                            <i class="fa fa-eye-slash toggle-password" onclick="toggleVisibility('password', this)"></i>
                        </div>
                        
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input class="form-control" type="password" name="confirmpassword" id="confirmpassword" required autocomplete="off" />
                            <i class="fa fa-eye-slash toggle-password" onclick="toggleVisibility('confirmpassword', this)"></i>
                        </div>
                        
                        <button type="submit" name="signup" class="btn btn-signup">REGISTER NOW</button>
                        
                        <div class="login-link-area">
                            Already have an account? <a href="login.php">Login Here</a>
                        </div>
                    </form>
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

    function valid() {
        if(document.signup.password.value != document.signup.confirmpassword.value) {
            alert("Passwords do not match!");
            document.signup.confirmpassword.focus();
            return false;
        }
        return true;
    }
    </script>
</body>
</html>