<?php
session_start();
$server="127.0.0.1";
$user="root";
$pass="";
$db = "complaint_portal";

$error="";
if($_SERVER['REQUEST_METHOD']=='POST'){
    $email=$_POST['email'];
    $password=$_POST['password'];
         //to chech if data is well shifted to POST 
         if(!empty($email) && !empty($password)){
            //let us check connection if is well working
            $con=mysqli_connect($server, $user, $pass, $db);
            if(!$con){
                die("failed to connect to database:".mysqli_connect_error());
            }
            //otherwise>> if connn is well working
            $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
            $result = mysqli_query($con, $sql);
            if($result && mysqli_num_rows($result) > 0){
                $userData = mysqli_fetch_assoc($result);
                if(password_verify($password, $userData['password'])){
                    $_SESSION['user_id'] = $userData['id'];
                    $_SESSION['email'] = $userData['email'];

                    header("location:submit_complaint.php");
                    exit;
                }
                else{
                    $error="no account found with that email";

                }
                mysqli_close($con);

            }
            else{
                $error = "no accunt found with your enetrd email";

            }
         }
         else{
            $error="please enter both email and password";
         }
}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
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
        .form-design{

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
        .form-design h2{
            margin-buttom:1.2rem;
            color: #0052cc;
            font-size:28px;
        }
        .form-design input[type="text"],
        .form-design input[type="password"],
        .form-design input[type="email"]{
            width:100%;
            padding:12px;
            margin-bottom:1rem;
            border:1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }
        .form-design input[type="submit"]{
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
        .form-design input[type="submit"]:hover{
            background-color:#005ac1;
        }
        .form-design .message{
            margin-top: 1rem;
            font-size: 14px;
        }
        .form-design .message.success{
            color:green;
        }
        .form-design .message.error{
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
	    <div class="form-design">
        <h2>Login</h2>
        <form method="post" action="login.php">
            <input type="text" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" value="Login">
        </form>

        <?php if ($error): ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
        <?php endif; ?>
        <div class="login-link">register here plaese? <a href="register.php">Register</a>
    </div>
    </div>


</body>
</html>
