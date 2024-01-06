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
    <link rel="stylesheet" type="text/css" href="deductionb.css">
    <link rel="stylesheet" type="text/css" href="navbar.css">
    <link rel="stylesheet" type="text/css" href="employee_form.css">

    <title>Deductions & Benefits</title>
</head>

<body>
    <?php include 'navbar.php';
  include("sqlconnection.php");

      $sql = "SELECT * FROM Deductions";


    $result = mysqli_query($con, $sql);

    $sql_benefit = "SELECT * FROM Benefits";
    $result_benefit = mysqli_query($con, $sql_benefit);
    ?>
    <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["deductionSubmit"])) {
      // Deduction form submission
      $DeductionName = mysqli_real_escape_string($con, $_POST["DeductionName"]);
      $DeductionAmount = mysqli_real_escape_string($con, $_POST["DeductionAmount"]);
      $DeductionPer = $DeductionAmount / 100;

      $insert_query_ded = "INSERT INTO Deductions (DeductionName, Amount)
      VALUES ('$DeductionName', '$DeductionPer')";
      $insert_result_ded = mysqli_query($con, $insert_query_ded);

      if ($insert_result_ded) {
        echo "<div class='center'><h2>Deduction successfully Added</h2></div>";
        header("Location: deductionb.php"); // Redirect back to department.php
                    exit();
      } else {
        echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
      }
    } 
    // Edit button for deduction
    elseif (isset($_POST["deductionSubmitbtn"])) {
        $newDeductionID = intval($_POST["newDeductionID"]);

        $NewDeductionName = mysqli_real_escape_string($con, $_POST["NewDeductionName"]);
        $NewDeductionAmount = mysqli_real_escape_string($con, $_POST["NewDeductionAmount"]);
        $DeductionPer = $NewDeductionAmount / 100;

        // Check if that payroll id exist
        $checkDeductionID = "SELECT DeductionID FROM Deductions WHERE DeductionID = $newDeductionID";
        $result_ded_id_check = mysqli_query($con, $checkDeductionID);

        if ($result_ded_id_check && mysqli_num_rows($result_ded_id_check) > 0) {
            $update_query_ded = "UPDATE Deductions SET DeductionName = '$NewDeductionName', Amount = '$DeductionPer' WHERE DeductionID = $newDeductionID";
            $update_result_ded = mysqli_query($con, $update_query_ded);

            if ($update_result_ded) {
              echo "<div class='center'><h2>Deduction successfully Updated</h2></div>";
              header("Location: deductionb.php"); // Redirect back to department.php
                          exit();
            } else {
              echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
            }
              
            } else {
              echo "<div class='center'><h2>Failed, Invalid Deduction ID</h2></div>";
            }

      
    } elseif (isset($_POST["deductionDeleteBtn"])) {
      $DeleteDeductionID = intval($_POST["DeleteDeductionID"]);

      $checkDeductionID = "SELECT DeductionID FROM Deductions WHERE DeductionID = $DeleteDeductionID";
      $result_ded_id_check = mysqli_query($con, $checkDeductionID);

      if ($result_ded_id_check && mysqli_num_rows($result_ded_id_check) > 0) {
          $delete_deduction_sql = "DELETE FROM Deductions WHERE DeductionID = $DeleteDeductionID";
          $delete_deduction_result = mysqli_query($con, $delete_deduction_sql);

          if ($delete_deduction_result) {
              echo "<div class='center'><h2>Deduction successfully Deleted</h2></div>";
              header("Location: deductionb.php"); // Redirect back to department.php
          exit();
          } else {
              echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
          }
      } else {
          echo "<div class='center'><h2>Failed, Invalid Deduction ID</h2></div>";
}

        }
    
    elseif (isset($_POST["benefitSubmit"])) {
      // Benefit form submission
      $BenefitName = mysqli_real_escape_string($con, $_POST["BenefitName"]);
      $BenefitDesciption = mysqli_real_escape_string($con, $_POST["BenefitDescription"]);

      $insert_query = "INSERT INTO Benefits (BenefitName, BenefitDesciption)
      VALUES ('$BenefitName', '$BenefitDesciption')";
      $insert_result = mysqli_query($con, $insert_query);

      if ($insert_result) {
        echo "<div class='center'><h2>Benefit successfully Added</h2></div>";
        header("Location: deductionb.php"); // Redirect back to department.php
                    exit();
      } else {
        echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
      }
    }
    // Edit button for benefits
    elseif (isset($_POST["benefitSubmitbtn"])) {
        $newBenefitID = intval($_POST["newBenefitID"]);

        $NewBenefitName = mysqli_real_escape_string($con, $_POST["NewBenefitName"]);
      $NewBenefitDescription = mysqli_real_escape_string($con, $_POST["NewBenefitDescription"]);
      

      // Check if that payroll id exist
      $checkBenefitID = "SELECT BenefitID FROM Benefits WHERE BenefitID = $newBenefitID";
      $result_benefit_id_check = mysqli_query($con, $checkBenefitID);

      if ($result_benefit_id_check && mysqli_num_rows($result_benefit_id_check) > 0) {
        $update_query_benefit = "UPDATE Benefits SET BenefitName = '$NewBenefitName', BenefitDesciption = '$NewBenefitDescription' WHERE BenefitID = $newBenefitID";
      $update_result_benefit = mysqli_query($con, $update_query_benefit);

      if ($update_result_benefit) {
        echo "<div class='center'><h2>Benefit successfully Updated</h2></div>";
        header("Location: deductionb.php"); // Redirect back to department.php
                    exit();
      } else {
        echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
      }
        
      } else {
        echo "<div class='center'><h2>Failed, Invalid Benefit ID</h2></div>";
      }

      
    } elseif (isset($_POST["BenefitDeleteBtn"])) {
            $DeleteBenefitID = intval($_POST["DeleteBenefitID"]);

            $checkBenefitID = "SELECT BenefitID FROM Benefits WHERE BenefitID = $DeleteBenefitID";
      $result_benefit_id_check = mysqli_query($con, $checkBenefitID);

            if ($result_benefit_id_check && mysqli_num_rows($result_benefit_id_check) > 0) {
                $delete_benefit_sql = "DELETE FROM Benefits WHERE BenefitID = $DeleteBenefitID";
                $delete_benefit_result = mysqli_query($con, $delete_benefit_sql);

                if ($delete_benefit_result) {
                    echo "<div class='center'><h2>Benefit successfully Deleted</h2></div>";
                    header("Location: deductionb.php"); // Redirect back to department.php
                exit();
                } else {
                    echo "<div class='center'><h2>Failed, Try Again</h2><br>Error: " . mysqli_error($con) . "</div>";
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

        <!-- add functionality -->
        <table>
            <thead>
                <tr>
                    <th>Deduction ID</th>
                    <th>Deduction Name</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                <?php
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row["DeductionID"]. "</td><td>" . $row["DeductionName"]. " </td><td>" . $row["Amount"]. " </td><td>" .
        "<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editDeductionForm' " ."data-deduction-id='" . $row["DeductionID"] . "' onclick='editDeduction(this)' " ."style='margin-right: 10px;'>Edit</button> " .

        "<button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteDeductionForm' " . "onclick='deleteDeduction(this)' data-dedudel-id='" . $row["DeductionID"] . "'>Delete</button>"  .
        "</td></tr>";
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
                    <th>Benefit ID</th>
                    <th>Benefit Name</th>
                    <th>Benefit Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                <?php
        if (mysqli_num_rows($result_benefit) > 0) {
          while ($row = mysqli_fetch_assoc($result_benefit)) {
        echo "<tr><td>" . $row["BenefitID"]. "</td><td>" . $row["BenefitName"]. " </td><td>" . $row["BenefitDesciption"]. " </td><td>" .
        "<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editBenefitForm' " ."data-benefit-id='" . $row["BenefitID"] . "' onclick='editBenefit(this)' " ."style='margin-right: 10px;'>Edit</button> " .

        "<button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteBenefitForm' " ."onclick='deleteBenefit(this)' data-benefitdel-id='" . $row["BenefitID"] . "'>Delete</button>"  .
        "</td></tr>";
    }
} else {
    echo "0 results";
}
        ?>

            </tbody>
        </table>
    </div>

    <!-- Deductions Forms -->
    <!-- Form To add New Deduction -->
    <div class="modal fade" id="addNewDeduction" tabindex="-1" aria-labelledby="addDeductionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addDeductionLabel">Add New Deduction</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="deductionb.php" method="POST">
                        <label for="DeductionName">Deduction Name:</label>
                        <input type="text" name="DeductionName" placeholder="Federal Tax" required>

                        <label for="DeductionAmount">Deduction Percentage:</label>
                        <input type="number" name="DeductionAmount" placeholder="Please enter a number: 15%" required>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="deductionSubmit">Save changes</button>

                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Form To Edit Deduction -->
    <div class="modal fade" id="editDeductionForm" tabindex="-1" aria-labelledby="editDeductionLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editDeductionLabel">Edit Deduction</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="deductionb.php" method="POST">
                        <label for="newDeductionID">Deduction ID to Edit:</label>
                        <input type="number" name="newDeductionID" required>

                        <label for="NewDeductionName">New Deduction Name:</label>
                        <input type="text" name="NewDeductionName" placeholder="Federal Tax" required>

                        <label for="NewDeductionAmount">New Deduction Percentage:</label>
                        <input type="number" name="NewDeductionAmount" placeholder="Please enter a number: 15%"
                            required>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="deductionSubmitbtn">Save
                                changes</button>
                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Form To Delete Deduction -->
    <div class="modal fade" id="deleteDeductionForm" tabindex="-1" aria-labelledby="deleteDeductionLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteDeductionLabel">Delete Deduction</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="deductionb.php" method="POST">
                        <label for="DeleteDeductionID">Deduction ID to Delete:</label>
                        <input type="number" name="DeleteDeductionID" required>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="deductionDeleteBtn">Save
                                changes</button>
                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefit Forms -->
    <!-- Form To add New Benefit -->
    <div class="modal fade" id="addNewBenefit" tabindex="-1" aria-labelledby="addDeductionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addDeductionLabel">Add New Benefit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="deductionb.php" method="POST">
                        <label for="BenefitName">Benefit Name:</label>
                        <input type="text" name="BenefitName" required>

                        <label for="BenefitDescription">Benefit Description:</label>
                        <input type="text" name="BenefitDescription" required>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="benefitSubmit">Save changes</button>

                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Form To Edit Benefit -->
    <div class="modal fade" id="editBenefitForm" tabindex="-1" aria-labelledby="editBenefitLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editBenefitLabel">Edit Benefit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="deductionb.php" method="POST">
                        <label for="newBenefitID">Benefit ID to Edit:</label>
                        <input type="number" name="newBenefitID" required>

                        <label for="NewBenefitName">New Benefit Name:</label>
                        <input type="text" name="NewBenefitName" placeholder="Dental" required>

                        <label for="NewBenefitDescription">New Benefit Description:</label>
                        <input type="text" name="NewBenefitDescription" placeholder="Cover 80% of cost" required>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="benefitSubmitbtn">Save
                                changes</button>
                            <button type="button" class="btn btn-secondary btn-danger"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Form To Delete Benefit -->
    <div class="modal fade" id="deleteBenefitForm" tabindex="-1" aria-labelledby="deleteBenefitLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteBenefitLabel">Delete Benefit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="deductionb.php" method="POST">
                        <label for="DeleteBenefitID">Benefit ID to Delete:</label>
                        <input type="number" name="DeleteBenefitID" required>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="BenefitDeleteBtn">Save
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
    function editDeduction(buttonElement) {
        // Get department ID 
        var deductionIdEdit = buttonElement.getAttribute('data-deduction-id');

        // Set the department ID in the form
        document.querySelector('#editDeductionForm input[name="newDeductionID"]').value = deductionIdEdit;
    }

    function deleteDeduction(buttonElement) {
        // Get department ID 
        var deductionIdDel = buttonElement.getAttribute('data-dedudel-id');

        // Set the department ID in the form
        document.querySelector('#deleteDeductionForm input[name="DeleteDeductionID"]').value = deductionIdDel;
    }


    // Benefit form
    function editBenefit(buttonElement) {
        // Get Benefit ID 
        var benefitIdEdit = buttonElement.getAttribute('data-benefit-id');

        // Set the Benefit ID in the form
        document.querySelector('#editBenefitForm input[name="newBenefitID"]').value = benefitIdEdit;
    }

    function deleteBenefit(buttonElement) {
        // Get Benefit ID 
        var benefitIdDel = buttonElement.getAttribute('data-benefitdel-id');

        // Set the Benefit ID in the form
        document.querySelector('#deleteBenefitForm input[name="DeleteBenefitID"]').value = benefitIdDel;
    }
    </script>

</body>


</html>