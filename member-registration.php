<?php

$dir_uploads = 'uploads/';
$cookies_timeout = 10;
$members_table = 'registered_members';
$members_table_columns = array();
$register_member_data = array();

if(isset($_COOKIE['register-member']) && $_COOKIE['register-member'] == 'true') {
    echo 'member-registration.php: starts'.PHP_EOL;
    setcookie('register-member', 'false', time() - 60, "/");
    setcookie('member-registered', 'false', time() + 60, "/");
    include('connection.php');

    get_columns();

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo 'count -> '.count($_FILES).PHP_EOL;
        foreach($_FILES as $key => $value) {
            echo '('.$key.' => '.$value['name'].'), ';
        }
        echo PHP_EOL;

        $upload_success = upload_photo_files();
        echo $upload_success.PHP_EOL;

        if ($upload_success) {
            echo 'files moved'.PHP_EOL;
            $sql_register_member = generate_query();
            $result = $connection->query($sql_register_member);
            echo $result.PHP_EOL;
            if($result) {
                // setcookie('registered-success', 'true', time() + $cookies_timeout, "/");
                setcookie('member-registered', 'true', time() + 60, "/");
                echo 'member registered'.PHP_EOL;
            } else {
                setcookie('member-registered', 'false', time() + 60, "/");
                echo 'member not registered'.PHP_EOL;
            }
        } else {
            setcookie('member-registered', 'false', time() + 60, "/");
            echo 'files not moved'.PHP_EOL;
        }
    }

    echo 'member-registration.php: end'.PHP_EOL;
    $connection->close();
    exit;
}
header('Location: http://localhost/IEEE%20WIE%20ILS/wieils-site-01/registration-system/member-registration.html');

function get_columns() {
    global $connection, $members_table, $members_table_columns;
    $sql = "SHOW COLUMNS FROM `{$members_table}`;";
    echo $sql.PHP_EOL;
    $result = $connection->query($sql);
    echo 'no. of rows: '.$result->num_rows.PHP_EOL;
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($row['Field'] == 'id' || $row['Field'] == 'date_time') {
                continue;
            }
            array_push($members_table_columns, $row['Field']);
        }
    }
}

function generate_query() {
    global $connection, $members_table, $members_table_columns, $register_member_data;
    $member_name = test_input($_POST['name']);
    $sql_register_member = "INSERT INTO `{$members_table}` (";
    $index = 1;
    foreach($members_table_columns as $column) {
        if(isset($_POST[$column])) {
            array_push($register_member_data, test_input($_POST[$column]));
            if($index < count($members_table_columns)) {
                $sql_register_member .= "`{$column}`, ";
            } else {
                $sql_register_member .= "`{$column}`";
            }
            $index = $index + 1;
        } else if(isset($_FILES[$column])) {
            $file_name = $member_name.' - '.test_input(basename($_FILES[$column]['name']));
            array_push($register_member_data, $file_name);
            if($index < count($members_table_columns)) {
                $sql_register_member .= "`{$column}`, ";
            } else {
                $sql_register_member .= "`{$column}`";
            }
            $index = $index + 1;
        }
    }
    $sql_register_member .= ") VALUES (";
    $index = 1;
    foreach($register_member_data as $value) {
        if($index < count($register_member_data)) {
            $sql_register_member .= "'{$value}', ";
        } else {
            $sql_register_member .= "'{$value}'";
        }
        $index = $index + 1;
    }
    $sql_register_member .= ");";
    echo $sql_register_member.PHP_EOL;
    return $sql_register_member;
}

function upload_photo_files() {
    global $dir_uploads, $members_table_columns;
    $upload_success = false;
    $file_input_names = array_slice($members_table_columns, -3);
    $member_name = test_input($_POST['name']);
    foreach($file_input_names as $file_name) {
        echo $file_name.': ';
        echo $_FILES[$file_name]["error"].', ';
        if ($_FILES[$file_name]["error"] == UPLOAD_ERR_OK) {
            $photo_name = $member_name.' - '.test_input(basename($_FILES[$file_name]['name']));
            // here file_name acts as inner folder name as well
            $destination = $dir_uploads.$file_name.'/'.$photo_name;
            echo $destination.', ';
            $tmp_name = $_FILES[$file_name]["tmp_name"];
            echo $tmp_name.'; ';
            if(move_uploaded_file($tmp_name, $destination)) {
                $upload_success = true;
            } else {
                $upload_success = false;
                return $upload_success;
            }
        }
        echo PHP_EOL;
    }
    return $upload_success;
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>
