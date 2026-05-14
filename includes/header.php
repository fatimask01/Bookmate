<div class="navbar navbar-inverse set-radius-zero">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand">
                <img src="assets/img/logo.png" alt="Library Logo" class="logo-img">
            </a>
        </div>
    </div>
</div>

<section class="menu-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="navbar-collapse collapse">
                    <ul id="menu-top" class="nav navbar-nav navbar-right">
                        
                        <?php if (isset($_SESSION['login']) && $_SESSION['login']) { ?>
                            <li><a href="dashboard.php">DASHBOARD</a></li>
                            <li><a href="listed-books.php">Explore Books</a></li>
                            <li><a href="issued-books.php">Issued Books</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">Account <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a href="my-profile.php">My Profile</a></li>
                                    <li><a href="change-password.php">Change Password</a></li>
                                    <li class="divider"></li>
                                    <li><a href="logout.php" style="color:#f5576c !important;"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </li>
                        <?php } else { ?>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="signup.php">User Signup</a></li>
                        <?php } ?>

                        <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">HELP <b class="caret"></b></a>
    <ul class="dropdown-menu">
        <li>
            <a href="view-help.php?view=intro">
                <i class="fa fa-file-pdf-o"></i> Introduction
            </a>
        </li>
        <li>
            <a href="assets/videos/user-guide.mp4" target="_blank">
                <i class="fa fa-video-camera"></i> User Guide
            </a>
        </li>
        <li>
            <a href="view-help.php?view=doc">
                <i class="fa fa-file-text-o"></i> Documentation
            </a>
        </li>
    </ul>
</li>
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>