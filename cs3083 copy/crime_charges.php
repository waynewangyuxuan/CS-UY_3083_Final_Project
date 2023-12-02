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

    mysqli_query($conn, "LOCK TABLES Crime_charges WRITE");

    try {
        // Prepare and bind parameters for INSERT, UPDATE, DELETE
        if (isset($_POST['insert'])) {
            $charge_id = $_POST['charge_id'] ?: null;
            $crime_id = $_POST['crime_id'] ;
            $crime_code = $_POST['crime_code'] ;
            $charge_status = $_POST['charge_status'] ;
            $fine_amount = $_POST['fine_amount'] ;
            $court_fee = $_POST['court_fee'];
            $amount_paid = $_POST['amount_paid'] ;
            $pay_due_date = $_POST['pay_due_date'] ;

            // Insert a new crime charge record
            $stmt = $conn->prepare("INSERT INTO Crime_charges (Charge_ID, Crime_ID, Crime_code, Charge_status, Fine_amount, Court_fee, Amount_paid, Pay_due_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisddds", $charge_id, $crime_id, $crime_code, $charge_status, $fine_amount, $court_fee, $amount_paid, $pay_due_date);
            $stmt->execute();
        }

        if (isset($_POST['update'])) {
            $charge_id = $_POST['charge_id'] ?: null;
            // Include other fields to be updated
            // Example: $crime_id = $_POST['crime_id'] ?: null;

            // Update an existing crime charge record
            // The SQL query should be adjusted based on the fields you want to update
            $stmt = $conn->prepare("UPDATE Crime_charges SET Crime_ID = ?, ... WHERE Charge_ID = ?");
            $stmt->bind_param("ii", $crime_id, $charge_id);
            $stmt->execute();
        }

        if (isset($_POST['delete'])) {
            $charge_id = $_POST['charge_id'] ?: null;

            // Delete a crime charge record
            $stmt = $conn->prepare("DELETE FROM Crime_charges WHERE Charge_ID = ?");
            $stmt->bind_param("i", $charge_id);
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
      <a href="crime_charges.php" class='active'>Crime charges</a>
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

    <!-- Main Content for Criminals Page -->
    <main>
      <h1>Crime Charges</h1>
<!-- Search Bar -->
<div class="search-container">
    <form action="crime_charges.php" method="get">
        <input type="text" name="search_charge_id" placeholder="Search by Charge ID...">
        <button type="submit">Search</button>
    </form>
    <form action="crime_charges.php" method="get">
        <input type="text" name="search_crime_id" placeholder="Search by Crime ID...">
        <button type="submit">Search</button>
    </form>
</div>



<!-- Insert Form -->
<div class="form-container">
    <h3>Add New Crime Charge</h3>
<form action="crime_charges.php" method="post">
    <input type="number" name="charge_id" placeholder="Charge ID" required>
    <input type="number" name="crime_id" placeholder="Crime ID" required>
    <input type="number" name="crime_code" placeholder="Crime Code" required>
    <input type="text" name="charge_status" placeholder="Charge Status" required>
    <input type="number" step="0.01" name="fine_amount" placeholder="Fine Amount" required>
    <input type="number" step="0.01" name="court_fee" placeholder="Court Fee" required>
    <input type="number" step="0.01" name="amount_paid" placeholder="Amount Paid" required>
    <input type="date" name="pay_due_date" placeholder="Pay Due Date" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
    <button type="submit" name="insert">Add Crime Charge</button>
</form>
</div>


<!-- Update Form -->
<div class="form-container">
    <h3>Update Crime Charge</h3>
<form action="crime_charges.php" method="post">
    <input type="number" name="charge_id" placeholder="Charge ID to Update" required>
    <input type="number" name="crime_id" placeholder="New Crime ID">
    <input type="number" name="crime_code" placeholder="New Crime Code">
    <input type="text" name="charge_status" placeholder="New Charge Status">
    <input type="number" step="0.01" name="fine_amount" placeholder="New Fine Amount">
    <input type="number" step="0.01" name="court_fee" placeholder="New Court Fee">
    <input type="number" step="0.01" name="amount_paid" placeholder="New Amount Paid">
    <input type="date" name="pay_due_date" placeholder="New Pay Due Date" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
    <button type="submit" name="update">Update Crime Charge</button>
</form>

</div>

<!-- Delete Form -->
<div class="form-container">
    <h3>Delete Crime Charge</h3>
<form action="crime_charges.php" method="post">
    <input type="number" name="charge_id" placeholder="Charge ID to Delete" required>
    <button type="submit" name="delete">Delete Crime Charge</button>
</form>
</div>



<!-- Table for displaying search results -->
<table>
    <thead>
        <tr>
            <th>Charge ID</th>
            <th>Crime ID</th>
            <th>Crime Code</th>
            <th>Charge Status</th>
            <th>Fine Amount</th>
            <th>Court Fee</th>
            <th>Amount Paid</th>
            <th>Pay Due Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include 'db_connect.php';

        mysqli_query($conn, "LOCK TABLES Crime_charges READ");


        $sql = "SELECT * FROM Crime_charges";
        $params = [];
        $types = '';

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (!empty($_GET['search_charge_id'])) {
                $sql .= " WHERE Charge_ID = ?";
                $params[] = $_GET['search_charge_id'];
                $types .= 'i';
            } elseif (!empty($_GET['search_crime_id'])) {
                $sql .= " WHERE Crime_ID = ?";
                $params[] = $_GET['search_crime_id'];
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
                            <td>".$row["Charge_ID"]."</td>
                            <td>".$row["Crime_ID"]."</td>
                            <td>".$row["Crime_code"]."</td>
                            <td>".$row["Charge_status"]."</td>
                            <td>".$row["Fine_amount"]."</td>
                            <td>".$row["Court_fee"]."</td>
                            <td>".$row["Amount_paid"]."</td>
                            <td>".$row["Pay_due_date"]."</td>
                          </tr>";
                }
                mysqli_query($conn, "UNLOCK TABLES");
            } else {
                mysqli_query($conn, "UNLOCK TABLES");
                echo "<tr><td colspan='8'>No results found</td></tr>";
            }
            $conn->close();
        }
        ?>
    </tbody>
</table>

    </main>
  </body>
</html>
