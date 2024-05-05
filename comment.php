<?php
include("dbconn.php");
include("session.php");

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '') . ' ago';
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) : 'just now';
}

// Handling comment submission
if (isset($_POST['comment'])) {
    $comment = $_POST['comment_content'];
    $post_id = isset($_POST['id']) ? $_POST['id'] : null; // Check if $_POST['id'] is set

    if ($post_id !== null) {
        // Prepare the SQL statement with placeholders
        $sql = "INSERT INTO comment (content, user_id, post_id, date_posted) VALUES (?, ?, ?, NOW())";
        
        // Prepare the statement
        $stmt = mysqli_prepare($conn, $sql);
        
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "sii", $comment, $user_id, $post_id);
        
        // Execute the statement
        mysqli_stmt_execute($stmt);
        
        // Check if the query was successful
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            // Redirect to the same page where the comment was posted
            header("Location: comment.php?id=$post_id");
            exit(); // Ensure script execution stops here
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        
        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle the case where $_POST['id'] is not set
        // For example, you could display an error message or redirect the user to another page
        echo "Error: Post ID not set.";
        exit();
    }
}

// Handling comment ratings
if (isset($_POST['rate_comment'])) {
    $comment_id = $_POST['comment_id'];
    $rating = ($_POST['rate_comment'] == 'up') ? 1 : -1; // Assuming upvote adds 1, downvote subtracts 1

    // Check if the user has already rated this comment
    $existing_rating_query = mysqli_query($conn, "SELECT * FROM comment_ratings WHERE comment_id = '$comment_id' AND user_id = '$user_id'");
    if (mysqli_num_rows($existing_rating_query) == 0) {
        // Insert the rating into the database
        $insert_rating_query = mysqli_query($conn, "INSERT INTO comment_ratings (comment_id, user_id, rating) VALUES ('$comment_id', '$user_id', '$rating')");

        if ($insert_rating_query) {
            // Rating successfully added
            // You can add further logic here if needed, like updating the displayed rating count
        } else {
            // Error handling for failed rating insertion
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Inform the user that they have already rated this comment
        echo "You have already rated this comment.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment Page</title>
    <link rel="stylesheet" href="style.css"/>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Proxima-nova";
        }
        @font-face {
            font-family: "Proxima-nova";
            src:url("Mark\ Simonson\ \ Proxima\ Nova\ Regular.otf") ;
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
        
        .main-question{
        }

        /* THE COMMENT SECTON */
        .answer-header{
            display: flex;
            justify-content: space-between;
            margin: 0 1rem;
            padding: 1rem 0;
        }
        .answer-header >*{
            font-variant: small-caps;
            color: #0b0345;
            font-weight: 600;
        }


        .current-comments{
            margin: 10px 10px;
        }

        .container{
            max-width: 45em;
            min-height: auto; 
            margin: 2em auto; 
        }
        /* containers */
        .container-1{
            min-height: 100%;
            min-width: 100%;
            padding: 1.2em;
            border-radius:2em ;
            box-shadow: 0 0 4px #0b0345;
        }   
        .container-1 >*{
            margin: 10px;
            line-height: 1.3em;
        }
        .question-wrap{
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            font-variant: small-caps;
        }
        .question-wrap p{
            font-size: 17px;
        }

        .comment-parent{
            max-width: 45em;
            margin: 2em auto; 
        }
        /* COMMENT SECTION PART */
        .comment-section{
            min-height: 100%;
            min-width: 100%;
            padding: 1.2em;
            border-radius:2em ;
            box-shadow: 0 0 4px #0b0345;
            /* background-color: green; */
        }
      
        .users-comment >*{
            margin: 10px;
        }
        .comment-form{
            max-width: 50em;
            width: 100%;
        }
        .comment-form textarea{
            width: 100%;
            min-height: 9em;
            outline:none;
            padding: 0 0 0 1em;
            border-radius: 1em;
            padding-top: 1em;
            resize: vertical;
            border: #0b0345 solid 2px;
            font-size: 16px;
        }
        /* input button */
        .comment-form input {
            margin-top: .6em;
			padding: .7em 1.7em;
			border-radius: 2em;
			background-color: #0b0345;
			font-variant: small-caps;
			color: white;
			font-size: .8em;
			outline: none;
			font-weight: bold;
			border: none;
			box-shadow: 0 0 4px #0b0345;
			transition: background-color 0.3s, color 0.3s, box-shadow 0.3s;
        }
        .comment-form input:hover{
            background-color: #fff; 
			color: #0b0345; 
			box-shadow: 0 0 8px #0b0345;
        }
        .comment-form input:active{
            background-color: #d6d3d3;
			color: #0b0345;
			box-shadow: 0 0 4px #0b0345; 
			transform: scale(0.95);
        }

        .answer-section{
            margin: 1em 0;
            text-align: center;
        }
        .answer-section .comment-header{
            font-size: 2em;
        }
        .answer-section p{
            font-size: 1.2em;
        }

        /* FORUM COMMENTS */

        .users-comment{
            /* background-color: gray; */
            max-width: 45em;
            margin: 2em auto; 
        }
        .current-comments{
            min-height: 100%;
            min-width: 100%;
            padding: 1.2em;
            border-radius:2em ;
            box-shadow: 0 0 4px #0b0345;
        }
        .comment{
            border: #0b0345 1px solid;
            margin: 1.5em 0;
            border-radius: 1em;
            padding: 10px;
            min-height: 200px; /* Set a maximum height for the comment container */
            word-wrap: break-word;
        }
        .comment > *{
            margin: 0 10px;
            line-height: 1.5em;
        }
        .comment h3{
            margin: 1em 10px;
        }
        .comment h2{
            margin-top: 10px;
        }
        .comment p{
            opacity: .8;
            font-size: 14px;
        }

        .comment button{
            margin-right: 1rem;
            margin-top: .6em;
			padding: .7em 1.7em;
			border-radius: 2em;
			background-color: #0b0345;
			font-variant: small-caps;
			color: white;
			font-size: .8em;
			outline: none;
			font-weight: bold;
			border: none;
			box-shadow: 0 0 4px #0b0345;
			transition: background-color 0.3s, color 0.3s, box-shadow 0.3s;

        }
        .comment button:active{
            background-color: #d6d3d3;
			color: #0b0345;
			box-shadow: 0 0 4px #0b0345; 
			transform: scale(0.95);
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


    <!-- Display the question -->
    <div class="main-question">

        <?php
        // Retrieve the post content based on the provided post_id if it's set
        $post_id = isset($_GET['id']) ? $_GET['id'] : null; // Check if $_GET['id'] is set
        if ($post_id !== null) {
            $post_query = mysqli_query($conn, "SELECT post.*, user.firstname, user.lastname FROM post JOIN user ON post.user_id = user.user_id WHERE post.post_id = '$post_id'");
            $post_row = mysqli_fetch_assoc($post_query);
            if ($post_row) {
                ?>
        </div>
    <div class="container">
        <div class="container-1">
            <div class="question-wrap">
            <h2>Question:</h2>
            <div class="brand">
                <p>Learnify</p>
            </div>
            </div>
            <p>Posted by: <?php echo $post_row['firstname'] . " " . $post_row['lastname']; ?></p>
            <h3><?php echo $post_row['content']; ?></h3>
            <?php if (isset($post_row['firstname']) && isset($post_row['lastname'])): ?>
            <?php endif; ?>
        </div>
    </div>
    <?php
            } else {
                // echo "Question not found.";
            }
        } else {
            echo "Post ID not provided.";
        }
    ?>
    
    <!-- <hr> -->
    <div class="comment-parent">

        <div class="comment-section">
            <div class="answer-section">
            <h2 class="comment-header">Comments:</h2>
            <p>Enter your answer here to help everyone</p>
            <hr/>
            <br/>
        </div>
        
        <form class="comment-form" method="post">
            <input type="hidden" name="id" value="<?php echo $post_id; ?>">
            <textarea name="comment_content" rows="2" cols="44" style="" placeholder=".........Type your comment here........" required></textarea><br>
            <input type="submit" name="comment">
        </form>
    </div>
</div>

    <!-- Display comments -->
    <div class="users-comment"> 
    <hr/>   
    <div class="answer-header">
                <h3>Answer</h3>
                <p class="reviews">Learnify</p>
            </div>
            <hr/>

           <div class="current-comments">
               
                <div class="coms">
               <?php
         if ($post_id !== null) {
             $comment_query = mysqli_query($conn, "SELECT comment.*, user.firstname, user.lastname FROM comment JOIN user ON comment.user_id = user.user_id WHERE comment.post_id = '$post_id'");
             while ($comment_row = mysqli_fetch_assoc($comment_query)) {
                 // Display each comment with commenter's name and posted date/time
                 echo "<div class='comment'>";
                 echo "<strong>Commented by: {$comment_row['firstname']} {$comment_row['lastname']}</strong>";
                 echo '<p class="comment_date">' . $comment_row['date_posted'] . '</p>';
                 echo "<h2>Answer</h2>";
                 echo "<h3>{$comment_row['content']}</h3>";
                 // Rating buttons
                 echo "<form method='post'>";
                 echo "<input type='hidden' name='comment_id' value='{$comment_row['comment_id']}'>";
                //  echo "<button type='submit' name='rate_comment' value='up'>Thumbs Up</button>";
                //  echo "<button type='submit' name='rate_comment' value='down'>Thumbs Down</button>";
                 echo "</form>";
                 echo "</div class='rating'>";
                 
                }
            }
            ?>
            </div>
            </div>
    </div>
</body>
</html>
