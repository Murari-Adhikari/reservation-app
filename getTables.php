<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=resturant_db;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set PDO to throw exceptions on error
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

$availableTables = ['Table1', 'Table2', 'Table3', 'Table4', 'Table5'];

$sqlCheckTables = "SELECT table_number FROM reservation WHERE reservation_date = ? AND reservation_time = ?";
$stmtCheckTables = $db->prepare($sqlCheckTables);

$stmtCheckTables->execute([$_GET['date'], $_GET['time']]);
$res = $stmtCheckTables->fetchAll(PDO::FETCH_ASSOC);

$reservedTables = [];
foreach ($res as $row) {
    $reservedTables[] = $row['table_number'];
}

$remainingTables = array_values(array_diff($availableTables, $reservedTables));

if (!empty($remainingTables)) {
    // $tableNumber = reset($remainingTables);
    // $tableNumber = array_splice($remainingTables, 0, 1);
    // $tableNumber = array_shift($remainingTables);
    echo json_encode([ 'status' => 'OK', 'tables' => $remainingTables]);
} else {
    echo json_encode([ 'status' => 'Error', 'message' => "Sorry, there are no available tables at the moment."]);
}
