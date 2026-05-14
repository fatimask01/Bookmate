<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
    exit();
} else { 

    // Code for blocking student     
    if(isset($_GET['inid'])) {
        $id = intval($_GET['inid']);
        $status = 0;
        $sql = "UPDATE tblstudents SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->bindParam(':status', $status, PDO::PARAM_INT);
        $query->execute();
        header('location:reg-students.php');
        exit();
    }

    // Code for activating student
    if(isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $status = 1;
        $sql = "UPDATE tblstudents SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->bindParam(':status', $status, PDO::PARAM_INT);
        $query->execute();
        header('location:reg-students.php');
        exit();
    }
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>OLMS | Manage Registered Students</title>

    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        /* ===== 1. CORE LAYOUT & VARIABLES ===== */
        :root {
            --gold-primary: rgb(225, 161, 25);
            --gold-dark: #8c6411;
            --panel-shadow: 0 15px 45px rgba(0,0,0,0.3);
        }

        html, body { height: 100%; margin: 0; padding: 0; background-color: #ffffff !important; }
        body { display: flex; flex-direction: column; font-family: 'Poppins', sans-serif; }

        /* ===== 2. NAVIGATION (ADMIN DASHBOARD SPEC) ===== */
        .navbar-inverse {
            background-color: #ffffff !important;
            border: none !important;
            margin-bottom: 0 !important;
            padding: 10px 0 !important; 
            min-height: 80px !important; 
            display: flex;
            align-items: center;
        }

        .logo-img {
            height: 60px;
            width: auto;
            display: block;
            object-fit: contain;
        }

        .menu-section {
            background-color: #ffffff !important;
            margin-top: 0 !important; 
            border-bottom: 5px solid var(--gold-primary) !important;
            width: 100%;
            position: relative;
            z-index: 99;
            /* Applying requested floating shadow */
            /* box-shadow: var(--panel-shadow) !important; */
        }

        #menu-top li a {
            color: var(--gold-primary) !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 13px !important;
            padding: 15px 20px !important;
            margin: 5px !important;
            border-radius: 10px !important;
            transition: all 0.3s ease;
            display: block;
        }

        #menu-top li a:hover, #menu-top li a.menu-top-active {
            background-color: var(--gold-dark) !important; 
            color: #ffffff !important; 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Dropdown Customization */
        .dropdown-menu {
            border: none !important;
            border-top: 4px solid var(--gold-primary) !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
            border-radius: 0 0 8px 8px !important;
            padding: 0 !important;
        }
        .dropdown-menu > li > a { padding: 12px 20px !important; font-weight: 600 !important; }

        /* ===== 3. GOLDEN CONTENT AREA ===== */
        .content-wrapper { 
            flex: 1 0 auto; 
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%) !important; 
            padding: 60px 0;
        }
        
        .container { width: 95% !important; max-width: none !important; }

        .header-line { 
            color: #ffffff !important; 
            font-weight: 800; 
            font-size: 28px;
            text-transform: uppercase;
            text-align: center;
            border-bottom: 2px solid rgba(255,255,255,0.3) !important; 
            padding-bottom: 10px;
            margin-bottom: 45px;
        }

        /* ===== 4. PANEL & TABLE STYLING ===== */
        .panel { 
            border-radius: 25px !important; 
            overflow: hidden; 
            box-shadow: var(--panel-shadow) !important; 
            border: none !important;
            background: #fff;
        }

        .panel-heading { 
            background: #ffffff !important; 
            color: var(--gold-dark) !important; 
            font-weight: 800; 
            font-size: 20px;
            padding: 20px !important;
            text-align: center;
            text-transform: uppercase;
            border-bottom: 1px solid #eee !important;
        }

        .table th { background: #f1f5f9; color: #333; text-align: center; }

        /* 3D Transform on Action Buttons */
        .btn-xs { 
            border-radius: 25px; 
            padding: 6px 18px; 
            font-weight: 700; 
            text-transform: uppercase; 
            transition: 0.3s;
            border: none;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        .btn-xs:hover { transform: translateY(-3px); box-shadow: 0 6px 12px rgba(0,0,0,0.2); filter: brightness(1.1); }

        /* ===== 5. FOOTER ===== */
        .footer-section {
            background: #111 !important; 
            color: #ffffff !important; 
            padding: 25px 0; 
            border-top: 5px solid var(--gold-primary) !important;
            text-align: center;
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
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Registered Students</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Student Listings</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Mobile Number</th>
                                            <th>Reg Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT * from tblstudents";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt=1;
                                        if($query->rowCount() > 0) {
                                            foreach($results as $result) { ?>
                                                <tr>
                                                    <td style="text-align:center;"><?php echo htmlentities($cnt);?></td>
                                                    <td><?php echo htmlentities($result->StudentId);?></td>
                                                    <td><?php echo htmlentities($result->FullName);?></td>
                                                    <td><?php echo htmlentities($result->MobileNumber);?></td>
                                                    <td style="text-align:center;"><?php echo htmlentities($result->RegDate);?></td>
                                                    <td style="text-align:center;">
                                                        <?php if($result->Status==1): ?>
                                                            <span class="label label-success" style="border-radius:10px;">Active</span>
                                                        <?php else: ?>
                                                            <span class="label label-danger" style="border-radius:10px;">Blocked</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td style="text-align:center;">
                                                        <?php if($result->Status==1) { ?>
                                                            <a href="reg-students.php?inid=<?php echo htmlentities($result->id);?>" onclick="return confirm('Block this student?');">
                                                                <button class="btn btn-danger btn-xs">Block</button>
                                                            </a>
                                                        <?php } else { ?>
                                                            <a href="reg-students.php?id=<?php echo htmlentities($result->id);?>" onclick="return confirm('Activate this student?');">
                                                                <button class="btn btn-primary btn-xs">Unblock</button>
                                                            </a>
                                                        <?php } ?>
                                                        <a href="student-history.php?stdid=<?php echo htmlentities($result->StudentId);?>">
                                                            <button class="btn btn-success btn-xs" style="background:#2ecc71;">History</button>
                                                        </a>
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
                "language": { "infoFiltered": "" }
            });
        });
    </script>
</body>
</html>
<?php } ?>