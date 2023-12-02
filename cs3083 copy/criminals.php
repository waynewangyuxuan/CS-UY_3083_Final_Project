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
    include 'db_connect.php';

    mysqli_query($conn, "LOCK TABLES Criminals WRITE");

    try {
        if (isset($_POST['update'])) {
            // Extract all fields from POST, use null for empty values
            $criminal_id = $_POST['criminal_id'];
            $last = $_POST['last'] ?: null;
            $first = $_POST['first'] ?: null;
            $street = $_POST['street'] ?: null;
            $city = $_POST['city'] ?: null;
            $state = $_POST['state'] ?: null;
            $zip = $_POST['zip'] ?: null;
            $phone = $_POST['phone'] ?: null;
            $v_status = $_POST['v_status'] ?: null;
            $p_status = $_POST['p_status'] ?: null;

            $stmt = $conn->prepare("UPDATE Criminals SET Last=?, First=?, Street=?, City=?, State=?, Zip=?, Phone=?, V_status=?, P_status=? WHERE Criminal_ID=?");
            $stmt->bind_param("sssssssssi", $last, $first, $street, $city, $state, $zip, $phone, $v_status, $p_status, $criminal_id);
            if (!$stmt->execute()) {
                throw new Exception("Error executing MySQL query: " . $stmt->error);
            }
        }

        if (isset($_POST['insert'])) {
            // Extract all fields from POST, use null for empty values
            $criminal_id = $_POST['criminal_id'];
            $last = $_POST['last'] ?: null;
            $first = $_POST['first'] ?: null;
            $street = $_POST['street'] ?: null;
            $city = $_POST['city'] ?: null;
            $state = $_POST['state'] ?: null;
            $zip = $_POST['zip'] ?: null;
            $phone = $_POST['phone'] ?: null;
            $v_status = $_POST['v_status'] ?: null;
            $p_status = $_POST['p_status'] ?: null;

            $stmt = $conn->prepare("INSERT INTO Criminals (Criminal_ID, Last, First, Street, City, State, Zip, Phone, V_status, P_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssssssss", $criminal_id, $last, $first, $street, $city, $state, $zip, $phone, $v_status, $p_status);
            if (!$stmt->execute()) {
                throw new Exception("Error executing MySQL query: " . $stmt->error);
            }
        }

        if (isset($_POST['delete'])) {
            $criminal_id = $_POST['criminal_id'];

            $stmt = $conn->prepare("DELETE FROM Criminals WHERE Criminal_ID=?");
            $stmt->bind_param("i", $criminal_id);
            if (!$stmt->execute()) {
                throw new Exception("Error executing MySQL query: " . $stmt->error);
            }
        }
    } catch (Exception $e) {
        mysqli_query($conn, "UNLOCK TABLES");
        $errorMessage = urlencode($e->getMessage());
        header("Location: error_display.php?error=$errorMessage");
        exit;
    }

    mysqli_query($conn, "UNLOCK TABLES");
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
      <a href="criminals.php" class="active">Criminals</a>
    </li>
    <li>
      <a href="crimes.php">Crimes</a>
    </li>
    <li>
      <a href="sentences.php">Sentences</a>
    </li>
    <li>
      <a href="prob_officer.php">Prob officers</a>
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
      <a href="crime_codes.php" >Crime codes</a>
    </li>
  </ul>
</nav>

    <!-- Main Content for Criminals Page -->
<main>
        <h1>Criminals</h1>
        
        <!-- Search Bars -->
        <!-- Search by Criminal ID -->
        <div class="search-container">
            <form action="criminals.php" method="get">
                <input type="text" name="search_criminal_id" placeholder="Search by Criminal ID...">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Search by Phone -->
        <div class="search-container">
            <form action="criminals.php" method="get">
                <input type="text" name="search_phone" placeholder="Search by Phone...">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Search by Last Name -->
        <div class="search-container">
            <form action="criminals.php" method="get">
                <input type="text" name="search_last_name" placeholder="Search by Last Name...">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Search by V_status -->
        <div class="search-container">
            <form action="criminals.php" method="get">
                <input type="text" name="search_v_status" placeholder="Search by V Status...">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Search by P_status -->
        <div class="search-container">
            <form action="criminals.php" method="get">
                <input type="text" name="search_p_status" placeholder="Search by P Status...">
                <button type="submit">Search</button>
            </form>
        </div>

