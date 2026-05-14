<div class="navbar navbar-inverse set-radius-zero">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="dashboard.php">
                <img src="assets/img/logo.png" alt="Library Logo" class="logo-img" />
            </a>
        </div>

        <div class="right-div">
            <a href="logout.php" class="btn btn-danger pull-right">LOG OUT</a>
        </div>
    </div>
</div>

<section class="menu-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="navbar-collapse collapse">
                    <ul id="menu-top" class="nav navbar-nav navbar-right">
                        <li><a href="dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'menu-top-active' : ''); ?>">DASHBOARD</a></li>
                        
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">CATEGORIES <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="add-category.php">Add Category</a></li>
                                <li><a href="manage-categories.php">Manage Categories</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">AUTHORS <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="add-author.php">Add Author</a></li>
                                <li><a href="manage-authors.php">Manage Authors</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">BOOKS <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="add-book.php">Add Book</a></li>
                                <li><a href="manage-books.php">Manage Books</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">ISSUE BOOKS <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="issue-book.php">Issue New Book</a></li>
                                <li><a href="manage-issued-books.php">Manage Issued Books</a></li>
                            </ul>
                        </li>

                        <li><a href="reg-students.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'reg-students.php' ? 'menu-top-active' : ''); ?>">REG STUDENTS</a></li>
                        <li><a href="change-password.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'change-password.php' ? 'menu-top-active' : ''); ?>">CHANGE PASSWORD</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>