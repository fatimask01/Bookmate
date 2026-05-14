<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 

    if(isset($_POST['update'])) {
        $bookname = $_POST['bookname'];
        $category = $_POST['category'];
        $author = $_POST['author'];
        $price = $_POST['price']; 
        $bookid = intval($_GET['bookid']);
        $bqty = $_POST['bqty'];
        
        $sql = "UPDATE tblbooks SET BookName=:bookname, CatId=:category, AuthorId=:author, BookPrice=:price, bookQty=:bqty WHERE id=:bookid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':author', $author, PDO::PARAM_STR);
        $query->bindParam(':price', $price, PDO::PARAM_STR);
        $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query->bindParam(':bqty', $bqty, PDO::PARAM_STR);
        $query->execute();
        
        $_SESSION['msg'] = "Book updated successfully";
        header('location:manage-books.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>LMS | Edit Book Details</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        /* === 1. NAVIGATION (ADMIN DASHBOARD SPEC) === */
        :root {
            --panel-shadow: 0 15px 45px rgba(0,0,0,0.3);
        }

        html, body { height: 100%; margin: 0; background-color: #ffffff !important; }
        body { display: flex; flex-direction: column; font-family: 'Poppins', sans-serif; }

        .navbar-inverse { background-color: #ffffff !important; border: none !important; margin-bottom: 0 !important; }

        .menu-section {
            background-color: #ffffff !important;
            border-bottom: 5px solid rgb(225, 161, 25) !important;
            width: 100%;
            z-index: 99;
            /* box-shadow: var(--panel-shadow) !important; Fixed Floating Shadow per your spec */
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

        .card-container { max-width: 850px; margin: 0 auto; }

        .btn-back {
            background: rgba(255, 255, 255, 0.2); 
            color: #fff; 
            border: 1px solid #fff;
            border-radius: 25px; 
            padding: 8px 22px; 
            font-weight: 600;
            text-decoration: none !important; 
            display: inline-block;
            margin-bottom: 25px;
            transition: 0.3s;
        }
        .btn-back:hover { background: #fff; color: #8c6411; transform: translateX(-5px); }

        /* === 3. FLOATING PANEL & FORM === */
        .panel-golden { 
            border: none !important; 
            border-radius: 25px !important; 
            box-shadow: var(--panel-shadow) !important; 
            overflow: hidden;
            background: #ffffff !important;
        }

        .panel-golden > .panel-heading { 
            background: #ffffff !important; 
            color: #8c6411 !important; 
            font-weight: 800; 
            text-align: center; 
            padding: 30px; 
            text-transform: uppercase; 
            border-bottom: 1px solid #f1f1f1 !important;
            font-size: 20px;
        }

        .panel-body { padding: 45px !important; }

        h4 { color: #8c6411; font-weight: 700; margin-top: 25px; }
        label { color: #555; font-weight: 600; text-transform: uppercase; font-size: 12px; }

        .form-control { 
            height: 48px; 
            border-radius: 12px; 
            border: 1px solid #ddd;
            background: #fdfdfd;
        }

        /* 3D GRADIENT BUTTON */
        .btn-golden-submit {
            background: linear-gradient(to bottom, #8c6411, rgb(225, 161, 25)) !important;
            border: none !important;
            padding: 15px;
            font-weight: 700;
            border-radius: 25px !important;
            width: 100%;
            margin-top: 30px;
            color: #fff !important;
            text-transform: uppercase;
            box-shadow: 0 4px 0 #6d4e0d;
            transition: all 0.3s ease;
        }
        .btn-golden-submit:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 6px 0 rgba(0,0,0,0.2); 
        }

        .book-img-styled { 
            border-radius: 15px; 
            box-shadow: 0 8px 20px rgba(0,0,0,0.15); 
            border: 4px solid #fff; 
            max-width: 160px;
        }

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
            <div class="card-container">
                <a href="manage-books.php" class="btn-back">
                    <i class="fa fa-arrow-left"></i> Back to Catalog
                </a>

                <div class="panel panel-golden">
                    <div class="panel-heading">Edit Book Information</div>
                    <div class="panel-body">
                        <form role="form" method="post">
                            <?php 
                            $bookid=intval($_GET['bookid']);
                            $sql = "SELECT tblbooks.*, tblcategory.CategoryName, tblcategory.id as cid, tblauthors.AuthorName, tblauthors.id as athrid 
                                    FROM tblbooks 
                                    JOIN tblcategory ON tblcategory.id=tblbooks.CatId 
                                    JOIN tblauthors ON tblauthors.id=tblbooks.AuthorId 
                                    WHERE tblbooks.id=:bookid";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':bookid',$bookid,PDO::PARAM_STR);
                            $query->execute();
                            $result=$query->fetch(PDO::FETCH_OBJ);
                            ?>

                            <div class="text-center">
                                <img src="bookimg/<?php echo htmlentities($result->bookImage);?>" class="book-img-styled">
                                <p style="margin-top:15px;"><a href="change-bookimg.php?bookid=<?php echo $result->id;?>" class="btn btn-xs btn-default" style="border-radius:10px; padding: 5px 12px;">Change Cover Image</a></p>
                            </div>

                            <h4><i class="fa fa-book"></i> Book Basics</h4>
                            <hr />
                            <div class="form-group">
                                <label>Book Name</label>
                                <input class="form-control" type="text" name="bookname" value="<?php echo htmlentities($result->BookName);?>" required />
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select class="form-control" name="category" required>
                                            <option value="<?php echo htmlentities($result->cid);?>"><?php echo htmlentities($result->CategoryName);?></option>
                                            <?php 
                                            $sql1 = "SELECT * from tblcategory";
                                            $query1 = $dbh->prepare($sql1);
                                            $query1->execute();
                                            $results1=$query1->fetchAll(PDO::FETCH_OBJ);
                                            foreach($results1 as $row1) {
                                                if($result->CategoryName == $row1->CategoryName) continue;
                                                echo "<option value='".htmlentities($row1->id)."'>".htmlentities($row1->CategoryName)."</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Author</label>
                                        <select class="form-control" name="author" required>
                                            <option value="<?php echo htmlentities($result->athrid);?>"><?php echo htmlentities($result->AuthorName);?></option>
                                            <?php 
                                            $sql2 = "SELECT * from tblauthors";
                                            $query2 = $dbh->prepare($sql2);
                                            $query2->execute();
                                            $results2=$query2->fetchAll(PDO::FETCH_OBJ);
                                            foreach($results2 as $row2) {
                                                if($result->AuthorName == $row2->AuthorName) continue;
                                                echo "<option value='".htmlentities($row2->id)."'>".htmlentities($row2->AuthorName)."</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <h4><i class="fa fa-tag"></i> Pricing & Stock</h4>
                            <hr />
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>ISBN</label>
                                        <input class="form-control" type="text" value="<?php echo htmlentities($result->ISBNNumber);?>" readonly style="background:#f5f5f5;" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input class="form-control" type="text" name="price" value="<?php echo htmlentities($result->BookPrice);?>" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <input class="form-control" type="number" name="bqty" value="<?php echo htmlentities($result->bookQty);?>" required />
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="update" class="btn-golden-submit">
                                <i class="fa fa-refresh"></i> Update Book Details
                            </button>
                        </form>
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