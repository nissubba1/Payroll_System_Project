<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Home Page</title>
    <link rel="stylesheet" type="text/css" href="home.css">
</head>

<body>

    <?php
    include 'cnavbar.php';
    include("sqlconnection.php");
    ?>
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
                  <p class="animated-box">Bi-weekly payment</p>
                  <p class="animated-box">Contect your HR department for more resources</p>
             </div>
             <div class="info-box upcoming-payday">
                 <h2>Upcoming Payday</h2>
                 <p class="animated-box">Next payday: 12/21/2023</p>
             </div>
             <div class="info-box payment-account">
                 <h2>Need to do</h2>
                 <p class="animated-box">None</p>
             </div>
         </div>
     </div>
     <?php
 // Fetch data from the Payroll table
 $payrollDataQuery = "SELECT PeriodEndDate, NetIncome FROM Payroll ORDER BY PeriodEndDate";
 $payrollDataResult = mysqli_query($con, $payrollDataQuery);

 // Initialize arrays to store labels and values for the graph
 $labels = [];
 $values = [];

 // Process the data
 if ($payrollDataResult && mysqli_num_rows($payrollDataResult) > 0) {
     while ($row = mysqli_fetch_assoc($payrollDataResult)) {
         // Format dates or use as is based on your needs
         $labels[] = date('M j, Y', strtotime($row['PeriodEndDate']));
         $values[] = floatval($row['NetIncome']);
     }
 }

 // Create a PHP associative array for the graph data
 $graphData = [
     'labels' => $labels,
     'values' => $values,
 ];

 // Convert PHP array to JSON format
 $graphDataJSON = json_encode($graphData);
 ?>

 <script>
 // Parse JSON data into JavaScript variables
 var graphData = <?php echo $graphDataJSON; ?>;

 // Render chart using Chart.js
 var ctx = document.getElementById('incomeChart').getContext('2d');
 var myChart = new Chart(ctx, {
     type: 'bar',
     data: {
         labels: graphData.labels,
         datasets: [{
             label: 'Net Income',
             data: graphData.values,
             backgroundColor: 'rgba(75, 192, 192, 0.2)',
             borderColor: 'rgba(75, 192, 192, 1)',
             borderWidth: 1
         }]
     },
     options: {
         scales: {
             y: {
                 beginAtZero: true
             }
         }
     }
 });
 </script>
</body>
</html>
