<!-- navbar.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="navbar.css">
    <link rel="stylesheet" type="text/css" href="dropdown.css">
    <title>admin dash</title>
</head>

<body>
    <?php
            session_start(); // Start the session
            include("sqlconnection.php");

            if (isset($_SESSION['employee_id'])) {
    $employeeIdSession = $_SESSION['employee_id']; // Retrieve the employee ID from session

    // Use $employeeId in your SQL query to fetch specific timesheet
    $sql = "SELECT * FROM Employees
 WHERE EmployeeID = $employeeIdSession";
    $result = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $firstName = $row['FirstName']; // Fetch the first name
    } else {
        echo "<div class='center'><h2>Employee not found</h2></div>";
    }


    } else {
        echo "<div class='center'><h2>Invalid Employee ID, Session Error</h2></div>";

}

    ?>
    <div class="Main-buttons">
        <!-- <a href="admindash.php" class="cool-button animated-button">Home</a> -->
        <a href="attentance.php" class="cool-button animated-button">Attendance</a>
        <a href="payroll.php" class="cool-button animated-button">Payroll</a>
        <a href="employee.php" class="cool-button animated-button">Employee List</a>
        <a href="department.php" class="cool-button animated-button">Department</a>
        <a href="deductionb.php" class="cool-button animated-button">Deduction List</a>
    </div>
    <div class="dropdown">
        <a href="dropdown.php" class="cool-button animated-button">Log Out</a>
    </div>
    <!-- <div class="name">
          <p class="name-cool">Hello Admin</p>
    </div> -->
    <div class="helloname">
        <?php
        if (isset($firstName)) {
        echo "<p class='cool-name'>Welcome Admin, " . $firstName . "</p>";
    } else {
        echo "<p class='cool-name'>Welcome Admin</p>";
    }

        ?>
    </div>

</body>

</html>
