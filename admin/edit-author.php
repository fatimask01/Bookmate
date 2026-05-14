<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 

    if(isset($_POST['update'])) {
        $athrid=intval($_GET['athrid']);
        $author=$_POST['author'];
        $sql="UPDATE tblauthors SET AuthorName=:author WHERE id=:athrid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':author',$author,PDO::PARAM_STR);
        $query->bindParam(':athrid',$athrid,PDO::PARAM_STR);
        $query->execute();
        $_SESSION['updatemsg']="Author info updated successfully";
        header('location:manage-authors.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>OLMS | Edit Author</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Poppins:400,600,700,800' rel='stylesheet' type='text/css' />

    <style>
        /* === 1. LAYOUT & NAV === */
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
        }

        /* === 2. GOLDEN CONTENT AREA === */
        .content-wrapper {
            flex: 1 0 auto; 
            background: linear-gradient(135deg, rgb(225, 161, 25) 0%, #8c6411 100%) !important; 
            padding: 60px 0;
        }

        .header-line {
            font-weight: 800;
            color: #fff !important;
            text-transform: uppercase;
            border-bottom: 2px solid rgba(255,255,255,0.3) !important;
            padding-bottom: 10px;
            margin-bottom: 40px;
        }

        /* Back Button Styling */
        .btn-back {
            background: transparent;
            color: #fff !important;
            border: 2px solid #fff;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            transition: all 0.3s ease;
            text-decoration: none !important;
            display: inline-block;
            margin-bottom: 20px;
        }
        .btn-back:hover { background: #fff; color: #8c6411 !important; transform: translateX(-5px); }

        /* === 3. FLOATING PANEL & FORM === */
        .panel-golden {
            border: none !important;
            border-radius: 20px !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            background: #ffffff;
        }

        .panel-golden > .panel-heading {
            background-color: #ffffff !important;
            color: #8c6411 !important;
            border-bottom: 1px solid #eee !important;
            padding: 20px;
            font-size: 18px;
            font-weight: 700;
            text-transform: uppercase;
            text-align: center;
        }

        .panel-body { padding: 40px !important; }

        label { color: #8c6411; font-weight: 700; text-transform: uppercase; font-size: 12px; }
        
        .form-control {
            height: 45px;
            border-radius: 10px;
            border: 2px solid #eee;
        }
        .form-control:focus {
            border-color: rgb(225, 161, 25);
            box-shadow: 0 0 8px rgba(225, 161, 25, 0.2);
        }

        /* 3D GRADIENT BUTTON */
        .btn-update {
            background: linear-gradient(135deg, rgb(225, 161, 25) 0%, #8c6411 100%) !important;
            color: white !important;
            border: none;
            padding: 15px;
            font-size: 14px;
            font-weight: 800;
            border-radius: 25px;
            text-transform: uppercase;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-update:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        /* === 4. FOOTER === */
        .footer-section {
            background: #111 !important; 
            color: #ffffff !important; 
            padding: 25px 0; 
            border-top: 5px solid rgb(225, 161, 25) !important;
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
                <div class="col-md-6 col-md-offset-3">
                    <a href="manage-authors.php" class="btn-back">
                        <i class="fa fa-arrow-left"></i> Back to Authors
                    </a>
                    
                    <h4 class="header-line">Edit Author Profile</h4>

                    <div class="panel panel-golden">
                        <div class="panel-heading">Update Author Details</div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>Full Author Name</label>
                                    <?php 
                                    $athrid=intval($_GET['athrid']);
                                    $sql = "SELECT * FROM tblauthors WHERE id=:athrid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':athrid',$athrid,PDO::PARAM_STR);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    if($query->rowCount() > 0) {
                                        foreach($results as $result) { ?> 
                                            <input class="form-control" type="text" name="author" value="<?php echo htmlentities($result->AuthorName);?>" required />
                                        <?php } 
                                    } ?>
                                </div>

                                <button type="submit" name="update" class="btn btn-update">
                                    <i class="fa fa-refresh"></i> Save Changes
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