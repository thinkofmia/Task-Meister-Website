<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

Recent Recommendations <!--Header for the tables-->
<table class="yellowtable">
<tr>
    <th>Date</th>
    <th>Action</th>
</tr>
<?php foreach ($results as $row) : ?>
    <?php if ($counter<$size) : ?>
    <?php /*
    Display rows based on main database result
        $row['date'] refers to the record date
        $row['es_uid'] refers to the User ID
        $row['es_aid'] refers to the article ID
        $row['es_action'] refers to the action done
    */
    $user = JFactory::getUser(intval($row['es_uid']));
    $username = $user->name;
    $article =& JTable::getInstance("content");
    $article->load(intval($row['es_aid']));
    $articleTitle= $article->get("title");
    ?>
    <tr>
        <td><?php echo $row['es_date']; ?></td>
        <td><?php echo "User ".$username." ".$row['es_action']. " article " .$articleTitle;?></td>
    </tr>
    <?php $counter = $counter + 1; ?>
    <?php endif; ?>
<?php endforeach; ?>
</table>