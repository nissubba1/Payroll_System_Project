<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="home.css">

    <link rel="stylesheet" type="text/css" href="dashboardadmin.css">
    <link rel="stylesheet" type="text/css" href="employee_form.css">

    <link rel="stylesheet" type="text/css" href="deductionb.css">
</head>

<body>
    <?php include 'navbar.php';
    include 'sqlconnection.php';

    ?>
    <div class="container card-container">
        <div class="card text-bg-primary mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
                <h5 class="card-title">Primary card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                    card's content.</p>
            </div>
        </div>
        <div class="card text-bg-primary mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
                <h5 class="card-title">Primary card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                    card's content.</p>
            </div>
        </div>
        <div class="card text-bg-primary mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
                <h5 class="card-title">Primary card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                    card's content.</p>
            </div>
        </div>

        <div class="card text-bg-primary mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
                <h5 class="card-title">Primary card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                    card's content.</p>
            </div>
        </div>
        <div class="card text-bg-primary mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
                <h5 class="card-title">Primary card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                    card's content.</p>
            </div>
        </div>
        <div class="card text-bg-primary mb-3" style="max-width: 18rem;">
            <div class="card-header">Benefits</div>
            <div class="card-body container">
                <h5 class="card-title">Primary card title</h5>

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
            </div>
        </div>

    </div>

    <div class="container">
        <div class="graph-container">
            <h2>Income Overview</h2>
            <div class="graph">
                <canvas id="incomeChart"></canvas>
            </div>
        </div>

        <div class="info-container">
            <div class="info-box announcements">
                <h2>Announcements</h2>
                <ul>
                    <li class="animated-box">Announcement 1</li>
                    <li class="animated-box">Announcement 2</li>
                </ul>
            </div>

            <div class="info-box upcoming-payday">
                <h2>Upcoming Payday</h2>
                <p class="animated-box">Next payday: 01/15/2023</p>
            </div>

            <div class="info-box payment-account">
                <h2>Payment Account</h2>
                <p class="animated-box">Account balance: $5,000.00</p>
            </div>
        </div>
    </div>

</body>

</html>