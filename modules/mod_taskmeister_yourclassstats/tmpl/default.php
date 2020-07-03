<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 

<h3>Your Class Stats</h3>

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