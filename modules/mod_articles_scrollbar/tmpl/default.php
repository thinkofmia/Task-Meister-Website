<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_latest
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<ul class="scrollbarnews<?php echo $moduleclass_sfx; ?> mod-list">
<div id="scrollbar" style="display:inline-flex; overflow-x: scroll; height:350px; width:100%">
<?php foreach ($list as $item) : ?>
<a href="<?php echo $item->link; ?>" itemprop="url">
    <div id="item" style="padding-right:20px; width:400px; height:300px; text-align:center">
		<span itemprop="name">
            <img src="<?php echo json_decode($item->images)->image_intro; ?>" width="100%" height="100%" /><br>
            <?php echo $item->title; ?> - description
		</span>
    </div>
</a>
<?php endforeach; ?>
</div>
</ul>
