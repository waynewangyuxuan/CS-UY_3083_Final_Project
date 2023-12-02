
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
    <!-- PHP Code to Handle Form Submissions -->
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include 'db_connect.php'; // Include your database connection script

            mysqli_query($conn, "LOCK TABLES Alias WRITE"); // Lock the Alias table for writing

            try {
                if (isset($_POST['update'])) {
                    $update_id = $_POST['update_id'];
                    $new_alias = $_POST['new_alias'];
                    $new_criminal_id = $_POST['new_criminal_id'];

                    $stmt = $conn->prepare("UPDATE Alias SET Alias = ?, Criminal_ID = ? WHERE Alias_ID = ?");
                    $stmt->bind_param("sii", $new_alias, $new_criminal_id, $update_id);
                    if (!$stmt->execute()) {
                        throw new Exception("Error executing MySQL query: " . $stmt->error);
                    }
                }

                if (isset($_POST['insert'])) {
                    $alias_id = $_POST['alias_id'];
                    $alias = $_POST['alias'];
                    $criminal_id = $_POST['criminal_id'];

                    $stmt = $conn->prepare("INSERT INTO Alias (Alias_ID, Alias, Criminal_ID) VALUES (?, ?, ?)");
                    $stmt->bind_param("isi", $alias_id, $alias, $criminal_id);
                    if (!$stmt->execute()) {
                        throw new Exception("Error executing MySQL query: " . $stmt->error);
                    }
                }

                if (isset($_POST['delete'])) {
                    $delete_id = $_POST['delete_id'];

                    $stmt = $conn->prepare("DELETE FROM Alias WHERE Alias_ID = ?");
                    $stmt->bind_param("i", $delete_id);
                    if (!$stmt->execute()) {
                        throw new Exception("Error executing MySQL query: " . $stmt->error);
                    }
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
      <a href="alias.php" class="active">Alias</a>
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
      <a href="officers.php">Officers</a>
    </li>
    <li>
      <a href="appeals.php">Appeals</a>
    </li>
    <li>
      <a href="crime_codes.php">Crime codes</a>
    </li>
  </ul>
</nav>

<!-- Main Content for Alias Page -->
<main>
        <h1>Alias</h1>
        
        <!-- Search Bar for Alias -->
        <div class="search-container">
            <form action="alias.php" method="get">
                <input type="text" name="search_alias" placeholder="Search for an alias...">
                <button type="submit">Search by Alias</button>
            </form>
        </div>

        <!-- Search Bar for Criminal ID -->
        <div class="search-container">
            <form action="alias.php" method="get">
                <input type="text" name="search_criminal_id" placeholder="Search for a criminal ID...">
                <button type="submit">Search by Criminal ID</button>
            </form>
        </div>
<hr> <!-- Horizontal line as a separator -->

        <!-- Insert Alias Form -->
        <div class="form-container">
            <h3>Add New Alias</h3>
            <form action="alias.php" method="post">
                <input type="text" name="alias_id" placeholder="Alias ID">
                <input type="text" name="alias" placeholder="Alias">
                <input type="text" name="criminal_id" placeholder="Criminal ID">
                <button type="submit" name="insert">Add</button>
            </form>
        </div>
<hr> <!-- Horizontal line as a separator -->
        <!-- Update Alias Form -->
        <div class="form-container">
            <h3>Update Alias</h3>
            <form action="alias.php" method="post">
                <input type="text" name="update_id" placeholder="Alias ID to Update">
                <input type="text" name="new_alias" placeholder="New Alias">
                <input type="text" name="new_criminal_id" placeholder="New Criminal ID">
                <button type="submit" name="update">Update</button>
            </form>
        </div>
<hr> <!-- Horizontal line as a separator -->
        <!-- Delete Alias Form -->
        <div class="form-container">
            <h3>Delete Alias</h3>
            <form action="alias.php" method="post">
                <input type="text" name="delete_id" placeholder="Alias ID to Delete">
                <button type="submit" name="delete">Delete</button>
            </form>
        </div>
<hr> <!-- Horizontal line as a separator -->
        <!-- Table for displaying search results -->
        <table>
            <thead>
                <tr>
                    <th>Alias ID</th>
                    <th>Criminal ID</th>
                    <th>Alias</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'db_connect.php'; // Include your DB connection script

                $sql = "SELECT * FROM Alias";
                $params = [];

                if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    if (!empty($_GET['search_alias'])) {
                        $sql .= " WHERE Alias LIKE ?";
                        $params[] = "%".$_GET['search_alias']."%";
                    } elseif (!empty($_GET['search_criminal_id'])) {
                        $sql .= " WHERE Criminal_ID = ?";
                        $params[] = $_GET['search_criminal_id'];
                    }
                }

                // Prepared statement to prevent SQL injection
                $stmt = $conn->prepare($sql);
                if ($params) {
                    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
                }
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>".$row["Alias_ID"]."</td>
                                <td>".$row["Criminal_ID"]."</td>
                                <td>".$row["Alias"]."</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No results found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </main>
        <script>
            <?php if ($operationFailed): ?>
                alert('Operation failed. Please check your input and try again.');
            <?php endif; ?>
        </script>
</body>
</html>
