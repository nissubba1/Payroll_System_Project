<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/prototype/1.7.3.0/prototype.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    <title>Payroll System</title>
    <link rel="stylesheet" type="text/css" href="employee_form.css">

    <link rel="stylesheet" type="text/css" href="navbar.css">
    <link rel="stylesheet" type="text/css" href="payroll.css">

</head>

<body>
    <?php 

    include 'cnavbar.php';
  include("sqlconnection.php");


  if (isset($_SESSION['employee_id'])) {
    $employeeIdSession = $_SESSION['employee_id']; // Retrieve the employee ID from session

    // Use $employeeId in your SQL query to fetch specific timesheet
    $sql = "SELECT * FROM Payroll LEFT JOIN Employees ON Payroll.EmployeeID = Employees.EmployeeID WHERE Payroll.EmployeeID = $employeeIdSession";
    $result = mysqli_query($con, $sql);

    
    } else {
        echo "<div class='center'><h2>Invalid Employee ID, Session Error</h2></div>";
    
}

    // $result = mysqli_query($con, $sql); ?>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $EmployeeID = intval($_POST["EmployeeID"]);
      $PayrollStartDate = mysqli_real_escape_string($con, $_POST["PayrollStartDate"]);
      $PayrollEndDate = mysqli_real_escape_string($con, $_POST["PayrollEndDate"]);
    
      $insert_query_ded = "INSERT INTO Payroll
    (EmployeeID, PeriodStartDate, PeriodEndDate, GrossIncome, NetIncome, DateProcessed)
VALUES
    ($employeeIdSession, '$PayrollStartDate', '$PayrollEndDate', NULL, NULL, NOW())";

      $insert_result_ded = mysqli_query($con, $insert_query_ded);

      if ($insert_result_ded) {
        echo "<div class='center'><h2>Payroll successfully Added</h2></div>";
        header("Location: client_payroll.php"); // Redirect back to department.php
                exit();
      } else {
        echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
      }
}
      ?>
    <div class="container">
        <h2>Payroll</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewPayroll">
            Add New Payroll
        </button>
        <table class="payroll-table">
            <thead>
                <tr>
                    <th>Payroll ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Employee ID</th>
                    <th>Period Start Date</th>
                    <th>Period End Date</th>
                    <th>Gross Income ($)</th>
                    <th>Net Income ($)</th>
                    <th>Date Processed</th>
                </tr>
            </thead>
            <tbody>

                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $FormatTimeTo12 = date('Y-m-d h:i:s A', strtotime($row["DateProcessed"]));
                        echo "<tr>
                                <td>" . $row["PayrollID"] . "</td>
                                <td>" . $row["FirstName"] . "</td>
                                <td>" . $row["LastName"] . "</td>
                                <td>" . $row["EmployeeID"] . "</td>
                                <td>" . $row["PeriodStartDate"] . "</td>
                                <td>" . $row["PeriodEndDate"] . "</td>
                                <td>" . $row["GrossIncome"] . "</td>
                                <td>" . $row["NetIncome"] . "</td>
                                <td>" . $FormatTimeTo12 . "</td>
                                
                            </tr>";
                    }
                } else {
                    echo "0 results";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Form To add New Payroll -->
    <div class="modal fade" id="addNewPayroll" tabindex="-1" aria-labelledby="addPayrollLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addPayrollLabel">Add New Payroll</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="client_payroll.php" method="POST">

                        <label for="PayrollStartDate">Period Start Date:</label>
                        <input type="date" name="PayrollStartDate" id="addPayrollStartDate" required>

                        <label for="PayrollEndDate">Period End Date:</label>
                        <input type="date" name="PayrollEndDate" id="addPayrollEndDate" required>

                        <script>
                        document.getElementById('addPayrollStartDate').addEventListener('change', function() {
                            var startDate = this.value;
                            var endDateInput = document.getElementById('addPayrollEndDate');
                            endDateInput.min = startDate;

                            // If the current value of endDate is less than startDate, clear it
                            if (endDateInput.value < startDate) {
                                endDateInput.value = '';
                            }
                        });
                        </script>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="payrollSubmit">Save changes</button>

                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



</body>

</html>