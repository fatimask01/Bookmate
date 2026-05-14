<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 

    if(isset($_POST['create'])) {
        $category = $_POST['category'];
        $status = $_POST['status'];
        $sql = "INSERT INTO tblcategory(CategoryName,Status) VALUES(:category,:status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':category',$category,PDO::PARAM_STR);
        $query->bindParam(':status',$status,PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        
        if($lastInsertId) {
            $_SESSION['msg']="Category Listed successfully";
            header('location:manage-categories.php');
        } else {
            $_SESSION['error']="Something went wrong. Please try again";
            header('location:manage-categories.php');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>OLMS | Add Categories</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        /* === 1. CORE LAYOUT & ADMIN NAV === */
        html, body { height: 100%; margin: 0; background-color: #ffffff !important; }
        body { display: flex; flex-direction: column; font-family: 'Poppins', sans-serif; }

        .navbar-inverse { background-color: #ffffff !important; border: none !important; margin-bottom: 0 !important; }
        
        .menu-section {
            background-color: #ffffff !important;
            border-bottom: 5px solid rgb(225, 161, 25) !important;
            width: 100%;
            z-index: 99;
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* === 2. GOLDEN CONTENT AREA === */
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
            border-bottom: 2px solid rgba(255,255,255,0.3); 
            padding-bottom: 10px;
            margin-bottom: 45px;
        }

        /* === 3. PANEL & FORM STYLING === */
        .panel {
            background: #ffffff;
            border-radius: 20px !important;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3); /* Deep floating shadow */
            border: none !important;
            max-width: 500px;
            margin: auto;
            overflow: hidden;
        }

        .panel-heading {
            background: #ffffff !important;
            color: #333 !important;
            font-weight: 700;
            font-size: 20px;
            padding: 25px !important;
            border-bottom: 1px solid #eee !important;
            text-align: center;
        }

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
        }

        /* 3D Golden Button */
        .btn-create {
            width: 100%;
            height: 50px;
            border-radius: 25px !important;
            background: linear-gradient(to bottom, #8c6411, #5d420b) !important;
            color: #fff !important;
            border: none !important;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 20px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 0 #4a3508;
        }

        .btn-create:hover {
            background: linear-gradient(to bottom, rgb(225, 161, 25), #8c6411) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 0 rgba(0,0,0,0.2);
        }

        /* === 4. FOOTER === */
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
                <h4 class="header-line">Add Category</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel">
                    <div class="panel-heading">CATEGORY DETAILS</div>
                    <div class="panel-body">
                        <form role="form" method="post">
                            <div class="form-group">
                                <label style="font-weight:700; color: #555;">Category Name</label>
                                <input class="form-control" type="text" name="category" autocomplete="off" required />
                            </div>
                            
                            <div class="form-group">
                                <label style="font-weight:700; color: #555;">Status</label>
                                <div class="radio">
                                    <label><input type="radio" name="status" value="1" checked>Active</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="status" value="0">Inactive</label>
                                </div>
                            </div>
                            
                            <button type="submit" name="create" class="btn btn-create">
                                <i class="fa fa-plus"></i> Create Category
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