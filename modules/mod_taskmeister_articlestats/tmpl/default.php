<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<!--Display Additional Texts if any-->
<?php if ($displayHeader) : ?><!--If custom header name is set-->
    <h3><?php echo $displayHeader; ?></h3><!--Display it-->
<?php else : ?>
    <h3>Article Stats</h3> <!--Else show default header: Article Stats-->
<?php endif; ?>
<?php echo $displayText; ?><!--Display custom texts if any -->

Article ID: <?php echo $articleID; ?><br><!--Display article id-->
Article Category ID: <?php echo $articleCategory; ?><br><!--Display article's category id-->
<!--Display Table-->
<table class="table table-hover bgAlt"><!--bgAlt is used to allow the table to fit the current theme of the taskmeister template-->
    <tr>
        <th scope="col">Opinions</th><!--Header for all users' opinions of the article-->
        <td>
            <ul>
                <!--Loop for each preference in the list-->
                <?php foreach ($preferenceList as $key => $value) : ?>
                    <!--Display the user (key) followed by their opinon (value) of the article-->
                    <li><?php echo modArticleStats::getName(intval($key));?>: <?php echo $value; ?></li>
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
    <tr>
        <th scope="col">Deployed Users</th><!--Header for the list of users that deployed the article-->
        <td>
            <ul>
                <!--Echo for each user found-->
                <?php foreach ($deploymentList as $row) : ?>
                    <li><?php echo modArticleStats::getName(intval($row));?></li>
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
    <tr>
        <!--Echo the total number of likes of the article-->
        <th scope="col">Total # of Likes</th>
        <td><?php echo $NoOfLikes; ?></td>
    </tr>
    <tr>
        <!--Echo the total number of dislikes of the article-->
        <th scope="col" >Total # of Dislikes</th>
        <td><?php echo $NoOfDislikes; ?></td>
    </tr>
    <tr>
        <!--Echo the total number of deployment of the article-->
        <th scope="col">Total # of Deployment</th>
        <td><?php echo $NoOfDeployment; ?></td>
    </tr>
    <tr>
        <!--Header for the article's tags-->
        <th scope="col">Tags</th>
        <td>
            <ul>
                <!--Loop for each tag found in the article-->
                <?php foreach ($tags as $row) : ?>
                    <li><?php echo $row; ?></li>
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
</table>