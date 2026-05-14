<?php
session_start();
error_reporting(0);
include('includes/config.php');
if($_SESSION['login']!=''){
$_SESSION['login']='';
}
if(isset($_POST['login']))
{
    $email=$_POST['emailid'];
    $password=md5($_POST['password']);
    $sql ="SELECT EmailId,Password,StudentId,Status FROM tblstudents WHERE EmailId=:email and Password=:password";
    $query= $dbh -> prepare($sql);
    $query-> bindParam(':email', $email, PDO::PARAM_STR);
    $query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() > 0)
    {
        foreach ($results as $result) {
            $_SESSION['stdid']=$result->StudentId;
            if($result->Status==1) {
                $_SESSION['login']=$_POST['emailid'];
                echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
            } else {
                echo "<script>alert('Your Account Has been blocked. Please contact admin');</script>";
            }
        }
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Poppins:400,600,700' rel='stylesheet' type='text/css' />
    
    <style>
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
        /* This targets the container and ensures no dark background exists */
        .navbar-inverse, .navbar-default, .navbar {
            background-color: #ffffff !important;
            background-image: none !important; /* Removes Bootstrap gradients */
            border-bottom: 4px solid rgb(225, 161, 25) !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
            margin-bottom: 0 !important;
            border-radius: 0 !important;
            padding: 5px 0 !important;
        }

        /* Navbar Brand/Logo */
        .navbar-brand {
            color: rgb(225, 161, 25) !important;
            font-weight: 700 !important;
            font-size: 22px !important;
        }

        /* Professional Nav Links (Home, Login, Signup, Help) */
        .navbar-
         > li > a {
color: rgb(225, 161, 25) !important; /* Changed from #333 to Golden */            font-weight: 600 !important;
            text-transform: uppercase !important;
            font-size: 13px !important;
            padding: 20px 20px !important;
            background: transparent !important;
            transition: all 0.3s ease !important;
        }

        /* Global Hovering Effect */
        .navbar-nav > li > a:hover, 
        .navbar-nav > li.active > a,
        .navbar-nav > li.open > a {
color: #8c6411 !important; /* Darker gold on hover for readability */            background-color: #f9f9f9 !important; /* Very subtle light bg */
        }

        /* ===== HELP DROPDOWN FIX ===== */
        .dropdown-menu {
            background-color: #ffffff !important;
            border: none !important;
            border-top: 4px solid rgb(225, 161, 25) !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
            margin-top: 0 !important;
            padding: 0 !important;
        }

        .dropdown-menu > li > a {
color: rgb(225, 161, 25) !important; /* Dropdown text also gold */            padding: 12px 20px !important;
            font-weight: 500 !important;
            border-bottom: 1px solid #f1f1f1 !important;
            transition: all 0.2s ease !important;
        }

        /* Dropdown Hover State */
        .dropdown-menu > li > a:hover {
            background-color: rgb(225, 161, 25) !important;
            color: #ffffff !important; /* Text turns white on gold background */
            padding-left: 25px !important; /* Smooth slide effect */
        }

        /* Fix for the little arrow (caret) */
        .caret {
            border-top-color: #333 !important;
        }
        .navbar-nav > li > a:hover .caret {
            border-top-color: rgb(225, 161, 25) !important;
        }

        /* ===== CAROUSEL ===== */
        .carousel {
            margin: 40px auto;
            border: 6px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }
        .carousel-inner img { height: 450px !important; object-fit: cover; width: 100%; }
        .carousel-control { background-image: none !important; width: 8%; display: flex; align-items: center; justify-content: center; opacity: 0.8; text-shadow: none; }
        .carousel-control:hover { opacity: 1; }
        .carousel-control .fa {
            background: #fff;
            color: rgb(225, 161, 25);
            width: 50px; height: 50px; line-height: 50px;
            border-radius: 50%; font-size: 20px; text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        /* ===== CONTENT CARD ===== */
        .content {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            margin-bottom: 60px;
            text-align: center;
        }
        .content h3 { color: rgb(225, 161, 25); font-weight: 700; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }

        /* ===== FOOTER ===== */
        .home-footer {
            background: #111; color: #eee; padding: 50px 0 20px;
            border-top: 5px solid rgb(225, 161, 25);
        }
        .home-footer-box h4 { color: rgb(225, 161, 25); font-weight: 700; margin-bottom: 20px; font-size: 16px; }
        .home-footer-bottom { margin-top: 30px; padding-top: 20px; border-top: 1px solid #333; text-align: center; color: rgb(225, 161, 25); font-size: 12px; }


    /* ===== 1. GLOBAL THEME ===== */
    html {
        background: linear-gradient(135deg, rgb(225, 161, 25), #8c6411) fixed !important;
    }
    body {
        background: transparent !important;
        font-family: 'Poppins', sans-serif !important;
        margin: 0;
    }

    /* ===== 2. NAVBAR CONTAINER ===== */
    .navbar, .navbar-inverse, .navbar-default, .menu-section {
        background-color: #ffffff !important;
        background-image: none !important;
        border-bottom: none !important; /* Clean look: no bottom line */
        /* Top-weighted shadow for depth */
        box-shadow: 0 -5px 15px rgba(0,0,0,0.1), 0 2px 10px rgba(0,0,0,0.05) !important;
        min-height: 80px !important;
        display: flex !important;
        align-items: center !important;
        margin-bottom: 0 !important;
    }

    /* Center navigation links vertically */
    .navbar-nav, #menu-top {
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        height: 100% !important;
    }

    /* ===== 3. NAVIGATION BUTTONS (BASE STATE) ===== */
    #menu-top li a, 
    .navbar-nav li a,
    .navbar-brand {
        color: rgb(225, 161, 25) !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        font-size: 14px !important;
        padding: 22px 25px !important; 
        margin: 0px !important; 
        background: transparent !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        border-radius: 12px !important; /* The rounded box shape */
        display: inline-block !important;
        text-decoration: none !important;
        opacity: 1 !important;
        visibility: visible !important;
    }

    /* ===== 4. THE HOVER BOX (ACTIVE & HOVER STATE) ===== */
    #menu-top li a:hover, 
    .navbar-nav li a:hover,
    .navbar-nav li.active > a,
    .navbar-nav li.open > a,
    .menu-top-active {
        background-color: #8c6411 !important; /* Darker Bronze */
        color: #ffffff !important; /* White text on hover */
        border-radius: 12px !important; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
        transform: translateY(-1px);
    }

    /* ===== 5. DROPDOWN STYLING ===== */
    .dropdown-menu {
        background-color: #ffffff !important;
    border: none !important;
    border-top: 4px solid #8c6411 !important;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
    border-radius: 0 0 10px 10px !important;
    margin-top: 0px !important; 
    padding: 0 !important;   /* This removes the top/bottom white space */
    min-width: 180px !important;
    overflow: hidden;
    }

    .dropdown-menu li {
    margin: 0 !important;   /* CRITICAL: Remove any margin between items */
}

    .dropdown-menu > li > a {
    color: #8c6411 !important;
    padding: 14px 20px !important; 
    display: block !important;  /* Forces the link to take up the full width */
    width: 100% !important;     /* Extra insurance for full width */
    margin: 0 !important;
    border-radius: 0 !important; /* Removes inner rounding that causes gaps */
    border-bottom: 1px solid #f1f1f1 !important;
    transition: all 0.3s ease !important;
    text-align: left !important;
}
    .dropdown-menu > li > a:hover {
    background-color: rgb(225, 161, 25) !important;
    color: #ffffff !important;
    padding-left: 28px !important; /* Elegant slide effect */
}
.dropdown-menu > li {
    padding: 0 !important;   /* Ensures no internal padding in the list item */
    margin: 0 !important;
}
/* Remove the border from the last item so it looks clean */
.dropdown-menu > li:last-child > a {
    border-bottom: none !important;
}
    /* ===== 6. UI ELEMENTS (CARET/ICONS) ===== */
    .caret {
        border-top-color: rgb(225, 161, 25) !important;
        margin-left: 5px !important;
    }

    /* Make caret white when the parent button is hovered/darkened */
    #menu-top li a:hover .caret, 
    .navbar-nav li.open > a .caret {
        border-top-color: #ffffff !important;
    }

    /* ===== FOOTER ===== */
.home-footer {
    background: #111; 
    color: #eee; 
    padding: 50px 0 20px;
    border-top: 5px solid rgb(225, 161, 25);
    text-align: center; /* This centers all text within footer boxes */
}

.home-footer-box {
    margin-bottom: 20px;
}

.home-footer-box h4 { 
    color: rgb(225, 161, 25); 
    font-weight: 700; 
    margin-bottom: 20px; 
    font-size: 16px; 
}

.home-footer-bottom { 
    margin-top: 30px; 
    padding-top: 20px; 
    border-top: 1px solid #333; 
    text-align: center; 
    color: rgb(225, 161, 25); 
    font-size: 13px; 
}

/* 2. LABELS (Professional Dark Charcoal) */
label {
    color: #444 !important; 
    font-weight: 600 !important;
    font-size: 13px;
    text-transform: uppercase; /* Adds a modern dashboard look */
    margin-bottom: 8px;
    display: block;
}

/* 3. REGISTRATION BUTTON (Signature Gold Gradient) */
.btn-signup {
    width: 100%;
    border-radius: 30px;
    background: linear-gradient(135deg, rgb(225, 161, 25), #8c6411) !important;
    color: #ffffff !important;
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

/* 4. INTERACTIVE ELEMENTS (Eye Icons & Bottom Link) */
.toggle-password {
    color: #8c6411 !important; /* Bronze color for the eye icon */
    cursor: pointer;
}

.login-link-area {
    text-align: center;
    margin-top: 20px;
    color: #666; /* Subtle grey for "Already have an account?" */
}

.login-link-area a {
    color: #8c6411 !important; /* Signature Deep Bronze for the link */
    font-weight: 700;
    text-decoration: none;
}

.login-link-area a:hover {
    color: rgb(225, 161, 25) !important;
    text-decoration: underline;
}
</style>

</head>
<body>
    <?php include('includes/header.php');?>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div id="main-slider" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="item active"><img src="assets/img/1.jpg" /></div>
                        <div class="item"><img src="assets/img/2.jpg" /></div>
                    </div>
                    <a class="left carousel-control" href="#main-slider" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
                    <a class="right carousel-control" href="#main-slider" data-slide="next"><i class="fa fa-chevron-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="content">
                    <h3>Explore, Learn, and Grow</h3>
                    <p>Welcome to the next generation of library management. Our system is designed to provide students and faculty with seamless access to a vast array of physical and digital collections. Whether you are conducting research, preparing for exams, or simply seeking your next great read, our modern interface makes it easier than ever to manage your books and discover new knowledge.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="home-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 home-footer-box">
                    <h4>ABOUT LIBRARY</h4>
                    <p>A comprehensive platform dedicated to enhancing the educational experience through streamlined resource management.</p>
                </div>
                <div class="col-md-4 home-footer-box">
                    <h4>VISIT US</h4>
                    <p>Flat NO.2,1st floor,Ultra C.H.S Ltd,Hari Shankar Road,next to Madhuram Hall,Krishna Colony,Dahisar(E),Mumbai-400068</p>
                </div>
                <div class="col-md-4 home-footer-box">
                    <h4>SUPPORT</h4>
                    <p>Help Desk: +91 98765 43210<br>bookmate.starlibrary@gmail.com</p>
                </div>
            </div>
            <div class="home-footer-bottom">
                &copy; <?php echo date("Y"); ?> Online Library Management System | Built with care
            </div>
        </div>
    </footer>

    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
</body>
</html>