<?php
session_start();
error_reporting(0);
include('includes/config.php');

$is_logged_in = (strlen($_SESSION['alogin']) != 0);

if(!$is_logged_in) {
    header('location:index.php');
} else {
    $stats = [];
    $queries = [
        // Added comma after the overdue query to prevent PHP error
        'overdue' => "SELECT id from tblissuedbookdetails WHERE RetrunStatus=0 AND DATEDIFF(NOW(), IssuesDate) > 15",
        'books' => "SELECT id from tblbooks",
        'issued' => "SELECT id from tblissuedbookdetails",
        'returned' => "SELECT id from tblissuedbookdetails where RetrunStatus=1",
        'students' => "SELECT id from tblstudents",
        'authors' => "SELECT id from tblauthors",
        'categories' => "SELECT id from tblcategory"
    ];
    foreach($queries as $key => $sql) {
        $q = $dbh->prepare($sql);
        $q->execute();
        $stats[$key] = $q->rowCount();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>OLMS | Admin Portal</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { 
            --panel-shadow: 0 15px 45px rgba(0,0,0,0.3); 
            --gold-primary: rgb(225, 161, 25); 
            --gold-dark: #8c6411; 
            --gold-gradient: linear-gradient(to bottom, #f1c40f, #8c6411);
        }
        
        body { font-family: 'Poppins', sans-serif; background-color: #fff; }
        
        /* Nav bar with standard box shadow */
        .menu-section { 
            background-color: #fff !important; 
            border-bottom: 5px solid var(--gold-primary) !important; 
            /* box-shadow: var(--panel-shadow) !important; */
            z-index: 999; 
        }
        
        .content-wrapper { 
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%) !important; 
            min-height: 85vh; 
            padding: 40px 0; 
        }

        .header-line { font-weight: 800; color: #fff; border-bottom: 2px solid rgba(255,255,255,0.2); padding-bottom: 10px; text-transform: uppercase; text-align: center; margin-bottom: 30px;}

        /* --- DASHBOARD WIDGETS --- */
        .back-widget-set { 
            background-color: #fff !important; 
            border-radius: 25px; 
            box-shadow: var(--panel-shadow); 
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            padding: 30px 10px; 
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }

        .back-widget-set:hover { transform: translateY(-12px); }

        /* Icon Base Colors */
        .back-widget-set i {
            margin-bottom: 15px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        /* Initial Colors */
        .icon-green { color: #27ae60; }
        .icon-blue { color: #2980b9; }
        .icon-yellow { color: #f1c40f; }
        .icon-red { color: #e74c3c; }

        /* Hover Effect: Turn Golden */
        .back-widget-set:hover i {
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transform: scale(1.1);
        }

        .back-widget-set h3 { font-weight: 800; font-size: 32px; color: #333; margin: 5px 0; }
        .back-widget-set p { font-weight: 600; text-transform: uppercase; font-size: 12px; color: #777; letter-spacing: 1px; }

        .footer-section { background: #111 !important; color: #fff !important; border-top: 5px solid var(--gold-primary) !important; padding: 25px 0; text-align: center; }
        
        a:hover { text-decoration: none !important; }
    </style>
</head>
<body>
    <?php include('includes/header.php');?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12"><h4 class="header-line">ADMIN DASHBOARD</h4></div>
            </div>
            
            <div class="row">
                <a href="manage-books.php">
                    <div class="col-md-3 col-sm-6">
                        <div class="back-widget-set text-center">
                            <i class="fa fa-book fa-5x icon-green"></i>
                            <h3><?php echo $stats['books'];?></h3>
                            <p>Books Listed</p>
                        </div>
                    </div>
                </a>

                <a href="manage-issued-books.php">
                    <div class="col-md-3 col-sm-6">
                        <div class="back-widget-set text-center">
                            <i class="fa fa-send fa-5x icon-blue"></i>
                            <h3><?php echo $stats['issued'];?></h3>
                            <p>Times Issued</p>
                        </div>
                    </div>
                </a>

                <a href="manage-issued-books.php">
                    <div class="col-md-3 col-sm-6">
                        <div class="back-widget-set text-center">
                            <i class="fa fa-recycle fa-5x icon-yellow"></i>
                            <h3><?php echo $stats['returned'];?></h3>
                            <p>Books Returned</p>
                        </div>
                    </div>
                </a>

                <a href="reg-students.php">
                    <div class="col-md-3 col-sm-6">
                        <div class="back-widget-set text-center">
                            <i class="fa fa-users fa-5x icon-red"></i>
                            <h3><?php echo $stats['students'];?></h3>
                            <p>Registered Users</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="row">
                <a href="manage-authors.php">
                    <div class="col-md-3 col-sm-6">
                        <div class="back-widget-set text-center">
                            <i class="fa fa-user fa-5x icon-green"></i>
                            <h3><?php echo $stats['authors'];?></h3>
                            <p>Authors Listed</p>
                        </div>
                    </div>
                </a>

                <a href="manage-issued-books.php?filter=overdue">
                    <div class="col-md-3 col-sm-6">
                        <div class="back-widget-set text-center">
                            <i class="fa fa-exclamation-triangle fa-5x icon-red"></i>
                            <h3><?php echo $stats['overdue'];?></h3>
                            <p>Overdue Books</p>
                        </div>
                    </div>
                </a>

                <a href="manage-categories.php">
                    <div class="col-md-3 col-sm-6">
                        <div class="back-widget-set text-center">
                            <i class="fa fa-tags fa-5x icon-blue"></i>
                            <h3><?php echo $stats['categories'];?></h3>
                            <p>Categories</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php');?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
</body>
</html>