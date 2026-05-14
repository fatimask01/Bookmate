<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 

    if(isset($_POST['update'])) {
        $category=$_POST['category'];
        $status=$_POST['status'];
        $catid=intval($_GET['catid']);
        $sql="update tblcategory set CategoryName=:category,Status=:status where id=:catid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':category',$category,PDO::PARAM_STR);
        $query->bindParam(':status',$status,PDO::PARAM_STR);
        $query->bindParam(':catid',$catid,PDO::PARAM_STR);
        $query->execute();
        $_SESSION['updatemsg']="Category updated successfully";
        header('location:manage-categories.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>OLMS | Edit Category</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --panel-shadow: 0 15px 45px rgba(0,0,0,0.3);
        }

        /* ===== 1. CORE RESET & ADMIN NAV ===== */
        html, body { height: 100%; margin: 0; background-color: #ffffff !important; }
        body { display: flex; flex-direction: column; font-family: 'Poppins', sans-serif; }

        .navbar-inverse { background-color: #ffffff !important; border: none !important; margin-bottom: 0 !important; }

        .menu-section {
            background-color: #ffffff !important;
            border-bottom: 5px solid rgb(225, 161, 25) !important;
            width: 100%;
            z-index: 99;
            /* box-shadow: var(--panel-shadow) !important; Standardized floating shadow */
        }

        #menu-top li a {
            color: rgb(225, 161, 25) !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 13px;
            padding: 12px 20px !important;
            margin: 10px 5px !important;
            border-radius: 10px !important;
            transition: all 0.3s ease;
        }

        #menu-top li a:hover, #menu-top li a.menu-top-active {
            background-color: #8c6411 !important; 
            color: #ffffff !important; 
            transform: translateY(-2px);
        }

        /* ===== 2. GOLDEN CONTENT AREA ===== */
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
            margin-bottom: 40px;
        }

        /* ===== 3. PANEL & 3D BUTTON ===== */
        .panel {
            background: #ffffff;
            border-radius: 20px !important;
            box-shadow: var(--panel-shadow);
            border: none !important;
            overflow: hidden;
        }

        .panel-heading {
            background: #fdfdfd !important;
            color: #8c6411 !important;
            font-weight: 700;
            padding: 20px !important;
            text-align: center;
            border-bottom: 1px solid #eee !important;
        }

        .form-control {
            height: 48px;
            border-radius: 10px !important;
            background: #f8fafc;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: rgb(225, 161, 25) !important;
            box-shadow: 0 0 8px rgba(225, 161, 25, 0.4) !important;
            outline: none !important;
        }

        .btn-update {
            width: 100%;
            padding: 15px;
            font-weight: 800;
            text-transform: uppercase;
            border-radius: 25px;
            border: none;
            color: white;
            background: linear-gradient(to bottom, rgb(225, 161, 25), #8c6411);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-update:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .btn-back {
            background: transparent;
            color: #fff;
            border: 2px solid #fff;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #fff;
            color: #8c6411;
            text-decoration: none;
        }

        .footer-section {
            background: #111 !important; 
            color: #ffffff !important; 
            padding: 25px 0; 
            border-top: 5px solid rgb(225, 161, 25) !important;
            text-align: center;
        }
    </style>
</head>
<body>
<?php include('includes/header.php');?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="manage-categories.php" class="btn-back">
                    <i class="fa fa-arrow-left"></i> Back To Categories
                </a>
                <h4 class="header-line">Edit Category</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">Update Details</div>
                    <div class="panel-body" style="padding: 30px;">
                        <form role="form" method="post">
                            <?php 
                            $catid=intval($_GET['catid']);
                            $sql="SELECT * from tblcategory where id=:catid";
                            $query=$dbh->prepare($sql);
                            $query-> bindParam(':catid',$catid, PDO::PARAM_STR);
                            $query->execute();
                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                            if($query->rowCount() > 0) {
                                foreach($results as $result) { ?> 
                                    <div class="form-group">
                                        <label>Category Name</label>
                                        <input class="form-control" type="text" name="category" value="<?php echo htmlentities($result->CategoryName);?>" required />
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Status</label>
                                        <div>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" value="1" <?php if($result->Status==1) echo "checked";?>> <span class="text-success" style="font-weight:600">Active</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" value="0" <?php if($result->Status==0) echo "checked";?>> <span class="text-danger" style="font-weight:600">Inactive</span>
                                            </label>
                                        </div>
                                    </div>
                            <?php }} ?>
                            
                            <button type="submit" name="update" class="btn-update">
                                <i class="fa fa-refresh"></i> Update Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>

<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
<?php } ?>