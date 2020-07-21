<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>
 
<h3>User Stats</h3>
Name: <?php echo $name; ?><br>
Username: <?php echo $username; ?><br>
ID: <?php echo $userID; ?>
<table class = "table table-hover table-info">
    <tr>
        <th>Preference List</th>
        <th>Deployed Pages</th>
        <th>Liked Pages</th>
        <th>Disliked Pages</th>
    </tr>
        <tr>
            <td>
                <?php if (isset($preferenceList)) : ?>
                    <ul>
                    <?php foreach ($preferenceList as $key => $value) : ?>
                        <li>
                            <?php if ($value == 2) : ?>
                                <?php echo $key; ?> (<?php echo "Preferred"; ?>)
                            <?php elseif ($value == 0) : ?>
                                <?php echo $key; ?> (<?php echo "Not Preferred"; ?>)
                            <?php elseif ($value == 1) : ?>
                                <?php echo $key; ?> (<?php echo "May Try"; ?>)
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    This user has not selected any preference yet.
                <?php endif; ?>
            </td>
            <td>
            <?php if (isset($deployedList)) : ?>
                    <ul>
                    <?php foreach ($deployedList as $row) : ?>
                        <li><?php echo $row; ?></li>
                    <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    This user has not deployed anything yet.
                <?php endif; ?>
            </td> 
            <td>
            <?php if (isset($likedList)) : ?>
                    <ul>
                    <?php foreach ($likedList as $row) : ?>
                        <li><?php echo $row; ?></li>
                    <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    This user has not liked anything yet.
                <?php endif; ?>    
            </td>
            <td>
            <?php if (isset($dislikedList)) : ?>
                    <ul>
                    <?php foreach ($dislikedList as $row) : ?>
                        <li><?php echo $row; ?></li>
                    <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    This user has not disliked anything yet.
                <?php endif; ?>    
            </td>
        </tr>
</table>