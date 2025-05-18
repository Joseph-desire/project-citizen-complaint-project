<?php
session_start();
$server = "127.0.0.1";
$user = "root";
$pass = "";
$db = "complaint_portal";
//connect to the database
$con = mysqli_connect($server, $user, $pass, $db);
if(!$con){
    die("connection failed: ". mysqli_connect_error());
}
//for fetch categories and issue templates
$categories = mysqli_query($con, "SELECT * FROM categories");
$templates = mysqli_query($con, "SELECT * FROM issue_templates");

$issues_by_category = [];
while ($row = mysqli_fetch_assoc($templates)){
    $cat_id = (string)$row['category_id'];
    $issues_by_category[$cat_id][] = $row;
}

//For handle form sibmission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$name = trim($_POST['name']);
$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
$title = trim($_POST['title']);
$district = trim($_POST['district']);
$sector = trim($_POST['sector']);
$location = trim($_POST['location']);
$category_id = intval($_POST['category']);
$issue_template_id = isset($_POST['issue_template']) ? intval($_POST['issue_template']) : null;

if (!$name || !$email || !$title || !$district || !$sector || !$location || !$category_id) {
    echo "<p style='text-align:center; color:red;'>Error: Please fill all required fields correctly.</p>";
}else{
    $stmt = $con->prepare("INSERT INTO complaints (name, email, title, category_id, issue_template_id, district, sector, location, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("sssiiiss",$name, $email, $title, $category_id, $issue_template_id, $district, $sector, $location);

if ($stmt->execute()) {
    echo "<p style='text-align:center; color:green;'>Complaintr submitted successfully! Your ticket is pending review.</p>";

}
else{
    echo "<p style='text-align:center; color:red;'>Error submitting complaint: " . $stmt->error . "</p>";
}
$stmt->close();

}
mysqli_close($con);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Submit Your Complaint</title>
    <script>
        const issues = <?php echo json_encode($issues_by_category); ?>;
        console.log("Loaded issue templates by category:", issues); // Debug line

        function updateIssueDropdown() {
            const categoryId = document.getElementById('category').value;
            const issueSelect = document.getElementById('issue_template');
            issueSelect.innerHTML = '<option value="">Select a specific issue</option>';

            if (issues[categoryId]) {
                issues[categoryId].forEach(issue => {
                    const option = document.createElement('option');
                    option.value = issue.id;
                    option.text = issue.issue_title;
                    issueSelect.appendChild(option);
                });
            }
        }
    </script> 
        <style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(to right, #0072ff, #00c6ff);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 40px 20px;
        flex-wrap: wrap;
        gap: 30px;
    }

    .whole {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        max-width: 1200px;
        width: 100%;
        justify-content: center;
    }

    .column {
        display: flex;
        flex-direction: column;
        gap: 30px;
        flex: 1;
        min-width: 300px;
        max-width: 450px;
    }

    .form-design, .imergenc-calls, .desire {
        background: #fff;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .form-design:hover,
    .desire:hover,
    .imergenc-calls:hover {
        transform: translateY(-5px);
    }

    h1, h2 {
        text-align: center;
        color: #0072ff;
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
        display: block;
        margin-top: 12px;
    }

    input, select, textarea {
        width: 100%;
        padding: 10px;
        margin-top: 6px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 15px;
        background-color: #f9f9f9;
    }

    textarea {
        resize: vertical;
        min-height: 80px;
    }

    button {
        background-color: #0072ff;
        color: white;
        border: none;
        width: 100%;
        border-radius: 6px;
        font-size: 16px;
        margin-top: 20px;
        padding: 12px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #005ac1;
    }

    a {
        display: block;
        text-align: center;
        margin-top: 1rem;
        color: #0072ff;
        text-decoration: none;
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline;
    }

    .imergenc-calls p,
    .imergenc-calls a {
        font-size: 1.1rem;
        margin: 10px 0;
        color: #b71c1c;
        text-decoration: none;
    }

    .imergenc-calls a:hover {
        text-decoration: underline;
    }

    .idea {
        font-style: italic;
        margin-top: 10px;
        color: #555;
        text-align: center;
    }

    @media (max-width: 768px) {
        body {
            flex-direction: column;
            align-items: stretch;
        }

        .form-design {
            max-width: 100%;
        }
    }
header {
  background-color:rgba(61, 204, 0, 0.99);
  color: white;
  padding: 2rem 1rem;
  text-align: center;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
header h1 {
  margin: 0;
  font-size: 2.5rem;
  letter-spacing: 2px;
  
}
.imergenc-calls table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

.imergenc-calls th, .imergenc-calls td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ccc;
}

.imergenc-calls a {
  color: #b71c1c;
  text-decoration: none;
  font-weight: bold;
}

.imergenc-calls a:hover {
  text-decoration: underline;
}


</style>

</head>
<body>
      <header>
    <h1>Citizen Complaints and Engagement System</h1>
  </header>
<div class="whole">
    <div class="column">
    <div class="desire">
        <h2>Tell Us Your Desire</h2>
<form action="submit_desire.php" method="POST">
  <label for="nme">Your Names</label>
  <input type="text" name="nme" required>

  <label for="location">Location</label>
  <input type="text" name="location" required placeholder="e.g. Kicukiro, Kigali">

  <label for="desiree">Your Long-Term Desire or Suggestion</label>
  <textarea id="desiree" name="desiree" required placeholder="Describe what you need, hope for, or suggest..."></textarea>

  <button type="submit">SUBMIT</button>
</form>
        <p class="idea"> suggest you recommendations here to foster community development </p>
    </div>
<div class="imergenc-calls">
  <h2>Emergency Contacts</h2>
  <p>Call for urgent issues</p>
  
  <table style="width: 100%; border-collapse: collapse;">
    <thead>
      <tr>
        <th style="text-align: left; padding: 8px;">Service</th>
        <th style="text-align: left; padding: 8px;">Contact</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="padding: 8px;">Local Police</td>
        <td style="padding: 8px;"><a href="tel:112">112</a></td>
      </tr>
      <tr>
        <td style="padding: 8px;">Health Emergencies</td>
        <td style="padding: 8px;"><a href="tel:0787439625">0787439625</a></td>
      </tr>
      <tr>
        <td style="padding: 8px;">Fire</td>
        <td style="padding: 8px;"><a href="tel:9789506443">9789506443</a></td>
      </tr>
      <tr>
        <td style="padding: 8px;">Public Services</td>
        <td style="padding: 8px;"><a href="tel:0785166856">0785166856</a></td>
      </tr>
    </tbody>
  </table>
</div>

            </div>
    <div class="form-design">
<h1>Tell us your complaint.</h1>
<form action="" method="POST">
    <label for="name">Full Name</label>
    <input type="text" name="name" required>

    <label for="email">Email Address</label>
    <input type="email" name="email" required>

    <label for="title">Complaint Title *</label>
    <input type="text" name="title" required>

    <label for="district">District</label>
    <select id="district" name="district" required>
        <option value="" disabled selected>Select your district</option>
        <option value="Gasabo">Gasabo</option>
        <option value="Kicukiro">Kicukiro</option>
        <option value="Nyarugenge">Nyarugenge</option>
        <option value="Kayonza">Kayonza</option>
        <option value="Kirehe">Kirehe</option>
        <option value="Ngoma">Ngoma</option>
        <option value="Gatsibo">Gatsibo</option>
        <option value="Rwamagana">Rwamagana</option>
        <option value="Bugesera">Bugesera</option>
        <option value="Nyagatare">Nyagatare</option>
        <option value="Gicumbi">Gicumbi</option>
        <option value="Musanze">Musanze</option>
        <option value="Burera">Burera</option>
        <option value="Rulindo">Rulindo</option>
        <option value="Nyabihu">Nyabihu</option>
        <option value="Rubavu">Rubavu</option>
        <option value="Ngororero">Ngororero</option>
        <option value="Karongi">Karongi</option>
        <option value="Nyamasheke">Nyamasheke</option>
        <option value="Rusizi">Rusizi</option>
        <option value="Nyamagabe">Nyamagabe</option>
        <option value="Nyaruguru">Nyaruguru</option>
    </select>

    <label for="sector">Sector</label>
    <input type="text" name="sector" required>

    <label for="location">Location</label>
    <input type="text" name="location" required>

    <label for="category">Complaint Category</label>
    <select name="category" id="category" required onchange="updateIssueDropdown()">
        <option value="" disabled selected>Select a category</option>
        <?php mysqli_data_seek($categories, 0); while ($cat = mysqli_fetch_assoc($categories)): ?>
            <option value="<?php echo (string)$cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
        <?php endwhile; ?>
    </select>

    <label for="issue_template">Issue Description</label>
    <select name="issue_template" id="issue_template">
        <option value="">Select a specific issue</option>
    </select>

    <button type="submit">Submit Complaint</button>
    <a href="user_dashboard.php">View your feedback</a>
</form>
        </div>
        </div>
</body>
</html>
