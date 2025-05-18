<?php
session_start();

$email = $_SESSION['email'];
$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';


$server = "127.0.0.1";
$user = "root";
$pass = "";
$db = "complaint_portal"; 


$con = mysqli_connect($server,$user,$pass,$db);

if (!$con){
 die("connection failed:" . mysqli_connect_error());
}
$query = "SELECT c.*,cat.name as category_name,it.issue_title
FROM complaints c
LEFT JOIN categories cat ON c.category_id=cat.id
LEFT JOIN issue_templates it ON c.issue_template_id=it.id
WHERE c.email='$email'
ORDER BY c.created_at DESC";

$result = mysqli_query($con, $query);

if(!$result) {
    die("query failed:".mysqli_error($con));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Complaints</title>
    <link rel ="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($name); ?></h2>
    <a href="logout.php" style="color:red;">Logout</a>
    <h3>My Submitted Complaints and Feedback</h3>

    <table border="1" width="100">
        <thead>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Description</th>
            <th>Status</th>
            <th>Admin Feedback</th>
            <th>Submitted At</th>
</tr>
</thead>
<tbody>
<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
        echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['issue_title']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td>" . (!empty($row['admin_response']) ? htmlspecialchars($row['admin_response']) : 'No response yet') . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' style='text-align:center;'>No complaints found.</td></tr>";
}
?>

            </tbody>
            </table>
        </body>
    </html>    
 