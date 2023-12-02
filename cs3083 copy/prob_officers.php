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

    mysqli_query($conn, "LOCK TABLES Prob_officers WRITE");

    try {
        $prob_id = $_POST['prob_id'] ?: null;
        $last = $_POST['last'] ?: null;
        $first = $_POST['first'] ?: null;
        $street = $_POST['street'] ?: null;
        $city = $_POST['city'] ?: null;
        $state = $_POST['state'] ?: null;
        $zip = $_POST['zip'] ?: null;
        $phone = $_POST['phone'] ?: null;
        $email = $_POST['email'] ?: null;
        $status = $_POST['status'] ?: null;

        if (isset($_POST['insert'])) {
            // Insert a new probation officer record
            $stmt = $conn->prepare("INSERT INTO Prob_officers (Prob_ID, Last, First, Street, City, State, Zip, Phone, Email, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssssiss", $prob_id, $last, $first, $street, $city, $state, $zip, $phone, $email, $status);
            $stmt->execute();
        }

        if (isset($_POST['update'])) {
            // Update an existing probation officer record
            $stmt = $conn->prepare("UPDATE Prob_officers SET Last = ?, First = ?, Street = ?, City = ?, State = ?, Zip = ?, Phone = ?, Email = ?, Status = ? WHERE Prob_ID = ?");
            $stmt->bind_param("ssssssissi", $last, $first, $street, $city, $state, $zip, $phone, $email, $status, $prob_id);
            $stmt->execute();
        }

        if (isset($_POST['delete'])) {
            // Delete a probation officer record
            $stmt = $conn->prepare("DELETE FROM Prob_officers WHERE Prob_ID = ?");
            $stmt->bind_param("i", $prob_id);
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
      <a href="prob_officers.php" class="active">Prob officers</a>
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
    <!-- Search Bars -->
    <div class="search-container">
        <!-- Search by Prob ID -->
        <form action="prob_officers.php" method="get">
            <input type="text" name="search_prob_id" placeholder="Search by Prob ID...">
            <button type="submit">Search</button>
        </form>

        <!-- Search by Last Name -->
        <form action="prob_officers.php" method="get">
            <input type="text" name="search_last" placeholder="Search by Last Name...">
            <button type="submit">Search</button>
        </form>

        <!-- Search by Zip Code -->
        <form action="prob_officers.php" method="get">
            <input type="text" name="search_zip" placeholder="Search by Zip Code...">
            <button type="submit">Search</button>
        </form>
    </div>



<!-- Add New Probation Officer Form -->
<div class="form-container">
<h3>Add Crime Officer</h3>
<form action="prob_officers.php" method="post">
    <input type="text" name="prob_id" placeholder="Prob ID">
    <input type="text" name="last" placeholder="Last Name">
    <input type="text" name="first" placeholder="First Name">
    <input type="text" name="street" placeholder="Street">
    <input type="text" name="city" placeholder="City">
    <input type="text" name="state" placeholder="State">
    <input type="text" name="zip" placeholder="Zip Code">
    <input type="text" name="phone" placeholder="Phone Number">
    <input type="text" name="email" placeholder="Email">
    <input type="text" name="status" placeholder="Status (e.g., A, I)">
    <button type="submit" name="insert">Add</button>
</form>
</div>

<!-- Update Probation Officer Form -->
<div class="form-container">
<h3>Update Crime Officer</h3>
<form action="prob_officers.php" method="post">
<style>
    .form-spacing {
        margin-bottom: 20px;
    }
</style>
    <input type="text" name="prob_id" placeholder="Prob ID to Update">
    <input type="text" name="last" placeholder="New Last Name">
    <input type="text" name="first" placeholder="New First Name">
    <input type="text" name="street" placeholder="New Street">
    <input type="text" name="city" placeholder="New City">
    <input type="text" name="state" placeholder="New State">
    <input type="text" name="zip" placeholder="New Zip Code">
    <input type="text" name="phone" placeholder="New Phone Number">
    <input type="text" name="email" placeholder="New Email">
    <input type="text" name="status" placeholder="New Status (e.g., A, I)">
    <button type="submit" name="update">Update</button>
</form>
</div>


<!-- Delete Probation Officer Form -->
<div class="form-container">
<h3>Delete Probation Officer</h3>
<form action="prob_officers.php" method="post">
<style>
    .form-spacing {
        margin-bottom: 20px;
    }
</style>
    <input type="text" name="prob_id" placeholder="Prob ID to Delete">
    <button type="submit" name="delete">Delete</button>
</form>
</div>




<!-- Table for displaying search results -->
<table>
    <thead>
        <tr>
            <th>Prob ID</th>
            <th>Last</th>
            <th>First</th>
            <th>Street</th>
            <th>City</th>
            <th>State</th>
            <th>Zip</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include 'db_connect.php';
        mysqli_query($conn, "LOCK TABLES Prob_officers READ");
        $sql = "SELECT * FROM Prob_officers";
        $params = [];
        $types = '';

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (!empty($_GET['search_prob_id'])) {
                $sql .= " WHERE Prob_ID = ?";
                $params[] = $_GET['search_prob_id'];
                $types .= 'i';
            } elseif (!empty($_GET['search_last'])) {
                $sql .= " WHERE Last LIKE ?";
                $params[] = "%".$_GET['search_last']."%";
                $types .= 's';
            } elseif (!empty($_GET['search_zip'])) {
                $sql .= " WHERE Zip = ?";
                $params[] = $_GET['search_zip'];
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
                            <td>".$row["Prob_ID"]."</td>
                            <td>".$row["Last"]."</td>
                            <td>".$row["First"]."</td>
                            <td>".$row["Street"]."</td>
                            <td>".$row["City"]."</td>
                            <td>".$row["State"]."</td>
                            <td>".$row["Zip"]."</td>
                            <td>".$row["Phone"]."</td>
                            <td>".$row["Email"]."</td>
                            <td>".$row["Status"]."</td>
                          </tr>";
                }
                mysqli_query($conn, "UNLOCK TABLES");
            } else {
                mysqli_query($conn, "UNLOCK TABLES");
                echo "<tr><td colspan='10'>No results found</td></tr>";
            }
            $conn->close();
        }
        ?>
    </tbody>
</table>




    </main>
  </body>
</html>
