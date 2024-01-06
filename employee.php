<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/prototype/1.7.3.0/prototype.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>


    <title>Employee Records</title>
    <link rel="stylesheet" type="text/css" href="employee_form.css">
    <link rel="stylesheet" type="text/css" href="employeee.css">
    <link rel="stylesheet" type="text/css" href="navbar.css">

</head>

<body>
    <?php include 'navbar.php'; ?>
    <?php
    include 'sqlconnection.php';

    $sql_former = "SELECT PastEmpID, FirstName, LastName, p.PositionTitle, d.DepartmentName, StartDate, EndDate 
    FROM Emp_Repository er, Positions p, Departments d
    WHERE er.positionid = p.positionid
    AND p.departmentid = d.departmentid
    ORDER BY pastempid ASC";

    $result_former = mysqli_query($con, $sql_former); 


    $showAllEmployeeInfo = "SELECT * FROM Employees LEFT JOIN UserLogin ON Employees.EmployeeID = UserLogin.EmployeeID WHERE Employees.EmployeeID = 5";
                        $execShowAllEmpInfo = mysqli_query($con, $showAllEmployeeInfo);

    $showAllEmployeeAddress = "SELECT * FROM Addresses LEFT JOIN Employees ON Addresses.EmployeeID = Employees.EmployeeID WHERE Employees.EmployeeID = 5";
                        $execShowAllEmpAddress = mysqli_query($con, $showAllEmployeeAddress);
    
     $showAllEmployeeLogin = "SELECT * FROM UserLogin LEFT JOIN Employees ON UserLogin.EmployeeID = Employees.EmployeeID WHERE Employees.EmployeeID = 5";
                        $execShowAllEmpLogin = mysqli_query($con, $showAllEmployeeLogin);

    $showAllEmployeeJob = "SELECT * FROM Positions LEFT JOIN Departments ON Positions.DepartmentID = Departments.DepartmentID LEFT JOIN Employees ON Employees.PositionID = Positions.PositionID WHERE Employees.EmployeeID = 5";
                        $execShowAllEmpJob = mysqli_query($con, $showAllEmployeeJob);
    
    $showAllEmployeeBenefit = "SELECT * FROM Benefits LEFT JOIN EmployeeBenefit ON Benefits.BenefitID = EmployeeBenefit.BenefitID LEFT JOIN Employees ON Employees.EmployeeID = EmployeeBenefit.EmployeeID WHERE Employees.EmployeeID = 5";
                        $execShowAllEmpBenefit = mysqli_query($con, $showAllEmployeeBenefit);

    $showAllEmployeeDeduction = "SELECT * FROM Deductions LEFT JOIN EmployeeDeduction ON Deductions.DeductionID = EmployeeDeduction.DeductionID LEFT JOIN Employees ON Employees.EmployeeID = EmployeeDeduction.EmployeeID WHERE Employees.EmployeeID = 5";
                        $execShowAllEmpDeduction = mysqli_query($con, $showAllEmployeeDeduction);

    $sql_dept = "SELECT DepartmentID, DepartmentName FROM Departments";
$result_dept = mysqli_query($con, $sql_dept);

