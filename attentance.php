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
    include 'navbar.php';
    include("sqlconnection.php");

    $sql = "SELECT * FROM Timesheet";
    $result = mysqli_query($con, $sql);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["timesheetbtn"])) {
        $EmployeeID = intval($_POST["EmployeeID"]);
        $StartTime = mysqli_real_escape_string($con, $_POST["StartTime"]);
        $EndTime = mysqli_real_escape_string($con, $_POST["EndTime"]);
        $OvertimeHours = intval($_POST["OvertimeHours"]);
        // $TotalHour = intval($_POST["TotalHour"]);

        $sqlStartDatetime = date("Y-m-d H:i:s", strtotime($StartTime));
        $sqlEndDatetime = date("Y-m-d H:i:s", strtotime($EndTime));

        $checkempexist = "SELECT EmployeeID FROM Employees WHERE EmployeeID = $EmployeeID";
        $sql_emp_query = mysqli_query($con,$checkempexist );

        if ($sql_emp_query && mysqli_num_rows($sql_emp_query) > 0) {
            $insert_query_ded = "INSERT INTO Timesheet (EmployeeID, StartTime, EndTime, TotalHoursWorked, OvertimeHours) VALUES ($EmployeeID, '$sqlStartDatetime', '$sqlEndDatetime', NULL, $OvertimeHours)";

        $insert_result_ded = mysqli_query($con, $insert_query_ded);
        
        if ($insert_result_ded) {
            echo "<div class='center'><h2>Attendance successfully Added</h2></div>";
            header("Location: attentance.php"); // Redirect back to department.php
          exit();
        } else {
            echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
        }

        } else {
            echo "<div class='center'><h2>Invalid Employee ID, Try Again</h2></div>";
        }
        
    } elseif (isset($_POST["clockInBtn"])) {
        $EmployeeID = intval($_POST["EmployeeID"]);
        $StartTime = mysqli_real_escape_string($con, $_POST["StartTime"]);

        $sqlStartDatetime = date("Y-m-d H:i:s", strtotime($StartTime));

        $checkempexist = "SELECT EmployeeID FROM Employees WHERE EmployeeID = $EmployeeID";
        $sql_emp_query = mysqli_query($con,$checkempexist );

        if ($sql_emp_query && mysqli_num_rows($sql_emp_query) > 0) {
            $insert_query_ded = "INSERT INTO Timesheet (EmployeeID, StartTime) VALUES ($EmployeeID, '$sqlStartDatetime')";

            $insert_result_ded = mysqli_query($con, $insert_query_ded);

            if ($insert_result_ded) {
                echo "<div class='center'><h2>Successfully Clock-In</h2></div>";
                header("Location: attentance.php"); // Redirect back to department.php
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
                    header("Location: attentance.php"); // Redirect back to department.php
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
            header("Location: attentance.php"); // Redirect back to department.php
          exit();
        } else {
            echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
        }

        } else {
            echo "<div class='center'><h2>Invalid Timesheet ID, Try Again</h2></div>";
        }

    }
    // Edit timesheet form
    elseif (isset($_POST["edittimesheetbtn"])) {
        $TimesheetID = intval($_POST["TimesheetID"]);
        // $EmployeeID = intval($_POST["EmployeeID"]);
        $StartTime = mysqli_real_escape_string($con, $_POST["StartTime"]);
        $EndTime = mysqli_real_escape_string($con, $_POST["EndTime"]);
        $OvertimeHours = intval($_POST["OvertimeHours"]);
        // $TotalHour = intval($_POST["TotalHour"]);

        $sqlStartDatetime = date("Y-m-d H:i:s", strtotime($StartTime));
        $sqlEndDatetime = date("Y-m-d H:i:s", strtotime($EndTime));

        $checktimeidexist = "SELECT Employees.EmployeeID, TimesheetID FROM Employees JOIN Timesheet ON Employees.EmployeeID = Timesheet.EmployeeID WHERE TimesheetID = $TimesheetID";
        $sql_timedid_query = mysqli_query($con,$checktimeidexist );

        if ($sql_timedid_query && mysqli_num_rows($sql_timedid_query) > 0) {
            $insert_query_ded = "UPDATE Timesheet SET StartTime = '$sqlStartDatetime', EndTime = '$sqlEndDatetime', OvertimeHours = $OvertimeHours WHERE TimesheetID = $TimesheetID";


        $insert_result_ded = mysqli_query($con, $insert_query_ded);

        if ($insert_result_ded) {
            echo "<div class='center'><h2>Successfully Updated Timesheet</h2></div>";
            header("Location: attentance.php"); // Redirect back to department.php
          exit();
        } else {
            echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
        }

        } else {
            echo "<div class='center'><h2>Invalid Timesheet ID, Try Again</h2></div>";
        }

        
    }
    // Delete timesheet form
    elseif (isset($_POST["deltimesheetbtn"])) {
        $TimesheetID = intval($_POST["TimesheetID"]);

        $insert_query_ded = "DELETE FROM Timesheet WHERE TimesheetID = $TimesheetID";

        $insert_result_ded = mysqli_query($con, $insert_query_ded);

        if ($insert_result_ded) {
            echo "<div class='center'><h2>Successfully Deleted</h2></div>";
            header("Location: attentance.php"); // Redirect back to department.php
          exit();
        } else {
            echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
        }
    }
    }
    ?>

    <div class="container">
        <h2>TimeSheet</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewTimesheet"
            style="margin-right: 10px;">
            Add New Timesheet
        </button>
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
                    <th>StartTime</th>
                    <th>EndTime</th>
                    <th>Total Hours Worked</th>
                    <th>OverTime</th>
                    <th>Action</th>
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
                        echo "<tr>
                                <td>" . $row["TimesheetID"] . "</td>
                                <td>" . $row["EmployeeID"] . "</td>
                                <td>" . $StartTimeFormat12 . " </td>
                                <td>" . $EndTimeFormat12 . "</td>
                                <td>" . $row["TotalHoursWorked"] . " </td>
                                <td>" . $row["OvertimeHours"] . " </td>
                                <td>
                                    <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editEmpTimesheet' data-edittimesheet-id='" . $row["TimesheetID"] . "' data-editemptime-id='" . $row["EmployeeID"] . "' onclick='editTimesheet(this)' style='margin-right: 10px;'>Edit</button>

                                    <button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteEmpTimesheet' onclick='deleteTimesheet(this)' data-deltimesheet-id='" . $row["TimesheetID"] . "'>Delete</button>

                                    
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
                    <form action="attentance.php" method="POST">
                        <label for="EmployeeID">Employee ID:</label>
                        <input type="number" name="EmployeeID" required>

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
                    <form action="attentance.php" method="POST">
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
                    <form action="attentance.php" method="POST">
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

    <!-- Form To Add New Timesheet -->
    <div class="modal fade" id="addNewTimesheet" tabindex="-1" aria-labelledby="editTimesheetLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editTimesheetLabel">Add New Timesheet</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="attentance.php" method="POST">
                        <label for="EmployeeID">Employee ID:</label>
                        <input type="number" name="EmployeeID" required>

                        <label for="StartTime">Clock-In Time:</label>
                        <input type="datetime-local" name="StartTime" required id="newtimeIDClockIN">

                        <label for="EndTime">Clock-Out Time:</label>
                        <input type="datetime-local" name="EndTime" required id="newtimeIDClockOut">


                        <label for="OvertimeHours">Over-time Hours:</label>
                        <input type="number" name="OvertimeHours" required>

                        <script>
                        document.getElementById('newtimeIDClockIN').addEventListener('change', function() {
                            var startDate = this.value;
                            var endDateInput = document.getElementById('newtimeIDClockOut');
                            endDateInput.min = startDate;

                            // If the current value of endDate is less than startDate, clear it
                            if (endDateInput.value < startDate) {
                                endDateInput.value = '';
                            }
                        });
                        </script>


                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="timesheetbtn">Save
                                changes</button>
                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Form To Edit Timesheet-->
    <div class="modal fade" id="editEmpTimesheet" tabindex="-1" aria-labelledby="editEmpTimesheetLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editEmpTimesheetLabel">Add New Timesheet</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="attentance.php" method="POST">
                        <label for="TimesheetID">Timesheet ID:</label>
                        <input type="number" name="TimesheetID" required>

                        <label for="EmployeeID">Employee ID:</label>
                        <input type="number" name="EmployeeID" required>

                        <label for="StartTime">Clock-In Time:</label>
                        <input type="datetime-local" name="StartTime" required id="edittimeclockinIDSys">

                        <label for="EndTime">Clock-Out Time:</label>
                        <input type="datetime-local" name="EndTime" required id="edittimeclockoutIDSys">
                        <script>
                        document.getElementById('edittimeclockinIDSys').addEventListener('change', function() {
                            var startDate = this.value;
                            var endDateInput = document.getElementById('edittimeclockoutIDSys');
                            endDateInput.min = startDate;

                            // If the current value of endDate is less than startDate, clear it
                            if (endDateInput.value < startDate) {
                                endDateInput.value = '';
                            }
                        });
                        </script>

                        <label for="OvertimeHours">Over-time Hours:</label>
                        <input type="number" name="OvertimeHours" required>


                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="edittimesheetbtn">Save
                                changes</button>
                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Form To Delete Timesheet-->
    <div class="modal fade" id="deleteEmpTimesheet" tabindex="-1" aria-labelledby="delEmpTimesheetLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="delEmpTimesheetLabel">Delete Timesheet</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="attentance.php" method="POST">
                        <label for="TimesheetID">Timesheet ID:</label>
                        <input type="number" name="TimesheetID" required>


                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="deltimesheetbtn">Save
                                changes</button>
                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function editTimesheet(buttonElement) {
        // Get Timesheet ID 
        var timesheetIdEdit = buttonElement.getAttribute('data-edittimesheet-id');
        var empIdEdit = buttonElement.getAttribute('data-editemptime-id');

        // Set the Timesheet ID in the form
        document.querySelector('#editEmpTimesheet input[name="TimesheetID"]').value = timesheetIdEdit;
        document.querySelector('#editEmpTimesheet input[name="EmployeeID"]').value = empIdEdit;
    }

    function deleteTimesheet(buttonElement) {
        // Get Timesheet ID 
        var timesheetIdDel = buttonElement.getAttribute('data-deltimesheet-id');

        // Set the Timesheet ID in the form
        document.querySelector('#deleteEmpTimesheet input[name="TimesheetID"]').value = timesheetIdDel;
    }
    </script>

</body>

</html>