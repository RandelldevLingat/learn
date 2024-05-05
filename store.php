<!DOCTYPE html>
<html>
<head>
	<title>POST AND COMMENT SYSTEM</title>
	<?php include('dbconn.php'); ?>
	<?php include('session.php'); ?>
	
	  <script src="vendors/jquery-1.7.2.min.js"></script>
    <script src="vendors/bootstrap.js"></script>
	<style>
		*{
			margin: 0;
			padding: 0;
			box-sizing: border-box;

		}

		.post-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
	background-color: red;
}

.delete-button {
    float: right; /* Align the delete button to the right */
}

.delete-btn {
    background-color: #ff3333; /* Red background color */
    color: white; /* White text color */
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

.post-info {
    margin-right: 10px; /* Add some margin between delete button and post info */
}

.posted-by {
    font-weight: bold; /* Make the "Posted by" text bold */
}

.posted-by a {
    text-decoration: none;
    color: #333; /* Link color */
}

.posted-by a:hover {
    text-decoration: underline; /* Underline link on hover */
}
.post-date {
            color: #666;
            font-size: 0.8em;
        }

/* posting comment */

.post-content {
            background-color:gray;
            padding: 10px;
            margin-top: 10px;
        }
	</style>

</head>
<body>
	<div id="container">

		<br>
		WELCOME!
			<a href="logout.php"><button><?php 
				echo $member_row['firstname']." ".$member_row['lastname'];
			?> - Log Out</button></a>
			
		<br>
		<br>
		
					<!-- <form method="post"> 
					<textarea name="post_content" rows="7" cols="64" style="" placeholder=".........Write Someting........" required></textarea>
					<br>
					<button name="post">&nbsp;POST</button>
					<br>
					<hr>
					</form> -->
						<?php	
							$query = mysqli_query($conn,"SELECT *,UNIX_TIMESTAMP() - date_created AS TimeSpent from post LEFT JOIN user on user.user_id = post.user_id order by post_id DESC")or die(mysqli_error());
							while($post_row=mysqli_fetch_array($query)){
							$id = $post_row['post_id'];	
							$upid = $post_row['user_id'];	
							$posted_by = $post_row['firstname']." ".$post_row['lastname'];
						?>
						<!-- this is one the student name -->
						<!-- The row of the recently uploaded posts -->
						
					<div class="post-header">
					<div class="post-info">
						<span class="posted-by">Posted by: <a href="#"><?php echo $posted_by; ?></a></span>
						<span class="post-date"><?php				
								$days = floor($post_row['TimeSpent'] / (60 * 60 * 24));
								$remainder = $post_row['TimeSpent'] % (60 * 60 * 24);
								$hours = floor($remainder / (60 * 60));
								$remainder = $remainder % (60 * 60);
								$minutes = floor($remainder / 60);
								$seconds = $remainder % 60;
								if ($days > 0) {
									echo date('F d, Y - H:i:sa', strtotime($post_row['date_created']));
								}
								elseif($days == 0 && $hours == 0 && $minutes == 0)
								echo "A few seconds ago";		
							elseif($days == 0 && $hours == 0)
							echo $minutes.' minutes ago';
						?><span/>
						</div>

						<!-- delete button container -->
						<div class="delete-button">
						<a href="deletepost.php<?php echo '?id='.$id; ?>">
							<button class="delete-btn">Delete post</button>
						</a>
					</div>
					</div>
					<br>
					<br>
					<div class="post-content">
						<?php echo $post_row['content']; ?></h3>
					</div>
					<!-- the reply to comment section  -->
					<form method="post">
					<hr>
					Comment:<br>
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<textarea name="comment_content" rows="2" cols="44" style="" placeholder=".........Type your comment here........" required></textarea><br>
					<input type="submit" name="comment">
					</form>
						
					</br>
				
							<?php 
								$comment_query = mysqli_query($conn,"SELECT * ,UNIX_TIMESTAMP() - date_posted AS TimeSpent FROM comment LEFT JOIN user on user.user_id = comment.user_id where post_id = '$id'") or die (mysqli_error());
								while ($comment_row=mysqli_fetch_array($comment_query)){
								$comment_id = $comment_row['comment_id'];
								$comment_by = $comment_row['firstname']." ".  $comment_row['lastname'];
							?>
					<br><a href="#"><?php echo $comment_by; ?></a> - <?php echo $comment_row['content']; ?>
					<br>
							<?php				
								$days = floor($comment_row['TimeSpent'] / (60 * 60 * 24));
								$remainder = $comment_row['TimeSpent'] % (60 * 60 * 24);
								$hours = floor($remainder / (60 * 60));
								$remainder = $remainder % (60 * 60);
								$minutes = floor($remainder / 60);
								$seconds = $remainder % 60;
								if($days > 0)
								echo date('F d, Y - H:i:sa', $comment_row['date_posted']);
								elseif($days == 0 && $hours == 0 && $minutes == 0)
								echo "A few seconds ago";		
								elseif($days == 0 && $hours == 0)
								echo $minutes.' minutes ago';
							?>
					<br>
							<?php
							}
							?>
					<hr
					&nbsp;
					<?php 
					if ($u_id = $id){
					?>
					
				
					
					<?php }else{ ?>
						
					<?php
					} } ?>
					
				
							<?php
								if (isset($_POST['post'])){
								$post_content  = $_POST['post_content'];
								
								mysqli_query($conn,"insert into post (content,date_created,user_id) values ('$post_content','".strtotime(date("Y-m-d h:i:sa"))."','$user_id') ")or die(mysqli_error());
								header('location:home.php');
								}
							?>

							<?php
							
								if (isset($_POST['comment'])){
								$comment_content = mysqli_real_escape_string($conn, $_POST['comment_content']);
								$post_id=$_POST['id'];
								
								mysqli_query($conn,"insert into comment (content,date_posted,user_id,post_id) values ('$comment_content','".strtotime(date("Y-m-d h:i:sa"))."','$user_id','$post_id')") or die (mysqli_error($conn));
								header('location:home.php');
								}
							?>

</body>

  <?php include('footer.php');?>

</html>




<!-- COMMENT AND MAIN CONTENT QUESTION -->
<div class="post-content">
						<?php echo $post_row['content']; ?></h3>
					</div>

					<!-- COMMENTING PART -->
					<form method="post">
					<hr>
					Comment:<br>
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<textarea name="comment_content" rows="2" cols="44" style="" placeholder=".........Type your comment here........" required></textarea><br>
					<input type="submit" name="comment">
					</form>
						
					</br>
				
					<div class="comment-container">

						<?php 
								$comment_query = mysqli_query($conn,"SELECT * ,UNIX_TIMESTAMP() - date_posted AS TimeSpent FROM comment LEFT JOIN user on user.user_id = comment.user_id where post_id = '$id'") or die (mysqli_error());
								while ($comment_row=mysqli_fetch_array($comment_query)){
									$comment_id = $comment_row['comment_id'];
									$comment_by = $comment_row['firstname']." ".  $comment_row['lastname'];
									?>

			<div class="comment">

					<br><a href="#"><?php echo $comment_by; ?></a> - <?php echo $comment_row['content']; ?>
					<br>
					<?php				
								$days = floor($comment_row['TimeSpent'] / (60 * 60 * 24));
								$remainder = $comment_row['TimeSpent'] % (60 * 60 * 24);
								$hours = floor($remainder / (60 * 60));
								$remainder = $remainder % (60 * 60);
								$minutes = floor($remainder / 60);
								$seconds = $remainder % 60;
								if($days > 0)
								echo date('F d, Y - H:i:sa', $comment_row['date_posted']);
							elseif($days == 0 && $hours == 0 && $minutes == 0)
							echo "A few seconds ago";		
						elseif($days == 0 && $hours == 0)
						echo $minutes.' minutes ago';
					?>
					</span>
				</div> <!-- End of the .comment-->
					<br>
					<?php
							}
							?>
				</div> <!-- End of the comment-container --> 
					<hr
					&nbsp;
					<?php 
					if ($u_id = $id){
						?>
					
					
					
					<?php }else{ ?>
						
						<?php
					} } ?>
					
					
							<?php
								if (isset($_POST['post'])){
									$post_content  = $_POST['post_content'];
									
									mysqli_query($conn,"insert into post (content,date_created,user_id) values ('$post_content','".strtotime(date("Y-m-d h:i:sa"))."','$user_id') ")or die(mysqli_error());
									header('location:home.php');
							}
							?>

