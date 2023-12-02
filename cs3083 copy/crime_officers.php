<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crime Tracker - Criminals</title>
    <style>
      /* Basic Reset */
      body,
      h1,
      h2,
      h3,
      p,
      table,
      td,
      th,
      tr,
      ul,
      li,
      a {
        margin: 0;
        padding: 0;
        font-family: "Arial", sans-serif;
      }
      /* Navigation */
      nav {
        background-color: #333;
        overflow: hidden;
      }
      nav ul {
        width: 80%;
        margin: 0 auto;
        list-style-type: none;
      }
      nav li {
        float: left;
      }
      nav a {
        display: block;
        color: white;
        text-align: center;
        padding: 14px 16px;

        text-decoration: none;
      }
      nav a:hover,
      nav a.active {
        background-color: #111;
      }
      /* Main Content */
      main {
        width: 80%;
        margin: 20px auto;
        background-color: #f4f4f4;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      }
      .search-container {
        margin-bottom: 20px;
      }
      input[type="text"],
      button {
        padding: 10px;
        margin-right: 10px;
        border: none;
        border-radius: 3px;
      }
      input[type="text"] {
        width: 80%;
      }
      button {
        background-color: #333;
        color: #fff;
        cursor: pointer;
      }
      button:hover {
        background-color: #555;
      }
      table {
        width: 100%;

        border-collapse: collapse;
      }
      th,
      td {
        border-bottom: 1px solid #ddd;
        padding: 8px 12px;
        text-align: left;
      }
      th {
        background-color: #333;
        color: #fff;
      }
      tr:hover {
        background-color: #f5f5f5;
      }
      .button-container {
        margin-top: 20px;
        text-align: right;
      }
    </style>
  </head>
  <body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connect.php'; // Database connection script

    mysqli_query($conn, "LOCK TABLES Crime_officers WRITE");

    try {
        $crime_id = $_POST['crime_id'] ?: null;
        $officer_id = $_POST['officer_id'] ?: null;

        if (isset($_POST['insert'])) {
            // Insert a new crime_officer record
            $stmt = $conn->prepare("INSERT INTO Crime_officers (Crime_ID, Officer_ID) VALUES (?, ?)");
            $stmt->bind_param("ii", $crime_id, $officer_id);
            $stmt->execute();
        }

        if (isset($_POST['update'])) {
            // Update an existing crime_officer record
            $stmt = $conn->prepare("UPDATE Crime_officers SET Officer_ID = ? WHERE Crime_ID = ?");
            $stmt->bind_param("ii", $officer_id, $crime_id);
            $stmt->execute();
        }

        if (isset($_POST['delete'])) {
            // Delete a crime_officer record
            $stmt = $conn->prepare("DELETE FROM Crime_officers WHERE Crime_ID = ? AND Officer_ID = ?");
            $stmt->bind_param("ii", $crime_id, $officer_id);
            $stmt->execute();
        }
    } catch (Exception $e) {
        mysqli_query($conn, "UNLOCK TABLES"); // Unlock the tables in case of an exception
        $errorMessage = urlencode($e->getMessage());
        header("Location: error_display.php?error=$errorMessage");
        exit;
    }

    mysqli_query($conn, "UNLOCK TABLES"); // Unlock the tables after operations are done
    $conn->close();
}
?>

<!-- Navigation Menu -->
<nav>
  <ul>
    <li>
      <a href="index.php">Home</a>
    </li>
    <li>
      <a href="alias.php">Alias</a>
    </li>
    <li>
      <a href="criminals.php">Criminals</a>
    </li>
    <li>
      <a href="crimes.php">Crimes</a>
    </li>
    <li>
      <a href="sentences.php">Sentences</a>
    </li>
    <li>
      <a href="prob_officers.php">Prob officers</a>
    </li>
    <li>
      <a href="crime_charges.php">Crime charges</a>
    </li>
    <li>
      <a href="crime_officers.php" class="active">Crime officers</a>
    </li>
    <li>
      <a href="officers.php">Officers</a>
    </li>
    <li>
      <a href="appeals.php">Appeals</a>
    </li>
    <li>
      <a href="crime_codes.php" >Crime codes</a>
    </li>
  </ul>
</nav>

    <!-- Main Content for Criminals Page -->
    <main>
      <h1>Crime Officers</h1>



<div class="search-container">
    <!-- Search Bar for Crime ID -->
    <form action="crime_officers.php" method="get">
        <input type="text" name="search_crime_id" placeholder="Search by Crime ID...">
        <button type="submit">Search</button>
    </form>

    <!-- Search Bar for Officer ID -->
    <form action="crime_officers.php" method="get">
        <input type="text" name="search_officer_id" placeholder="Search by Officer ID...">
        <button type="submit">Search/button>
    </form>
</div>


<!-- Insert Crime Officer Form -->
<div class="form-container">
    <h3>Add New Crime Officer</h3>
    <form action="crime_officers.php" method="post">
        <input type="text" name="crime_id" placeholder="Crime ID">
        <input type="text" name="officer_id" placeholder="Officer ID">
        <button type="submit" name="insert">Add</button>
    </form>
</div>


<!-- Update Crime Officer Form -->
<div class="form-container">
    <h3>Update Crime Officer</h3>
    <form action="crime_officers.php" method="post">
        <input type="text" name="existing_crime_id" placeholder="Existing Crime ID">
        <input type="text" name="existing_officer_id" placeholder="Existing Officer ID">
        <input type="text" name="new_officer_id" placeholder="New Officer ID">
        <button type="submit" name="update">Update</button>
    </form>
</div>


<!-- Delete Crime Officer Form -->
<div class="form-container">
    <h3>Delete Crime Officer</h3>
    <form action="crime_officers.php" method="post">
        <input type="text" name="crime_id" placeholder="Crime ID to Delete">
        <input type="text" name="officer_id" placeholder="Officer ID to Delete">
        <button type="submit" name="delete">Delete</button>
    </form>
</div>


<!-- Table for displaying search results -->
<table>
    <thead>
        <tr>
            <th>Crime ID</th>
            <th>Officer ID</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include 'db_connect.php';

        mysqli_query($conn, "LOCK TABLES Crime_officers READ");
        $sql = "SELECT * FROM Crime_officers";
        $params = [];
        $types = '';

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (!empty($_GET['search_crime_id'])) {
                $sql .= " WHERE Crime_ID = ?";
                $params[] = $_GET['search_crime_id'];
                $types .= 'i';
            } elseif (!empty($_GET['search_officer_id'])) {
                $sql .= " WHERE Officer_ID = ?";
                $params[] = $_GET['search_officer_id'];
                $types .= 'i';
            }

            $stmt = $conn->prepare($sql);
            if ($params) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row["Crime_ID"]."</td>
                            <td>".$row["Officer_ID"]."</td>
                          </tr>";
                }
                mysqli_query($conn, "UNLOCK TABLES");
            } else {
                mysqli_query($conn, "UNLOCK TABLES");
                echo "<tr><td colspan='2'>No results found</td></tr>";
            }
            $conn->close();
        }
        ?>
    </tbody>
</table>


      
    </main>
  </body>
</html>
