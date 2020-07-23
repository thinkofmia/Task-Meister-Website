<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<div class="registration-complete<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	<?php endif; ?>
	<div class="alert alert-warning" role="alert">
		Awesome mate! You have successfully registered with us.<br>
		Now we would like to invite you to activate your account with the email you have provided to us. <br>
		We can't wait to have you on our team.
	</div>
</div>
