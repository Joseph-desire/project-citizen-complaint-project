<?php
$server="127.0.0.1";
$user="root";
$pass="";
$db="complaint_portal";
if($_SERVER['REQUEST_METHOD']=="POST") {
	$username=$_POST['username'];
	$email=$_POST['email'];
	$password=$_POST['password'];
	if(!empty($username) && !empty($email) && !empty($password)) {
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$con=mysqli_connect($server, $user, $pass, $db);
		if(!con){
			die("connection failed:".mysqli_connect_error());
		}
		$sql= "INSERT INTO users (username, email, password) VALUES('$username', '$email', '$hashedPassword')";
		if(mysqli_query($con, $sql)) {
			$success= "registration succesfully..wait  a bit dear";
			header("refresh:3; url=index.html");
		}
		else {
			$error= "Error".mysqli_error($con);
		}
		mysqli_close($con);
	}
	else {
		$error = "please fill the field first";
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>register into system</title>
	    <style>
        body{
            margin:0;
            padding:0;
			font-family:'segoe UI',Tahoma,Geneva,Verdana,sans-serif;
            background:linear-gradient(to right,rgb(0, 128, 255), #0072ff);
            display:flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .lgn-frm{

      background-color: white;
      padding: 2.5rem;
      border-radius: 12px;
      box-shadow: 0 8px 24px hsla(0, 0.00%, 0.00%, 0.07);
      width: 100%;
      max-width: 420px;
      text-align: center;
      animation: slideIn 0.5s ease-in-out;
    }
        @keyframes slideIn {
            form{
                transiform:translateY(20px);
                opacity: 0;
            }
            to{
                opacity: 1;
                transiform:translateY(0);
            }
            
        }
        .lgn-frm h2{
            margin-buttom:1.2rem;
            color: #0052cc;
            font-size:28px;
        }
        .lgn-frm input[type="text"],
        .lgn-frm input[type="password"],
        .lgn-frm input[type="email"]{
            width:100%;
            padding:12px;
            margin-bottom:1rem;
            border:1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }
        .lgn-frm input[type="submit"]{
            background-color: #0072ff;
            color: white;
            border:none;
            padding:12px;
            width: 100%;
            border-radius:6px;
            font-size :16px;
            cursor:16px;
            transition: background-color 0.3s ease;
        }
        .lgn-frm input[type="submit"]:hover{
            background-color:#005ac1;
        }
        .lgn-frm .message{
            margin-top: 1rem;
            font-size: 14px;
        }
        .lgn-frm .message.success{
            color:green;
        }
        .lgn-frm.message.error{
            color:red;
        }
        .login-link{
            margin-top: 1rem;
            font-size: 14px;
        }
        .login-link a{
            color:#0072ff;
            font-weight:bold;
            text-decoration:none;
        }
        .login-link a:hover{
            text-decoration:underline
        }

    </style>

</head>
<body>
<div >
	<div class="lgn-frm">
		<h2>REGISTER </h2>
	    <form method="post" action="register.php">
	    	<input type="text" name="username" placeholder="username" required><br>
	    	<input type="text" name="email" placeholder="email" required><br>
	    	<input type="password" name="password" placeholder="password" required><br>

	    	<input type="submit" name="register" value="Register">
	    </form>

	<?php if (!empty($success)): ?>
            <p class="message success"><?php echo $success; ?></p>
        <?php elseif (!empty($error)): ?>
            <p class="message error"><?php echo $error; ?></p>
        <?php endif; ?>

        <div class="login-link">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>