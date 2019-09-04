<?php

error_reporting(E_ERROR);

$ambassadors_table = 'ambassadors';
$members_table = 'registered_members';
$ambassadors_table_columns = array();
$members_table_columns = array();
$ambassador_id = 0;
$current_ambassador;
$table_html = '';
$response->header = '';
$response->sql = '';
$response->num_rows = '';
$response->table = '';

if(isset($_COOKIE['login-success']) && $_COOKIE['login-success'] == 'true') {
	include_once('connection.php');
	$ambassadors_table_columns = get_columns($ambassadors_table);
	$members_table_columns = get_columns($members_table);

	$ambassador_id = test_input($_POST['ambassador_id']);
	$sql = "SELECT * FROM `{$ambassadors_table}` WHERE `id`='{$ambassador_id}';";
	$result = $connection->query($sql);
	$current_ambassador = $result->fetch_assoc();
	$response->header = 'Welcome Ambassador '.$current_ambassador["{$ambassadors_table_columns[3]}"];

	$sql = "SELECT * FROM `{$members_table}` WHERE `ambassador`={$ambassador_id}";
	$response->sql = $sql;
	$result = $connection->query($sql);
	$response->num_rows = $result;
	$table_html .= '<table id="table_members">';
	$table_html .= '    <tr>';
	foreach ($members_table_columns as $column){
		$table_html .= "        <th>{$column}</th>";
	}
	$table_html .= '    </tr>';
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$table_html .= '    <tr>';
			foreach ($members_table_columns as $column){
				$table_html .= "        <td>{$row[$column]}</td>";
			}
			$table_html .= '    </tr>';
		}
	}
	$table_html .= '</table>';
	$response->table = $table_html;

	echo json_encode($response);
	$connection->close();
	exit;
}
header('Location: http://localhost/IEEE%20WIE%20ILS/wieils-site-01/registration-system/login.html');

function get_columns($table_name) {
	global $connection;
	$table_columns = array();
    $sql = "SHOW COLUMNS FROM `{$table_name}`;";
    $result = $connection->query($sql);
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
			array_push($table_columns, $row['Field']);
        }
	}
	return $table_columns;
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>