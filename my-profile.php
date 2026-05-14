<?php 
session_start();
include('includes/config.php');
error_reporting(0);
if(strlen($_SESSION['login'])==0) {   
    header('location:index.php');
} else { 
    if(isset($_POST['update'])) {    
        $sid=$_SESSION['stdid'];  
        $fname=$_POST['fullanme'];
        $mobileno=$_POST['mobileno'];

        $sql="update tblstudents set FullName=:fname,MobileNumber=:mobileno where StudentId=:sid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':sid',$sid,PDO::PARAM_STR);
        $query->bindParam(':fname',$fname,PDO::PARAM_STR);
        $query->bindParam(':mobileno',$mobileno,PDO::PARAM_STR);
        $query->execute();

        echo '<script>alert("Your profile has been updated")</script>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>OLMS | Student Profile</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Poppins:300,400,600,700' rel='stylesheet' type='text/css' /> 
    
    <style>
        /* ===== GLOBAL THEME & BACKGROUND (Matched) ===== */
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

        /* ===== 1. LOGO SECTION ===== */
        .logo-section {
            padding: 15px 0;
            background-color: #fff;
            text-align: left;
        }
        .logo-section img {
            max-height: 60px;
        }

        /* ===== 2. NAVBAR CONTAINER ===== */
        .navbar {
            background-color: #ffffff !important;
            border-bottom: 4px solid rgb(225, 161, 25) !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
            margin-bottom: 0 !important;
            border-radius: 0 !important;
            padding: 5px 0 !important;
            min-height: 70px !important;
            border: none !important;
        }

        /* ===== 3. NAVIGATION BUTTONS ===== */
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
            border: none !important;
            border-top: 4px solid rgb(225, 161, 25) !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
            padding: 0 !important;
            overflow: hidden;
        }
        .dropdown-menu > li > a {
            color: rgb(225, 161, 25) !important;
            padding: 15px 20px !important;
            font-weight: 600 !important;
        }
        .dropdown-menu > li > a:hover {
            background-color: rgb(225, 161, 25) !important;
            color: #ffffff !important;
        }

        /* ===== 4. CONTENT WRAPPER ===== */
        .content-wrapper { padding: 40px 0; }
        
        .header-line { 
            color: #fff !important; 
            font-weight: 300; 
            letter-spacing: 2px; 
            text-transform: uppercase; 
            margin-bottom: 40px; 
            text-align: center;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .header-line strong { font-weight: 800 !important; }

        /* ===== 5. PROFILE CARD ===== */
        .panel { 
            border-radius: 24px; 
            border: none; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.3); 
            max-width: 600px; 
            margin: auto; 
            background: rgba(255, 255, 255, 0.98);
            overflow: hidden;
        }

        .panel-heading { 
            background: #fff !important; 
            color: #8c6411 !important; 
            font-weight: 700; 
            text-align: center; 
            font-size: 20px; 
            padding: 25px;
            border-bottom: 1px solid #f1f1f1 !important;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .panel-body { padding: 35px !important; }

        /* Info Box Style */
        .info-box {
            background: #fdfaf3;
            border: 1px solid #f3e8d2;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-item label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 2px;
            font-weight: 700;
        }

        .info-item span {
            font-size: 14px;
            color: #444;
            font-weight: 600;
        }

        /* Form Controls */
        .form-group label { 
            font-weight: 600; 
            color: #8c6411; 
            font-size: 13px;
            margin-bottom: 8px;
        }

        .form-control { 
            border-radius: 12px; 
            height: 50px; 
            border: 2px solid #eee; 
            transition: 0.3s;
            box-shadow: none !important;
        }

        .form-control:focus { 
            border-color: rgb(225, 161, 25) !important; 
            background-color: #fff;
        }

        /* Buttons */
        .btn-update { 
            background: #8c6411; 
            color: #fff !important; 
            border: none; 
            padding: 15px; 
            border-radius: 12px; 
            font-weight: 700; 
            width: 100%; 
            transition: 0.3s; 
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .btn-update:hover { 
            background: rgb(225, 161, 25); 
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(140, 100, 17, 0.3);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
        }
        .active-bg { background: #e6f4ea; color: #1e7e34; }

        /* ===== 6. FOOTER ===== */
        .home-footer {
            background: #111 !important; color: #eee !important; 
            padding: 30px 0; border-top: 5px solid rgb(225, 161, 25) !important;
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="logo-section">
    <div class="container">
        <a href="dashboard.php">
            <img src="assets/img/logo.png" alt="Library Logo">
        </a>
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
                        <li><a href="my-profile.php" class="menu-top-active">Profile</a></li>
                        <li><a href="change-password.php">Security</a></li>
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
                <h4 class="header-line">👤<strong>My Profile</strong></h4>
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">Student Information</div>
            <div class="panel-body">
                <form name="signup" method="post">
                    <?php 
                    $sid=$_SESSION['stdid'];
                    $sql="SELECT StudentId,FullName,MobileNumber,RegDate,Status from tblstudents where StudentId=:sid ";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    if($query->rowCount() > 0) {
                        foreach($results as $result) { ?>  

                        <div class="info-box">
                            <div class="info-item">
                                <label>Student ID</label>
                                <span><?php echo htmlentities($result->StudentId);?></span>
                            </div>
                            <div class="info-item" style="text-align:center;">
                                <label>Member Since</label>
                                <span><?php echo htmlentities($result->RegDate);?></span>
                            </div>
                            <div class="info-item" style="text-align:right;">
                                <label>Status</label>
                                <span class="status-badge <?php echo ($result->Status==1) ? 'active-bg' : 'blocked-bg'; ?>">
                                    <?php echo ($result->Status==1) ? '● Active' : '● Blocked'; ?>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fa fa-user"></i> Full Name</label>
                            <input class="form-control" type="text" name="fullanme" value="<?php echo htmlentities($result->FullName);?>" required />
                        </div>

                        <div class="form-group">
                            <label><i class="fa fa-phone"></i> Mobile Number</label>
                            <input class="form-control" type="text" name="mobileno" maxlength="10" value="<?php echo htmlentities($result->MobileNumber);?>" required />
                        </div>

                    <?php }} ?>
                    
                    <button type="submit" name="update" class="btn btn-update">Save Profile Changes</button>
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
</body>
</html>
<?php } ?>