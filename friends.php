<?php
include("includes/header.php");

if(isset($_GET['username'])) {
	$username = $_GET['username'];
}
else {
	$username = $userLoggedIn;
}
?>

<div class="main_column column" id="main_column">

	<?php

		$user_obj = new User($con, $username);
		foreach($user_obj->getFriendsList() as $friend) {
			$friend_obj = new User($con, $friend);


   $query = "SELECT * FROM users WHERE username = '$friend' ";
			 $select_all_post_query = mysqli_query($con, $query);

                     while($users = mysqli_fetch_array($select_all_post_query)) {
                        
                           if (! $select_all_post_query) {

                       die ("QUERY FAILED" . mysqly_error($con));
                        }

                     $profile_pic = $users['profile_pic'];

			echo "<a href='$friend'>
					<img class='profilePicSmall'  src='assets/images/profile_pics/" 
					. $users['profile_pic']." '>
					 ".$users['first_name'] . " " . $users['last_name']." 
				</a>
				<br>";
		}
	}

	?>

</div>