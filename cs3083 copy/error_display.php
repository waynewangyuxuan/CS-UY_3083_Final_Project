
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
        include 'db_connect.php'; // Database connection

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['update'])) {
                $update_id = $_POST['update_id'];
                $new_alias = $_POST['new_alias'];
                $new_criminal_id = $_POST['new_criminal_id'];

                mysqli_query($conn, "LOCK TABLES Alias WRITE");
                $stmt = $conn->prepare("UPDATE Alias SET Alias = ?, Criminal_ID = ? WHERE Alias_ID = ?");
                $stmt->bind_param("sii", $new_alias, $new_criminal_id, $update_id);
                $stmt->execute();
                mysqli_query($conn, "UNLOCK TABLES");
            }

            if (isset($_POST['insert'])) {
                $alias_id = $_POST['alias_id'];
                $alias = $_POST['alias'];
                $criminal_id = $_POST['criminal_id'];

                mysqli_query($conn, "LOCK TABLES Alias WRITE");
                $stmt = $conn->prepare("INSERT INTO Alias (Alias_ID, Alias, Criminal_ID) VALUES (?, ?, ?)");
                $stmt->bind_param("isi", $alias_id, $alias, $criminal_id);
                $stmt->execute();
                mysqli_query($conn, "UNLOCK TABLES");
            }

            if (isset($_POST['delete'])) {
                $delete_id = $_POST['delete_id'];

                mysqli_query($conn, "LOCK TABLES Alias WRITE");
                $stmt = $conn->prepare("DELETE FROM Alias WHERE Alias_ID = ?");
                $stmt->bind_param("i", $delete_id);
                $stmt->execute();
                mysqli_query($conn, "UNLOCK TABLES");
            }
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
      <a href="crime_codes.php" class="active">Crime codes</a>
    </li>
  </ul>
</nav>


<main>
    <h1>Error in Database Operation</h1>
    <p>
        <?php
        if (isset($_GET['error'])) {
            echo htmlspecialchars($_GET['error']);
        }
        ?>
    </p>
    <button onclick="window.history.back();">Go Back</button>
</main>


        <script>
            <?php if ($operationFailed): ?>
                alert('Operation failed. Please check your input and try again.');
            <?php endif; ?>
        </script>
</body>
</html>
