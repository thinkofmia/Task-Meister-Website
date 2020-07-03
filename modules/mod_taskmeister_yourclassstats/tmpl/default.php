<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 

<h3>Your Class Stats</h3>

<!--Code for Bar Chart-->
<div class="chart-container" style="position: relative; width:80vw">
    <canvas id="barChart" width="400" height="400"></canvas>
</div>
<script>
var ctx = document.getElementById('barChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>

<!--Pie Chart codes -->
<div class = "charts">
    <div id="piechart"></div>
    <div id="dislikedchart"></div>
</div>
<script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
google.charts.setOnLoadCallback(drawDislikedChart);

// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Preference', 'Score'],
  <?php foreach ($fullPreferencesScore as $key => $value) : ?>
    ['<?php echo $key; ?>', <?php echo $value; ?>],
  <?php endforeach?>
    ['Others', 0]
]);

  // Optional; add a title and set the width and height of the chart
  var options = {'title':'Your Class Recommended Preferences', is3D: true, 'width': '45vw', 'height':'45vw' };

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}

// Draw the chart and set the chart values
function drawDislikedChart() {
  var data = google.visualization.arrayToDataTable([
  ['Preference', 'Total Students'],
  <?php foreach ($dislikedPreferencesScore as $key => $value) : ?>
    ['<?php echo $key; ?>', <?php echo $value; ?>],
  <?php endforeach?>
    ['Others', 0]
]);

  // Optional; add a title and set the width and height of the chart
  var options = {'title':'Your Class Disliked Preferences',
                pieHole: 0.2,
                'width': '20vw', 'height': '20vh'};

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('dislikedchart'));
  chart.draw(data, options);
}
</script>

<?php if ($showTable) : ?>
<table class='yourClassStatsTable'>
    <tr>
        <th>Teacher</th>
        <th>Students</th>
        <th>Overall Preferences</th>
        <th>Disliked Preferences</th>
    </tr>
<!--Show the external teacher stats-->
    <!--Print out the data-->
    <tr>
        <td><?php echo $teacherName; ?></td>
        <td>
        <?php if ($studentsList) : ?>
            <ul>
            <?php foreach ($studentsList as $row) : ?>
                <?php 
                    $student = JFactory::getUser(intval($row));
                    $studentName = $student->name;
                ?>
                <li><?php echo $studentName;?></li>
            <?php endforeach; ?>
            </ul>
        <?php else : ?>
            No Students in your class.    
        <?php endif; ?>
        </td>
        <td>
        <?php if ($fullPreferencesScore) : ?>
            <!--For each student in the list, get preferences-->    
            <ul>
            <?php foreach ($fullPreferencesScore as $key => $value) : ?>
                <?php if (intval($value)>0) : ?>
                    <li><?php echo $key;?> - Total Students Score: <?php echo $value; ?></li>
                <?php endif ?>
            <?php endforeach ?>
            </ul>
        <?php else : ?>
            No available data.    
        <?php endif ?>
        </td>
        <td>
        <?php if ($dislikedPreferencesScore) : ?>
            <!--For each student in the list, get preferences-->    
            <ul>
            <?php foreach ($dislikedPreferencesScore as $key => $value) : ?>
                <?php if (intval($value)>0) : ?>
                    <li><?php echo $key;?> - Total Students: <?php echo $value; ?></li>
                <?php endif ?>
            <?php endforeach ?>
            </ul>
        <?php else : ?>
            No available data.    
        <?php endif ?>
        </td>
    </tr>
</table>
<?php endif ?>