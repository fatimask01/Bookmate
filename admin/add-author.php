<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 
    if(isset($_POST['create'])) {
        $author=$_POST['author'];
        $sql="INSERT INTO tblauthors(AuthorName) VALUES(:author)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':author',$author,PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        
        if($lastInsertId) {
            $_SESSION['msg']="Author Listed successfully";
            header('location:manage-authors.php');
        } else {
            $_SESSION['error']="Something went wrong. Please try again";
            header('location:manage-authors.php');
        }
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Library | Add Author</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ===== GLOBAL THEME ===== */
        :root {
            --gold-primary: rgb(225, 161, 25);
            --gold-dark: #8c6411;
            --panel-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        html, body {
            height: 100%;
            margin: 0;
            background-color: #ffffff !important; 
        }

        body { 
            display: flex;
            flex-direction: column;
            font-family: 'Poppins', sans-serif;
        }

        /* ===== NAVBAR & MENU ===== */
        .navbar-inverse {
            background-color: #ffffff !important;
            border: none !important;
        }

        .menu-section {
            background-color: #ffffff !important;
            border-bottom: 5px solid var(--gold-primary) !important;
            width: 100%;
            z-index: 99;
        }

        #menu-top li a {
            color: var(--gold-primary) !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            padding: 12px 20px !important;
            margin: 10px 5px !important;
            border-radius: 10px !important;
            transition: all 0.3s ease;
        }

        #menu-top li a:hover, .menu-top-active {
            background-color: var(--gold-dark) !important; 
            color: #ffffff !important; 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* ===== CONTENT AREA ===== */
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
            border-bottom: 2px solid rgba(255,255,255,0.3); 
            padding-bottom: 10px;
            margin-bottom: 45px;
            text-align: center;
        }

        /* ===== PANEL & FORM ===== */
        .panel {
            background: #ffffff;
            border-radius: 20px !important; 
            padding: 20px !important;
            box-shadow: var(--panel-shadow) !important;
            border: none !important;
        }

        .panel-heading {
            background: transparent !important;
            color: var(--gold-dark) !important;
            font-weight: 800;
            font-size: 20px;
            text-align: center;
            text-transform: uppercase;
            border-bottom: 1px solid #eee !important;
            padding-bottom: 15px !important;
        }

        .form-control {
            height: 48px;
            border-radius: 10px !important;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--gold-primary) !important;
            box-shadow: 0 0 8px rgba(225, 161, 25, 0.4) !important;
        }

        /* ===== 3D GOLDEN BUTTON ===== */
        .btn-golden-3d {
            width: 100%;
            height: 50px;
            border-radius: 25px !important;
            background: linear-gradient(to bottom, var(--gold-primary) 0%, var(--gold-dark) 100%);
            border: none;
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 20px;
            box-shadow: 0 4px 0 #6d4e0d, 0 8px 15px rgba(0,0,0,0.2);
            transition: all 0.2s ease;
        }

        .btn-golden-3d:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 0 #6d4e0d, 0 12px 20px rgba(0,0,0,0.3);
        }

        .btn-golden-3d:active {
            transform: translateY(1px);
            box-shadow: 0 2px 0 #6d4e0d, 0 4px 10px rgba(0,0,0,0.2);
        }
        /* Apply this to your add-author.php style section */
.btn-create-author {
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

.btn-create-author:hover {
    background: linear-gradient(to bottom, rgb(225, 161, 25), #8c6411) !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 0 rgba(0,0,0,0.2);
}
    </style>
</head>
<body>
    <?php include('includes/header.php');?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Add New Author</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel">
                        <div class="panel-heading">Author Information</div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label><i class="fa fa-user"></i> Author Name</label>
                                    <input class="form-control" type="text" name="author" autocomplete="off" placeholder="Enter author full name" required />
                                </div>
                               <button type="submit" name="create" class="btn-create-author">
    <i class="fa fa-user-plus"></i> Add Author
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