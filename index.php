<?php
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

include 'db1.php'; 

function getTotalNotesCount()
{
    $conn = connectDB();

    // Call the stored procedure
    $result = mysqli_query($conn, "CALL getTotalNotesCount()");

    // Fetch the result
    $row = mysqli_fetch_assoc($result);

    // Close the database connection
    mysqli_close($conn);

    // Return the total count
    return $row['TotalNotesCount'];
}

$totalNotesCount = getTotalNotesCount();

// Output the total number of notes
echo "Total number of notes: " . $totalNotesCount;
// Function to insert a new note
function insertNote($title, $description)
{
    $conn = connectDB();
    
    $title = mysqli_real_escape_string($conn, $title);
    $description = mysqli_real_escape_string($conn, $description);

    $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
    $result = mysqli_query($conn, $sql);

    return $result;
}

// Function to update an existing note
function updateNote($sno, $title, $description)
{
    $conn = connectDB();
    
    $title = mysqli_real_escape_string($conn, $title);
    $description = mysqli_real_escape_string($conn, $description);

    $sql = "UPDATE `notes` SET `title` = '$title', `description` = '$description' WHERE `notes`.`sno` = $sno";
    $result = mysqli_query($conn, $sql);

    return $result;
}

// Function to delete a note
function deleteNote($sno)
{
    $conn = connectDB();

    $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
    $result = mysqli_query($conn, $sql);

    return $result;
}

$insert = false;
$update = false;
$delete = false;

// Connect to the Database using the function from db.php
$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['snoEdit'])) {
        // Update the record
        $sno = $_POST["snoEdit"];
        $title = $_POST["titleEdit"];
        $description = $_POST["descriptionEdit"];

        $update = updateNote($sno, $title, $description);
    } else {
        $title = $_POST["title"];
        $description = $_POST["description"];

        $insert = insertNote($title, $description);
    }
}

if (isset($_GET['delete'])) {
    $sno = $_GET['delete'];
    $delete = deleteNote($sno);
}
?>


<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="style.css">
  <script defer src="script.js"></script>


  <title>iNotes - Notes taking made easy</title>

</head>

<body>

 <!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit this Note</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form action="/php/index.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="snoEdit" id="snoEdit">
          <div class="form-group">
            <label for="title">Note Title</label>
            <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
          </div>

          <div class="form-group">
            <label for="desc">Note Description</label>
            <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
          </div>
          
        </div>
        
        <div class="modal-footer d-block mr-auto">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
          <!-- Button to open the history modal -->
          
        </div>
      </form>
    </div>
  </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="historyModalLabel">History for Note</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body" id="historyModalBody">
        <!-- History data will be displayed here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#"><img src="/crud/logo.svg" height="28px" alt=""></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact Us</a>
        </li>

      </ul>
      <<form class="form-inline my-2 my-lg-0" action="index.php" method="GET">
    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
  </nav>



  <?php
  if (isset($_GET['search'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM `notes` WHERE `title` LIKE '%$searchTerm%' OR `description` LIKE '%$searchTerm%'";
    } else {
    $sql = "SELECT * FROM `notes`";
    }

$result = mysqli_query($conn, $sql);
  if($insert){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been inserted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <?php
  if($delete){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been deleted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <?php
  if($update){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been updated successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <div class="container my-4">
    <h2>Add a Note to iNotes</h2>
    <form action="index.php" method="POST">
      <div class="form-group">
        <label for="title">Note Title</label>
        <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
      </div>

      <div class="form-group">
        <label for="desc">Note Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Add Note</button>
      <button type="button" class="btn btn-secondary" id="startVoiceNote" onclick="startVoiceNote()">Start Voice Note</button>
    <p id="voiceNoteStatus"></p>
    </form>
  </div>

  <div class="container my-4">

    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col">S.No</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php 
    $sql = "SELECT * FROM `notes`";
    $result = mysqli_query($conn, $sql);
    $sno = 0;
    while($row = mysqli_fetch_assoc($result)){
      $sno = $sno + 1;
      echo "<tr>
          <th scope='row'>". $sno . "</th>
          <td>". $row['title'] . "</td>
          <td>". $row['description'] . "</td>
          <td> 
              <button class='edit btn btn-sm btn-primary' id=".$row['sno'].">Edit</button> 
              <button class='delete btn btn-sm btn-primary' id=d".$row['sno'].">Delete</button>
              <button class='history btn btn-sm btn-primary' data-note-id='".$row['sno']."'>View History</button>
          </td>
      </tr>";
  }
  
?>


      </tbody>
    </table>
  </div>
  <hr>
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();

    });
  </script>
  <script>
    
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        tr = e.target.parentNode.parentNode;
        title = tr.getElementsByTagName("td")[0].innerText;
        description = tr.getElementsByTagName("td")[1].innerText;
        console.log(title, description);
        titleEdit.value = title;
        descriptionEdit.value = description;
        snoEdit.value = e.target.id;
        console.log(e.target.id)
        $('#editModal').modal('toggle');
      })
    })

    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        sno = e.target.id.substr(1);

        if (confirm("Are you sure you want to delete this note!")) {
          console.log("yes");
          window.location = `/php/index.php?delete=${sno}`;
        
        }
        else {
          console.log("no");
        }
      })
    })
    histories = document.getElementsByClassName('history');
Array.from(histories).forEach((element) => {
    element.addEventListener("click", (e) => {
        console.log("history ");
        sno = e.target.dataset.noteId;
        // Use AJAX to fetch and display history for the note with ID 'sno'
        $.ajax({
            type: 'GET',
            url: 'history.php',
            data: { note_id: sno },
            success: function(data) {
                displayHistoryModal(data);
            },
            error: function(error) {
                console.error('Error fetching history:', error);
            }
        });
    })
});

function displayHistoryModal(historyData) {
    // Customize this function to create and show a modal with the historyData
    $('#historyModalBody').html(''); // Clear existing content

    // Check if historyData is an actual array and not just a string
    if (Array.isArray(historyData)) {
        // Iterate through the historyData and append it to the modal body
        for (let i = 0; i < historyData.length; i++) {
    $('#historyModalBody').append(`
        <p>
            <strong>Change Time:</strong> ${historyData[i].change_time}<br><br>
            <strong>Old Title:</strong> ${historyData[i].old_title}<br><br>
            <strong>New Title:</strong> ${historyData[i].new_title}<br><br>
            <strong>Old Description:</strong> ${historyData[i].old_description}<br><br>
            <strong>New Description:</strong> ${historyData[i].new_description}
        </p>
        <hr>
    `);
}

    } else {
        // Handle the case where historyData is not an array (e.g., an error message)
        $('#historyModalBody').html('<p>' + historyData + '</p>');
    }

    // Show the Bootstrap modal
    $('#historyModal').modal('show');
}


  </script>
  
</body>

</html>
