<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<?php if ($displayHeader) : ?>
    <h3><?php echo $displayHeader; ?></h3>
<?php else : ?>
    <h3>Article Stats</h3> <!--Header for the tables-->
<?php endif; ?>
<?php echo $displayText; ?>

Article ID: <?php echo $articleID; ?><br>
Article Category ID: <?php echo $articleCategory; ?><br>
<!--Display Table-->
<table class="table table-hover bgAlt">
    <tr>
        <th scope="col">All Users' Preferences</th>
        <td>
            <ul>
                <?php foreach ($preferenceList as $key => $value) : ?>
                    <li><?php echo modArticleStats::getName(intval($key));?>: <?php echo $value; ?></li>
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
    <tr>
        <th scope="col">Who has deployed</th>
        <td>
            <ul>
                <?php foreach ($deploymentList as $row) : ?>
                    <li><?php echo modArticleStats::getName(intval($row));?></li>
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
    <tr>
        <th scope="col">Total # of Likes</th>
        <td><?php echo $NoOfLikes; ?></td>
    </tr>
    <tr>
        <th scope="col" >Total # of Dislikes</th>
        <td><?php echo $NoOfDislikes; ?></td>
    </tr>
    <tr>
        <th scope="col">Total # of Deployment</th>
        <td><?php echo $NoOfDeployment; ?></td>
    </tr>
    <tr>
        <th scope="col">Tags</th>
        <td>
            <ul>
                <?php foreach ($tags as $row) : ?>
                    <li><?php echo $row; ?></li>
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
</table>