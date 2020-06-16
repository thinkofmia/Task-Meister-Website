<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_review
 * 
 * @copyright   (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 // No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * General Controller of Review component
 * 
 * @package     Joomla.Administrator
 * @subpackage  com_review
 * @since       0.0.1
 */
class ReviewController extends JControllerLegacy
{
    /**
     * The default view for the display method
     * 
     * @var string
     * @since 12.2
     */
    protected $default_view = 'reviews';
}