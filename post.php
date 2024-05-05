<?php
include('dbconn.php');
include('session.php');
if (isset($_POST['post'])) {
    // Prepare the SQL statement with placeholders
    $sql = "INSERT INTO post (content, date_created, user_id) VALUES (?, NOW(), ?)";
    
    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "si", $content, $user_id); // Assuming user_id is an integer
    
    // Set the parameters and execute the statement
    $content = $_POST['content'];
    $user_id = $user_id; // Assuming $user_id is defined in your session.php file
    mysqli_stmt_execute($stmt);
    
    // Check if the query was successful
    if(mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<script>window.location = 'home.php';</script>";
        exit(); // Ensure script execution stops after redirect
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    
    // Close the statement
    mysqli_stmt_close($stmt);
}
?>
    
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- navigation css -->
    <link rel="stylesheet" href="style.css"/>

     <!-- Bootstrap CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="post.css"/>
    <title>Post</title>
    <!-- Include necessary styles/scripts -->
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        @font-face {
            font-family: "Proxima-nova";
            src: url(Mark\ Simonson\ \ Proxima\ Nova\ Regular.otf);
        }
        body{
            font-family: "Proxima-nova";
        }
        /* navigation additions */
        .user-logined{
			margin-right: 1em;
		}
		.user-logined span{
			font-weight: bold;
			color: white;
		}
        .logout-btn{
			background-color: #0b0345; /* Changed button color */
			color: #fff; /* Changed button text color */
			border: none;
			padding: 0.5em 1em; /* Added padding for better appearance */
			border-radius: 4px; /* Added border radius for rounded corners */
			cursor: pointer;    
			transition: all .1s ease;
			box-shadow: 0 0 4px #0b0345;
			font-variant: small-caps;
		}

		.logout-btn:active{
			background-color: #d6d3d3;
			color: #0b0345;
			box-shadow: 0 0 4px #0b0345; 
			transform: scale(0.95);
		}

        .container{
            min-height: 100vh;
            max-width:70em;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 2em 5em;
        }

        .post-form{
            display: flex;
            flex-direction: column;
            align-items: start;
            gap: 2em;
            padding: 2em;
            border-radius: 1.5em;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.4);
        }

        .post-title h2{
            font-weight: 600;
            font-size: 2.2em;
            color: #35408E;
            margin-bottom:.5em ;
        }

        .post-title h3{
            font-size: 1.5em;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .post-title hr{
            max-width: 36em;
            border: none;
            border-radius: 2em;
            height: .3em;
            background-color: #0b0345;

        }
        .post-container{
            max-width: 50em;
            width: 100%;
        }
        textarea{
            width: 100%;
            min-height: 9em;
            outline:none;
            padding: 0 0 0 1em;
            border-radius: 2em;
            padding-top: 1em;
            resize: none;
            border: gray solid 2px;
            transition: border-color .1s ease-in-out;
        }

        .message_input:focus{
            border-color: #35408E;
        }
        /* Dropdown menu design */

        .btn{
            background-color: #0b0345 !important;
            transition: 0.2s ease-in-out;
        }
        .btn:hover{
            box-shadow: 0 0 4px #0b0345;
            
        }
        /* Menu */
        .dropdown-menu{
            min-width: 20em;
            max-height: 200px;
            overflow-y  : scroll;
        }

        /* Buttons */
        .ask_button{
            display: flex;
            align-items: center;
            padding: .7em 3em;
            border-radius: 2em;
            gap: .3em;
            cursor: pointer;
            background-color: #0b0345;
            color: white;
            font-weight: 600;
            font-size: 1em;
            outline: none;
            border: none;
            transition: scale .2s ease-in;
        }

        .ask_button:focus{
            border: solid 1px #0b0345;
            transform: scale(1);
            box-shadow: 0 0 4px #0b0345;

        }

        .submit-image {
            filter: brightness(0) invert(1);    
            height: 1.2em;
        }

        .right_image_container{
            margin: 2em;
        }
        .right_image_container img{   
            -webkit-mask-image: url(data:image/svg+xml;base64,PCEtLT94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/LS0+CiAgICAgICAgICAgICAgPHN2ZyBpZD0ic3ctanMtYmxvYi1zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSI+CiAgICAgICAgICAgICAgICAgICAgPGRlZnM+IAogICAgICAgICAgICAgICAgICAgICAgICA8bGluZWFyR3JhZGllbnQgaWQ9InN3LWdyYWRpZW50IiB4MT0iMCIgeDI9IjEiIHkxPSIxIiB5Mj0iMCI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8c3RvcCBpZD0ic3RvcDEiIHN0b3AtY29sb3I9InJnYmEoMjQ4LCAxMTcsIDU1LCAxKSIgb2Zmc2V0PSIwJSI+PC9zdG9wPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPHN0b3AgaWQ9InN0b3AyIiBzdG9wLWNvbG9yPSJyZ2JhKDI1MSwgMTY4LCAzMSwgMSkiIG9mZnNldD0iMTAwJSI+PC9zdG9wPgogICAgICAgICAgICAgICAgICAgICAgICA8L2xpbmVhckdyYWRpZW50PgogICAgICAgICAgICAgICAgICAgIDwvZGVmcz4KICAgICAgICAgICAgICAgIDxwYXRoIGZpbGw9InVybCgjc3ctZ3JhZGllbnQpIiBkPSJNMTcuOCwtMjkuNkMyMy4zLC0yNy42LDI4LjIsLTIzLjQsMzIuNywtMThDMzcuMiwtMTIuNyw0MS40LC02LjQsNDMsMC45QzQ0LjYsOC4yLDQzLjcsMTYuNCwzOC4zLDIwLjRDMzMsMjQuMywyMy4zLDIzLjksMTYuMiwyNi45QzksMjkuOCw0LjUsMzYuMiwtMS41LDM4LjdDLTcuNSw0MS4zLC0xNC45LDQwLC0xOS43LDM1LjdDLTI0LjQsMzEuMywtMjYuNCwyMy45LC0yNi45LDE3LjRDLTI3LjQsMTAuOSwtMjYuNCw1LjUsLTI4LjgsLTEuNEMtMzEuMywtOC4zLC0zNy4zLC0xNi42LC0zNS4xLC0yMC4xQy0zMi45LC0yMy42LC0yMi41LC0yMi4zLC0xNS4zLC0yMy4zQy04LjEsLTI0LjQsLTQsLTI3LjcsMS4xLC0yOS41QzYuMSwtMzEuNCwxMi4zLC0zMS42LDE3LjgsLTI5LjZaIiB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSg1MCA1MCkiIHN0cm9rZS13aWR0aD0iMCIgc3R5bGU9InRyYW5zaXRpb246IGFsbCAwLjNzIGVhc2UgMHM7Ij48L3BhdGg+CiAgICAgICAgICAgICAgPC9zdmc+);
        }

        @media screen and (max-width:1055px){
            .right_image_container{
                max-width: 40em;
                width: 100%;
            }
            .right_image_container img{
                width: 100%;
            }
        }

        @media screen and (max-width:960px){
            .container{
                max-width: 40em;
            }
            .container{
                display: block;
            }
        }

        @media screen and (max-width:500px){
            .right_image_container img{
                width: 80%;
            }
        }

        @media screen and (max-width:750px){
            .container{
                margin: 0 auto;
            }
            .right_image_container{
                text-align: center;
            }
            .right_image_container img{
                width: 70%;
            }
        }

        @media screen and (max-width:950px){
            .container{
                margin: 0 auto;
            }
            .right_image_container img{
                width: 70%;
            }
        }

        @media screen and (max-width:999px){
            .right_image_container img{
                width: 70%;
            }
        }
    </style>
</head>
<body>
    <!-- Nav-bar html -->
    <nav>
        <label id="logo">
          <img id="logos" src="images/logo.png">
        </label>
        <div class="box">
          <i class="fa fa-search" aria-hidden="true"></i>
          <input type="text" name="" placeholder="Search here for answers to any question...">
        </div>
        <div class="links-container">
          <a class="home-link" href="home.php">Home</a> <!-- link back to homepage -->
        </div>
        <div class="user-logined">
		<span><?php echo $member_row['firstname']." ".$member_row['lastname']; ?></span>
		</div>
		<div class="user-info">
        <form action="logout.php" method="post">
			<button class="logout-btn" type="submit">Log Out</button>
		</form>
		</div>  
      </nav>

      <!-- end of nav bar -->
    <div class="container">
        <br>
        <form method="post" action="post.php" class="post-form">
        <div class="post-title">
            <h2>Ask some questions about your assignment</h2>
            <hr/>   
            <h3>Please enter your question below</h3>
        </div>

        <div class="post-container">
            <textarea name="content" rows="7" cols="64" placeholder=".........Write Something........" required></textarea>
        </div>

        <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                      Subject
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                      <li><a class="dropdown-item" href="#">Filipino</a></li>
                      <li><a class="dropdown-item" href="#">Math</a></li>
                      <li><a class="dropdown-item" href="#">English</a></li>
                      <li><a class="dropdown-item" href="#">Science</a></li>
                      <li><a class="dropdown-item" href="#">Health</a></li>
                    </ul>
                  </div>
            <button class="ask_button" type="submit" name="post">POST</button>
        </form>
        <div class="right_image_container">
                <img class="right_img" src="images/rightImage.webp"/>
            </div>
    </div>


    <!-- BOOTSTRAP JS -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
