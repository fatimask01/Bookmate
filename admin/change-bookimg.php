<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 
    if(isset($_POST['update'])) {
        $bookid=intval($_GET['bookid']);
        $bookimg=$_FILES["bookpic"]["name"];
        $cimage=$_POST['curremtimage'];
        $cpath="bookimg"."/".$cimage;
        $extension = substr($bookimg,strlen($bookimg)-4,strlen($bookimg));
        $allowed_extensions = array(".jpg","jpeg",".png",".gif");
        $imgnewname=md5($bookimg.time()).$extension;

        if(!in_array($extension,$allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        } else {
            move_uploaded_file($_FILES["bookpic"]["tmp_name"],"bookimg/".$imgnewname);
            $sql="update tblbooks set bookImage=:imgnewname where id=:bookid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':imgnewname',$imgnewname,PDO::PARAM_STR);
            $query->bindParam(':bookid',$bookid,PDO::PARAM_STR);
            $query->execute();
            if($cimage != "" && file_exists($cpath)) { unlink($cpath); } 
            echo "<script>alert('Book image updated successfully');</script>";
            echo "<script>window.location.href='manage-books.php'</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>OLMS | Edit Book Image</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* === 1. CORE THEME & VARIABLES === */
        :root { 
            --panel-shadow: 0 15px 45px rgba(0,0,0,0.3); 
            --gold-primary: rgb(225, 161, 25); 
            --gold-dark: #8c6411; 
            --gold-gradient: linear-gradient(135deg, rgb(225, 161, 25) 0%, #8c6411 100%);
        }

        html, body { height: 100%; margin: 0; background-color: #ffffff !important; font-family: 'Poppins', sans-serif; }

        /* === 2. NAVIGATION (ADMIN DASHBOARD STYLE) === */
        .navbar-inverse {
            background-color: #ffffff !important;
            border: none !important;
            margin-bottom: 0 !important;
            padding: 10px 0;
        }

        .logo-img { height: 55px; width: auto; }

        .menu-section { 
            background-color: #ffffff !important; 
            border-bottom: 5px solid var(--gold-primary) !important; 
            /* box-shadow: var(--panel-shadow) !important;  */
            z-index: 999;
            position: relative;
        }

        #menu-top li a {
            color: var(--gold-primary) !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 13px;
            padding: 12px 20px !important;
            margin: 10px 5px !important;
            border-radius: 10px !important;
            transition: all 0.3s ease;
            background: transparent !important;
        }

        #menu-top li a:hover, #menu-top li a.menu-top-active {
            background-color: var(--gold-dark) !important; 
            color: #ffffff !important; 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
        }

        .btn-logout {
            background: #e74c3c !important;
            color: #fff !important;
            font-weight: 800;
            border-radius: 10px !important;
            padding: 8px 20px !important;
            border: none;
            box-shadow: 0 4px 0 #c0392b;
            transition: all 0.2s;
            margin-top: 15px;
        }

        /* === 3. CONTENT AREA === */
        .content-wrapper { 
            background: var(--gold-gradient) !important; 
            min-height: 85vh; 
            padding: 60px 0; 
        }

        .header-line { 
            font-weight: 800; 
            color: #fff; 
            border-bottom: 2px solid rgba(255,255,255,0.2); 
            padding-bottom: 10px; 
            text-transform: uppercase; 
            text-align: center; 
            margin-bottom: 30px;
        }

        /* === 4. PANEL & FORM STYLING === */
        .panel-golden {
            background: #ffffff !important;
            border: none !important;
            border-radius: 25px !important;
            box-shadow: var(--panel-shadow) !important;
            max-width: 550px;
            margin: auto;
            overflow: hidden;
        }

        .panel-golden .panel-heading {
            background: #fff !important;
            color: var(--gold-dark) !important;
            font-weight: 800;
            text-align: center;
            padding: 30px !important;
            font-size: 20px;
            border-bottom: 1px solid #f1f1f1 !important;
            text-transform: uppercase;
        }

        .gold-label {
            color: var(--gold-dark) !important;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 13px;
            margin-bottom: 8px;
            display: block;
        }

        .gold-input {
            border-radius: 12px !important;
            border: 2px solid #eee !important;
            height: 48px !important;
        }

        /* 3D GOLDEN BUTTON */
        .btn-golden-submit {
            background: var(--gold-gradient) !important;
            color: white !important;
            border: none !important;
            border-radius: 25px !important;
            padding: 15px !important;
            font-weight: 800;
            text-transform: uppercase;
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 6px 0 #5d420b !important;
            transition: all 0.2s ease;
        }

        .btn-golden-submit:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 15px rgba(0,0,0,0.3) !important;
        }

        .img-frame {
            display: inline-block;
            padding: 10px;
            background: #fff;
            border: 2px dashed var(--gold-primary);
            border-radius: 20px;
            margin-bottom: 20px;
        }

        /* === 5. FOOTER === */
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
                    <div class="panel panel-golden">
                        <div class="panel-heading">Update Book Cover</div>
                        <div class="panel-body" style="padding: 40px !important;">
                            <form role="form" method="post" enctype="multipart/form-data">
                                <?php 
                                $bookid=intval($_GET['bookid']);
                                $sql = "SELECT BookName,bookImage from tblbooks where id=:bookid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':bookid',$bookid,PDO::PARAM_STR);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                if($query->rowCount() > 0) {
                                    foreach($results as $result) { ?>
                                        
                                        <input type="hidden" name="curremtimage" value="<?php echo htmlentities($result->bookImage);?>">
                                        
                                        <div class="text-center">
                                            <span class="gold-label">Current Preview</span>
                                            <div class="img-frame">
                                                <img src="bookimg/<?php echo htmlentities($result->bookImage);?>" width="130" style="border-radius: 12px;">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <span class="gold-label">Book Title</span>
                                            <input class="form-control gold-input" type="text" value="<?php echo htmlentities($result->BookName);?>" readonly />
                                        </div>

                                        <div class="form-group">
                                            <span class="gold-label">Select New Image <span style="color:red;">*</span></span>
                                            <input class="form-control gold-input" type="file" name="bookpic" required />
                                        </div>

                                    <?php } 
                                } ?>
                                
                                <button type="submit" name="update" class="btn-golden-submit">
                                    <i class="fa fa-refresh"></i> Confirm Image Update
                                </button>
                                
                                <div class="text-center" style="margin-top: 25px;">
                                    <a href="edit-book.php?bookid=<?php echo $bookid; ?>" style="color: var(--gold-dark); font-weight: 700; text-decoration: none;">
                                        <i class="fa fa-long-arrow-left"></i> Back to Book Details
                                    </a>
                                </div>
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