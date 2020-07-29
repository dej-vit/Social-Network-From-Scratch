<?php  
include("../../config/config.php");
include("../classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query);

if(strpos($query, "_") !== false) {
	$usersReturned = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");
}
else if(count($names) == 2) {
	$usersReturned = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '%$names[0]%' AND last_name LIKE '%$names[1]%') AND user_closed='no' LIMIT 8");
}
else {
	$usersReturned = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '%$names[0]%' OR last_name LIKE '%$names[0]%') AND user_closed='no' LIMIT 8");
}
if($query != "") {
	while($row = mysqli_fetch_array($usersReturned)) {

		$user = new User($con, $userLoggedIn);

		if($row['username'] != $userLoggedIn) {
			$mutual_friends = $user->getMutualFriends($row['username']) . " friends in common";
		}
		else {
			$mutual_friends = "";
		}



		if($user->isFriend($row['username'])) {

         $query = "SELECT * FROM users";
			 $select_all_post_query = mysqli_query($con, $query);

                     while($users = mysqli_fetch_array($select_all_post_query)) {
                        
                           if (! $select_all_post_query) {

                       die ("QUERY FAILED" . mysqly_error($con));
                        }


                         
                          $profile_pic = $users['profile_pic'];



			echo "<div class='resultDisplay'>
					<a href='messages.php?u=" . $users['username'] . "' style='color: #000'>
						<div class='liveSearchProfilePic'>
							 <img  src='assets/images/profile_pics/" . $users['profile_pic']." '>	
						</div>

						<div class='liveSearchText'>
							".$users['first_name'] . " " . $users['last_name']. "
							<p style='margin: 0;'>". $users['username'] . "</p>
							<p id='grey'>".$mutual_friends . "</p>
						</div>
					</a>
				</div>";


		}
       }

	}
}

?>