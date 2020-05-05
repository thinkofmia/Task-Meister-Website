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
  <!--Displays Header Name if any-->
  <?php if ($params->get('headerName')) : ?>
  	<h3><?php echo $params->get('headerName'); ?></h3>
	<?php endif; ?>
<div class="articlesRow">
  <!-- Arrow button to scroll left-->
  <i class="arrowLeft" onclick="this.closest('div').querySelector('.recommendedArticles').scrollLeft -= 100;"></i>
  <!--Displays list of articles-->
  <div class="recommendedArticles" id= "recommendation">
    <?php foreach ($list as $item) : ?>
    <a href="<?php echo $item->link; ?>" itemprop="url">
        <div class="article">
          <img src="<?php echo json_decode($item->images)->image_intro; ?>" width="100%" height="100%" />
          <p><?php echo $item->title; ?></p>
        </div>
    </a>
    <?php endforeach; ?>
  </div>
  <!-- Arrow button to scroll right-->
  <i class="arrowRight" onclick="this.closest('div').querySelector('.recommendedArticles').scrollLeft += 100;"></i>
</div>
</ul>