$sql_pos = "SELECT PositionID, PositionTitle FROM Positions";
$result_pos = mysqli_query($con, $sql_pos);
    ?>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["newEmpAddSys"])) {
            $FirstName = mysqli_real_escape_string($con, $_POST["FirstName"]);
        $LastName = mysqli_real_escape_string($con, $_POST["LastName"]);
        $PositionID = intval($_POST["positionaddpos"]);
        $DepartmentID = intval($_POST["departmentaddemp"]);
        $Role = intval($_POST["roleChosen"]);

        $insert_query = "INSERT INTO Employees (FirstName, LastName, PositionID, DepartmentID, HireDate, Role) 
        VALUES ('$FirstName', '$LastName', $PositionID, $DepartmentID, CURRENT_DATE(), $Role)";
        $insert_result = mysqli_query($con, $insert_query);

        if ($insert_result) {
            echo "<div class='center'><h2>Employee successfully Added</h2></div>";
            header("Location: employee.php"); // Redirect back to department.php
                        exit();
        } else {
            echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
        }

        } elseif (isset($_POST["deleteEmpBtnID"])) {
                $DeleteEmpID = intval($_POST["DeleteEmpID"]);

                $checkEmpExistence_1 = "SELECT EmployeeID FROM Employees WHERE EmployeeID = $DeleteEmpID";
                $empExistCheck = mysqli_query($con, $checkEmpExistence_1);

                if ($empExistCheck && mysqli_num_rows($empExistCheck) > 0) {
                    $delete_query = "DELETE FROM Employees WHERE EmployeeID = $DeleteEmpID";
                    $delete_result = mysqli_query($con, $delete_query);

                    if ($delete_result) {
                        echo "<div class='center'><h2>Employee Deleted successfully</h2></div>";
                        header("Location: employee.php"); // Redirect back to department.php
                    exit();
                    } else {
                        echo "<div class='center'><h2>Failed to delete Employee, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
                    }
                } else {
                    echo "<div class='center'><h2>No Employee with that ID, Try Again</h2></div>";
                }
            }
        
    }
    ?>

    <div class="container">
        <h2>Employees</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addnewEmployee"
            style="margin-bottom: 20px; margin-right: 10px;">
            Add New Employee
        </button>

        <form method="get" action="">
            <?php
            $searchValue = '';
            if (isset($_GET['search'])) {
                $searchValue = htmlspecialchars($_GET['search']);
            }
            ?>
            <input type="text" name="search" class="form-control mb-3" placeholder="Search by employee number or name"
                value="<?php echo $searchValue; ?>">
            <input type="submit" value="Search" class="btn btn-primary">
        </form>

        <?php
        $searchQuery = '';
        if (isset($_GET['search'])) {
            $searchQuery = $_GET['search'];
        }

        if (!empty($searchQuery)) {
            $searchQuery = mysqli_real_escape_string($con, $searchQuery); // Sanitize user input
            $sql = "SELECT * FROM Employees WHERE EmployeeID LIKE '%$searchQuery%' OR FirstName LIKE '%$searchQuery%' OR LastName LIKE '%$searchQuery%'";
        } else {
            $sql = "CALL Show_All_Employee_Record()";
        }

        $result = mysqli_query($con, $sql);

        if (!$result) {
            echo "<p>Error fetching employees: " . mysqli_error($con) . "</p>";
        } else {
            echo "<p><strong>Showing " . mysqli_num_rows($result) . " entries</strong></p>";
            ?>
        <div class="container">
            <?php
            echo '<table>
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Position</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>UserName</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                    ?>
        </div>
        <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>" . $row["EmployeeID"]. "</td>
                        <td>" . $row["FirstName"]. "</td>
                        <td>" . $row["LastName"]. "</td>
                        <td>" . $row["PositionTitle"]. "</td>
                        
                        <td>" . $row["Email"]. "</td>
                        <td>" . $row["Phone"]. "</td>
                        
                        <td>" . $row["UserName"]. "</td>
                        <td>
                          <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#viewEmployeeRecord' " .
                                    "data-emp-id='" . $row["EmployeeID"] . "' onclick='editEmployeeID(this)' " .
                                    "style='margin-right: 10px;'>View</button>"
                                     .
                        "<button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteEmpIDForm' " .
                                    "onclick='deleteEmployee(this)' data-empdel-id='" . $row["EmployeeID"] . "'>Delete</button>
                        </td>
                    </tr>";
            }

            echo '</tbody></table>';
        }
        ?>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="viewEmployeeRecord" tabindex="-1" aria-labelledby="empRecordLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="empRecordLabel">Employee Record for John Jackson</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3>Employee Info</h3>
                    <!-- Body Table -->
                    <!-- Employee Information -->
                    <?php 

                        ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>


                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                    if (mysqli_num_rows($execShowAllEmpInfo) > 0) {
                                    while ($row = mysqli_fetch_assoc($execShowAllEmpInfo)) {
                                    echo "<tr>
                                    <td>" . $row["EmployeeID"]. "</td>
                                    <td>" . $row["FirstName"]. " </td>
                                    <td>" . $row["LastName"]  . "</td>
                                    <td>" . $row["Email"]. "</td>
                                    <td>" . $row["Phone"]. " </td>
                                    

                                    </tr>";
                                }
                            } else {
                                echo "0 results";
                            }
                                    ?>

                        </tbody>
                    </table>
                    <h3 style="margin-top: 20px;">Address</h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Street Name</th>
                                <th>City</th>
                                <th>State</th>
                                <th>ZipCode</th>


                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                    if (mysqli_num_rows($execShowAllEmpAddress) > 0) {
                                    while ($row = mysqli_fetch_assoc($execShowAllEmpAddress)) {
                                    echo "<tr>
                                    <td>" . $row["StreetAddress"]. "</td>
                                    <td>" . $row["City"]. " </td>
                                    <td>" . $row["State"]  . "</td>
                                    <td>" . $row["ZipCode"]. "</td>
                                    

                                    </tr>";
                                }
                            } else {
                                echo "0 results";
                            }
                                    ?>
                        </tbody>
                    </table>
                    <!-- UserLogin -->
                    <h3 style="margin-top: 20px;">User Login Information</h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Last Login</th>

                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                    if (mysqli_num_rows($execShowAllEmpLogin) > 0) {
                                    while ($row = mysqli_fetch_assoc($execShowAllEmpLogin)) {
                                        $FormatTimeTo12 = date('Y-m-d h:i:s A', strtotime($row["LastLogin"]));
                                    echo "<tr>
                                    <td>" . $row["UserName"]. "</td>
                                    <td>" . $row["Password"]. " </td>
                                    <td>" . $FormatTimeTo12  . "</td>

                                    </tr>";
                                }
                            } else {
                                echo "0 results";
                            }
                                    ?>
                        </tbody>
                    </table>

                    <h3 style="margin-top: 20px;">Job Description</h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Department Name</th>
                                <th>Position</th>
                                <th>Hourly Rate</th>
                                <th>Hire Date</th>
                                <th>Role</th>

                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                    if (mysqli_num_rows($execShowAllEmpJob) > 0) {
                                    while ($row = mysqli_fetch_assoc($execShowAllEmpJob)) {
                                        $title = "";
                                        if ($row["Role"] == 0) {
                                            $title = "Admin";
                                        } else {
                                            $title = "Client";
                                        }
                                    echo "<tr>
                                    <td>" . $row["DepartmentName"]. "</td>
                                    <td>" . $row["PositionTitle"]. " </td>
                                    <td>" . $row["HourlyRate"]. "</td>
                                    <td>" . $row["HireDate"]. " </td>
                                    <td>" . $title. "</td>

                                    </tr>";
                                }
                            } else {
                                echo "0 results";
                            }
                                    ?>
                        </tbody>
                    </table>

                    <h3 style="margin-top: 20px;">Employee's Benefits</h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Benefit Name</th>
                                <th>Benefit Description</th>





                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                    if (mysqli_num_rows($execShowAllEmpBenefit) > 0) {
                                    while ($row = mysqli_fetch_assoc($execShowAllEmpBenefit)) {
                                        
                                    echo "<tr>
                                    <td>" . $row["BenefitName"]. "</td>
                                    <td>" . $row["BenefitDesciption"]. " </td>
                                    
                                    </tr>";
                                }
                            } else {
                                echo "0 results";
                            }
                                    ?>
                        </tbody>
                    </table>

                    <h3 style="margin-top: 20px;">Employee's Deduction</h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Deduction Name</th>
                                <th>Deduction Amount</th>

                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                    if (mysqli_num_rows($execShowAllEmpDeduction) > 0) {
                                    while ($row = mysqli_fetch_assoc($execShowAllEmpDeduction)) {
                                        
                                    echo "<tr>
                                    <td>" . $row["DeductionName"]. "</td>
                                    <td>" . $row["Amount"]. " </td>
                            
                                    </tr>";
                                }
                            } else {
                                echo "0 results";
                            }
                                    ?>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="updateEmpRecord">Save changes</button>

                    <button type="button" class="btn btn-secondary btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h2>Former Employees</h2>
        <?php
        echo '<table>
                <thead>
                    <tr>
                        <th>PastEmpID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Hire Date</th>
                        <th>Termination Date</th>
                    </tr>
                </thead>
                <tbody>';

        while ($row = mysqli_fetch_assoc($result_former)) {
            echo "<tr>
                    <td>" . $row["PastEmpID"]. "</td>
                    <td>" . $row["FirstName"]. "</td>
                    <td>" . $row["LastName"]. "</td>
                    <td>" . $row["PositionTitle"]. "</td>
                    <td>" . $row["DepartmentName"]. "</td>
                    <td>" . $row["StartDate"]. "</td>
                    <td>" . $row["EndDate"]. "</td>
                </tr>";
        }

        echo '</tbody></table>';
        ?>
    </div>

    <!-- Form To Delete Employee -->
    <div class="modal fade" id="deleteEmpIDForm" tabindex="-1" aria-labelledby="deleteEmpIDFormLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteEmpIDFormLabel">Delete Employee</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="employee.php" method="POST">
                        <label for="DeleteEmpID">Employee ID to Delete:</label>
                        <input type="number" name="DeleteEmpID" required>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="deleteEmpBtnID">Save
                                changes</button>
                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Form To add New Employee -->
    <div class="modal fade" id="addnewEmployee" tabindex="-1" aria-labelledby="addNewEmpLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addNewEmpLabel">Add New Employee</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="employee.php" method="POST">
                        <label for="FirstName">First Name:</label>
                        <input type="text" name="FirstName" required placeholder="John">

                        <label for="LastName">Last Name:</label>
                        <input type="text" name="LastName" required placeholder="Doe">





                        <label for="departmentChosen">Choose a Department:</label>
                        <select name="departmentaddemp" id="departmentChosen">
                            <?php
                            if (mysqli_num_rows($result_dept) > 0) {
                                while ($row = mysqli_fetch_assoc($result_dept)) {
                                    echo "<option value='" . $row["DepartmentID"] . "'>" . $row["DepartmentName"] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No departments available</option>";
                            }
                            ?>
                        </select>

                        <label for="positionChosen">Choose a Position:</label>
                        <select name="positionaddpos" id="positionChosen" required>
                            <?php
                            if (mysqli_num_rows($result_pos) > 0) {
                                while ($row = mysqli_fetch_assoc($result_pos)) {
                                    echo "<option value='" . $row["PositionID"] . "'>" . $row["PositionTitle"] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No Position available</option>";
                            }
                            ?>
                        </select>

                        <!-- <label for="Role">Role:</label>
                        <input type="number" name="Role" min="0" max="1" required> -->

                        <label for="choseRoleNewEmp">Choose a Role:</label>
                        <select name="roleChosen" id="choseRoleNewEmp" required>
                            <option value="0">Admin</option>
                            <option value="1">Client</option>

                        </select>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="newEmpAddSys">Save changes</button>

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