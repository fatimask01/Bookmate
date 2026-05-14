<?php
session_start();
error_reporting(E_ALL); 
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 
    if(isset($_POST['issue'])) {
        $studentid = strtoupper($_POST['studentid']);
        $bookid = $_POST['bookid']; 
        $aremark = $_POST['aremark']; 
        $aqty = isset($_POST['aqty']) ? $_POST['aqty'] : 0; 

        if($aqty > 0) {
            $sql = "INSERT INTO tblissuedbookdetails(StudentID, BookId, remark) VALUES(:studentid, :bookid, :aremark)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
            $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
            $query->bindParam(':aremark', $aremark, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();

            if($lastInsertId) {
                $_SESSION['msg'] = "Book issued successfully";
                header('location:manage-issued-books.php');
                exit;
            } else {
                $_SESSION['error'] = "Something went wrong. Please try again";
                header('location:manage-issued-books.php');
                exit;
            } 
        } else {
            $_SESSION['error'] = "Book Not available in stock";
            header('location:manage-issued-books.php');   
            exit;
        }
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>OLMS | Issue New Book</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
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

        /* === 2. NAVIGATION & HEADER (FIXED ALIGNMENT) === */
        .navbar-inverse {
            background-color: #ffffff !important;
            border: none !important;
            margin-bottom: 0 !important;
            padding: 10px 0 !important; 
            min-height: 90px !important; /* Extra height to prevent logo cutting */
            display: flex;
            align-items: center;
        }

        .navbar-header { padding: 0 !important; }

        .logo-img { 
            height: 65px; 
            width: auto; 
            object-fit: contain;
            display: block;
        }

        .menu-section {
            background-color: #ffffff !important;
            border-bottom: 5px solid var(--gold-primary) !important;
            width: 100%;
            position: relative;
            z-index: 99;
            /* box-shadow: var(--panel-shadow) !important; Standard deep shadow */
        }

        /* Nav item centering */
        #menu-top {
            display: flex;
            align-items: center;
            margin: 0;
        }

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

        /* === 3. CONTENT AREA === */
        .content-wrapper { 
            flex: 1 0 auto;
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%) !important; 
            padding: 60px 0 !important;
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

        /* === 4. PANEL & FORM STYLING === */
        .panel-info {
            background: #ffffff !important;
            border: none !important;
            border-radius: 25px !important;
            box-shadow: var(--panel-shadow) !important; 
            overflow: hidden;
        }

        .panel-info > .panel-heading {
            background: #fff !important;
            color: var(--gold-dark) !important;
            font-weight: 800 !important;
            text-align: center;
            padding: 25px !important;
            font-size: 20px !important;
            border-bottom: 1px solid #f1f1f1 !important;
            text-transform: uppercase;
        }

        .panel-body { padding: 40px !important; }

        label {
            color: var(--gold-dark) !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .form-control {
            border-radius: 12px !important;
            border: 2px solid #eee !important;
            height: 48px !important;
        }

        .form-control:focus {
            border-color: var(--gold-primary) !important;
            background: #fff8eb !important;
            box-shadow: none !important;
        }

        .btn-custom-issue {
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%) !important;
            color: white !important;
            border: none !important;
            border-radius: 25px !important; 
            padding: 15px !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2) !important;
            transition: all 0.3s ease !important;
        }

        .btn-custom-issue:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 12px 25px rgba(0,0,0,0.3) !important;
        }

        /* === 5. FOOTER === */
        .footer-section {
            background: #111 !important; 
            color: #ffffff !important; 
            padding: 25px 0; 
            border-top: 5px solid var(--gold-primary) !important;
            text-align: center;
        }
    </style>

    <script>
    function getstudent() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "get_student.php",
            data: 'studentid=' + $("#studentid").val(),
            type: "POST",
            success: function(data) {
                $("#get_student_name").html(data);
                $("#loaderIcon").hide();
            }
        });
    }

    function getbook() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "get_book.php",
            data: 'bookid=' + $("#bookid").val(),
            type: "POST",
            success: function(data) {
                $("#get_book_name").html(data);
                $("#loaderIcon").hide();
            }
        });
    }
    </script> 
</head>
<body>
    <?php include('includes/header.php');?>
    
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Issue a New Book</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                           <i class="fa fa-book"></i> Issue Entry Form
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>Student ID <span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="studentid" id="studentid" onBlur="getstudent()" placeholder="Enter Student ID" autocomplete="off" required />
                                    <div id="get_student_name" style="margin-top:8px; font-size:13px; font-weight:600;"></div> 
                                </div>

                                <div class="form-group">
                                    <label>ISBN or Book Title <span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="bookid" id="bookid" onBlur="getbook()" placeholder="Enter ISBN or Title" required />
                                    <div id="get_book_name" style="margin-top:8px; font-size:13px; font-weight:600;"></div>
                                </div>

                                <div class="form-group">
                                    <label>Remark / Notes <span style="color:red;">*</span></label>
                                    <textarea class="form-control" name="aremark" id="aremark" style="height: 100px !important; padding: 12px !important;" placeholder="Add any condition remarks" required></textarea> 
                                </div>

                                <button type="submit" name="issue" id="submit" class="btn-custom-issue">
                                   <i class="fa fa-send"></i> Confirm Issue
                                </button>
                                
                                <div id="loaderIcon" class="text-center" style="display:none; margin-top:15px;">
                                    <i class="fa fa-spinner fa-spin fa-2x" style="color: var(--gold-dark);"></i>
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