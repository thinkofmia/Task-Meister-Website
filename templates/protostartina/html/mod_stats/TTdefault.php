<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_stats
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php foreach ($list as $item) : ?>
  <strong><?php echo $item->title;?>:</strong>&nbsp;<?php echo $item->data;?>&nbsp;&nbsp;&nbsp;
<?php endforeach; ?>
