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
    <link rel="stylesheet" type="text/css" href="employee_form.css">

    <link rel="stylesheet" type="text/css" href="attentancee.css">
    <link rel="stylesheet" type="text/css" href="navbar.css">

    <title>Timesheet</title>
</head>

<body>
    <?php 
    // session_start(); // Start the session

    include 'cnavbar.php';
  include("sqlconnection.php");

  if (isset($_SESSION['employee_id'])) {
    $employeeIdSession = $_SESSION['employee_id']; // Retrieve the employee ID from session

    // Use $employeeId in your SQL query to fetch specific timesheet
    $sql = "SELECT * FROM Timesheet LEFT JOIN Employees ON Timesheet.EmployeeID = Employees.EmployeeID WHERE Timesheet.EmployeeID = $employeeIdSession";
    $result = mysqli_query($con, $sql);
    

    
    } else {
        echo "<div class='center'><h2>Invalid Employee ID, Session Error</h2></div>";
    
}

    ?>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["clockInBtn"])) {
        // $EmployeeID = intval($_POST["EmployeeID"]);
        $StartTime = mysqli_real_escape_string($con, $_POST["StartTime"]);

        $sqlStartDatetime = date("Y-m-d H:i:s", strtotime($StartTime));

        $checkempexist = "SELECT EmployeeID FROM Employees WHERE EmployeeID = $employeeIdSession";
        $sql_emp_query = mysqli_query($con,$checkempexist );

        if ($sql_emp_query && mysqli_num_rows($sql_emp_query) > 0) {
            $insert_query_ded = "INSERT INTO Timesheet (EmployeeID, StartTime) VALUES ($employeeIdSession, '$sqlStartDatetime')";

            $insert_result_ded = mysqli_query($con, $insert_query_ded);

            if ($insert_result_ded) {
                echo "<div class='center'><h2>Successfully Clock-In</h2></div>";
                header("Location: client_attandance.php"); // Redirect back to department.php
            exit();
            } else {
                echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
            }

        } else {
            echo "<div class='center'><h2>Invalid Employee ID, Try Again</h2></div>";
        }

       
        
    } elseif (isset($_POST["clockOutBtn"])) {
        $TimesheetID = intval($_POST["TimesheetID"]);
        $EndTime = mysqli_real_escape_string($con, $_POST["EndTime"]);

        $sqlEndDatetime = date("Y-m-d H:i:s", strtotime($EndTime));

         $checktimeidexist = "SELECT TimesheetID FROM Timesheet WHERE TimesheetID = $TimesheetID";
        $sql_timedid_query = mysqli_query($con,$checktimeidexist );

        if ($sql_timedid_query && mysqli_num_rows($sql_timedid_query) > 0)
        {
            // Fetch the start time for the given TimesheetID
            $fetchStartTimeQuery = "SELECT StartTime FROM Timesheet WHERE TimesheetID = $TimesheetID";
            $startTimeResult = mysqli_query($con, $fetchStartTimeQuery);
            $startTimeRow = mysqli_fetch_assoc($startTimeResult);
            $startTime = $startTimeRow['StartTime'];
            
            if (strtotime($sqlEndDatetime) > strtotime($startTime)) {
                $insert_query_ded = "UPDATE Timesheet SET EndTime = '$sqlEndDatetime' WHERE TimesheetID = $TimesheetID";

                $insert_result_ded = mysqli_query($con, $insert_query_ded);

                if ($insert_result_ded) {
                    echo "<div class='center'><h2>Successfully Clock-Out</h2></div>";
                    header("Location: client_attandance.php"); // Redirect back to department.php
                exit();
                } else {
                    echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
                }

        
            } else {
                echo "<div class='center'><h2>Clock-Out Time cannot be before the Clock-In Time</h2></div>";
            }


            
        } else {
            echo "<div class='center'><h2>Invalid Timesheet ID, Try Again</h2></div>";
        }

        
    } elseif (isset($_POST["overtimeBtn"])) {
        $TimesheetID = intval($_POST["TimesheetID"]);
        $Overtimeid = intval($_POST["Overtimeid"]);

        $checktimeidexist = "SELECT TimesheetID FROM Timesheet WHERE TimesheetID = $TimesheetID";
        $sql_timedid_query = mysqli_query($con,$checktimeidexist );

        if ($sql_timedid_query && mysqli_num_rows($sql_timedid_query) > 0) {
            $insert_query_ded = "UPDATE Timesheet SET OvertimeHours = $Overtimeid WHERE TimesheetID = $TimesheetID";

        $insert_result_ded = mysqli_query($con, $insert_query_ded);

        if ($insert_result_ded) {
            echo "<div class='center'><h2>Successfully Added Overtime</h2></div>";
            header("Location: client_attandance.php"); // Redirect back to department.php
          exit();
        } else {
            echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
        }

        } else {
            echo "<div class='center'><h2>Invalid Timesheet ID, Try Again</h2></div>";
        }

    }


}
      ?>
    <div class="container">
        <h2>TimeSheet</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewClockIn"
            style="margin-right: 10px;">
            Clock-In
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewClockOut"
            style="margin-right: 10px;">
            Clock-Out
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewOvertime">
            Overtime
        </button>

        <table>
            <thead>
                <tr>
                    <th>Timesheet ID</th>
                    <th>Employee ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>StartTime</th>
                    <th>EndTime</th>
                    <th>Total Hours Worked</th>
                    <th>OverTime</th>
                    <!-- <th>Action</th> -->
                </tr>
            </thead>
            <tbody>

                <?php
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {

            $StartTimeFormat12 = date('Y-m-d h:i:s A', strtotime($row["StartTime"]));
            // $EndTimeFormat12 = date('Y-m-d h:i:s A', strtotime($row["EndTime"]));

        $EndTimeFormat12 = '';

        // Check if EndTime is not null and format it
        if (!is_null($row["EndTime"])) {
            $EndTimeFormat12 = date('Y-m-d h:i:s A', strtotime($row["EndTime"]));
        }



        echo "<tr><td>" . $row["TimesheetID"]. "</td><td>" . $row["EmployeeID"]. "</td> <td>" . $row["FirstName"]. "</td>
        <td>" . $row["LastName"]. "</td><td>". $StartTimeFormat12. " </td><td>" . $EndTimeFormat12. "</td><td>" . $row["TotalHoursWorked"]. " </td><td>".
        $row["OvertimeHours"]. " </td></tr>";
    }
} else {
    echo "0 results";
}
        ?>
            </tbody>
        </table>
    </div>


    <!-- Timesheet Forms -->
    <!-- Form To Add new clock in -->
    <div class="modal fade" id="addNewClockIn" tabindex="-1" aria-labelledby="addClockInLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addClockInLabel">Clock-In</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="client_attandance.php" method="POST">
                        <!-- <label for="EmployeeID">Employee ID:</label>
                        <input type="number" name="EmployeeID" required> -->

                        <label for="StartTime">Clock-In Time:</label>
                        <input type="datetime-local" name="StartTime" required id="clockinTimeID">

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="clockInBtn">Save changes</button>

                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Clock Out -->
    <div class="modal fade" id="addNewClockOut" tabindex="-1" aria-labelledby="addClockOutLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addClockOutLabel">Clock-Out</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="client_attandance.php" method="POST">
                        <label for="TimesheetID">Timesheet ID:</label>
                        <input type="number" name="TimesheetID" required>

                        <label for="EndTime">Clock-Out Time:</label>
                        <input type="datetime-local" name="EndTime" required id="clockouttimeID">


                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="clockOutBtn">Save changes</button>

                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Overtime -->
    <div class="modal fade" id="addNewOvertime" tabindex="-1" aria-labelledby="addOvertimeLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addOvertimeLabel">Add Overtime</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="client_attandance.php" method="POST">
                        <label for="TimesheetID">Timesheet ID:</label>
                        <input type="number" name="TimesheetID" required>

                        <label for="Overtimeid">Overtime Hours:</label>
                        <input type="number" name="Overtimeid" min="0" required>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="overtimeBtn">Save changes</button>

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