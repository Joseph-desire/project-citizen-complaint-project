<?php
// update_complaint.php
session_start();

$server = "127.0.0.1";
$user = "root";
$pass = "";
$db = "complaint_portal";

$con = mysqli_connect($server, $user, $pass, $db);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaint_id = intval($_POST['complaint_id']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $admin_response = mysqli_real_escape_string($con, $_POST['admin_response']);

    $query = "UPDATE complaints SET status = '$status', admin_response = '$admin_response', is_active = 0 WHERE id = $complaint_id";

    if (mysqli_query($con, $query)) {
        // Redirect back to dashboard after update
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating complaint: " . mysqli_error($con);
    }
} else {
    echo "Invalid request";
}

mysqli_close($con);
?>
