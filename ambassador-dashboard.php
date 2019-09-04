<?php

error_reporting(E_ERROR);

$ambassadors_table = 'ambassadors';
$ambassadors_table_columns = array();
$ambassador_id = 0;
$current_ambassador;
$response->header = '';

if(isset($_COOKIE['login-success']) && $_COOKIE['login-success'] == 'true') {
	include_once('connection.php');
	get_columns();

	$ambassador_id = test_input($_POST['ambassador_id']);
	$sql = "SELECT * FROM `{$ambassadors_table}` WHERE `id`='{$ambassador_id}';";
	$result = $connection->query($sql);
	$current_ambassador = $result->fetch_assoc();
	$response->header = 'Welcome Ambassador '.$current_ambassador["{$ambassadors_table_columns[3]}"];

	echo json_encode($response);
	$connection->close();
	exit;
}
header('Location: http://localhost/IEEE%20WIE%20ILS/wieils-site-01/registration-system/login.html');

function get_columns() {
    global $connection, $ambassadors_table, $ambassadors_table_columns;
    $sql = "SHOW COLUMNS FROM `{$ambassadors_table}`;";
    $result = $connection->query($sql);
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
			array_push($ambassadors_table_columns, $row['Field']);
        }
    }
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>