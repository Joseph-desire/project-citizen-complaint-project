<? php
$server="127.0.0.1";
$user="root"
$pass="";
$db="complaint_portal";

$con = mysqli_connect($server, $user, $pass, $db);

//  for checking connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get and sanitize input
$name = mysqli_real_escape_string($conn, $_POST['nme']);
$location = mysqli_real_escape_string($conn, $_POST['location']);
$desire = mysqli_real_escape_string($conn, $_POST['desiree']);

// Insert into database
$sql = "INSERT INTO desires (name, location, desire) VALUES ('$name', '$location', '$desire')";

if (mysqli_query($conn, $sql)) {
    echo "Your desire has been submitted. Thank you!";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
