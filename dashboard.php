<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0) {   
    header('location:index.php');
} else { 
    $sid = $_SESSION['stdid']; 
    
    // Fetch total books
    $sql1 = "SELECT id from tblbooks";
    $query1 = $dbh->prepare($sql1);
    $query1->execute();
    $listdbooks = $query1->rowCount();

    // Fetch books issued to this student
    $sql2 = "SELECT id from tblissuedbookdetails where StudentID=:sid";
    $query2 = $dbh->prepare($sql2);
    $query2->bindParam(':sid', $sid, PDO::PARAM_STR);
    $query2->execute();
    $issuedbooks = $query2->rowCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>OLMS | Student Dashboard</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Poppins:300,400,600,700' rel='stylesheet' type='text/css' />
    
    <style>
        /* ===== GLOBAL THEME & BACKGROUND ===== */
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

        /* ===== 2. NAVBAR CONTAINER (INDEX STYLE) ===== */
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

        /* ===== 3. NAVIGATION BUTTONS (ROUNDED BOX STYLE) ===== */
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
            /* display: inline-block !important; */
        }

        /* Hover & Active State: Dark Gold Box with White Text */
        #menu-top li a:hover, .menu-top-active {
            background-color: #8c6411 !important; 
            color: #ffffff !important; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
            transform: translateY(-2px) !important;
        }

        /* Dropdown Styling */
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
            border-bottom: 1px solid #f1f1f1 !important;
            border-radius: 0 !important;
        }
        .dropdown-menu > li > a:hover {
            background-color: rgb(225, 161, 25) !important;
            color: #ffffff !important;
        }

        /* ===== 4. CONTENT WRAPPER & DASHBOARD CARDS ===== */
        .content-wrapper { 
            padding-top: 40px !important; 
            padding-bottom: 60px; 
        }
