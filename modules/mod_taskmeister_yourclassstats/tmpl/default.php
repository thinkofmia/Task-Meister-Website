<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<!--JavaScript codes for the bar charts and pie charts-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 

<!--Display custom header and texts-->
<h3><?php echo $displayHeader; ?></h3>
<?php echo $displayText; ?>

<!--If the display mode is bar graph-->
<?php if ($displayMode=="choice_bar") : ?>
    <div class="chart-container" style="position: relative; display:inline-flex;">
        <!--Bar Chart for Preferred Tags-->
        <div>
            <canvas id="barChart-preferred" width="400" height="400"></canvas>
        </div>
        <!--Bar Chart For Disliked Tags-->
        <div>
        <canvas id="barChart-notpreferred" width="400" height="400"></canvas>
        </div>
    </div>

    <script>
    //Set Chart Size Responsively
    var chart = document.getElementById('barChart-preferred');
    chart.parentNode.style.width = '45vw';//Sets the width of the bar chart
    //Chart code
    var ctx = document.getElementById('barChart-preferred').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: { 
            labels: ['<?php echo implode("', '",array_keys($likePreferencesScore)); ?>'],//Loop the keys of the dictionary as labels
            datasets: [{
                label: 'Recommended Preferences Score',
                data: [<?php echo implode(', ',$likePreferencesScore); ?>],//Loop the values of the dictionaries as the data
                backgroundColor: [//Randomize the colors based on number of tags
                    <?php foreach ($likePreferencesScore as $key => $value) : ?>
                        "<?php echo 'rgba('.rand(50, 255).', '.rand(50, 255).', '.rand(50, 255).', 0.7) '; ?>",
                    <?php endforeach; ?>
                    "<?php echo 'rgba('.rand(50, 255).', '.rand(50, 255).', '.rand(50, 255).', 0.7) '; ?>"
                ],
                borderColor: [//Randomize the colors based on number of tags
                    <?php foreach ($likePreferencesScore as $key => $value) : ?>
                        "<?php echo 'rgba('.rand(50, 255).', '.rand(50, 255).', '.rand(50, 255).', 0.7)'; ?>",
                    <?php endforeach; ?>
                    "<?php echo 'rgba('.rand(50, 255).', '.rand(50, 255).', '.rand(50, 255).', 0.7) '; ?>"
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

    //Do the same for the against Chart
    var chart = document.getElementById('barChart-notpreferred');
    chart.parentNode.style.width = '45vw';//Sets the width of the bar chart
    //Display chart
    var ctx = document.getElementById('barChart-notpreferred').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['<?php echo implode("', '",array_keys($dislikedPreferencesScore)); ?>'],//Sets the labels to be the keys of the dictionary
            datasets: [{
                label: '# of Students who dislike',
                data: [<?php echo implode(', ',$dislikedPreferencesScore); ?>],//Sets the data to the value of the dictionary
                backgroundColor: [//Randomize the color based on number of tags
                    <?php foreach ($dislikedPreferencesScore as $key => $value) : ?>
                        "<?php echo 'rgba('.rand(50, 255).', '.rand(50, 255).', '.rand(50, 255).', 0.7) '; ?>",
                    <?php endforeach; ?>
                    "<?php echo 'rgba('.rand(50, 255).', '.rand(50, 255).', '.rand(50, 255).', 0.7) '; ?>"
                ],
                borderColor: [//Randomize the color based on number of tags
                    <?php foreach ($dislikedPreferencesScore as $key => $value) : ?>
                        "<?php echo 'rgba('.rand(50, 255).', '.rand(50, 255).', '.rand(50, 255).', 0.7)'; ?>",
                    <?php endforeach; ?>
                    "<?php echo 'rgba('.rand(50, 255).', '.rand(50, 255).', '.rand(50, 255).', 0.7) '; ?>"
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

<!--Else if display mode is pie chart-->
<?php elseif ($displayMode=="choice_pie") : ?>
    <!--Display for pie chart -->
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
    <?php foreach ($fullPreferencesScore as $key => $value) : ?>//Loop the the preference by their key and value
        ['<?php echo $key; ?>', <?php echo $value; ?>],//Sets data
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
    <?php foreach ($dislikedPreferencesScore as $key => $value) : ?>//Loop for each tag found
        ['<?php echo $key; ?>', <?php echo $value; ?>],//Display their data
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

<!--Else display a standard table for the class statistics-->
<?php else : ?>
<table class='yourClassStatsTable'>
    <tr>
        <!--Headers-->
        <th>Teacher</th>
        <th>Students</th>
        <th>Overall Preferences</th>
        <th>Disliked Preferences</th>
    </tr>
<!--Show the external teacher stats-->
    <!--Print out the data-->
    <tr>
        <!--Teacher Name-->
        <td><?php echo $teacherName; ?></td>
        <td>
        <!--If class exists-->
        <?php if ($studentsList) : ?>
            <ul>
            <!--Echo out all of the student's name in the list-->
            <?php foreach ($studentsList as $row) : ?>
                <?php 
                    $student = JFactory::getUser(intval($row));
                    $studentName = $student->name;
                ?>
                <li><?php echo $studentName;?></li>
            <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <!--Else if class doesn't exist, show default empty msg here-->
            No Students in your class.    
        <?php endif; ?>
        </td>
        <td>
        <!--If exists a preference score-->
        <?php if ($fullPreferencesScore) : ?>
            <ul>
            <?php foreach ($fullPreferencesScore as $key => $value) : ?>
                <!--Loop for each preference as their score-->
                <?php if (intval($value)>0) : ?>
                    <li><?php echo $key;?> - Total Students Score: <?php echo $value; ?></li>
                <?php endif ?>
            <?php endforeach ?>
            </ul>
        <?php else : ?>
            <!--Else display empty default msg-->
            No available data.    
        <?php endif ?>
        </td>
        <td>
        <!--If disliked preference scores exist-->
        <?php if ($dislikedPreferencesScore) : ?>
            <ul>
            <!--Loop for each disliked tag-->
            <?php foreach ($dislikedPreferencesScore as $key => $value) : ?>
                <!--If the value is more than 0-->
                <?php if (intval($value)>0) : ?>
                    <!--Echo out the disliked data for the tag-->
                    <li><?php echo $key;?> - Total Students: <?php echo $value; ?></li>
                <?php endif ?>
            <?php endforeach ?>
            </ul>
        <?php else : ?>
            <!--Else if doesn't exists, display empty default msg-->
            No available data.    
        <?php endif ?>
        </td>
    </tr>
</table>
<?php endif ?>