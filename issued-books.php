<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0) { 
    header('location:index.php'); 
    exit; 
} else { ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>OLMS | Issued Books History</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Poppins:300,400,600,700' rel='stylesheet' type='text/css' />

    <style>
        /* ===== STICKY FOOTER LAYOUT ===== */
        html { 
            height: 100%;
            background: linear-gradient(135deg, rgb(225, 161, 25), #8c6411) fixed !important; 
            background-size: cover !important;
        }
        body { 
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Full viewport height */
            background: transparent !important; 
            font-family: 'Poppins', sans-serif !important; 
            margin: 0; 
        }

        /* ===== 1. LOGO SECTION ===== */
        .logo-section {
            padding: 15px 0;
            background-color: #fff;
            text-align: left;
        }
        .logo-section img { max-height: 60px; }

        /* ===== 2. NAVBAR ===== */
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
            padding: 0 !important;
            overflow: hidden;
            border: none !important;
        }
        .dropdown-menu > li > a {
            color: rgb(225, 161, 25) !important;
            padding: 15px 20px !important;
            font-weight: 600 !important;
        }

        /* ===== 3. CONTENT AREA (Flex Grow pushes footer down) ===== */
        .content-wrapper { 
            padding: 40px 0; 
            flex: 1 0 auto; /* This pushes the footer to the bottom */
        }
        
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

        /* ===== 4. TABLE CONTAINER CARD ===== */
        .table-container-card {
            background: rgba(255, 255, 255, 0.98);
            padding: 30px;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .main-table thead th {
            color: #8c6411;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 700;
            border-bottom: 2px solid #eee !important;
        }

        .book-name-cell { font-size: 15px; font-weight: 700; color: #8c6411; }
        .isbn-code { background: #f8f9fa; border: 1px solid #ddd; padding: 2px 6px; border-radius: 4px; font-family: monospace; color: #333; }

        .label-returned { background-color: #27ae60 !important; padding: 6px 12px; border-radius: 50px; font-size: 10px; font-weight: 700; }
        .label-not-returned { background-color: #e74c3c !important; padding: 6px 12px; border-radius: 50px; font-size: 10px; font-weight: 700; }
        .fine-text { font-weight: 700; color: #e74c3c; font-size: 14px; }

        /* Golden Glow Focus */
        .form-control:focus {
            border-color: rgb(225, 161, 25) !important;
            outline: 0 !important;
            box-shadow: 0 0 8px rgba(225, 161, 25, 0.5) !important;
        }

        /* ===== 5. FOOTER (FIXED STYLE) ===== */
        .home-footer {
            flex-shrink: 0; /* Prevents footer from shrinking */
            background: #111 !important; 
            color: #eee !important; 
            padding: 30px 0; 
            border-top: 5px solid rgb(225, 161, 25) !important;
            text-align: center;
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
                <li><a href="issued-books.php" class="menu-top-active">My History</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account <i class="fa fa-angle-down"></i></a>
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
                <h4 class="header-line">📜 Your <strong>Issued Books History</strong></h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-container-card">
                    <div class="table-responsive">
                        <table class="table main-table" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th>Book Information</th>
                                    <th>ISBN</th>
                                    <th>Issued Date</th>
                                    <th>Return Status</th>
                                    <th class="text-center">Fine (INR)</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $sid=$_SESSION['stdid'];
                            $sql="SELECT tblbooks.BookName,tblbooks.ISBNNumber,tblissuedbookdetails.IssuesDate,tblissuedbookdetails.ReturnDate,tblissuedbookdetails.id as rid,tblissuedbookdetails.fine from tblissuedbookdetails join tblstudents on tblstudents.StudentId=tblissuedbookdetails.StudentId join tblbooks on tblbooks.id=tblissuedbookdetails.BookId where tblstudents.StudentId=:sid order by tblissuedbookdetails.id desc";
                            $query = $dbh -> prepare($sql);
                            $query-> bindParam(':sid', $sid, PDO::PARAM_STR);
                            $query->execute();
                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                            $cnt=1;
                            if($query->rowCount() > 0) {
                                foreach($results as $result) { ?>                                     
                                    <tr>
                                        <td><?php echo $cnt++;?></td>
                                        <td><span class="book-name-cell"><?php echo htmlentities($result->BookName);?></span></td>
                                        <td><span class="isbn-code"><?php echo htmlentities($result->ISBNNumber);?></span></td>
                                        <td style="color: #666; font-weight: 500;"><?php echo htmlentities($result->IssuesDate);?></td>
                                        <td>
                                            <?php if($result->ReturnDate=="") { ?>
                                                <span class="label label-not-returned"><i class="fa fa-clock-o"></i> NOT RETURNED YET</span>
                                            <?php } else { ?>
                                                <span class="label label-returned"><?php echo htmlentities($result->ReturnDate); ?></span>
                                            <?php } ?>
                                        </td>
                                        <td class="fine-text text-center">
                                            <?php echo ($result->fine == "" || $result->fine == "0") ? '<span style="color:#bbb;">—</span>' : "₹".htmlentities($result->fine);?>
                                        </td>
                                    </tr>
                            <?php }} ?>                                     
                            </tbody>
                        </table>
                    </div>
                </div>
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
<script src="assets/js/dataTables/jquery.dataTables.js"></script>
<script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
<script> 
    $(document).ready(function () { 
        $('#dataTables-example').dataTable({ 
            "order": [[ 0, "desc" ]],
            "pageLength": 10
        }); 
    }); 
</script>

</body>
</html>
<?php } ?>