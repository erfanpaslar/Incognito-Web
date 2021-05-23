<?php

$db['db_host']     = '<DB HOSTNAME>';
$db['db_username'] = '<DB USERNAME>';
$db['db_password'] = '<DB PASSWORD>';
$db['db_name']     = '<DB NAME>';


$connection = mysqli_connect($db['db_host'], $db['db_username'], $db['db_password'], $db['db_name']);
if (!$connection) {
	echo "Connection ERROR!";
}

// * FUNCTIONS
function confirmQuery($whatQuery, $ErrorMessage = 'NONE') {
	global $connection;
	if (!$whatQuery) {
		die("QUERY FAILED " . $ErrorMessage . mysqli_error($connection));
	}
}
