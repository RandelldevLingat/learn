

<?php
    include('dbconn.php');
    session_start();

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if username and password are set in $_POST
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Your SQL query and login logic here
            $query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username' AND password='$password'") or die(mysqli_error($conn));
            $count = mysqli_num_rows($query);
            $row = mysqli_fetch_array($query);

            if ($count > 0) {
                $_SESSION['id'] = $row['user_id'];
                // Redirect to post and comment page
                header('Location: home.php');
                exit; // Make sure to stop the script execution after redirection
            } else {
				header('Location: login.php?error=1');
				exit;
            }
        } else {
            // Handle case where username or password is not set
			header('Location: login.php?error=1');
            exit;
        }
    } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" type="text/css" href="style.css"/>
	<style>
		
		/* LOGIN FORM CSS */
		* {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        @font-face {
            font-family: "Proxima-nova";
            src:url("Mark\ Simonson\ \ Proxima\ Nova\ Regular.otf") ;
        }

		body{
		margin: 0;
		padding: 0;
		height: 200vh;
		font-family: "Proxima-nova";
		}
	
		.center{
			max-width: 400px;
			margin: 2em auto;
			background: white;
			border-radius: 10px;
			box-shadow: 10px 10px 15px rgba(0,0,0,0.1);
			background-color: rgb(243 244 246);
			min-height: 30em;
		}
		.center h1{
		text-align: center;
		padding: 20px 0;
		border-bottom: 1px solid silver;
		}
		.center form{
		padding: 0 40px;
		box-sizing: border-box;
		}
		form .txt_field{
		position: relative;
		border-bottom: 2px solid #adadad;
		margin: 30px 0;
		}
		.txt_field input{
		width: 100%;
		padding: 0 5px;
		height: 40px;
		font-size: 16px;
		border: none;
		background: none;
		outline: none;
		transition: all ease .3s;
		}
		.txt_field label{
		position: absolute;
		top: 50%;
		left: 5px;
		color: #35408E;
		transform: translateY(-50%);
		font-size: 16px;
		pointer-events: none;
		transition:all ease .3s;
		}
		.txt_field span::before{
		content: '';
		position: absolute;
		top: 40px;
		left: 0;
		width: 0%;
		height: 2px;
		background: #2691d9;
		transition: .5s;
		}
		.txt_field input:focus ~ label,
		.txt_field input:valid ~ label{
		top: -5px;
		color: #35408E;
		}
		.txt_field input:focus ~ span::before,
		.txt_field input:valid ~ span::before{
		width: 100%;
		}
		.pass{
		color: #35408E;
		cursor: pointer;
		}
		.pass:hover{
		text-decoration: underline;
		}
		button[type="submit"]{
		width: 100%;
		height: 50px;
		border: 1px solid;
		background: #2691d9;
		border-radius: 25px;
		font-size: 18px;
		color: #e9f4fb;
		font-weight: 700;
		cursor: pointer;
		outline: none;
		}
		button[type="submit"]:hover{
		border-color: #2691d9;
		transition: .5s;
		}
		.signup_link{
		margin: 30px 0;
		text-align: center;
		font-size: 16px;
		color: #666666;
		}
		.signup_link a{
		color: #2691d9;
		text-decoration: none;
		}
		.signup_link a:hover{
		text-decoration: underline;
		}

		.remember-container{
			display: flex;
			align-items: center;
			justify-content: space-evenly;
		}
		.remember-container >*{
			margin: 1em .5em;
		}
		.description-container{
			text-align: center;
			margin: 2em 0;
		}
		.description-container h1{
			font-size: 2.5em;
		}


	</style>
</head>
<body>
   <!-- Navigation-bar -->
   <nav>
        <label id="logo">
          <img id="logos" src="images/logo.png">
        </label>
        <div class="box">
          <i class="fa fa-search" aria-hidden="true"></i>
          <input type="text" name="" placeholder="Search here for answers to any question...">
        </div>
        <div class="links-container">
          <a class="home-link" href="index.html">Home</a> <!-- link back to homepage -->
          <a href="post.html">Ask Question</a> 
          <a href="products.html">Join For Free</a>
        </div>
      </nav>
	  
<!-- LOGIN FORM -->
<div class="description-container">
	<h1 class="header-title">Welcome Back</h1>
	<p class="description">Get answers to your wonder and finish your work faster</p>
</div>
<div class="center">
      <h1>Login</h1>
      <form id="login_form"  method="post">
        <div class="txt_field">
		<input type="text"  id="username" name="username" placeholder="Username" required>
		<span></span>
		<label for="">Username</label>
        </div>

        <div class="txt_field">
		<input type="password" id="password" name="password" placeholder="Password" required>
		<span></span>
		<label for="">Password</label>
        </div>
		<div class="remember-container">
		<label>
			<input type="checkbox">
			Remember me
		</label>
        <div class="pass">Forgot Password?</div>
		</div>
        <!-- <input type="submit" value="Login"> -->
		<button name="login" type="submit">Sign in</button>
        <div class="signup_link">
          Don't have an account? <a href="index.php">Signup</a>
        </div>
      </form>
    </div>


<!-- LOGIN FORM  -->
	<!-- <form id="login_form"  method="post">
			<h3>Please Login</h3>
			<label for="">Username</label>
			<input type="text"  id="username" name="username" placeholder="Username" required>
			<label for="">Password</label>
			<input type="password" id="password" name="password" placeholder="Password" required>
			<button name="login" type="submit">Sign in</button>
			</form> -->

						  	<script>
			jQuery(document).ready(function(){
			jQuery("#login_form").submit(function(e){
					e.preventDefault();
					var formData = jQuery(this).serialize();
					$.ajax({
						type: "POST",
						url: "login.php",
						data: formData,
						success: function(html){
						if(html=='true')
						{
						$.jGrowl("Welcome Back!", { header: 'Access Granted' });
						var delay = 2000;
							setTimeout(function(){ window.location = 'home.php'  }, delay);  
						}
						else
						{
						$.jGrowl("Please Check your username and Password", { header: 'Login Failed' });
						}
						}
						
					});
					return false;
				});
			});

		
			</script>  

</body>
</html>