<hr> <!-- Horizontal line as a separator -->


        <!-- Add, Update, Delete Forms -->
        <!-- Insert Criminal Form -->
        <div class="form-container">
            <h3>Add New Criminal</h3>
            <form action="criminals.php" method="post">
                <input type="number" name="criminal_id" placeholder="Criminal ID" required>
                <input type="text" name="last" placeholder="Last Name">
                <input type="text" name="first" placeholder="First Name">
                <input type="text" name="street" placeholder="Street">
                <input type="text" name="city" placeholder="City">
                <input type="text" name="state" placeholder="State">
                <input type="text" name="zip" placeholder="Zip">
                <input type="text" name="phone" placeholder="Phone">
                <input type="text" name="v_status" placeholder="V Status">
                <input type="text" name="p_status" placeholder="P Status">
                <button type="submit" name="insert">Add</button>
            </form>
        </div>
<hr> <!-- Horizontal line as a separator -->
        <!-- Update Criminal Form -->
        <div class="form-container">
            <h3>Update Criminal</h3>
            <form action="criminals.php" method="post">
                <input type="number" name="criminal_id" placeholder="Criminal ID" required>
                <input type="text" name="last" placeholder="Last Name">
                <input type="text" name="first" placeholder="First Name">
                <input type="text" name="street" placeholder="Street">
                <input type="text" name="city" placeholder="City">
                <input type="text" name="state" placeholder="State">
                <input type="text" name="zip" placeholder="Zip">
                <input type="text" name="phone" placeholder="Phone">
                <input type="text" name="v_status" placeholder="V Status">
                <input type="text" name="p_status" placeholder="P Status">
                <button type="submit" name="update">Update</button>
            </form>
        </div>
<hr> <!-- Horizontal line as a separator -->
        <!-- Delete Criminal Form -->
        <div class="form-container">
            <h3>Delete Criminal</h3>
            <form action="criminals.php" method="post">
                <input type="number" name="criminal_id" placeholder="Criminal ID to Delete" required>
                <button type="submit" name="delete">Delete</button>
            </form>
        </div>
<hr> <!-- Horizontal line as a separator -->
        <!-- Table for Displaying Search Results -->

    <table>
        <thead>
            <tr>
                <th>Criminal ID</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Street</th>
                <th>City</th>
                <th>State</th>
                <th>Zip</th>
                <th>Phone</th>
                <th>V Status</th>
                <th>P Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'db_connect.php'; // Include your DB connection script
mysqli_query($conn, "LOCK TABLES Criminals READ");
            $sql = "SELECT * FROM Criminals";

            $params = [];
            $types = '';

            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                if (!empty($_GET['search_criminal_id'])) {
                    $sql .= " WHERE Criminal_ID = ?";
                    $params[] = $_GET['search_criminal_id'];
                    $types .= 'i';
                } elseif (!empty($_GET['search_phone'])) {
                    $sql .= " WHERE Phone LIKE ?";
                    $params[] = "%".$_GET['search_phone']."%";
                    $types .= 's';
                } elseif (!empty($_GET['search_last_name'])) {
                    $sql .= " WHERE Last LIKE ?";
                    $params[] = "%".$_GET['search_last_name']."%";
                    $types .= 's';
                } elseif (!empty($_GET['search_v_status'])) {
                    $sql .= " WHERE V_status = ?";
                    $params[] = $_GET['search_v_status'];
                    $types .= 's';
                } elseif (!empty($_GET['search_p_status'])) {
                    $sql .= " WHERE P_status = ?";
                    $params[] = $_GET['search_p_status'];
                    $types .= 's';
                }
                }
                
                $stmt = $conn->prepare($sql);
                if ($params) {
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                    <td>".$row["Criminal_ID"]."</td>
                                    <td>".$row["Last"]."</td>
                                    <td>".$row["First"]."</td>
                                    <td>".$row["Street"]."</td>
                                    <td>".$row["City"]."</td>
                                    <td>".$row["State"]."</td>
                                    <td>".$row["Zip"]."</td>
                                    <td>".$row["Phone"]."</td>
                                    <td>".$row["V_status"]."</td>
                                    <td>".$row["P_status"]."</td>
                            </tr>";
                        }
                    mysqli_query($conn, "UNLOCK TABLES");
                    } else {
                        mysqli_query($conn, "UNLOCK TABLES");
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

