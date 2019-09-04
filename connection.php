<?php 
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'wieils_registration';

$connection = new mysqli($servername, $username, $password, $dbname);

if($connection->connect_error) {
	echo 'Connection Failed'.'<br>';
	die('Connection Failed: '.$connection->connect_error);
} 
// else {
	// echo 'Connection Established Successfully'.'<br>';
// }
?>
