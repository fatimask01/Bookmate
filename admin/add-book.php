<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 

    if(isset($_POST['add'])) {
        $bookname = $_POST['bookname'];
        $category = $_POST['category'];
        $author   = $_POST['author'];
        $isbn     = $_POST['isbn'];
        $price    = $_POST['price'];

        // File handling
        $imgFile = $_FILES["bookimg"]["name"];
        $imgExtension = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));
        $pdfFile = $_FILES["bookpdf"]["name"];
        $pdfExtension = strtolower(pathinfo($pdfFile, PATHINFO_EXTENSION));

        $allowedImgExts = array("jpg", "jpeg", "png", "gif");

        if(!in_array($imgExtension, $allowedImgExts)) {
            echo "<script>alert('Invalid image format. Only JPG, PNG, GIF allowed');</script>";
        } else if($pdfExtension != "pdf") {
            echo "<script>alert('Invalid document format. Only PDF allowed');</script>";
        } else {
            $newImgName = md5($imgFile . time()) . "." . $imgExtension;
            $newPdfName = md5($pdfFile . time()) . "." . $pdfExtension;
            
            $imgDir = "bookimg/";
            $pdfDir = "bookfiles/";

            if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);
            if (!is_dir($pdfDir)) mkdir($pdfDir, 0777, true);

            if(move_uploaded_file($_FILES["bookimg"]["tmp_name"], $imgDir . $newImgName) && 
               move_uploaded_file($_FILES["bookpdf"]["tmp_name"], $pdfDir . $newPdfName)) {

               // ===============================================
// CREATE 12-PAGE PREVIEW PDF AUTOMATICALLY
// ===============================================
$full_pdf = $pdfDir . $newPdfName;                     // original uploaded PDF
$preview_pdf = $pdfDir . "preview_" . $newPdfName;     // new preview PDF

// requires pdftk installed on system
shell_exec("pdftk $full_pdf cat 1-12 output $preview_pdf");
                
                $sql = "INSERT INTO tblbooks(BookName, CatId, AuthorId, ISBNNumber, BookPrice, bookImage, digital_file) 
                        VALUES(:bookname, :category, :author, :isbn, :price, :bookimg, :digitalfile)";
                
                $query = $dbh->prepare($sql);
                $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
                $query->bindParam(':category', $category, PDO::PARAM_INT);
                $query->bindParam(':author', $author, PDO::PARAM_INT);
                $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
                $query->bindParam(':price', $price, PDO::PARAM_STR);
                $query->bindParam(':bookimg', $newImgName, PDO::PARAM_STR);
                $query->bindParam(':digitalfile', $newPdfName, PDO::PARAM_STR);
                $query->execute();

                if($dbh->lastInsertId()) {
                    echo "<script>alert('Book added successfully!');</script>";
                    echo "<script>window.location.href='manage-books.php'</script>";
                } else {
                    echo "<script>alert('Database error. Please try again');</script>";
                }
            } else {
                echo "<script>alert('Error uploading files.');</script>";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>OLMS | Add Book</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Poppins:400,600,700,800' rel='stylesheet' type='text/css' />
    
    <style>
        /* === 1. CORE THEME & NAVIGATION === */
        :root {
            --gold-primary: rgb(225, 161, 25);
            --gold-dark: #8c6411;
            --panel-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }

        html, body { height: 100%; margin: 0; background-color: #ffffff !important; }
        body { display: flex; flex-direction: column; font-family: 'Poppins', sans-serif; }

        .navbar-inverse { background-color: #ffffff !important; border: none !important; margin-bottom: 0 !important; }
        .logo-img { height: 60px; width: auto; }

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

        #menu-top li a:hover, #menu-top li a.menu-top-active {
            background-color: var(--gold-dark) !important; 
            color: #ffffff !important; 
            transform: translateY(-2px);
        }

        /* === 2. CONTENT AREA (GOLDEN GRADIENT) === */
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
            border-bottom: 2px solid rgba(255,255,255,0.3); 
            padding-bottom: 10px;
            margin-bottom: 45px;
        }

        /* === 3. PANEL & FORM ELEMENTS === */
        .panel { 
            border-radius: 20px !important; 
            box-shadow: var(--panel-shadow) !important; 
            border: none !important;
            background: #fff;
            padding: 20px;
        }

        .panel-heading { 
            background: #ffffff !important; 
            color: var(--gold-dark) !important; 
            font-weight: 800; 
            font-size: 20px;
            text-transform: uppercase;
            text-align: center;
            border-bottom: 1px solid #eee !important;
        }

        label { color: #555; font-weight: 600; text-transform: uppercase; font-size: 11px; margin-top: 10px;}
        
        .form-control { height: 45px; border-radius: 10px; border: 1px solid #ddd; }

        .gen-btn { 
            background: linear-gradient(to bottom, var(--gold-dark), #5d420b) !important; 
            color: white !important; 
            font-weight: 700;
            border-radius: 0 10px 10px 0 !important; 
            box-shadow: 0 4px 0 #4a3508;
            height: 45px; 
        }
        /* Replace your existing .btn-submit or .btn-add styles with this */
.btn-create-book {
    width: 100%;
    height: 55px; /* Slightly taller for the main book action */
    border-radius: 25px !important;
    background: linear-gradient(to bottom, #8c6411, #5d420b) !important;
    color: #fff !important;
    border: none !important;
    font-weight: 700;
    text-transform: uppercase;
    margin-top: 25px;
    transition: all 0.2s ease;
    box-shadow: 0 4px 0 #4a3508; /* 3D depth */
}

.btn-create-book:hover {
    background: linear-gradient(to bottom, rgb(225, 161, 25), #8c6411) !important;
    transform: translateY(-3px); /* Floating effect */
    box-shadow: 0 6px 0 rgba(0,0,0,0.2);
}

        .btn-submit { 
            width: 100%; 
            border-radius: 25px; 
            background: linear-gradient(to bottom, var(--gold-dark), var(--gold-primary)) !important; 
            color: #fff !important;
            padding: 15px; 
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 25px;
            border: none;
            box-shadow: 0 4px 0 #6d4e0d;
            transition: 0.3s;
        }
        
        .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 6px 0 rgba(0,0,0,0.2); }

        /* 1. Remove default blue outline and apply Golden Glow on Focus */
.form-control:focus {
    border-color: rgb(225, 161, 25) !important;
    outline: 0 !important;
    box-shadow: 0 0 8px rgba(225, 161, 25, 0.5) !important;
    background-color: #fff;
}

    </style>

    <script src="assets/js/jquery-1.10.2.js"></script>
    <script>
    function checkisbnAvailability() {
        $.ajax({
            url: "check_availability.php",
            data: 'isbn=' + $("#isbn").val(),
            type: "POST",
            success: function(data) {
                $("#isbn-availability-status").html(data);
            }
        });
    }

    function generateAutoISBN() {
        let prefix = "978";
        let randomPart = Math.floor(Math.random() * 9000000000) + 1000000000;
        document.getElementById('isbn').value = prefix + randomPart;
        checkisbnAvailability();
    }
    </script>
</head>
<body>
    <?php include('includes/header.php');?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12"><h4 class="header-line">Add New Book</h4></div>
            </div>
            
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel">
                        <div class="panel-heading">Enter Book Information</div>
                        <div class="panel-body">
                            <form role="form" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Book Name<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="bookname" required />
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>ISBN Number<span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="isbn" id="isbn" required onBlur="checkisbnAvailability()" />
                                            <span class="input-group-btn">
                                                <button class="btn gen-btn" type="button" onclick="generateAutoISBN()">
                                                    <i class="fa fa-magic"></i> Auto Generate
                                                </button>
                                            </span>
                                        </div>
                                        <span id="isbn-availability-status" style="font-size:12px;"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Category<span style="color:red;">*</span></label>
                                        <select class="form-control" name="category" required>
                                            <option value="">Select Category</option>
                                            <?php 
                                            $sql = "SELECT * from tblcategory where Status=1";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                                            foreach($results as $result) { ?>  
                                                <option value="<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->CategoryName);?></option>
                                            <?php } ?> 
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Author<span style="color:red;">*</span></label>
                                        <select class="form-control" name="author" required>
                                            <option value="">Select Author</option>
                                            <?php 
                                            $sql = "SELECT * from tblauthors";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                                            foreach($results as $result) { ?>  
                                                <option value="<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->AuthorName);?></option>
                                            <?php } ?> 
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>Price<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="price" required />
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>Cover Image<span style="color:red;">*</span></label>
                                        <input class="form-control" type="file" name="bookimg" accept="image/*" required />
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>Digital Book (PDF)<span style="color:red;">*</span></label>
                                        <input class="form-control" type="file" name="bookpdf" accept=".pdf" required />
                                    </div>
                                </div>

                                <button type="submit" name="add" class="btn-create-book">
    <i class="fa fa-plus"></i> Add Book to Library
</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php');?>
    <script src="assets/js/bootstrap.js"></script>
</body>
</html>
<?php } ?>