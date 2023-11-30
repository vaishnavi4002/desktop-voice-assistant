<?php
include 'db1.php';

$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['note_id'])) {
    $note_id = $_GET['note_id'];

    // Query the note_changes_log table for update history
    $sql = "SELECT * FROM note_changes_log WHERE note_id = $note_id";
    $result = mysqli_query($conn, $sql);

    // Process and display history data
    echo "<h2>Update History for Note :</h2>";
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>Change Time: " . $row['change_time'] . ", Old Title: " . $row['old_title'] . ", New Title: " . $row['new_title'] . ", Old Description: " . $row['old_description'] . ", New Description: " . $row['new_description'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "Invalid request.";
}
?>

