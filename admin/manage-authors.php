<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else {
    if(isset($_GET['del'])) {
        $id=$_GET['del'];
        $sql = "delete from tblauthors WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':id',$id, PDO::PARAM_STR);
        $query -> execute();
        $_SESSION['delmsg']="Author deleted";
        header('location:manage-authors.php');
        exit();
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>OLMS | Manage Authors</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* === 1. CORE THEME & VARIABLES === */
        :root {
            --gold-primary: rgb(225, 161, 25);
            --gold-dark: #8c6411;
            --panel-shadow: 0 15px 45px rgba(0,0,0,0.3);
        }

        html, body { height: 100%; margin: 0; padding: 0; background-color: #ffffff !important; }
        body { display: flex; flex-direction: column; font-family: 'Poppins', sans-serif; }

        /* === 2. NAVIGATION (FIXED ALIGNMENT & LOGO) === */
        .navbar-inverse {
            background-color: #ffffff !important;
            border: none !important;
            margin-bottom: 0 !important;
            padding: 10px 0 !important; 
            min-height: 90px !important; /* Prevents logo cutting */
            display: flex;
            align-items: center;
        }

        .navbar-header { padding: 0 !important; }
        .logo-img { height: 65px; width: auto; object-fit: contain; display: block; }

        .menu-section {
            background-color: #ffffff !important;
            border-bottom: 5px solid var(--gold-primary) !important;
            width: 100%;
            position: relative;
            z-index: 99;
            /* box-shadow: var(--panel-shadow) !important; Deep floating shadow */
        }

        #menu-top { display: flex; align-items: center; margin: 0; }

        #menu-top li a {
            color: var(--gold-primary) !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 13px !important;
            padding: 12px 20px !important;
            margin: 5px !important;
            border-radius: 10px !important;
            transition: all 0.3s ease;
            line-height: 1.5;
        }

        #menu-top li a:hover, 
        #menu-top li a.menu-top-active {
            background-color: var(--gold-dark) !important; 
            color: #ffffff !important; 
            transform: translateY(-2px);
        }

        /* === 3. CONTENT AREA (GOLDEN GRADIENT) === */
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
            border-bottom: 2px solid rgba(255,255,255,0.3) !important; 
            padding-bottom: 10px;
            margin-bottom: 45px;
            text-align: center;
        }

        /* === 4. PANEL & TABLE STYLING === */
        .panel {
            background: #ffffff;
            border-radius: 20px !important; 
            box-shadow: var(--panel-shadow) !important;
            border: none !important;
            overflow: hidden;
        }

        .panel-heading {
            background: #fdfdfd !important;
            color: var(--gold-dark) !important;
            font-weight: 800;
            font-size: 20px;
            text-align: center;
            padding: 25px !important;
            border-bottom: 1px solid #eee !important;
            text-transform: uppercase;
        }

        .table thead th {
            background-color: #f8fafc;
            color: var(--gold-dark);
            text-align: center;
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 2px solid #eee !important;
        }

        .table td {
            text-align: center;
            vertical-align: middle !important;
            padding: 15px !important;
        }

        /* 3D GRADIENT BUTTONS */
        .btn-edit-3d, .btn-del-3d {
            color: white !important;
            border: none;
            border-radius: 25px; 
            padding: 8px 20px;
            font-weight: 700;
            transition: all 0.2s;
            text-transform: uppercase;
            font-size: 11px;
            display: inline-block;
        }

        .btn-edit-3d {
            background: linear-gradient(to bottom, #3498db, #2980b9);
            box-shadow: 0 4px 0 #1c5e85;
        }

        .btn-del-3d {
            background: linear-gradient(to bottom, #e74c3c, #c0392b);
            box-shadow: 0 4px 0 #8e2a1e;
        }

        .btn-edit-3d:hover, .btn-del-3d:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 0 rgba(0,0,0,0.2);
            text-decoration: none;
        }

        /* === 5. FOOTER === */
        .footer-section {
            background: #111 !important; 
            color: #ffffff !important; 
            padding: 25px 0; 
            border-top: 5px solid var(--gold-primary) !important;
            text-align: center;
        }
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
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">Manage Authors</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">Authors Listing</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Author Name</th>
                                        <th>Creation Date</th>
                                        <th>Updation Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $sql = "SELECT * from tblauthors";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                if($query->rowCount() > 0) {
                                    foreach($results as $result) { ?>                                          
                                        <tr>
                                            <td><?php echo htmlentities($cnt);?></td>
                                            <td><?php echo htmlentities($result->AuthorName);?></td>
                                            <td><?php echo htmlentities($result->creationDate);?></td>
                                            <td><?php echo htmlentities($result->UpdationDate);?></td>
                                            <td>
                                                <a href="edit-author.php?athrid=<?php echo htmlentities($result->id);?>">
                                                    <button class="btn-edit-3d"><i class="fa fa-edit"></i> Edit</button>
                                                </a> 
                                                <a href="manage-authors.php?del=<?php echo htmlentities($result->id);?>" onclick="return confirm('Are you sure you want to delete?');">
                                                    <button class="btn-del-3d"><i class="fa fa-trash"></i> Delete</button>
                                                </a>
                                            </td>
                                        </tr>
                                <?php $cnt=$cnt+1; } } ?>                                     
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
        $('#dataTables-example').dataTable();
    });
</script>
</body>
</html>
<?php } ?>