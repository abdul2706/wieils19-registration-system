<?php

include('connection.php');
$ambassador_table = 'ambassadors';
$universities_table = 'universities';

$sql = "SELECT * FROM `{$ambassador_table}`;";
$result = $connection->query($sql);
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo $row['id'].':'.strtoupper($row['ambassador_name']).';';
    }
}
echo '<end>';

$sql = "SELECT * FROM `{$universities_table}`;";
$result = $connection->query($sql);
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo $row['id'].':'.strtoupper($row['university_name']).';';
    }
}
echo '<end>';

$connection->close();

?>