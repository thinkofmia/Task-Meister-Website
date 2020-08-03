<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<!--Header for the tables-->
Recent Recommendations 
<!--Table-->
<table class="yellowtable">
<tr>
    <!--Headers-->
    <th>Date</th>
    <th>Action</th>
</tr>
<!--Loop for each action in the $results-->
<?php foreach ($results as $row) : ?>
    <!--If yet to exceed the max number of actions to display-->
    <?php if ($counter<$size) : ?>
    <?php /*
    Display rows based on main database result
        $row['date'] refers to the record date
        $row['es_uid'] refers to the User ID
        $row['es_aid'] refers to the article ID
        $row['es_action'] refers to the action done
    */
    $user = JFactory::getUser(intval($row['es_uid']));//Gets user variable
    $username = $user->name;//Gets user name
    $article =& JTable::getInstance("content");//Sets content variable
    $article->load(intval($row['es_aid']));//Gets article by their id
    $articleTitle= $article->get("title");//Get article title
    ?>
    <tr>
        <!--Display the date of action-->
        <td><?php echo $row['es_date']; ?></td>
        <!--Display the action-->
        <td><?php echo "User ".$username." ".$row['es_action']. " article " .$articleTitle;?></td>
    </tr>
    <?php $counter = $counter + 1; //Increment counter by 1 ?>
    <?php endif; ?>
<?php endforeach; ?>
</table>