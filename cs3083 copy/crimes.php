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
    <script>
        function setupDateInputs() {
            // Convert all date inputs with a placeholder to text type initially
            var dateInputs = document.querySelectorAll('input[type="date"][placeholder]');
            dateInputs.forEach(function (input) {
                input.type = 'text';
                input.addEventListener('focus', function () {
                    this.type = 'date';
                    this.style.color = 'black';
                });
                input.addEventListener('blur', function () {
                    if (this.value === '') {
                        this.type = 'text';
                        this.style.color = 'grey';
                    }
                });
                input.addEventListener('input', function () {
                    if (this.value === '') {
                        this.type = 'text';
                        this.style.color = 'grey';
                    } else {
                        this.style.color = 'black';
                    }
                });
            });
        }

        window.onload = setupDateInputs;
    </script>
  </head>
  <body>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connect.php'; // Ensure this points to your database connection script

    mysqli_query($conn, "LOCK TABLES Crimes WRITE");

    try {
        $crime_id = $_POST['crime_id'] ?: null;
        $criminal_id = $_POST['criminal_id'] ;
        $classification = $_POST['classification'] ;
        $date_charged = $_POST['date_charged'] ;
        $status = $_POST['status'] ;
        $hearing_date = $_POST['hearing_date'] ;
        $appeal_cut_date = $_POST['appeal_cut_date'];

        if (isset($_POST['insert'])) {
            // Insert a new crime record
            $stmt = $conn->prepare("INSERT INTO Crimes (Crime_ID, Criminal_ID, Classification, Date_charged, Status, Hearing_date, Appeal_cut_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisssss", $crime_id, $criminal_id, $classification, $date_charged, $status, $hearing_date, $appeal_cut_date);
            $stmt->execute();
        }

        if (isset($_POST['update'])) {
            // Update an existing crime record
            $stmt = $conn->prepare("UPDATE Crimes SET Criminal_ID = ?, Classification = ?, Date_charged = ?, Status = ?, Hearing_date = ?, Appeal_cut_date = ? WHERE Crime_ID = ?");
            $stmt->bind_param("isssssi", $criminal_id, $classification, $date_charged, $status, $hearing_date, $appeal_cut_date, $crime_id);
            $stmt->execute();
        }

        if (isset($_POST['delete'])) {
            // Delete a crime record
            $stmt = $conn->prepare("DELETE FROM Crimes WHERE Crime_ID = ?");
            $stmt->bind_param("i", $crime_id);
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
      <a href="crimes.php" class="active">Crimes</a>
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
      <a href="crime_codes.php" >Crime codes</a>
    </li>
  </ul>
</nav>

    <!-- Main Content for Criminals Page -->
    <main>
      <h1>Crimes</h1>

    <!-- Search Bars -->
    <div class="search-container">
        <form action="crimes.php" method="get">
            <input type="text" name="search_crime_id" placeholder="Search by Crime ID...">
            <button type="submit">Search</button>
        </form>
        <form action="crimes.php" method="get">
            <input type="text" name="search_criminal_id" placeholder="Search by Criminal ID...">
            <button type="submit">Search</button>
        </form>
        <form action="crimes.php" method="get">
            <input type="text" name="search_classification" placeholder="Search by Classification...">
            <button type="submit">Search</button>
        </form>
        <form action="crimes.php" method="get">
            <input type="date" name="search_date_charged" placeholder="Search Date Charged..." onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Insert Crime Form -->
     <div class="form-container">
         <h3>Add New Crime</h3>
         <form action="crimes.php" method="post">
        <style>
            .form-spacing {
                margin-bottom: 20px;
            }
        </style>
             <input type="text" name="crime_id" placeholder="Crime ID">
             <input type="text" name="criminal_id" placeholder="Criminal ID">
             <input type="text" name="classification" placeholder="Classification">
             <input type="date" name="_date_charged" placeholder="Date Charged" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
             <input type="text" name="status" placeholder="Status">
             <input type="date" name="hearing_date_" placeholder="Hearing Date" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
             <input type="date" name="appeal_cut_date" placeholder="Appeal Cut Date" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
             <button type="submit" name="insert">Add</button>
         </form>
     </div>



    <div class="form-container">
        <h3>Update Crime</h3>
        <form action="crimes.php" method="post">
        <style>
            .form-spacing {
                margin-bottom: 20px;
            }
        </style>
            <input type="text" name="update_crime_id" placeholder="Crime ID to Update">
            <input type="text" name="new_criminal_id" placeholder="New Criminal ID">
            <input type="text" name="new_classification" placeholder="New Classification">
            <input type="date" name="new_date_charged" placeholder="New Date Charged" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
            <input type="text" name="new_status" placeholder="New Status">
            <input type="date" name="new_hearing_date" placeholder="New Hearing Date" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
            <input type="date" name="new_appeal_cut_date" placeholder="New Appeal Cut Date" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
            <button type="submit" name="update">Update</button>
        </form>
    </div>



     <!-- Delete Crime Form -->
     <div class="form-container">
         <h3>Delete Crime</h3>
         <form action="crimes.php" method="post">
        <style>
            .form-spacing {
                margin-bottom: 20px;
            }
        </style>
             <input type="text" name="delete_crime_id" placeholder="Crime ID to Delete">
             <button type="submit" name="delete">Delete</button>
         </form>
     </div>





        <!-- Table for displaying search results -->
        <table>
            <thead>
                <tr>
                    <th>Crime ID</th>
                    <th>Criminal ID</th>
                    <th>Classification</th>
                    <th>Date charged</th>
                    <th>Status</th>
                    <th>Hearing date</th>
                    <th>Appeal cut date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'db_connect.php';

                mysqli_query($conn, "LOCK TABLES Crimes READ");
                $sql = "SELECT * FROM Crimes";
                $params = [];
                $types = '';

                if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    if (!empty($_GET['search_crime_id'])) {
                        $sql .= " WHERE Crime_ID = ?";
                        $params[] = $_GET['search_crime_id'];
                        $types .= 'i';
                    } elseif (!empty($_GET['search_criminal_id'])) {
                        $sql .= " WHERE Criminal_ID = ?";
                        $params[] = $_GET['search_criminal_id'];
                        $types .= 'i';
                    } elseif (!empty($_GET['search_classification'])) {
                        $sql .= " WHERE Classification LIKE ?";
                        $params[] = "%".$_GET['search_classification']."%";
                        $types .= 's';
                    } elseif (!empty($_GET['search_date_charged'])) {
                        $sql .= " WHERE Date_charged = ?";
                        $params[] = $_GET['search_date_charged'];
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
                                    <td>".$row["Crime_ID"]."</td>
                                    <td>".$row["Criminal_ID"]."</td>
                                    <td>".$row["Classification"]."</td>
                                    <td>".$row["Date_charged"]."</td>
                                    <td>".$row["Status"]."</td>
                                    <td>".$row["Hearing_date"]."</td>
                                    <td>".$row["Appeal_cut_date"]."</td>
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
