<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 
    // Logic for blocking/activating from this page if needed
    if(isset($_GET['inid'])) {
        $id=$_GET['inid'];
        $status=0;
        $sql = "update tblstudents set Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id',$id, PDO::PARAM_STR);
        $query->bindParam(':status',$status, PDO::PARAM_STR);
        $query->execute();
        header('location:reg-students.php');
    }

    if(isset($_GET['id'])) {
        $id=$_GET['id'];
        $status=1;
        $sql = "update tblstudents set Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id',$id, PDO::PARAM_STR);
        $query->bindParam(':status',$status, PDO::PARAM_STR);
        $query->execute();
        header('location:reg-students.php');
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Student History</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <style>
        /* Global Background */
        html { background: linear-gradient(135deg, #1e3c72, #2a5298); min-height: 100%; }
        body { background: transparent; font-family: 'Open Sans', sans-serif; }
        
        /* Layout Fixes */
        .content-wrapper { margin-top: 40px; }
        .header-line { color: #fff; font-weight: 700; text-transform: uppercase; border-bottom: 2px solid rgba(255,255,255,0.2); padding-bottom: 10px; }
        
        .panel {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: none;
            width: 100% !important; /* Full width fix */
        }
        
        .panel-heading {
            background: #f8f9fa !important;
            color: #1e3c72 !important;
            font-weight: 700;
            border-bottom: 1px solid #ddd;
        }

        /* Table Text Fixes */
        th, td { white-space: nowrap; text-align: center; vertical-align: middle !important; }

        /* ADDED FOR BACK BUTTON VISIBILITY */
        .back-btn-container { margin-bottom: 15px; }
        .btn-back {
            background: transparent;
            color: #fff;
            border: 2px solid #fff;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 13px;
            transition: all 0.3s ease;
            text-decoration: none !important;
            display: inline-block;
        }
        .btn-back:hover {
            background: #fff;
            color: #1e3c72;
            transform: translateX(-5px);
        }
            /* ===== 1. CORE RESET & GAP PREVENTER ===== */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            /* Using white as base so any tiny sub-pixel gaps match the header */
            background-color: #ffffff !important; 
        }
        body { 
            display: flex;
            flex-direction: column;
            font-family: 'Poppins', sans-serif;
        }

        /* ===== 2. FIXED HEADER (LOGO & NAV) ===== */
        .navbar-inverse {
            background-color: #ffffff !important;
            border: none !important;
            margin-bottom: 0 !important;
            padding: 0 !important;
            min-height: auto !important;
        }

        .navbar-header {
            padding: 15px 0 !important;
        }

        .logo-img {
            height: 60px;
            width: auto;
        }

        /* This forces the menu to touch the logo area with zero gap */
   /* ===== 2. FIXED HEADER (LOGO & NAV) ===== */
        .navbar-inverse {
            background-color: #ffffff !important;
            border: none !important;
            margin-bottom: 0 !important;
            padding: 0 !important;
            min-height: auto !important;
        }

        .navbar-header {
            padding: 15px 0 !important;
        }

        .logo-img {
            height: 60px;
            width: auto;
        }

        /* This forces the menu to touch the logo area with zero gap */
      .menu-section {
            background-color: #ffffff !important;
            margin-top: 0 !important; 
            border-bottom: 5px solid rgb(225, 161, 25) !important;
            width: 100%;
            position: relative;
            z-index: 99;
            box-shadow: none !important; /* Explicitly removed shadow */
        }


        /* Navigation Buttons - Matching your Image 1 & 2 */
        #menu-top li a {
            color: rgb(225, 161, 25) !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 13px !important;
            padding: 12px 20px !important;
            margin: 10px 5px !important;
            border-radius: 10px !important;
            transition: all 0.3s ease;
            background: transparent !important;
        }

        /* Hover & Active States (The Golden Box) */
        #menu-top li a:hover, 
        #menu-top li a.menu-top-active {
            background-color: #8c6411 !important; 
            color: #ffffff !important; 
            /* box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important; */
            transform: translateY(-2px);
        }

        /* Dropdown Customization */
        .dropdown-menu {
            border: none !important;
            border-top: 4px solid rgb(225, 161, 25) !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
            border-radius: 0 0 8px 8px !important;
            padding: 0 !important;
        }
        .dropdown-menu > li > a {
            padding: 12px 20px !important;
            font-weight: 600 !important;
        }

        /* ===== 3. GOLDEN CONTENT AREA ===== */
        .content-wrapper { 
            flex: 1 0 auto; 
            /* Gradient matches the background of your dashboard screenshot */
            background: linear-gradient(135deg, rgb(225, 161, 25) 0%, #8c6411 100%) !important; 
            margin-top: 0 !important; 
            padding: 60px 0;
        }
        
        .header-line { 
            color: #ffffff !important; 
            font-weight: 800; 
            font-size: 28px;
            text-transform: uppercase;
            border-bottom: 2px solid rgba(255,255,255,0.3) !important; 
            padding-bottom: 10px;
            margin-bottom: 45px;
        }

        /* Navigation Buttons - Matching your Image 1 & 2 */
        #menu-top li a {
            color: rgb(225, 161, 25) !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 13px !important;
            padding: 12px 20px !important;
            margin: 10px 5px !important;
            border-radius: 10px !important;
            transition: all 0.3s ease;
            background: transparent !important;
        }

        /* Hover & Active States (The Golden Box) */
        #menu-top li a:hover, 
        #menu-top li a.menu-top-active {
            background-color: #8c6411 !important; 
            color: #ffffff !important; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
            transform: translateY(-2px);
        }

        /* Dropdown Customization */
        .dropdown-menu {
            border: none !important;
            border-top: 4px solid rgb(225, 161, 25) !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
            border-radius: 0 0 8px 8px !important;
            padding: 0 !important;
        }
        .dropdown-menu > li > a {
            padding: 12px 20px !important;
            font-weight: 600 !important;
        }

        /* ===== 3. GOLDEN CONTENT AREA ===== */
        .content-wrapper { 
            flex: 1 0 auto; 
            /* Gradient matches the background of your dashboard screenshot */
            background: linear-gradient(135deg, rgb(225, 161, 25) 0%, #8c6411 100%) !important; 
            margin-top: 0 !important; 
            padding: 60px 0;
        }
        
        .header-line { 
            color: #ffffff !important; 
            font-weight: 800; 
            font-size: 28px;
            text-transform: uppercase;
            border-bottom: 2px solid rgba(255,255,255,0.3) !important; 
            padding-bottom: 10px;
            margin-bottom: 45px;
        }

        /* ===== 4. ENHANCED DASHBOARD CARDS ===== */
        .back-widget-set {
            background-color: #ffffff !important;
            border-radius: 20px !important; 
            padding: 40px 20px !important;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
        }

        .back-widget-set:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3) !important;
        }

        /* Icon Colors */
        .icon-books { color: #2ecc71 !important; }    
        .icon-issued { color: #3498db !important; }   
        .icon-returned { color: #f1c40f !important; } 
        .icon-users { color: #e74c3c !important; }    

        .back-widget-set h3 { font-weight: 800; font-size: 36px; margin: 15px 0 5px 0; color: #333; }
        .back-widget-set p { font-weight: 700; color: #777; text-transform: uppercase; font-size: 12px; }

        /* ===== 5. BLACK STICKY FOOTER ===== */
        .footer-section {
            flex-shrink: 0;
            background: #111 !important; 
            color: #ffffff !important; 
            padding: 25px 0; 
            border-top: 5px solid rgb(225, 161, 25) !important;
            text-align: center;
        }
        .footer-section a { color: rgb(225, 161, 25) !important; font-weight: 600; }
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


    </style>
</head>
<body>
    <?php include('includes/header.php');?>

    <div class="content-wrapper">
        <div class="container">
            
            <div class="row back-btn-container">
                <div class="col-md-12">
                    <a href="reg-students.php" class="btn-back">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <div class="row pad-botm">
                <div class="col-md-12">
                    <?php $sid=$_GET['stdid']; ?>
                    <h4 class="header-line">#<?php echo htmlentities($sid);?> Book Issued History</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Details for Student: <?php echo htmlentities($sid);?></div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Issued Book</th>
                                            <th>Issued Date</th>
                                            <th>Returned Date</th>
                                            <th>Fine</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT tblstudents.StudentId, tblstudents.FullName, tblbooks.BookName, tblissuedbookdetails.IssuesDate, tblissuedbookdetails.ReturnDate, tblissuedbookdetails.fine 
                                                FROM tblissuedbookdetails 
                                                JOIN tblstudents ON tblstudents.StudentId=tblissuedbookdetails.StudentId 
                                                JOIN tblbooks ON tblbooks.id=tblissuedbookdetails.BookId 
                                                WHERE tblstudents.StudentId=:sid";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                                        $query->execute();
                                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt=1;
                                        if($query->rowCount() > 0) {
                                            foreach($results as $result) { ?>                                          
                                                <tr class="odd gradeX">
                                                    <td><?php echo htmlentities($cnt);?></td>
                                                    <td><?php echo htmlentities($result->StudentId);?></td>
                                                    <td><?php echo htmlentities($result->FullName);?></td>
                                                    <td><?php echo htmlentities($result->BookName);?></td>
                                                    <td><?php echo htmlentities($result->IssuesDate);?></td>
                                                    <td>
                                                        <?php if($result->ReturnDate=="") {
                                                            echo '<span class="label label-warning">Not returned yet</span>';
                                                        } else {
                                                            echo htmlentities($result->ReturnDate);
                                                        } ?>
                                                    </td>
                                                    <td>
                                                        <?php if($result->ReturnDate=="") {
                                                            echo "N/A";
                                                        } else {
                                                            echo "₹ " . htmlentities($result->fine);
                                                        } ?>
                                                    </td>
                                                </tr>
                                        <?php $cnt++; } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php');?>

    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable({
                "language": {
                    "info": "Showing _TOTAL_ entries",
                    "infoEmpty": "Showing 0 entries",
                    "infoFiltered": ""
                }
            });
        });
    </script>
</body>
</html>
<?php } ?>