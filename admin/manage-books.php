<?php
session_start();
error_reporting(E_ALL); 
include('../includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 

    // Handle Deletion Logic
    if(isset($_GET['del'])) {
        $id = $_GET['del'];
        $sql = "SELECT bookImage, digital_file FROM tblbooks WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetch(PDO::FETCH_OBJ);

        if($results) {
            if(!empty($results->bookImage) && file_exists("bookimg/".$results->bookImage)) {
                unlink("bookimg/".$results->bookImage);
            }
            if(!empty($results->digital_file) && file_exists("bookfiles/".$results->digital_file)) {
                unlink("bookfiles/".$results->digital_file);
            }
        }

        $sql = "DELETE FROM tblbooks WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();

        $_SESSION['msg'] = "Book and files deleted successfully";
        header('location:manage-books.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>LMS | Manage Catalog</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* === 1. CORE THEME & VARIABLES === */
        :root {
            --gold-primary: rgb(225, 161, 25);
            --gold-dark: #8c6411;
            --gold-gradient: linear-gradient(135deg, rgb(225, 161, 25) 0%, #8c6411 100%);
            --panel-shadow: 0 15px 45px rgba(0,0,0,0.3);
        }

        html, body { height: 100%; margin: 0; background-color: #ffffff !important; font-family: 'Poppins', sans-serif; }
        body { display: flex; flex-direction: column; }

        /* === 2. NAVIGATION (ADMIN DASHBOARD STYLE) === */
        .navbar-inverse {
            background-color: #ffffff !important;
            border: none !important;
            margin-bottom: 0 !important;
            padding: 10px 0 !important;
            min-height: 80px !important;
            display: flex;
            align-items: center;
        }

        .menu-section {
            background-color: #ffffff !important;
            margin-top: 0 !important; 
            border-bottom: 5px solid var(--gold-primary) !important;
            width: 100%;
            position: relative;
            z-index: 99;
            /* box-shadow: var(--panel-shadow) !important; Deep floating shadow */
        }

        .logo-img { height: 60px; width: auto; object-fit: contain; }

        #menu-top li a {
            color: var(--gold-primary) !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 13px !important;
            padding: 12px 20px !important;
            margin: 5px !important;
            border-radius: 10px !important;
            transition: all 0.3s ease;
        }

        #menu-top li a:hover, 
        #menu-top li a.menu-top-active {
            background-color: var(--gold-dark) !important; 
            color: #ffffff !important; 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* === 3. CONTENT AREA === */
        .content-wrapper { 
            flex: 1 0 auto; 
            background: var(--gold-gradient) !important; 
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

        /* === 4. DATA TABLE PANEL === */
        .panel { 
            border-radius: 20px !important; 
            overflow: hidden; 
            box-shadow: var(--panel-shadow) !important; 
            border: none !important;
            background: #fff;
            padding: 20px;
        }

        .book-cover { 
            width: 45px; height: 65px; object-fit: cover; border-radius: 4px; 
            border: 1px solid #ddd; box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }

        .table td { vertical-align: middle !important; }

        /* 3D Action Buttons */
        .btn-circle { 
            width: 35px; height: 35px; border-radius: 50%; display: inline-flex; 
            align-items: center; justify-content: center; color: white !important; 
            transition: 0.3s; border: none;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
        }
        .btn-edit { background: #3498db; box-shadow: 0 3px 0 #2980b9; }
        .btn-del { background: #e74c3c; box-shadow: 0 3px 0 #c0392b; }
        
        .btn-circle:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 5px 10px rgba(0,0,0,0.3); 
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
        <div class="container-fluid" style="padding: 0 40px;">
            <h2 class="header-line">Advanced Inventory Manager</h2>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Book & Cover</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>ISBN</th>
                                    <th>Price</th>
                                    <th>PDF</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
<?php 
$sql = "SELECT tblbooks.*, tblcategory.CategoryName, tblauthors.AuthorName FROM tblbooks 
        LEFT JOIN tblcategory ON tblcategory.id = tblbooks.CatId 
        LEFT JOIN tblauthors ON tblauthors.id = tblbooks.AuthorId";
$query = $dbh->prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
foreach($results as $result) { ?>                                    
                                <tr>
                                    <td><?php echo $cnt;?></td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <img src="bookimg/<?php echo htmlentities($result->bookImage);?>" class="book-cover" onerror="this.src='https://via.placeholder.com/45x65'">
                                            <span style="font-weight:700; color:#333;"><?php echo htmlentities($result->BookName);?></span>
                                        </div>
                                    </td>
                                    <td><?php echo htmlentities($result->CategoryName);?></td>
                                    <td><?php echo htmlentities($result->AuthorName);?></td>
                                    <td><code><?php echo htmlentities($result->ISBNNumber);?></code></td>
                                    <td><strong><?php echo htmlentities($result->BookPrice);?></strong></td>
                                    <td class="text-center">
                                        <?php if(!empty($result->digital_file)): ?>
                                            <a href="bookfiles/<?php echo htmlentities($result->digital_file);?>" target="_blank">
                                                <i class="fa fa-file-pdf-o" style="color:#e74c3c; font-size:20px;"></i>
                                            </a>
                                        <?php else: ?>
                                            <i class="fa fa-times text-muted"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="display:flex; gap:8px;">
                                            <a href="edit-book.php?bookid=<?php echo $result->id;?>" class="btn-circle btn-edit" title="Edit"><i class="fa fa-pencil"></i></a>
                                            <a href="manage-books.php?del=<?php echo $result->id;?>" onclick="return confirm('Delete this book permanently?');" class="btn-circle btn-del" title="Delete"><i class="fa fa-trash-o"></i></a>
                                        </div>
                                    </td>
                                </tr>
<?php $cnt++; } ?>                                     
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-section">
        <div class="container">
            &copy; <?php echo date('Y');?> Online Library Management System | Admin Dashboard
        </div>
    </footer>

    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable({
                "pageLength": 10
            });
        });
    </script>
</body>
</html>
<?php } ?>