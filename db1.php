<?php

function connectDB()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "notes";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Sorry we failed to connect: " . mysqli_connect_error());
    }

    return $conn;
}
$createLogTable = "CREATE TABLE IF NOT EXISTS note_changes_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    note_id INT,
    old_title VARCHAR(255),
    new_title VARCHAR(255),
    old_description TEXT,
    new_description TEXT,
    change_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
// $conn = connectDB();
// if (mysqli_query($conn, $createLogTable)) {
//     echo "note_changes_log table created successfully";
// } else {
//     echo "Error creating note_changes_log table: " . mysqli_error($conn);
// }

?>