<?php
							if (isset($_POST['comment'])){
								$comment_content = $_POST['comment_content'];
								$post_id=$_POST['id'];
								
								mysqli_query($conn,"insert into comment (content,date_posted,user_id,post_id) values ('$comment_content','".strtotime(date("Y-m-d h:i:sa"))."','$user_id','$post_id')") or die (mysqli_error());
								header('location:home.php');
							}
							?>




<!--  -->
<!-- corrected -->

<!-- COMMENTING PART -->
<form method="post">
					<hr>
					Comment:<br>
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<textarea name="comment_content" rows="2" cols="44" style="" placeholder=".........Type your comment here........" required></textarea><br>
					<input type="submit" name="comment">
					</form>
						
					</br>

<div class="comment-container">

						<?php 
								$comment_query = mysqli_query($conn,"SELECT * ,UNIX_TIMESTAMP() - date_posted AS TimeSpent FROM comment LEFT JOIN user on user.user_id = comment.user_id where post_id = '$id'") or die (mysqli_error());
								while ($comment_row=mysqli_fetch_array($comment_query)){
									$comment_id = $comment_row['comment_id'];
									$comment_by = $comment_row['firstname']." ".  $comment_row['lastname'];
									?>

			<div class="comment">

					<br><a href="#"><?php echo $comment_by; ?></a> - <?php echo $comment_row['content']; ?>
					<br>
					<?php				
								$days = floor($comment_row['TimeSpent'] / (60 * 60 * 24));
								$remainder = $comment_row['TimeSpent'] % (60 * 60 * 24);
								$hours = floor($remainder / (60 * 60));
								$remainder = $remainder % (60 * 60);
								$minutes = floor($remainder / 60);
								$seconds = $remainder % 60;
								if($days > 0)
								echo date('F d, Y - H:i:sa', $comment_row['date_posted']);
							elseif($days == 0 && $hours == 0 && $minutes == 0)
							echo "A few seconds ago";		
						elseif($days == 0 && $hours == 0)
						echo $minutes.' minutes ago';
					?>
					</span>
				</div> <!-- End of the .comment-->
					<br>
					<?php
							}
							?>
				</div> <!-- End of the comment-container --> 
					<hr