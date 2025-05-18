<? php
$server="127.0.0.1";
$user="root"
$pass="";
$db="complaint_portal";

$con = mysqli_connect($server, $user, $pass, $db);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

?>