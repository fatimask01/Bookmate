<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 

if(isset($_POST['return'])) {
    $rid = intval($_GET['rid']);
    $fine = $_POST['fine']; 
    $rstatus = 1;
    $bookid = $_POST['bookid'];
    
    // Fixed: Combined SQL and added missing closing quote
    $sql = "UPDATE tblissuedbookdetails SET fine=:fine, RetrunStatus=:rstatus, ReturnDate=NOW() WHERE id=:rid;
            UPDATE tblbooks SET isIssued=0 WHERE id=:bookid";
    
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_INT);
    $query->bindParam(':fine', $fine, PDO::PARAM_STR);
    $query->bindParam(':rstatus', $rstatus, PDO::PARAM_INT);
    $query->bindParam(':bookid', $bookid, PDO::PARAM_INT);
    $query->execute();

    $_SESSION['msg'] = "Book Returned successfully. Fine: ₹$fine";
    header('location:manage-issued-books.php');
    exit;
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>OLMS | Issued Book Details</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ===== 1. CORE RESET & VARIABLES ===== */
        :root {
            --gold-primary: rgb(225, 161, 25);
            --gold-dark: #8c6411;
            --panel-shadow: 0 15px 45px rgba(0,0,0,0.3);
        }

        html, body { height: 100%; margin: 0; padding: 0; background-color: #ffffff !important; }
        body { display: flex; flex-direction: column; font-family: 'Poppins', sans-serif; }
        
        /* ===== 2. NAVIGATION (ADMIN STYLE) ===== */
        .navbar-inverse {
            background-color: #ffffff !important;
            border: none !important;
            margin-bottom: 0 !important;
            padding: 10px 0 !important; 
            min-height: 80px !important; 
            display: flex;
            align-items: center;
        }

        .logo-img { height: 60px; width: auto; object-fit: contain; }

        .menu-section {
            background-color: #ffffff !important;
            margin-top: 0 !important; 
            border-bottom: 5px solid var(--gold-primary) !important;
            width: 100%;
            position: relative;
            z-index: 99;
            /* box-shadow: var(--panel-shadow) !important; Your requested deep shadow */
        }

        #menu-top li a {
            color: var(--gold-primary) !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 13px !important;
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

        /* ===== 3. GOLDEN CONTENT AREA & BUTTONS ===== */
        .content-wrapper { 
            flex: 1 0 auto; 
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%) !important; 
            padding: 40px 0 !important; 
        }

        .btn-back {
            background: rgba(255,255,255,0.2);
            color: #fff;
            border: 2px solid #fff;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 12px;
            transition: 0.3s;
            text-decoration: none !important;
            display: inline-block;
            margin-bottom: 20px;
        }
        .btn-back:hover { background: #fff; color: var(--gold-dark); transform: translateX(-5px); }

        .panel-info { 
            background: #ffffff !important; 
            border-radius: 25px !important; 
            box-shadow: var(--panel-shadow) !important; 
            overflow: hidden; 
            border:none; 
        }
        
        .panel-info > .panel-heading { 
            background: #fff !important; 
            color: var(--gold-dark) !important; 
            font-weight: 800 !important; 
            text-align: center; 
            padding: 20px; 
            border-bottom: 1px solid #eee; 
            font-size: 20px;
        }
        
        .btn-info { 
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%) !important; 
            border: none !important; 
            border-radius: 25px !important; 
            padding: 15px !important; 
            font-weight: 800 !important; 
            text-transform: uppercase; 
            width: 100%; 
            margin-top: 20px; 
            box-shadow: 0 8px 20px rgba(0,0,0,0.2) !important; 
            transition: 0.3s;
            color: #fff;
        }
        .btn-info:hover { transform: translateY(-5px) !important; box-shadow: 0 12px 25px rgba(0,0,0,0.3) !important; }
        
        /* ===== 4. SPECIAL UI ELEMENTS ===== */
        .fine-box { 
            background: #fff8eb; 
            border: 2px dashed var(--gold-primary); 
            padding: 20px; 
            border-radius: 12px; 
            text-align: center; 
        }
        .fine-value { font-size: 32px; font-weight: 800; color: #c0392b; }
        
        .form-control { 
            border-radius: 10px; 
            border: 2px solid #eee; 
            height: 45px; 
            transition: 0.3s;
        }
        .form-control:focus { border-color: var(--gold-primary); box-shadow: none; }

        .footer-section {
            background: #111 !important; 
            color: #ffffff !important; 
            padding: 25px 0; 
            border-top: 5px solid var(--gold-primary) !important;
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
                    <a href="manage-issued-books.php" class="btn-back"><i class="fa fa-arrow-left"></i> Back to records</a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-info">
                        <div class="panel-heading">RETURN PROCESSING</div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <?php 
                                $rid=intval($_GET['rid']);
                                $sql = "SELECT tblstudents.FullName, tblbooks.BookName, tblbooks.id as bid,
                                               tblissuedbookdetails.IssuesDate, tblissuedbookdetails.RetrunStatus 
                                        FROM tblissuedbookdetails 
                                        JOIN tblstudents ON tblstudents.StudentId=tblissuedbookdetails.StudentId 
                                        JOIN tblbooks ON tblbooks.id=tblissuedbookdetails.BookId 
                                        WHERE tblissuedbookdetails.id=:rid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':rid', $rid, PDO::PARAM_INT);
                                $query->execute();
                                $result=$query->fetch(PDO::FETCH_OBJ);

                                if($query->rowCount() > 0) {
                                    $issueDate = new DateTime($result->IssuesDate);
                                    $today = new DateTime();
                                    $daysKept = $issueDate->diff($today)->days;
                                    $allowedDays = 15;
                                    $overdueDays = ($daysKept > $allowedDays) ? ($daysKept - $allowedDays) : 0;
                                ?>
                                    
                                    <input type="hidden" name="bookid" value="<?php echo htmlentities($result->bid);?>">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4 style="color:var(--gold-dark); font-weight:800;"><i class="fa fa-user"></i> <?php echo htmlentities($result->FullName);?></h4>
                                            <p><b>Book:</b> <?php echo htmlentities($result->BookName);?></p>
                                            <p><b>Issued On:</b> <?php echo htmlentities($result->IssuesDate);?></p>
                                            <hr>
                                            <div class="form-group">
                                                <label>Set Fine Rate (INR)</label>
                                                <select class="form-control" id="fineRate" onchange="calculateFine()">
                                                    <option value="0">Free (₹0/day)</option>
                                                    <option value="2">₹2 per day</option>
                                                    <option value="5" selected>₹5 per day</option>
                                                    <option value="10">₹10 per day</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="fine-box">
                                                <label>System Calculated Fine</label><br>
                                                <span class="fine-value" id="displayFine">₹<?php echo ($overdueDays * 5); ?></span>
                                                <p style="margin-top:10px;">
                                                    <span class="label label-danger" style="font-size: 12px; border-radius: 8px;"><?php echo $overdueDays; ?> Overdue Days</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top:20px;">
                                        <label>Final Fine to Charge (Adjustable)</label>
                                        <input class="form-control" type="number" name="fine" id="finalFine" value="<?php echo ($overdueDays * 5); ?>" required />
                                    </div>

                                    <?php if($result->RetrunStatus == 0): ?>
                                        <button type="submit" name="return" class="btn btn-info">Complete Return</button>
                                    <?php else: ?>
                                        <div class="alert alert-success" style="border-radius:15px; text-align:center; font-weight:700; margin-top:20px;">
                                            <i class="fa fa-check-circle"></i> This book has already been returned.
                                        </div>
                                    <?php endif; ?>

                                <?php } ?>
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
    
    <script>
    function calculateFine() {
        var overdueDays = <?php echo isset($overdueDays) ? $overdueDays : 0; ?>;
        var rate = document.getElementById('fineRate').value;
        var total = overdueDays * rate;
        
        document.getElementById('displayFine').innerHTML = "₹" + total;
        document.getElementById('finalFine').value = total;
    }
    </script>
</body>
</html>
<?php } ?>