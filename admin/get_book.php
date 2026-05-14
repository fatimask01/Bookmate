<?php 
require_once("includes/config.php");

if(!empty($_POST["bookid"])) {
    $bookid = $_POST["bookid"];

    // Query to get book details and calculate availability
    $sql = "SELECT 
                tblbooks.BookName, 
                tblcategory.CategoryName, 
                tblauthors.AuthorName, 
                tblbooks.ISBNNumber, 
                tblbooks.BookPrice, 
                tblbooks.id as bookid, 
                tblbooks.bookImage, 
                tblbooks.bookQty,   
                COUNT(tblissuedbookdetails.id) AS totalIssued,
                COUNT(CASE WHEN tblissuedbookdetails.RetrunStatus IS NOT NULL AND tblissuedbookdetails.RetrunStatus != '' THEN 1 END) AS totalReturned
            FROM tblbooks
            LEFT JOIN tblissuedbookdetails ON tblissuedbookdetails.BookId = tblbooks.id
            LEFT JOIN tblauthors ON tblauthors.id = tblbooks.AuthorId
            LEFT JOIN tblcategory ON tblcategory.id = tblbooks.CatId
            WHERE (tblbooks.ISBNNumber = :bookid OR tblbooks.BookName LIKE :bookname) 
            GROUP BY tblbooks.id";

    $query = $dbh->prepare($sql);
    $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
    $bookSearch = "%$bookid%";
    $query->bindParam(':bookname', $bookSearch, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() > 0) {
        echo '<div class="row" style="margin-top:20px;">';
        foreach ($results as $result) {
            // Calculate Availability
            $totalIssuedActive = $result->totalIssued - $result->totalReturned;
            $availableQty = $result->bookQty - $totalIssuedActive;
    ?>
            <div class="col-md-4 text-center" style="border: 1px solid #eee; padding: 15px; border-radius: 10px;">
                <img src="bookimg/<?php echo htmlentities($result->bookImage); ?>" width="100" style="border-radius:5px; margin-bottom:10px;">
                <p><strong><?php echo htmlentities($result->BookName); ?></strong><br />
                <small><?php echo htmlentities($result->AuthorName); ?></small></p>
                
                <p style="font-size: 12px;">
                    Stock: <?php echo htmlentities($result->bookQty); ?> | 
                    Available: <span class="badge"><?php echo $availableQty; ?></span>
                </p>

                <?php if($availableQty <= 0): ?>
                    <p style="color:red; font-weight:bold;">Out of Stock</p>
                <?php else: ?>
                    <label class="radio-inline">
                        <input type="radio" name="bookid" value="<?php echo htmlentities($result->bookid); ?>" required> Select Book
                    </label>
                    <input type="hidden" name="aqty" value="<?php echo $availableQty; ?>">
                <?php endif; ?>
            </div>
    <?php 
        }
        echo '</div>';
        echo "<script>$('#submit').prop('disabled',false);</script>";
    } else {
        echo '<p style="color:red;">Record not found. Please try again.</p>';
        echo "<script>$('#submit').prop('disabled',true);</script>";
    }
}
?>