<?php
echo 'show-tables.php: starts'.'<br>';

include_once('connection.php');

$query = 'SHOW TABLES;';
echo $query.'<br>';
$result = $connection->query($query);
echo 'no. of rows: '.$result->num_rows.'<br>';
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        print_r($row);
        echo '<br>';
        $table_name = $row['Tables_in_wieils_registration'];
        $query2 = 'SHOW COLUMNS FROM `'.$table_name.'`;';
        echo $query2.'<br>';
        $result2 = $connection->query($query2);
        echo 'no. of rows: '.$result2->num_rows.'<br>';
        if($result2->num_rows > 0) {
            while($row2 = $result2->fetch_assoc()) {
                print_r($row2);
                echo '<br>';
            }
        }
    }
}

echo 'show-tables.php: end'.'<br>';

?>