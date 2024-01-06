<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="signup.css">
    <link rel="stylesheet" type="text/css" href="dropdown.css">
    <link rel="stylesheet" type="text/css" href="employee_form.css">

    <title>Sign Up</title>
</head>

<body>

    <?php
    session_start();
    include 'sqlconnection.php';
    ?>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $employeeid = intval($_POST["employeeid"]);
        $UserName = mysqli_real_escape_string($con, $_POST["UserName"]);
        $Password = mysqli_real_escape_string($con, $_POST["Password"]);
        $Email = mysqli_real_escape_string($con, $_POST["Email"]);
        $Phone = mysqli_real_escape_string($con, $_POST["Phone"]);
        $Address = mysqli_real_escape_string($con, $_POST["Address"]);
        $City = mysqli_real_escape_string($con, $_POST["City"]);
        $State = mysqli_real_escape_string($con, $_POST["State"]);
        $ZipCode = mysqli_real_escape_string($con, $_POST["ZipCode"]);

        $check_emp_exist = "SELECT EmployeeID FROM Employees WHERE EmployeeID = $employeeid";

        $emp_exist_result = mysqli_query($con, $check_emp_exist);

        if ($emp_exist_result && mysqli_num_rows($emp_exist_result) > 0) {

            // check if user name exist
            $user_name_query = "SELECT UserName FROM UserLogin WHERE UserName = '$UserName'";
            $user_name_query_result = mysqli_query ($con, $user_name_query);

            // check if email exist
            $user_email_query = "SELECT Email FROM UserLogin WHERE Email = '$Email'";
                        $user_email_query_result = mysqli_query ($con, $user_email_query);

            // check if phone exist
            $user_phone_query = "SELECT Phone FROM UserLogin WHERE Phone = '$Phone'";
                $user_phone_query_result = mysqli_query ($con, $user_phone_query);

            if ($user_name_query_result && mysqli_num_rows($user_name_query_result) > 0) {

                echo "<div class='center'>
                <h2>UserName already exist, please login or use different username</h2>
                <h2>You can log in, by clicking here <a href='signin.php'> Sign In </a></h2>
                </div>";
                

            } elseif ($user_email_query_result && mysqli_num_rows($user_email_query_result) > 0) {
                echo "<div class='center'>
                <h2>Email already exist, please use different email or login</h2>
                <h2>You can log in, by clicking here <a href='signin.php'> Sign In </a></h2>
                </div>";
               

            } elseif ($user_phone_query_result && mysqli_num_rows($user_phone_query_result) > 0) {
               echo "<div class='center'>
               <h2>Phone already exist, please use different number or login</h2>
               <h2>You can log in, by clicking here <a href='signin.php'> Sign In </a></h2>
               </div>";
               

            } else {
                $insert_query = "INSERT INTO UserLogin (EmployeeID, Email, Phone, UserName, Password, LastLogin)
                VALUES ($employeeid, '$Email', '$Phone','$UserName','$Password', NOW())";
                $insert_result = mysqli_query($con, $insert_query);

                $insert_address_query = "INSERT INTO Addresses
                (EmployeeID, StreetAddress, City, State, ZipCode)
                VALUES
                    ($employeeid, '$Address', '$City','$State','$ZipCode')";
                $insert_address = mysqli_query($con, $insert_address_query);
                if ($insert_result && $insert_address) {
                    echo "<div class='center'>
                        <h2>Employee successfully Registered</h2>
                        <h2>You can now log in, by clicking here <a href='signin.php'> Sign In </a></h2>
                        </div>";
                    
                    
                } else {
                    echo "<div class='center'><h2>Employee ID does not exist, please contact your HR</h2><br>Error: " . mysqli_error($con) . "</div>";
                }
            }
        } else {
                echo "<div class='center'><h2>Employee ID does not exist, please contact your HR</h2></div>";
    }
    }
    ?>


    <video id="video-background" autoplay muted loop>
        <source src="https://swe.umbc.edu/~nsubba1/prototypev7/PayrollSystem/images/page.MP4" type="video/mp4">
    </video>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form action="signup.php" method="post">

            <label for="employeeid">Employee ID:</label>
            <input type="number" name="employeeid" placeholder="1" required>

            <label for="UserName">Username:</label>
            <input type="text" name="UserName" placeholder="johndoe101" required>

            <label for="Password">Password:</label>
            <input type="password" name="Password" required>

            <label for="Email">Email:</label>
            <input type="email" name="Email" placeholder="johndoe101@gmail.com" required>

            <label for="Phone">Phone:</label>
            <input type="text" name="Phone" placeholder="4434205000" pattern="\d{10}"
                title="Phone number should be 10 digits (EX:4434205000)" maxlength="10" required>

            <label for="Address">Address:</label>
            <input type="text" name="Address" placeholder="2000 Main Street" required>

            <label for="City">City:</label>
            <input type="text" name="City" placeholder="Baltimore" required>


            <label for="State">State:</label>
            <!-- <input type="text" name="State" placeholder="MD" required> -->
            <select name="State" placeholder="Maryland" required>
                <option>Please select one</option>
                <option value="AL">Alabama</option>
                <option value="AK">Alaska</option>
                <option value="AZ">Arizona</option>
                <option value="AR">Arkansas</option>
                <option value="CA">California</option>
                <option value="CO">Colorado</option>
                <option value="CT">Connecticut</option>
                <option value="DE">Delaware</option>
                <option value="DC">District Of Columbia</option>
                <option value="FL">Florida</option>
                <option value="GA">Georgia</option>
                <option value="HI">Hawaii</option>
                <option value="ID">Idaho</option>
                <option value="IL">Illinois</option>
                <option value="IN">Indiana</option>
                <option value="IA">Iowa</option>
                <option value="KS">Kansas</option>
                <option value="KY">Kentucky</option>
                <option value="LA">Louisiana</option>
                <option value="ME">Maine</option>
                <option value="MD">Maryland</option>
                <option value="MA">Massachusetts</option>
                <option value="MI">Michigan</option>
                <option value="MN">Minnesota</option>
                <option value="MS">Mississippi</option>
                <option value="MO">Missouri</option>
                <option value="MT">Montana</option>
                <option value="NE">Nebraska</option>
                <option value="NV">Nevada</option>
                <option value="NH">New Hampshire</option>
                <option value="NJ">New Jersey</option>
                <option value="NM">New Mexico</option>
                <option value="NY">New York</option>
                <option value="NC">North Carolina</option>
                <option value="ND">North Dakota</option>
                <option value="OH">Ohio</option>
                <option value="OK">Oklahoma</option>
                <option value="OR">Oregon</option>
                <option value="PA">Pennsylvania</option>
                <option value="RI">Rhode Island</option>
                <option value="SC">South Carolina</option>
                <option value="SD">South Dakota</option>
                <option value="TN">Tennessee</option>
                <option value="TX">Texas</option>
                <option value="UT">Utah</option>
                <option value="VT">Vermont</option>
                <option value="VA">Virginia</option>
                <option value="WA">Washington</option>
                <option value="WV">West Virginia</option>
                <option value="WI">Wisconsin</option>
                <option value="WY">Wyoming</option>
            </select>

            <label for="ZipCode">ZipCode:</label>
            <input type="text" name="ZipCode" placeholder="21237" pattern="\d{5}" title="ZipCode should be 5 digits"
                maxlength="5" required>


            <button type="submit" class="cool-button animated-button" name="signupbtn">Sign Up</button>
        </form>
        <div>
            <p>Already have an account? <a href="signin.php"> Sign In </a>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>

</html>