/* Update this section in your <style> block */
.header-line { 
    color: #fff !important; 
    font-weight: 300; /* Change this to 300 (Light) */
    letter-spacing: 2px; 
    text-transform: uppercase; 
    margin-bottom: 40px; 
    text-align: center;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* Add this to make the strong tag actually work */
.header-line strong {
    font-weight: 800 !important; /* Extra Bold */
    border-bottom: 2px solid rgba(255,255,255,0.5); /* Optional: adds a nice underline */
}

        .dash-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            text-align: center;
            margin-bottom: 30px;
            display: block;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            text-decoration: none !important;
            transition: all 0.4s ease;
            padding: 35px 20px;
        }
        .dash-card:hover { transform: translateY(-8px); background: #fff; }
        .dash-card h3 { font-size: 34px; font-weight: 700; color: #333; margin: 0; }
        .dash-card p { color: #8c6411; font-weight: 600; text-transform: uppercase; font-size: 11px; margin-top: 10px; }

        .icon-featured-green { color: #27ae60; font-size: 45px; margin-bottom: 15px; }
        .icon-featured-yellow { color: #f1c40f; font-size: 45px; margin-bottom: 15px; }
        .icon-featured-blue { color: #3498db; font-size: 45px; margin-bottom: 15px; }

        /* Book Card Styles */
        .book-card {
            position: relative; background: #fff; border-radius: 15px; overflow: hidden; 
            margin-bottom: 30px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: 0.3s;
        }
        .badge-floating {
            position: absolute; top: 12px; right: 12px; z-index: 10;
            padding: 5px 12px; border-radius: 50px; font-size: 10px;
            font-weight: 700; text-transform: uppercase; color: #fff;
        }
        .label-new { background: #27ae60; }
        .label-pop { background: #3498db; }
        .book-image-box { height: 200px; display: flex; align-items: center; justify-content: center; padding: 15px; }
        .book-image-box img { max-height: 100%; transition: 0.5s; }
        .book-info { padding: 15px; text-align: center; }
        .btn-primary { 
            background: #8c6411 !important; 
            border: none !important; border-radius: 8px; font-weight: 600; padding: 10px;
        }

        /* ===== 5. FOOTER ===== */
        .home-footer {
            background: #111 !important; color: #eee !important; 
            padding: 30px 0; border-top: 5px solid rgb(225, 161, 25) !important;
            text-align: center;
        }

        /* ===== ELITE INTERACTIVE CARD ANIMATIONS ===== */

/* 1. Dashboard Stats Cards */
.dash-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 24px;
    text-align: center;
    margin-bottom: 30px;
    display: block;
    box-shadow: 0 15px 35px rgba(140, 100, 17, 0.1); /* Subtle gold shadow */
    text-decoration: none !important;
    padding: 40px 20px;
    position: relative;
    overflow: hidden;
    z-index: 1;
    /* Smooth, spring-like transition */
    /* transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1); */
    border: 1px solid rgba(255, 255, 255, 0.8);
}

.dash-card:hover { 
    transform: translateY(-15px) scale(1.03); 
    background: #ffffff; 
    box-shadow: 0 30px 60px rgba(0,0,0,0.25);
}

/* 2. Featured Book Cards (3D Tilt Effect) */
.book-card {
    position: relative; 
    background: #ffffff; 
    border-radius: 20px; 
    overflow: hidden; 
    margin-bottom: 30px; 
    box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
    transition: all 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    perspective: 1000px; /* Prepares for 3D tilt */
}

.book-card:hover {
    transform: translateY(-12px) rotateX(4deg) rotateY(-2deg);
    box-shadow: 0 40px 70px rgba(0,0,0,0.3);
}

/* 3. Image "Pop-Out" Animation */
.book-image-box {
    overflow: hidden;
    position: relative;
}

.book-image-box img { 
    max-height: 100%; 
    transition: transform 0.8s cubic-bezier(0.2, 1, 0.3, 1); 
}

.book-card:hover .book-image-box img {
    transform: scale(1.15) translateZ(20px); /* Brings image "forward" */
}

/* 4. Professional Glass Shimmer (Light Streak) */
.dash-card::before, .book-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: -150%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        120deg,
        transparent,
        rgba(255, 255, 255, 0.4),
        transparent
    );
    transition: all 0.8s ease;
    z-index: 2;
}

.dash-card:hover::before, .book-card:hover::before {
    left: 150%;
}

/* 5. Icon Pulse on Stats */
.dash-card:hover i {
    animation: pulseIcon 1.5s infinite ease-in-out;
}

@keyframes pulseIcon {
    0% { transform: scale(1); }
    50% { transform: scale(1.15); text-shadow: 0 0 15px rgba(140, 100, 17, 0.4); }
    100% { transform: scale(1); }
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
                <li><a href="dashboard.php" class="menu-top-active">Dashboard</a></li>
                <li><a href="listed-books.php">explore books</a></li>
                <li><a href="issued-books.php">My History</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> my Account <i class="fa fa-angle-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="my-profile.php">Profile</a></li>
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
<h4 class="header-line">✨ welcome to <strong>student dashboard</strong></h4>            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <a href="listed-books.php" class="dash-card">
                    <i class="fa fa-book icon-featured-green"></i>
                    <h3><?php echo htmlentities($listdbooks);?></h3>
                    <p>Books Available</p>
                </a>
            </div>
            <div class="col-md-4 col-sm-6">
                <a href="#featured" class="dash-card">
                    <i class="fa fa-star icon-featured-yellow"></i>
                    <h3>Featured</h3>
                    <p>Handpicked Books</p>
                </a>
            </div>
            <div class="col-md-4 col-sm-6">
                <a href="issued-books.php" class="dash-card">
                    <i class="fa fa-history icon-featured-blue"></i>
                    <h3><?php echo htmlentities($issuedbooks);?></h3>
                    <p>Books Issued</p>
                </a>
            </div>
        </div>

        <div class="row" id="featured" style="margin-top:40px;">
            <div class="col-md-12">
<h4 class="header-line">✨ <strong style="font-weight: 900 !important;">Featured Collection </strong></h4>            </div>
            
            <?php
            $sqlFeatured = "(SELECT id, BookName, bookImage, 'New' as label FROM tblbooks ORDER BY id DESC LIMIT 3)
                            UNION
                            (SELECT b.id, b.BookName, b.bookImage, 'Popular' as label 
                             FROM tblbooks b 
                             LEFT JOIN tblissuedbookdetails i ON i.BookId = b.id 
                             GROUP BY b.id ORDER BY COUNT(i.id) DESC LIMIT 3)";
            
            $queryFeatured = $dbh->prepare($sqlFeatured);
            $queryFeatured->execute();
            $featuredBooks = $queryFeatured->fetchAll(PDO::FETCH_OBJ);

            foreach($featuredBooks as $fBook) { ?>
                <div class="col-md-4 col-sm-6">
                    <div class="book-card">
                        <span class="badge-floating <?php echo ($fBook->label == 'New') ? 'label-new' : 'label-pop'; ?>">
                            <?php echo $fBook->label; ?>
                        </span>
                        <div class="book-image-box">
                            <img src="admin/bookimg/<?php echo htmlentities($fBook->bookImage); ?>" alt="Book">
                        </div>
                        <div class="book-info">
                            <h5><?php echo htmlentities($fBook->BookName); ?></h5>
                            <a href="preview.php?bookid=<?php echo htmlentities($fBook->id); ?>" class="btn btn-primary btn-block">
                            VEIW   PREVIEW
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
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