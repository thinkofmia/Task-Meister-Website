<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<!--Header-->
<h3>User Stats</h3>
<!--Name/Username/ID info-->
Name: <?php echo $name; ?><br>
Username: <?php echo $username; ?><br>
ID: <?php echo $userID; ?>
<!--User stats Table-->
<table class = "table table-hover table-info">
    <tr>
        <!--Headers-->
        <th>Preference List</th>
        <th>Deployed Pages</th>
        <th>Liked Pages</th>
        <th>Disliked Pages</th>
    </tr>
        <tr>
            <!--Show data if exists-->
            <td>
                <!--If there exists a preference list-->
                <?php if (isset($preferenceList)) : ?>
                    <ul>
                    <!--Loop for each tag in the list-->
                    <?php foreach ($preferenceList as $key => $value) : ?>
                        <li>
                            <!--If preferred, show tag as preferred-->
                            <?php if ($value == 2) : ?>
                                <?php echo $key; ?> (<?php echo "Preferred"; ?>)
                            <!--If against, show tag as against-->
                            <?php elseif ($value == 0) : ?>
                                <?php echo $key; ?> (<?php echo "Against"; ?>)
                            <!--If may try, show tag as may try-->
                            <?php elseif ($value == 1) : ?>
                                <?php echo $key; ?> (<?php echo "May Try"; ?>)
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <!--Else if no preferences found, show default empty msg-->
                    This user has not selected any preference yet.
                <?php endif; ?>
            </td>
            <td>
            <?php if (isset($deployedList)) : ?>
            <!--If deployment list exists-->
                    <ul>
                    <!--Loop for each deployed page in the list-->
                    <?php foreach ($deployedList as $row) : ?>
                        <li><?php echo $row; ?></li>
                    <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <!--Else just display empty default message-->
                    This user has not deployed anything yet.
                <?php endif; ?>
            </td> 
            <td>
            <!--If liked list exists-->
            <?php if (isset($likedList)) : ?>
                    <ul>
                    <!--Loop for all liked pages in the list-->
                    <?php foreach ($likedList as $row) : ?>
                        <li><?php echo $row; ?></li>
                    <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <!--Else display empty default message-->
                    This user has not liked anything yet.
                <?php endif; ?>    
            </td>
            <td>
            <!--If there exists a disliked list-->
            <?php if (isset($dislikedList)) : ?>
                    <ul>
                    <!--Loop for each disliked page in the list-->
                    <?php foreach ($dislikedList as $row) : ?>
                        <li><?php echo $row; ?></li>
                    <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <!--Else display default empty message-->
                    This user has not disliked anything yet.
                <?php endif; ?>    
            </td>
        </tr>
</table>