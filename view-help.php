<?php
session_start();
error_reporting(0);
include('includes/config.php');

// LOGIC: Determine which PDF to show based on the URL parameter
$view = isset($_GET['view']) ? $_GET['view'] : 'intro';

if ($view == 'doc') {
    $pdfFile = "blackbook.pdf";
    $pageTitle = "PROJECT DOCUMENTATION";
} else {
    $pdfFile = "presentation_final.pdf";
    $pageTitle = "TEAM INTRODUCTION";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Library | <?php echo $pageTitle; ?></title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Poppins:400,600,700' rel='stylesheet' type='text/css' />
    
    <style>
        /* Variables based on your Admin Dashboard requirements */
        :root {
            --panel-shadow: 0 10px 30px rgba(0,0,0,0.25), 0 15px 45px rgba(0,0,0,0.18);
            --gold-primary: rgb(225, 161, 25);
            --gold-dark: #8c6411;
        }

        /* 1. GLOBAL THEME & BACKGROUND */
        html { 
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%) fixed !important; 
        }
        body { 
            margin: 0; 
            font-family: 'Poppins', sans-serif !important; 
            background: transparent !important; 
            min-height: 100vh; 
        }

        /* 2. NAVBAR: White background + 5px Golden Bottom Border */
        .navbar, .menu-section {
            background-color: #ffffff !important;
            background-image: none !important;
            border-bottom: none !important;
            box-shadow: 0 -5px 15px rgba(0,0,0,0.1), 0 2px 10px rgba(0,0,0,0.05) !important;
            min-height: 80px !important;
            display: flex !important;
            align-items: center !important;
            margin-bottom: 0 !important;
        }

        #menu-top li a, .navbar-nav li a {
            color: rgb(225, 161, 25) !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 14px !important;
            padding: 22px 25px !important; 
            background: transparent !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border-radius: 12px !important;
        }

        #menu-top li a:hover, .navbar-nav li a:hover {
            background-color: #8c6411 !important; 
            color: #ffffff !important; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
            transform: translateY(-1px);
        }

        /* 3. CONTENT AREA: PDF PANEL & SHADOWS */
        .page-header {
            color: #ffffff;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 3px solid var(--gold-dark);
            padding-bottom: 12px;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .pdf-container {
            margin: 30px 0;
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: var(--panel-shadow) !important; 
        }

        .pdf-viewer {
            width: 100%;
            height: 80vh;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .dropdown-menu {
            background-color: #ffffff !important;
            border-top: 4px solid #8c6411 !important;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
            border-radius: 0 0 10px 10px !important;
            padding: 0 !important;
        }

        .dropdown-menu > li > a {
            color: #8c6411 !important;
            padding: 15px 20px !important; 
            font-weight: 600 !important;
        }

        .dropdown-menu > li > a:hover {
            background-color: rgb(225, 161, 25) !important;
            color: #ffffff !important;
        }

        .home-footer {
            background: #111 !important; 
            color: #eee !important; 
            padding: 40px 0 !important;
            border-top: 5px solid var(--gold-primary) !important;
            text-align: center !important;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <?php include('includes/header.php');?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-header"><?php echo $pageTitle; ?></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="pdf-container">
                        <iframe src="<?php echo $pdfFile; ?>" class="pdf-viewer">
                            <p>Your browser does not support embedded PDFs. 
                               <a href="<?php echo $pdfFile; ?>">Download the PDF</a>
                            </p>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="home-footer">
        <div class="container">
            &copy; <?php echo date("Y"); ?> Online Library Management System | Built with Care
        </div>
    </footer>

    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
</body>
</html>