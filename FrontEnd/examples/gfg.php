<?php

include("config.php");

// Get the user id
$user_id = $_REQUEST['user_id'];

// Database connection
//$con = mysqli_connect("localhost", "root", "", "gfg");

if ($user_id !== "") {
	
	// Get corresponding first name and
	// last name for that user id	
	//$query = mysqli_query($con, "SELECT first_name,
	//last_name FROM userdata WHERE user_id='$user_id'");

    $query = mysqli_query($con, "SELECT movie_name,
    movie_year FROM movies WHERE movieId='$user_id'");

	$row = mysqli_fetch_array($query);


	// Get the first name
	$first_name = $row["movie_name"];

	// Get the first name
	$last_name = $row["movie_year"];
}

// Store it in a array
$result = array("$first_name", "$last_name");

// Send in JSON encoded form
$myJSON = json_encode($result);
echo $myJSON;
?>
