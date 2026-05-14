<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login'])==0) {   
    header('location:index.php');
} else { 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>OLMS | Books Catalog</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
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
        .logo-section img { max-height: 60px; }

        /* ===== 2. NAVBAR (FIXED TO MATCH DASHBOARD) ===== */
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
            border-bottom: 1px solid #f1f1f1 !important;
        }
        .dropdown-menu > li > a:hover {
            background-color: rgb(225, 161, 25) !important;
            color: #ffffff !important;
        }

        /* ===== 3. CONTENT AREA ===== */
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

        .book-title-cell { font-size: 15px; font-weight: 700; color: #8c6411; }
        .isbn-pill { background: #f8f9fa; border: 1px solid #eee; padding: 2px 8px; border-radius: 4px; font-family: monospace; }

        .btn-details {
            display: inline-block;
            padding: 8px 18px;
            background: #8c6411;
            color: #fff !important;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            transition: 0.3s;
            text-decoration: none !important;
        }
        .btn-details:hover {
            background: rgb(225, 161, 25);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(140, 100, 17, 0.3);
        }

        .status-badge { padding: 6px 12px; border-radius: 50px; font-size: 10px; font-weight: 700; }
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
    transition: all 0.3s ease-in-out; /* Smooth transition for the glow */
}

/* 1. Remove default blue outline and apply Golden Glow on Focus */
.form-control:focus {
    border-color: rgb(225, 161, 25) !important;
    outline: 0 !important;
    box-shadow: 0 0 8px rgba(225, 161, 25, 0.5) !important;
    background-color: #fff;
}


        /* ===== 5. FOOTER ===== */
        .home-footer {
            background: #111 !important; color: #eee !important; 
            padding: 30px 0; border-top: 5px solid rgb(225, 161, 25) !important;
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="logo-section">
    <div class="container">
        <a href="dashboard.php"><img src="assets/img/logo.png" alt="Library Logo"></a>
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
                <li><a href="listed-books.php" class="menu-top-active">Explore Books</a></li>
                <li><a href="issued-books.php">My History</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account <i class="fa fa-angle-down"></i></a>
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
                <h4 class="header-line">📖 Library <strong>Books Catalog</strong></h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-container-card">
                    <div class="table-responsive">
                        <table class="table main-table" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th width="80">Cover</th>
                                    <th>Book Information</th>
                                    <th>Author</th>
                                    <th>ISBN</th>
                                    <th>Status</th>
                                    <th width="140" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $sql = "SELECT tblbooks.id, tblbooks.BookName, tblauthors.AuthorName, tblbooks.ISBNNumber, 
                                           tblbooks.bookQty, tblbooks.bookImage,
                                           COUNT(tblissuedbookdetails.id) AS issuedBooks,
                                           SUM(CASE WHEN tblissuedbookdetails.RetrunStatus = 1 THEN 1 ELSE 0 END) AS returnedbook
                                    FROM tblbooks
                                    LEFT JOIN tblissuedbookdetails ON tblissuedbookdetails.BookId = tblbooks.id
                                    LEFT JOIN tblauthors ON tblauthors.id = tblbooks.AuthorId
                                    GROUP BY tblbooks.id
                                    ORDER BY tblbooks.BookName ASC";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                            foreach($results as $result) { ?>   
                                <tr>
                                    <td>
                                        <img src="admin/bookimg/<?php echo htmlentities($result->bookImage);?>" style="width:50px; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.15);">
                                    </td>
                                    <td>
                                        <span class="book-title-cell"><?php echo htmlentities($result->BookName);?></span>
                                    </td>
                                    <td style="color: #555; font-weight: 500;"><?php echo htmlentities($result->AuthorName);?></td>
                                    <td><span class="isbn-pill"><?php echo htmlentities($result->ISBNNumber);?></span></td>
                                    <td>
                                        <?php 
                                        $available = $result->bookQty - ($result->issuedBooks - $result->returnedbook);
                                        if($available > 0) {
                                            echo '<span class="label label-success status-badge">'.$available.' IN STOCK</span>';
                                        } else {
                                            echo '<span class="label label-danger status-badge">Out Of Stock </span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="preview.php?bookid=<?php echo htmlentities($result->id);?>" class="btn-details">
                                            <i class="fa fa-search"></i> VIEW
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
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
            "pageLength": 10,
            "order": [[ 1, "asc" ]] 
        });
    });
</script>

</body>
</html>
<?php } ?>