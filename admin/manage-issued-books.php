<?php
session_start();
error_reporting(E_ALL);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 

    // ===== 1. ONE-CLICK RETURN LOGIC =====
    if(isset($_GET['returnid'])) {
        $rid = intval($_GET['returnid']);
        $currDate = date('Y-m-d H:i:s');
        
        $sql = "UPDATE tblissuedbookdetails SET ReturnDate=:returndate, fine=0, RetrunStatus=1 WHERE id=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_INT);
        $query->bindParam(':returndate', $currDate, PDO::PARAM_STR);
        
        if($query->execute()) {
            $_SESSION['msg'] = "Book marked as Returned (Fine: ₹0)!";
        }
        header('location:manage-issued-books.php');
        exit();
    }

    // ===== 2. ONE-CLICK UNDO =====
    if(isset($_GET['undoid'])) {
        $rid = intval($_GET['undoid']);
        
        $sql = "UPDATE tblissuedbookdetails SET ReturnDate=NULL, fine=NULL, RetrunStatus=0 WHERE id=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_INT);
        
        if($query->execute()) {
            $_SESSION['msg'] = "Status reset. Fine cleared.";
        }
        header('location:manage-issued-books.php');
        exit();
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>OLMS | Manage Issued Books</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        /* === 1. CORE LAYOUT & VARIABLES === */
        :root {
            --gold-primary: rgb(225, 161, 25);
            --gold-dark: #8c6411;
            --panel-shadow: 0 15px 45px rgba(0,0,0,0.3);
        }
        html, body { height: 100%; margin: 0; background-color: #ffffff !important; }
        body { display: flex; flex-direction: column; font-family: 'Poppins', sans-serif; }

        /* === 2. NAVIGATION (ADMIN SPEC) === */
        .navbar-inverse { background-color: #ffffff !important; border: none !important; margin-bottom: 0 !important; padding: 10px 0 !important; min-height: 80px !important; display: flex; align-items: center; }
        .logo-img { height: 60px; width: auto; object-fit: contain; }

        .menu-section {
            background-color: #ffffff !important;
            border-bottom: 5px solid var(--gold-primary) !important;
            width: 100%;
            position: relative;
            
            z-index: 99;
            /* box-shadow: var(--panel-shadow) !important;  */
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
        }

        #menu-top li a:hover, #menu-top li a.menu-top-active {
            background-color: var(--gold-dark) !important; 
            color: #ffffff !important; 
            transform: translateY(-2px);
        }

        /* === 3. CONTENT AREA === */
        .content-wrapper { 
            flex: 1 0 auto; 
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%) !important; 
            padding: 60px 0;
        }

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

        /* === 4. PANELS & TABLES === */
        .panel-default { 
            border-radius: 25px !important; 
            box-shadow: var(--panel-shadow) !important; 
            border: none; 
            overflow: hidden; 
            background: #fff;
        }

        .panel-heading { background:#fff !important; color: var(--gold-dark) !important; font-weight:800; text-align:center; padding: 20px; text-transform: uppercase; }

        /* === 5. ACTION BUTTONS (3D STYLE) === */
        .btn-action { border-radius: 25px; font-weight: 700; text-transform: uppercase; font-size: 10px; padding: 8px 16px; transition: 0.3s; border: none; color: #fff !important; margin: 2px; display: inline-block; }
        .btn-return { background: linear-gradient(to bottom, #2ecc71, #27ae60); box-shadow: 0 4px #1e8449; }
        .btn-undo { background: linear-gradient(to bottom, #f39c12, #e67e22); box-shadow: 0 4px #a04000; }
        .btn-edit { background: linear-gradient(to bottom, #3498db, #2980b9); box-shadow: 0 4px #1a5276; }
        .btn-action:hover { transform: translateY(-3px); filter: brightness(1.1); box-shadow: 0 6px rgba(0,0,0,0.2); text-decoration: none; }

        .status-badge { padding: 5px 12px; border-radius: 15px; font-size: 11px; font-weight: bold; display: inline-block; }
        .fine-amount { font-weight: 800; color: #c0392b; font-size: 14px; }

        /* === 6. FOOTER === */
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
                <div class="col-md-12"><h4 class="header-line">Manage Issued Books</h4></div>
            </div>

            <?php if(isset($_SESSION['msg']) && $_SESSION['msg']!="") { ?>
                <div class="alert alert-success" style="border-radius:20px;">
                    <strong>Success:</strong> <?php echo htmlentities($_SESSION['msg']); $_SESSION['msg']=""; ?>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Master Issue Records</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student Name</th>
                                            <th>Book Name</th>
                                            <th>Issued Date</th>
                                            <th>Return Date</th>
                                            <th>Fine (INR)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $sql = "SELECT tblstudents.FullName,tblbooks.BookName,tblissuedbookdetails.IssuesDate,tblissuedbookdetails.ReturnDate,tblissuedbookdetails.fine,tblissuedbookdetails.id as rid from tblissuedbookdetails join tblstudents on tblstudents.StudentId=tblissuedbookdetails.StudentId join tblbooks on tblbooks.id=tblissuedbookdetails.BookId order by tblissuedbookdetails.id desc";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt=1;
                                    if($query->rowCount() > 0) {
                                        foreach($results as $result) { ?>                                           
                                        <tr>
                                            <td style="text-align:center;"><?php echo $cnt;?></td>
                                            <td><?php echo htmlentities($result->FullName);?></td>
                                            <td><?php echo htmlentities($result->BookName);?></td>
                                            <td style="text-align:center;"><?php echo htmlentities($result->IssuesDate);?></td>
                                            <td style="text-align:center;">
                                                <?php if(empty($result->ReturnDate)) {
                                                    echo '<span class="status-badge" style="background:#f9ebea; color:#c0392b;">NOT RETURNED</span>';
                                                } else {
                                                    echo '<span class="status-badge" style="background:#eafaf1; color:#27ae60;">'.htmlentities($result->ReturnDate).'</span>';
                                                } ?>
                                            </td>
                                            <td style="text-align:center; vertical-align: middle;">
                                                <span class="fine-amount">
                                                    <?php 
                                                        if(($result->fine === NULL || $result->fine === "") && empty($result->ReturnDate)) {
                                                            echo '<span style="color:#999;">---</span>';
                                                        } else {
                                                            echo "₹" . htmlentities($result->fine);
                                                        }
                                                    ?>
                                                </span>
                                            </td>
                                            <td style="text-align:center;">
                                                <?php if(empty($result->ReturnDate)) { ?>
                                                    <a href="manage-issued-books.php?returnid=<?php echo $result->rid;?>" onclick="return confirm('Mark as returned?');">
                                                        <button class="btn-action btn-return"><i class="fa fa-check"></i> Return</button>
                                                    </a>
                                                <?php } else { ?>
                                                    <a href="manage-issued-books.php?undoid=<?php echo $result->rid;?>" onclick="return confirm('Undo return?');">
                                                        <button class="btn-action btn-undo"><i class="fa fa-undo"></i> Undo</button>
                                                    </a>
                                                <?php } ?>
                                                
                                                <a href="update-issue-bookdeails.php?rid=<?php echo $result->rid;?>">
                                                    <button class="btn-action btn-edit"><i class="fa fa-edit"></i> Edit</button>
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
        $(document).ready(function(){ 
            $('#dataTables-example').dataTable(); 
        });
    </script>
</body>
</html>
<?php } ?>