<?php
session_start();
$server="127.0.0.1";
$user="root";
$pass="";
$db="complaint_portal";
$error = "";

$con=mysqli_connect($server, $user, $pass, $db);

if(!$con) {
  die("faired to connect to database:".mysqli_connect_error());
}
//for handling firters
$where=" ";
if(isset($_GET['category']) && $_GET['category'] !=='') {
  $cat = intval($_GET['category']);
  $where .= " AND c.category_id = $cat";
}
if (isset($_GET['status']) && $_GET['status'] !== '') {
  $status = mysqli_real_escape_string($con, $_GET['status']);
  $where .= " AND c.status = '$status' ";
}
//for categories for filter
$categories_result = mysqli_query($con, "SELECT * FROM categories");
$query = "SELECT c.*, cat.name as category_name, it.issue_title FROM complaints c 
          LEFT JOIN categories cat ON c.category_id = cat.id 
          LEFT JOIN issue_templates it ON c.issue_template_id = it.id
          WHERE c.is_active = 1 $where
          ORDER BY c.created_at DESC";
$result = mysqli_query($con, $query);
if(!$result) {
  die("our query failed:".mysqli_error($con));
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="veiwpoint" content ="width=device-width, initial-scale=1"/>
    <title>admin dashboard complaint</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css" />
    <style>
      body{
        max-width: 1200px;
        margin:20px, auto;
        padding: 0 15px;
      }
      h1{
        text-align: center;
        margin-bottom: 1.5rem; 
        color: #2a2a86;
      }
      form.filters{
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 20px;
        align-items: flex-end;
        justify-content: center;
      }
      form.filters label {
        display: flex;
        flex-direction: column;
        font-weight: 600;
        font-size:0.9rem;
      }
      table{
        border-collapse: collapse;
        width: 100%;
        font-size 0.9rem;
      }
      table th, table td {
        padding: 8px 10px;
        vertical-align: top;
        text-align:left;
        border: 1px solid $ddd;
      }
      table th {
        background-color: #2a2a86;
        color: white;
        font-weight: 700;
      }
      textarea{
        resize:vertical;
        font-family: inherit;
        font-size: 0.9rem;
      }
      button[type="submit"]{
        background-color: #2a2a86;
        color:white;
        padding: 6px 12px;
        border:none;
        cursor:pionter;
        border-radius: 4px;
        font-weight: 600;
      }
      button[type="submit"]:hover {
        background-color: #1a1a5c;
      }
      .no-data {
        text-align: center;
        font-style: italic;
        color:#555;
        padding: 20px;
      }

    </style>
  </head>
  <body>
    <a href="logout.php" style="color:red;">Logout</a>
    <h1> All Submited complaint</h1>
    <form method="GET" class="filters">
      <label>
        Filter by category:
        <select name="category">
          <option value="">All</option>
          <?php while ($cat = mysqli_fetch_assoc($categories_result)): ?>
            <option value="<?php ech &cat['id']; ?>" <?php if(isset($_GET['category']) && $_GET['category'] == $cat['id']) echo 'selected'; ?>>
              <?php echo  htmlspecialchars($cat['name']); ?>
          </option>
          <?php endwhile; ?>
        </select>
      </label>
      <label>
        Filter by status:
        <select name="status">
          <option value="">All</option>
          <?php
          $statusOptions = ['pending', 'In progress', 'Resolved'];
          foreach ($statusOptions as $option) {
            $selected = (isset($_GET['status']) && $_GET['status'] === $option) ? 'selected' : '';
            echo "<option value=\"$option\" $selected>$option</option>";
          }
          ?>
        </select>
        </label> 
        <button type="submit">Apply Filters</button>
    </form>
  <table>
    <thead>
      <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Email</th>
        <th>Title</th>
        <th>Category</th>
        <th>Issue Description</th>
        <th>District</th>
        <th>Sector</th>
        <th>Location</th>
        <th>Status</th>
        <th>Submitted at</th>
        <th>Update Status & Response</th>
      </tr>
    </thead>
    <tbody>
      <?php if(mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td> <?php echo htmlspecialchars($row['name']); ?></td>
            <td> <?php echo htmlspecialchars($row['email']); ?></td>
            <td> <?php echo htmlspecialchars($row['title']); ?></td>
            <td> <?php echo htmlspecialchars($row['category_name']); ?></td>
            <td> <?php echo htmlspecialchars($row['issue_title']); ?></td>
            <td> <?php echo htmlspecialchars($row['district']); ?></td>
            <td> <?php echo htmlspecialchars($row['sector']); ?></td>
            <td> <?php echo htmlspecialchars($row['location']); ?></td>
            <td> <?php echo htmlspecialchars($row['status']); ?></td>
            <td> <?php echo htmlspecialchars($row['created_at']); ?></td>
            <td>
              <form method="post" action="update_complaint.php" style="display:flex; flex-direction: column; gap: 8px; max-width: 250px;">
                <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                <select name="status" required>
                  <?php
                  foreach ($statusOptions as $statusOption) {
                    $selected = ($row['status'] === $statusOption) ? 'selected' : '';
                    echo "<option value=\"$statusOption\" $selected>$statusOption</option>";
                  }
                  ?>
                </select>
                <textarea name="admin_response" rows="3" placeholder="provide feedback...."><?php echo isset($row['admin_response']) ? htmlspecialchars($row['admin_response']) : ''; ?></textarea>
                <button type="submit">UPDATE</button>
              </form>
          </tr>
          <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="12" class="no-data">  No complaints found.</td></tr>
          <?php endif; ?>
    </tbody>
  </table>

</body>
</html>

<?php mysqli_close($con); ?>
