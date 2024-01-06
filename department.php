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
    <link rel="stylesheet" type="text/css" href="department.css">
    <link rel="stylesheet" type="text/css" href="navbar.css">
    <title>Department Page</title>
</head>

<body>
    <?php include 'navbar.php';
    include("sqlconnection.php");
    $sql = "SELECT * FROM Departments";
    $result = mysqli_query($con, $sql);
    ?>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["newDeptAdd"])) {
                $DepartmentName = mysqli_real_escape_string($con, $_POST["DepartmentName"]);
                $insert_query = "INSERT INTO Departments (DepartmentName)
                VALUES ('$DepartmentName')";
                $insert_result = mysqli_query($con, $insert_query);

                if ($insert_result) {
                    echo "<div class='center'><h2>Department successfully Added</h2></div>";
                    header("Location: department.php"); // Redirect back to department.php
                    exit();
                } else {
                echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
                }

            } elseif (isset($_POST["deptEdit"])) {
                $NewDepartmentName = mysqli_real_escape_string($con, $_POST["NewDepartmentName"]);
                $NewDepartmentID = intval($_POST["NewDepartmentID"]);

                $checkDeptExistence = "SELECT DepartmentID FROM Departments WHERE DepartmentID = $NewDepartmentID";
                $deptExistCheck = mysqli_query($con, $checkDeptExistence);

                if ($deptExistCheck && mysqli_num_rows($deptExistCheck) > 0) {
                    $update_query = "UPDATE Departments SET DepartmentName = '$NewDepartmentName' WHERE DepartmentID = $NewDepartmentID";
                    $update_result = mysqli_query($con, $update_query);

                    if ($update_result) {
                        echo "<div class='center'><h2>Department Updated successfully</h2></div>";
                        header("Location: department.php"); // Redirect back to department.php
                        exit();

                    } else {
                        echo "<div class='center'><h2>Failed to update department, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
                    }
                } else {
                    echo "<div class='center'><h2>No Department with that ID, Try Again</h2></div>";
                }

            } elseif (isset($_POST["deptDelete"])) {
                $DeleteDeptID = intval($_POST["DeleteDeptID"]);

                $checkDeptExistence_1 = "SELECT DepartmentID FROM Departments WHERE DepartmentID = $DeleteDeptID";
                $deptExistCheck = mysqli_query($con, $checkDeptExistence_1);

                if ($deptExistCheck && mysqli_num_rows($deptExistCheck) > 0) {
                    $delete_query = "DELETE FROM Departments WHERE DepartmentID = $DeleteDeptID";
                    $delete_result = mysqli_query($con, $delete_query);

                    if ($delete_result) {
                        echo "<div class='center'><h2>Department Deleted successfully</h2></div>";
                        header("Location: department.php"); // Redirect back to department.php
                    exit();
                    } else {
                        echo "<div class='center'><h2>Failed to delete department, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
                    }
                } else {
                    echo "<div class='center'><h2>No Department with that ID, Try Again</h2></div>";
                }
            }
        }
    ?>

    <div class="container">
        <h2>Departments</h2>
        <!-- Button To add New Department -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewDept">
            Add New Department
        </button>

        <table>
            <thead>
                <tr>
                    <th>Department ID</th>
                    <th>Department Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>" .
                                "<td>" . $row["DepartmentID"] . "</td>" .
                                "<td>" . $row["DepartmentName"] . "</td>" .
                                "<td>" .
                                    "<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editDeptID' " .
                                    "data-dept-id='" . $row["DepartmentID"] . "' onclick='editDepartment(this)' " .
                                    "style='margin-right: 10px;'>Edit</button>" .
                                    "<button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteDeptID' " .
                                    "onclick='deleteDepartment(this)' data-dept-id='" . $row["DepartmentID"] . "'>Delete</button>" .
                                "</td>" .
                                "</tr>";
                        }
                    } else {
                        echo "0 results";
                    }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Form To add New Department -->
    <div class="modal fade" id="addNewDept" tabindex="-1" aria-labelledby="addDeptLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addDeptLabel">Add New Department</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="department.php" method="POST">
                        <label for="DepartmentName">Department Name:</label>
                        <input type="text" name="DepartmentName" required>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="newDeptAdd">Save changes</button>

                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Form To Edit Department -->
    <div class="modal fade" id="editDeptID" tabindex="-1" aria-labelledby="editDeptLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editDeptLabel">Edit Department</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="department.php" method="POST">
                        <label for="NewDepartmentID">Department ID to Edit:</label>
                        <input type="number" name="NewDepartmentID" required>

                        <label for="NewDepartmentName">New Department Name:</label>
                        <input type="text" name="NewDepartmentName" required>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="deptEdit">Save changes</button>
                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Form To Delete Department -->
    <div class="modal fade" id="deleteDeptID" tabindex="-1" aria-labelledby="deleteDeptLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteDeptLabel">Delete Department</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="department.php" method="POST">
                        <label for="DeleteDeptID">Department ID to Delete:</label>
                        <input type="number" name="DeleteDeptID" required>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="deptDelete">Save
                                changes</button>
                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php
        // include 'footer.php';
    ?>
    <script>
    function editDepartment(buttonElement) {
        // Get department ID 
        var departmentId = buttonElement.getAttribute('data-dept-id');

        // Set the department ID in the form
        document.querySelector('#editDeptID input[name="NewDepartmentID"]').value = departmentId;
    }

    function deleteDepartment(buttonElement) {
        // Get department ID 
        var departmentId = buttonElement.getAttribute('data-dept-id');

        // Set the department ID in the form
        document.querySelector('#deleteDeptID input[name="DeleteDeptID"]').value = departmentId;
    }
    </script>

</body>

</html>