<?php
include("../../config/config.php");
include("../../includes/classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query);

//If query contains an underscore, assume user is searching for usernames
if(strpos($query, '_') !== false) 
	$usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");
//If there are two words, assume they are first and last names respectively
else if(count($names) == 2)
	$usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') AND user_closed='no' LIMIT 8");
//If query has one word only, search first names or last names 
else 
	$usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND user_closed='no' LIMIT 8");


if($query != ""){

	while($row = mysqli_fetch_array($usersReturnedQuery)) {
		$user = new User($con, $userLoggedIn);

		if($row['username'] != $userLoggedIn)
			$mutual_friends = $user->getMutualFriends($row['username']) . " friends in common";
		else 
			$mutual_friends = "";

		$query = "SELECT * FROM users ";
			 $select_all_post_query = mysqli_query($con, $query);

                     while($users = mysqli_fetch_array($select_all_post_query)) {
                        
                           if (! $select_all_post_query) {

                       die ("QUERY FAILED" . mysqly_error($con));
                        }

                     $profile_pic = $users['profile_pic'];
                          

		echo "<div class='resultDisplay'>
				<a href='" . $users['username'] . "' style='color: #1485BD'>
					<div class='liveSearchProfilePic'>
						 <img src='assets/images/profile_pics/" . $users['profile_pic']." ' >
					</div>

					<div class='liveSearchText'>
						" . $users['first_name'] . " " . $users['last_name'] . "
						<p>" . $users['username'] ."</p>
						<p id='grey'>" . $mutual_friends ."</p>
					</div>
				</a>
				</div>";

	}
}
}

?>