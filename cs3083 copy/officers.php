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

    mysqli_query($conn, "LOCK TABLES Officers WRITE");

    try {
        $officer_id = $_POST['officer_id'] ?: null;
        $last_name = $_POST['last_name'];
        $first_name = $_POST['first_name'];
        $precinct = $_POST['precinct'];
        $badge = $_POST['badge'];
        $phone = $_POST['phone'] ;
        $status = $_POST['status'] ;

        if (isset($_POST['insert'])) {
            // Insert a new officer record
            $stmt = $conn->prepare("INSERT INTO Officers (Officer_ID, Last, First, Precinct, Badge, Phone, Status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssss", $officer_id, $last_name, $first_name, $precinct, $badge, $phone, $status);
            $stmt->execute();
        }

        if (isset($_POST['update'])) {
            // Update an existing officer record
            $stmt = $conn->prepare("UPDATE Officers SET Last = ?, First = ?, Precinct = ?, Badge = ?, Phone = ?, Status = ? WHERE Officer_ID = ?");
            $stmt->bind_param("ssssssi", $last_name, $first_name, $precinct, $badge, $phone, $status, $officer_id);
            $stmt->execute();
        }

        if (isset($_POST['delete'])) {
            // Delete an officer record
            $stmt = $conn->prepare("DELETE FROM Officers WHERE Officer_ID = ?");
            $stmt->bind_param("i", $officer_id);
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
      <a href="crime_officers.php">Crime officers</a>
    </li>
    <li>
      <a href="officers.php" class="active">Officers</a>
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
<div class="search-container">
    <!-- Search Bar for Officer ID -->
    <form action="officers.php" method="get">
        <input type="text" name="search_officer_id" placeholder="Search by Officer ID...">
        <button type="submit">Search</button>
    </form>

    <!-- Search Bar for Precinct -->
    <form action="officers.php" method="get">
        <input type="text" name="search_precinct" placeholder="Search by Precinct...">
        <button type="submit">Search</button>
    </form>

    <!-- Search Bar for City -->
    <form action="officers.php" method="get">
        <input type="text" name="search_city" placeholder="Search by City...">
        <button type="submit">Search</button>
    </form>

    <!-- Search Bar for Last Name -->
    <form action="officers.php" method="get">
        <input type="text" name="search_last_name" placeholder="Search by Last Name...">
        <button type="submit">Search</button>
    </form>
</div>

<!-- Insert Officer Form -->
<div class="form-container">
    <h3>Add New Officer</h3>
    <form action="officers.php" method="post">
        <style>
            .form-spacing {
                margin-bottom: 20px;
            }
        </style>
        <input class="form-spacing" type="text" name="officer_id" placeholder="Officer ID">
        <input class="form-spacing" type="text" name="last_name" placeholder="Last Name">
        <input class="form-spacing" type="text" name="first_name" placeholder="First Name">
        <input class="form-spacing" type="text" name="precinct" placeholder="Precinct">
        <input class="form-spacing" type="text" name="badge" placeholder="Badge">
        <input class="form-spacing" type="text" name="phone" placeholder="Phone">
        <input class="form-spacing" type="text" name="status" placeholder="Status">
        <button type="submit" name="insert">Add</button>
    </form>
</div>


<!-- Update Officer Form -->
<div class="form-container">
    <h3>Update Officer</h3>
    <form action="officers.php" method="post">
        <style>
            .form-spacing {
                margin-bottom: 20px;
            }
        </style>
        <input class="form-spacing" type="text" name="officer_id" placeholder="Officer ID to Update">
        <input class="form-spacing" type="text" name="new_last_name" placeholder="New Last Name">
        <input class="form-spacing" type="text" name="new_first_name" placeholder="New First Name">
        <input class="form-spacing" type="text" name="new_precinct" placeholder="New Precinct">
        <input class="form-spacing" type="text" name="new_badge" placeholder="New Badge">
        <input class="form-spacing" type="text" name="new_phone" placeholder="New Phone">
        <input class="form-spacing" type="text" name="new_status" placeholder="New Status">
        <button type="submit" name="update">Update</button>
    </form>
</div>



<!-- Delete Officer Form -->
<div class="form-container">
    <h3>Delete Officer</h3>
    <form action="officers.php" method="post">
        <style>
            .form-spacing {
                margin-bottom: 20px;
            }
        </style>
        <input class="form-spacing" type="text" name="officer_id" placeholder="Officer ID to Delete">
        <button type="submit" name="delete">Delete</button>
    </form>
</div>

<!-- Table for displaying search results -->
<table>
    <thead>
        <tr>
            <th>Officer ID</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Precinct</th>
            <th>Badge</th>
            <th>Phone</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include 'db_connect.php';

        mysqli_query($conn, "LOCK TABLES Officers READ");
        $sql = "SELECT * FROM Officers";
        $params = [];
        $types = '';

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            // Implement search filters based on GET parameters
            // You can adapt the conditions based on the search functionality implemented

            $stmt = $conn->prepare($sql);
            if ($params) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row["Officer_ID"]."</td>
                            <td>".$row["Last"]."</td>
                            <td>".$row["First"]."</td>
                            <td>".$row["Precinct"]."</td>
                            <td>".$row["Badge"]."</td>
                            <td>".$row["Phone"]."</td>
                            <td>".$row["Status"]."</td>
                          </tr>";
                }
                mysqli_query($conn, "UNLOCK TABLES");
            } else {
                mysqli_query($conn, "UNLOCK TABLES");
                echo "<tr><td colspan='7'>No results found</td></tr>";
            }
            $conn->close();
        }
        ?>
    </tbody>
</table>

    </main>
  </body>
</html>
