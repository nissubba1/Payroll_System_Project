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
    include 'navbar.php';
    include("sqlconnection.php");

    $sql = "SELECT * FROM Payroll";
    $result = mysqli_query($con, $sql);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["payrollSubmit"])) {
        $EmployeeID = intval($_POST["EmployeeID"]);
        $PayrollStartDate = mysqli_real_escape_string($con, $_POST["PayrollStartDate"]);
        $PayrollEndDate = mysqli_real_escape_string($con, $_POST["PayrollEndDate"]);

        $insert_query_ded = "INSERT INTO Payroll (EmployeeID, PeriodStartDate, PeriodEndDate, GrossIncome, NetIncome, DateProcessed) VALUES ($EmployeeID, '$PayrollStartDate', '$PayrollEndDate', NULL, NULL, NOW())";

        $insert_result_ded = mysqli_query($con, $insert_query_ded);

        if ($insert_result_ded) {
            echo "<div class='center'><h2>Payroll successfully Added</h2></div>";
            header("Location: payroll.php"); // Redirect back to department.php
            exit();
        } else {
            echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
        }
        } elseif (isset($_POST["editPayrollbtn"])) {
            $EditEmployeeID = intval($_POST["editEmployeeID"]);
            $EditPayrollID = intval($_POST["editPayrollID"]);
            $editPayrollEndDate = mysqli_real_escape_string($con, $_POST["editPayrollEndDate"]);
            $editPayrollStartDate = mysqli_real_escape_string($con, $_POST["editPayrollStartDate"]);

            // Check if that payroll id exist
            $checkPayrollExistence = "SELECT PayrollID FROM Payroll WHERE PayrollID = $EditPayrollID";
            $PayrollExistCheck = mysqli_query($con, $checkPayrollExistence);

            if ($PayrollExistCheck && mysqli_num_rows($PayrollExistCheck) > 0) {
                $update_payroll_sql = "UPDATE Payroll
                                    SET PeriodStartDate = '$editPayrollStartDate',
                                        PeriodEndDate = '$editPayrollEndDate',
                                        DateProcessed = NOW()
                                    WHERE PayrollID = $EditPayrollID";

                $update_sql_result = mysqli_query($con, $update_payroll_sql);

                if ($update_sql_result) {
                    echo "<div class='center'><h2>Payroll successfully Updated</h2></div>";
                    header("Location: payroll.php"); // Redirect back to department.php
                exit();
                } else {
                    echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
                }
            }

        } elseif (isset($_POST["deletePayrollbtn"])) {
            $DeletePayrollID = intval($_POST["DeletePayrollID"]);

            // Check if that payroll id exist
            $checkPayrollExistence = "SELECT PayrollID FROM Payroll WHERE PayrollID = $DeletePayrollID";
            $PayrollExistCheck = mysqli_query($con, $checkPayrollExistence);

            if ($PayrollExistCheck && mysqli_num_rows($PayrollExistCheck) > 0) {
                $deletePayroll_sql = "DELETE FROM Payroll WHERE PayrollID = $DeletePayrollID";
                $delete_payroll_result = mysqli_query($con, $deletePayroll_sql);

                if ($delete_payroll_result) {
                    echo "<div class='center'><h2>Payroll successfully Deleted</h2></div>";
                    header("Location: payroll.php"); // Redirect back to department.php
                exit();
                } else {
                    echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
                }
            }

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
                    <th>Employee ID</th>
                    <th>Period Start Date</th>
                    <th>Period End Date</th>
                    <th>Gross Income ($)</th>
                    <th>Net Income ($)</th>
                    <th>Date Processed</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $FormatTimeTo12 = date('Y-m-d h:i:s A', strtotime($row["DateProcessed"]));
                        echo "<tr>
                                <td>" . $row["PayrollID"] . "</td>
                                <td>" . $row["EmployeeID"] . "</td>
                                <td>" . $row["PeriodStartDate"] . "</td>
                                <td>" . $row["PeriodEndDate"] . "</td>
                                <td>" . $row["GrossIncome"] . "</td>
                                <td>" . $row["NetIncome"] . "</td>
                                <td>" . $FormatTimeTo12 . "</td>
                                <td>
                                    <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editPayrollIDForm'
                                    data-payroll-id='" . $row["PayrollID"] . "' data-emp-id='" . $row["EmployeeID"] . "' onclick='editPayroll(this)'
                                    style='margin-right: 10px;'>Edit</button>

                                    <button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deletePayrollIDForm' " .
                                    "onclick='deletePayroll(this)' data-payroll-id='" . $row["PayrollID"] . "'>Delete</button>
                                </td>
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
                    <form action="payroll.php" method="POST">
                        <label for="EmployeeID">Employee ID:</label>
                        <input type="number" name="EmployeeID" required>

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

    <!-- Form To Edit Payroll -->
    <div class="modal fade" id="editPayrollIDForm" tabindex="-1" aria-labelledby="editPayrollLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editPayrollLabel">Edit Payroll</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="payroll.php" method="POST">
                        <label for="editPayrollID">Payroll ID to Edit:</label>
                        <input type="number" name="editPayrollID" required>

                        <label for="editEmployeeID">Employee ID:</label>
                        <input type="number" name="editEmployeeID" required disabled>

                        <label for="editPayrollStartDate">Period Start Date:</label>
                        <input type="date" id="editPayrollStartDate" name="editPayrollStartDate" required>

                        <label for="editPayrollEndDate">Period End Date:</label>
                        <input type="date" id="editPayrollEndDate" name="editPayrollEndDate" required>

                        <script>
                        document.getElementById('editPayrollStartDate').addEventListener('change', function() {
                            var startDate = this.value;
                            var endDateInput = document.getElementById('editPayrollEndDate');
                            endDateInput.min = startDate;

                            // If the current value of endDate is less than startDate, clear it
                            if (endDateInput.value < startDate) {
                                endDateInput.value = '';
                            }
                        });
                        </script>


                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="editPayrollbtn">Save changes</button>
                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Form To Delete Payroll -->
    <div class="modal fade" id="deletePayrollIDForm" tabindex="-1" aria-labelledby="deletePayrollLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deletePayrollLabel">Delete Payroll</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="payroll.php" method="POST">
                        <label for="DeletePayrollID">Payroll ID to Delete:</label>
                        <input type="number" name="DeletePayrollID" required>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="deletePayrollbtn">Save changes</button>
                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    function editPayroll(buttonElement) {
        // Get payroll and employee IDs
        var payrollID = buttonElement.getAttribute('data-payroll-id');
        var employeeID = buttonElement.getAttribute('data-emp-id');

        // Set the payroll and employee IDs in the form
        document.querySelector('#editPayrollIDForm input[name="editPayrollID"]').value = payrollID;
        document.querySelector('#editPayrollIDForm input[name="editEmployeeID"]').value = employeeID;
    }

    function deletePayroll(buttonElement) {
        // Get payroll and employee IDs
        var payrollID = buttonElement.getAttribute('data-payroll-id');

        // Set the employee IDs in the form
        document.querySelector('#deletePayrollIDForm input[name="DeletePayrollID"]').value = payrollID;
    }
    </script>
    <script src="scripts.js"></script>
</body>
</html>
