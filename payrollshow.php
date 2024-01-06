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
</body>
</html>
