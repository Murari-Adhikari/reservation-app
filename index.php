<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=resturant_db;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set PDO to throw exceptions on error
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}


$reservationDate = $_POST['reservation-date'];
$reservationTime = $_POST['reservation-time'];
$numberOfGuest = $_POST['numberOfGuest'];
$lastName = $_POST['lname'];
$firstName = $_POST['fname'];
$email = $_POST['email'];
$phoneNumber = $_POST['contact']; 


if (empty($reservationDate) || empty($reservationTime) || empty($numberOfGuest) || empty($lastName) || empty($firstName) || empty($email) || empty($phoneNumber)) {
    die("<html>
    <body style='color: #fff; background-color: DarkOrchid; text-align: center; display: flex; justify-content: center; align-items: center; height: 100vh; font-weight: bold;'>Please fill all the required information. Click <a href='index.html'>here</a> to go back.</body></html>");
}




$availableTables = ['Table1', 'Table2', 'Table3', 'Table4', 'Table5'];

if ($numberOfGuest > count($availableTables)) {
    die("<html><body>Insufficient tables. Please call the restaurant for assistance. Click <a href='index.html'>here</a> to continue.</body></html>");
}

$sqlCheckTables = "SELECT table_number FROM reservation WHERE reservation_date = ? AND reservation_time = ?";
$stmtCheckTables = $db->prepare($sqlCheckTables);

try {
    $stmtCheckTables->execute([$reservationDate, $reservationTime]);
    $reservedTables = $stmtCheckTables->fetchAll(PDO::FETCH_COLUMN);

    
    $remainingTables = array_diff($availableTables, $reservedTables);
    
    if (!empty($remainingTables)) {
        // $tableNumber = reset($remainingTables);
        // $tableNumber = array_splice($remainingTables, 0, 1);
        $tableNumber = array_shift($remainingTables);
    } else {
        die("Sorry, there are no available tables at the moment.");
    }
    

    

   
    

  
    $sqlInsertReservation = "INSERT INTO reservation (reservation_date, reservation_time, number_of_guests, last_name, first_name, email, phone_number, table_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsertReservation = $db->prepare($sqlInsertReservation);

    $stmtInsertReservation->execute([$reservationDate, $reservationTime, $numberOfGuest, $lastName, $firstName, $email, $phoneNumber, $tableNumber]);

    if ($stmtInsertReservation) {
        echo "<html><body style='color: #fff; background-color: #D2691E; text-align: center; display: flex; justify-content: center; align-items: center; height: 100vh; font-weight: bold;'>Your reservation has been submitted successfully. Click <a href='index.html'>here</a> to continue.</body></html>";
        exit();
    } else {
        
        error_log("Error: " . $sqlInsertReservation . "\n" . $stmtInsertReservation->errorInfo()[2]);
        echo "An error occurred while processing your reservation. Please try again later.";
    }
} catch (PDOException $e) {
    
    error_log("Database error: " . $e->getMessage());
    echo "An error occurred while processing your reservation. Please try again later.";
}

$db = null;
?>
