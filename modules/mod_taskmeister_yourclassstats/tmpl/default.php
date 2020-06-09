<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>
 
<h3>Your Class Stats</h3>
<table class='yourClassStatsTable'>
    <tr>
        <th>Teacher</th>
        <th>Students</th>
        <th>Analysis</th>
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
    </tr>
</table>