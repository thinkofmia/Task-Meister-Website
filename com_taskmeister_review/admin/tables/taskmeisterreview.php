<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_taskmeister_review
 * 
 * @copyright   (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 // No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Taskmeister Review Table class
 * 
 * @since   0.0.1
 */
class TaskmeisterReviewTableTaskmeisterReview extends JTable
{
    /**
     * Constructor
     * 
     * @param   JDatabaseDriver &$db    A database connector object
     */
    function __construct(&$db)
    {
        parent::__construct('taskmeister_reviews', 'id', $db);
    }
}