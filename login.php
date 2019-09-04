<?php

$ambassadors_table = 'ambassadors';
$ambassadors_table_columns = array();
$username = '';
$password = '';
$attemps = 0;
$cookies_timeout = 86400;

if(isset($_COOKIE['ambassador-login']) && $_COOKIE['ambassador-login'] == 'true') {
    echo 'login.php: starts'.PHP_EOL;
    setcookie('ambassador-login', 'false', time() - 60, "/");
    setcookie('login-success', 'false', time() + $cookies_timeout, "/");
    include_once('connection.php');
    get_columns();

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['username'])) {
            $username = test_input($_POST['username']);
        }
        if(isset($_POST['password'])) {
            $password = test_input($_POST['password']);
        }
        
        $query = "SELECT * FROM `{$ambassadors_table}` WHERE `{$ambassadors_table_columns[1]}`='{$username}' AND `{$ambassadors_table_columns[2]}`='{$password}';";
        $result = $connection->query($query);
        if($result->num_rows == 1) {
            echo 'success'.PHP_EOL;
            $id = $result->fetch_assoc()['id'];
            echo 'id -> '.$id.PHP_EOL;
            setcookie('ambassador_id', $id, time() + $cookies_timeout, "/");
            setcookie('login-success', 'true', time() + $cookies_timeout, "/");
            header('Location: http://localhost/IEEE%20WIE%20ILS/wieils-site-01/registration-system/ambassador-dashboard.html');
            $connection->close();
            exit;
        } else {
            echo 'failed'.PHP_EOL;
            setcookie('login-success', 'false', time() + $cookies_timeout, "/");
            header('Location: http://localhost/IEEE%20WIE%20ILS/wieils-site-01/registration-system/login.html');
            $connection->close();
            exit;
        }
        echo PHP_EOL;
    }
    echo 'login.php: end'.PHP_EOL;
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
