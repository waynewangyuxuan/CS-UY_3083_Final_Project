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

    mysqli_query($conn, "LOCK TABLES Sentences WRITE");

    try {
        $sentence_id = $_POST['sentence_id'] ?: null;
        $criminal_id = $_POST['criminal_id'] ?: null;
        $type = $_POST['type'] ?: null;
        $prob_id = $_POST['prob_id'] ?: null;
        $start_date = $_POST['start_date'] ?: null;
        $end_date = $_POST['end_date'] ?: null;
        $violations = $_POST['violations'] ?: null;

        if (isset($_POST['insert'])) {
            // Insert a new sentence record
            $stmt = $conn->prepare("INSERT INTO Sentences (Sentence_ID, Criminal_ID, Type, Prob_ID, Start_date, End_date, Violations) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisiisi", $sentence_id, $criminal_id, $type, $prob_id, $start_date, $end_date, $violations);
            $stmt->execute();
        }

        if (isset($_POST['update'])) {
            // Update an existing sentence record
            $stmt = $conn->prepare("UPDATE Sentences SET Criminal_ID = ?, Type = ?, Prob_ID = ?, Start_date = ?, End_date = ?, Violations = ? WHERE Sentence_ID = ?");
            $stmt->bind_param("isiisii", $criminal_id, $type, $prob_id, $start_date, $end_date, $violations, $sentence_id);
            $stmt->execute();
        }

        if (isset($_POST['delete'])) {
            // Delete a sentence record
            $stmt = $conn->prepare("DELETE FROM Sentences WHERE Sentence_ID = ?");
            $stmt->bind_param("i", $sentence_id);
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
      <a href="sentences.php" class="active">Sentences</a>
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
      <a href="officers.php">Officers</a>
    </li>
    <li>
      <a href="appeals.php">Appeals</a>
    </li>
    <li>
      <a href="crime_codes.php" class="active">Crime codes</a>
    </li>
  </ul>
</nav>


    <!-- Main Content for Criminals Page -->
    <main>
      <h1>Sentences</h1>
    <!-- Search Bars -->
    <div class="search-container">
        <form action="sentences.php" method="get">
            <input type="text" name="search_sentence_id" placeholder="Search by Sentence ID...">
            <button type="submit">Search</button>
        </form>
        <form action="sentences.php" method="get">
            <input type="text" name="search_criminal_id" placeholder="Search by Criminal ID...">
            <button type="submit">Search</button>
        </form>
        <form action="sentences.php" method="get">
            <input type="date" name="search_start_date" placeholder="Search by Start Date..." onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
            <button type="submit">Search</button>
        </form>
        <form action="sentences.php" method="get">
            <input type="text" name="search_violations" placeholder="Search by Violations...">
            <button type="submit">Search</button>
        </form>
    </div>

<!-- Insert Sentence Form -->
<div class="form-container">
    <h3>Add New Sentence</h3>
    <form action="sentences.php" method="post">
        <input type="text" name="sentence_id" placeholder="Sentence ID" required>
        <input type="text" name="criminal_id" placeholder="Criminal ID" required>
        <input type="text" name="type" placeholder="Type" required>
        <input type="text" name="prob_id" placeholder="Probation Officer ID">
        <input type="date" name="start_date" placeholder="Start Date" required onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
        <input type="date" name="end_date" placeholder="End Date" required onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
        <input type="text" name="violations" placeholder="Violations">
        <button type="submit" name="insert">Add</button>
    </form>
</div>


<!-- Update Sentence Form -->
<div class="form-container">
    <h3>Update Sentence</h3>
    <form action="sentences.php" method="post">
        <input type="text" name="update_sentence_id" placeholder="Sentence ID to Update" required>
        <input type="text" name="new_criminal_id" placeholder="New Criminal ID">
        <input type="text" name="new_type" placeholder="New Type">
        <input type="text" name="new_prob_id" placeholder="New Probation Officer ID">
        <input type="date" name="new_start_date" placeholder="New Start Date" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
        <input type="date" name="new_end_date" placeholder="New End Date" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
        <input type="text" name="new_violations" placeholder="New Violations">
        <button type="submit" name="update">Update</button>
    </form>
</div>


<!-- Delete Sentence Form -->
<div class="form-container">
    <h3>Delete Sentence</h3>
    <form action="sentences.php" method="post">
        <input type="text" name="delete_sentence_id" placeholder="Sentence ID to Delete" required>
        <button type="submit" name="delete">Delete</button>
    </form>
</div>


<!-- Table for displaying search results or all sentences -->
        <table>
            <thead>
                <tr>
                    <th>Sentence ID</th>
                    <th>Criminal ID</th>
                    <th>Type</th>
                    <th>Prob_ID</th>
                    <th>Start date</th>
                    <th>End date</th>
                    <th>Violations</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include your database connection here
                include 'db_connect.php';
                mysqli_query($conn, "LOCK TABLES Sentences READ");
                $sql = "SELECT * FROM Sentences";
                $params = [];
                $types = '';

                if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    if (!empty($_GET['search_sentence_id'])) {
                        $sql .= " WHERE Sentence_ID = ?";
                        $params[] = $_GET['search_sentence_id'];
                        $types .= 'i';
                    } elseif (!empty($_GET['search_criminal_id'])) {
                        $sql .= " WHERE Criminal_ID = ?";
                        $params[] = $_GET['search_criminal_id'];
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
                                    <td>".$row["Sentence_ID"]."</td>
                                    <td>".$row["Criminal_ID"]."</td>
                                    <td>".$row["Type"]."</td>
                                    <td>".$row["Prob_ID"]."</td>
                                    <td>".$row["Start_date"]."</td>
                                    <td>".$row["End_date"]."</td>
                                    <td>".$row["Violations"]."</td>
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
