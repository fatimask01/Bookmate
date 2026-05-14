<?php 
require_once("includes/config.php");

// 1. Ensure the script stops immediately if the email field is empty
if(!empty($_POST["emailid"])) {
    $email = trim($_POST["emailid"]);

    // 2. Validate email format first
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        echo "error : You did not enter a valid email.";
        exit; // Stop execution
    }
    else {
        // 3. OPTIMIZATION: Only select 1 column for existence check
        $sql = "SELECT EmailId FROM tblstudents WHERE EmailId=:email";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        // 4. Use rowCount() directly (No need for fetchAll)
        if($query->rowCount() > 0)
        {
            echo "<span style='color:red'> Email already exists.</span>";
            echo "<script>$('#submit').prop('disabled',true);</script>";
            exit;
        } 
        else {
            echo "<span style='color:green'> Email available for Registration.</span>";
            echo "<script>$('#submit').prop('disabled',false);</script>";
            exit;
        }
    }
}
?>