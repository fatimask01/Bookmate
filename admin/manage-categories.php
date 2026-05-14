<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 
    if(isset($_GET['del'])) {
        $id=$_GET['del'];
        $sql = "delete from tblcategory WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':id',$id, PDO::PARAM_STR);
        $query -> execute();
        $_SESSION['delmsg']="Category deleted successfully";
        header('location:manage-categories.php');
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>OLMS | Manage Categories</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

  <style>
    /* ===== 1. CORE LAYOUT & FONTS ===== */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: #ffffff !important; 
    }
    body { 
        display: flex;
        flex-direction: column;
        font-family: 'Poppins', sans-serif;
        /* Preference: Deep floating shadow variable */
        --panel-shadow: 0 15px 35px rgba(0,0,0,0.2), 0 5px 15px rgba(0,0,0,0.1);
    }

    /* ===== 2. NAVIGATION (ADMIN SPEC) ===== */
    .navbar-inverse {
        background-color: #ffffff !important;
        border: none !important;
        margin-bottom: 0 !important;
    }

    .menu-section {
        background-color: #ffffff !important;
        margin-top: 0 !important; 
        border-bottom: 5px solid rgb(225, 161, 25) !important;
        width: 100%;
        position: relative;
        z-index: 99;
        /* Explicitly removed shadow per request */
        box-shadow: none !important; 
    }

    #menu-top li a {
        color: rgb(225, 161, 25) !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        font-size: 13px !important;
        padding: 12px 20px !important;
        margin: 10px 5px !important;
        border-radius: 10px !important;
        transition: all 0.3s ease;
    }

    #menu-top li a:hover, 
    #menu-top li a.menu-top-active {
        background-color: #8c6411 !important; 
        color: #ffffff !important; 
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    }

    /* ===== 3. GOLDEN CONTENT AREA ===== */
    .content-wrapper { 
        flex: 1 0 auto; 
        background: linear-gradient(135deg, rgb(225, 161, 25) 0%, #8c6411 100%) !important; 
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

    /* ===== 4. TABLES & PANELS ===== */
    .panel {
        background: #ffffff;
        border-radius: 20px !important;
        /* Preference: Applied deep floating shadow */
        box-shadow: var(--panel-shadow) !important; 
        border: none !important;
        overflow: hidden;
    }

    .panel-heading {
        background: #fdfdfd !important;
        color: #8c6411 !important;
        font-weight: 800;
        font-size: 20px;
        text-align: center;
        padding: 25px !important;
        border-bottom: 1px solid #eee !important;
        text-transform: uppercase;
    }

    .table thead th {
        background-color: #f8fafc;
        color: #8c6411;
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

    /* Status Labels */
    .label-success { background-color: #27ae60 !important; border-radius: 10px; padding: 5px 12px; }
    .label-danger { background-color: #e74c3c !important; border-radius: 10px; padding: 5px 12px; }

    /* ===== 5. 3D ACTION BUTTONS (25PX RADIUS) ===== */
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
        color: #fff;
        text-decoration: none;
    }

    /* ===== 6. FORMS & INPUTS ===== */
    .form-control {
        height: 48px;
        border-radius: 10px !important;
        background: #f8fafc;
        border: 1px solid #ddd;
        transition: all 0.3s ease-in-out;
    }

    .form-control:focus {
        border-color: rgb(225, 161, 25) !important;
        outline: 0 !important;
        box-shadow: 0 0 8px rgba(225, 161, 25, 0.5) !important;
        background-color: #fff;
    }

    /* ===== 7. FOOTER ===== */
    .footer-section {
        background: #111 !important; 
        color: #ffffff !important; 
        padding: 25px 0; 
        border-top: 5px solid rgb(225, 161, 25) !important;
        text-align: center;
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
</style>


</head>
<body>
    <?php include('includes/header.php');?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Categories</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            Categories Listing
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Creation Date</th>
                                            <th>Updation Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT * from tblcategory";
                                        $query = $dbh -> prepare($sql);
                                        $query->execute();
                                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt=1;
                                        if($query->rowCount() > 0) {
                                            foreach($results as $result) { ?>                                          
                                                <tr class="odd gradeX">
                                                    <td><?php echo htmlentities($cnt);?></td>
                                                    <td><strong><?php echo htmlentities($result->CategoryName);?></strong></td>
                                                    <td>
                                                        <?php if($result->Status==1) { ?>
                                                            <span class="label label-success">Active</span>
                                                        <?php } else { ?>
                                                            <span class="label label-danger">Inactive</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td><?php echo htmlentities($result->CreationDate);?></td>
                                                    <td><?php echo htmlentities($result->UpdationDate);?></td>
                                                    <td>
                                                        <a href="edit-category.php?catid=<?php echo htmlentities($result->id);?>">
                                                            <button class="btn-edit-3d"><i class="fa fa-edit"></i> Edit</button>
                                                        </a>
                                                        <a href="manage-categories.php?del=<?php echo htmlentities($result->id);?>" onclick="return confirm('Are you sure?');">
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