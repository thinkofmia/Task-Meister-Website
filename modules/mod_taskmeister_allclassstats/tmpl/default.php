<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>
 
<h3>All Classes Stats</h3>
<table id='allClassStatsTable'>
    <tr>
        <th>Teacher</th>
        <th>Students</th>
        <th>Analysis</th>
    </tr>
<!--Show the external teacher stats-->
<?php foreach ($results as $row): ?><!--For loop for each item in $results-->
    <!--Set Teacher-->
    <?php 
    $teacher = JFactory::getUser($row['es_teacherid']);
    $teacherName = $teacher->name;
    //Set the student list for each teacher
    $studentsList = json_decode($row['es_students']);
    ?>
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
        <?php if ($studentsList) : ?>
            <?php $fullPreferencesScore = array(); ?>
            <!--For each student in the list, get preferences-->    
            <?php foreach ($studentsList as $row){
                $query = $db->getQuery(true);
                $query->select($db->quoteName(array('es_userid','es_userpreference')))//Get user id, user preference
                    ->from($db->quoteName('#__customtables_table_userstats'))//From our external user stats table
                    ->where($db->quoteName('es_userid') . ' = ' . $row);//Where it is the current user's userid
                $db->setQuery($query);
                $results2 = $db->loadAssocList();//Save results as $results2
                foreach ($results2 as $row2){
                    $studentPreferences = json_decode($row2['es_userpreference']);
                    foreach ($studentPreferences as $key => $value){
                        if (isset($fullPreferencesScore[$key])) $fullPreferencesScore[$key] += $value;
                        else $fullPreferencesScore[$key] = $value;
                    }
                }
            }
            arsort($fullPreferencesScore); 
            ?>
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
    </tr>
    <?php endforeach ?>
</table>