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

    mysqli_query($conn, "LOCK TABLES Appeals WRITE");

    try {
        $appeal_id = $_POST['appeal_id'] ?: null;
        $crime_id = $_POST['crime_id'] ;
        $filing_date = $_POST['filing_date'] ;
        $hearing_date = $_POST['hearing_date'] ;
        $status = $_POST['status'] ;

        if (isset($_POST['insert'])) {
            // Insert a new appeal record
            $stmt = $conn->prepare("INSERT INTO Appeals (Appeal_ID, Crime_ID, Filing_date, Hearing_date, Status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $appeal_id, $crime_id, $filing_date, $hearing_date, $status);
            $stmt->execute();
        }

        if (isset($_POST['update'])) {
            // Update an existing appeal record
            $stmt = $conn->prepare("UPDATE Appeals SET Crime_ID = ?, Filing_date = ?, Hearing_date = ?, Status = ? WHERE Appeal_ID = ?");
            $stmt->bind_param("isssi", $crime_id, $filing_date, $hearing_date, $status, $appeal_id);
            $stmt->execute();
        }

        if (isset($_POST['delete'])) {
            // Delete an appeal record
            $stmt = $conn->prepare("DELETE FROM Appeals WHERE Appeal_ID = ?");
            $stmt->bind_param("i", $appeal_id);
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
      <a href="crime_officers.php" >Crime officers</a>
    </li>
    <li>
      <a href="officers.php">Officers</a>
    </li>
    <li>
      <a href="appeals.php" class="active">Appeals</a>
    </li>
    <li>
      <a href="crime_codes.php" >Crime codes</a>
    </li>
  </ul>
</nav>



    <!-- Main Content for Criminals Page -->
    <main>
<h1>Crimes Codes</h1>
<div class="search-container">
  <form action="appeals.php" method="get">
      <input type="text" name="search_appeal_id" placeholder="Search by Appeal ID...">
      <button type="submit">Search by Appeal ID</button>
  </form>
  <form action="appeals.php" method="get">
      <input type="text" name="search_crime_id" placeholder="Search by Crime ID...">
      <button type="submit">Search by Crime ID</button>
  </form>
</div>

<!-- Insert Appeal Form -->
<div class="form-container">
    <h3>Add New Appeal</h3>
    <form action="appeals.php" method="post">
        <style>
            .form-spacing {
                margin-bottom: 20px;
            }
        </style>
        <input class="form-spacing" type="text" name="appeal_id" placeholder="Appeal ID">
        <input class="form-spacing" type="text" name="crime_id" placeholder="Crime ID">
        <input class="form-spacing" type="date" name="filing_date" placeholder="Filing Date" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
        <input class="form-spacing" type="date" name="hearing_date" placeholder="Hearing Date" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
        <input class="form-spacing" type="text" name="status" placeholder="Status">
        <button type="submit" name="insert">Add</button>
    </form>
</div>

<!-- Update Appeal Form -->
<div class="form-container">
    <h3>Update Appeal</h3>
    <form action="appeals.php" method="post">
        <style>
            .form-spacing {
                margin-bottom: 20px;
            }
        </style>
        <input class="form-spacing" type="text" name="update_appeal_id" placeholder="Appeal ID to Update">
        <input class="form-spacing" type="text" name="new_crime_id" placeholder="New Crime ID">
        <input class="form-spacing" type="date" name="new_filing_date" placeholder="New Filing Date" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
        <input class="form-spacing" type="date" name="new_hearing_date" placeholder="New Hearing Date" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
        <input class="form-spacing" type="text" name="new_status" placeholder="New Status">
        <button type="submit" name="update">Update</button>
    </form>
</div>
<!-- Delete Appeal Form -->
<div class="form-container">
    <h3>Delete Appeal</h3>
    <form action="appeals.php" method="post">
        <style>
            .form-spacing {
                margin-bottom: 20px;
            }
        </style>
        <input class="form-spacing" type="text" name="delete_appeal_id" placeholder="Appeal ID to Delete">
        <button type="submit" name="delete">Delete</button>
    </form>
</div>

<!-- Table for displaying search results -->
<table>
    <thead>
        <tr>
            <th>Appeal ID</th>
            <th>Crime ID</th>
            <th>Filing Date</th>
            <th>Hearing Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include 'db_connect.php';

        mysqli_query($conn, "LOCK TABLES Appeals READ");
        $sql = "SELECT * FROM Appeals";
        $params = [];
        $types = '';

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (!empty($_GET['search_appeal_id'])) {
                $sql .= " WHERE Appeal_ID = ?";
                $params[] = $_GET['search_appeal_id'];
                $types .= 'i';
            } elseif (!empty($_GET['search_crime_id'])) {
                $sql .= " WHERE Crime_ID = ?";
                $params[] = $_GET['search_crime_id'];
                $types .= 'i';
            } elseif (!empty($_GET['search_filing_date'])) {
                $sql .= " WHERE Filing_date = ?";
                $params[] = $_GET['search_filing_date'];
                $types .= 's';
            } elseif (!empty($_GET['search_hearing_date'])) {
                $sql .= " WHERE Hearing_date = ?";
                $params[] = $_GET['search_hearing_date'];
                $types .= 's';
            } elseif (!empty($_GET['search_status'])) {
                $sql .= " WHERE Status LIKE ?";
                $params[] = "%".$_GET['search_status']."%";
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
                            <td>".$row["Appeal_ID"]."</td>
                            <td>".$row["Crime_ID"]."</td>
                            <td>".$row["Filing_date"]."</td>
                            <td>".$row["Hearing_date"]."</td>
                            <td>".$row["Status"]."</td>
                          </tr>";
                }
                mysqli_query($conn, "UNLOCK TABLES");
            } else {
                mysqli_query($conn, "UNLOCK TABLES");
                echo "<tr><td colspan='5'>No results found</td></tr>";
            }
            $conn->close();
        }
        ?>
    </tbody>
</table>



</main>
  </body>
</html>
