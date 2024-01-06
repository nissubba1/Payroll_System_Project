<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="signin.css">
    <link rel="stylesheet" type="text/css" href="dropdown.css">
    <title>Sign In</title>
</head>

<body>

    <?php
  session_start();
  	include("sqlconnection.php");
    $login_success = false;
  $welcome_message = '';
  $check_access = 0;
  ?>
    <?php
  if($_SERVER['REQUEST_METHOD'] == "POST")
  	{
  		//something was posted
  		$UserName = mysqli_real_escape_string($con,$_POST['UserName']);
  		$Password = mysqli_real_escape_string($con,$_POST['Password']);

  		if(!empty($UserName) && !empty($Password) && !is_numeric($UserName))
  		{
  			//read from database
  			$sql = "SELECT UserName FROM UserLogin WHERE UserName = '$UserName' AND Password = '$Password'";
  			$result = mysqli_query($con, $sql);

  			if($result && mysqli_num_rows($result) > 0)
  			{
                // Get User Name
                $getName = "SELECT FirstName, Role FROM Employees JOIN UserLogin ON Employees.EmployeeID = UserLogin.EmployeeID WHERE UserLogin.UserName = '$UserName' AND UserLogin.Password = '$Password'";

                $get_name_result = mysqli_query($con, $getName);

                $get_EmpID = "SELECT Employees.EmployeeID FROM Employees JOIN UserLogin ON Employees.EmployeeID = UserLogin.EmployeeID WHERE UserLogin.UserName = '$UserName'";
                $get_empid_result = mysqli_query($con, $get_EmpID);

                $row = mysqli_fetch_assoc($get_empid_result);
                // $emp_id_user = $row['EmployeeID'];
                $employeeIdSession = $row["EmployeeID"]; // Fetch the employee ID

                // Store the employee ID in session
                $_SESSION['employee_id'] = $employeeIdSession;

                while ($row = mysqli_fetch_assoc($get_name_result)) {

                    $title = "";
                    if ($row["Role"] == 0) {
                        $title = "Admin";
                        $check_access = 0;
                    } else {
                        $title = "Client";
                        $check_access = 1;
                    }
                        $welcome_message = "<div class='center'><h2>Welcome " . $title . ", " . $row["FirstName"] ."</h2></div>";
                        $login_success = true;

                }

  			} else {
                echo "<div class='center'><h2>Invalid Username or Password, try again</h2></div>";
            }
  		}else
  		{
            echo "<div class='center'><h2>Please enter all data</h2></div>";
  		}
  	}
  ?>
    <?php
if ($login_success) {
    echo $welcome_message;

    $redirectUrl = 'clienthome.php';
    if ($check_access === 0) {
        $redirectUrl = 'employee.php';
    }
    echo "<script>
      setTimeout(function() {
        window.location.href = '$redirectUrl';
      }, 500);
    </script>";
}
?>
    <video id="video-background" autoplay muted loop>
        <source src="https://swe.umbc.edu/~nsubba1/prototypev7/PayrollSystem/images/page.MP4" type="video/mp4">
    </video>

    <div class="signin-container">
        <h2>Sign In</h2>
        <form action="signin.php" method="post">
            <label for="UserName">Username:</label>
            <input type="text" name="UserName" required>

            <label for="Password">Password:</label>
            <input type="password" name="Password" required>

            <button type="submit" class="cool-button animated-button" name="signin">Log In</button>
        </form>
        <p>Don't have account, register now? <a href="signup.php"> Sign Up </a>
        <h2>Demo:</h2>
        <p>Admin: Username: jones105, Password: Jones@105</p>
        <p>Client: Username: thomas107, Password: Thomas@107 </p>
    </div>
    <script src="scripts.js"></script>
</body>

</html>