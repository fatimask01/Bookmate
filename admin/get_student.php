<?php 
require_once("includes/config.php");

if(!empty($_POST["studentid"])) {
    $studentid = strtoupper($_POST["studentid"]);
    
    $sql = "SELECT FullName, Status, MobileNumber FROM tblstudents WHERE StudentId = :studentid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() > 0) {
        foreach ($results as $result) {
            if($result->Status == 0) {
                // Case: Student is Blocked
                echo "<span style='color:red; font-weight:bold;'> <i class='fa fa-times-circle'></i> Student ID Blocked</span><br />";
                echo "<b>Name:</b> " . htmlentities($result->FullName);
                echo "<script>$('#submit').prop('disabled', true);</script>";
            } else {
                // Case: Student is Valid & Active
                echo "<span style='color:green; font-weight:bold;'> <i class='fa fa-check-circle'></i> Valid Student</span><br />";
                echo "<b>Name:</b> " . htmlentities($result->FullName) . "<br />";
                echo "<b>Mobile:</b> " . htmlentities($result->MobileNumber);
                echo "<script>$('#submit').prop('disabled', false);</script>";
            }
        }
    } else {
        // Case: ID not found
        echo "<span style='color:red;'> <i class='fa fa-warning'></i> Invalid Student ID. Please try again.</span>";
        echo "<script>$('#submit').prop('disabled', true);</script>";
    }
}
?>