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

    <link rel="stylesheet" type="text/css" href="deductionb.css">
    <link rel="stylesheet" type="text/css" href="navbar.css">

    <title>Deductions & Benefits</title>
</head>

<body>
    <?php 

    include 'cnavbar.php';
  include("sqlconnection.php");

  if (isset($_SESSION['employee_id'])) {
    $employeeIdSession = $_SESSION['employee_id']; // Retrieve the employee ID from session

    // Use $employeeId in your SQL query to fetch specific timesheet
    $sql = "SELECT * FROM Deductions 
LEFT JOIN EmployeeDeduction 
ON Deductions.DeductionID = EmployeeDeduction.DeductionID 
LEFT JOIN Employees 
ON EmployeeDeduction.EmployeeID = Employees.EmployeeID WHERE Employees.EmployeeID = $employeeIdSession";
    $result = mysqli_query($con, $sql);

    $sql_benefit = "SELECT * FROM Benefits 
LEFT JOIN EmployeeBenefit 
ON Benefits.BenefitID = EmployeeBenefit.BenefitID 
LEFT JOIN Employees 
ON EmployeeBenefit.EmployeeID = Employees.EmployeeID 
WHERE Employees.EmployeeID = $employeeIdSession";
    $result_benefit = mysqli_query($con, $sql_benefit);

    
    } else {
        echo "<div class='center'><h2>Invalid Employee ID, Session Error</h2></div>";
    
}

    ?>
    <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["deductionSubmit"])) {
        $DeductionID = intval($_POST["DeductionID"]);
     
        // Check if deduction ID exists
        $checkDeductionID = "SELECT DeductionID FROM Deductions WHERE DeductionID = $DeductionID";
      $result_ded_id_check = mysqli_query($con, $checkDeductionID);
      if ($result_ded_id_check && mysqli_num_rows($result_ded_id_check) > 0)  {

        // check if emp don't already have that deduction
        $checkPreDeduction = "SELECT * FROM EmployeeDeduction WHERE EmployeeID = '$employeeIdSession' AND DeductionID = '$DeductionID'";
        $execuCheckPreDeduction = mysqli_query($con, $checkPreDeduction);

        if ($execuCheckPreDeduction && mysqli_num_rows($execuCheckPreDeduction) == 0) {
            $insert_query_ded = "INSERT INTO EmployeeDeduction (EmployeeID, DeductionID) 
      VALUES ($employeeIdSession, $DeductionID)";
      $insert_result_ded = mysqli_query($con, $insert_query_ded);

      if ($insert_result_ded) {
        echo "<div class='center'><h2>Deduction successfully Added</h2></div>";
        header("Location: client_deductions.php"); // Redirect back to department.php
                exit();
      } else {
        echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
      }

        } else {
            echo "<div class='center'><h2>Failed, You Already Have This Deduction</h2></div>";
        }


        

      } else {
          echo "<div class='center'><h2>Failed, Invalid Deduction ID</h2></div>";
}

      
    } 
    elseif (isset($_POST["benefitSubmit"])) {
      // Benefit form submission
    
            $BenefitID = intval($_POST["BenefitID"]);


       // Check if that payroll id exist
      $checkBenefitID = "SELECT BenefitID FROM Benefits WHERE BenefitID = $BenefitID";
      $result_benefit_id_check = mysqli_query($con, $checkBenefitID);

      if ($result_benefit_id_check && mysqli_num_rows($result_benefit_id_check) > 0) {

        // check if emp don't already have that deduction
        $checkPreBenefit = "SELECT * FROM EmployeeBenefit WHERE EmployeeID = '$employeeIdSession' AND BenefitID = '$BenefitID'";
        $execuCheckPreBenefit = mysqli_query($con, $checkPreBenefit);

        if ($execuCheckPreBenefit && mysqli_num_rows($execuCheckPreBenefit) == 0) {
            $insert_query = "INSERT INTO EmployeeBenefit (EmployeeID, BenefitID) 
      VALUES ($employeeIdSession, $BenefitID)";
      $insert_result = mysqli_query($con, $insert_query);

      if ($insert_result) {
        echo "<div class='center'><h2>Benefit successfully Added</h2></div>";
        header("Location: client_deductions.php"); // Redirect back to department.php
                exit();
      } else {
        echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
      }


        } else {
            echo "<div class='center'><h2>Failed, You Already Have This Benefit</h2></div>";
        }

        
      } else {
          echo "<div class='center'><h2>Failed, Invalid Benefit ID</h2></div>";
}

      
    }
  }
  ?>
    <div class="container">
        <h2>Deductions</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewDeduction">
            Add New Deduction
        </button>

        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Deduction ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>

                    <th>Deduction Name</th>
                    <th>Amount</th>
                    <!-- <th>Action</th> -->
                </tr>
            </thead>
            <tbody>

                <?php
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
        <td>" . $row["EmployeeID"]. "</td>
        <td>" . $row["DeductionID"]. "</td>
        <td>" . $row["FirstName"]. "</td>
        <td>" . $row["LastName"]. "</td>
        
        <td>" . $row["DeductionName"]. " </td><td>" . $row["Amount"]. " </td></tr>";
    }
} else {
    echo "0 results";
}
        ?>

            </tbody>
        </table>


        <h2 style="margin-top: 20px;">Benefits</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewBenefit">
            Add New Benefit
        </button>


        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Benefit ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>

                    <th>Benefit Name</th>
                    <th>Benefit Description</th>
                    <!-- <th>Action</th> -->
                </tr>
            </thead>
            <tbody>
                <!-- PHP data -->

                <?php
        if (mysqli_num_rows($result_benefit) > 0) {
          while ($row = mysqli_fetch_assoc($result_benefit)) {
        echo "<tr>
        <td>" . $row["EmployeeID"]. "</td>
        <td>" . $row["BenefitID"]. "</td>
        <td>" . $row["FirstName"]. "</td>
        <td>" . $row["LastName"]. "</td>
        
        <td>" . $row["BenefitName"]. " </td><td>" . $row["BenefitDesciption"]. " </td></tr>";
    }
} else {
    echo "0 results";
}
        ?>

            </tbody>
        </table>
    </div>

    <!-- Add Deduction -->
    <!-- Form To add New Deduction -->
    <div class="modal fade" id="addNewDeduction" tabindex="-1" aria-labelledby="addDeductionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addDeductionLabel">Add New Deduction</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="client_deductions.php" method="POST">
                        <?php 
                        $showAllDeductions = "SELECT * FROM Deductions";
                        $execShowAllDeduction = mysqli_query($con, $showAllDeductions);
                        
                        ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Deduction ID</th>
                                    <th>Deduction Name</th>
                                    <th>Amount</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                    if (mysqli_num_rows($execShowAllDeduction) > 0) {
                                    while ($row = mysqli_fetch_assoc($execShowAllDeduction)) {
                                    echo "<tr><td>" . $row["DeductionID"]. "</td><td>" . $row["DeductionName"]. " </td><td>" . $row["Amount"]  .
                                    "</td></tr>";
                                }
                            } else {
                                echo "0 results";
                            }
                                    ?>
                            </tbody>
                        </table>


                        <label style="margin-top: 20px;" for="DeductionID">Deduction ID:</label>
                        <input type="number" name="DeductionID" required>



                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="deductionSubmit">Save
                                changes</button>

                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addNewBenefit" tabindex="-1" aria-labelledby="addBenefitLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addBenefitLabel">Add New Benefit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="client_deductions.php" method="POST">
                        <?php 
                        $showAllBenefits = "SELECT * FROM Benefits";
                        $execShowAllBenefits = mysqli_query($con, $showAllBenefits);
                        
                        ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Benefit ID</th>
                                    <th>Benefit Name</th>
                                    <th>Benefit Description</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                    if (mysqli_num_rows($execShowAllBenefits) > 0) {
                                    while ($row = mysqli_fetch_assoc($execShowAllBenefits)) {
                                    echo "<tr><td>" . $row["BenefitID"]. "</td><td>" . $row["BenefitName"]. " </td><td>" . $row["BenefitDesciption"]  .
                                    "</td></tr>";
                                }
                            } else {
                                echo "0 results";
                            }
                                    ?>
                            </tbody>
                        </table>


                        <label style="margin-top: 20px;" for="BenefitID">Benefit ID:</label>
                        <input type="number" name="BenefitID" required>



                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="benefitSubmit">Save
                                changes</button>

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