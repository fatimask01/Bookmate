<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Project Video</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa, #e4ecf7);
            font-family: 'Segoe UI', sans-serif;
        }

        .video-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-top: 40px;
        }

        .page-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .back-btn {
            border-radius: 30px;
            padding: 8px 20px;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .back-btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        video {
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>

<body>

<div class="container">
    <div class="video-container">

        <h2 class="text-center page-title">
            Library Management System Video
        </h2>

        <!-- Back Button Top Left -->
        <div style="margin-bottom:20px;">
            <a href="index.php" class="btn btn-primary back-btn">
                ← Back to Home
            </a>
        </div>

        <div class="text-center">
            <video width="85%" controls>
                <source src="assets/help/video.mp4" type="video/mp4">
            </video>
        </div>

    </div>
</div>

</body>

</body>
</html>