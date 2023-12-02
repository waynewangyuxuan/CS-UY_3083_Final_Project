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
    include 'db_connect.php'; // Ensure this points to your database connection script

    mysqli_query($conn, "LOCK TABLES Crime_codes WRITE");

    try {
        $crime_code = $_POST['crime_code'] ?: null;
        $code_description = $_POST['code_description'];

        if (isset($_POST['insert'])) {
            // Insert a new crime code record
            $stmt = $conn->prepare("INSERT INTO Crime_codes (Crime_code, Code_description) VALUES (?, ?)");
            $stmt->bind_param("is", $crime_code, $code_description);
            $stmt->execute();
        }

        if (isset($_POST['update'])) {
            // Update an existing crime code record
            $stmt = $conn->prepare("UPDATE Crime_codes SET Code_description = ? WHERE Crime_code = ?");
            $stmt->bind_param("si", $code_description, $crime_code);
            $stmt->execute();
        }

        if (isset($_POST['delete'])) {
            // Delete a crime code record
            $stmt = $conn->prepare("DELETE FROM Crime_codes WHERE Crime_code = ?");
            $stmt->bind_param("i", $crime_code);
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
      <a href="prob_officerw.php">Prob officers</a>
    </li>
    <li>
      <a href="crime_charges.php">Crime charges</a>
    </li>
    <li>
      <a href="crime_officers.php">Crime officers</a>
    </li>
    <li>
      <a href="officers.php">Officers</a>
    </li>
    <li>
      <a href="appeals.php">Appeals</a>
    </li>
    <li>
      <a href="crime_codes.php"class="active">Crime codes</a>
    </li>
  </ul>
</nav>

    <!-- Main Content for Criminals Page -->
    <main>
      <h1>Crimes Codes</h1>

<div class="search-container">
    <form action="crime_codes.php" method="get">
        <input type="text" name="search_crime_code" placeholder="Search by Crime Code...">
        <button type="submit">Search</button>
    </form>
</div>

<div class="search-container">
    <form action="crime_codes.php" method="get">
        <input type="text" name="search_code_description" placeholder="Search by Code Description...">
        <button type="submit">Search</button>
    </form>
</div>


<!-- Insert Crime Code Form -->
<div class="form-container form-spacing">
    <h3>Add New Crime Code</h3>
    <form action="crime_codes.php" method="post">
        <input type="text" name="crime_code" placeholder="Crime Code">
        <input type="text" name="code_description" placeholder="Code Description">
        <button type="submit" name="insert">Add</button>
    </form>
</div>


<!-- Update Crime Code Form -->
<div class="form-container form-spacing">
    <h3>Update Crime Code</h3>
    <form action="crime_codes.php" method="post">
        <input type="text" name="update_crime_code" placeholder="Crime Code to Update">
        <input type="text" name="new_code_description" placeholder="New Code Description">
        <button type="submit" name="update">Update</button>
    </form>
</div>


<!-- Delete Crime Code Form -->
<div class="form-container form-spacing">
    <h3>Delete Crime Code</h3>
    <form action="crime_codes.php" method="post">
        <input type="text" name="delete_crime_code" placeholder="Crime Code to Delete">
        <button type="submit" name="delete">Delete</button>
    </form>
</div>

<!-- Table for displaying search results -->
<table>
    <thead>
        <tr>
            <th>Crime Code</th>
            <th>Code Description</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include 'db_connect.php';

        mysqli_query($conn, "LOCK TABLES Crime_codes READ");
        $sql = "SELECT * FROM Crime_codes";
        $params = [];
        $types = '';

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (!empty($_GET['search_crime_code'])) {
                $sql .= " WHERE Crime_code = ?";
                $params[] = $_GET['search_crime_code'];
                $types .= 'i';
            } elseif (!empty($_GET['search_code_description'])) {
                $sql .= " WHERE Code_description LIKE ?";
                $params[] = "%".$_GET['search_code_description']."%";
                $types .= 's';
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
                            <td>".$row["Crime_code"]."</td>
                            <td>".$row["Code_description"]."</td>
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
