<?php 
// No direct access
defined('_JEXEC') or die; 
// Displays module output
?>
 
<!--Header for the all classes stats (Optional)--> 
<h3>All Classes Stats</h3>
<!--Set up table-->
<table id='allClassStatsTable'>
    <tr>
        <!--Headers: Teacher, Students, Analysis-->
        <th>Teacher</th>
        <th>Students</th>
        <th>Analysis</th>
    </tr>
<!--Loop for each teacher found in $results-->
<?php foreach ($results as $row): ?>
    <?php 
    $teacher = JFactory::getUser($row['es_teacherid']);//Get teacher by their id
    $teacherName = $teacher->name;//Get teacher's name
    //Gets the teacher's class, by converting the stringified list of students to an array
    $studentsList = json_decode($row['es_students']);
    ?>
    <!--Print out the data-->
    <tr>
        <td><?php echo $teacherName;//Put the name of the teacher here (column 1) ?></td>
        <td>
        <?php if ($studentsList) : // If class exists ?>
            <ul>
            <?php foreach ($studentsList as $row) : //Loop for each student in the class?>
                <?php 
                    $student = JFactory::getUser(intval($row));//Get student by their id
                    $studentName = $student->name;//Get student's name
                ?>
                <li><?php echo $studentName;//Place the students' name here (column 2) ?></li>
            <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <!--If no students in the teacher's class, display this default message-->
            No Students in your class. 
        <?php endif; ?>
        </td>
        <td>
        <!--If class exists-->
        <?php if ($studentsList) : ?>
            <?php $fullPreferencesScore = array(); ?>
            <!--For each student in the list, get preferences-->    
            <?php foreach ($studentsList as $row){//Loop for each student in the list
                //Query the database
                $query = $db->getQuery(true);
                $query->select($db->quoteName(array('es_userid','es_userpreference')))//Get user id, user preference
                    ->from($db->quoteName('#__customtables_table_userstats'))//From our external user stats table
                    ->where($db->quoteName('es_userid') . ' = ' . intval($row));//Where the userid is equal to the student id
                $db->setQuery($query);
                $results2 = $db->loadAssocList();//Save results as $results2
                foreach ($results2 as $row2){//Loop for the student found
                    $studentPreferences = json_decode($row2['es_userpreference']);//Get the array of preferences the students have
                    foreach ($studentPreferences as $key => $value){//Loop for each preference
                        if (isset($fullPreferencesScore[$key])) $fullPreferencesScore[$key] += $value;//If preference score already exists, increment the scores of the preference
                        else $fullPreferencesScore[$key] = $value;//If doesnt exists, then create it inside the score board
                    }
                }
            }
            //Sort the total preferences score in descending order
            arsort($fullPreferencesScore); 
            ?>
            <ul>
            <!--Loop for each preferences with their scores-->
            <?php foreach ($fullPreferencesScore as $key => $value) : ?>
                <?php if (intval($value)>0) : ?>
                    <!--//If the score is bigger than 0, display: -->
                    <li><?php echo $key;?> - Total Students Score: <?php echo $value; //Echo the results of the tags ?></li>
                <?php endif ?>
            <?php endforeach ?>
            </ul>
        <?php else : ?>
            <!--Else echo default message that no data is found-->
            No available data.    
        <?php endif ?>
        </td>
    </tr>
    <?php endforeach ?>
</table>